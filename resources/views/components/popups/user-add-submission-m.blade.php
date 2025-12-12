<x-popups.upload-thesis-success-m />
<x-popups.upload-thesis-fail-m />
<x-popups.universal-x-m />

<div id="user-add-submission-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div id="uea-step1"
        class="relative max-h-[90vh] w-[95vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-8 shadow-xl sm:w-[700px]">

        <!-- Close Button -->
        <button id="uas-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-3 text-2xl font-bold text-gray-900">Add New Submission</h2>
            <p class="text-normal font-regular text-center">Enter your thesis details</p>
        </div>

        <form id="thesis-submission-form" class="mt-2 space-y-6" method="POST" action="{{ route('thesis.submit') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="space-y-1.5">
                <!-- Title -->
                <label for="uas-title" class="block text-sm font-medium text-gray-700">Title</label>
                <input id="uas-title" name="title" type="text" placeholder="Thesis Title"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light uppercase text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />

                <!-- Duplicate title warning -->
                <div id="title-duplicate-warning"
                    class="mt-1 hidden w-full rounded-md border border-yellow-300 bg-yellow-50 px-3 py-2 shadow-sm">
                    <div class="flex items-start">
                        <svg class="mr-2 mt-0.5 h-4 w-4 flex-shrink-0 text-yellow-500" fill="currentColor"
                            viewBox="0 0 20 20">
                            <path fill-rule="evenodd"
                                d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z"
                                clip-rule="evenodd"></path>
                        </svg>
                        <div class="text-xs text-yellow-800">
                            <p class="font-medium text-yellow-900">Duplicate Title Detected</p>
                            <p id="duplicate-details" class="mt-0.5 text-xs leading-tight"></p>
                        </div>
                    </div>
                </div>

                <!-- Adviser -->
                <label for="uas-adviser" class="block text-sm font-medium text-gray-700">Adviser</label>
                <input id="uas-adviser" name="adviser" type="text" placeholder="Adviser's Name" list="adviser-list"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />

                <!-- Datalist for adviser suggestions -->
                <datalist id="adviser-list">
                    @if ($userAdvisers->isNotEmpty())
                        @foreach ($userAdvisers as $adviser)
                            <option value="{{ $adviser->name }}">
                        @endforeach
                    @else
                        <option value="No advisers available for your program">
                    @endif
                </datalist>

                <!-- Authors -->
                <label for="uas-authors" class="block text-sm font-medium text-gray-700">Author/s <span
                        class="mb-2 text-xs italic text-gray-500">(comma
                        separated) </span></label>
                <input id="uas-authors" name="authors" type="text"
                    placeholder="e.g. Juan A. Dela Cruz, Jose R. Santos, Maria L. Reyes"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    required />

                <!-- Abstract -->
                <label for="uas-abstract" class="block text-sm font-medium text-gray-700">Abstract</label>
                <textarea id="uas-abstract" name="abstract" placeholder="Enter abstract here"
                    class="mt-1 block max-h-[25vh] min-h-[15vh] w-full overflow-y-auto rounded-lg border border-[#575757] px-4 py-3 pr-5 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    rows="5" required></textarea>
                <div class="mt-1 text-xs" id="uas-abstract-counter">
                    <span id="uas-abstract-words">Words: 0/500</span>
                </div>

                <!-- File Upload -->
                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700">Manuscript (PDF only)</label>
                    <p class="mb-2 text-xs italic text-gray-500">Please upload the final version of your manuscript in
                        PDF format (maximum 15MB).</p>

                    <div class="flex items-center gap-3">
                        <!-- Hidden file input -->
                        <input type="file" id="uas-file-input" name="document" class="hidden" accept=".pdf">

                        <!-- Upload button -->
                        <button type="button" id="uas-upload-btn"
                            class="w-auto cursor-pointer rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-4 py-2 text-sm text-[#fdfdfd] shadow hover:brightness-110">
                            Choose File
                        </button>

                        <!-- File display area -->
                        <div id="uploaded-file" class="flex items-center space-x-2">
                            <span id="uas-file-name"
                                class="max-w-[18vw] truncate text-sm font-medium text-[#575757]"></span>
                            <button type="button" id="uas-remove-file" class="hidden text-red-500 hover:text-red-700">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-10 flex justify-center space-x-6">
                <button id="uas-cancel-btn" type="button"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-4 py-2 text-sm text-[#fdfdfd] hover:brightness-110 sm:px-5 sm:py-2 sm:text-base md:px-6 md:py-2.5 md:text-lg lg:px-7 lg:py-3 lg:text-lg xl:px-8 xl:py-3 xl:text-xl">
                    Cancel
                </button>

                <button id="uas-confirm-btn" type="submit"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-4 py-2 text-sm text-[#fdfdfd] hover:brightness-110 sm:px-5 sm:py-2 sm:text-base md:px-6 md:py-2.5 md:text-lg lg:px-7 lg:py-3 lg:text-lg xl:px-8 xl:py-3 xl:text-xl">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Hide the default datalist dropdown arrow/indicator for adviser input */
    #uas-adviser {
        -webkit-appearance: none;
        appearance: none;
        background-image: none;
    }

    #uas-adviser::-webkit-calendar-picker-indicator {
        display: none !important;
        opacity: 0;
    }

    /* Smooth transition for title input border color changes */
    #uas-title {
        transition: border-color 0.2s ease-in-out;
    }

    /* Prevent layout shift for duplicate warning */
    #title-duplicate-warning {
        width: 100%;
        max-width: 100%;
        box-sizing: border-box;
        transition: all 0.2s ease-in-out;
    }

    /* Ensure the warning content is properly hidden when not needed */
    #title-duplicate-warning .flex {
        width: 100%;
        max-width: 100%;
        opacity: 1;
        transition: opacity 0.2s ease-in-out;
        overflow: hidden;
    }

    #title-duplicate-warning .flex.hidden {
        opacity: 0;
    }

    /* Prevent text from expanding the container */
    #title-duplicate-warning p {
        margin: 0;
        line-height: 1.3;
        word-wrap: break-word;
        overflow-wrap: break-word;
        white-space: normal;
        max-width: 100%;
    }

    /* Ensure form maintains consistent spacing */
    .space-y-1.5>*+* {
        margin-top: 0.375rem;
    }

    /* Compact spacing for warning area */
    #title-duplicate-warning p {
        margin: 0;
        line-height: 1.3;
        word-wrap: break-word;
        overflow-wrap: break-word;
    }

    /* Ensure consistent width */
    #title-duplicate-warning .flex {
        width: 100%;
    }

    #title-duplicate-warning .flex>div:last-child {
        flex: 1;
        min-width: 0;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const submissionPopup = document.getElementById('user-add-submission-popup');
        const submissionForm = document.getElementById('thesis-submission-form');
        const successModal = document.getElementById('upload-thesis-success');
        const errorModal = document.getElementById('upload-thesis-fail');
        const errorMessage = document.getElementById('upload-thesis-fail-message');
        // Preserve line breaks when using textContent for error messages
        if (errorMessage) {
            errorMessage.style.whiteSpace = 'pre-line';
        }
        const fileInput = document.getElementById('uas-file-input');
        const uploadBtn = document.getElementById('uas-upload-btn');
        const uploadedFile = document.getElementById('uploaded-file');
        const fileNameDisplay = document.getElementById('uas-file-name');
        const removeFileBtn = document.getElementById('uas-remove-file');
        const titleInput = document.getElementById('uas-title');
        const abstractInput = document.getElementById('uas-abstract');
        const abstractWords = document.getElementById('uas-abstract-words');
        const adviserInput = document.getElementById('uas-adviser');
        const authorsInput = document.getElementById('uas-authors');
        const titleDuplicateWarning = document.getElementById('title-duplicate-warning');
        const duplicateDetails = document.getElementById('duplicate-details');
        const cancelBtn = document.getElementById('uas-cancel-btn');
        const closeBtn = document.getElementById('uas-close-popup');

        // Open popup when add submission button is clicked
        document.addEventListener('click', function(e) {
            if (e.target?.id === 'add-submission-btn') {
                submissionPopup.style.display = 'flex';
                resetForm();
            }
        });

        // Input sanitization: restrict invalid characters
        // Title: allow letters, numbers, spaces and common punctuation . , : ; ( ) & ' - /
        titleInput?.addEventListener('input', function() {
            const original = this.value;
            let sanitized = original.replace(/[^A-Za-z0-9ñÑ .,:;()&'\/\-]/g, '');
            // Collapse multiple spaces
            sanitized = sanitized.replace(/\s{2,}/g, ' ');
            if (sanitized !== original) this.value = sanitized;
        });

        // Adviser: allow letters, spaces, period, hyphen, apostrophe
        adviserInput?.addEventListener('input', function() {
            const original = this.value;
            let sanitized = original.replace(/[^A-Za-zñÑ .\-']/g, '');
            sanitized = sanitized.replace(/\s{2,}/g, ' ');
            if (sanitized !== original) this.value = sanitized;
        });

        // Authors: list of names separated by commas; allow letters, spaces, comma, period, hyphen, apostrophe
        authorsInput?.addEventListener('input', function() {
            const original = this.value;
            let sanitized = original.replace(/[^A-Za-zñÑ ,.\-']/g, '');
            // Normalize comma spacing and collapse spaces
            sanitized = sanitized.replace(/\s*,\s*/g, ', ');
            sanitized = sanitized.replace(/\s{2,}/g, ' ');
            // Only remove leading/trailing spaces, preserve commas
            sanitized = sanitized.replace(/^\s+/g, '').replace(/\s+$/g, '');
            if (sanitized !== original) this.value = sanitized;
        });

        // Abstract: strip angle brackets to prevent HTML, keep whitespace/newlines
        abstractInput?.addEventListener('input', function() {
            const original = this.value;
            // Remove angle brackets and control chars except tab/newline/carriage return
            let sanitized = original.replace(/[<>]/g, '');
            sanitized = sanitized.replace(/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g, '');
            // Limit excessive consecutive newlines to max 2
            sanitized = sanitized.replace(/\n{3,}/g, '\n\n');
            if (sanitized !== original) this.value = sanitized;

            updateAbstractCounter();
        });

        function updateAbstractCounter() {
            if (!abstractInput) return;
            const text = abstractInput.value.trim();
            const words = text.length ? text.split(/\s+/).filter(Boolean).length : 0;
            if (abstractWords) abstractWords.textContent = `Words: ${words}/500`;

            // Styling based on limits
            const overLimit = words > 500;
            const underMin = text.length < 100;
            if (overLimit || underMin) {
                abstractInput.classList.remove('border-[#575757]');
                abstractInput.classList.add('border-yellow-500');
            } else {
                abstractInput.classList.remove('border-yellow-500');
                abstractInput.classList.add('border-[#575757]');
            }
        }

        // Close popup handlers
        document.getElementById('uas-close-popup')?.addEventListener('click', () => {
            submissionPopup.style.display = 'none';
            resetForm();
        });

        document.getElementById('uas-cancel-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            submissionPopup.style.display = 'none';
            resetForm();
        });

        // File upload handling
        uploadBtn.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 15 * 1024 * 1024; // 15MB in bytes

                if (file.size > maxSize) {
                    const submissionPopup = document.getElementById('user-add-submission-popup');
                    const kpopup = document.getElementById('universal-x-popup');
                    const kTopText = document.getElementById('x-topText');
                    const kSubText = document.getElementById('x-subText');
                    const kConfirmBtn = document.getElementById('uniX-confirm-btn');

                    kTopText.textContent = "File Too Large!";
                    kSubText.textContent =
                        `File size is ${(file.size / 1024 / 1024).toFixed(2)}MB. Maximum allowed size is 15MB.`;
                    submissionPopup.style.display = 'none';
                    kpopup.style.display = 'flex';

                    kConfirmBtn.addEventListener('click', function() {
                        kpopup.style.display = 'none';
                        submissionPopup.style.display = 'flex';
                    });

                    // Clear the file input
                    fileInput.value = '';
                    return;
                }

                fileNameDisplay.textContent = file.name;
                uploadedFile.classList.remove('hidden');
                removeFileBtn.classList.remove('hidden');
            }
        });

        removeFileBtn.addEventListener('click', () => {
            fileInput.value = '';
            uploadedFile.classList.add('hidden');
            removeFileBtn.classList.add('hidden');
        });

        // Duplicate title checking
        let titleCheckTimeout;
        titleInput.addEventListener('input', function() {
            const title = this.value.trim();

            // Clear previous timeout
            clearTimeout(titleCheckTimeout);

            // Hide warning if title is empty
            if (!title) {
                titleDuplicateWarning.classList.add('hidden');
                titleInput.classList.remove('border-yellow-500');
                titleInput.classList.add('border-[#575757]');
                return;
            }

            // Debounce the API call (wait 500ms after user stops typing)
            titleCheckTimeout = setTimeout(async () => {
                try {
                    const response = await fetch(
                        '{{ route('thesis.check-duplicate-title') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                            },
                            body: JSON.stringify({
                                title: title
                            })
                        });

                    const data = await response.json();

                    if (data.isDuplicate) {
                        // Show duplicate warning (build nodes to avoid innerHTML XSS)
                        const existing = data.existingSubmission;
                        // Clear previous content
                        duplicateDetails.textContent = '';

                        const authorsLabel = document.createElement('strong');
                        authorsLabel.textContent = 'Authors:';
                        const authorsValue = document.createElement('span');
                        authorsValue.textContent = ` ${existing.authors} `;

                        const sep1 = document.createElement('span');
                        sep1.textContent = '| ';

                        const dateLabel = document.createElement('strong');
                        dateLabel.textContent = 'Date:';
                        const dateValue = document.createElement('span');
                        dateValue.textContent =
                            ` ${new Date(existing.submitted_at).toLocaleDateString()} `;

                        const sep2 = document.createElement('span');
                        sep2.textContent = '| ';

                        const statusLabel = document.createElement('strong');
                        statusLabel.textContent = 'Status:';
                        const statusValue = document.createElement('span');
                        statusValue.textContent = ` ${existing.status}`;

                        duplicateDetails.appendChild(authorsLabel);
                        duplicateDetails.appendChild(document.createTextNode(' '));
                        duplicateDetails.appendChild(authorsValue);
                        duplicateDetails.appendChild(sep1);
                        duplicateDetails.appendChild(dateLabel);
                        duplicateDetails.appendChild(document.createTextNode(' '));
                        duplicateDetails.appendChild(dateValue);
                        duplicateDetails.appendChild(sep2);
                        duplicateDetails.appendChild(statusLabel);
                        duplicateDetails.appendChild(document.createTextNode(' '));
                        duplicateDetails.appendChild(statusValue);
                        titleDuplicateWarning.classList.remove('hidden');

                        // Change input border to warning color
                        titleInput.classList.add('border-yellow-500');
                        titleInput.classList.remove('border-[#575757]');
                    } else {
                        // Hide warning and reset input styling
                        titleDuplicateWarning.classList.add('hidden');
                        titleInput.classList.remove('border-yellow-500');
                        titleInput.classList.add('border-[#575757]');
                    }
                } catch (error) {
                    console.error('Error checking duplicate title:', error);
                }
            }, 500);
        });



        // Function to reset form and clear warnings
        function resetForm() {
            // Reset form fields
            submissionForm.reset();

            // Clear file display
            uploadedFile.classList.add('hidden');
            removeFileBtn.classList.add('hidden');

            // Clear duplicate title warning
            titleDuplicateWarning.classList.add('hidden');
            titleInput.classList.remove('border-yellow-500');
            titleInput.classList.add('border-[#575757]');

            // Clear any existing timeouts
            if (titleCheckTimeout) {
                clearTimeout(titleCheckTimeout);
            }
        }

        // Form submission
        submissionForm?.addEventListener('submit', async function(e) {
            e.preventDefault();

            // Validate adviser name
            const adviserInput = document.getElementById('uas-adviser');
            const enteredAdviser = adviserInput.value.trim();
            const validAdvisers = @json($userAdvisers->pluck('name')->toArray());

            if (!validAdvisers.includes(enteredAdviser)) {
                const submissionPopup = document.getElementById('user-add-submission-popup');
                const kpopup = document.getElementById('universal-x-popup');
                const kTopText = document.getElementById('x-topText');
                const kSubText = document.getElementById('x-subText');
                const kConfirmBtn = document.getElementById('uniX-confirm-btn');

                kTopText.textContent = "Invalid Adviser!";
                kSubText.textContent = validAdvisers.length ?
                    'Please select an adviser from your program.' :
                    'No advisers available. Please contact the administrator.';
                submissionPopup.style.display = 'none';
                kpopup.style.display = 'flex';

                kConfirmBtn.addEventListener('click', function() {
                    kpopup.style.display = 'none';
                    submissionPopup.style.display = 'flex';
                });

                return;
            }

            // Client-side Abstract limits: min 100 chars, max 500 words
            const abstractText = abstractInput?.value?.trim() ?? '';
            const abstractWordsCount = abstractText.length ? abstractText.split(/\s+/).filter(
                Boolean).length : 0;
            if (abstractText.length < 100 || abstractWordsCount > 500) {
                const submissionPopup = document.getElementById('user-add-submission-popup');
                const kpopup = document.getElementById('universal-x-popup');
                const kTopText = document.getElementById('x-topText');
                const kSubText = document.getElementById('x-subText');
                const kConfirmBtn = document.getElementById('uniX-confirm-btn');

                kTopText.textContent = "Abstract Length Issue";
                kSubText.textContent =
                    `Your abstract must be at least 100 characters and no more than 500 words. Currently: ${abstractText.length} chars, ${abstractWordsCount} words.`;
                submissionPopup.style.display = 'none';
                kpopup.style.display = 'flex';

                kConfirmBtn.addEventListener('click', function() {
                    kpopup.style.display = 'none';
                    submissionPopup.style.display = 'flex';
                });

                return;
            }

            if (fileInput.files.length === 0) {
                const submissionPopup = document.getElementById('user-add-submission-popup');
                const kpopup = document.getElementById('universal-x-popup');
                const kTopText = document.getElementById('x-topText');
                const kSubText = document.getElementById('x-subText');
                const kConfirmBtn = document.getElementById('uniX-confirm-btn');

                kTopText.textContent = "Missing File!";
                kSubText.textContent = 'Please select a PDF file to upload.';
                submissionPopup.style.display = 'none';
                kpopup.style.display = 'flex';

                kConfirmBtn.addEventListener('click', function() {
                    kpopup.style.display = 'none';
                    submissionPopup.style.display = 'flex';
                });

                return;
            } else {
                const submitBtn = document.getElementById('uas-confirm-btn');
                const originalText = submitBtn.innerHTML;

                submitBtn.disabled = true;
                submitBtn.innerHTML = `
                                        <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></span>
                                        Submitting...
                                    `;

                // Disable Cancel and Close while submitting
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
                    const formData = new FormData(this);

                    const response = await fetch(this.action, {
                        method: 'POST',
                        headers: {
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]')
                                .content,
                        },
                        body: formData,
                    });

                    const responseData = await response.json();

                    if (!response.ok) {
                        // Debug: Log the error response
                        console.error('Upload failed with status:', response.status);
                        console.error('Error response:', responseData);
                        throw responseData;
                    }

                    // On success
                    submissionPopup.style.display = 'none';
                    successModal.style.display = 'flex';

                } catch (error) {
                    console.error('Error:', error);

                    // Set error message
                    if (error.errors) {
                        let messagesToShow = [];
                        // Special handling for authors validation
                        if (error.errors.authors) {
                            messagesToShow = error.errors.authors;
                        } else {
                            for (const [, messages] of Object.entries(error.errors)) {
                                messagesToShow = messagesToShow.concat(messages);
                            }
                        }
                        errorMessage.textContent = messagesToShow.join('\n');
                    } else {
                        errorMessage.textContent = error.message || 'An unexpected error occurred';
                    }

                    // Show error modal
                    submissionPopup.style.display = 'none';
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
            }
        });

        // Success modal handlers
        document.getElementById('success-modal-close')?.addEventListener('click', () => {
            document.getElementById('upload-thesis-success').style.display = 'none';
            window.location.reload();
        });

        document.getElementById('success-modal-ok-btn')?.addEventListener('click', () => {
            document.getElementById('upload-thesis-success').style.display = 'none';
            window.location.reload();
        });

        // Fail modal handlers
        document.getElementById('fail-modal-close')?.addEventListener('click', () => {
            document.getElementById('upload-thesis-fail').style.display = 'none';
        });

        document.getElementById('fail-modal-ok-btn')?.addEventListener('click', () => {
            document.getElementById('upload-thesis-fail').style.display = 'none';
        });
    });
</script>
