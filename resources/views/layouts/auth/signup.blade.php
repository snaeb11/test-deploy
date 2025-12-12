@extends('layouts.template.base', ['cssClass' => 'bg-[#fdfdfd]'])
@section('title', 'Sign up')

@section('childContent')
    <x-layout-partials.header />

    <!-- Popup Modals -->
    <x-popups.email-alr-tkn-m />
    <x-popups.email-invalid-m />
    <x-popups.account-creation-successful-m />
    <x-popups.first-time-user-login />

    <form method="POST" action="{{ route('signup.store') }}" class="-mt-8 flex w-full flex-grow items-center justify-center">
        @csrf
        <div class="flex flex-col items-center justify-center space-y-8 px-4 md:px-8">
            <!-- Title -->
            <div class="flex flex-col items-center">
                <span class="text-[clamp(18px,3vw,36px)] font-bold text-[#575757]">Welcome!</span>
                <span class="text-[clamp(14px,2vw,24px)] font-light text-[#575757]">create an account</span>
            </div>

            <!-- Grid: 2x3 Inputs -->
            <div class="flex flex-col gap-4 lg:flex-row">
                <!-- LEFT COLUMN -->
                <div class="flex flex-col gap-4">

                    <div>
                        <input type="text" id="first_name" name="first_name" placeholder="First Name"
                            value="{{ old('first_name') }}"
                            class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none md:w-[300px] lg:w-[20vw]"
                            maxlength="50" inputmode="text" autocomplete="given-name"
                            pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]{1,50}$" required />
                    </div>

                    <div>
                        <input type="text" id="last_name" name="last_name" placeholder="Last Name"
                            value="{{ old('last_name') }}"
                            class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none md:w-[300px] lg:w-[20vw]"
                            maxlength="50" inputmode="text" autocomplete="family-name"
                            pattern="^[A-Za-zÀ-ÖØ-öø-ÿ\-\s]{1,50}$" required />
                    </div>

                    <div>
                        <div class="relative w-[min(90vw,360px)] md:w-[300px] lg:w-[20vw]">
                            <select id="program-select" name="program_id"
                                class="min-h-[45px] w-full appearance-none rounded-[10px] border border-[#575757] px-4 pr-10 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] transition-colors duration-200 focus:outline-none"
                                required>
                                <option value="" disabled selected>Select your program</option>

                                @if ($undergraduate->isNotEmpty())
                                    <optgroup label="Undergraduate Programs">
                                        @foreach ($undergraduate as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($graduate->isNotEmpty())
                                    <optgroup label="Graduate Programs">
                                        @foreach ($graduate as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
                            </select>
                            <div
                                class="pointer-events-none absolute right-3 top-1/2 -translate-y-1/2 transform text-[#575757]">
                                <svg class="h-4 w-4 md:h-5 md:w-5" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- RIGHT COLUMN -->
                <div class="flex flex-col gap-4">
                    <!-- Email Field with Help Icon -->
                    <div class="flex flex-row items-center gap-1.5">
                        <input type="email" name="email" id="email" placeholder="USeP Email"
                            value="{{ old('email') }}"
                            class="peer min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none md:w-[300px] lg:w-[20vw]"
                            required />

                        <div>
                            <!-- Help Icon -->
                            <div id="email-help-icon" class="group visible relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 cursor-pointer text-[#575757]"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div
                                    class="absolute bottom-full right-0 z-10 mb-2 hidden w-64 rounded-lg border border-[#575757] bg-white p-2 text-sm text-[#575757] shadow-lg group-hover:block">
                                    Must be a valid USeP email address (e.g., username@usep.edu.ph)
                                </div>
                            </div>
                            <!-- Validation Icon -->
                            <div id="email-validation" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Password Field with Help Icon -->
                    <div class="flex flex-row items-center gap-1.5">
                        <input id="password" type="password" name="password" placeholder="Password"
                            class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none md:w-[300px] lg:w-[20vw]"
                            required />

                        <div>
                            <!-- Help Icon -->
                            <div id="password-help-icon" class="group visible relative">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 cursor-pointer text-[#575757]"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <div
                                    class="absolute bottom-full right-0 z-10 mb-2 hidden w-64 rounded-lg border border-[#575757] bg-white p-2 text-sm text-[#575757] shadow-lg group-hover:block">
                                    Password must contain:
                                    <ul class="mt-1 list-disc pl-5">
                                        <li id="length-req">At least 8 characters</li>
                                        <li id="upper-req">One uppercase letter</li>
                                        <li id="lower-req">One lowercase letter</li>
                                        <li id="number-req">One number</li>
                                        <li id="special-req">One special character</li>
                                    </ul>
                                </div>
                            </div>
                            <!-- Validation Icon -->
                            <div id="password-validation" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm Password Field -->
                    <div class="flex flex-col">
                        <!-- Password Field with Help Icon -->
                        <div class="flex flex-row items-center gap-1.5">
                            <input id="confirm-password" type="password" name="password_confirmation"
                                placeholder="Confirm password"
                                class="min-h-[45px] w-[min(90vw,360px)] rounded-[10px] border border-[#575757] px-4 text-[clamp(14px,1.2vw,18px)] font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none md:w-[300px] lg:w-[20vw]"
                                required />

                            <!-- Validation Icon -->
                            <div id="confirm-password-validation" class="hidden">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-green-500"
                                    viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"
                                        clip-rule="evenodd" />
                                </svg>
                            </div>
                        </div>
                        <label
                            class="mt-2 flex items-center justify-end space-x-2 text-[clamp(12px,1vw,16px)] text-sm font-light text-[#575757]">
                            <input type="checkbox" id="show-password-toggle"
                                class="h-4 w-4 accent-[#575757] hover:cursor-pointer"
                                onclick="togglePasswordVisibility()" />
                            <span class="hover:cursor-pointer">Show password</span>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Centered Buttons Below -->
            <div class="flex flex-col items-center space-y-2">
                <button
                    class="w-full max-w-xs rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-6 py-3 font-semibold text-[#fdfdfd] transition duration-200 hover:cursor-pointer hover:brightness-110">
                    Create account
                </button>
                <a href="{{ route('login') }}"
                    class="text-sm font-light text-[#575757] underline transition duration-150 hover:cursor-pointer hover:text-[#9D3E3E]">
                    Already have an account?
                </a>
            </div>
        </div>
    </form>
    <x-layout-partials.footer />

    <script>
        function togglePasswordVisibility() {
            const password = document.getElementById('password');
            const confirmPassword = document.getElementById('confirm-password');
            const toggle = document.getElementById('show-password-toggle');

            if (toggle.checked) {
                password.type = 'text';
                confirmPassword.type = 'text';
            } else {
                password.type = 'password';
                confirmPassword.type = 'password';
            }
        }

        // Email validation
        const emailInput = document.getElementById('email');
        const emailValidation = document.getElementById('email-validation');

        emailInput.addEventListener('input', function() {
            const isValid = /^[a-zA-Z0-9._%+-]+@usep\.edu\.ph$/.test(this.value);
            const emailHelpIcon = document.getElementById('email-help-icon').querySelector('svg');

            if (this.value && isValid) {
                emailValidation.classList.remove('hidden');
                emailHelpIcon.classList.add('hidden');
            } else {
                emailValidation.classList.add('hidden');
                emailHelpIcon.classList.remove('hidden');
            }
        });

        // Password validation
        const passwordInput = document.getElementById('password');
        const passwordLength = document.getElementById('length-req');
        const passwordUppercase = document.getElementById('upper-req');
        const passwordLowercase = document.getElementById('lower-req');
        const passwordNumber = document.getElementById('number-req');
        const passwordSpecial = document.getElementById('special-req');
        const passwordValidation = document.getElementById('password-validation');
        const passwordHelpIcon = document.getElementById('password-help-icon').querySelector('svg');
        const passwordTooltip = document.querySelector('#password-help-icon div');

        // Password match elements
        const confirmPasswordInput = document.getElementById('confirm-password');
        const confirmPasswordValidation = document.getElementById('confirm-password-validation');
        const form = document.querySelector('form');

        function checkPasswordRequirements() {
            const password = passwordInput.value;
            return {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };
        }

        passwordInput.addEventListener('input', function() {
            const requirements = checkPasswordRequirements();
            const allValid = Object.values(requirements).every(met => met);

            // Update requirement text colors
            passwordLength.style.color = requirements.length ? '#16a34a' : '#575757';
            passwordUppercase.style.color = requirements.uppercase ? '#16a34a' : '#575757';
            passwordLowercase.style.color = requirements.lowercase ? '#16a34a' : '#575757';
            passwordNumber.style.color = requirements.number ? '#16a34a' : '#575757';
            passwordSpecial.style.color = requirements.special ? '#16a34a' : '#575757';

            // Show validation icon when all requirements are met
            if (passwordInput.value && allValid) {
                passwordValidation.classList.remove('hidden');
                passwordHelpIcon.classList.add('hidden');
            } else {
                passwordValidation.classList.add('hidden');
                passwordHelpIcon.classList.remove('hidden');
                passwordInput.setCustomValidity('');
            }
        });

        function checkPasswordMatch() {
            if (passwordInput.value && confirmPasswordInput.value) {
                if (passwordInput.value === confirmPasswordInput.value) {
                    confirmPasswordValidation.classList.remove('hidden');
                    confirmPasswordInput.setCustomValidity('');
                } else {
                    confirmPasswordValidation.classList.add('hidden');
                    confirmPasswordInput.setCustomValidity('Passwords do not match');
                }
            } else {
                confirmPasswordValidation.classList.add('hidden');
                confirmPasswordInput.setCustomValidity(confirmPasswordInput.required ? 'Please confirm your password' : '');
            }
        }

        passwordInput.addEventListener('input', checkPasswordMatch);
        confirmPasswordInput.addEventListener('input', checkPasswordMatch);

        // Form submission handler
        form.addEventListener('submit', function(event) {
            const requirements = checkPasswordRequirements();
            const passwordValid = Object.values(requirements).every(met => met);

            if (!passwordValid) {
                event.preventDefault();
                passwordTooltip.classList.remove('hidden');
                setTimeout(() => passwordTooltip.classList.add('hidden'), 8000);
                passwordInput.focus();
                return;
            }
        });

        document.addEventListener('DOMContentLoaded', () => {
            // NAME INPUT SANITIZATION
            const firstNameInput = document.getElementById('first_name');
            const lastNameInput = document.getElementById('last_name');

            const sanitizeNameValue = (value) => {
                // Remove anything except letters (incl. accents), spaces, and hyphens
                let cleaned = value.replace(/[^A-Za-zÀ-ÖØ-öø-ÿ\-\s]/g, '');
                // Collapse multiple spaces and trim
                cleaned = cleaned.replace(/\s+/g, ' ').trimStart();
                return cleaned;
            };

            const attachNameSanitizer = (el) => {
                if (!el) return;
                el.addEventListener('input', (e) => {
                    const sanitized = sanitizeNameValue(e.target.value);
                    if (sanitized !== e.target.value) {
                        e.target.value = sanitized;
                    }
                });
                el.addEventListener('blur', (e) => {
                    e.target.value = sanitizeNameValue(e.target.value).trim();
                });
            };

            attachNameSanitizer(firstNameInput);
            attachNameSanitizer(lastNameInput);

            // EMAIL ALREADY TAKEN MODAL
            @if (session('email_taken'))
                document.getElementById('email-taken-popup').style.display = 'flex';
            @endif

            // EMAIL INVALID MODAL
            @if (session('invalid_email'))
                const popup = document.getElementById('email-invalid-popup');
                if (popup) popup.style.display = 'flex';
            @endif

            // ACCOUNT CREATION SUCCESS MODAL
            @if (session('account_created'))
                const successPopup = document.getElementById('account-creation-succ-popup');
                const nameSpan = document.getElementById('account-name');
                const emailSpan = document.getElementById('user-email');

                successPopup.style.display = 'flex';

                @if (session('account_name'))
                    nameSpan.textContent = @json(session('account_name'));
                @endif

                // Handle success modal confirm button
                document.getElementById('acs-confirm-btn').addEventListener('click', function() {
                    successPopup.style.display = 'none';

                    @if (session('show_verification'))
                        document.getElementById('first-time-user-login-popup').style.display = 'flex';
                    @endif
                });
            @endif
        });
    </script>
@endsection
