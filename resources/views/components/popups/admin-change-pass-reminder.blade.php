<div id="admin-change-pass-reminder-popup" style="display: flex;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 transition-opacity duration-200 ease-out sm:p-6"
    role="dialog" aria-modal="true" aria-labelledby="changepass-title" aria-describedby="changepass-description">

    <div
        class="relative w-full max-w-sm scale-95 transform rounded-2xl bg-white p-6 shadow-2xl transition-all duration-200 sm:max-w-md sm:p-8">
        <!-- Icon -->
        <div class="flex justify-center">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="h-16 w-16 text-[#575757] sm:h-20 sm:w-20">
                <path stroke-linecap="round" stroke-linejoin="round"
                    d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
            </svg>

        </div>

        <!-- Title -->
        <h2 id="changepass-title" class="mt-6 text-center text-xl font-bold text-[#575757] sm:mt-8 sm:text-2xl">
            Change Default Password
        </h2>

        <!-- Description -->
        <p id="changepass-description" class="mt-2 text-center text-sm text-gray-600 sm:text-base">
            For security reasons, please change your default password. If not done, this could compromise the secutiry
            of your account.
        </p>

        <!-- Buttons -->
        <div class="mt-8 flex flex-col-reverse justify-center gap-3 sm:mt-10 sm:flex-row sm:gap-5">
            <button type="submit" id="changepass-confirm-btn"
                class="w-full cursor-pointer rounded-full bg-gradient-to-r from-[#FF5656] to-[#DF0606] px-6 py-2 text-white hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-red-300 sm:w-auto">
                I understand, take me to edit account.
            </button>
        </div>
    </div>
</div>

<!-- JavaScript to close the popup -->
<script>
    document.addEventListener("DOMContentLoaded", () => {

        document.getElementById('changepass-confirm-btn').addEventListener('click', function() {
            // Close reminder
            document.getElementById('admin-change-pass-reminder-popup').style.display = 'none';

            // Open Edit Account modal (admin)
            const editPopup = document.getElementById('edit-account-popup');
            if (editPopup) {
                editPopup.style.display = 'flex';

                // Ensure the Change Password section is visible and active
                const passwordFields = document.getElementById('password-fields');
                const togglePasswordBtn = document.getElementById('toggle-password-change');
                if (passwordFields && passwordFields.classList.contains('hidden')) {
                    passwordFields.classList.remove('hidden');
                    if (togglePasswordBtn) {
                        togglePasswordBtn.textContent = 'Cancel Password Change';
                    }
                }

                // Focus the current password field for quick action
                const currentPassword = document.getElementById('current-password');
                if (currentPassword) {
                    currentPassword.focus();
                }
            }
        });
    });
</script>
