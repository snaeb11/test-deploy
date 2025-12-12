<?php

namespace App\Imports;

use App\Models\Inventory;
use App\Models\Program;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;

class InventoryImport implements ToModel, WithHeadingRow, WithValidation
{
    use Importable;

    /**
     * Derive a program acronym from its display name.
     */
    protected function getProgramAcronymFromName(?string $programName): string
    {
        $name = trim((string) $programName);
        if ($name === '') {
            return 'GEN';
        }
        if (preg_match('/\(([^)]+)\)/', $name, $m)) {
            return strtoupper(preg_replace('/[^A-Z0-9]/i', '', $m[1]));
        }
        $words = preg_split('/\s+/', $name) ?: [];
        $initials = array_map(function ($w) {
            return $w !== '' ? strtoupper(substr(preg_replace('/[^A-Za-z0-9]/', '', $w), 0, 1)) : '';
        }, $words);
        $acronym = implode('', $initials);
        $acronym = $acronym !== '' ? $acronym : strtoupper(preg_replace('/\s+/', '', $name));

        return $acronym !== '' ? $acronym : 'GEN';
    }

    /* -------------------------------------------------
     *  Validation rules for every row
     * -------------------------------------------------*/
    public function rules(): array
    {
        return [
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'adviser' => 'required|string|max:255',
            'abstract' => 'required|string',
            'program' => 'required|string|exists:programs,name', // code must exist
            'academic_year' => 'required|integer|min:2000|max:3000',
        ];
    }

    /* -------------------------------------------------
     *  Nice attribute names in error messages
     * -------------------------------------------------*/
    public function customValidationAttributes()
    {
        return [
            'title' => 'Title',
            'authors' => 'Authors',
            'adviser' => 'Adviser',
            'abstract' => 'Abstract',
            'program' => 'Program',
            'academic_year' => 'Academic Year',
        ];
    }

    /* -------------------------------------------------
     *  Map row → Inventory
     * -------------------------------------------------*/
    public function model(array $row)
    {
        // Resolve program code → program_id
        $program = Program::where('name', $row['program'])->firstOrFail();
        $year = (int) $row['academic_year'];

        // Build the next sequence number
        $programCode = $this->getProgramAcronymFromName($program->name ?? '');
        $latest = Inventory::where('inventory_number', 'like', "{$programCode}-{$year}-%")
            ->orderBy('inventory_number', 'desc')
            ->value('inventory_number');

        $nextSerial = 1;
        if ($latest) {
            preg_match("/-(\d+)$/", $latest, $m);
            $nextSerial = ((int) $m[1]) + 1;
        }
        $inventoryNumber = sprintf('%s-%d-%03d', $programCode, $year, $nextSerial);

        // Dummy file info (or leave columns nullable)
        $fileName = 'imported_file.xlsx';
        $filePath = 'inventory/'.uniqid().'_'.$fileName;
        Storage::disk('local')->put($filePath, 'dummy');

        return new Inventory([
            'submission_id' => null,
            'title' => strtoupper($row['title']),
            'authors' => $row['authors'],
            'adviser' => $row['adviser'],
            'abstract' => $row['abstract'],
            'program_id' => $program->id,
            'archived_path' => 'N/A',
            'original_filename' => 'N/A',
            'file_size' => 0,
            'academic_year' => $year,
            'inventory_number' => $inventoryNumber,
            'archived_by' => 1,
            'archived_at' => now(),
        ]);
    }
}
