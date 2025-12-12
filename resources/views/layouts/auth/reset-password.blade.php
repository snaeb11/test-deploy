@extends('layouts.template.base', ['cssClass' => 'bg-[#fdfdfd]'])
@section('title', 'Reset Password')

@section('childContent')
    <x-layout-partials.header />

    <x-popups.reset-password-success />
    <x-popups.reset-password-fail />

    <form id="reset-password-form" class="-mt-8 flex w-full flex-grow items-center justify-center">
        @csrf

        <input type="hidden" name="token" value="{{ $token }}">

        <div class="flex flex-col items-center justify-center space-y-8 px-4 md:px-8">
            <div class="flex flex-col items-center">
                <span class="text-[clamp(16px,2.5vw,32px)] font-bold text-[#575757]">Reset Your Password</span>
                <span class="text-[clamp(13px,2vw,20px)] font-light text-[#575757]">Enter your new password below.</span>
            </div>

            <div class="flex flex-col gap-4">
                <div class="flex flex-row items-center gap-1.5">
                    <input id="reset-email" type="email" name="email" value="{{ $email ?? old('email') }}" readonly
                        class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] bg-gray-100 px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] focus:outline-none md:w-[300px] lg:w-[20vw]" />
                </div>

                <div class="flex flex-row items-center gap-1.5">
                    <input id="reset-new-password" type="password" name="password" placeholder="New Password"
                        class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] focus:outline-none md:w-[300px] lg:w-[20vw]"
                        required />

                    <div>
                        <div id="reset-password-help" class="group relative">
                            <svg id="reset-help-icon" xmlns="http://www.w3.org/2000/svg"
                                class="h-5 w-5 cursor-pointer text-[#575757]" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <div
                                class="absolute bottom-full right-0 z-10 mb-2 hidden w-64 rounded-lg border border-[#575757] bg-white p-2 text-sm text-[#575757] shadow-lg group-hover:block">
                                Password must contain:
                                <ul class="mt-1 list-disc pl-5">
                                    <li id="reset-length-req">At least 8 characters</li>
                                    <li id="reset-upper-req">One uppercase letter</li>
                                    <li id="reset-lower-req">One lowercase letter</li>
                                    <li id="reset-number-req">One number</li>
                                    <li id="reset-special-req">One special character</li>
                                </ul>
                            </div>
                        </div>
                        <div id="reset-password-validation" class="hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col">
                    <div class="flex flex-row items-center gap-1.5">
                        <input id="reset-confirm-password" type="password" name="password_confirmation"
                            placeholder="Confirm Password"
                            class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] focus:outline-none md:w-[300px] lg:w-[20vw]"
                            required />
                        <div id="reset-confirm-validation" class="hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                fill="currentColor">
                                <path fill-rule="evenodd"
                                    d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                    clip-rule="evenodd" />
                            </svg>
                        </div>
                    </div>

                    <label class="mt-2 flex items-center justify-end space-x-2 text-sm font-light text-[#575757]">
                        <input type="checkbox" id="reset-show-password"
                            class="h-4 w-4 accent-[#575757] hover:cursor-pointer" />
                        <span class="hover:cursor-pointer">Show password</span>
                    </label>
                </div>
            </div>

            <div class="flex flex-col items-center space-y-2">
                <button id="reset-submit-btn" type="submit"
                    class="w-full max-w-xs rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-6 py-3 font-semibold text-[#fdfdfd] transition duration-200 hover:cursor-pointer hover:brightness-110">
                    Reset Password
                </button>
                <a href="{{ route('login') }}"
                    class="text-sm font-light text-[#575757] underline transition duration-150 hover:cursor-pointer hover:text-[#9D3E3E]">
                    Back to Login
                </a>
            </div>
        </div>
    </form>

    <div class="mt-4 h-11"></div>
    <x-layout-partials.footer />

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.getElementById('reset-password-form');
            const newPassword = document.getElementById('reset-new-password');
            const confirmPassword = document.getElementById('reset-confirm-password');
            const showPasswordToggle = document.getElementById('reset-show-password');
            const passwordTooltip = document.querySelector('#reset-password-help div');

            const requirements = {
                length: document.getElementById('reset-length-req'),
                upper: document.getElementById('reset-upper-req'),
                lower: document.getElementById('reset-lower-req'),
                number: document.getElementById('reset-number-req'),
                special: document.getElementById('reset-special-req')
            };

            const icons = {
                help: document.getElementById('reset-help-icon'),
                passwordValidation: document.getElementById('reset-password-validation'),
                confirmValidation: document.getElementById('reset-confirm-validation')
            };

            showPasswordToggle.addEventListener('change', () => {
                const type = showPasswordToggle.checked ? 'text' : 'password';
                newPassword.type = type;
                confirmPassword.type = type;
            });

            newPassword.addEventListener('input', validatePassword);
            confirmPassword.addEventListener('input', checkPasswordMatch);

            function validatePassword() {
                const password = newPassword.value;

                const validations = {
                    length: password.length >= 8,
                    upper: /[A-Z]/.test(password),
                    lower: /[a-z]/.test(password),
                    number: /[0-9]/.test(password),
                    special: /[^A-Za-z0-9]/.test(password)
                };

                Object.keys(requirements).forEach(key => {
                    requirements[key].style.color = validations[key] ? '#16a34a' : '#575757';
                });

                const allValid = Object.values(validations).every(Boolean);

                if (password && allValid) {
                    icons.passwordValidation.classList.remove('hidden');
                    icons.help.classList.add('hidden');
                } else {
                    icons.passwordValidation.classList.add('hidden');
                    icons.help.classList.remove('hidden');
                    passwordTooltip.classList.remove('hidden');
                    setTimeout(() => passwordTooltip.classList.add('hidden'), 8000);
                }

                checkPasswordMatch();
            }

            function checkPasswordMatch() {
                if (newPassword.value && confirmPassword.value) {
                    if (newPassword.value === confirmPassword.value) {
                        icons.confirmValidation.classList.remove('hidden');
                        confirmPassword.setCustomValidity('');
                    } else {
                        icons.confirmValidation.classList.add('hidden');
                        confirmPassword.setCustomValidity('Passwords do not match');
                    }
                } else {
                    icons.confirmValidation.classList.add('hidden');
                    confirmPassword.setCustomValidity(confirmPassword.required ?
                        'Please confirm your password' : '');
                }
            }

            // Form submission handling
            document.getElementById('reset-password-form').addEventListener('submit', async function(e) {
                e.preventDefault();

                const form = e.target;
                const submitBtn = document.getElementById('reset-submit-btn');
                const email = document.getElementById('reset-email').value;
                const password = document.getElementById('reset-new-password').value;
                const confirmPassword = document.getElementById('reset-confirm-password').value;

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                    <span class="inline-flex items-center">
                        <svg class="mr-2 h-4 w-4 animate-spin" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Processing...
                    </span>
                `;

                try {
                    const response = await fetch("{{ route('password.reset.update') }}", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/json",
                            "X-CSRF-TOKEN": "{{ csrf_token() }}",
                            "Accept": "application/json"
                        },
                        body: JSON.stringify({
                            token: "{{ $token }}",
                            email: email,
                            password: password,
                            password_confirmation: confirmPassword
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        // Handle different error cases
                        let errorTitle = 'Password Reset Failed';
                        let errorMessage = 'An error occurred while resetting your password.';

                        if (data.errors) {
                            if (data.errors.email) {
                                errorTitle = 'Invalid Email';
                                errorMessage = data.errors.email[0];
                            } else if (data.errors.password) {
                                errorTitle = 'Invalid Password';
                                errorMessage = data.errors.password[0];
                            }
                        } else if (data.message) {
                            // Custom error messages from backend
                            if (data.message.toLowerCase().includes('token')) {
                                errorTitle = 'Invalid Reset Link';
                                errorMessage = 'This password reset link is invalid or has expired.';
                            } else if (data.message.toLowerCase().includes('email')) {
                                errorTitle = 'Account Not Found';
                                errorMessage = 'No user found with this email address.';
                            } else {
                                errorMessage = data.message;
                            }
                        }

                        // Show error modal
                        const failModal = document.getElementById('reset-password-fail-modal');
                        const failTitle = document.getElementById('rp-fail-title');
                        const failMessage = document.getElementById('rp-fail-message');

                        failTitle.textContent = errorTitle;
                        failMessage.textContent = errorMessage;
                        failModal.style.display = 'flex';
                        return;
                    }

                    // Show success modal
                    const successModal = document.getElementById('reset-password-success-modal');
                    successModal.style.display = 'flex';

                } catch (error) {
                    // Show error modal for network errors
                    const failModal = document.getElementById('reset-password-fail-modal');
                    const failTitle = document.getElementById('rp-fail-title');
                    const failMessage = document.getElementById('rp-fail-message');

                    failTitle.textContent = 'Error';
                    failMessage.textContent = 'An error occurred. Please try again.';
                    failModal.style.display = 'flex';
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Reset Password';
                }
            });
        });
    </script>
@endsection
