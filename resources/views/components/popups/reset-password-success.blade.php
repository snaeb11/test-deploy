<div id="reset-password-success-modal" style="display: none;"
    class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 p-4">
    <div
        class="relative max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:p-8 md:min-w-[21vw] md:max-w-[25vw]">
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
                class="w-30 h-30">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <div class="mt-10 text-center text-lg font-semibold sm:text-xl">
            <span class="text-[#575757]">Password Successfully Reset!</span>
            <div class="mt-2 text-sm font-normal text-[#575757] sm:text-base">
                You can now log in using your new password.
            </div>
        </div>

        <div class="mt-13 flex justify-center">
            <button id="rp-success-confirm-btn"
                class="w-full cursor-pointer rounded-full bg-gradient-to-r from-[#4CAF50] to-[#2E7D32] px-8 py-3 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto sm:px-10 sm:py-4">
                OK
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const successModal = document.getElementById('reset-password-success-modal');
        const successButton = document.getElementById('rp-success-confirm-btn');

        // Close modal on button click and redirect to login
        successButton.addEventListener('click', () => {
            successModal.style.display = 'none';
            window.location.href = "{{ route('login') }}";
        });
    });
</script>
