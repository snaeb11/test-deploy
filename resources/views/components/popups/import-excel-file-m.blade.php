<x-popups.universal-ok-m />
<x-popups.universal-x-m />
<!-- Wrapper for the modal -->
<div id="import-excel-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div
        class="relative max-h-[90vh] w-[90vw] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-4 shadow-xl sm:w-[80vw] sm:p-6 md:w-[60vw] lg:w-[30vw]">

        <!-- Close Button -->
        <button id="ie-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Title -->
        <div class="w-full">
            <div
                class="mt-3 flex flex-col items-center justify-center space-y-2 sm:flex-row sm:items-center sm:justify-start sm:space-x-4 sm:space-y-0">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1"
                    stroke="currentColor" class="h-16 w-16 text-gray-700 sm:h-20 sm:w-20">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9.75v6.75m0 0-3-3m3 3 3-3m-8.25 6a4.5 4.5 0 0 1-1.41-8.775
                5.25 5.25 0 0 1 10.233-2.33 3 3 0 0 1 3.758 3.848A3.752
                3.752 0 0 1 18 19.5H6.75Z" />
                </svg>
                <span class="text-base font-bold text-[#575757] sm:text-lg">Import Excel File</span>
            </div>
        </div>

        <!-- STEP 1 -->
        <div id="ie-step-1" class="mt-6 flex justify-center">
            <div
                class="flex w-full flex-col items-center space-y-3 rounded-xl border-[1px] border-dashed border-[#575757] p-4">
                <span class="text-center text-sm font-semibold text-[#575757]">Choose a file.</span>
                <span class="text-center text-sm text-[#575757]">File type must be .XLSX</span>

                <button id="ie-browse-btn-1"
                    class="w-full rounded-lg border border-[#575757] bg-[#fdfdfd] py-2 font-semibold text-[#575757] transition-all duration-200 hover:brightness-95 sm:w-[60%]">
                    Browse
                </button>

                <input type="file" id="ie-file-input-1" accept=".xlsx" class="hidden" />
            </div>
        </div>

        <!-- STEP 2 (Initially Hidden) -->
        <div id="ie-step-2" class="mt-6 hidden justify-center">
            <div
                class="flex w-full flex-col items-center space-y-4 rounded-xl border-[1px] border-dashed border-[#575757] p-4">
                <div class="flex items-center justify-center space-x-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="#575757" class="h-8 w-8 sm:h-10 sm:w-10">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                    </svg>
                    <span id="ie-file-name" class="break-words text-center text-sm font-medium text-[#575757]"></span>
                </div>

                <button id="ie-browse-btn-2"
                    class="w-full rounded-lg border border-[#575757] bg-[#fdfdfd] py-2 font-semibold text-[#575757] transition-all duration-200 hover:brightness-95 sm:w-[60%]">
                    Browse Again
                </button>

                <input type="file" id="ie-file-input-2" accept=".xlsx" class="hidden" />

                <div class="mt-4 flex w-full flex-col justify-center gap-4 sm:w-[60%] sm:flex-row">
                    <button id="ie-cancel-btn2"
                        class="w-full rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2 text-white hover:from-[#cccaca] hover:to-[#888888] sm:w-auto">
                        Cancel
                    </button>
                    <button id="ie-confirm-btn"
                        class="flex-1 rounded-full bg-gradient-to-r from-[#28CA0E] to-[#1BA104] py-2 text-white transition-all duration-200 hover:from-[#3ceb22] hover:to-[#2db415]">
                        Confirm
                    </button>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- JavaScript -->
