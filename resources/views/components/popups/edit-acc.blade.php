<x-popups.user-edit-acc-success />
<x-popups.user-edit-acc-fail />

<div id="edit-account-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div id="aea-step1" class="relative max-h-[90vh] w-[700px] rounded-2xl bg-[#fdfdfd] p-8 shadow-xl">
        <!-- Close Button -->
        <button id="uea-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-3 text-2xl font-bold text-gray-900">Edit Your Account</h2>
            <p class="text-normal font-regular text-center">Update your personal information</p>
        </div>

        <form id="admin-edit-form" class="mt-2 space-y-6" method="POST" action="{{ route('admin.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <!-- First Name -->
                <label for="aea-first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input id="aea-first-name" name="first_name" type="text" value="{{ $user->decrypted_first_name }}"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />
                <div id="first-name-error" class="hidden text-sm text-red-500"></div>

                <!-- Last Name -->
                <label for="aea-last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input id="aea-last-name" name="last_name" type="text" value="{{ $user->decrypted_last_name }}"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />
                <div id="last-name-error" class="hidden text-sm text-red-500"></div>

                <!-- Email -->
                <label for="aea-usep-email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="aea-usep-email" type="email" value="{{ $user->email }}" readonly
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none" />

                <!-- Password Change Section -->
                <div class="pt-4">
                    <div class="flex items-center justify-end">
                        <button type="button" id="toggle-password-change"
                            class="bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] bg-clip-text text-sm text-transparent hover:from-[#9D3E3E] hover:to-[#D56C6C]">
                            Change Password
                        </button>
                    </div>

                    <div id="password-fields" class="mt-2 hidden space-y-1">
                        <!-- Current Password -->
                        <label for="current-password" class="block text-sm font-medium text-gray-700">Current
                            Password</label>
                        <input id="current-password" name="current_password" type="password"
                            placeholder="Input Current Password"
                            class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none" />
                        <div id="current-password-error" class="hidden text-sm text-red-500"></div>

                        <!-- New Password -->
                        <label for="new-password" class="block text-sm font-medium text-gray-700">New Password</label>
                        <input id="new-password" name="new_password" type="password" placeholder="Input New Password"
                            class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none" />
                        <div id="new-password-error" class="hidden text-sm text-red-500"></div>

                        <!-- Confirm New Password -->
                        <label for="new-password-confirmation" class="block text-sm font-medium text-gray-700">Confirm
                            New Password</label>
                        <input id="new-password-confirmation" name="new_password_confirmation" type="password"
                            placeholder="Confirm New Password"
                            class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none" />
                        <div id="confirm-password-error" class="hidden text-sm text-red-500"></div>

                        <!-- Show Password -->
                        <label class="flex items-center justify-end space-x-2 text-sm font-light text-[#575757]">
                            <input type="checkbox" id="edit-show-password-toggle"
                                class="h-4 w-4 accent-[#575757] hover:cursor-pointer" />
                            <span class="hover:cursor-pointer">Show password</span>
                        </label>
                    </div>
                </div>
            </div>
            <div class="mt-10 flex justify-center space-x-4 sm:space-x-6">
                <button id="aea-cancel-btn" type="button"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-3 text-sm text-[#fdfdfd] hover:brightness-110 sm:min-h-[3vw] sm:min-w-[10vw]">
                    Cancel
                </button>
                <button id="aea-confirm-btn" type="submit"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-3 text-sm text-[#fdfdfd] hover:brightness-110 sm:min-h-[3vw] sm:min-w-[10vw]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const togglePasswordBtn = document.getElementById('toggle-password-change');
        const showPasswordToggle = document.getElementById('edit-show-password-toggle');
        const currentPassword = document.getElementById('current-password');
        const newPassword = document.getElementById('new-password');
        const confirmPassword = document.getElementById('new-password-confirmation');
        const passwordFields = document.getElementById('password-fields');
        const editForm = document.getElementById('admin-edit-form');
        const editPopup = document.getElementById('edit-account-popup');
        const successModal = document.getElementById('user-edit-acc-success');
        const errorModal = document.getElementById('user-edit-acc-fail');
        const errorMessage = document.getElementById('user-edit-acc-fail-message');


        const nameRegex = /^[A-Za-z\s'\-]+$/;
        // Store original values when popup opens
        let originalValues = {};

        function storeOriginalValues() {
            originalValues = {
                firstName: document.getElementById('aea-first-name').value,
                lastName: document.getElementById('aea-last-name').value,
            };
        }
        storeOriginalValues();

        // Reset form to original values
        function resetForm() {
            document.getElementById('aea-first-name').value = originalValues.firstName;
            document.getElementById('aea-last-name').value = originalValues.lastName;

            // Reset password fields
            if (!passwordFields.classList.contains('hidden')) {
                passwordFields.classList.add('hidden');
                clearPasswordFields();
                togglePasswordBtn.textContent = 'Change Password';
            }

            // Clear errors
            clearAllErrors();
        }

        // Add real-time sanitization and validation for name fields
        const disallowedNameCharsPattern =
            /[^A-Za-z\s'\-]/g; // allow only letters, spaces, apostrophes, hyphens

        function sanitizeAndValidateNameForField(field, errorId) {
            const cleaned = field.value.replace(disallowedNameCharsPattern, '');
            if (cleaned !== field.value) {
                field.value = cleaned;
            }
            validateNameField(field, errorId);
        }

        const aeaFirstNameField = document.getElementById('aea-first-name');
        const aeaLastNameField = document.getElementById('aea-last-name');

        aeaFirstNameField.addEventListener('input', function() {
            sanitizeAndValidateNameForField(this, 'first-name-error');
        });

        aeaLastNameField.addEventListener('input', function() {
            sanitizeAndValidateNameForField(this, 'last-name-error');
        });

        // Block disallowed characters on keydown while allowing control/navigation keys
        function blockDisallowedNameKeydown(e) {
            const isAllowedChar = /^[A-Za-z]$/.test(e.key) || e.key === ' ' || e.key === "'" || e.key === '-';
            const controlKeys = [
                'Backspace', 'Delete', 'Tab', 'Enter', 'Escape',
                'ArrowLeft', 'ArrowRight', 'ArrowUp', 'ArrowDown',
                'Home', 'End'
            ];
            if (e.ctrlKey || e.metaKey || controlKeys.includes(e.key)) {
                return;
            }
            if (!isAllowedChar) {
                e.preventDefault();
            }
        }

        aeaFirstNameField.addEventListener('keydown', blockDisallowedNameKeydown);
        aeaLastNameField.addEventListener('keydown', blockDisallowedNameKeydown);

        function validateNameField(field, errorId) {
            const errorElement = document.getElementById(errorId);
            if (!nameRegex.test(field.value)) {
                errorElement.textContent = 'Only letters, spaces, apostrophes, and hyphens are allowed';
                errorElement.classList.remove('hidden');
                return false;
            } else {
                errorElement.classList.add('hidden');
                return true;
            }
        }

        // Enhanced form validation before submission
        function validateForm() {
            let isValid = true;

            // Validate first name
            const firstNameValid = validateNameField(
                document.getElementById('aea-first-name'),
                'first-name-error'
            );
            isValid = isValid && firstNameValid;

            // Validate last name
            const lastNameValid = validateNameField(
                document.getElementById('aea-last-name'),
                'last-name-error'
            );
            isValid = isValid && lastNameValid;

            // If password fields are visible, validate them
            if (!passwordFields.classList.contains('hidden')) {
                const currentPass = document.getElementById('current-password').value;
                const newPass = document.getElementById('new-password').value;
                const confirmPass = document.getElementById('new-password-confirmation').value;

                if (!currentPass) {
                    document.getElementById('current-password-error').textContent =
                        'Current password is required';
                    document.getElementById('current-password-error').classList.remove('hidden');
                    isValid = false;
                }

                if (newPass && newPass.length < 8) {
                    document.getElementById('new-password-error').textContent =
                        'Password must be at least 8 characters';
                    document.getElementById('new-password-error').classList.remove('hidden');
                    isValid = false;
                }

                if (newPass && newPass !== confirmPass) {
                    document.getElementById('confirm-password-error').textContent = 'Passwords do not match';
                    document.getElementById('confirm-password-error').classList.remove('hidden');
                    isValid = false;
                }
            }

            return isValid;
        }

        // Toggle password fields visibility
        togglePasswordBtn.addEventListener('click', function() {
            if (passwordFields.classList.contains('hidden')) {
                passwordFields.classList.remove('hidden');
                togglePasswordBtn.textContent = 'Cancel Password Change';
            } else {
                passwordFields.classList.add('hidden');
                clearPasswordFields();
                clearPasswordErrors();
                togglePasswordBtn.textContent = 'Change Password';
            }
        });

        // Toggle password visibility
        showPasswordToggle.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            currentPassword.type = type;
            newPassword.type = type;
            confirmPassword.type = type;
        });

        // Form submission
        editForm?.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearAllErrors();

            // Frontend validation
            if (!validateForm()) {
                return;
            }

            // Check if there are actual changes
            const firstName = document.getElementById('aea-first-name').value;
            const lastName = document.getElementById('aea-last-name').value;
            const passwordChanged = !passwordFields.classList.contains('hidden') &&
                document.getElementById('new-password').value;

            const hasChanges = firstName !== originalValues.firstName ||
                lastName !== originalValues.lastName ||
                passwordChanged;

            if (!hasChanges) {
                closePopup();
                return;
            }

            const submitBtn = document.getElementById('aea-confirm-btn');
            const originalText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                                        <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></span>
                                        Saving...
                                    `;

            try {
                const formData = new FormData(this);

                // Only include password fields if password change was initiated
                if (passwordFields.classList.contains('hidden')) {
                    formData.delete('current_password');
                    formData.delete('new_password');
                    formData.delete('new_password_confirmation');
                }

                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    // Create consistent error object structure
                    throw {
                        message: data.message || 'Failed to update profile',
                        errors: data.errors || null
                    };
                }

                // On success
                editPopup.style.display = 'none';
                successModal.style.display = 'flex';
                setTimeout(() => window.location.reload(), 1500);

            } catch (error) {
                console.error('Submission error:', error);

                // Normalize error object
                const normalizedError = {
                    message: error.message || 'An unexpected error occurred',
                    errors: error.errors || null
                };

                handleFormError(normalizedError);
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
            }
        });


        // Error handling functions
        function clearAllErrors() {
            // Clear name errors
            document.getElementById('first-name-error').classList.add('hidden');
            document.getElementById('last-name-error').classList.add('hidden');

            // Clear password errors
            clearPasswordErrors();
        }

        function clearPasswordErrors() {
            document.getElementById('current-password-error').classList.add('hidden');
            document.getElementById('new-password-error').classList.add('hidden');
            document.getElementById('confirm-password-error').classList.add('hidden');
        }

        function clearPasswordFields() {
            currentPassword.value = '';
            newPassword.value = '';
            confirmPassword.value = '';
        }

        // Update the handleFormError function to properly use your modal
        function handleFormError(error) {
            console.log('Handling error:', error); // Debugging

            // Clear all field errors first
            clearAllErrors();

            // Handle field-specific errors (for inline validation messages)
            if (error.errors) {
                for (const [field, messages] of Object.entries(error.errors)) {
                    const fieldId = field.replace(/_/g, '-'); // Convert first_name to first-name
                    const errorElement = document.getElementById(`${fieldId}-error`);

                    if (errorElement) {
                        errorElement.textContent = messages.join(', ');
                        errorElement.classList.remove('hidden');
                    }
                }
            }

            // Only show modal for non-validation errors (server errors, etc.)
            if (error.message && !error.errors) {
                const errorMessageElement = document.getElementById('user-edit-acc-fail-message');
                if (errorMessageElement) {
                    errorMessageElement.textContent = error.message;
                    document.getElementById('user-edit-acc-fail').style.display = 'flex';
                }
            }
        }

        // Close popup handlers
        document.getElementById('uea-close-popup').addEventListener('click', function() {
            resetForm();
            closePopup();
        });

        document.getElementById('aea-cancel-btn').addEventListener('click', function() {
            resetForm();
            closePopup();
        });

        function closePopup() {
            document.getElementById('edit-account-popup').style.display = 'none';
        }
    });
</script>
