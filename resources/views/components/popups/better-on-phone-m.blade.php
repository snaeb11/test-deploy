<div id="better-on-phone" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 px-2 sm:px-0">
  <div id="bop-popup" class="w-full max-w-[95vw] sm:min-w-[20vw] sm:max-w-[25vw] max-h-[90vh] bg-[#fdfdfd] rounded-2xl shadow-xl relative p-4 sm:p-8 block overflow-y-auto">

    <div class="flex justify-center mt-0">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
           stroke-width="1" stroke="#575757"
           class="w-20 h-20 sm:w-30 sm:h-30">
        <path stroke-linecap="round" stroke-linejoin="round"
              d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
      </svg>
    </div>

    <div class="text-center text-lg sm:text-xl font-semibold mt-10">
      <span id="bop-topText" class="text-[#575757]">This function works better on mobile devices.</span>
    </div>

    <div class="text-center mt-5 text-sm sm:text-base font-regular">
      <span id="bop-subText" class="text-[#575757]">
        It is highly suggested to utilize a mobile device camera for the scanning functionality of the system.
      </span>
    </div>

    <div class="mt-16 sm:mt-20 flex justify-center">
      <button id="bop-confirm-btn" class="px-10 py-4 rounded-full text-[#fdfdfd] bg-gradient-to-r from-[#27C50D] to-[#1CA506] shadow hover:brightness-110 cursor-pointer">
        I understand
      </button>
    </div>

  </div>
</div>


<script>
    document.getElementById('bop-confirm-btn').addEventListener('click', function() {
        document.getElementById('better-on-phone').style.display = 'none';
    });
</script>