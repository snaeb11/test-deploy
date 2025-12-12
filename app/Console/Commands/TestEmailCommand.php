<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Notifications\ResetPasswordNotification;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class TestEmailCommand extends Command
{
    protected $signature = 'test:email {email}';
    protected $description = 'Test email sending functionality';

    public function handle()
    {
        $email = $this->argument('email');

        $this->info("Testing email functionality for: {$email}");

        // Check mail configuration
        $this->info('Mail driver: ' . config('mail.default'));
        $this->info('Mail from: ' . config('mail.from.address'));

        // Find user
        $emailHash = hash('sha256', Str::lower($email));
        $user = User::where('email_hash', $emailHash)->first();

        if (!$user) {
            $this->error("User not found with email: {$email}");
            return 1;
        }

        $this->info("User found: {$user->full_name} (ID: {$user->id})");

        try {
            // Test sending reset password notification
            $token = Str::random(64);
            $user->sendPasswordResetNotification($token);

            $this->info('✅ Password reset notification sent successfully!');

            // If using log driver, show where to check
            if (config('mail.default') === 'log') {
                $this->warn('📝 Using log driver - check storage/logs/laravel.log for email content');
            }
        } catch (\Exception $e) {
            $this->error('❌ Failed to send email: ' . $e->getMessage());
            $this->error('Trace: ' . $e->getTraceAsString());
            return 1;
        }

        return 0;
    }
}
