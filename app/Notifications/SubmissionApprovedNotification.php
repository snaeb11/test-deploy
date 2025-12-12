<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionApprovedNotification extends Notification
{
    use Queueable;

    public function __construct(public Submission $submission) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();

        return $mailMessage->subject('Your Thesis Submission has been Approved')->view('emails.thesis-approved', [
            'user' => $notifiable,
            'submission' => $this->submission,
        ]);
    }
}
