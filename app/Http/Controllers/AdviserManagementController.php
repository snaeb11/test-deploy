<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class AdviserManagementController extends Controller
{
    public function index()
    {
        $this->authorizeModify();

        return response()->json(Adviser::with('program')->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $this->authorizeModify();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
        ]);
        $adviser = Adviser::create($validated);

        // Log adviser creation
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_ADVISER_CREATED, $adviser, $adviser->program_id, [
            'adviser_name' => $adviser->name,
            'program_name' => $adviser->program->name,
        ]);

        return response()->json($adviser->load('program'), 201);
    }

    public function update(Request $request, Adviser $adviser)
    {
        $this->authorizeModify();
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
        ]);

        // Track changes for logging
        $originalData = $adviser->toArray();
        $changedColumns = [];

        $adviser->update($validated);

        // Check what changed
        foreach (['name', 'program_id'] as $field) {
            if ($originalData[$field] !== $adviser->fresh()->$field) {
                $changedColumns[] = $field;
            }
        }

        // Log adviser update if changes were made
        if (! empty($changedColumns)) {
            UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_ADVISER_UPDATED, $adviser, $adviser->program_id, [
                'adviser_name' => $adviser->name,
                'program_name' => $adviser->program->name,
                'changed_columns' => $changedColumns,
            ]);
        }

        return response()->json($adviser->load('program'));
    }

    public function destroy(Adviser $adviser)
    {
        $this->authorizeModify();

        // Log adviser deletion before deleting
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_ADVISER_DELETED, $adviser, $adviser->program_id, [
            'adviser_name' => $adviser->name,
            'program_name' => $adviser->program->name,
        ]);

        $adviser->delete();

        return response()->json(['deleted' => true]);
    }

    private function authorizeModify(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->hasPermission('modify-advisers-list'), 403);
    }
}
