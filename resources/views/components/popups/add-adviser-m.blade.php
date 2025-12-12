@props(['programs', 'undergraduate' => [], 'graduate' => []])

<x-popups.universal-ok-m />
<x-popups.universal-x-m />

<div id="add-adviser-popup" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="relative max-h-[90vh] w-[700px] rounded-2xl bg-[#fdfdfd] p-8 shadow-xl">

        <!-- Close Button -->
        <button id="aa-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-3 text-2xl font-bold text-gray-900">Add New Adviser</h2>
            <p class="text-normal font-regular text-center">Enter adviser details</p>
        </div>

        <form id="add-adviser-form" class="mt-6 space-y-6">
            @csrf

            <div class="space-y-4">
                <!-- Adviser Name -->
                <div>
                    <label for="aa-name" class="block text-sm font-medium text-gray-700">Adviser Name</label>
                    <input id="aa-name" name="name" type="text" placeholder="Enter adviser name"
                        class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                        required />
                </div>

                <!-- Program Selection -->
                <div>
                    <label for="aa-program" class="block text-sm font-medium text-gray-700">Program</label>
                    <div class="relative">
                        <select id="aa-program" name="program_id"
                            class="mt-1 block w-full appearance-none rounded-lg border border-[#575757] px-4 py-3 pr-10 text-sm text-[#575757] focus:outline-none"
                            required>
                            <option value="" disabled selected>Select your program</option>

                            <optgroup label="Undergraduate Programs">
                                @foreach ($undergraduate as $program)
                                    <option value="{{ $program->id }}">{{ $program->name }}</option>
                                @endforeach
                            </optgroup>

                            @if (count($graduate) > 0)
                                <optgroup label="Graduate Programs">
                                    @foreach ($graduate as $program)
                                        <option value="{{ $program->id }}">{{ $program->name }}</option>
                                    @endforeach
                                </optgroup>
                            @endif
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
                <button id="aa-cancel-btn" type="button"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2.5 text-base text-[#fdfdfd] hover:brightness-110">
                    Cancel
                </button>

                <button id="aa-confirm-btn" type="submit"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-2.5 text-base text-[#fdfdfd] hover:brightness-110">
                    Add Adviser
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const addAdviserPopup = document.getElementById('add-adviser-popup');
        const addAdviserForm = document.getElementById('add-adviser-form');
        const nameInput = document.getElementById('aa-name');
        const programSelect = document.getElementById('aa-program');
        const confirmBtn = document.getElementById('aa-confirm-btn');

        // Open popup when add adviser button is clicked
        document.addEventListener('click', function(e) {
            if (e.target?.id === 'add-adviser-btn') {
                addAdviserPopup.style.display = 'flex';
                resetForm();
            }
        });

        // Input sanitization for adviser name
        nameInput?.addEventListener('input', function() {
            const original = this.value;
            let sanitized = original.replace(/[^A-Za-z .\-']/g, '');
            sanitized = sanitized.replace(/\s{2,}/g, ' ');
            if (sanitized !== original) this.value = sanitized;
        });

        // Close popup handlers
        document.getElementById('aa-close-popup')?.addEventListener('click', () => {
            addAdviserPopup.style.display = 'none';
            resetForm();
        });

        document.getElementById('aa-cancel-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            addAdviserPopup.style.display = 'none';
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
            addAdviserForm.reset();
        }

        // Form submission
        addAdviserForm?.addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const name = formData.get('name')?.trim();
            const program_id = formData.get('program_id');

            if (!name || !program_id) {
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

                const response = await fetch('/admin/advisers', {
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
                    addAdviserPopup.style.display = 'none';
                    resetForm();

                    // Show success modal
                    showSuccess('Adviser added successfully!');

                    // Trigger page refresh or reload advisers list
                    if (typeof loadAdvisers === 'function') {
                        loadAdvisers();
                    } else {
                        window.location.reload();
                    }
                } else {
                    // Close add modal first
                    addAdviserPopup.style.display = 'none';

                    // Show error modal
                    const errorMessage = responseData?.message ||
                        'Failed to add adviser. Please try again.';
                    showError(errorMessage);
                }
            } catch (error) {
                console.error('Error:', error);
                // Close add modal first
                addAdviserPopup.style.display = 'none';
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
