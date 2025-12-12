<div id="email-invalid-popup" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
    <div class="w-full max-w-md md:min-w-[21vw] md:max-w-[25vw] bg-[#fdfdfd] rounded-2xl shadow-xl relative p-6 sm:p-8">
        
        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                 stroke-width="1.5" stroke="#575757" class="w-16 h-16 sm:w-20 sm:h-20">
                <path stroke-linecap="round" stroke-linejoin="round" 
                      d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="text-center text-lg sm:text-xl font-semibold mt-8 sm:mt-10">
            <span class="text-[#575757]">Signup failed.</span>
        </div>

        <!-- Message -->
        <div class="text-center mt-4 sm:mt-5 text-sm sm:text-base font-light text-[#575757]">
            Email invalid. Please use your university provided email address.
        </div>

        <!-- Confirm Button -->
        <div class="mt-8 sm:mt-12 flex justify-center">
            <button id="eminvalid-confirm-btn" 
                class="w-full sm:w-auto px-8 sm:px-10 py-3 sm:py-4 rounded-full text-[#fdfdfd] bg-gradient-to-r from-[#FF5656] to-[#DF0606] shadow hover:brightness-110 cursor-pointer">
                Confirm
            </button>
        </div>
    </div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', () => {
        const confirmBtn = document.getElementById('eminvalid-confirm-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                document.getElementById('email-invalid-popup').style.display = 'none';
            });
        }
    });
</script>