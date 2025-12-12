<?php

namespace App\Mail;

use App\Models\FacultyFormSubmission;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class FormForwardedMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public FacultyFormSubmission $formSubmission, public string $toEmail, public string $customMessage = '')
    {
        \Log::info('FormForwardedMail created', [
            'form_id' => $formSubmission->id,
            'to_email' => $toEmail,
            'has_document' => $formSubmission->hasDocument(),
            'document_path' => $formSubmission->document_path,
        ]);
    }

    public function envelope(): Envelope
    {
        return new Envelope(subject: 'Received a forwarded Form Submission');
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.form-forwarded',
            with: [
                'formSubmission' => $this->formSubmission,
                'toEmail' => $this->toEmail,
                'customMessage' => $this->customMessage,
            ],
        );
    }

    public function attachments(): array
    {
        $attachments = [];

        \Log::info('FormForwardedMail attachments() called', [
            'form_id' => $this->formSubmission->id,
            'has_document' => $this->formSubmission->hasDocument(),
        ]);

        try {
            if ($this->formSubmission->hasDocument()) {
                // Use Laravel's Storage facade to get the correct path
                $filePath = Storage::disk('public')->path($this->formSubmission->document_path);

                \Log::info('Attempting to attach file', [
                    'file_path' => $filePath,
                    'exists' => file_exists($filePath),
                    'document_path' => $this->formSubmission->document_path,
                    'document_filename' => $this->formSubmission->document_filename,
                    'storage_path' => Storage::disk('public')->path(''),
                ]);

                if (file_exists($filePath)) {
                    $attachments[] = Attachment::fromPath($filePath)
                        ->as($this->formSubmission->document_filename)
                        ->withMime($this->formSubmission->document_mime ?? 'application/octet-stream');

                    \Log::info('File attached successfully');
                } else {
                    \Log::warning('File does not exist at path: ' . $filePath);
                    // Try alternative path construction
                    $altPath = storage_path('app/public/' . $this->formSubmission->document_path);
                    \Log::info('Trying alternative path: ' . $altPath . ' (exists: ' . (file_exists($altPath) ? 'yes' : 'no') . ')');

                    if (file_exists($altPath)) {
                        $attachments[] = Attachment::fromPath($altPath)
                            ->as($this->formSubmission->document_filename)
                            ->withMime($this->formSubmission->document_mime ?? 'application/octet-stream');
                        \Log::info('File attached using alternative path');
                    } else {
                        \Log::warning('File not found at either path');
                    }
                }
            } else {
                \Log::info('No document to attach');
            }
        } catch (\Exception $e) {
            \Log::error('Error in attachment process: ' . $e->getMessage(), [
                'form_id' => $this->formSubmission->id,
                'error_trace' => $e->getTraceAsString(),
            ]);
            // Continue without attachment rather than failing the email
        }

        \Log::info('FormForwardedMail attachments() completed', [
            'attachment_count' => count($attachments),
            'form_id' => $this->formSubmission->id,
        ]);

        return $attachments;
    }
}
