<div id="forms-forward-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-auto bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-[90vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-4 shadow-xl sm:max-w-[70vw] sm:p-6 md:max-w-[55vw] md:p-8 lg:max-w-[35vw] xl:max-w-[30vw]">

        <!-- Close Button -->
        <button id="forms-forward-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6 sm:h-7 sm:w-7">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="mt-2 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="#1D4ED8"
                class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
            </svg>
        </div>

        <div class="mt-6 text-center text-lg font-semibold sm:text-xl md:mt-8 md:text-2xl">
            <span class="text-[#575757]">Forward</span>
            <span class="text-[#1D4ED8]">Form</span>
        </div>

        <input type="hidden" id="forms-forward-id-holder" />

        <!-- Recipient Email -->
        <div class="mt-6">
            <label for="forms-forward-email" class="mb-1 block text-sm font-medium text-[#575757]">Recipient's
                email</label>
            <input id="forms-forward-email" type="email" placeholder="recipient@example.com"
                class="w-full rounded-xl border-2 border-[#E5E5E5] px-4 py-3 text-sm text-[#575757] placeholder-[#A0A0A0] transition-all duration-300 focus:border-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-[#1D4ED8]/20 sm:px-5 sm:py-3 sm:text-base" />
        </div>

        <!-- Message -->
        <div class="mt-4">
            <label for="forms-forward-message" class="mb-1 block text-sm font-medium text-[#575757]">Message</label>
            <textarea id="forms-forward-message" rows="5" placeholder="Add a message (optional)"
                class="max-h-[40vh] min-h-[15vh] w-full resize-none rounded-xl border-2 border-[#E5E5E5] px-4 py-3 text-sm text-[#575757] placeholder-[#A0A0A0] transition-all duration-300 focus:border-[#1D4ED8] focus:outline-none focus:ring-2 focus:ring-[#1D4ED8]/20 sm:px-5 sm:py-3 sm:text-base"></textarea>
        </div>

        <!-- Attachment -->
        <div class="mt-4">
            <p class="mb-1 text-sm font-medium text-[#575757]">Attached file</p>
            <div id="forms-forward-attachment"
                class="flex items-center gap-2 rounded-lg border border-dashed border-gray-300 bg-white px-3 py-2">
                <svg class="h-5 w-5 flex-shrink-0 text-gray-500" fill="none" stroke="currentColor"
                    viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <a id="forms-forward-file-link" href="#" target="_blank"
                    class="truncate text-sm font-medium text-[#9D3E3E] hover:underline">No file</a>
            </div>
        </div>

        <!-- Actions -->
        <div class="mt-8 flex flex-col justify-center gap-3 sm:flex-row sm:gap-5">
            <button id="forms-forward-cancel-btn"
                class="rounded-full border-2 border-[#A4A2A2] bg-transparent px-6 py-2 text-[#575757] transition-all duration-200 hover:bg-[#A4A2A2] hover:text-white sm:px-8 sm:py-3">Cancel</button>
            <button id="forms-forward-submit-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#2563EB] to-[#1D4ED8] px-6 py-2 text-white shadow-lg transition-all duration-200 hover:shadow-xl hover:brightness-110 sm:px-8 sm:py-3">Send</button>
        </div>

    </div>
</div>

<script>
    (function() {
        const popup = document.getElementById('forms-forward-popup');
        const closeBtn = document.getElementById('forms-forward-close-popup');
        const cancelBtn = document.getElementById('forms-forward-cancel-btn');
        const submitBtn = document.getElementById('forms-forward-submit-btn');
        const emailInput = document.getElementById('forms-forward-email');
        const messageInput = document.getElementById('forms-forward-message');
        const idHolder = document.getElementById('forms-forward-id-holder');
        const fileLink = document.getElementById('forms-forward-file-link');

        // Email input sanitization
        emailInput.addEventListener('input', function() {
            let value = this.value;

            // Remove risky characters for email input
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .replace(/[^\w@.-]/g, ''); // Only allow alphanumeric, @, ., and - characters

            // Update the input value if it was modified
            if (this.value !== value) {
                this.value = value;
            }
        });

        // Message textarea sanitization
        messageInput.addEventListener('input', function() {
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
        });

        // Prevent pasting risky content into email
        emailInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .replace(/[^\w@.-]/g, '');

            const currentValue = emailInput.value;
            const newValue = currentValue.substring(0, emailInput.selectionStart) +
                cleanPaste +
                currentValue.substring(emailInput.selectionEnd);

            emailInput.value = newValue;
            emailInput.dispatchEvent(new Event('input'));
        });

        // Prevent pasting risky content into message
        messageInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .substring(0, 1000);

            const currentValue = messageInput.value;
            const newValue = currentValue.substring(0, messageInput.selectionStart) +
                cleanPaste +
                currentValue.substring(messageInput.selectionEnd);

            messageInput.value = newValue.substring(0, 1000);
            messageInput.dispatchEvent(new Event('input'));
        });

        window.openFormsForwardModal = function(formId, filename) {
            idHolder.value = formId;
            messageInput.value = '';
            emailInput.value = '';
            fileLink.textContent = filename || 'No file';
            fileLink.href = filename ? `/forms/${formId}/download` : '#';
            popup.style.display = 'flex';
        }

        function closeModal() {
            popup.style.display = 'none';
        }

        closeBtn.addEventListener('click', closeModal);
        cancelBtn.addEventListener('click', closeModal);

        submitBtn.addEventListener('click', async function() {
            const formId = idHolder.value;
            const email = (emailInput.value || '').trim();
            const message = (messageInput.value || '').trim();

            if (!email) {
                const popupX = document.getElementById('universal-x-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                xTopText.textContent = 'Recipient email required';
                xSubText.textContent = 'Please provide a valid recipient email to forward this form.';
                popup.style.display = 'none';
                popupX.style.display = 'flex';
                return;
            }

            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
            submitBtn.textContent = 'Sending...';

            // Disable cancel and close buttons
            cancelBtn.disabled = true;
            cancelBtn.classList.add('opacity-50', 'cursor-not-allowed');
            closeBtn.style.pointerEvents = 'none';
            closeBtn.style.opacity = '0.5';

            try {
                const res = await fetch(`/forms/${formId}/forward`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify({
                        to_email: email,
                        message: message
                    })
                });

                if (!res.ok) throw new Error(`HTTP ${res.status}`);
                const okPopup = document.getElementById('universal-ok-popup');
                const okTopText = document.getElementById('OKtopText');
                const okSubText = document.getElementById('OKsubText');
                const okBtn = document.getElementById('uniOK-confirm-btn');
                okTopText.textContent = 'Form forwarded successfully!';
                okSubText.textContent = 'The recipient has been notified via email.';
                popup.style.display = 'none';
                okPopup.style.display = 'flex';
                okBtn?.addEventListener('click', () => {
                    okPopup.style.display = 'none';
                    location.reload();
                });
            } catch (err) {
                console.error('Failed to forward form:', err);
                const xPopup = document.getElementById('universal-x-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                xTopText.textContent = 'Forward failed';
                xSubText.textContent = 'An error occurred while forwarding. Please try again.';
                popup.style.display = 'none';
                xPopup.style.display = 'flex';
            } finally {
                submitBtn.disabled = false;
                submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                submitBtn.textContent = 'Send';

                // Re-enable cancel and close buttons
                cancelBtn.disabled = false;
                cancelBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                closeBtn.style.pointerEvents = 'auto';
                closeBtn.style.opacity = '1';
            }
        });
    })();
</script>
