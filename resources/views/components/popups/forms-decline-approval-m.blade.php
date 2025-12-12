<div id="forms-confirm-rejection-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-[80vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-4 shadow-xl sm:max-w-[70vw] sm:p-6 md:max-w-[55vw] md:p-8 lg:max-w-[35vw] xl:max-w-[25vw]">

        <!-- Close Button -->
        <button id="forms-cr-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6 sm:h-7 sm:w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Step 1 -->
        <div id="forms-cr-step1">
            <div class="mt-2 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#575757"
                    class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div class="mt-6 text-center text-lg font-semibold sm:text-xl md:mt-8 md:text-2xl">
                <span class="text-[#575757]">Confirm form submission </span>
                <span class="text-[#ED2828]">rejection<span class="text-[#575757]">?</span></span>
            </div>

            <input type="hidden" id="forms-submission-id-holder" />

            <div class="mt-3 text-center text-sm text-[#575757] sm:mt-4 sm:text-base">
                Rejection will remove this form submission from further consideration.
            </div>

            <div class="mt-6 flex flex-col justify-center gap-3 sm:mt-10 sm:flex-row sm:gap-5">
                <button id="forms-cr-cancel1-btn"
                    class="rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2 text-white shadow hover:brightness-110 sm:px-8 sm:py-3">
                    Cancel
                </button>
                <button id="forms-cr-confirm1-btn"
                    class="rounded-full bg-gradient-to-r from-[#FE5252] to-[#E10C0C] px-6 py-2 text-white shadow hover:brightness-110 sm:px-8 sm:py-3">
                    Confirm
                </button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="forms-cr-step2" class="hidden">
            <div class="mt-2 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#575757"
                    class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div class="mt-6 text-center text-lg font-semibold sm:text-xl md:mt-8 md:text-2xl">
                <span class="text-[#575757]">Add rejection </span>
                <span class="text-[#ED2828]">remarks<span class="text-[#575757]">?</span></span>
            </div>

            <div class="mt-3 text-center text-sm text-[#575757] sm:mt-4 sm:text-base">
                You can add optional remarks for this rejection.
            </div>

            <!-- Textarea with enhanced styling -->
            <div class="mt-6 sm:mt-8">
                <div class="relative">
                    <textarea id="forms-reject-remarks" placeholder="Enter your rejection remarks here..."
                        class="max-h-[50vh] min-h-[20vh] w-full resize-none rounded-xl border-2 border-[#E5E5E5] px-4 py-3 text-sm text-[#575757] placeholder-[#A0A0A0] transition-all duration-300 focus:border-[#FE5252] focus:outline-none focus:ring-2 focus:ring-[#FE5252]/20 sm:px-5 sm:py-4 sm:text-base"
                        rows="6"></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-[#A0A0A0]">
                        <span id="forms-reject-char-count">0</span>/1000
                    </div>
                </div>
            </div>

            <!-- Action buttons with enhanced styling -->
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-5">
                <button id="forms-cr-back-btn"
                    class="rounded-full border-2 border-[#A4A2A2] bg-transparent px-6 py-2 text-[#575757] transition-all duration-200 hover:bg-[#A4A2A2] hover:text-white sm:px-8 sm:py-3">
                    Back
                </button>
                <button id="forms-cr-confirm2-btn"
                    class="cursor-pointer rounded-full bg-gradient-to-r from-[#FE5252] to-[#E10C0C] px-6 py-2 text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:brightness-110 sm:px-8 sm:py-3">
                    Confirm Rejection
                </button>
            </div>
        </div>

    </div>
</div>

<script>
    (function() {
        const formsRejectPopup = document.getElementById('forms-confirm-rejection-popup');
        const formsCrStep1 = document.getElementById('forms-cr-step1');
        const formsCrStep2 = document.getElementById('forms-cr-step2');
        const formsCrCloseBtn = document.getElementById('forms-cr-close-popup');
        const formsCrCancel1Btn = document.getElementById('forms-cr-cancel1-btn');
        const formsCrConfirm1Btn = document.getElementById('forms-cr-confirm1-btn');
        const formsCrBackBtn = document.getElementById('forms-cr-back-btn');
        const formsCrConfirm2Btn = document.getElementById('forms-cr-confirm2-btn');
        const formsRejectRemarks = document.getElementById('forms-reject-remarks');
        const formsRejectCharCount = document.getElementById('forms-reject-char-count');

        // Character count and sanitization for remarks
        formsRejectRemarks.addEventListener('input', function() {
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
            formsRejectCharCount.textContent = count;

            if (count > 900) {
                formsRejectCharCount.classList.add('text-orange-500');
            } else if (count > 950) {
                formsRejectCharCount.classList.remove('text-orange-500');
                formsRejectCharCount.classList.add('text-red-500');
            } else {
                formsRejectCharCount.classList.remove('text-orange-500', 'text-red-500');
            }
        });

        // Prevent pasting risky content
        formsRejectRemarks.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .substring(0, 1000);

            const currentValue = formsRejectRemarks.value;
            const newValue = currentValue.substring(0, formsRejectRemarks.selectionStart) +
                cleanPaste +
                currentValue.substring(formsRejectRemarks.selectionEnd);

            formsRejectRemarks.value = newValue.substring(0, 1000);
            formsRejectRemarks.dispatchEvent(new Event('input'));
        });

        // Close popup
        formsCrCloseBtn.addEventListener('click', function() {
            formsRejectPopup.style.display = 'none';
        });

        // Cancel button
        formsCrCancel1Btn.addEventListener('click', function() {
            formsRejectPopup.style.display = 'none';
        });

        // Step 1 to Step 2
        formsCrConfirm1Btn.addEventListener('click', function() {
            formsCrStep1.classList.add('hidden');
            formsCrStep2.classList.remove('hidden');
        });

        // Back to Step 1
        formsCrBackBtn.addEventListener('click', function() {
            formsCrStep2.classList.add('hidden');
            formsCrStep1.classList.remove('hidden');
        });

        // Final confirmation
        formsCrConfirm2Btn.addEventListener('click', function() {
            const formsSubmissionId = document.getElementById('forms-submission-id-holder').value;
            const remarks = formsRejectRemarks.value.trim();

            if (!remarks) {
                const popup = document.getElementById('universal-x-popup');
                const mainPopup = document.getElementById('forms-confirm-rejection-popup');
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
                formsCrConfirm2Btn.disabled = true;
                formsCrConfirm2Btn.classList.remove('cursor-pointer');
                formsCrConfirm2Btn.classList.add('opacity-50', 'cursor-not-allowed');
                formsCrConfirm2Btn.textContent = 'Processing...';

                formsCrBackBtn.disabled = true;
                formsCrBackBtn.classList.remove('cursor-pointer');
                formsCrBackBtn.classList.add('opacity-50', 'cursor-not-allowed');

                formsCrCloseBtn.disabled = true;
                formsCrCloseBtn.classList.remove('cursor-pointer');
                formsCrCloseBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            fetch(`/forms/${formsSubmissionId}/reject`, {
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

                    const succPopup = document.getElementById('universal-ok-popup');
                    const mainPopup = document.getElementById('forms-confirm-rejection-popup');
                    const okTopText = document.getElementById('OKtopText');
                    const okSubText = document.getElementById('OKsubText');
                    const okBtn = document.getElementById('uniOK-confirm-btn');
                    okTopText.textContent = "Successfully Rejected Form Submission!";
                    okSubText.textContent =
                        "The form submission has been rejected and will not proceed to the next steps.";
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
                    document.getElementById('forms-confirm-rejection-popup').style.display = 'none';
                })
                .catch(error => {
                    console.error('Error rejecting form submission:', error);
                    // Re-enable buttons on error
                    formsCrConfirm2Btn.disabled = false;
                    formsCrConfirm2Btn.classList.remove('opacity-50', 'cursor-not-allowed');
                    formsCrConfirm2Btn.classList.add('cursor-pointer');
                    formsCrConfirm2Btn.textContent = 'Confirm Rejection';

                    formsCrBackBtn.disabled = false;
                    formsCrBackBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    formsCrBackBtn.classList.add('cursor-pointer');

                    formsCrCloseBtn.disabled = false;
                    formsCrCloseBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    formsCrCloseBtn.classList.add('cursor-pointer');

                    // Show error modal
                    const popup = document.getElementById('universal-x-popup');
                    const mainPopup = document.getElementById('forms-confirm-rejection-popup');
                    const xTopText = document.getElementById('x-topText');
                    const xSubText = document.getElementById('x-subText');
                    const okBtn = document.getElementById('uniX-confirm-btn');
                    xTopText.textContent = "Error Processing Request";
                    xSubText.textContent =
                        "An error occurred while processing the rejection. Please try again.";
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
