<div id="forgot-password-modal" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:p-8 md:min-w-[21vw] md:max-w-[25vw]">
        <!-- X Button -->
        <button id="forgot-password-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <form id="forgot-password-form" method="POST" action="{{ route('password.email') }}" novalidate
            class="flex flex-col space-y-6">
            @csrf
            <div id="form-errors" class="mb-4 hidden rounded-lg bg-red-50 p-4 text-red-600">
                <ul class="list-inside list-disc" id="errors-list"></ul>
            </div>

            <!-- Title -->
            <div class="flex flex-col items-center">
                <span class="text-lg font-medium text-[#575757] sm:text-xl">Forgot Password?</span>
                <span class="text-sm font-light text-[#575757] sm:text-base">Enter your USeP email to receive a reset
                    link.</span>
            </div>

            <!-- Email Input -->
            <div class="flex flex-col gap-2">
                <input type="text" id="fp-email-input" name="email" placeholder="USeP Email" required
                    autocomplete="email" maxlength="254" inputmode="email"
                    oninput="this.value = this.value
                        .toLowerCase()
                        .replace(/[\s]/g, '')
                        .replace(/[<>\"'`]/g, '')
                        .replace(/[\u0000-\u001F\u007F]/g, '')"
                    class="min-h-[45px] w-full rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:border-[#D56C6C] focus:outline-none" />
            </div>

            <!-- Buttons -->
            <div class="flex flex-col items-center space-y-4">
                <button type="submit" id="submit-btn"
                    class="w-full rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-8 py-3 font-semibold text-[#fdfdfd] transition duration-200 hover:brightness-110 sm:w-auto sm:px-10 sm:py-4">
                    Send Reset Link
                </button>
                <button type="button" id="forgot-password-back-btn"
                    class="text-sm font-light text-[#575757] underline transition duration-150 hover:text-[#9D3E3E]">
                    Back to Login
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Elements
        const forgotPasswordModal = document.getElementById('forgot-password-modal');
        const forgotPasswordBtn = document.getElementById('forgot-password-btn');
        const closeBtn = document.getElementById('forgot-password-close-popup');
        const backBtn = document.getElementById('forgot-password-back-btn');
        const form = document.getElementById('forgot-password-form');
        const submitBtn = document.getElementById('submit-btn');

        // Modals
        const successModal = document.getElementById('forgot-password-success-modal');
        const failModal = document.getElementById('forgot-password-fail-modal');
        const successMessage = document.getElementById('fp-success-title');
        const failMessage = document.getElementById('fp-fail-title');
        const failSubtext = document.getElementById('fp-fail-subtext');

        // Show/hide functions
        function showModal(modal) {
            forgotPasswordModal.style.display = 'none';
            modal.style.display = 'flex';
        }

        function hideModal(modal) {
            modal.style.display = 'none';
        }

        // Event listeners
        if (forgotPasswordBtn) {
            forgotPasswordBtn.addEventListener('click', function() {
                forgotPasswordModal.style.display = 'flex';
            });
        }

        if (closeBtn) closeBtn.addEventListener('click', () => hideModal(forgotPasswordModal));
        if (backBtn) backBtn.addEventListener('click', () => hideModal(forgotPasswordModal));

        // Form submission
        if (form) {
            form.addEventListener('submit', async function(e) {
                e.preventDefault();

                const originalBtnText = submitBtn.innerHTML;

                // Sanitize and validate email to avoid native tooltips
                const emailInput = document.getElementById('fp-email-input');
                let email = (emailInput?.value || '')
                    .toLowerCase()
                    .replace(/[\s]/g, '')
                    .replace(/[<>\"'`]/g, '')
                    .replace(/[\u0000-\u001F\u007F]/g, '');
                if (emailInput) emailInput.value = email;

                // Required check: must not be empty after sanitization
                if (!email) {
                    failMessage.textContent = 'Password Reset Request Failed';
                    if (failSubtext) failSubtext.textContent = 'Email is required.';
                    showModal(failModal);
                    return;
                }

                const usepRegex = /^[a-zA-Z0-9._%+-]+@usep\.edu\.ph$/;
                if (!usepRegex.test(email)) {
                    failMessage.textContent = 'Password Reset Request Failed';
                    if (failSubtext) failSubtext.textContent =
                        'Please enter a valid USeP email address.';
                    showModal(failModal);
                    return;
                }

                const formData = new FormData(form);

                // Show loading state
                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                <span class="inline-flex items-center">
                    <svg class="mr-2 h-4 w-4 animate-spin" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Sending...
                </span>
            `;

                try {
                    const response = await fetch(form.action, {
                        method: 'POST',
                        body: formData,
                        headers: {
                            'Accept': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest'
                        }
                    });

                    const data = await response.json();

                    if (response.ok) {
                        // Success case
                        hideModal(forgotPasswordModal);
                        successMessage.textContent = data.message ||
                            'If your email exists in our system, you will receive a password reset link shortly.';
                        showModal(successModal);
                    } else {
                        // Error cases
                        if (response.status === 422) {
                            // Validation error - show in fail modal
                            failMessage.textContent = 'Password Reset Request Failed';
                            const errorMsg = data.errors?.email?.[0] || data.message ||
                                'Invalid email format';
                            if (failSubtext) failSubtext.textContent = errorMsg;
                        } else {
                            // Other errors
                            failMessage.textContent = 'Password Reset Request Failed';
                            if (failSubtext) failSubtext.textContent = data.message ||
                                'Failed to send reset link';
                        }
                        showModal(failModal);
                    }
                } catch (error) {
                    // Network error
                    failMessage.textContent = 'Password Reset Request Failed';
                    if (failSubtext) failSubtext.textContent = 'Network error. Please try again.';
                    showModal(failModal);
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
            });
        }

        // Close modal handlers
        document.querySelectorAll('#fp-success-confirm-btn, #fp-fail-confirm-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                hideModal(this.closest('.fixed'));
            });
        });

        // Close modals when clicking outside
        document.querySelectorAll('.fixed').forEach(modal => {
            modal.addEventListener('click', function(e) {
                if (e.target === this) {
                    hideModal(this);
                }
            });
        });

        // Close modals with Escape key
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                document.querySelectorAll('.fixed').forEach(modal => {
                    if (modal.style.display === 'flex') {
                        hideModal(modal);
                    }
                });
            }
        });
    });
</script>
