@props(['faculty', 'undergraduate' => [], 'graduate' => []])

<x-popups.faculty-edit-acc-success />
<x-popups.faculty-edit-acc-fail />

<div id="faculty-edit-account-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div id="fea-step1" class="relative max-h-[90vh] w-[700px] rounded-2xl bg-[#fdfdfd] p-8 shadow-xl">

        <!-- Close Button -->
        <button id="fea-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
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

        <form id="faculty-edit-form" class="mt-2 space-y-6" method="POST"
            action="{{ route('faculty.profile.update') }}">
            @csrf
            @method('PUT')
            <div class="space-y-1">
                <label for="fea-first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                <input id="fea-first-name" name="first_name" type="text" value="{{ $faculty->decrypted_first_name }}"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />

                <label for="fea-last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                <input id="fea-last-name" name="last_name" type="text" value="{{ $faculty->decrypted_last_name }}"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />

                <label for="fea-usep-email" class="block text-sm font-medium text-gray-700">Email Address</label>
                <input id="fea-usep-email" type="email" value="{{ $faculty->email }}" readonly
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none" />
            </div>
            <div class="mt-10 flex justify-center space-x-4 sm:space-x-6">
                <button id="fea-cancel-btn" type="button"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-3 text-sm text-[#fdfdfd] hover:brightness-110 sm:min-h-[3vw] sm:min-w-[10vw]">
                    Cancel
                </button>
                <button id="fea-confirm-btn" type="submit"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-3 text-sm text-[#fdfdfd] hover:brightness-110 sm:min-h-[3vw] sm:min-w-[10vw]">
                    Save Changes
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const editPopup = document.getElementById('faculty-edit-account-popup');
        const editForm = document.getElementById('faculty-edit-form');
        const successModal = document.getElementById('faculty-edit-acc-success');
        const errorModal = document.getElementById('faculty-edit-acc-fail');
        const errorMessage = document.getElementById('faculty-edit-acc-fail-message');
        let originalValues = {};

        function storeOriginalValues() {
            originalValues = {
                firstName: document.getElementById('fea-first-name')?.value || '',
                lastName: document.getElementById('fea-last-name')?.value || ''
            };
        }

        // Open popup when edit button is clicked
        document.addEventListener('click', function(e) {
            if (e.target?.id === 'edit-faculty-btn') {
                editPopup.style.display = 'flex';
                storeOriginalValues();
            }
        });

        // Sanitize name fields and block invalid characters (allow only letters, spaces, apostrophes, hyphens)
        const disallowedNameCharsPattern = /[^A-Za-z\s'\-]/g;
        const feaFirstName = document.getElementById('fea-first-name');
        const feaLastName = document.getElementById('fea-last-name');

        function sanitizeField(field) {
            const cleaned = field.value.replace(disallowedNameCharsPattern, '');
            if (cleaned !== field.value) {
                field.value = cleaned;
            }
        }

        function blockDisallowedKeydown(e) {
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

        feaFirstName?.addEventListener('input', () => sanitizeField(feaFirstName));
        feaLastName?.addEventListener('input', () => sanitizeField(feaLastName));
        feaFirstName?.addEventListener('keydown', blockDisallowedKeydown);
        feaLastName?.addEventListener('keydown', blockDisallowedKeydown);

        // Close popup handlers
        document.getElementById('fea-close-popup')?.addEventListener('click', () => {
            editPopup.style.display = 'none';
        });

        document.getElementById('fea-cancel-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            editPopup.style.display = 'none';
        });

        // Form submission
        editForm?.addEventListener('submit', async function(e) {
            e.preventDefault();
            // Change detection: if nothing changed, just close and skip success modal
            const currentFirst = document.getElementById('fea-first-name')?.value || '';
            const currentLast = document.getElementById('fea-last-name')?.value || '';
            const hasChanges = currentFirst !== originalValues.firstName ||
                currentLast !== originalValues.lastName;

            if (!hasChanges) {
                editPopup.style.display = 'none';
                return;
            }
            const submitBtn = document.getElementById('fea-confirm-btn');
            const originalText = submitBtn.innerHTML;
            const cancelBtn = document.getElementById('fea-cancel-btn');
            const closeBtn = document.getElementById('fea-close-popup');

            submitBtn.disabled = true;
            submitBtn.innerHTML = `
            <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></span>
            Saving...
        `;

            // Disable Cancel and Close while saving
            if (cancelBtn) {
                cancelBtn.disabled = true;
                cancelBtn.classList.remove('cursor-pointer');
                cancelBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }
            if (closeBtn) {
                closeBtn.disabled = true;
                closeBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            try {
                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: new FormData(this),
                });

                if (!response.ok) {
                    const errorData = await response.json();
                    throw errorData;
                }

                // On success - show modal first, then reload when OK is clicked
                editPopup.style.display = 'none';
                successModal.style.display = 'flex';

            } catch (error) {
                console.error('Error:', error);

                // Set error message
                if (error.errors) {
                    let errorText = '<ul class="text-left">';
                    for (const [field, messages] of Object.entries(error.errors)) {
                        errorText += `<li class="text-sm">• ${messages.join(', ')}</li>`;
                    }
                    errorText += '</ul>';
                    errorMessage.innerHTML = errorText;
                } else {
                    errorMessage.textContent = error.message || 'An unexpected error occurred';
                }

                // Show error modal
                editPopup.style.display = 'none';
                errorModal.style.display = 'flex';
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalText;
                // Re-enable Cancel and Close after request completes
                if (cancelBtn) {
                    cancelBtn.disabled = false;
                    cancelBtn.classList.add('cursor-pointer');
                    cancelBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
                if (closeBtn) {
                    closeBtn.disabled = false;
                    closeBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            }
        });
    });
</script>
