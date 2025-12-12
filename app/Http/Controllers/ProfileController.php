<?php

namespace App\Http\Controllers;

use App\Models\Adviser;
use App\Models\Program;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function showAdminDashboard()
    {
        $user = auth()->user();
        $undergraduate = Program::undergraduate()->orderBy('name')->get();
        $graduate = Program::graduate()->orderBy('name')->get();
        $programs = Program::orderBy('name')->get(); // All programs for advisers management
        $advisers = Adviser::with('program')->orderBy('name')->get();

        // Check if the user is logged in and has permission
        if (!$user || !$user->hasPermission('view-dashboard')) {
            return redirect()->route('home')->with('error', 'You are not authorized to access the admin dashboard.');
        }

        return view('layouts.admin-layout.admin-dashboard', [
            'undergraduate' => $undergraduate,
            'graduate' => $graduate,
            'programs' => $programs,
            'advisers' => $advisers,
            'user' => $user,
        ]);
    }

    public function showUserDashboard()
    {
        $user = auth()->user();
        $undergraduate = Program::undergraduate()->orderBy('name')->get();
        $graduate = Program::graduate()->orderBy('name')->get();

        // Get advisers for the user's program only
        $userAdvisers = collect();
        if ($user->program_id) {
            $userAdvisers = Adviser::where('program_id', $user->program_id)->orderBy('name')->get();
        }

        return view('layouts.user-layout.user-dashboard', [
            'undergraduate' => $undergraduate,
            'graduate' => $graduate,
            'userAdvisers' => $userAdvisers,
            'user' => $user,
        ]);
    }

    public function showFacultyDashboard()
    {
        $user = auth()->user();
        $undergraduate = Program::undergraduate()->orderBy('name')->get();
        $graduate = Program::graduate()->orderBy('name')->get();

        // Get advisers for the faculty's program only
        $facultyAdvisers = collect();
        if ($user->program_id) {
            $facultyAdvisers = Adviser::where('program_id', $user->program_id)->orderBy('name')->get();
        }

        return view('layouts.faculty-layout.faculty-dashboard', [
            'undergraduate' => $undergraduate,
            'graduate' => $graduate,
            'facultyAdvisers' => $facultyAdvisers,
            'faculty' => $user,
        ]);
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'program_id' => 'required|exists:programs,id',
        ]);

        $user = auth()->user();

        // Store original values before update
        $originalValues = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'program_id' => $user->program_id,
        ];

        $user->update([
            'first_name' => Crypt::encrypt($validated['first_name']),
            'last_name' => Crypt::encrypt($validated['last_name']),
            'program_id' => $validated['program_id'],
        ]);

        // Determine which fields changed
        $changedFields = [];
        foreach ($originalValues as $field => $originalValue) {
            $changedFields[$field] = $originalValue !== $user->$field;
        }

        // Log profile update activity
        UserActivityLog::log($user, UserActivityLog::ACTION_PROFILE_UPDATED, $user, $user->program_id, [
            'changes' => $changedFields,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
        ]);
    }

    public function updateFacultyProfile(Request $request)
    {
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);

        $user = auth()->user();

        // Store original values before update
        $originalValues = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
        ];

        $user->update([
            'first_name' => Crypt::encrypt($validated['first_name']),
            'last_name' => Crypt::encrypt($validated['last_name']),
        ]);

        // Determine which fields changed
        $changedFields = [];
        foreach ($originalValues as $field => $originalValue) {
            $changedFields[$field] = $originalValue !== $user->$field;
        }

        // Log profile update activity
        UserActivityLog::log($user, UserActivityLog::ACTION_PROFILE_UPDATED, $user, $user->program_id, [
            'changes' => $changedFields,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Faculty profile updated successfully',
        ]);
    }

    public function deactivate_account(Request $request)
    {
        $user = $request->user();

        if (!$user) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'User not authenticated',
                ],
                401,
            );
        }

        // Store relevant info before updating
        $originalStatus = $user->status;
        $deletionDate = now()->addDays(30);

        $updateSuccess = $user->update([
            'status' => 'deactivated',
            'deactivated_at' => now(),
            'scheduled_for_deletion' => $deletionDate,
            'remember_token' => null,
        ]);

        if (!$updateSuccess) {
            return response()->json(
                [
                    'success' => false,
                    'message' => 'Failed to update user record',
                ],
                500,
            );
        }

        // Log deactivation activity
        UserActivityLog::log($user, UserActivityLog::ACTION_ACCOUNT_DEACTIVATED, $user, $user->program_id, [
            'system' => [
                'previous_status' => $originalStatus,
                'scheduled_deletion_date' => $deletionDate->format('Y-m-d'),
                'retention_days' => 30,
            ],
        ]);

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return response()->json([
            'success' => true,
            'message' => 'Account deactivated successfully. You will be redirected shortly.',
            'redirect' => url('/'),
        ]);
    }

    // checker for same amoghus balls
    public function updateAdminProfile(Request $request)
    {
        $validationRules = [
            'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'\-]+$/',
            'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'\-]+$/',
        ];

        if ($request->filled('current_password') || $request->filled('new_password')) {
            $validationRules['current_password'] = 'required';
            $validationRules['new_password'] = ['required', 'min:8', 'confirmed', 'different:current_password', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'];
        }

        try {
            $validated = $request->validate($validationRules, [
                'first_name.regex' => 'Only letters, spaces, apostrophes and hyphens are allowed',
                'last_name.regex' => 'Only letters, spaces, apostrophes and hyphens are allowed',
                'new_password.regex' => 'Password must contain at least one uppercase, one lowercase, one number and one special character',
            ]);

            // ... rest of your update logic ...
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json(
                [
                    'message' => 'Validation failed',
                    'errors' => $e->errors(),
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'message' => 'Failed to update profile: ' . $e->getMessage(),
                ],
                500,
            );
        }

        $user = auth()->user();

        // Store original values before update
        $originalValues = [
            'first_name' => $user->first_name,
            'last_name' => $user->last_name,
            'password' => $user->password,
        ];

        // Prepare update data
        $updateData = [
            'first_name' => Crypt::encrypt($validated['first_name']),
            'last_name' => Crypt::encrypt($validated['last_name']),
        ];

        // Handle password update if provided
        if (isset($validated['new_password'])) {
            if (!Hash::check($validated['current_password'], $user->password)) {
                return response()->json(
                    [
                        'errors' => ['current_password' => ['The current password is incorrect']],
                    ],
                    422,
                );
            }
            $updateData['password'] = Hash::make($validated['new_password']);
        }

        $user->update($updateData);

        // Determine which fields changed
        $changedFields = [];
        foreach ($originalValues as $field => $originalValue) {
            $changedFields[$field] = $originalValue !== $user->$field;
        }

        // Log profile update activity
        UserActivityLog::log($user, UserActivityLog::ACTION_PROFILE_UPDATED, $user, null, [
            'changes' => $changedFields,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully',
        ]);
    }
}
