<div id="upload-thesis-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-black/50 px-4 sm:px-0">
    <div
        class="relative my-10 w-full rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:my-0 sm:max-h-[90vh] sm:min-w-[21vw] sm:max-w-[25vw] sm:p-8">

        <!-- Close Button -->
        <button id="pt-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Title -->
        <div>
            <div class="mt-3 flex items-center justify-start space-x-4">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="h-16 w-16 text-gray-700 sm:h-20 sm:w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775
                5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752
                3.752 0 0 1 18 19.5H6.75Z" />
                </svg>
                <span class="rounded py-1 text-lg font-bold text-[#575757]">Import thesis</span>
            </div>
        </div>

        <!-- STEP 1 -->
        <div id="pt-step-1" class="flex justify-center">
            <div class="flex w-full flex-col items-center rounded-xl border-dashed">
                <div class="flex w-full flex-col items-center rounded-xl border border-dashed border-[#575757] p-4">
                    <span class="mt-2 rounded py-1 text-center text-sm font-semibold text-[#575757]">Choose a
                        file.</span>
                    <span class="mt-2 rounded py-1 text-center text-sm text-[#575757]">File type must be PDF</span>

                    <button id="pt-browse-btn-1"
                        class="mb-5 mt-5 min-h-[3vw] w-full rounded-[10px] border border-[#575757] bg-[#fdfdfd] font-semibold text-[#575757] transition-all duration-200 hover:cursor-pointer hover:brightness-95 sm:w-[10vw]">
                        Browse
                    </button>

                    <input type="file" id="pt-file-input-1" accept=".pdf" class="hidden">
                </div>

                <!-- Buttons -->
                <div
                    class="mt-8 flex w-full flex-col justify-center space-x-0 space-y-2 sm:flex-row sm:space-x-6 sm:space-y-0">
                    <button id="pt-cancel-btn"
                        class="min-h-[3vw] w-full rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] text-[#fdfdfd] transition-all duration-200 hover:from-[#cccaca] hover:to-[#888888] sm:w-[10vw]">
                        Cancel
                    </button>
                </div>
            </div>
        </div>

        <!-- STEP 2 -->
        <div id="pt-step-2" class="hidden justify-center">
            <div class="flex w-full flex-col items-center rounded-xl border-dashed">
                <div class="flex w-full flex-col items-center rounded-xl border border-dashed border-[#575757] p-4">
                    <div class="mt-2 flex flex-col items-center text-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="#575757" class="mb-1 h-10 w-10">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                        <span id="pt-file-name" class="mt-2 break-words text-sm font-semibold text-[#575757]"></span>
                    </div>

                    <button id="pt-browse-btn-2"
                        class="mb-5 mt-5 min-h-[3vw] w-full rounded-[10px] border border-[#575757] bg-[#fdfdfd] font-semibold text-[#575757] transition-all duration-200 hover:brightness-95 sm:w-[10vw]">
                        Browse Again
                    </button>

                    <input type="file" id="pt-file-input-2" accept=".pdf" class="hidden">
                </div>

                <!-- Buttons -->
                <div class="m-5 flex w-full flex-col justify-center space-y-2 sm:flex-row sm:space-x-4 sm:space-y-0">
                    <button id="pt-cancel-btn1"
                        class="max-h-[7vh] min-h-[5vh] w-full rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] text-[#fdfdfd] transition-all duration-200 hover:from-[#cccaca] hover:to-[#888888] sm:w-[10vw]">
                        Cancel
                    </button>
                    <button id="pt-confirm-btn"
                        class="max-h-[7vh] min-h-[5vh] w-full rounded-full bg-gradient-to-r from-[#28CA0E] to-[#1BA104] text-[#fdfdfd] transition-all duration-200 hover:from-[#3ceb22] hover:to-[#2db415] sm:w-[10vw]">
                        Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- JavaScript -->
<script>
    function resetUploadThesisModal() {
        // Clear file name display
        document.getElementById('pt-file-name').textContent = '';

        // Reset file inputs
        document.getElementById('pt-file-input-1').value = '';
        document.getElementById('pt-file-input-2').value = '';

        // Reset step visibility
        document.getElementById('pt-step-1').classList.remove('hidden');
        document.getElementById('pt-step-1').classList.add('flex');
        document.getElementById('pt-step-2').classList.add('hidden');
        document.getElementById('pt-step-2').classList.remove('flex');

        // Hide the modal
        document.getElementById('upload-thesis-popup').style.display = 'none';
    }

    // Use the reset function for all close/cancel/confirm actions
    document.getElementById('pt-close-popup').addEventListener('click', resetUploadThesisModal);
    document.getElementById('pt-cancel-btn').addEventListener('click', resetUploadThesisModal);
    document.getElementById('pt-cancel-btn1').addEventListener('click', resetUploadThesisModal);
    document.getElementById('pt-confirm-btn').addEventListener('click', resetUploadThesisModal);

    // File browse buttons
    document.getElementById('pt-browse-btn-1').addEventListener('click', () => {
        document.getElementById('pt-file-input-1').click();
    });

    document.getElementById('pt-browse-btn-2').addEventListener('click', () => {
        document.getElementById('pt-file-input-2').click();
    });

    function isPDF(file) {
        return file && file.type === 'application/pdf';
    }

    // Step 1: Select file
    document.getElementById('pt-file-input-1').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!isPDF(file)) {
            alert('Only PDF files are allowed.');
            e.target.value = ''; // reset input
            return;
        }

        document.getElementById('pt-file-name').textContent = `Selected: ${file.name}`;
        document.getElementById('pt-step-1').classList.add('hidden');
        document.getElementById('pt-step-1').classList.remove('flex');
        document.getElementById('pt-step-2').classList.remove('hidden');
        document.getElementById('pt-step-2').classList.add('flex');
    });

    // Step 2: Re-browse
    document.getElementById('pt-file-input-2').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!isPDF(file)) {
            alert('Only PDF files are allowed.');
            e.target.value = '';
            return;
        }

        document.getElementById('pt-file-name').textContent = `Selected: ${file.name}`;
    });

    //---------------------
    let selectedFileName = ""; // global file name to pass to submission popup

    document.getElementById('pt-file-input-1').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (!file || file.type !== 'application/pdf') {
            alert('Only PDF files are allowed.');
            return;
        }

        selectedFileName = file.name;
        document.getElementById('pt-file-name').textContent = `Selected: ${file.name}`;

        // show step 2
        document.getElementById('pt-step-1').classList.add('hidden');
        document.getElementById('pt-step-1').classList.remove('flex');
        document.getElementById('pt-step-2').classList.remove('hidden');
        document.getElementById('pt-step-2').classList.add('flex');
    });

    // pass filename back to submission popup
    document.getElementById('pt-confirm-btn').addEventListener('click', () => {
        document.getElementById('upload-thesis-popup').style.display = 'none';

        // Dispatch the event (admin side will catch this)
        const event = new CustomEvent('thesisFileSelected', {
            detail: {
                fileName: selectedFileName
            }
        });
        window.dispatchEvent(event);

        // Optional: if used in user submission context
        const userPopup = document.getElementById('user-add-submission-popup');
        const fileNameSpan = document.getElementById('uas-file-name');
        const uploadedFileContainer = document.getElementById('uploaded-file');

        if (userPopup && fileNameSpan && uploadedFileContainer) {
            userPopup.style.display = 'flex';
            fileNameSpan.textContent = `Selected: ${selectedFileName}`;
            uploadedFileContainer.classList.remove('hidden');
            uploadedFileContainer.classList.add('flex');
        }

        resetUploadThesisModal();
    });
</script>
