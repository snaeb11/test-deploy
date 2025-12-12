<div id="confirm-rejection-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-[80vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-4 shadow-xl sm:max-w-[70vw] sm:p-6 md:max-w-[55vw] md:p-8 lg:max-w-[35vw] xl:max-w-[25vw]">

        <!-- Close Button -->
        <button id="cr-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6 sm:h-7 sm:w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Step 1 -->
        <div id="cr-step1">
            <div class="mt-2 flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="#575757" class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
            </div>

            <div class="mt-6 text-center text-lg font-semibold sm:text-xl md:mt-8 md:text-2xl">
                <span class="text-[#575757]">Confirm submission </span>
                <span class="text-[#ED2828]">rejection<span class="text-[#575757]">?</span></span>
            </div>

            <input type="hidden" id="submission-id-holder" />

            <div class="mt-3 text-center text-sm text-[#575757] sm:mt-4 sm:text-base">
                Rejection will remove this submission from further consideration.
            </div>

            <div class="mt-6 flex flex-col justify-center gap-3 sm:mt-10 sm:flex-row sm:gap-5">
                <button id="cr-cancel1-btn"
                    class="rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2 text-white shadow hover:brightness-110 sm:px-8 sm:py-3">
                    Cancel
                </button>
                <button id="cr-confirm1-btn"
                    class="rounded-full bg-gradient-to-r from-[#FE5252] to-[#E10C0C] px-6 py-2 text-white shadow hover:brightness-110 sm:px-8 sm:py-3">
                    Confirm
                </button>
            </div>
        </div>

        <!-- Step 2 -->
        <div id="cr-step2" class="hidden">
            <!-- Header with icon -->
            <div class="mt-4 flex items-center gap-3 sm:mt-6">
                <div
                    class="flex h-10 w-10 items-center justify-center rounded-full bg-gradient-to-r from-[#FE5252] to-[#E10C0C]">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="white" class="h-5 w-5">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z" />
                    </svg>
                </div>
                <div class="text-left">
                    <h3 class="text-lg font-semibold text-[#575757] sm:text-xl md:text-2xl">Add Remarks</h3>
                    <p class="text-sm text-[#8B8B8B]">Please provide your rejection remarks</p>
                </div>
            </div>

            <!-- Textarea with enhanced styling -->
            <div class="mt-6 sm:mt-8">
                <div class="relative">
                    <textarea id="reject-remarks" placeholder="Enter your rejection remarks here..."
                        class="max-h-[50vh] min-h-[20vh] w-full resize-none rounded-xl border-2 border-[#E5E5E5] px-4 py-3 text-sm text-[#575757] placeholder-[#A0A0A0] transition-all duration-300 focus:border-[#FE5252] focus:outline-none focus:ring-2 focus:ring-[#FE5252]/20 sm:px-5 sm:py-4 sm:text-base"
                        rows="6"></textarea>
                    <div class="absolute bottom-3 right-3 text-xs text-[#A0A0A0]">
                        <span id="reject-char-count">0</span>/1000
                    </div>
                </div>
            </div>

            <!-- Action buttons with enhanced styling -->
            <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-5">
                <button id="cr-back-btn"
                    class="rounded-full border-2 border-[#A4A2A2] bg-transparent px-6 py-2 text-[#575757] transition-all duration-200 hover:bg-[#A4A2A2] hover:text-white sm:px-8 sm:py-3">
                    Back
                </button>
                <button id="cr-confirm2-btn"
                    class="rounded-full bg-gradient-to-r from-[#FE5252] to-[#E10C0C] px-6 py-2 text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:brightness-110 sm:px-8 sm:py-3">
                    Confirm Rejection
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const popup = document.getElementById('confirm-rejection-popup');
        const step1 = document.getElementById('cr-step1');
        const step2 = document.getElementById('cr-step2');

        // Step 1 → Step 2
        document.getElementById('cr-confirm1-btn').addEventListener('click', () => {
            step1.classList.add('hidden');
            step2.classList.remove('hidden');
        });

        // Step 2 → Step 1 (Back button)
        document.getElementById('cr-back-btn').addEventListener('click', () => {
            step2.classList.add('hidden');
            step1.classList.remove('hidden');
        });

        // Character counter and input restriction for remarks
        const rejectTextarea = document.getElementById('reject-remarks');
        const rejectCharCount = document.getElementById('reject-char-count');

        // Restrict risky characters in real-time
        rejectTextarea.addEventListener('input', (e) => {
            let value = e.target.value;

            // Remove risky characters
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .substring(0, 1000); // Limit length to 1000 characters

            // Update the textarea value if it was modified
            if (e.target.value !== value) {
                e.target.value = value;
            }

            // Update character counter
            const length = value.length;
            rejectCharCount.textContent = length;

            if (length > 900) {
                rejectCharCount.classList.add('text-orange-500');
            } else if (length > 950) {
                rejectCharCount.classList.remove('text-orange-500');
                rejectCharCount.classList.add('text-red-500');
            } else {
                rejectCharCount.classList.remove('text-orange-500', 'text-red-500');
            }
        });

        // Prevent pasting risky content
        rejectTextarea.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .substring(0, 1000);

            const currentValue = rejectTextarea.value;
            const newValue = currentValue.substring(0, rejectTextarea.selectionStart) +
                cleanPaste +
                currentValue.substring(rejectTextarea.selectionEnd);

            rejectTextarea.value = newValue.substring(0, 1000);
            rejectTextarea.dispatchEvent(new Event('input'));
        });

        // Close on Cancel or X
        document.getElementById('cr-close-popup').addEventListener('click', () => {
            popup.style.display = 'none';
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
        });

        document.getElementById('cr-cancel1-btn').addEventListener('click', () => {
            popup.style.display = 'none';
            step1.classList.remove('hidden');
            step2.classList.add('hidden');
        });

        const confirmDeclineBtn = document.getElementById('cr-confirm2-btn');
        const cancelDeclineBtn = document.getElementById('cr-back-btn');
        const closeDeclineBtn = document.getElementById('cr-close-popup');

        confirmDeclineBtn.addEventListener('click', () => {
            const remarks = document.getElementById('reject-remarks').value.trim();
            const currentSubmissionId = document.getElementById('submission-id-holder').value;
            console.log('Current Submission ID:', currentSubmissionId);

            if (!remarks) {
                const popup = document.getElementById('universal-x-popup');
                const mainPopup = document.getElementById('confirm-rejection-popup');
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
                confirmDeclineBtn.disabled = true;
                confirmDeclineBtn.classList.remove('cursor-pointer');
                confirmDeclineBtn.classList.add('opacity-50', 'cursor-not-allowed');
                confirmDeclineBtn.textContent = 'Processing...';

                cancelDeclineBtn.disabled = true;
                cancelDeclineBtn.classList.remove('cursor-pointer');
                cancelDeclineBtn.classList.add('opacity-50', 'cursor-not-allowed');

                closeDeclineBtn.disabled = true;
                closeDeclineBtn.classList.remove('cursor-pointer');
                closeDeclineBtn.classList.add('opacity-50', 'cursor-not-allowed');
            }

            fetch(`/submission/${currentSubmissionId}/reject`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: JSON.stringify({
                        remarks
                    })
                })
                .then(res => res.json())
                .then(() => {
                    /* remove the row visually */
                    document.querySelector(`button[data-id="${currentSubmissionId}"]`)
                        ?.closest('tr').remove();
                    const succPopup = document.getElementById('universal-ok-popup');
                    const mainPopup = document.getElementById('confirm-rejection-popup');
                    const okTopText = document.getElementById('OKtopText');
                    const okSubText = document.getElementById('OKsubText');
                    const okBtn = document.getElementById('uniOK-confirm-btn');
                    okTopText.textContent = "Declined Submission.";
                    okSubText.textContent =
                        "The submission has been declined and will no longer be processed.";
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
                    document.getElementById('confirm-rejection-popup').style.display = 'none';
                });
        });
    });
</script>
