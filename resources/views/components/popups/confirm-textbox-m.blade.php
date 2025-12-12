<div id="confirm-textbox-popup" class="fixed inset-0 bg-black/50 items-center justify-center hidden z-50">
    <div class="bg-white p-6 rounded-lg shadow-lg w-[90%] max-w-md space-y-4 text-center">
        <h2 id="header-text" class="text-xl font-bold text-[#1CA305]">Confirm Backup and Reset</h2>
        <p id="normal-text" class="text-sm text-gray-700">Backup and rest will <span id="warning-text" class="font-semibold text-red-600">reset the database</span>. This action cannot be undone.</p>

        <input type="text" autocomplete="off" id="confirm-textbox-input"
            class="border border-gray-300 rounded w-full px-3 py-2"
            placeholder="Type BACKUPANDRESET to confirm (case sensitive)">
        <input type="text" id="ctp-confirm-name-input" class="border border-gray-300 rounded w-full px-3 py-2 mt-2"
            placeholder="Enter your name">

        <div class="flex flex-col sm:flex-row justify-end gap-2 pt-2">
            <button id="ctp-cancel-btn"
                class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400 text-sm">Cancel</button>
            <button id="ctp-confirm-submit-btn"
                class="px-4 py-2 bg-[#28C90E] text-white rounded hover:brightness-110 text-sm cursor-pointer disabled:bg-gray-400 disabled:cursor-not-allowed">Confirm</button>
        </div>
    </div>
</div>

<script>
    const closeBtn = document.getElementById('ctp-cancel-btn');
    closeBtn.addEventListener('click', function () {
        document.getElementById('confirm-textbox-popup').style.display = 'none';
    });
</script>
