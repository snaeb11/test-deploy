@extends('emails.layouts.base')

@section('title', 'Form Forwarded | CTET-CTSuL')

@section('header-title', 'Form Submission Forwarded')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p class="text-justify">Your form submission has been reviewed and <span
                style="color: #007bff; font-weight: 600;">forwarded</span> to the appropriate recipient for further
            processing.</p>
    </div>

    <div class="info-box">
        <p><strong>Form Type:</strong> {{ $formSubmission->form_type }}</p>
        <p><strong>Submitted on:</strong> {{ $formSubmission->submitted_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Forwarded on:</strong>
            {{ $formSubmission->reviewed_at ? $formSubmission->reviewed_at->format('F j, Y \a\t g:i A') : now()->format('F j, Y \a\t g:i A') }}
        </p>
        <p><strong>Forwarded to:</strong> {{ $toEmail }}</p>
        @if (!empty($customMessage))
            <p><strong>Message:</strong> {{ $customMessage }}</p>
        @endif
    </div>

    <div class="message-content">
        <p class="text-justify">Your form has been sent to the designated recipient who will review and process it according
            to established procedures. The recipient has been notified and will handle your submission appropriately.</p>

        <p><strong>What to expect:</strong></p>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li>The recipient will review your form submission</li>
            <li>You may be contacted directly if additional information is needed</li>
            <li>Processing will continue according to standard timelines</li>
            <li>You will receive updates on the status as appropriate</li>
        </ul>

        <p class="text-justify">No further action is required from you at this time. Thank you for using the CTET-CTSuL
            Inventory System and for your patience during the review process.</p>
    </div>
@endsection
