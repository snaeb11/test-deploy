@extends('emails.layouts.base')

@section('title', 'Form Submission Forwarded | CTET-CTSuL')

@section('header-title', 'Forwarded: Form Submission')

@section('content')
    <div class="greeting">
        Greetings,
    </div>

    <div class="message-content">
        <p class="text-justify">A form submission has been <span style="color: #007bff; font-weight: 600;">forwarded</span> to
            you from the CTET-CTSuL Inventory System for your review and processing.</p>
    </div>

    <div class="info-box">
        <p><strong>Form Type:</strong> {{ $formSubmission->form_type }}</p>
        <p><strong>Submitted by:</strong> {{ $formSubmission->submitter->full_name }}</p>
        <p><strong>Submitted on:</strong> {{ $formSubmission->submitted_at->format('F j, Y \a\t g:i A') }}</p>
        @if (!empty($customMessage))
            <p><strong>Forwarding Message:</strong> {{ $customMessage }}</p>
        @endif
    </div>

    <div class="message-content">
        <p class="text-justify">Please review the form submission at your earliest convenience. The attached file is below
            and can be viewed or downloaded directly.</p>
    </div>
@endsection
