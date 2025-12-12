@extends('emails.layouts.base')

@section('title', 'Form Submission Review | CTET-CTSuL')

@section('header-title', 'Form Submission Review')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p class="text-justify">Thank you for your form submission through the CTET-CTSuL Inventory System. After review, we
            need to request some corrections before your form can be processed.</p>
    </div>

    <div class="info-box">
        <p><strong>Form Type:</strong> {{ $formSubmission->form_type }}</p>
        <p><strong>Submitted on:</strong> {{ $formSubmission->submitted_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Reviewed on:</strong> {{ $formSubmission->reviewed_at->format('F j, Y \a\t g:i A') }}</p>
        @if ($formSubmission->review_remarks)
            <p><strong>Reviewer Comments:</strong> {{ $formSubmission->review_remarks }}</p>
        @endif
    </div>

    <div class="message-content">
        <p><strong>Required Actions:</strong></p>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li>Please review the comments provided by the reviewer</li>
            <li>Make the necessary corrections to your form</li>
            <li>Resubmit your form through the CTET-CTSuL Inventory System</li>
            <li>Ensure all required fields are completed accurately</li>
            <li>Attach any additional documentation if requested</li>
        </ul>

        <p class="text-justify">The review process helps ensure that all submissions meet the required standards and contain
            complete, accurate information. We appreciate your understanding and cooperation.</p>

        <p class="text-justify">If you have questions about the review comments or need assistance with the resubmission
            process, please contact your system administrator or the relevant department.</p>
    </div>
@endsection
