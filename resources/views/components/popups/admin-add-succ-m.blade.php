<!-- Success Modal -->
<div id="admin-add-succ-m" style="display: none;" class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="relative w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl rounded-2xl bg-[#fdfdfd] p-6 sm:p-8 shadow-xl max-h-[90vh] overflow-y-auto">
        
        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#575757"
                class="w-20 h-20 sm:w-24 sm:h-24 md:w-28 md:h-28 lg:w-30 lg:h-30">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="mt-8 text-center text-lg sm:text-xl md:text-2xl font-semibold">
            <span class="text-[#575757]">Added Admin Successfully!</span>
        </div>

        <!-- Message -->
        <div class="mt-4 sm:mt-5 text-center text-sm sm:text-base md:text-lg leading-relaxed">
            <span class="text-[#575757]">Admin <span id="added-admin-email" class="font-semibold text-[#575757]"></span>
                has been added successfully.</span>
        </div>

        <!-- Button -->
        <div class="mt-8 flex justify-center">
            <button id="aas-confirm-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-8 py-3 sm:px-10 sm:py-4 text-sm sm:text-base text-[#fdfdfd] shadow hover:brightness-110">
                Confirm
            </button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const confirmBtn = document.getElementById('aas-confirm-btn');
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                document.getElementById('add-admin-succ-m').style.display = 'none';
            });
        }
    });
</script>
