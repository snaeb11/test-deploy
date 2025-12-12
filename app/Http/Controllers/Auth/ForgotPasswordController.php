<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class ForgotPasswordController extends Controller
{
    /**
     * Handle sending of the password reset email.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => ['required', 'email', 'regex:/^[a-zA-Z0-9._%+-]+@usep\.edu\.ph$/'],
        ]);

        if ($validator->fails()) {
            if ($request->wantsJson()) {
                return response()->json(
                    [
                        'errors' => $validator->errors(),
                        'message' => 'Please enter a valid USeP email address',
                    ],
                    422,
                );
            }

            return back()->withInput()->with('showForgotPasswordFailModal', true)->with('forgot_password_fail_message', 'Please enter a valid USeP email address');
        }

        $emailHash = hash('sha256', Str::lower($request->email));
        $user = User::where('email_hash', $emailHash)->first();

        if ($user) {
            try {
                // Log password reset request
                UserActivityLog::log($user, UserActivityLog::ACTION_PASSWORD_RESET_REQUESTED, $user);

                // Generate a password reset token
                $token = Str::random(64);

                // Store the token in the database
                DB::table('password_reset_tokens')->updateOrInsert(
                    ['email' => Str::lower($request->email)],
                    [
                        'email' => Str::lower($request->email),
                        'token' => Hash::make($token),
                        'created_at' => now(),
                    ],
                );

                // Send the password reset notification
                $user->sendPasswordResetNotification($token);

                // Log success for debugging
                \Log::info('Password reset email sent successfully', [
                    'email' => $request->email,
                    'user_id' => $user->id,
                ]);
            } catch (\Exception $e) {
                // Log the actual error for debugging
                \Log::error('Password reset email failed: ' . $e->getMessage(), [
                    'email' => $request->email,
                    'user_id' => $user->id ?? null,
                    'trace' => $e->getTraceAsString(),
                ]);

                if ($request->wantsJson()) {
                    return response()->json(
                        [
                            'message' => 'Failed to send reset link. Please try again.',
                            'error' => config('app.debug') ? $e->getMessage() : null,
                        ],
                        500,
                    );
                }

                return back()->withInput()->with('showForgotPasswordFailModal', true)->with('forgot_password_fail_message', 'Failed to send reset link. Please try again.');
            }
        }

        // Handle both JSON and web responses
        if ($request->wantsJson()) {
            return response()->json([
                'message' => 'Password reset link sent!',
            ]);
        }
    }
}
