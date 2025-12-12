<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Laravel Webcam OCR Capture</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />

  <!-- Bootstrap & Cropper.js CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/css/bootstrap.min.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.css"/>

  <style>
    video, canvas, img {
      border: 1px solid #ddd;
      max-width: 100%;
      height: auto;
    }
    #camera-section {
      margin-top: 20px;
    }
    #capturedImage, #croppedImage {
      max-width: 90%;
      height: auto;
    }
    .cropper-container-wrapper {
      max-width: 100%;
      max-height: 60vh;
      overflow: hidden;
    }
    #imageToCrop {
      width: 100%;
      height: auto;
      display: block;
      max-height: 60vh;
      object-fit: contain;
    }
    @media (max-width: 767px) {
      .modal-dialog {
        max-width: 95%;
        margin: 1rem auto;
      }
    }
    textarea#ocrInput {
      width: 100%;
      min-height: 80px;
      resize: vertical;
    }
  </style>
</head>
<body>
<div class="container mt-4">
  <h2 class="text-center mb-4">Laravel Webcam Capture with OCR</h2>

  <div class="text-center">
    <button id="openCameraBtn" class="btn btn-primary mb-3">
      Open Camera
    </button>
  </div>

  <!-- Flashlight Checkbox -->
  <div class="mb-3 text-center">
    <div class="form-check d-inline-block">
      <input type="checkbox" id="flashToggle" class="form-check-input" />
      <label class="form-check-label" for="flashToggle">
        Turn on flashlight
      </label>
    </div>
  </div>

  <!-- Camera Select Dropdown -->
  <select id="cameraSelect" class="form-control mb-3 d-none"></select>

  <!-- Video Stream -->
  <div id="camera-section" class="d-none text-center">
    <video id="webcam" autoplay playsinline></video>
    <br />
    <button id="takePictureBtn" class="btn btn-success mt-3">
      Take Picture
    </button>
  </div>

  <!-- Captured Image -->
  <div id="result-section" class="mt-4 d-none text-center">
    <h4>Captured Image:</h4>
    <img id="capturedImage" src="" alt="Captured Image" class="mb-3" />
    <br />

    <!-- B/W Filters after capture -->
    <div class="mb-3 text-center">
      <h5>B/W Filter</h5>
      <div class="form-check form-check-inline">
        <input class="form-check-input bw-radio" type="radio" name="bwThreshold" id="lowLight" value="105" checked>
        <label class="form-check-label" for="lowLight">Low light - 105</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input bw-radio" type="radio" name="bwThreshold" id="mediumLight" value="80">
        <label class="form-check-label" for="mediumLight">Medium light - 80</label>
      </div>
      <div class="form-check form-check-inline">
        <input class="form-check-input bw-radio" type="radio" name="bwThreshold" id="highLight" value="50">
        <label class="form-check-label" for="highLight">High light - 50</label>
      </div>
    </div>

    <button id="cropImageBtn" class="btn btn-warning mb-3">
      Crop Image
    </button>
    <button id="extractTextBtn" class="btn btn-primary mb-3">
      Extract Text
    </button>

    <h5>Extracted Text:</h5>
    <textarea id="ocrInput" class="form-control" placeholder="OCR text will appear here..."></textarea>
    <br>
    <button id="useTextBtn" class="btn btn-success">Use this text</button>
    <button id="cancelTextBtn" class="btn btn-secondary">Cancel</button>
  </div>

  <!-- Loading Spinner -->
  <div id="loadingSpinner" class="text-center mt-3 d-none">
    <div class="spinner-border text-primary" role="status"></div>
    <p class="mt-2">Extracting text, please wait...</p>
  </div>

  <!-- Cropping Modal -->
  <div class="modal fade" id="cropModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Crop Image</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span>&times;</span>
          </button>
        </div>
        <div class="modal-body text-center">
          <div class="cropper-container-wrapper">
            <img id="imageToCrop" />
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" id="confirmCropBtn" class="btn btn-success">
            Confirm Crop
          </button>
          <button type="button" class="btn btn-secondary" data-dismiss="modal">
            Cancel
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- JS Libraries -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.6.2/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/cropperjs@1.5.13/dist/cropper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/tesseract.js@5.0.1/dist/tesseract.min.js"></script>

