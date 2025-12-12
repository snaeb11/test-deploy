@extends('emails.layouts.base')

@section('title', 'Thesis Approved | CTET-CTSuL')

@section('header-title', 'Thesis Submission Approved')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p class="text-justify"><strong>Congratulations!</strong> We are pleased to inform you that your thesis submission
            has been reviewed and
            <span style="color: #28a745; font-weight: 600;">approved</span> for inclusion in the CTET-CTSuL Inventory System.
        </p>
    </div>

    <div class="info-box">
        <p><strong>Thesis Title:</strong> {{ $submission->title }}</p>
        <p><strong>Submitted on:</strong> {{ $submission->submitted_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Approved on:</strong> {{ $submission->reviewed_at->format('F j, Y \a\t g:i A') }}</p>
        @if ($submission->remarks)
            <p><strong>Reviewer Comments:</strong> {{ $submission->remarks }}</p>
        @endif
    </div>

    <div class="message-content">
        <p class="text-justify">Your thesis has now been added to the official CTET-CTSuL Inventory System and will be
            available for academic reference and research purposes. This is a significant achievement that contributes to
            the academic knowledge base of the University of Southeastern Philippines.</p>

        <p><strong>What happens next:</strong></p>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li>Your thesis is now part of the official university repository</li>
            <li>It will be accessible to future researchers and students</li>
        </ul>

        <p class="text-justify">Thank you for your valuable contribution to the academic community and for using the
            CTET-CTSuL Inventory System for your thesis submission.</p>
    </div>
@endsection
