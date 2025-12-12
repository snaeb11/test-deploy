@extends('emails.layouts.base')

@section('title', 'Form Approved | CTET-CTSuL')

@section('header-title', 'Form Submission Approved')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p class="text-justify"><strong>Great news!</strong> Your form submission has been reviewed and <span
                style="color: #28a745; font-weight: 600;">approved</span> by the administration.</p>
    </div>

    <div class="info-box">
        <p><strong>Form Type:</strong> {{ $formSubmission->form_type }}</p>
        <p><strong>Submitted on:</strong> {{ $formSubmission->submitted_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Approved on:</strong> {{ $formSubmission->reviewed_at->format('F j, Y \a\t g:i A') }}</p>
        @if ($formSubmission->review_remarks)
            <p><strong>Reviewer Comments:</strong> {{ $formSubmission->review_remarks }}</p>
        @endif
    </div>

    <div class="message-content">
        <p class="text-justify">Your form has been processed successfully and any necessary actions will be taken by the
            appropriate department. You should expect to receive further communication if any additional steps are required
            on your part.</p>

        <p><strong>What happens next:</strong></p>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li>Your form has been forwarded to the relevant department or office</li>
            <li>Processing will begin according to standard procedures</li>
            <li>You will be notified of any updates or required actions</li>
            <li>Keep this email for your records</li>
        </ul>

        <p class="text-justify">Thank you for using the CTET-CTSuL Inventory System for your form submission. We appreciate
            your contribution to maintaining efficient administrative processes.</p>
    </div>
@endsection
