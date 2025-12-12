<!-- Wrapper for the modal -->
<div id="backup-successful-popup" style="display: none;" class="fixed inset-0 flex items-center justify-center bg-black/50 z-50">

  <div class="min-w-[21vw] max-w-[25vw] max-h-[90vh] bg-[#fdfdfd] rounded-2xl shadow-xl relative p-8">

    <!-- ❌ X Button -->
    <button id="bs-close-popup" class="absolute top-4 right-4 text-[#575757] hover:text-red-500">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
           viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" 
           class="w-6 h-6">
        <path stroke-linecap="round" stroke-linejoin="round" 
              d="M6 18L18 6M6 6l12 12" />
      </svg>
    </button>

    <!-- Check Icon -->
    <div class="flex justify-center">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1" stroke="#575757" class="w-40 h-40">
            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
        </svg>
    </div>

    <!-- Text Message -->
    <div class="text-center mt-5 text-xl font-medium">
      <span class="text-[#575757]">Successfully performed a backup!</span>
    </div>

    <!-- subtext -->
    <div class="text-center mt-7 text-base font-light">
      <span class="text-[#575757]">Saved as: </span> <br>
      <span id="file-name" class="text-[#575757] text-decoration: underline">ResearchDataBackup_07-04-25.filetype</span>
    </div>

    <!-- Buttons -->
    <div class="flex justify-center space-x-6 mt-13">
      <button id="bs-confirm-btn"class="min-w-[10vw] min-h-[3vw] rounded-full text-[#fdfdfd] bg-gradient-to-r from-[#28CA0E] to-[#1BA104] hover:from-[#3ceb22] hover:to-[#2db415]">
        Confirm   
      </button>
    </div>

  </div>
</div>

<!-- JavaScript to close the popup -->
<script>
  document.getElementById('bs-close-popup').addEventListener('click', function () {
    document.getElementById('backup-successful-popup').style.display = 'none';
  });

  document.getElementById('bs-confirm-btn').addEventListener('click', function () {
    document.getElementById('backup-successful-popup').style.display = 'none';
  });
</script>
