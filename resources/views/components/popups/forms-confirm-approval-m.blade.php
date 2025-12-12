<div id="forms-confirm-approval-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-[80vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-4 shadow-xl sm:max-w-[70vw] sm:p-6 md:max-w-[55vw] md:p-8 lg:max-w-[35vw] xl:max-w-[25vw]">

        <!-- Close Button -->
        <button id="forms-ca-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6 sm:h-7 sm:w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Step 1 -->
        <div id="forms-ca-step1">
            <div class="mt-2 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#575757"
                    class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div class="mt-6 text-center text-lg font-semibold sm:text-xl md:mt-8 md:text-2xl">
                <span class="text-[#575757]">Confirm form submission </span>
                <span class="text-[#28C90E]">approval<span class="text-[#575757]">?</span></span>
            </div>

            <input type="hidden" id="forms-submission-id-holder" />

            <div class="mt-3 text-center text-sm text-[#575757] sm:mt-4 sm:text-base">
                Approval will confirm this form submission for the next steps.
            </div>

            <div class="mt-6 flex flex-col justify-center gap-3 sm:mt-10 sm:flex-row sm:gap-5">
                <button id="forms-cancel1-btn"
                    class="rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2 text-white shadow hover:brightness-110 sm:px-8 sm:py-3">
                    Cancel
                </button>
                <button id="forms-ca-confirm1-btn"
                    class="rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-2 text-white shadow hover:brightness-110 sm:px-8 sm:py-3">
                    Confirm
                </button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="forms-ca-step2" class="hidden">
            <div class="mt-2 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#575757"
                    class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div class="mt-6 text-center text-lg font-semibold sm:text-xl md:mt-8 md:text-2xl">
                <span class="text-[#575757]">Add approval </span>
                <span class="text-[#28C90E]">remarks<span class="text-[#575757]">?</span></span>
            </div>

            <div class="mt-3 text-center text-sm text-[#575757] sm:mt-4 sm:text-base">
                You can add optional remarks for this approval.
            </div>

            <!-- Textarea with enhanced styling -->
            <div class="mt-6 sm:mt-8">
                <div class="relative">
                    <textarea id="forms-approve-remarks" placeholder="Enter your approval remarks here..."
                        class="max-h-[50vh] min-h-[20vh] w-full resize-none rounded-xl border-2 border-[#E5E5E5] px-4 py-3 text-sm text-[#575757] placeholder-[#A0A0A0] transition-all duration-300 focus:border-[#27C50D] focus:outline-none focus:ring-2 focus:ring-[#27C50D]/20 sm:px-5 sm:py-4 sm:text-base"
                        rows="6"></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-[#A0A0A0]">
                        <span id="forms-approve-char-count">0</span>/1000
                    </div>
                </div>
            </div>

            <!-- Forward-after-approve option -->
            <div class="mt-4 flex items-center gap-3">
                <input id="forms-forward-after-approve" type="checkbox"
                    class="h-4 w-4 cursor-pointer rounded border-gray-300 text-[#27C50D] focus:ring-[#27C50D]">
                <label for="forms-forward-after-approve" class="text-sm text-[#575757]">Forward this form after
                    approving</label>
            </div>

            <!-- Holders populated from list row -->
            <input type="hidden" id="forms-filename-holder" />

            <!-- Action buttons with enhanced styling -->
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-5">
                <button id="forms-ca-back-btn"
                    class="rounded-full border-2 border-[#A4A2A2] bg-transparent px-6 py-2 text-[#575757] transition-all duration-200 hover:bg-[#A4A2A2] hover:text-white sm:px-8 sm:py-3">
                    Back
                </button>
                <button id="forms-ca-confirm2-btn"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-2 text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:brightness-110 sm:px-8 sm:py-3">
                    Confirm Approval
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    (function() {
        const formsApprovePopup = document.getElementById('forms-confirm-approval-popup');
        const formsCaStep1 = document.getElementById('forms-ca-step1');
        const formsCaStep2 = document.getElementById('forms-ca-step2');
        const formsCaCloseBtn = document.getElementById('forms-ca-close-popup');
        const formsCancel1Btn = document.getElementById('forms-cancel1-btn');
        const formsCaConfirm1Btn = document.getElementById('forms-ca-confirm1-btn');
        const formsCaBackBtn = document.getElementById('forms-ca-back-btn');
        const formsCaConfirm2Btn = document.getElementById('forms-ca-confirm2-btn');
        const formsApproveRemarks = document.getElementById('forms-approve-remarks');
        const formsApproveCharCount = document.getElementById('forms-approve-char-count');
        const forwardAfterApprove = document.getElementById('forms-forward-after-approve');

        // Character count and sanitization for remarks
        formsApproveRemarks.addEventListener('input', function() {
            let value = this.value;

            // Remove risky characters
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .substring(0, 1000); // Limit length to 1000 characters

            // Update the textarea value if it was modified
            if (this.value !== value) {
                this.value = value;
            }

            // Update character counter
            const count = value.length;
            formsApproveCharCount.textContent = count;

            if (count > 900) {
                formsApproveCharCount.classList.add('text-orange-500');
            } else if (count > 950) {
                formsApproveCharCount.classList.remove('text-orange-500');
                formsApproveCharCount.classList.add('text-red-500');
            } else {
                formsApproveCharCount.classList.remove('text-orange-500', 'text-red-500');
            }
        });

        // Prevent pasting risky content
        formsApproveRemarks.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .substring(0, 1000);

            const currentValue = formsApproveRemarks.value;
            const newValue = currentValue.substring(0, formsApproveRemarks.selectionStart) +
                cleanPaste +
                currentValue.substring(formsApproveRemarks.selectionEnd);

            formsApproveRemarks.value = newValue.substring(0, 1000);
            formsApproveRemarks.dispatchEvent(new Event('input'));
        });

        // Close popup
        formsCaCloseBtn.addEventListener('click', function() {
            formsApprovePopup.style.display = 'none';
        });

        // Cancel button
        formsCancel1Btn.addEventListener('click', function() {
            formsApprovePopup.style.display = 'none';
        });

        // Step 1 to Step 2
        formsCaConfirm1Btn.addEventListener('click', function() {
            formsCaStep1.classList.add('hidden');
            formsCaStep2.classList.remove('hidden');
        });

        // Back to Step 1
        formsCaBackBtn.addEventListener('click', function() {
            formsCaStep2.classList.add('hidden');
            formsCaStep1.classList.remove('hidden');
        });

        // Final confirmation
        formsCaConfirm2Btn.addEventListener('click', function() {
            const formsSubmissionId = document.getElementById('forms-submission-id-holder').value;
            const remarks = formsApproveRemarks.value.trim();

            if (!remarks) {
                const popup = document.getElementById('universal-x-popup');
                const mainPopup = document.getElementById('forms-confirm-approval-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                const okBtn = document.getElementById('uniX-confirm-btn');
                xTopText.textContent = "Remarks field is empty";
                xSubText.textContent = "Please enter a remark before confirming.";
                mainPopup.style.display = 'none';
                popup.style.display = 'flex';
                if (okBtn) {
                    // Remove any existing listeners to prevent duplication
                    const newOkBtn = okBtn.cloneNode(true);
                    okBtn.parentNode.replaceChild(newOkBtn, okBtn);
                    newOkBtn.addEventListener('click', () => {
                        popup.style.display = 'none';
                        mainPopup.style.display = 'flex';
                    });
                }
                return;
            } else {
                // Disable buttons during processing
                formsCaConfirm2Btn.disabled = true;
                formsCaConfirm2Btn.classList.remove('cursor-pointer');
                formsCaConfirm2Btn.classList.add('opacity-50', 'cursor-not-allowed');
                formsCaConfirm2Btn.textContent = 'Processing...';

                formsCaBackBtn.disabled = true;
                formsCaBackBtn.classList.remove('cursor-pointer');
                formsCaBackBtn.classList.add('opacity-50', 'cursor-not-allowed');

                formsCaCloseBtn.disabled = true;
                formsCaCloseBtn.classList.remove('cursor-pointer');
                formsCaCloseBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            fetch(`/forms/${formsSubmissionId}/approve`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        remarks: remarks
                    })
                })
                .then(res => res.json())
                .then(() => {
                    /* remove the row visually */
                    console.log('Looking for button with data-id:', formsSubmissionId);
                    const button = document.querySelector(`button[data-id="${formsSubmissionId}"]`);
                    console.log('Found button:', button);
                    if (button) {
                        const row = button.closest('tr');
                        console.log('Found row:', row);
                        if (row) {
                            row.remove();
                        }
                    }

                    const mainPopup = document.getElementById('forms-confirm-approval-popup');

                    // If forwarding is selected, open the forward modal instead of showing success
                    const shouldForward = !!forwardAfterApprove?.checked;
                    if (shouldForward && window.openFormsForwardModal) {
                        const filename = document.getElementById('forms-filename-holder')?.value || '';
                        mainPopup.style.display = 'none';
                        window.openFormsForwardModal(formsSubmissionId, filename);
                        // Reset checkbox for next use
                        forwardAfterApprove.checked = false;
                        return;
                    }

                    // Default success flow
                    const succPopup = document.getElementById('universal-ok-popup');
                    const okTopText = document.getElementById('OKtopText');
                    const okSubText = document.getElementById('OKsubText');
                    const okBtn = document.getElementById('uniOK-confirm-btn');
                    okTopText.textContent = "Successfully Approved Form Submission!";
                    okSubText.textContent =
                        "The form submission has been approved and will proceed to the next steps.";
                    mainPopup.style.display = 'none';
                    succPopup.style.display = 'flex';
                    if (okBtn) {
                        // Remove any existing listeners to prevent duplication
                        const newOkBtn = okBtn.cloneNode(true);
                        okBtn.parentNode.replaceChild(newOkBtn, okBtn);
                        newOkBtn.addEventListener('click', () => {
                            succPopup.style.display = 'none';
                            mainPopup.style.display = 'none';
                            location.reload();
                        });
                    }
                    document.getElementById('forms-confirm-approval-popup').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error approving form submission:', error);
                    // Re-enable buttons on error
                    formsCaConfirm2Btn.disabled = false;
                    formsCaConfirm2Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    formsCaConfirm2Btn.classList.add('cursor-pointer');
                    formsCaConfirm2Btn.textContent = 'Confirm Approval';

                    formsCaBackBtn.disabled = false;
                    formsCaBackBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    formsCaBackBtn.classList.add('cursor-pointer');

                    formsCaCloseBtn.disabled = false;
                    formsCaCloseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    formsCaCloseBtn.classList.add('cursor-pointer');

                    // Show error modal
                    const popup = document.getElementById('universal-x-popup');
                    const mainPopup = document.getElementById('forms-confirm-approval-popup');
                    const xTopText = document.getElementById('x-topText');
                    const xSubText = document.getElementById('x-subText');
                    const okBtn = document.getElementById('uniX-confirm-btn');
                    xTopText.textContent = "Error Processing Request";
                    xSubText.textContent =
                        "An error occurred while processing the approval. Please try again.";
                    mainPopup.style.display = 'none';
                    popup.style.display = 'flex';
                    if (okBtn) {
                        // Remove any existing listeners to prevent duplication
                        const newOkBtn = okBtn.cloneNode(true);
                        okBtn.parentNode.replaceChild(newOkBtn, okBtn);
                        newOkBtn.addEventListener('click', () => {
                            popup.style.display = 'none';
                            mainPopup.style.display = 'flex';
                        });
                    }
                });
        });
    })();
</script>
