<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class UserAccountsController extends Controller
{
    public function getAllUsers(Request $request)
    {
        $query = User::with('program');

        // Filter by account_type if provided
        if ($request->has('account_type') && $request->account_type) {
            $query->where('account_type', $request->account_type);
        }

        // Always fetch first (cannot search encrypted columns directly in DB)
        $users = $query->get();

        // Laravel-side search (works with decrypted values)
        if ($request->has('search') && $request->search) {
            $search = strtolower($request->search);

            $users = $users->filter(function ($user) use ($search) {
                return stripos($user->decrypted_first_name, $search) !== false ||
                    stripos($user->decrypted_last_name, $search) !== false ||
                    stripos($user->email, $search) !== false || // use decrypted_email if email is encrypted
                    stripos($user->account_type, $search) !== false ||
                    stripos($user->status, $search) !== false ||
                    stripos(optional($user->program)->name, $search) !== false ||
                    stripos(optional($user->program)->degree, $search) !== false;
            });
        }

        // Transform
        $formatted = $users->map(function ($user) {
            return [
                'id' => $user->id,
                'first_name' => $user->decrypted_first_name,
                'last_name' => $user->decrypted_last_name,
                'email' => $user->email, // change to $user->decrypted_email if stored encrypted
                'account_type' => ucfirst($user->account_type),
                'status' => ucfirst($user->status),
                'program' => $user->program->name ?? null,
                'degree' => $user->program->degree ?? null,
            ];
        });

        return response()->json($formatted->values()); // reset keys
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'first_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'\-]+$/',
                'last_name' => 'required|string|max:255|regex:/^[A-Za-z\s\'\-]+$/',
                'email' => [
                    'required',
                    'email',
                    'regex:/^[^\s@]+@usep\.edu\.ph$/',
                    function ($attribute, $value, $fail) {
                        $emailHash = hash('sha256', $value);
                        if (User::where('email_hash', $emailHash)->exists()) {
                            $fail('The email has already been taken.');
                        }
                    },
                ],
                'password' => 'required|string',
                'permissions' => 'required|string',
            ]);

            // Define all valid permissions
            $validPermissions = ['view-dashboard', 'view-thesis-submissions', 'view-forms-submissions', 'acc-rej-thesis-submissions', 'acc-rej-forms-submissions', 'view-inventory', 'add-inventory', 'edit-inventory', 'export-inventory', 'import-inventory', 'view-accounts', 'edit-permissions', 'add-admin', 'modify-programs-list', 'modify-advisers-list', 'view-logs', 'view-backup', 'download-backup', 'allow-restore'];

            // Clean and split the permissions string
            $permissionsString = trim($validated['permissions']);
            $permissionsArray = preg_split('/\s*,\s*/', $permissionsString, -1, PREG_SPLIT_NO_EMPTY);

            // Validate each permission
            $invalidPermissions = [];
            foreach ($permissionsArray as $permission) {
                if (! in_array($permission, $validPermissions)) {
                    $invalidPermissions[] = $permission;
                }
            }

            if (! empty($invalidPermissions)) {
                throw ValidationException::withMessages([
                    'permissions' => ['Invalid permission(s): '.implode(', ', $invalidPermissions)],
                ]);
            }

            $user = new User;
            $user->first_name = encrypt(ucwords(strtolower($validated['first_name'])));
            $user->last_name = encrypt(ucwords(strtolower($validated['last_name'])));
            $user->email = encrypt($validated['email']);
            $user->email_hash = hash('sha256', $validated['email']);
            $user->account_type = 'admin';
            $user->status = 'active';
            $user->password = bcrypt($validated['password']);
            $user->permissions = $validated['permissions'];

            $user->save();

            $actingUser = auth()->user(); // The admin who is creating this account
            UserActivityLog::log($actingUser, UserActivityLog::ACTION_USER_CREATED, $user, null, [
                'account_type' => $user->account_type,
                'status' => $user->status,
                'permissions' => $permissionsArray,
            ]);

            return response()->json([
                'message' => 'Admin created successfully',
                'user' => [
                    'id' => $user->id,
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'email' => $validated['email'],
                    'account_type' => 'Admin',
                    'status' => 'Active',
                    'permissions' => $user->permissions,
                ],
            ]);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'errors' => $e->errors(),
                    'message' => 'Validation failed',
                ],
                422,
            );
        } catch (\Exception $e) {
            return response()->json(
                [
                    'error' => 'Server error',
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function updatePermissions(Request $request, $userId)
    {
        try {
            $validated = $request->validate(
                [
                    'permissions' => 'required|array|min:1',
                    'permissions.*' => 'string',
                ],
                [
                    'permissions.required' => 'Permissions are required.',
                    'permissions.min' => 'Please select at least one permission.',
                ],
            );

            $user = User::findOrFail($userId);
            $oldPermissions = $user->permissions ? explode(', ', $user->permissions) : [];

            $hyphenatedPermissions = array_map(function ($permission) {
                return str_replace('_', '-', $permission);
            }, $validated['permissions']);

            $permissionsString = implode(', ', $hyphenatedPermissions);

            $user->update([
                'permissions' => $permissionsString,
            ]);

            // Log the activity
            $actingUser = auth()->user();
            UserActivityLog::log($actingUser, UserActivityLog::ACTION_PERMISSIONS_UPDATED, $user, null, [
                'old_permissions' => $oldPermissions,
                'new_permissions' => $hyphenatedPermissions,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Permissions updated successfully',
                'permissions' => $user->permissions,
            ]);
        } catch (\Exception $e) {
            \Log::error('Permission update failed: '.$e->getMessage());

            return response()->json(
                [
                    'error' => 'Server error',
                    'message' => $e->getMessage(),
                ],
                500,
            );
        }
    }

    public function getUserPermissions(User $user)
    {
        $currentUser = auth()->user();

        $currentPermissions = array_map(fn ($p) => strtolower(trim($p)), explode(',', $currentUser->permissions));

        \Log::debug('Final permission verification', [
            'user' => $currentUser->id,
            'permissions' => $currentPermissions,
            'check_for' => 'edit-permissions',
            'found' => in_array('edit-permissions', $currentPermissions),
        ]);

        if (! in_array('edit-permissions', $currentPermissions)) {
            \Log::error('Permission check failed despite debug showing permission exists');

            return response()->json(
                [
                    'error' => 'permission-check-failed',
                    'debug_info' => [
                        'raw_permissions' => $currentUser->permissions,
                        'processed_permissions' => $currentPermissions,
                        'missing_permission' => 'edit-permissions',
                        'check_type' => gettype($currentPermissions[0]),
                    ],
                ],
                403,
            );
        }

        $targetPermissions = array_map(fn ($p) => strtolower(trim($p)), explode(',', $user->permissions));

        return response()->json([
            'permissions' => $targetPermissions,
            'debug_note' => 'Successfully loaded permissions',
        ]);
    }

    public function canAddAdmin()
    {
        $currentUser = auth()->user();

        if (! $currentUser) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }

        $currentPermissions = array_map(fn ($p) => strtolower(trim($p)), explode(',', $currentUser->permissions));

        if (! in_array('add-admin', $currentPermissions)) {
            return response()->json(
                [
                    'error' => 'permission-denied',
                    'message' => 'You do not have permission to add admins',
                ],
                403,
            );
        }

        return response()->json(['can_add' => true]);
    }
}
