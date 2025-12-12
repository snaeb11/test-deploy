<?php

namespace App\Notifications;

use App\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SubmissionRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public Submission $submission) {}

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();

        return $mailMessage->subject('Your Thesis Submission has been Rejected')->view('emails.thesis-rejected', [
            'user' => $notifiable,
            'submission' => $this->submission,
        ]);
    }
}
