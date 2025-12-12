<div id="account-creation-succ-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <!-- 🔹 CHANGED: Responsive width & padding -->
    <div class="relative max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 sm:p-8 shadow-xl">
        
        <!-- Icon -->
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                stroke-width="1" stroke="#575757"
                class="h-20 w-20 sm:h-28 sm:w-28"> 
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="mt-6 text-center text-lg font-semibold sm:mt-10 sm:text-xl">
            
            <span class="text-[#575757]">Account Created Successfully!</span>
        </div>

        <!-- Description -->
        <div class="mt-4 text-center text-sm sm:mt-5 sm:text-base">
            
            <span class="text-[#575757]">The account 
                <span id="account-name" class="font-semibold">--account name--</span> 
                has been successfully created.</span>
        </div>

        <!-- Button -->
        <div class="mt-8 flex justify-center sm:mt-13">
            <button id="acs-confirm-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-3 text-sm sm:px-10 sm:py-4 sm:text-base text-[#fdfdfd] shadow hover:brightness-110">
                Confirm
            </button>
        </div>
    </div>
</div>


<script>
    document.addEventListener('DOMContentLoaded', () => {
        const successPopup = document.getElementById('account-creation-succ-popup');
        const confirmBtn = document.getElementById('acs-confirm-btn');
        const firstTimeLoginPopup = document.getElementById('first-time-user-login-popup');
        const nameSpan = document.getElementById('account-name');
        const emailSpan = document.getElementById('user-email');

        // Show success popup if session exists
        @if (session('account_created'))
            if (successPopup) {
                successPopup.style.display = 'flex';
            }
            @if (session('account_name'))
                if (nameSpan) {
                    nameSpan.textContent = @json(session('account_name'));
                }
            @endif
            @if (session('account_email'))
                const rawEmail = @json(session('account_email'));
                if (emailSpan) {
                    emailSpan.textContent = rawEmail;
                }
            @endif
        @endif

        // Confirm button click: close success, open first-time login
        if (confirmBtn) {
            confirmBtn.addEventListener('click', () => {
                if (successPopup) successPopup.style.display = 'none';

                if (firstTimeLoginPopup) {
                    firstTimeLoginPopup.style.display = 'flex';
                }
            });
        }
    });
</script>
