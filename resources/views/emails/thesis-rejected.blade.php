@extends('emails.layouts.base')

@section('title', 'Thesis Submission Review | CTET-CTSuL')

@section('header-title', 'Thesis Submission Review')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p class="text-justify">Thank you for your thesis submission to the CTET-CTSuL Inventory System. After careful
            review, we need to request some revisions before your submission can be approved.</p>
    </div>

    <div class="info-box">
        <p><strong>Thesis Title:</strong> {{ $submission->title }}</p>
        <p><strong>Submitted on:</strong> {{ $submission->submitted_at->format('F j, Y \a\t g:i A') }}</p>
        <p><strong>Reviewed on:</strong> {{ $submission->reviewed_at->format('F j, Y \a\t g:i A') }}</p>
        @if ($submission->remarks)
            <p><strong>Reviewer Comments:</strong> {{ $submission->remarks }}</p>
        @endif
    </div>

    <div class="message-content">
        <p><strong>Next Steps:</strong></p>
        <ul style="margin: 15px 0; padding-left: 20px;">
            <li>Please review the comments provided by the reviewer</li>
            <li>Make the necessary revisions to your thesis document</li>
            <li>Resubmit your thesis through the CTET-CTSuL Inventory System when ready</li>
            <li>Contact your adviser if you need guidance on the required changes</li>
        </ul>

        <p class="text-justify">We appreciate your understanding and look forward to receiving your revised submission. The
            review process helps ensure the quality and consistency of all materials in our thesis inventory.</p>

        <p class="text-justify">If you have any questions about the review comments or need assistance with the resubmission
            process, please don't hesitate to contact your system administrator.</p>
    </div>
@endsection
