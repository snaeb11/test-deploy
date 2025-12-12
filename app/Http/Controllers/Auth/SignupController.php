<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Program;
use App\Models\User;
use App\Models\UserActivityLog;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class SignupController extends Controller
{
    public function showRegistrationForm()
    {
        $undergraduate = Program::undergraduate()->orderBy('name')->get();
        $graduate = Program::graduate()->orderBy('name')->get();

        return view('layouts.auth.signup', [
            'undergraduate' => $undergraduate,
            'graduate' => $graduate,
        ]);
    }

    public function store(Request $request)
    {
        // Validate email
        $emailValidator = Validator::make($request->only('email'), [
            'email' => ['required', 'string', 'email', 'max:255', 'regex:/^[a-zA-Z0-9._%+-]+@usep\.edu\.ph$/'],
        ]);

        if ($emailValidator->fails()) {
            return redirect()->back()->withInput()->with('invalid_email', true);
        }

        // Validate the rest of the fields
        $validated = $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => ['required', 'confirmed', Password::min(8)->letters()->mixedCase()->numbers()->symbols()],
            'program_id' => 'required|exists:programs,id',
        ]);

        $email = Str::lower($validated['email']);

        // Check for existing user using the hash
        if (User::where('email_hash', hash('sha256', $email))->exists()) {
            return redirect()->back()->withInput()->with('email_taken', true);
        }

        // Create the user with encrypted data
        $user = User::create([
            'first_name' => Crypt::encrypt($validated['first_name']),
            'last_name' => Crypt::encrypt($validated['last_name']),
            'email' => Crypt::encrypt($email),
            'email_hash' => hash('sha256', $email),
            'password' => Hash::make($validated['password']),
            'program_id' => $validated['program_id'],
            'account_type' => User::ROLE_STUDENT,
            'status' => 'active',
        ]);

        // Log the registration with target table and ID
        UserActivityLog::create([
            'user_id' => $user->id,
            'account_type' => $user->account_type,
            'program_id' => $user->program_id,
            'action' => UserActivityLog::ACTION_REGISTERED,
            'target_table' => 'users',
            'target_id' => $user->id,
            'performed_at' => now(),
        ]);

        // Store verification data in session
        $request->session()->put('verifying_email', $email);
        $request->session()->put('verifying_user_id', $user->id);

        // Trigger registration event
        event(new Registered($user));

        return redirect()
            ->route('signup')
            ->with([
                'account_created' => true,
                'account_name' => $validated['first_name'].' '.$validated['last_name'],
                'account_email' => $email,
                'show_verification' => true,
            ]);
    }
}
