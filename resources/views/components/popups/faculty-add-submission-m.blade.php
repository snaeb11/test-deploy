@props(['facultyAdvisers'])

<x-popups.upload-thesis-success-m />
<x-popups.upload-thesis-fail-m />
<x-popups.universal-x-m />

<div id="faculty-add-submission-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div id="uea-step1" class="relative max-h-[90vh] w-[700px] rounded-2xl bg-[#fdfdfd] p-8 shadow-xl">

        <!-- Close Button -->
        <button id="fas-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-3 text-2xl font-bold text-gray-900">Add Form Submission</h2>
            <p class="text-normal font-regular text-center">Submit your document</p>
        </div>

        <form id="form-submission-form" class="mt-2 space-y-6" method="POST" action="{{ route('form.submit') }}"
            enctype="multipart/form-data">
            @csrf

            <div class="space-y-1.5">
                <!-- Form Type -->
                <label for="fas-form-type" class="block text-sm font-medium text-gray-700">Form Type</label>
                <select id="fas-form-type" name="form_type"
                    class="mt-1 block w-full rounded-lg border border-[#575757] px-4 py-3 text-sm text-black focus:outline-none"
                    required>
                    <option value="" disabled selected>Select a form type</option>
                    <optgroup label="R&DD Forms, Templates, and References">
                        <option>Research Proposal Form</option>
                        <option>Monthly Accomplishment Report</option>
                        <option>Quarterly Progress Report</option>
                        <option>Monitoting and Evaluation Form</option>
                        <option>Monitoring and Performance Evaluation Form</option>
                        <option>Monitoring Minutes</option>
                        <option>Terminal Report Form</option>
                        <option>SETI Scorecard</option>
                        <option>SETI for SDGs Scorecard Guide</option>
                        <option>GAD Assessment Checklist</option>
                        <option>Special Order Template</option>
                        <option>Notice of Engagement Template</option>
                        <option>Request Letter for Extension Template</option>
                        <option>Updated Workplan Template</option>
                    </optgroup>
                    <optgroup label="R&DD MOA Forms, Samples, and Referneces">
                        <option>Review Form for Agreement (RFA)</option>
                        <option>Routing Slip for Agreements (RSA)</option>
                        <option>MOA Sample</option>
                        <option>MOU Sample</option>
                        <option>Supplemental MOA Sample</option>
                    </optgroup>
                </select>

                <!-- Note (optional) -->
                <label for="fas-note" class="block text-sm font-medium text-gray-700">Note (optional)</label>
                <textarea id="fas-note" name="note" placeholder="Add a note (optional)"
                    class="mt-1 block max-h-[25vh] min-h-[12vh] w-full overflow-y-auto rounded-lg border border-[#575757] px-4 py-3 pr-5 font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none"
                    rows="4"></textarea>
                <div class="mt-1 text-xs" id="fas-note-counter">
                    <span id="fas-note-words">Words: 0/150</span>
                </div>

                <!-- File Upload -->
                <div class="mt-5">
                    <label class="block text-sm font-medium text-gray-700">Upload File (PDF, Word, Excel)</label>
                    <p class="mb-2 text-xs italic text-gray-500">Allowed: .pdf, .doc, .docx, .xls, .xlsx · Max 15MB</p>

                    <div class="flex items-center gap-3">
                        <!-- Hidden file input -->
                        <input type="file" id="fas-file-input" name="document" class="hidden"
                            accept=".pdf,.doc,.docx,.xls,.xlsx">

                        <!-- Upload button -->
                        <button type="button" id="fas-upload-btn"
                            class="w-auto cursor-pointer rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-4 py-2 text-sm text-[#fdfdfd] shadow hover:brightness-110">
                            Choose File
                        </button>

                        <!-- File display area -->
                        <div id="uploaded-file" class="flex items-center space-x-2">
                            <span id="fas-file-name"
                                class="max-w-[18vw] truncate text-sm font-medium text-[#575757]"></span>
                            <button type="button" id="fas-remove-file" class="hidden text-red-500 hover:text-red-700">
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
                <button id="fas-cancel-btn" type="button"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-4 py-2 text-sm text-[#fdfdfd] hover:brightness-110 sm:px-5 sm:py-2 sm:text-base md:px-6 md:py-2.5 md:text-lg lg:px-7 lg:py-3 lg:text-lg xl:px-8 xl:py-3 xl:text-xl">
                    Cancel
                </button>

                <button id="fas-confirm-btn" type="submit"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-4 py-2 text-sm text-[#fdfdfd] hover:brightness-110 sm:px-5 sm:py-2 sm:text-base md:px-6 md:py-2.5 md:text-lg lg:px-7 lg:py-3 lg:text-lg xl:px-8 xl:py-3 xl:text-xl">
                    Submit
                </button>
            </div>
        </form>
    </div>
</div>

<style>
    /* Ensure form maintains consistent spacing */
    .space-y-1.5>*+* {
        margin-top: 0.375rem;
    }

    /* Force dropdown options to render in black for better visibility */
    #fas-form-type,
    #fas-form-type option,
    #fas-form-type optgroup {
        color: #000;
    }
</style>
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const submissionPopup = document.getElementById('faculty-add-submission-popup');
        const submissionForm = document.getElementById('form-submission-form');
        const successModal = document.getElementById('upload-thesis-success');
        const errorModal = document.getElementById('upload-thesis-fail');
        const errorMessage = document.getElementById('upload-thesis-fail-message');
        if (errorMessage) {
            errorMessage.style.whiteSpace = 'pre-line';
        }
        const fileInput = document.getElementById('fas-file-input');
        const uploadBtn = document.getElementById('fas-upload-btn');
        const uploadedFile = document.getElementById('uploaded-file');
        const fileNameDisplay = document.getElementById('fas-file-name');
        const removeFileBtn = document.getElementById('fas-remove-file');
        const formTypeSelect = document.getElementById('fas-form-type');
        const noteInput = document.getElementById('fas-note');
        const noteWords = document.getElementById('fas-note-words');
        const cancelBtn = document.getElementById('fas-cancel-btn');
        const closeBtn = document.getElementById('fas-close-popup');

        // Helper function to show error modal
        function showErrorModal(title, message) {
            const kpopup = document.getElementById('universal-x-popup');
            const kTopText = document.getElementById('x-topText');
            const kSubText = document.getElementById('x-subText');
            const kConfirmBtn = document.getElementById('uniX-confirm-btn');

            kTopText.textContent = title;
            kSubText.textContent = message;
            submissionPopup.style.display = 'none';
            kpopup.style.display = 'flex';

            // Remove any existing listeners to prevent duplication
            const newConfirmBtn = kConfirmBtn.cloneNode(true);
            kConfirmBtn.parentNode.replaceChild(newConfirmBtn, kConfirmBtn);

            newConfirmBtn.addEventListener('click', function() {
                kpopup.style.display = 'none';
                submissionPopup.style.display = 'flex';
            });
        }

        // Open popup when add submission button is clicked
        document.addEventListener('click', function(e) {
            if (e.target?.id === 'faculty-add-submission-btn') {
                submissionPopup.style.display = 'flex';
                resetForm();
            }
        });

        // Close popup handlers
        document.getElementById('fas-close-popup')?.addEventListener('click', () => {
            submissionPopup.style.display = 'none';
            resetForm();
        });

        document.getElementById('fas-cancel-btn')?.addEventListener('click', (e) => {
            e.preventDefault();
            submissionPopup.style.display = 'none';
            resetForm();
        });

        // Optional note sanitization + 150-word limit
        function countWords(str) {
            const words = (str || '').trim().split(/\s+/).filter(Boolean);
            return words.length;
        }

        noteInput?.addEventListener('input', function() {
            const original = this.value;
            // Sanitize risky input in real-time (mirror approval popup behavior, allow common punctuation)
            let sanitized = original
                .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g, '') // remove control chars
                .replace(/[<>]/g, '') // strip angle brackets
                .replace(/`/g, '') // strip backticks
                .replace(/javascript:/gi, '') // strip protocol injections
                .replace(/on\w+=/gi, ''); // strip inline handlers

            // Enforce a permissive whitelist for symbols suitable for a note field
            sanitized = sanitized.replace(/[^\w\s\.,:;!?'"()\[\]\-_/&@#%+*=\n]/g, '');

            if (sanitized !== original) this.value = sanitized;

            const words = this.value.trim().split(/\s+/).filter(Boolean);
            if (words.length > 150) {
                this.value = words.slice(0, 150).join(' ');
                showErrorModal("Note Too Long",
                    'Maximum of 150 words allowed. Extra words have been removed.');
            }
            if (noteWords) {
                const count = this.value.trim() ? this.value.trim().split(/\s+/).filter(Boolean)
                    .length : 0;
                noteWords.textContent = `Words: ${count}/150`;
            }
        });

        // Prevent pasting risky content into note
        noteInput?.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            let cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/`/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                // allow letters, numbers, whitespace, and common punctuation/symbols for notes
                .replace(/[^\w\s\.,:;!?'"()\[\]\-_/&@#%+*=\n]/g, '');

            const el = noteInput;
            const start = el.selectionStart || 0;
            const end = el.selectionEnd || 0;
            const currentValue = el.value;
            const newValue = currentValue.substring(0, start) + cleanPaste + currentValue.substring(
                end);
            el.value = newValue;
            el.dispatchEvent(new Event('input'));
        });

        // File upload handling
        uploadBtn.addEventListener('click', () => {
            fileInput.click();
        });

        fileInput.addEventListener('change', () => {
            if (fileInput.files.length > 0) {
                const file = fileInput.files[0];
                const maxSize = 15 * 1024 * 1024; // 15MB
                const allowedExt = ['pdf', 'doc', 'docx', 'xls', 'xlsx'];
                const ext = file.name.split('.').pop()?.toLowerCase();

                if (!ext || !allowedExt.includes(ext)) {
                    showErrorModal("Invalid File Type",
                        'Allowed file types: PDF, Word (.doc/.docx), Excel (.xls/.xlsx).');
                    fileInput.value = '';
                    return;
                }

                if (file.size > maxSize) {
                    showErrorModal("File Too Large!",
                        `File size is ${(file.size / 1024 / 1024).toFixed(2)}MB. Maximum allowed size is 15MB.`
                    );
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

        function resetForm() {
            submissionForm.reset();
            uploadedFile.classList.add('hidden');
            removeFileBtn.classList.add('hidden');
            if (noteWords) noteWords.textContent = 'Words: 0/150';
        }

        // Form submission
        submissionForm?.addEventListener('submit', async function(e) {
            e.preventDefault();

            if (!formTypeSelect.value) {
                showErrorModal("Missing Form Type", 'Please select a form type before submitting.');
                return;
            }

            // Enforce note 150-word limit on submit
            if (noteInput && countWords(noteInput.value) > 150) {
                showErrorModal("Note Too Long",
                    'Please reduce your note to a maximum of 150 words.');
                return;
            }

            if (fileInput.files.length === 0) {
                showErrorModal("Missing File!",
                    'Please select a file to upload (.pdf, .doc, .docx, .xls, .xlsx).');
                return;
            }

            const submitBtn = document.getElementById('fas-confirm-btn');
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
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: formData,
                });

                const responseData = await response.json();
                if (!response.ok) {
                    console.error('Upload failed with status:', response.status);
                    console.error('Error response:', responseData);
                    throw responseData;
                }

                submissionPopup.style.display = 'none';
                successModal.style.display = 'flex';
            } catch (error) {
                console.error('Error:', error);
                if (error.errors) {
                    let messagesToShow = [];
                    for (const [, messages] of Object.entries(error.errors)) {
                        messagesToShow = messagesToShow.concat(messages);
                    }
                    errorMessage.textContent = messagesToShow.join('\n');
                } else {
                    errorMessage.textContent = error.message || 'An unexpected error occurred';
                }
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
        });

        // Success modal handlers
        document.getElementById('success-modal-ok-btn')?.addEventListener('click', () => {
            document.getElementById('upload-thesis-success').style.display = 'none';
            window.location.reload();
        });

        // Fail modal handlers
        document.getElementById('fail-modal-ok-btn')?.addEventListener('click', () => {
            document.getElementById('upload-thesis-fail').style.display = 'none';
        });
    });
</script>
