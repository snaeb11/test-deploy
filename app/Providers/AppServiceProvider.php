<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        VerifyEmail::toMailUsing(function (object $notifiable) {
            $code = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);

            $notifiable->verification_code = $code;
            $notifiable->verification_code_expires_at = now()->addMinutes(15);
            $notifiable->save();

            $mailMessage = new MailMessage;

            return $mailMessage->subject('Verify Your Email')->view('emails.verify-email', [
                'user' => $notifiable,
                'verificationCode' => $code,
            ]);
        });
    }
}
