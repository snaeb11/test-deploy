<div id="universal-option-popup" style="display: none;"
    class="fixed inset-0 z-[60] flex items-center justify-center bg-black/50">
    <div id="option-popup"
        class="relative block max-h-[90vh] w-[70vw] rounded-2xl bg-[#fdfdfd] p-6 shadow-xl md:min-w-[20vw] md:max-w-[25vw] md:p-8">
        <div class="mt-0 flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
                class="w-30 h-30">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
            </svg>

        </div>

        <div class="mt-10 text-center text-xl font-semibold">
            <span id="opt-topText" class="text-[#575757]"></span>
        </div>

        <div class="text-normal font-regular mt-5 text-center">
            <span id="opt-subText" class="text-[#575757]"></span>
        </div>

        <div class="ml-3 mr-3 mt-20 flex flex-col justify-center space-y-3 md:flex-row md:space-x-5 md:space-y-0">
            <button id="uniOpt-cancel-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#707070] to-[#a5a5a5] px-10 py-4 text-[#fdfdfd] shadow hover:brightness-110">
                Cancel
            </button>
            <button id="uniOpt-confirm-btn"
                class="cursor-pointer rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-10 py-4 text-[#fdfdfd] shadow hover:brightness-110">
                Confirm
            </button>
        </div>
    </div>
</div>

<script>
    document.getElementById('uniOpt-confirm-btn').addEventListener('click', function() {
        document.getElementById('universal-option-popup').style.display = 'none';
    });
</script>
