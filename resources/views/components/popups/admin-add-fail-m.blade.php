<!-- Fail Modal -->
<div id="admin-add-fail-m" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-2 sm:p-4">
    <div class="relative w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl max-h-[90vh] overflow-y-auto rounded-2xl bg-[#fdfdfd] p-4 sm:p-6 md:p-8 shadow-xl">

        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke-width="1.5" stroke="#575757"
                class="w-16 h-16 sm:w-20 sm:h-20 md:w-24 md:h-24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="mt-6 sm:mt-8 text-center text-lg sm:text-xl md:text-2xl font-semibold">
            <span class="text-[#575757]">Failed to add Admin Account!</span>
        </div>

        <!-- Dynamic Error Message -->
        <div id="admin-add-fail-message" class="mt-3 sm:mt-4 text-center text-sm sm:text-base text-[#575757]">
            <!-- Error message will be inserted here -->
        </div>

        <!-- Action Button -->
        <div class="mt-6 sm:mt-8 flex justify-center">
            <button id="aaf-ok-btn"
                class="w-full sm:w-auto cursor-pointer rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-6 py-2 sm:px-10 sm:py-3 text-sm sm:text-base text-[#fdfdfd] shadow hover:brightness-110">
                OK
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const confirmBtn = document.getElementById('aaf-ok-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                document.getElementById('admin-add-fail-m').style.display = 'none';
            });
        }
    });
</script>
