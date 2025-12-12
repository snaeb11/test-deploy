<div id="password-change-success-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div
        class="relative max-h-[90vh] w-[90vw] max-w-[22rem] rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:min-w-[21vw] sm:max-w-[25vw] sm:p-8">
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
                class="w-30 h-30">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
        </div>
        <div class="mt-10 text-center text-xl font-semibold">
            <span class="text-[#575757]">Password changed successfully!</span>
        </div>
        <div class="mt-13 flex justify-center">
            <button id="success-passChange-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#4CAF50] to-[#2E7D32] px-10 py-4 text-[#fdfdfd] shadow hover:brightness-110">
                OK
            </button>
        </div>
    </div>
</div>
