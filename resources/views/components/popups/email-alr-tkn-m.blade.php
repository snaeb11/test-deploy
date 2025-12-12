<div id="email-taken-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <!-- 🔹 CHANGED: responsive width & padding -->
    <div class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:p-8">

        <!-- Icon -->
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
                class="h-16 w-16 sm:h-20 sm:w-20">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="mt-6 text-center text-lg font-semibold sm:mt-10 sm:text-xl">
            <span class="text-[#575757]">Signup failed.</span>
        </div>

        <!-- Message -->
        <div class="mt-4 text-center text-sm font-light text-[#575757] sm:mt-5 sm:text-base">
            Email already taken. Please try again.
        </div>

        <!-- Button -->
        <div class="sm:mt-13 mt-8 flex justify-center">
            <button id="emtkn-confirm-btn"
                class="w-full cursor-pointer rounded-full bg-gradient-to-r from-[#FF5656] to-[#DF0606] px-6 py-3 text-sm text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto sm:px-10 sm:py-4 sm:text-base">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const confirmBtn = document.getElementById('emtkn-confirm-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                document.getElementById('email-taken-popup').style.display = 'none';
            });
        }
    });
</script>