<script>
  let videoStream = null;
  let cropper = null;
  let currentTrack = null;
  let originalImageDataUrl = "";

  document.getElementById('openCameraBtn').addEventListener('click', async () => {
    try {
      const devices = await navigator.mediaDevices.enumerateDevices();
      const videoDevices = devices.filter(device => device.kind === 'videoinput');
      const select = document.getElementById('cameraSelect');
      select.innerHTML = '';

      videoDevices.forEach(device => {
        const option = document.createElement('option');
        option.value = device.deviceId;
        option.text = device.label || `Camera ${select.length + 1}`;
        select.appendChild(option);
      });

      if (videoDevices.length > 1) {
        select.classList.remove('d-none');
        select.addEventListener('change', () => startCamera(select.value));
      } else if (videoDevices.length === 1) {
        startCamera(videoDevices[0].deviceId);
      } else {
        alert("No cameras detected.");
      }

      document.getElementById('camera-section').classList.remove('d-none');
    } catch (err) {
      console.error("Error accessing devices:", err);
      alert("Could not list cameras. Check permissions.");
    }
  });

  async function startCamera(deviceId) {
    if (videoStream) {
      videoStream.getTracks().forEach(t => t.stop());
    }

    try {
      videoStream = await navigator.mediaDevices.getUserMedia({
        video: {
          deviceId: { exact: deviceId },
          facingMode: "environment",
          width: { ideal: 1920 },
          height: { ideal: 1080 }
        }
      });

      const video = document.getElementById('webcam');
      video.srcObject = videoStream;

      currentTrack = videoStream.getVideoTracks()[0];
      toggleFlashlight();

    } catch (err) {
      console.error("Error starting camera:", err);
      alert("Could not start selected camera.");
    }
  }

  document.getElementById('flashToggle').addEventListener('change', () => {
    toggleFlashlight();
  });

  async function toggleFlashlight() {
    if (!currentTrack) return;

    const flashOn = document.getElementById('flashToggle').checked;
    const capabilities = currentTrack.getCapabilities();

    if (capabilities && capabilities.torch) {
      try {
        await currentTrack.applyConstraints({
          advanced: [{ torch: flashOn }]
        });
      } catch (e) {
        console.error("Torch error:", e);
        alert("Torch control failed.");
      }
    } else if (flashOn) {
      alert("Torch not supported on this device/browser.");
    }
  }

  document.getElementById('takePictureBtn').addEventListener('click', () => {
    const video = document.getElementById('webcam');

    const canvas = document.createElement('canvas');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;

    const context = canvas.getContext('2d');
    context.drawImage(video, 0, 0, canvas.width, canvas.height);

    originalImageDataUrl = canvas.toDataURL('image/png');
    document.getElementById('capturedImage').src = originalImageDataUrl;
    document.getElementById('result-section').classList.remove('d-none');

    applyBWFilter();
  });

  document.querySelectorAll('.bw-radio').forEach(radio => {
    radio.addEventListener('change', () => {
      applyBWFilter();
    });
  });

  function applyBWFilter() {
    if (!originalImageDataUrl) return;

    const threshold = getThresholdValue();
    const img = new Image();
    img.onload = function() {
      const canvas = document.createElement('canvas');
      canvas.width = img.width;
      canvas.height = img.height;

      const context = canvas.getContext('2d');
      context.drawImage(img, 0, 0, img.width, img.height);

      const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
      const data = imageData.data;
      for (let i = 0; i < data.length; i += 4) {
        const avg = (data[i] + data[i+1] + data[i+2]) / 3;
        const bw = avg > threshold ? 255 : 0;
        data[i] = data[i+1] = data[i+2] = bw;
      }
      context.putImageData(imageData, 0, 0);

      document.getElementById('capturedImage').src = canvas.toDataURL('image/png');
    };
    img.src = originalImageDataUrl;
  }

  function getThresholdValue() {
    const radios = document.getElementsByName("bwThreshold");
    for (const r of radios) {
      if (r.checked) return parseInt(r.value);
    }
    return 80;
  }

  document.getElementById('cropImageBtn').addEventListener('click', () => {
    openCropper(document.getElementById('capturedImage').src);
  });

  function openCropper(imageSrc) {
    $('#cropModal').modal('show');
    const img = document.getElementById('imageToCrop');
    img.src = imageSrc;

    $('#cropModal').on('shown.bs.modal', () => {
      cropper = new Cropper(img, {
        aspectRatio: NaN,
        viewMode: 1,
        autoCropArea: 1,
        responsive: true,
      });
    });

    $('#cropModal').on('hidden.bs.modal', () => {
      if (cropper) {
        cropper.destroy();
        cropper = null;
      }
    });
  }

  document.getElementById('confirmCropBtn').addEventListener('click', () => {
    const croppedCanvas = cropper.getCroppedCanvas({
      width: 640,
      height: 480
    });
    const croppedDataURL = croppedCanvas.toDataURL('image/png');
    $('#cropModal').modal('hide');
    document.getElementById('capturedImage').src = croppedDataURL;
  });

  document.getElementById('extractTextBtn').addEventListener('click', () => {
    runOCR(document.getElementById('capturedImage').src);
  });

  document.getElementById('useTextBtn').addEventListener('click', () => {
    alert("Text submitted: \n" + document.getElementById('ocrInput').value);
  });

  document.getElementById('cancelTextBtn').addEventListener('click', () => {
    document.getElementById('ocrInput').value = '';
    document.getElementById('result-section').classList.add('d-none');
  });

  function runOCR(imageDataUrl) {
    document.getElementById('loadingSpinner').classList.remove('d-none');
    document.getElementById('ocrInput').value = '';

    Tesseract.recognize(
      imageDataUrl,
      'eng',
      {
        logger: m => console.log(m),
        tessedit_pageseg_mode: Tesseract.PSM.SINGLE_BLOCK,
        tessedit_char_whitelist: 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789'
      }
    ).then(({ data: { text } }) => {
      document.getElementById('ocrInput').value = text;
    }).catch(err => {
      console.error("OCR error:", err);
      document.getElementById('ocrInput').value = "Error reading text.";
    }).finally(() => {
      document.getElementById('loadingSpinner').classList.add('d-none');
    });
  }
</script>
</body>
</html>
