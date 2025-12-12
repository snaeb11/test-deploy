<div id="universal-ok-popup" style="display: none;"
    class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50 p-4">
    <div id="ok-popup"
        class="relative block max-h-[90vh] w-full max-w-md rounded-2xl bg-[#fdfdfd] p-6 shadow-xl sm:p-8 md:min-w-[20vw] md:max-w-[25vw]">

        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="#575757"
                class="h-16 w-16 sm:h-20 sm:w-20">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>
        </div>

        <!-- Title -->
        <div class="mt-8 text-center text-lg font-semibold sm:mt-10 sm:text-xl">
            <span id="OKtopText" class="text-[#575757]">
                Successfully Changed Account Details!
            </span>
        </div>

        <!-- Message -->
        <div class="mt-4 text-center text-sm font-light sm:mt-5 sm:text-base">
            <span id="OKsubText" class="text-[#575757]">
                Successfully altered this account’s information.
            </span>
        </div>

        <!-- Button -->
        <div class="mt-8 flex justify-center sm:mt-12">
            <button id="uniOK-confirm-btn"
                class="w-full cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-8 py-3 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto sm:px-10 sm:py-4">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('uniOK-confirm-btn').addEventListener('click', function() {
        document.getElementById('universal-ok-popup').style.display = 'none';
    });
</script>
