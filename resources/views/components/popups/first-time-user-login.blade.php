<!-- Wrapper for the modal -->
<div id="first-time-user-login-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4" aria-modal="true" role="dialog">
    <!-- 🔹 CHANGED: responsive width & padding -->
    <div class="relative max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 sm:p-8 shadow-xl overflow-y-auto">

        <!-- X Button -->
        <button id="ftul-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500"
            aria-label="Close verification modal">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div id="ftul-step1" class="mt-0 flex flex-col items-center justify-center space-y-6">
            <!-- Check Icon -->
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="#575757" class="h-16 w-16 sm:h-20 sm:w-20">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                </svg>
            </div>

            <!-- Main Message -->
            <h2 class="text-center text-lg font-medium text-[#575757] sm:text-xl">
                Verify Your Email
            </h2>

            <!-- Subtext -->
            <div class="text-center text-sm font-light text-[#575757] sm:text-base">
                Please confirm your account by entering the security code sent to
                <span class="email-display font-semibold">
                    {{ session('verifying_email', '--email@usep.edu.ph--') }}
                </span>
            </div>

            <!-- Error Message -->
            <div id="verification-error" class="hidden text-center text-sm font-medium text-red-600"></div>

            <!-- Input and Buttons Wrapper -->
            <div class="flex w-full max-w-sm flex-col space-y-4 sm:w-[20vw]">
                <!-- Input Field -->
                <input type="text" id="verification-code" placeholder="Security code" inputmode="numeric"
                    maxlength="6"
                    class="code-input h-14 rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:border-[#D56C6C] focus:outline-none"
                    autocomplete="one-time-code" />

                <!-- Resend Code Link -->
                <div class="text-left">
                    <button id="resend-button"
                        class="text-sm font-light text-[#575757] underline transition duration-150 hover:text-[#9D3E3E]">
                        Resend code
                    </button>
                </div>

                <!-- Confirm Button -->
                <div class="flex justify-end">
                    <button id="ftul-confirm-btn" disabled
                        class="w-full sm:w-auto px-6 py-3 text-sm sm:text-base cursor-not-allowed rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] text-[#fdfdfd] opacity-50 transition duration-200">
                        
                        Submit code
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
    let isResending = false;
    let resendCooldown = null;

    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const popup = document.getElementById('first-time-user-login-popup');
        const resendButton = document.getElementById('resend-button');
        const closeButton = document.getElementById('ftul-close-popup');
        const codeInput = document.getElementById('verification-code');
        const submitButton = document.getElementById('ftul-confirm-btn');
        const errorDisplay = document.getElementById('verification-error');
        const emailVerifiedPopup = document.getElementById('email-verified-popup');
        const successConfirmBtn = document.getElementById('ev-confirm-btn');

        // State
        let resendCooldown = null;

        // Input validation
        codeInput.addEventListener('input', function() {
            // Only allow numbers and limit to 6 digits
            codeInput.value = codeInput.value.replace(/\D/g, '').slice(0, 6);

            const isValid = codeInput.value.length === 6;
            submitButton.disabled = !isValid;

            if (isValid) {
                submitButton.classList.remove('opacity-50', 'cursor-not-allowed');
                submitButton.classList.add('hover:from-[#f18e8e]', 'hover:to-[#d16868]');
            } else {
                submitButton.classList.add('opacity-50', 'cursor-not-allowed');
                submitButton.classList.remove('hover:from-[#f18e8e]', 'hover:to-[#d16868]');
            }

            // Clear any previous errors when typing
            errorDisplay.classList.add('hidden');
        });

        // Close popup
        closeButton.addEventListener('click', function() {
            popup.style.display = 'none';
        });

        // Resend code functionality
        resendButton.addEventListener('click', async function() {
            // Prevent multiple simultaneous requests
            if (isResending || resendCooldown) return;
            isResending = true;

            // Disable button immediately
            resendButton.disabled = true;
            let secondsLeft = 60;

            // Update button text
            const updateButtonText = () => {
                resendButton.textContent = `Resend code (${secondsLeft}s)`;
                resendButton.classList.remove('hover:text-[#9D3E3E]');
                resendButton.style.cursor = 'not-allowed';
            };
            updateButtonText();

            try {
                const response = await fetch("{{ route('verification.resend') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    credentials: "same-origin"
                });

                if (!response.ok) {
                    throw new Error('Failed to resend code');
                }

                const data = await response.json();
                if (data.error) {
                    throw new Error(data.error);
                }
            } catch (error) {
                showTemporaryMessage(error.message || 'Failed to resend code', 'text-red-600');
            } finally {
                isResending = false;

                // Start cooldown timer
                resendCooldown = setInterval(() => {
                    secondsLeft--;
                    updateButtonText();

                    if (secondsLeft <= 0) {
                        clearInterval(resendCooldown);
                        resendCooldown = null;
                        resendButton.textContent = 'Resend code';
                        resendButton.disabled = false;
                        resendButton.classList.add('hover:text-[#9D3E3E]');
                        resendButton.style.cursor = 'pointer';
                    }
                }, 1000);
            }
        });

        // Submit verification code
        submitButton.addEventListener('click', verifyCode);

        // Allow pressing Enter to submit
        codeInput.addEventListener('keydown', (e) => {
            if (e.key === 'Enter' && !submitButton.disabled) {
                verifyCode();
            }
        });

        function verifyCode() {
            const code = codeInput.value;
            const originalText = submitButton.innerHTML;

            submitButton.disabled = true;
            submitButton.innerHTML = `
        <span class="inline-flex items-center">
            <svg class="mr-2 h-4 w-4 animate-spin" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Verifying...
        </span>
    `;

            fetch("{{ route('verify.code') }}", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/json",
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json"
                    },
                    body: JSON.stringify({
                        code
                    }),
                    credentials: "same-origin"
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Verification failed');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.status === 'success') {
                        // Hide verification popup
                        popup.style.display = 'none';

                        // Show success modal if exists
                        const successModal = document.getElementById('email-verified-popup');
                        if (successModal) {
                            const welcomeMessage = document.getElementById('welcome-message');
                            if (welcomeMessage && data.first_name) {
                                welcomeMessage.textContent = `Welcome, ${data.first_name}!`;
                            }
                            successModal.style.display = 'flex';

                            // Redirect after delay
                            setTimeout(() => {
                                window.location.href = data.redirect;
                            }, 3000);
                        } else {
                            // Immediate redirect to the route provided by backend
                            window.location.href = data.redirect;
                        }
                    } else {
                        throw new Error(data.message || 'Verification failed');
                    }
                })
                .catch(error => {
                    showError(error.message || 'Verification failed. Please try again.');
                    codeInput.focus();
                })
                .finally(() => {
                    submitButton.disabled = false;
                    submitButton.innerHTML = originalText;
                });
        }

        function showError(message) {
            errorDisplay.textContent = message;
            errorDisplay.classList.remove('hidden');
        }

        function showTemporaryMessage(message, textClass) {
            errorDisplay.textContent = message;
            errorDisplay.className = `text-center text-sm font-medium ${textClass}`;
            errorDisplay.classList.remove('hidden');

            setTimeout(() => {
                errorDisplay.classList.add('hidden');
            }, 5000);
        }

        // Prevent closing with Escape key
        window.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') e.preventDefault();
        });
    });
</script>
