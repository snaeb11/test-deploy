<div id="reset-password-fail-modal" style="display: none;"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:p-8 md:min-w-[21vw] md:max-w-[25vw]">
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
                class="w-30 h-30">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <div class="mt-10 text-center text-lg font-semibold sm:text-xl">
            <span id="rp-fail-title" class="text-[#575757]">Password Reset Failed</span>
            <div id="rp-fail-message" class="mt-2 text-sm font-normal text-[#575757] sm:text-base">
                An error occurred while resetting your password.
            </div>
        </div>

        <div class="mt-13 flex justify-center">
            <button id="rp-fail-confirm-btn"
                class="w-full cursor-pointer rounded-full bg-gradient-to-r from-[#FF5656] to-[#DF0606] px-8 py-3 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto sm:px-10 sm:py-4">
                OK
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const failModal = document.getElementById('reset-password-fail-modal');
        const failButton = document.getElementById('rp-fail-confirm-btn');

        // Close modal on button click
        failButton.addEventListener('click', () => {
            failModal.style.display = 'none';
        });
    });
</script>
