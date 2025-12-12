<div id="data-priv-popup" style="display: flex;"
    class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
    <div id="dpp-popup"
        class="relative w-full sm:min-w-[15vw] sm:max-w-[25vw] max-h-[90vh] bg-[#fdfdfd] rounded-xl shadow-xl p-6 sm:p-8 flex flex-col items-center">

        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
                class="w-15 h-15 sm:w-25 sm:h-25">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M7.864 4.243A7.5 7.5 0 0 1 19.5 10.5c0 2.92-.556 5.709-1.568 8.268M5.742 6.364A7.465 7.465 0 0 0 4.5 10.5a7.464 7.464 0 0 1-1.15 3.993m1.989 3.559A11.209 11.209 0 0 0 8.25 10.5a3.75 3.75 0 1 1 7.5 0c0 .527-.021 1.049-.064 1.565M12 10.5a14.94 14.94 0 0 1-3.6 9.75m6.633-4.596a18.666 18.666 0 0 1-2.485 5.33" />
            </svg>
        </div>

        <!-- Spacer -->
        <div class="flex-1 m-5"></div>
        <div class="text-center">
            <span class="text-[#575757] font-light">By continuing to use the <span class="font-black">Research Inventory
                    System</span>, you agree to the <a target="_blank" href="https://www.usep.edu.ph/usep-data-privacy-statement/"
                    class="text-[#972020] font-bold hover:underline">University of Southeastern Philippines’ Data Privacy
                    Statement.</a></span>
        </div>

        <!-- Spacer -->
        <div class="flex-1 m-3"></div>

        <!-- Button -->
        <div class="flex justify-center mt-6">
            <button id="dpp-confirm-btn"
                class="px-6 sm:px-5 py-3 sm:py-2 rounded-full text-[#fdfdfd] bg-gradient-to-r from-[#FE5252] to-[#E10C0C] shadow hover:brightness-110 cursor-pointer">
                Confirm
            </button>
        </div>

        <!-- Gradient bar -->
        <div class="absolute bottom-0 left-0 w-full rounded-b-xl h-2 bg-gradient-to-r from-[#FE5252] to-[#E10C0C]">
        </div>
    </div>
</div>



<script>
    document.getElementById('dpp-confirm-btn').addEventListener('click', function() {
        document.getElementById('data-priv-popup').style.display = 'none';
    });
</script>
