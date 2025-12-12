<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        /** @var \App\Models\User $user */
        $user = Auth::user();

        // Check if the user is logged in and has permission
        if (! $user || ! $user->hasPermission('view-dashboard')) {
            return redirect()->route('home')->with('error', 'You are not authorized to access the admin dashboard.');
        }

        $role = $user->account_type ?? 'admin';

        $undergraduate = Program::undergraduate()->orderBy('name')->get();
        $graduate = Program::graduate()->orderBy('name')->get();

        return view('layouts.admin-layout.admin-dashboard', [
            'page' => 'home',
            'role' => $role,
            'undergraduate' => $undergraduate,
            'graduate' => $graduate,
        ]);
    }

    public function updatePermissions(Request $request, $userId)
    {
        $user = User::findOrFail($userId);
        $currentUser = auth()->user();

        // Debug output
        \Log::info('Updating permissions', [
            'admin' => $currentUser->id,
            'target' => $userId,
            'permissions' => $request->permissions,
        ]);

        // Convert array to comma-separated string
        $permissionsString = implode(', ', $request->permissions);

        $user->update([
            'permissions' => $permissionsString,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Permissions updated',
            'permissions' => $user->permissions,
        ]);
    }

    public function getLogsData(Request $request)
    {
        $currentUser = auth()->user();
        if (! $currentUser || ! $currentUser->hasPermission('view-logs')) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $query = UserActivityLog::with(['user'])
            ->orderByDesc('performed_at')
            // Tie-break to ensure newest inserts with same timestamp appear first
            ->orderByDesc('id');

        if ($request->filled('action')) {
            $query->where('action', $request->string('action'));
        }
        if ($request->filled('account_type')) {
            $query->where('account_type', $request->string('account_type'));
        }
        if ($request->filled('program_id')) {
            $query->where('program_id', (int) $request->input('program_id'));
        }
        if ($request->filled('from')) {
            $query->whereDate('performed_at', '>=', $request->date('from'));
        }
        if ($request->filled('to')) {
            $query->whereDate('performed_at', '<=', $request->date('to'));
        }

        $limit = (int) $request->input('limit', 200);
        $limit = max(1, min($limit, 1000));

        $logs = $query->limit($limit)->get();

        $data = $logs->map(function (UserActivityLog $log) {
            // Keep a clear, concise action label. Do not append changed-columns details.
            $actionText = $log->action === UserActivityLog::ACTION_THESIS_UPDATED ? 'Thesis Item Updated' : $log->action_label;

            return [
                'name' => trim(($log->user?->decrypted_first_name ?? ($log->user?->first_name ?? '')).' '.($log->user?->decrypted_last_name ?? ($log->user?->last_name ?? ''))) ?: '—',
                'action' => $actionText,
                'target_table' => $log->target_table ?? '—',
                'target_id' => $log->target_id ?? '—',
                'performed_at' => optional($log->performed_at)->toIso8601String() ?? now()->toIso8601String(),
            ];
        });

        return response()->json($data)->header('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0')->header('Pragma', 'no-cache')->header('Expires', '0');
    }
}
