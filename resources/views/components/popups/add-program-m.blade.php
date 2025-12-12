<x-popups.universal-ok-m />
<x-popups.universal-x-m />

<div id="add-program-popup" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="relative max-h-[90vh] w-[580px] rounded-2xl bg-[#fdfdfd] p-8 shadow-xl">

        <!-- Close Button -->
        <button id="ap-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-3 text-2xl font-bold text-gray-900">Add New Program</h2>
            <p class="text-normal font-regular text-center">Enter program details</p>
        </div>

        <form id="add-program-form" class="mt-6 space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- Program Name -->
                <div>
                    <label for="ap-name" class="block text-sm font-medium text-gray-700">Program Name</label>
                    <input id="ap-name" name="name" type="text"
                        placeholder="e.g., Bachelor of Science in Information Technology"
                        class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                        required />
                </div>

                <!-- Degree Selection -->
                <div>
                    <label for="ap-degree" class="block text-sm font-medium text-gray-700">Degree</label>
                    <div class="relative">
                        <select id="ap-degree" name="degree"
                            class="mt-1 block w-full appearance-none rounded-lg border border-[#575757] px-4 py-3 pr-10 font-light text-[#575757] transition-colors duration-200 focus:outline-none"
                            required>
                            <option value="">Select degree level</option>
                            <option value="Undergraduate">Undergraduate</option>
                            <option value="Graduate">Graduate</option>
                        </select>
                        <div
                            class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 transform text-[#575757]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 flex justify-center space-x-6">
                <button id="ap-cancel-btn" type="button"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2.5 text-base text-[#fdfdfd] hover:brightness-110">
                    Cancel
                </button>

                <button id="ap-confirm-btn" type="submit"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-2.5 text-base text-[#fdfdfd] hover:brightness-110">
                    Add Program
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addProgramPopup = document.getElementById('add-program-popup');
        const addProgramForm = document.getElementById('add-program-form');
        const nameInput = document.getElementById('ap-name');
        const degreeSelect = document.getElementById('ap-degree');
        const confirmBtn = document.getElementById('ap-confirm-btn');

        // Open popup when add program button is clicked
        document.addEventListener('click', function(e) {
            if (e.target?.id === 'add-program-btn') {
                addProgramPopup.style.display = 'flex';
                resetForm();
            }
        });

        // Input sanitization for program name
        nameInput?.addEventListener('input', function() {
            const original = this.value;
            let sanitized = original.replace(/[^A-Za-z0-9 \-&\/().]/g, '');
            sanitized = sanitized.replace(/\s{2,}/g, ' ');
            if (sanitized !== original) this.value = sanitized;
        });

        // Close popup handlers
        document.getElementById('ap-close-popup')?.addEventListener('click', () => {
            addProgramPopup.style.display = 'none';
            resetForm();
        });

        document.getElementById('ap-cancel-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            addProgramPopup.style.display = 'none';
            resetForm();
        });

        // Helper functions for universal modals
        function showSuccess(message) {
            const okTop = document.getElementById('OKtopText');
            const okSub = document.getElementById('OKsubText');
            const okPopup = document.getElementById('universal-ok-popup');
            if (okTop) okTop.textContent = "Success!";
            if (okSub) okSub.textContent = message;
            if (okPopup) okPopup.style.display = 'flex';
        }

        function showError(message) {
            const xTop = document.getElementById('x-topText');
            const xSub = document.getElementById('x-subText');
            const xPopup = document.getElementById('universal-x-popup');
            if (xTop) xTop.textContent = "Error!";
            if (xSub) xSub.textContent = message;
            if (xPopup) xPopup.style.display = 'flex';
        }

        // Function to reset form
        function resetForm() {
            addProgramForm.reset();
        }

        // Form submission
        addProgramForm?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const name = formData.get('name')?.trim();
            const degree = formData.get('degree');

            if (!name || !degree) {
                showError('Please fill in all required fields.');
                return;
            }

            // Disable submit button and show loading
            const originalText = confirmBtn.innerHTML;
            confirmBtn.disabled = true;
            confirmBtn.innerHTML = `
                <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></span>
                Adding...
            `;

            try {
                const body = new URLSearchParams(formData);

                const response = await fetch('/admin/programs', {
                    method: 'POST',
                    credentials: 'same-origin',
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                        'Content-Type': 'application/x-www-form-urlencoded',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body
                });

                const responseData = await response.json();

                if (response.ok) {
                    // Close add modal first
                    addProgramPopup.style.display = 'none';
                    resetForm();

                    // Show success modal
                    showSuccess('Program added successfully!');

                    // Trigger page refresh or reload programs list
                    if (typeof loadPrograms === 'function') {
                        loadPrograms();
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Close add modal first
                    addProgramPopup.style.display = 'none';

                    // Show error modal
                    const errorMessage = responseData?.message ||
                        'Failed to add program. Please try again.';
                    showError(errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                // Close add modal first
                addProgramPopup.style.display = 'none';
                showError('Network error. Please check your connection and try again.');
            } finally {
                confirmBtn.disabled = false;
                confirmBtn.innerHTML = originalText;
            }
        });

        // Universal modal close handlers
        document.getElementById('uniOK-confirm-btn')?.addEventListener('click', () => {
            document.getElementById('universal-ok-popup').style.display = 'none';
        });

        document.getElementById('uniX-confirm-btn')?.addEventListener('click', () => {
            document.getElementById('universal-x-popup').style.display = 'none';
        });
    });
</script>
