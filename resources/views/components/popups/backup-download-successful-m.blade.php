<!-- Wrapper for the modal -->
<div id="backup-download-popup" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50 p-4">
  
  <!-- Modal container -->
  <div class="w-full max-w-md md:max-w-lg lg:max-w-xl xl:max-w-2xl bg-[#fdfdfd] rounded-2xl shadow-xl relative p-6 sm:p-8">

    <!-- Check Icon -->
    <div class="flex justify-center">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757"
        class="w-20 h-20 sm:w-28 sm:h-28 md:w-32 md:h-32 lg:w-40 lg:h-40">
        <path stroke-linecap="round" stroke-linejoin="round"
          d="m9 13.5 3 3m0 0 3-3m-3 3v-6m1.06-4.19-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z" />
      </svg>
    </div>

    <!-- Text Message -->
    <div class="text-center mt-5 text-lg sm:text-xl font-semibold">
      <span class="text-[#575757]">Backup successful!</span>
    </div>
    <div class="text-center mt-2 text-sm sm:text-base font-normal">
      <span class="text-[#575757]">Click confirm to download</span>
    </div>

    <!-- Subtext -->
    <div class="text-center mt-6 text-sm sm:text-base font-light break-words">
      <span class="text-[#575757]">Filename:</span> <br>
      <span id="bds-file-name" class="text-[#575757] underline"></span>
    </div>

    <!-- Buttons -->
    <div class="flex justify-center mt-10">
      <button id="bds-confirm-btn" 
        class="w-full sm:w-auto min-w-[120px] px-6 py-3 rounded-full text-[#fdfdfd] text-sm sm:text-base font-medium 
               bg-gradient-to-r from-[#28CA0E] to-[#1BA104] hover:from-[#3ceb22] hover:to-[#2db415] transition-all">
        Confirm
      </button>
    </div>

  </div>
</div>



<!-- JavaScript to close the popup -->
<script>
  //nyehehe
  document.getElementById('bds-confirm-btn').addEventListener('click', function () {
    document.getElementById('backup-download-popup').style.display = 'none';
  });
</script>
