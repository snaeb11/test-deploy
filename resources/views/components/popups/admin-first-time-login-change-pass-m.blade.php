<div id="admin-ftl-changepass" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
    <div class="border-1 relative rounded-2xl border-[#a1a1a1] bg-[#fdfdfd] p-10">
        <button id="admin-ftl-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <div>
            <span class="text-center text-2xl font-semibold text-[#575757]">Change password</span>
        </div>
        <div class="h-3"></div>
        <form id="ftl-password-change-form" method="POST">

            <div class="flex flex-col gap-6 px-6 md:flex-row">
                <!-- Left: Inputs -->
                <div id="input-fields" class="flex w-full flex-col space-y-4 md:w-1/2">
                    <input id="ftl-current-password" name="current_password" type="password"
                        placeholder="Current password" required
                        class="h-[50px] rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none" />
                    <div id="current-password-error" class="hidden text-sm text-red-500"></div>

                    <input id="ftl-new-password" name="new_password" type="password" placeholder="New password" required
                        class="h-[50px] rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none" />
                    <div id="new-password-error" class="hidden text-sm text-red-500"></div>

                    <input id="ftl-confirm-password" name="new_password_confirmation" type="password"
                        placeholder="Confirm password" required
                        class=":border-[#D56C6C] h-[50px] rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none" />
                    <div id="confirm-password-error" class="hidden text-sm text-red-500"></div>

                    <label class="flex items-center justify-end space-x-2 text-sm font-light text-[#575757]">
                        <input type="checkbox" id="ftl-show-password-toggle"
                            class="h-4 w-4 accent-[#575757] hover:cursor-pointer" />
                        <span class="hover:cursor-pointer">Show password</span>
                    </label>
                </div>

                <!-- Right: Requirements -->
                <div id="requirements" class="flex w-full flex-col space-y-4 rounded-lg pl-7 md:w-1/2">
                    <span class="text-sm font-semibold text-[#575757]">New password must contain the following:</span>
                    <div id="ftl-password-requirements" class="ml-4 space-y-2 text-sm font-light text-[#575757]">
                        <div class="flex items-center space-x-2">
                            <div id="ftl-circle-length" class="h-3 w-3 rounded-full bg-gray-300"></div>
                            <span>Minimum of 8 characters</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div id="ftl-circle-uppercase" class="h-3 w-3 rounded-full bg-gray-300"></div>
                            <span>An uppercase letter</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div id="ftl-circle-lowercase" class="h-3 w-3 rounded-full bg-gray-300"></div>
                            <span>A lowercase letter</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div id="ftl-circle-number" class="h-3 w-3 rounded-full bg-gray-300"></div>
                            <span>A number</span>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div id="ftl-circle-special" class="h-3 w-3 rounded-full bg-gray-300"></div>
                            <span>A special character</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="mt-5 flex justify-center">
                <button id="user-submit-btn" type="submit"
                    class="w-full max-w-xs rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-6 py-3 font-semibold text-[#fdfdfd] transition duration-200 hover:cursor-pointer hover:brightness-110">
                    Change password
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('admin-ftl-changepass');
        const closeBtn = document.getElementById('admin-ftl-close-popup');
        const showPasswordToggle = document.getElementById('ftl-show-password-toggle');

        const currentPassword = document.getElementById('ftl-current-password');
        const newPassword = document.getElementById('ftl-new-password');
        const confirmPassword = document.getElementById('ftl-confirm-password');

        const form = document.getElementById('ftl-password-change-form');
        const confirmPasswordError = document.getElementById('confirm-password-error');

        const circleLength = document.getElementById('ftl-circle-length');
        const circleUpper = document.getElementById('ftl-circle-uppercase');
        const circleLower = document.getElementById('ftl-circle-lowercase');
        const circleNumber = document.getElementById('ftl-circle-number');
        const circleSpecial = document.getElementById('ftl-circle-special');

        window.addEventListener('show-admin-ftl-password-popup', () => {
            popup.style.display = 'flex';
        });

        closeBtn.addEventListener('click', function() {
            popup.style.display = 'none';
            form.reset();
            resetCircles();
            confirmPasswordError.classList.add('hidden');
        });

        showPasswordToggle.addEventListener('change', function() {
            const type = this.checked ? 'text' : 'password';
            [currentPassword, newPassword, confirmPassword].forEach(input => input.type = type);
        });

        newPassword.addEventListener('input', function() {
            const val = newPassword.value;
            toggleCircle(circleLength, val.length >= 8);
            toggleCircle(circleUpper, /[A-Z]/.test(val));
            toggleCircle(circleLower, /[a-z]/.test(val));
            toggleCircle(circleNumber, /[0-9]/.test(val));
            toggleCircle(circleSpecial, /[^A-Za-z0-9]/.test(val));
        });

        form.addEventListener('submit', function(e) {
            if (newPassword.value !== confirmPassword.value) {
                e.preventDefault();
                confirmPasswordError.textContent = "Passwords do not match.";
                confirmPasswordError.classList.remove('hidden');
            } else {
                confirmPasswordError.classList.add('hidden');
            }
        });

        function toggleCircle(circle, valid) {
            circle.classList.remove(valid ? 'bg-gray-300' : 'bg-green-500');
            circle.classList.add(valid ? 'bg-green-500' : 'bg-gray-300');
        }

        function resetCircles() {
            [circleLength, circleUpper, circleLower, circleNumber, circleSpecial].forEach(c => {
                c.classList.remove('bg-green-500');
                c.classList.add('bg-gray-300');
            });
        }
    });
</script>
