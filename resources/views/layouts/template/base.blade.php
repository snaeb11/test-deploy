<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title>@yield('title') | {{ config('app.name', 'Research Niche') }} </title>
        <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;600;700&display=swap"
            rel="stylesheet">
        <link rel="icon" href="{{ asset('assets/ctet-logo.png') }}" type="image/x-icon">
        <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>

    <body @isset($cssClass) class="{{ $cssClass }} flex flex-col min-h-screen" @endisset>
        @yield('childContent')

        <x-popups.login-successful-m />
        <x-popups.login-failed-m />
        <x-popups.email-verified-m />
        <x-popups.first-time-user-login :message="session('verification_message')" />

        @if (session('showLoginSuccessModal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('login-success-modal');
                    const msgEl = document.getElementById('ls-message');
                    modal.style.display = 'flex';
                    if (msgEl) {
                        msgEl.textContent = @json(session('login_success_message', 'Login successful.'));
                    }
                    setTimeout(() => modal.style.display = 'none', 2000);
                });
            </script>
        @endif

        @if (session('showLoginFailModal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    const modal = document.getElementById('login-fail-modal');
                    const msgEl = document.getElementById('lf-message');
                    modal.style.display = 'flex';
                    if (msgEl) {
                        msgEl.textContent = @json(session('login_fail_message', 'Login failed.'));
                    }
                    setTimeout(() => modal.style.display = 'none', 3000);
                });
            </script>
        @endif

        @if (session('showVerificationModal'))
            <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let modal = document.getElementById('first-time-user-login-popup');
                    modal.style.display = 'flex';
                });
            </script>
        @endif

        @if (session('showForgotPasswordSuccessModal'))
            <x-popups.forgot-password-success message="{{ session('forgot_password_success_message') }}" />
        @endif

        @if (session('showForgotPasswordFailModal'))
            <x-popups.forgot-password-fail message="{{ session('forgot_password_fail_message') }}" />
        @endif

        @if (session('showResetPasswordSuccessModal'))
            <x-popups.reset-password-success message="{{ session('reset_password_success_message') }}" />
        @endif

        @if (session('showResetPasswordFailModal'))
            <x-popups.reset-password-fail message="{{ session('reset_password_fail_message') }}" />
        @endif

    </body>

</html>
