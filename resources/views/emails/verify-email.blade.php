@extends('emails.layouts.base')

@section('title', 'Email Verification | CTET-CTSuL')

@section('header-title', 'Email Verification')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p class="text-justify">Welcome to the CTET-CTSuL Inventory System! To complete your account setup, please verify
            your email address using the 6-digit verification code below:</p>
    </div>

    <div class="verification-code">
        {{ $verificationCode }}
    </div>

    <div class="info-box">
        <p><strong>Important Notes:</strong></p>
        <p>• This verification code will expire in <strong>15 minutes</strong></p>
        <p>• Enter this code exactly as shown above</p>
        <p>• If you didn't request this verification, please ignore this email</p>
    </div>

    <div class="message-content">
        <p>Once verified, you'll have full access to submit and manage your thesis documents through the CTET-CTSuL
            Inventory System.</p>
        <p>If you're having trouble with the verification process, please contact your system administrator for assistance.
        </p>
    </div>
@endsection