<script>
    function resetImportExcelModal() {
        document.getElementById('ie-file-name').textContent = '';
        document.getElementById('ie-file-input-1').value = '';
        document.getElementById('ie-file-input-2').value = '';

        document.getElementById('ie-step-1').classList.remove('hidden');
        document.getElementById('ie-step-1').classList.add('flex');
        document.getElementById('ie-step-2').classList.add('hidden');
        document.getElementById('ie-step-2').classList.remove('flex');

        document.getElementById('import-excel-popup').style.display = 'none';
    }

    document.getElementById('ie-close-popup').addEventListener('click', resetImportExcelModal);
    document.getElementById('ie-cancel-btn1').addEventListener('click', resetImportExcelModal);
    document.getElementById('ie-cancel-btn2').addEventListener('click', resetImportExcelModal);

    document.getElementById('ie-confirm-btn').addEventListener('click', async () => {
        const confirmBtn = document.getElementById('ie-confirm-btn');
        const fileInput = document.getElementById('ie-file-input-1');
        const file = fileInput.files[0];

        confirmBtn.disabled = true;
        confirmBtn.classList.add("opacity-50", "cursor-not-allowed");

        if (!file) {
            resetImportExcelModal();
            document.getElementById('import-excel-popup').style.display = 'none';
            const popup = document.getElementById('universal-x-popup');
            const xTopText = document.getElementById('x-topText');
            const xSubText = document.getElementById('x-subText');
            xTopText.textContent = "404";
            xSubText.textContent = "No file chosen";
            confirmBtn.disabled = false;
            confirmBtn.classList.remove("opacity-50", "cursor-not-allowed");
            return;
        }

        const form = new FormData();
        form.append('file', file);

        try {
            const res = await fetch('/inventory/import-excel', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: form,
            });

            const data = await res.json();
            if (!res.ok) {
                document.getElementById('import-excel-popup').style.display = 'none';
                const kpopup = document.getElementById('universal-x-popup');
                const kTopText = document.getElementById('x-topText');
                const kSubText = document.getElementById('x-subText');
                kTopText.textContent = "Error!";
                kSubText.textContent = data.errors ? data.errors.join("\n") : data.message;
                kpopup.style.display = 'flex';
                confirmBtn.disabled = false;
                confirmBtn.classList.remove("opacity-50", "cursor-not-allowed");
                return;
            }
            resetImportExcelModal();
            document.getElementById('import-excel-popup').style.display = 'none';
            const kpopup = document.getElementById('universal-ok-popup');
            const kTopText = document.getElementById('OKtopText');
            const kSubText = document.getElementById('OKsubText');
            kTopText.textContent = "Sucessful!";
            kSubText.textContent = data.message;
            kpopup.style.display = 'flex';
            location.reload();
        } catch (e) {
            resetImportExcelModal();
            document.getElementById('import-excel-popup').style.display = 'none';
            console.error(e);
            const popup = document.getElementById('universal-x-popup');
            const xTopText = document.getElementById('x-topText');
            const xSubText = document.getElementById('x-subText');
            xTopText.textContent = "Import Failed!";
            xSubText.textContent = "There was an error importing your Excel file. Please try again.";
            popup.style.display = 'flex';
            confirmBtn.disabled = false;
            confirmBtn.classList.remove("opacity-50", "cursor-not-allowed");
        }
    });

    document.getElementById('ie-browse-btn-1').addEventListener('click', () => {
        document.getElementById('ie-file-input-1').click();
    });

    document.getElementById('ie-browse-btn-2').addEventListener('click', () => {
        document.getElementById('ie-file-input-2').click();
    });

    const validExtension = /\.xlsx$/i;

    document.getElementById('ie-file-input-1').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && validExtension.test(file.name)) {
            document.getElementById('ie-file-name').textContent = `Selected: ${file.name}`;
            document.getElementById('ie-step-1').classList.add('hidden');
            document.getElementById('ie-step-1').classList.remove('flex');
            document.getElementById('ie-step-2').classList.remove('hidden');
            document.getElementById('ie-step-2').classList.add('flex');
        } else {
            resetImportExcelModal();
            document.getElementById('import-excel-popup').style.display = 'none';
            const popup = document.getElementById('universal-x-popup');
            const xTopText = document.getElementById('x-topText');
            const xSubText = document.getElementById('x-subText');
            xTopText.textContent = "Invalid File Type!";
            xSubText.textContent = "Only .xlsx files are allowed.";
            e.target.value = "";
        }
    });

    document.getElementById('ie-file-input-2').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file && validExtension.test(file.name)) {
            document.getElementById('ie-file-name').textContent = `Selected: ${file.name}`;
        } else {
            resetImportExcelModal();
            document.getElementById('import-excel-popup').style.display = 'none';
            const popup = document.getElementById('universal-x-popup');
            const xTopText = document.getElementById('x-topText');
            const xSubText = document.getElementById('x-subText');
            xTopText.textContent = "Invalid File Type!";
            xSubText.textContent = "Only .xlsx files are allowed.";
            e.target.value = "";
        }
    });
</script>
