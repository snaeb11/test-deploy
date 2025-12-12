<!-- Email Verified Modal -->
<div id="email-verified-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 sm:p-6 md:p-8">
    
    <!-- Modal Card -->
    <div class="relative w-full max-w-sm sm:max-w-md md:max-w-lg lg:max-w-xl 
                max-h-[90vh] rounded-2xl bg-[#fdfdfd] p-5 sm:p-6 md:p-8 shadow-xl overflow-y-auto">
        
        <!-- Close Button -->
        <button class="absolute right-4 top-4 text-[#575757] hover:text-red-500"
            onclick="document.getElementById('email-verified-popup').style.display='none'">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" 
                stroke-width="2" stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div class="flex flex-col items-center justify-center space-y-3 sm:space-y-4 md:space-y-6">
            
            <!-- Check Icon -->
            <div class="flex justify-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" 
                    viewBox="0 0 24 24" stroke-width="1.5" stroke="#27C50D" 
                    class="h-14 w-14 sm:h-16 sm:w-16 md:h-20 md:w-20 lg:h-24 lg:w-24">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>

            <!-- Main Message -->
            <h2 id="welcome-message" 
                class="text-center text-base sm:text-lg md:text-xl lg:text-2xl 
                       font-semibold text-[#575757]">
                Email Verified Successfully!
            </h2>

            <!-- Sub Message -->
            <div class="text-center text-xs sm:text-sm md:text-base text-[#575757]">
                Redirecting to your dashboard...
            </div>
        </div>
    </div>
</div>
