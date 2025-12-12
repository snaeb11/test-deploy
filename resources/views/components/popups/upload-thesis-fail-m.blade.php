<div id="upload-thesis-fail" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 sm:p-6 md:p-8">

    <!-- Modal Card -->
    <div
        class="relative max-h-[90vh] w-full max-w-sm overflow-y-auto rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:max-w-md sm:p-8 md:max-w-lg lg:max-w-xl">

        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#D32F2F"
                class="h-16 w-16 sm:h-20 sm:w-20 md:h-24 md:w-24">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Error Title -->
        <div class="mt-6 text-center text-lg font-semibold text-[#575757] sm:mt-8 sm:text-xl md:text-2xl">
            Failed to submit form!
        </div>

        <!-- Dynamic Error Message -->
        <div id="upload-thesis-fail-message"
            class="mt-3 text-center text-sm text-[#575757] sm:mt-4 sm:text-base md:text-lg">
            <!-- Error message will be inserted here -->
        </div>

        <!-- Action Button -->
        <div class="mt-8 flex justify-center">
            <button id="fail-modal-ok-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-6 py-2 text-sm text-[#fdfdfd] shadow hover:brightness-110 sm:px-8 sm:py-3 sm:text-base md:px-10 md:py-4 md:text-lg">
                OK
            </button>
        </div>
    </div>
</div>
