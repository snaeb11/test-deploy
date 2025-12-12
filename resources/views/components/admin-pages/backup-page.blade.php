<!-- Backup Table -->
<x-popups.universal-option-m />
@if (session('success'))
    <script>
        const kpopup = document.getElementById('universal-ok-popup');
        const kConfirm = document.getElementById('uniOK-confirm-btn');
        const kTopText = document.getElementById('OKtopText');
        const kSubText = document.getElementById('OKsubText');
        kTopText.textContent = "Sucessful!";
        kSubText.textContent = "{{ session('success') }}";
        kpopup.style.display = 'flex';
        kConfirm.addEventListener('click', () => {
            kpopup.style.display = 'none';
        });
    </script>
@endif
@if (session('error'))
    <script>
        alert("{{ session('error') }}");
    </script>
@endif

<!-- Confirmation Modal Content (Hidden by default) -->
@if (auth()->user()->hasPermission('view-backup'))
    <div id="confirm-input-popup" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
        <div class="w-[90%] max-w-md space-y-4 rounded-lg bg-white p-6 text-center shadow-lg">
            <h2 class="text-xl font-bold text-[#1CA305]">Confirm Restore</h2>
            <p class="text-sm text-gray-700">Restoring will <span class="font-semibold text-red-600">overwrite your
                    current database</span>. This action cannot be undone.</p>

            <input type="text" autocomplete="off" id="confirm-overwrite-input"
                class="w-full rounded border border-gray-300 px-3 py-2"
                placeholder="Type OVERWRITE to confirm (case sensitive)">
            <input type="text" id="confirm-name-input" class="mt-2 w-full rounded border border-gray-300 px-3 py-2"
                placeholder="Enter your name">

            <div class="flex flex-col justify-end gap-2 pt-2 sm:flex-row">
                <button id="cancel-confirm-btn"
                    class="rounded bg-gray-300 px-4 py-2 text-sm hover:bg-gray-400">Cancel</button>
                <button id="confirm-submit-btn"
                    class="rounded bg-[#28C90E] px-4 py-2 text-sm text-white hover:brightness-110">Confirm
                    Restore</button>
            </div>
        </div>
    </div>
@endif

<main id="backup-table" class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    @if (auth()->user()->hasPermission('view-backup'))
        <div class="flex items-center justify-center px-4">
            <div
                class="flex w-full flex-col items-center justify-center space-y-6 rounded-lg border border-[#575757] bg-[#fdfdfd] p-6 sm:w-3/4 md:w-2/3 lg:w-1/2">
                <h1 class="m-4 text-2xl font-bold">Backup and Restore</h1>

                <form action="{{ route('admin.backup.reset') }}" method="post" class="w-full" id="backupAndResetForm">
                    @csrf

                    @if (auth()->user()->hasPermission('download-backup'))
                        <button type="submit" id="backup-and-reset-btn"
                            class="w-full cursor-pointer rounded bg-gradient-to-r from-[#28C90E] to-[#1CA305] px-4 py-2 text-white hover:brightness-110">Backup
                            and Reset</button>
                    @else
                        <button type="submit" class="hidden"></button>
                    @endif
                </form>

                <form id="backup-form" action="{{ route('admin.backup.download') }}" method="get" class="w-full">
                    @if (auth()->user()->hasPermission('download-backup'))
                        <button id="backup-btn"
                            class="hover: w-full cursor-pointer rounded bg-gradient-to-r from-[#28C90E] to-[#1CA305] px-4 py-2 text-white brightness-110">Backup</button>
                    @else
                        <button id="backup-btn" class="hidden"></button>
                    @endif
                </form>

                <!-- Restore Section -->
                @if (auth()->user()->hasPermission('allow-restore'))
                    <div class="w-full space-y-4">
                        <h3 class="text-lg font-semibold text-[#575757]">Restore Database</h3>

                        <!-- Option 1: Upload File -->
                        <div class="space-y-2">
                            <h4 class="font-medium text-[#575757]">Option 1: Upload Backup File</h4>
                            <div
                                class="flex flex-col items-center justify-center rounded border border-dashed border-[#575757] p-4">
                                <span class="py-1 text-sm font-semibold text-[#575757]">Choose a file or drag & drop it
                                    here.</span>
                                <span class="py-1 text-sm text-[#575757]">File type must be .sqlite or .sql</span>

                                <div id="hidden-class" class="mt-5 hidden items-center space-x-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="#575757" class="h-10 w-10">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <span id="backup-file-name" class="text-sm font-semibold text-[#575757]"></span>
                                </div>

                                <!-- File input lives inside the restore form below; we only keep the Browse button here -->
                                <button id="backup-browse-btn"
                                    class="mb-5 mt-5 min-h-[3vw] min-w-[10vw] rounded-lg border border-[#575757] bg-[#fdfdfd] font-semibold text-[#575757] transition-all duration-200 hover:brightness-95">
                                    Browse
                                </button>
                            </div>
                        </div>

                        <!-- Restore Button -->
                        <button type="button" id="restore-btn"
                            class="w-full cursor-pointer rounded bg-gradient-to-r from-[#28C90E] to-[#1CA305] px-4 py-2 text-white hover:brightness-110">Restore</button>
                    </div>

                    <!-- Restore Form -->
                    <form id="restore-form" action="{{ route('admin.backup.restore') }}" method="post"
                        enctype="multipart/form-data" class="hidden">
                        @csrf
                        <input type="file" name="backup_file" accept=".sqlite,.sql" id="visible-file-input">
                    </form>
                @else
                    <p class="text-red-600">You have no restore permissions.</p>
                @endif
            </div>
        </div>
    @else
        <p class="text-red-600">You have no view permissions for Backup.</p>
    @endif
