<!-- Updated version using Tailwind CSS only (no Bootstrap CSS) -->
<!-- Cropper.js CSS (required, does not conflict heavily with Tailwind) -->
<link href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css" rel="stylesheet" id="cropper-css" />

<!-- Required JS Libraries -->
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@2.1.5/dist/tesseract.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>

@vite(['resources/css/app.css', 'resources/js/app.js'])

<!-- Image Edit Popup (Tailwind Only) -->
<div id="image-edit-popup" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="relative max-h-[90vh] min-w-[52vw] max-w-[60vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-8 shadow-xl">
        <button id="imageEdit-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex flex-col gap-6 md:flex-row">
            <!-- Left Side -->
            <div class="flex flex-1 flex-col">
                <h2 id="popup-title" class="mb-3 text-xl font-bold text-[#575757]">Image Extract</h2>
                <div
                    class="relative mb-4 flex max-h-[50vh] min-h-[30vh] items-center justify-center overflow-hidden rounded-xl border border-[#575757]">
                    <video id="webcam" autoplay playsinline
                        class="absolute inset-0 z-0 h-full w-full object-contain"></video>
                    <img id="capturedImage" class="absolute inset-0 z-10 hidden h-full w-full object-contain"
                        alt="Captured">
                </div>

                <!-- Camera Controls -->
                <div class="mb-3 flex flex-wrap justify-between gap-2">
                    <button id="openCameraBtn" class="rounded bg-blue-500 px-4 py-2 text-white hover:bg-blue-600">Open
                        Camera</button>
                    <select id="cameraSelect" class="rounded border border-gray-300 px-2 py-1"></select>
                    <label class="flex items-center space-x-2">
                        <input type="checkbox" id="flashToggle">
                        <span class="text-sm">Flash</span>
                    </label>
                    <button id="takePictureBtn"
                        class="hidden rounded bg-green-500 px-4 py-2 text-white hover:bg-green-600">Take
                        Picture</button>
                    <button id="retakeBtn"
                        class="hidden rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">Retake</button>
                    <button id="browseBn"
                        class="hidden rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">Browse</button>
                </div>

                <!-- Contrast Slider -->
                <div class="space-y-3">
                    <label for="bwSlider" class="text-sm font-medium text-[#575757]">Contrast Threshold</label>
                    <input type="range" id="bwSlider" min="0" max="255" value="80" class="w-full">
                </div>

                <div class="mt-3 flex justify-end gap-2">
                    <button id="cropImageBtn"
                        class="hidden rounded-lg bg-gradient-to-r from-[#FFC15C] to-[#FFA206] px-4 py-2 font-semibold text-white transition hover:brightness-110">Crop
                        Image</button>
                    <button id="extractTextBtn"
                        class="hidden rounded-lg bg-gradient-to-r from-[#FFC15C] to-[#FFA206] px-4 py-2 font-semibold text-white transition hover:brightness-110">Extract
                        Text</button>
                </div>
            </div>

            <!-- Right Side -->
            <div class="flex flex-1 flex-col">
                <h2 class="mb-3 text-xl font-bold text-[#575757]">Extracted Text</h2>
                <div
                    class="relative max-h-[50vh] min-h-[30vh] overflow-auto rounded border bg-white p-4 text-[#575757]">
                    <textarea id="ocrInput" class="min-h-[20vh] w-full resize-none bg-transparent text-sm outline-none"></textarea>
                    <div id="loadingSpinner"
                        class="absolute inset-0 z-10 hidden flex-col items-center justify-center bg-white/70">
                        <div class="h-6 w-6 animate-spin rounded-full border-4 border-blue-500 border-t-transparent">
                        </div>
                        <p class="mt-2 text-[#575757]">Extracting text, please wait...</p>
                    </div>
                </div>
                <div class="mt-3 flex justify-end">
                    <button id="useTextBtn" disabled
                        class="rounded bg-emerald-500 px-4 py-2 text-white hover:bg-emerald-600 disabled:cursor-not-allowed disabled:bg-gray-400">Add
                        Text to Form</button>
                </div>
            </div>
        </div>

        <!-- Crop Modal (Tailwind only, will be rendered dynamically) -->
        <div id="cropModal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
            <div class="w-[90vw] max-w-4xl rounded-xl bg-[#fdfdfd] p-4">
                <div class="mb-4 flex items-center justify-between border-b pb-2">
                    <h5 class="text-xl font-bold text-[#575757]">Crop Image</h5>
                    <button id="closeCropModal" class="text-xl font-bold text-red-500">&times;</button>
                </div>
                <div class="h-[60vh] w-full overflow-auto rounded border">
                    <img id="imageToCrop" src="#" alt="To Crop"
                        class="mx-auto max-h-full max-w-full object-contain">
                </div>
                <div class="mt-4 flex justify-end gap-2">
                    <button id="cancelCropBtn" class="rounded bg-gray-300 px-4 py-2 text-gray-700">Cancel</button>
                    <button id="confirmCropBtn"
                        class="rounded bg-yellow-500 px-4 py-2 text-white hover:bg-yellow-600">Crop</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // === Global Vars ===
        let videoStream = null;
        let currentTrack = null;
        let cropper = null;
        let originalImageDataUrl = "";

        const webcam = document.getElementById('webcam');
        const capturedImage = document.getElementById('capturedImage');
        const flashToggle = document.getElementById('flashToggle');
        const takePictureBtn = document.getElementById('takePictureBtn');
        const retakeBtn = document.getElementById('retakeBtn');
        const bwSlider = document.getElementById('bwSlider');
        const cropModal = document.getElementById('cropModal');
        const ocrInput = document.getElementById('ocrInput');
        const spinner = document.getElementById('loadingSpinner');
        const fileNameDisplay = document.getElementById('fileNameDisplay') || document.getElementById(
            'image-file-name');

        //amoghus
        //amoghus
        let bwThreshold = parseInt(bwSlider.value, 10);

        // === Close Popup ===
        document.getElementById('imageEdit-close-popup').addEventListener('click', () => {
            document.getElementById('image-edit-popup').style.display = 'none';
            extractTextBtn.classList.add('hidden');
            cropImageBtn.classList.add('hidden');
            fileNameDisplay.textContent = '';

            stopCamera();
            resetImageEditor();
        });

        // reset
        function resetImageEditor() {
            document.getElementById('ocrInput').value = '';
            document.getElementById('capturedImage').src = '';
            document.getElementById('capturedImage').classList.add('hidden');
            document.getElementById('webcam').classList.add('hidden');
            document.getElementById('takePictureBtn').classList.add('hidden');
            document.getElementById('retakeBtn').classList.add('hidden');
            document.getElementById('useTextBtn').disabled = true;

            if (window.originalImageDataUrl) window.originalImageDataUrl = '';

            if (window.videoStream) {
                window.videoStream.getTracks().forEach(track => track.stop());
                window.videoStream = null;
            }

            if (window.cropper) {
                window.cropper.destroy();
                window.cropper = null;
            }
        }

        // === Populate Camera List ===
        async function populateCameraList() {
            const devices = await navigator.mediaDevices.enumerateDevices();
            const videoDevices = devices.filter(d => d.kind === 'videoinput');
            const select = document.getElementById('cameraSelect');
            select.innerHTML = '';
            videoDevices.forEach((device, i) => {
                const option = document.createElement('option');
                option.value = device.deviceId;
                option.text = device.label || `Camera ${i + 1}`;
                select.appendChild(option);
            });
        }

        // === Switch Camera ===
        document.getElementById('cameraSelect').addEventListener('change', async () => {
            if (!videoStream) return;
            stopCamera();
            await startCamera(document.getElementById('cameraSelect').value);
        });

        // === Start Camera ===
        async function startCamera(deviceId) {
            try {
                videoStream = await navigator.mediaDevices.getUserMedia({
                    video: {
                        deviceId: {
                            exact: deviceId
                        },
                        facingMode: 'environment',
                        width: {
                            ideal: 1920
                        },
                        height: {
                            ideal: 1080
                        }
                    }
                });
                webcam.srcObject = videoStream;
                currentTrack = videoStream.getVideoTracks()[0];
                toggleFlashlight();
            } catch (err) {
                console.error("Error starting camera:", err);
                alert("Could not start camera: " + err.message);
            }
        }

        // === Open Camera Button ===
        document.getElementById('openCameraBtn').addEventListener('click', async () => {
            stopCamera();
            capturedImage.classList.add('hidden');
            webcam.classList.remove('hidden');
            takePictureBtn.classList.remove('hidden');
            retakeBtn.classList.add('hidden');

            try {
                const tempStream = await navigator.mediaDevices.getUserMedia({
                    video: true
                });
                tempStream.getTracks().forEach(track => track.stop());

                await populateCameraList();
                await startCamera(document.getElementById('cameraSelect').value);
            } catch (err) {
                alert("Camera access failed: " + err.message);
            }
        });

        // === Stop Camera ===
        function stopCamera() {
            if (videoStream) {
                videoStream.getTracks().forEach(t => t.stop());
                videoStream = null;
            }
        }

        // === Flashlight Toggle ===
        flashToggle.addEventListener('change', () => toggleFlashlight());

        function toggleFlashlight() {
            if (!currentTrack) return;
            const flashOn = flashToggle.checked;
            const capabilities = currentTrack.getCapabilities?.();
            if (capabilities?.torch) {
                currentTrack.applyConstraints({
                        advanced: [{
                            torch: flashOn
                        }]
                    })
                    .catch(e => alert("Torch not supported: " + e.message));
            } else if (flashOn) {
                alert("Torch not supported on this device.");
            }
        }

        // === Take Picture ===
        takePictureBtn.addEventListener('click', () => {
            const canvas = document.createElement('canvas');

            //add shit
            extractTextBtn.classList.remove('hidden');
            cropImageBtn.classList.remove('hidden');
            canvas.width = webcam.videoWidth || 640;
            canvas.height = webcam.videoHeight || 480;
            const ctx = canvas.getContext('2d');
            ctx.drawImage(webcam, 0, 0, canvas.width, canvas.height);
            originalImageDataUrl = canvas.toDataURL('image/png');
            capturedImage.src = originalImageDataUrl;
            webcam.classList.add('hidden');
            capturedImage.classList.remove('hidden');
            takePictureBtn.classList.add('hidden');
            retakeBtn.classList.remove('hidden');
            applyBWFilter();
        });

        // === Retake Button ===
        retakeBtn.addEventListener('click', () => {
            document.getElementById('openCameraBtn').click();
        });

        // === Apply B&W Filter ===
        bwSlider.addEventListener('input', () => {
            bwThreshold = parseInt(bwSlider.value, 10);
            applyBWFilter();
        });

        function applyBWFilter() {
            if (!originalImageDataUrl) return;
            const threshold = bwThreshold;
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                canvas.width = img.width;
                canvas.height = img.height;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(img, 0, 0);
                const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
                const data = imageData.data;
                for (let i = 0; i < data.length; i += 4) {
                    const avg = (data[i] + data[i + 1] + data[i + 2]) / 3;
                    const bw = avg > threshold ? 255 : 0;
                    data[i] = data[i + 1] = data[i + 2] = bw;
                }
                ctx.putImageData(imageData, 0, 0);
                capturedImage.src = canvas.toDataURL('image/png');
            };
            img.src = originalImageDataUrl;
        }

        // === Crop Image Button ===
        document.getElementById('cropImageBtn').addEventListener('click', () => {
            const img = document.getElementById('imageToCrop');
            img.src = capturedImage.src;
            cropModal.classList.remove('hidden');
            cropModal.classList.add('flex');

            setTimeout(() => {
                if (cropper) cropper.destroy();
                cropper = new Cropper(img, {
                    aspectRatio: NaN,
                    viewMode: 1,
                    autoCropArea: 1,
                    responsive: true,
                });
            }, 100);
        });

        // === Confirm Crop Button ===
        document.getElementById('confirmCropBtn').addEventListener('click', () => {
            if (!cropper) return;
            const croppedCanvas = cropper.getCroppedCanvas({
                width: 640,
                height: 480
            });
            const croppedDataURL = croppedCanvas.toDataURL('image/png');
            capturedImage.src = croppedDataURL;
            originalImageDataUrl = croppedDataURL;
            webcam.classList.add('hidden');
            capturedImage.classList.remove('hidden');
            cropModal.classList.add('hidden');
            cropper.destroy();
            cropper = null;
        });

        // === Cancel/Close Crop Modal ===
        document.getElementById('cancelCropBtn').addEventListener('click', closeCropModal);
        document.getElementById('closeCropModal').addEventListener('click', closeCropModal);

        function closeCropModal() {
            cropModal.classList.add('hidden');
            if (cropper) {
                cropper.destroy();
                cropper = null;
            }
        }

        document.getElementById('extractTextBtn').addEventListener('click', () => {
            document.getElementById('useTextBtn').disabled = true; // Disable before extraction
            runOCR(capturedImage.src);
        });

        // === OCR ===
        function runOCR(imageDataUrl) {
            spinner.classList.remove('hidden');
            spinner.classList.add('flex');
            ocrInput.value = '';

            Tesseract.recognize(imageDataUrl, 'eng', {
                logger: m => console.log(m)
            }).then(({
                data: {
                    text
                }
            }) => {
                ocrInput.value = text.trim();
            }).catch(err => {
                console.error("OCR Error:", err);
                ocrInput.value = "Error reading text.";
            }).finally(() => {
                spinner.classList.add('hidden');
                document.getElementById('useTextBtn').disabled = false; // Enable after extraction
            });
        }

        // OCR input sanitization
        ocrInput.addEventListener('input', function() {
            let value = this.value;

            // Remove risky characters
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g, ''); // Remove control characters

            // Update the textarea value if it was modified
            if (this.value !== value) {
                this.value = value;
            }
        });

        // Prevent pasting risky content into OCR input
        ocrInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .replace(/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]/g, '');

            const currentValue = ocrInput.value;
            const newValue = currentValue.substring(0, ocrInput.selectionStart) +
                cleanPaste +
                currentValue.substring(ocrInput.selectionEnd);

            ocrInput.value = newValue;
            ocrInput.dispatchEvent(new Event('input'));
        });

        document.getElementById('useTextBtn').addEventListener('click', () => {
            const extractedText = document.getElementById('ocrInput').value.trim();
            const targetId = window.selectedInputId;

            if (targetId) {
                const targetInput = document.getElementById(targetId);
                if (targetInput) {
                    targetInput.value = extractedText;
                } else {
                    console.warn('Target input not found for ID:', targetId);
                }
            } else {
                console.warn('window.selectedInputId is not set.');
            }

            document.getElementById('image-edit-popup').style.display = 'none';
            fileNameDisplay.textContent = '';
            extractTextBtn.classList.add('hidden');
            cropImageBtn.classList.add('hidden');
            resetImageEditor();
        });



        // === Init ===
        populateCameraList();
    });
</script>
