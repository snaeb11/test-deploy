<div id="logout-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 sm:p-6 transition-opacity duration-200 ease-out"
    role="dialog" aria-modal="true" aria-labelledby="logout-title" aria-describedby="logout-description">

    <div
        class="relative w-full max-w-sm sm:max-w-md rounded-2xl bg-white p-6 sm:p-8 shadow-2xl transform transition-all duration-200 scale-95">
        <!-- Icon -->
        <div class="mt-10 sm:mt-12 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-16 w-16 sm:h-20 sm:w-20 text-[#575757]" fill="none"
                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15M12 9l-3 3m0 0 3 3m-3-3h12.75" />
            </svg>
        </div>

        <!-- Title -->
        <h2 id="logout-title" class="mt-6 sm:mt-8 text-center text-xl sm:text-2xl font-bold text-[#575757]">
            Confirm Logout
        </h2>

        <!-- Description -->
        <p id="logout-description" class="mt-2 text-center text-sm sm:text-base text-gray-600">
            You will be signed out from your account. Any unsaved changes may be lost.
        </p>

        <!-- Buttons -->
        <div class="mt-8 sm:mt-10 flex flex-col-reverse sm:flex-row justify-center gap-3 sm:gap-5">
            <button id="logout-cancel-btn"
                class="w-full sm:w-auto px-6 py-2 rounded-full border border-[#575757] text-[#575757] hover:bg-gray-100 cursor-pointer focus:outline-none focus:ring-2 focus:ring-[#575757]">
                Cancel
            </button>
            <form id="logout-form" method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                @csrf
                <button type="submit" id="logout-confirm-btn"
                    class="w-full sm:w-auto px-6 py-2 rounded-full text-white bg-gradient-to-r from-[#FF5656] to-[#DF0606] hover:brightness-110 cursor-pointer focus:outline-none focus:ring-2 focus:ring-red-300">
                    Log Out
                </button>
            </form>
        </div>
    </div>
</div>

<!-- JavaScript to close the popup -->
<script>
    document.addEventListener("DOMContentLoaded", () => {

        document.getElementById('logout-cancel-btn').addEventListener('click', function() {
            document.getElementById('logout-popup').style.display = 'none';
        });

        document.getElementById('logout-confirm-btn').addEventListener('click', function() {
            document.getElementById('logout-popup').style.display = 'none';
        });
    });
</script>
