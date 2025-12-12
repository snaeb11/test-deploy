<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password as PasswordRule;
use Illuminate\Validation\ValidationException;

class ResetPasswordController extends Controller
{
    public function showResetForm(Request $request, $token = null)
    {
        return view('layouts.auth.reset-password', [
            'token' => $token,
            'email' => $request->input('email'),
        ]);
    }

    public function reset(Request $request)
    {
        try {
            $request->validate([
                'token' => ['required'],
                'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@usep\.edu\.ph$/'],
                'password' => ['required', 'confirmed', PasswordRule::min(8)->mixedCase()->numbers()->symbols()],
            ]);
        } catch (ValidationException $e) {
            return response()->json(
                [
                    'errors' => $e->validator->errors(),
                    'message' => $e->validator->errors()->first(),
                ],
                422,
            );
        }

        // Normalize email (trim and lowercase)
        $email = Str::lower(trim($request->email));
        $emailHash = hash('sha256', $email);

        // Find user by email hash
        $user = User::where('email_hash', $emailHash)->first();

        if (! $user) {
            return response()->json(
                [
                    'message' => 'No user found with this email address.',
                ],
                404,
            );
        }

        // Get token record with created_at check
        $tokenRecord = \DB::table('password_reset_tokens')->where('email', $email)->first();

        if (! $tokenRecord) {
            return response()->json(
                [
                    'message' => 'This password reset token is invalid or has expired.',
                ],
                422,
            );
        }

        // Token validation
        $requestToken = trim($request->token);
        if (! Hash::check($requestToken, $tokenRecord->token)) {
            $urlDecodedToken = rawurldecode($requestToken);
            if (! Hash::check($urlDecodedToken, $tokenRecord->token)) {
                return response()->json(
                    [
                        'message' => 'This password reset token is invalid or has expired.',
                    ],
                    422,
                );
            }
        }

        // Check token expiration
        if (now()->diffInMinutes($tokenRecord->created_at) > config('auth.passwords.users.expire', 60)) {
            \DB::table('password_reset_tokens')->where('email', $email)->delete();

            return response()->json(
                [
                    'message' => 'This password reset link has expired.',
                ],
                422,
            );
        }

        // Update password
        $user
            ->forceFill([
                'password' => Hash::make($request->password),
                'remember_token' => Str::random(60),
            ])
            ->save();

        // Delete the token
        \DB::table('password_reset_tokens')->where('email', $email)->delete();

        // Log password reset success
        UserActivityLog::log($user, UserActivityLog::ACTION_PASSWORD_RESET_SUCCESSFUL, $user);

        event(new PasswordReset($user));

        return response()->json([
            'success' => true,
            'message' => 'Your password has been reset successfully.',
        ]);
    }
}
