<x-popups.better-on-phone-m />
<div id="scan-option-popup" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">
    <div
        class="w-[50vw] md:w-[30vw] lg:w-[30vw] max-h-[95vh] bg-[#fdfdfd] rounded-2xl shadow-xl relative p-6 overflow-y-auto">

        <button id="scanOpt-close-popup" class="absolute top-4 right-4 text-[#575757] hover:text-red-500 z-10">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="w-6 h-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 items-start justify-center">

            <!-- Column 1 -->
            <div class="text-center">
                <div
                    class="bg-[#fdfdfd] rounded-lg text-left px-5 py-2 text-[#575757] font-semibold hover:brightness-110">
                    <span>Upload image</span>
                </div>
                <div class="mt-2 w-full border border-dashed border-[#575757] rounded-xl p-4">
                    <div class="flex flex-col items-center">
                        <span class="text-[#575757] text-sm mt-2 font-semibold text-center">Choose a file or drag & drop
                            it here.</span>
                        <span class="text-[#575757] text-sm mt-2 mb-4 text-center">File type must be .PNG, .JPG, .JPEG,
                            .HEIC</span>

                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="#575757" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159m-1.5-1.5 1.409-1.409a2.25 2.25 0 0 1 3.182 0l2.909 2.909m-18 3.75h16.5a1.5 1.5 0 0 0 1.5-1.5V6a1.5 1.5 0 0 0-1.5-1.5H3.75A1.5 1.5 0 0 0 2.25 6v12a1.5 1.5 0 0 0 1.5 1.5Zm10.5-11.25h.008v.008h-.008V8.25Zm.375 0a.375.375 0 1 1-.75 0 .375.375 0 0 1 .75 0Z" />
                            </svg>
                            <span id="image-file-name" class="text-[#575757] text-sm mt-2 font-semibold"></span>
                        </div>

                        <button id="upload-image-btn"
                            class="w-full md:w-[10vw] mt-6 mb-2 rounded-[10px] bg-[#fdfdfd] text-[#575757] font-semibold hover:brightness-95 border border-[#575757] transition-all duration-200">
                            Browse
                        </button>

                        <input type="file" id="image-input" accept=".png,.jpg,.jpeg,.heic" class="hidden" />
                        <input type="file" id="ir-file-input-1" class="hidden">
                    </div>
                </div>
            </div>

            <!-- Column 2 -->
            <div class="text-center">
                <div
                    class="bg-[#fdfdfd] rounded-lg text-left px-5 py-2 text-[#575757] font-semibold hover:brightness-110">
                    <span>Scan document</span>
                </div>
                <div class="mt-2 w-full border border-dashed border-[#575757] rounded-xl p-4">
                    <div class="flex flex-col items-center">
                        <span class="text-[#575757] text-sm mt-2 font-semibold text-center">Scan your document.</span>
                        <span class="text-[#575757] text-sm mt-2 mb-4 text-center">This will open your device
                            camera.</span>

                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="#575757" class="w-10 h-10">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z" />
                            </svg>
                        </div>

                        <button id="scan-docu-upload-btn"
                            class="w-full md:w-[10vw] mt-6 mb-2 rounded-[10px] bg-[#fdfdfd] text-[#575757] font-semibold hover:brightness-95 border border-[#575757] transition-all duration-200">
                            Scan
                        </button>

                        <input type="file" id="ir-file-input-1" class="hidden">
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const scanOptionPopup = document.getElementById('scan-option-popup');
        const editImagePopup = document.getElementById('image-edit-popup');
        const popupTitle = document.getElementById('popup-title');
        const fileNameDisplay = document.getElementById('image-file-name');

        const fileInput = document.getElementById('image-input');
        const uploadBtn = document.getElementById('upload-image-btn');
        const scanCamera = document.getElementById('scan-docu-upload-btn');

        const betterOnPhonePopup = document.getElementById('better-on-phone');
        const betterOnPhoneConfirmBtn = document.getElementById('bop-confirm-btn');

        scanCamera.addEventListener('click', () => {
            if (window.innerWidth >= 1024) {
                scanOptionPopup.style.display = 'none';
                betterOnPhonePopup.style.display = 'flex';
                return;
            }
            betterOnPhonePopup.style.display = 'none';
            scanOptionPopup.style.display = 'none';
            editImagePopup.style.display = 'flex';
            popupTitle.textContent = window.selectedScanTitle || "Untitled";
            showCameraUI();
        });

        betterOnPhoneConfirmBtn.addEventListener('click', () => {
            betterOnPhonePopup.style.display = 'none';
            scanOptionPopup.style.display = 'none';
            editImagePopup.style.display = 'flex';
            popupTitle.textContent = window.selectedScanTitle || "Untitled";
            showCameraUI();
        });

        function hideCameraUI() {
            const openCameraBtn = document.getElementById('openCameraBtn');
            const flashToggle = document.getElementById('flashToggle');
            const bwSlider = document.getElementById('bwSlider');
            const cameraSelect = document.getElementById('cameraSelect');
            const takePictureBtn = document.getElementById('takePictureBtn');
            const retakeBtn = document.getElementById('retakeBtn');
            const browseBtn = document.getElementById('browseBn');
            const cropImageBtn = document.getElementById('cropImageBtn');
            const extractText = document.getElementById('extractTextBtn');

            if (openCameraBtn) openCameraBtn.classList.add('hidden');
            if (flashToggle) flashToggle.closest('label')?.classList.add('hidden');
            if (bwSlider) bwSlider.parentElement?.classList.add('hidden');
            if (cameraSelect) cameraSelect.classList.add('hidden');
            if (takePictureBtn) takePictureBtn.classList.add('hidden');
            if (retakeBtn) retakeBtn.classList.add('hidden');
            if (browseBtn) browseBtn.classList.remove('hidden');
            if (cropImageBtn) cropImageBtn.classList.remove('hidden');
            if (extractText) extractText.classList.remove('hidden');
        }

        function showCameraUI() {
            const openCameraBtn = document.getElementById('openCameraBtn');
            const flashToggle = document.getElementById('flashToggle');
            const bwSlider = document.getElementById('bwSlider');
            const cameraSelect = document.getElementById('cameraSelect');
            const browseBtn = document.getElementById('browseBn');

            if (openCameraBtn) openCameraBtn.classList.remove('hidden');
            if (flashToggle) flashToggle.closest('label')?.classList.remove('hidden');
            if (bwSlider) bwSlider.parentElement?.classList.remove('hidden');
            if (browseBtn) browseBtn.classList.add('hidden');
            if (cameraSelect) cameraSelect.classList.remove('hidden');
        }

        function resetModal() {
            fileInput.value = '';
            document.getElementById('ir-file-input-1').value = '';
            fileNameDisplay.textContent = '';
            popupTitle.textContent = 'Untitled';
            if (capturedImage) capturedImage.src = '';
            // if (capturedImage) capturedImage.classList.add('hidden');
            // if (webcam) webcam.classList.remove('hidden');
        }

        document.getElementById('scanOpt-close-popup').addEventListener('click', () => {
            scanOptionPopup.style.display = 'none';
            resetModal();
        });

        uploadBtn.addEventListener('click', () => {
            fileInput.click();
            resetModal();
        });

        fileInput.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (!file) {
                console.log("User canceled file selection.");
                return;
            }

            fileNameDisplay.textContent = file.name;

            const reader = new FileReader();
            reader.onload = function(e) {
                const imgDataUrl = e.target.result;

                capturedImage.src = imgDataUrl;
                capturedImage.classList.remove('hidden');
                webcam.classList.add('hidden');

                const retakeBtn = document.getElementById('retakeBtn');
                retakeBtn.classList.remove('hidden');

                const browseBtn = document.getElementById('browseBn');
                browseBtn.onclick = () => fileInput.click();

                window.originalImageDataUrl = imgDataUrl;

                scanOptionPopup.style.display = 'none';
                editImagePopup.style.display = 'flex';
                popupTitle.textContent = window.selectedScanTitle || "Untitled";

                hideCameraUI();
            };

            reader.readAsDataURL(file);
        });
    });
</script>