</main>

<script>
    const visibleFileInput = document.getElementById('visible-file-input');
    const browseBtn = document.getElementById('backup-browse-btn');
    let selectedBackupType = 'upload';

    // Browse trigger w null checking
    if (browseBtn && visibleFileInput) {
        browseBtn.addEventListener('click', () => {
            visibleFileInput.click();
        });

        visibleFileInput.addEventListener('change', () => {
            const fileName = visibleFileInput.files[0]?.name || '';
            const fileNameLabel = document.getElementById('backup-file-name');
            const fileDisplayBox = document.getElementById('hidden-class');
            if (fileNameLabel && fileDisplayBox) {
                fileNameLabel.textContent = fileName;
                fileDisplayBox.classList.remove('hidden');
                fileDisplayBox.classList.add('flex');
            }
            selectedBackupType = 'upload';
        });
    }

    // No stored backups logic — upload only

    const restoreBtn = document.getElementById('restore-btn');
    const confirmPopup = document.getElementById('confirm-input-popup');
    const confirmSubmitBtn = document.getElementById('confirm-submit-btn');
    const cancelConfirmBtn = document.getElementById('cancel-confirm-btn');

    // Restore button opens modal
    if (restoreBtn && confirmPopup) {
        restoreBtn.addEventListener('click', () => {
            if (selectedBackupType === 'upload' && (!visibleFileInput?.files.length)) {
                const popup = document.getElementById('universal-x-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                xTopText.textContent = "No file chosen";
                xSubText.textContent = "Please select a backup file first.";
                popup.style.display = 'flex';
                return;
            }

            // Only upload path remains

            confirmPopup.classList.remove('hidden');
            confirmPopup.classList.add('flex');
        });
    }

    // Cancel restore
    const confirmOverwriteInput = document.getElementById('confirm-overwrite-input');
    const confirmNameInput = document.getElementById('confirm-name-input');
    if (cancelConfirmBtn) {
        cancelConfirmBtn.addEventListener('click', () => {
            confirmPopup?.classList.add('hidden');

            if (confirmOverwriteInput) confirmOverwriteInput.value = '';
            if (confirmNameInput) confirmNameInput.value = '';
        });
    }

    // Confirm restore with validation
    if (confirmSubmitBtn) {
        confirmSubmitBtn.addEventListener('click', () => {
            const confirmText = confirmOverwriteInput?.value.trim();
            const nameText = confirmNameInput?.value.trim();

            if (confirmText !== 'OVERWRITE') {
                const popup = document.getElementById('universal-x-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                const kButton = document.getElementById('uniX-confirm-btn');
                xTopText.textContent = "Wrong Confirmation";
                xSubText.textContent = "You must type 'OVERWRITE' exactly (case-sensitive).";
                confirmPopup.classList.add('hidden');
                popup.style.display = 'flex';

                if (kButton) {
                    kButton.addEventListener('click', () => {
                        popup.style.display = 'none';
                        confirmPopup.classList.remove('hidden');
                        confirmPopup.classList.add('flex');
                    });
                }
                return;
            }

            if (!nameText) {
                const popup = document.getElementById('universal-x-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                const kButton = document.getElementById('uniX-confirm-btn');
                xTopText.textContent = "Name Field Required";
                xSubText.textContent = "Please enter your name to confirm.";
                confirmPopup.classList.add('hidden');
                popup.style.display = 'flex';

                if (kButton) {
                    kButton.addEventListener('click', () => {
                        popup.style.display = 'none';
                        confirmPopup.classList.remove('hidden');
                        confirmPopup.classList.add('flex');
                    });
                }
                return;
            }

            // Submit upload restore
            const restoreForm = document.getElementById('restore-form');
            if (visibleFileInput && visibleFileInput.files.length > 0 && restoreForm) {
                restoreForm.submit();
            } else {
                alert('Error: Please select a file to upload');
            }
        });
    }
</script>
