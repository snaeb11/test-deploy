<?php

namespace App\Http\Controllers;

use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BackupController extends Controller
{
    // backup only
    public function download(): StreamedResponse
    {
        $fileName = 'backup_'.now()->format('Y-m-d_H-i-s').'.sql';

        // Log backup creation (streamed)
        if (auth()->check()) {
            try {
                UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_BACKUP_CREATED, null, null, [
                    'filename' => $fileName,
                    'mode' => 'stream',
                    'driver' => 'sqlite',
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Failed to log backup creation: '.$e->getMessage());
            }
        }

        return response()->streamDownload(function () {
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            $tables = array_map(fn ($table) => $table->name, $tables);

            $output = fopen('php://output', 'w');

            // Safer transaction-wrapped dump
            fwrite($output, "PRAGMA foreign_keys = OFF;\n");
            fwrite($output, "BEGIN TRANSACTION;\n");
            foreach ($tables as $table) {
                $this->dumpTable($table, $output);
            }
            fwrite($output, "COMMIT;\n");
            fwrite($output, "PRAGMA foreign_keys = ON;\n");

            fclose($output);
        }, $fileName);
    }

    // Removed listBackups; upload-only restore

    // restore from uploaded file
    public function restore(Request $request)
    {
        $request->validate([
            'backup_file' => 'required|file',
        ]);

        $path = $request->file('backup_file')->getRealPath();
        $sql = file_get_contents($path);

        try {
            // Disable foreign keys
            DB::statement('PRAGMA foreign_keys = OFF');

            // Drop all user tables
            $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
            foreach ($tables as $table) {
                DB::statement("DROP TABLE IF EXISTS {$table->name}");
            }

            // Execute SQL dump safely with better parsing
            $sql = preg_replace('/\R/', "\n", $sql); // Normalize line endings

            // Split SQL statements more reliably
            $statements = [];
            $currentStatement = '';
            $lines = explode("\n", $sql);

            foreach ($lines as $line) {
                $line = trim($line);

                // Skip comments and empty lines
                if (empty($line) || strpos($line, '--') === 0) {
                    continue;
                }

                $currentStatement .= $line."\n";

                // If line ends with semicolon, it's a complete statement
                if (substr($line, -1) === ';') {
                    $statements[] = trim($currentStatement);
                    $currentStatement = '';
                }
            }

            // Add any remaining statement
            if (! empty(trim($currentStatement))) {
                $statements[] = trim($currentStatement);
            }

            // Execute each statement
            foreach ($statements as $statement) {
                if (! empty($statement)) {
                    try {
                        DB::unprepared($statement);
                    } catch (\Exception $e) {
                        // Log the error but continue with other statements
                        \Log::error('Failed to execute SQL statement: '.$e->getMessage());
                        \Log::error('Statement: '.$statement);
                    }
                }
            }

            // Re-enable foreign keys
            DB::statement('PRAGMA foreign_keys = ON');

            // Log backup restored
            if (auth()->check()) {
                try {
                    $uploaded = $request->file('backup_file');
                    UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_SYSTEM_RESTORED, null, null, [
                        'filename' => $uploaded?->getClientOriginalName(),
                        'mime' => $uploaded?->getClientMimeType(),
                        'size' => $uploaded?->getSize(),
                        'driver' => 'sqlite',
                    ]);
                } catch (\Throwable $e) {
                    \Log::warning('Failed to log system restore: '.$e->getMessage());
                }
            }

            return back()->with('success', 'Database restored successfully!');
        } catch (\Exception $e) {
            DB::statement('PRAGMA foreign_keys = ON'); // Always re-enable
            \Log::error('Restore failed: '.$e->getMessage());

            return back()->with('error', 'Restore failed: '.$e->getMessage());
        }
    }

    // Removed restoreFromStored; upload-only restore

    // backup + resets tbales
    public function backupAndReset(): BinaryFileResponse
    {
        // 1. Backup to file
        $fileName = 'backup_reset_'.now()->format('Y-m-d_H-i-s').'.sql';
        $dumpPath = storage_path("app/backups/{$fileName}");
        if (! is_dir(dirname($dumpPath))) {
            mkdir(dirname($dumpPath), 0755, true);
        }

        $output = fopen($dumpPath, 'w');
        // Safer transaction-wrapped dump
        fwrite($output, "PRAGMA foreign_keys = OFF;\n");
        fwrite($output, "BEGIN TRANSACTION;\n");
        $tables = DB::select("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");

        foreach ($tables as $table) {
            $this->dumpTable($table->name, $output);
        }
        fwrite($output, "COMMIT;\n");
        fwrite($output, "PRAGMA foreign_keys = ON;\n");
        fclose($output);

        // 2. Truncate data while preserving super admin in users
        DB::statement('PRAGMA foreign_keys = OFF');

        // Build a set of table names
        $tableNames = array_map(fn ($t) => $t->name, $tables);

        // Delete from all tables except migrations and users
        foreach ($tableNames as $tableName) {
            if ($tableName === 'migrations' || $tableName === 'users') {
                continue;
            }
            DB::statement("DELETE FROM {$tableName}");
        }

        // Preserve super admin row by email_hash and account_type
        if (in_array('users', $tableNames)) {
            $superAdminEmailHash = hash('sha256', 'superadmin@usep.edu.ph');
            // Delete everyone who is not the super admin
            DB::statement("DELETE FROM users WHERE NOT (account_type = 'super_admin' AND email_hash = ?)", [$superAdminEmailHash]);
        }

        DB::statement('PRAGMA foreign_keys = ON');

        // 3. Log backup created and system reset
        if (auth()->check()) {
            try {
                UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_BACKUP_CREATED, null, null, [
                    'filename' => $fileName,
                    'path' => $dumpPath,
                    'size' => @filesize($dumpPath) ?: null,
                    'mode' => 'file',
                    'driver' => 'sqlite',
                ]);

                UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_SYSTEM_RESET, null, null, [
                    'preserved_user' => 'superadmin@usep.edu.ph',
                    'preserve_rule' => "account_type='super_admin' AND email_hash=sha256(email)",
                ]);
            } catch (\Throwable $e) {
                \Log::warning('Failed to log backup/reset: '.$e->getMessage());
            }
        }

        // 4. Return file as download
        return response()->download($dumpPath)->deleteFileAfterSend();
    }

    /* ───────────────────────────────
       Helper: dump CREATE + INSERT
    ─────────────────────────────── */
    private function dumpTable(string $table, $output)
    {
        fwrite($output, "-- Table: {$table}\n");
        fwrite($output, "DROP TABLE IF EXISTS {$table};\n"); // ✅ Force overwrite

        $create = DB::select("SELECT sql FROM sqlite_master WHERE type='table' AND name= ?", [$table])[0]->sql;
        fwrite($output, "{$create};\n");

        $rows = DB::table($table)->get();
        foreach ($rows as $row) {
            $values = collect($row)
                ->map(function ($v) {
                    if (is_null($v)) {
                        return 'NULL';
                    }
                    if (is_numeric($v)) {
                        return (string) $v;
                    }
                    // Escape single quotes by doubling them
                    $escaped = str_replace("'", "''", (string) $v);

                    return "'{$escaped}'";
                })
                ->implode(',');
            fwrite($output, "INSERT INTO {$table} VALUES ({$values});\n");
        }
    }
}
