<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class PasswordController extends Controller
{
    public function update_password(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'current_password' => 'required',
            'new_password' => ['required', 'min:8', 'confirmed', 'different:current_password', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^a-zA-Z\d]).+$/'],
            'new_password_confirmation' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = auth()->user();

        if (! Hash::check($request->input('current_password'), $user->password)) {
            return response()->json(
                [
                    'errors' => ['current_password' => ['The current password is incorrect']],
                ],
                422,
            );
        }

        $user->update([
            'password' => Hash::make($request->input('new_password')),
        ]);

        if (! Hash::check('!2Qwerty', $user->password)) {
            $request->session()->forget('show_password_reminder');
        }

        // Log successful password change
        UserActivityLog::log($user, UserActivityLog::ACTION_PASSWORD_CHANGED, $user);

        return response()->json(['message' => 'Password updated successfully']);
    }
}
