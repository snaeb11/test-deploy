<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class VerificationController extends Controller
{
    /**
     * Verify the 6-digit code entered by the user.
     */
    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required|string|digits:6',
        ]);

        $userId = $request->session()->get('verifying_user_id');

        if (! $userId || ! ($user = User::find($userId))) {
            return response()->json(['message' => 'No user found. Please try logging in again.'], 422);
        }

        // Check verification code exists and matches
        if (! $user->verification_code || ! $user->verification_code_expires_at) {
            return response()->json(
                [
                    'message' => 'No active verification code found. Please request a new one.',
                ],
                422,
            );
        }

        if (now()->greaterThan($user->verification_code_expires_at)) {
            return response()->json(
                [
                    'message' => 'Verification code has expired. Please request a new one.',
                ],
                422,
            );
        }

        if (! hash_equals((string) $user->verification_code, $request->input('code'))) {
            return response()->json(
                [
                    'message' => 'The verification code is incorrect.',
                ],
                422,
            );
        }

        // Mark email as verified
        if (! $user->hasVerifiedEmail()) {
            $user->markEmailAsVerified();
        }

        // Clear verification data
        $user
            ->forceFill([
                'verification_code' => null,
                'verification_code_expires_at' => null,
            ])
            ->save();

        // Clear session
        $request->session()->forget('verifying_email');
        $request->session()->forget('verifying_user_id');

        // Log the email verification
        UserActivityLog::create([
            'user_id' => $user->id,
            'account_type' => $user->account_type,
            'program_id' => $user->program_id,
            'action' => UserActivityLog::ACTION_EMAIL_VERIFIED,
            'target_table' => 'users',
            'target_id' => $user->id,
            'performed_at' => now(),
        ]);

        // Log the user in
        Auth::login($user);
        $request->session()->regenerate();

        // Log successful login
        UserActivityLog::create([
            'user_id' => $user->id,
            'account_type' => $user->account_type,
            'program_id' => $user->program_id,
            'action' => UserActivityLog::ACTION_LOGGED_IN,
            'target_table' => 'users',
            'target_id' => $user->id,
            'performed_at' => now(),
        ]);

        // Determine redirect based on account type
        $redirectRoute = $this->getDashboardRoute($user);

        return response()->json([
            'message' => 'Email verified successfully',
            'status' => 'success',
            'redirect' => $redirectRoute,
            'first_name' => Crypt::decrypt($user->getRawOriginal('first_name')),
        ]);
    }

    protected function getDashboardRoute(User $user)
    {
        switch ($user->account_type) {
            case User::ROLE_ADMIN:
            case User::ROLE_SUPER_ADMIN:
                return url('/admin/dashboard');
            default:
                return url('/user/dashboard');
        }
    }

    /**
     * Resend a new code to the user identified in session.
     * Your Notification should send the code; see note below.
     */
    public function resend(Request $request)
    {
        $userId = $request->session()->get('verifying_user_id');

        if (! $userId) {
            return response()->json(['error' => 'Session expired or missing. Please log in again.'], 400);
        }

        $user = User::find($userId);

        if (! $user) {
            return response()->json(['error' => 'User not found'], 404);
        }

        if ($user->hasVerifiedEmail()) {
            return response()->json(['error' => 'Email already verified'], 400);
        }

        // Generate new 6-digit code
        $code = str_pad((string) random_int(0, 999999), 6, '0', STR_PAD_LEFT);

        $user
            ->forceFill([
                'verification_code' => $code,
                'verification_code_expires_at' => now()->addMinutes(15),
            ])
            ->save();

        $user->sendEmailVerificationNotification();

        return response()->json(['message' => 'Verification email resent']);
    }
}
