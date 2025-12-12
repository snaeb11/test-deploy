<!-- Wrapper for the modal -->
<div id="export-file-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4">

    <div id="export-step1"
        class="relative max-h-[90vh] w-full overflow-y-auto rounded-2xl bg-[#fdfdfd] p-6 shadow-xl ring-1 ring-gray-200 sm:w-[60vw] sm:p-8 md:w-[50vw] lg:w-[22vw]">

        <!-- Close Button -->
        <button id="ef-close-btn" class="absolute right-4 top-4 text-[#575757] transition-colors hover:text-red-500"
            aria-label="Close">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Export Icon -->
        <div class="mt-6 flex justify-center sm:mt-8">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="#575757" class="h-16 w-16 rotate-[15deg] sm:h-20 sm:w-20">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M13.5 6H5.25A2.25 2.25 0 0 0 3 8.25v10.5A2.25 2.25 0 0 0 5.25 21h10.5A2.25 2.25 0 0 0 18 18.75V10.5m-10.5 6L21 3m0 0h-5.25M21 3v5.25" />
            </svg>
        </div>

        <!-- Text Message -->
        <div class="mt-4 text-center text-xl font-semibold text-[#575757] sm:mt-5">
            Export file
        </div>
        <p class="mt-1 text-center text-sm text-[#8a8a8a]">Choose a format to download your inventory.</p>

        <!-- Radio Options -->
        <div class="mt-6 flex justify-center sm:mt-8">
            <div class="w-full max-w-[240px] text-[#575757]">
                <label
                    class="inline-flex w-full items-center justify-between rounded-lg bg-white px-4 py-3 shadow-sm transition hover:bg-gray-50">
                    <span class="text-base">Word File</span>
                    <input type="radio" name="file_type" value="docx" class="h-5 w-5 text-red-600" checked>
                </label>
                <div class="h-3"></div>
                <label
                    class="inline-flex w-full items-center justify-between rounded-lg bg-white px-4 py-3 shadow-sm transition hover:bg-gray-50">
                    <span class="text-base">Excel File</span>
                    <input type="radio" name="file_type" value="excel" class="h-5 w-5 text-green-600">
                </label>
            </div>
        </div>

        <!-- Buttons -->
        <div class="mt-8 flex items-center justify-center gap-4 sm:mt-10 sm:flex-row">
            <button id="ef-cancel-btn"
                class="w-full rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2 text-white hover:from-[#cccaca] hover:to-[#888888] sm:w-auto">
                Cancel
            </button>
            <button id="ef-export-btn"
                class="w-full rounded-full bg-gradient-to-r from-[#28C90E] to-[#1CA305] px-6 py-2 text-white hover:brightness-110 sm:w-auto">
                Export
            </button>
        </div>

    </div>
</div>

<script>
    const efStep1 = document.getElementById('export-step1');

    document.getElementById('ef-cancel-btn').addEventListener('click', function() {
        document.getElementById('export-file-popup').style.display = 'none';
    });

    document.getElementById('ef-close-btn').addEventListener('click', function() {
        document.getElementById('export-file-popup').style.display = 'none';
    });

    document.getElementById('ef-export-btn').addEventListener('click', function() {
        const selectedType = document.querySelector('input[name="file_type"]:checked').value;

        let url = '';
        switch (selectedType) {
            case 'docx':
                url = '{{ route('inventory.export.docx') }}';
                break;
            case 'excel':
                url = '{{ route('inventory.export.excel') }}';
                break;
            case 'pdf':
                url = '{{ route('inventory.export.pdf') }}';
                break;
            default:
                alert("Unsupported export format.");
                return;
        }

        window.location.href = url;
        document.getElementById('export-file-popup').style.display = 'none';
    });
</script>
