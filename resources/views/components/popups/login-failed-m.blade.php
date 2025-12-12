<!-- Login Fail Modal -->
<div id="login-fail-modal" style="display: none;" 
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4">
    <div class="relative max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 sm:p-8 shadow-xl overflow-y-auto">
        
        <!-- Icon -->
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                stroke-width="1" stroke="#575757"
                class="h-16 w-16 sm:h-20 sm:w-20"> 
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="mt-6 text-center text-lg font-semibold sm:mt-10 sm:text-xl">
            <span id="lf-title" class="text-[#575757]">Login failed.</span>
        </div>

        <!-- Message -->
        <div class="mt-4 text-center text-sm sm:mt-5 sm:text-base">
            <span id="lf-message" class="text-[#575757]">Please try again.</span>
        </div>

        <!-- Button -->
        <div class="mt-8 flex justify-center sm:mt-13">
            <button id="lf-confirm-btn"
                class="w-full sm:w-auto px-6 py-3 text-sm sm:px-10 sm:py-4 sm:text-base cursor-pointer rounded-full bg-gradient-to-r from-[#FF5656] to-[#DF0606] text-[#fdfdfd] shadow hover:brightness-110">
                Confirm
            </button>
        </div>
    </div>
</div>


<script>
    // Login fail modal handling
    document.addEventListener('DOMContentLoaded', function() {
        const loginFailModal = document.getElementById('login-fail-modal');
        const lfConfirmBtn = document.getElementById('lf-confirm-btn');

        // Close modal with confirm button
        if (lfConfirmBtn) {
            lfConfirmBtn.addEventListener('click', () => {
                loginFailModal.style.display = 'none';
            });
        }

        // Close when clicking outside modal
        loginFailModal.addEventListener('click', function(e) {
            if (e.target === loginFailModal) {
                loginFailModal.style.display = 'none';
            }
        });

        // Escape key to close
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape' && loginFailModal.style.display === 'flex') {
                loginFailModal.style.display = 'none';
            }
        });

        // Auto-show if session says so
        @if (session('showLoginFailModal'))
            loginFailModal.style.display = 'flex';
        @endif
    });
</script>
