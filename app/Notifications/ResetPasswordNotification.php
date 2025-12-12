<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetPasswordNotification extends Notification
{
    public function __construct(public string $token) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $url = url(
            route(
                'password.reset',
                [
                    'token' => $this->token,
                    'email' => $notifiable->getEmailForVerification(),
                ],
                false,
            ),
        );

        $mailMessage = new MailMessage();

        return $mailMessage->subject('Reset Your Password')->view('emails.reset-password', [
            'user' => $notifiable,
            'resetUrl' => $url,
            'token' => $this->token,
        ]);
    }
}
