@extends('emails.layouts.base')

@section('title', 'Reset Your Password | CTET-CTSuL')

@section('header-title', 'Password Reset')

@section('content')
    <div class="greeting">
        Dear {{ $user->full_name }},
    </div>

    <div class="message-content">
        <p>You are receiving this email because we received a password reset request for your CTET-CTSuL Inventory System
            account.</p>

        <p>To reset your password, click the button below. This link will be valid for <strong>60 minutes</strong> from the
            time this email was sent.</p>
    </div>

    <div class="action-button">
        <a href="{{ $resetUrl }}">Reset Your Password</a>
    </div>

    <div class="info-box">
        <p><strong>Security Information:</strong></p>
        <p>• This password reset link will expire in 60 minutes</p>
        <p>• If you did not request a password reset, no further action is required</p>
        <p>• For security reasons, please do not share this link with anyone</p>
        <p>• Make sure to choose a strong, unique password</p>
    </div>

    <div class="message-content">
        <p>If you're having trouble clicking the "Reset Your Password" button, copy and paste the URL below into your web
            browser:</p>
        <p
            style="word-break: break-all; color: #666; font-size: 14px; background-color: #f8f9fa; padding: 10px; border-radius: 4px;">
            {{ $resetUrl }}
        </p>

        <p>If you continue to have issues accessing your account, please contact your system administrator for assistance.
        </p>
    </div>
@endsection
