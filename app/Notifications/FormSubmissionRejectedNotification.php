<?php

namespace App\Notifications;

use App\Models\FacultyFormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormSubmissionRejectedNotification extends Notification
{
    use Queueable;

    public function __construct(public FacultyFormSubmission $formSubmission) {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();

        return $mailMessage->subject('Your Form Submission has been Rejected')->view('emails.form-rejected', [
            'user' => $notifiable,
            'formSubmission' => $this->formSubmission,
        ]);
    }
}
