<?php

namespace App\Notifications;

use App\Models\FacultyFormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class FormSubmissionForwardedNotification extends Notification
{
    use Queueable;

    public function __construct(public FacultyFormSubmission $formSubmission, public string $toEmail, public string $customMessage = '') {}

    public function via($notifiable): array
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = new MailMessage();

        return $mailMessage->subject('Your Form Submission has been Forwarded')->view('emails.form-forwarded-user', [
            'user' => $notifiable,
            'formSubmission' => $this->formSubmission,
            'toEmail' => $this->toEmail,
            'customMessage' => $this->customMessage,
        ]);
    }
}
