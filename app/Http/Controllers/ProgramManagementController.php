<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;

class ProgramManagementController extends Controller
{
    public function index()
    {
        $this->authorizeModify();

        return response()->json(Program::orderBy('degree')->orderBy('name')->get());
    }

    public function store(Request $request)
    {
        $this->authorizeModify();
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:programs,name',
            'degree' => 'required|string|in:Undergraduate,Graduate',
        ]);
        $program = Program::create($validated);

        // Log program creation
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_PROGRAM_CREATED, $program, null, [
            'program_name' => $program->name,
            'degree' => $program->degree,
        ]);

        return response()->json($program, 201);
    }

    public function update(Request $request, Program $program)
    {
        $this->authorizeModify();
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:programs,name,'.$program->id,
            'degree' => 'required|string|in:Undergraduate,Graduate',
        ]);

        // Track changes for logging
        $originalData = $program->toArray();
        $changedColumns = [];

        $program->update($validated);

        // Check what changed
        foreach (['name', 'degree'] as $field) {
            if ($originalData[$field] !== $program->fresh()->$field) {
                $changedColumns[] = $field;
            }
        }

        // Log program update if changes were made
        if (! empty($changedColumns)) {
            UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_PROGRAM_UPDATED, $program, null, [
                'program_name' => $program->name,
                'degree' => $program->degree,
                'changed_columns' => $changedColumns,
            ]);
        }

        return response()->json($program);
    }

    public function destroy(Program $program)
    {
        $this->authorizeModify();

        // Log program deletion before deleting
        UserActivityLog::log(auth()->user(), UserActivityLog::ACTION_PROGRAM_DELETED, $program, null, [
            'program_name' => $program->name,
            'degree' => $program->degree,
        ]);

        $program->delete();

        return response()->json(['deleted' => true]);
    }

    private function authorizeModify(): void
    {
        $user = auth()->user();
        abort_unless($user && $user->hasPermission('modify-programs-list'), 403);
    }
}
