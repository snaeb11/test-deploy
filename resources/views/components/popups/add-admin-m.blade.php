<x-popups.admin-add-succ-m />
<x-popups.admin-add-fail-m />

<div id="add-admin-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-2 sm:p-4">
    <div id="aaa-step1"
        class="relative max-h-[95vh] w-full overflow-y-auto rounded-2xl bg-[#fdfdfd] p-6 shadow-xl [scrollbar-width:none] sm:max-w-3xl sm:p-8 md:max-w-4xl lg:max-w-6xl">

        <!-- Close Button -->
        <button id="aaa-close-popup" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="mt-3 text-xl font-bold text-gray-900 sm:text-2xl">Add Admin Account</h2>
        </div>

        <form id="add-admin-form" class="mt-4 space-y-6" method="POST" action="{{ route('admin.store') }}">
            @csrf

            <div class="flex flex-col gap-8 md:flex-row">
                <!-- Left Side (Personal Information) -->
                <div class="flex-1 space-y-2">
                    <label class="block text-sm font-bold text-gray-700">PERSONAL INFORMATION:</label>

                    <!-- First Name -->
                    <label for="aaa-first-name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input id="aaa-first-name" name="first_name" type="text" placeholder="First Name"
                        value="{{ old('first-name') }}"
                        class="mt-1 block w-full rounded-lg border border-[#575757] px-3 py-2 text-sm font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none sm:px-4 sm:py-3 sm:text-base"
                        required />
                    <div id="aaa-first-name-error" class="hidden text-sm text-red-500"></div>

                    <!-- Last Name -->
                    <label for="aaa-last-name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input id="aaa-last-name" name="last_name" type="text" placeholder="Last Name"
                        value="{{ old('last-name') }}"
                        class="mt-1 block w-full rounded-lg border border-[#575757] px-3 py-2 text-sm font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none sm:px-4 sm:py-3 sm:text-base"
                        required />
                    <div id="aaa-last-name-error" class="hidden text-sm text-red-500"></div>

                    <!-- Email -->
                    <label for="aaa-usep-email" class="block text-sm font-medium text-gray-700">Email Address</label>
                    <input id="aaa-usep-email" type="email" placeholder="USeP Email" name="email"
                        value="{{ old('email') }}"
                        class="mt-1 block w-full rounded-lg border border-[#575757] px-3 py-2 text-sm font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none sm:px-4 sm:py-3 sm:text-base"
                        required />
                    <div id="aaa-email-error" class="hidden text-sm text-red-500"></div>

                    <!-- Temporary Password -->
                    <label for="aaa-temp-password" class="block text-sm font-medium text-gray-700">Temporary
                        Password</label>
                    <input id="aaa-temp-password" type="text" name="password" value="!2Qwerty" readonly
                        class="mt-1 block w-full rounded-lg border border-[#575757] bg-gray-100 px-3 py-2 text-sm font-light text-[#575757] placeholder-gray-400 transition-colors duration-200 focus:outline-none sm:px-4 sm:py-3 sm:text-base" />
                    <p class="mt-1 text-xs text-red-500"><span class="font-semibold">Note:</span> User will be required
                        to change this password on first login</p>
                </div>

                <!-- Divider (hidden on small screens) -->
                <div class="hidden h-auto w-px bg-[#dddddd] md:block"></div>

                <!-- Right Side (Permissions) -->
                <div class="flex-1 space-y-3">
                    <div class="flex items-center justify-start gap-2">
                        <label class="block text-sm font-bold text-gray-700">PERMISSIONS:</label>
                        <button type="button" id="toggle-all-permissions" class="text-xs text-red-600 hover:underline">
                            <span id="toggle-all-text">[Check All]</span>
                        </button>
                    </div>
                    <p id="permissions-error" class="mt-2 hidden text-sm text-red-600">Please select at least one
                        permission</p>

                    <!-- Admin Management -->
                    <div>
                        <h5 class="text-sm font-semibold text-gray-700">Admin Management</h5>
                        <div class="mt-1 grid grid-cols-1 gap-2">
                            <label class="flex items-center">
                                <input id="view-dashboard-cb" value="view-dashboard" type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Dashboard</span>
                            </label>
                        </div>
                    </div>

                    <!-- Submissions Management -->
                    <div class="mt-3">
                        <h5 class="text-sm font-semibold text-gray-700">Submissions Management</h5>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <label class="flex items-center">
                                <input id="view-thesis-submissions-cb" data-group="submissions"
                                    value="view-thesis-submissions" type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Thesis Submissions</span>
                            </label>
                            <label class="flex items-center">
                                <input id="view-forms-submissions-cb" data-group="submissions"
                                    value="view-forms-submissions" type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Forms Submissions</span>
                            </label>
                            <label class="flex items-center">
                                <input id="acc-rej-thesis-submissions-cb" data-group="submissions"
                                    value="acc-rej-thesis-submissions" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Accept/Reject Thesis Submissions</span>
                            </label>
                            <label class="flex items-center">
                                <input id="acc-rej-forms-submissions-cb" data-group="submissions"
                                    value="acc-rej-forms-submissions" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Accept/Reject Forms Submissions</span>
                            </label>
                        </div>
                    </div>

                    <!-- Inventory Management -->
                    <div class="mt-3">
                        <h5 class="text-sm font-semibold text-gray-700">Inventory Management</h5>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <label class="flex items-center">
                                <input id="view-inventory-cb" data-group="inventory-management"
                                    value="view-inventory" type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Inventory</span>
                            </label>
                            <label class="flex items-center">
                                <input id="add-inventory-cb" data-group="inventory-management" value="add-inventory"
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Add Inventory</span>
                            </label>
                            <label class="flex items-center">
                                <input id="edit-inventory-cb" data-group="inventory-management"
                                    value="edit-inventory" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Edit Inventory</span>
                            </label>
                            <label class="flex items-center">
                                <input id="import-inventory-cb" data-group="inventory-management"
                                    value="import-inventory" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Import Inventory</span>
                            </label>
                            <label class="flex items-center">
                                <input id="export-inventory-cb" data-group="inventory-management"
                                    value="export-inventory" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Export Inventory</span>
                            </label>
                        </div>
                    </div>

                    <!-- User Management -->
                    <div class="mt-3">
                        <h5 class="text-sm font-semibold text-gray-700">User Management</h5>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <label class="flex items-center">
                                <input id="view-accounts-cb" data-group="user-management" value="view-accounts"
                                    type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Accounts</span>
                            </label>
                            <label class="flex items-center">
                                <input id="edit-permissions-cb" data-group="user-management" value="edit-permissions"
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Edit Permissions</span>
                            </label>
                            <label class="flex items-center">
                                <input id="add-admin-cb" data-group="user-management" value="add-admin"
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Add Admin</span>
                            </label>
                        </div>
                    </div>

                    <!-- Data List Management -->
                    <div class="mt-3">
                        <h5 class="text-sm font-semibold text-gray-700">Data List Management</h5>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <label class="flex items-center">
                                <input id="modify-programs-list-cb" value="modify-programs-list" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                <span class="ml-2 text-sm text-gray-700">Modify Programs List</span>
                            </label>
                            <label class="flex items-center">
                                <input id="modify-advisers-list-cb" value="modify-advisers-list" type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                <span class="ml-2 text-sm text-gray-700">Modify Advisers List</span>
                            </label>
                            <label class="flex items-center">
                                <input id="modify-downloadable-forms-cb" value="modify-downloadable-forms"
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500" />
                                <span class="ml-2 text-sm text-gray-700">Modify Downloadable Forms</span>
                            </label>
                        </div>
                    </div>

                    <!-- Logs Management -->
                    <div class="mt-3">
                        <h5 class="text-sm font-semibold text-gray-700">Logs Management</h5>
                        <div class="grid grid-cols-1 gap-2">
                            <label class="flex items-center">
                                <input id="view-logs-cb" value="view-logs" type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Logs</span>
                            </label>
                        </div>
                    </div>

                    <!-- Backup Management -->
                    <div class="mt-3">
                        <h5 class="text-sm font-semibold text-gray-700">Backup Management</h5>
                        <div class="grid grid-cols-1 gap-2 sm:grid-cols-2">
                            <label class="flex items-center">
                                <input id="view-backup-cb" data-group="backup-management" value="view-backup"
                                    type="checkbox"
                                    class="permission-checkbox view-checkbox h-4 w-4 rounded border-gray-300 text-green-600 focus:ring-green-500" />
                                <span class="ml-2 text-sm text-gray-700">View Backup</span>
                            </label>
                            <label class="flex items-center">
                                <input id="download-backup-cb" data-group="backup-management" value="download-backup"
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Download Backup</span>
                            </label>
                            <label class="flex items-center">
                                <input id="allow-restore-cb" data-group="backup-management" value="allow-restore"
                                    type="checkbox"
                                    class="permission-checkbox h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 disabled:opacity-50"
                                    disabled />
                                <span class="ml-2 text-sm text-gray-700">Allow Restore</span>
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <input type="hidden" name="permissions" id="permissions">

            <!-- Action Buttons -->
            <div class="mt-6 flex flex-col justify-center gap-4 sm:flex-row sm:gap-6">
                <button id="aaa-cancel-btn" type="button"
                    class="w-full min-w-[120px] rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-6 py-2 text-white hover:brightness-110 sm:w-auto sm:py-3">
                    Cancel
                </button>
                <button id="aaa-add-admin-btn" type="submit"
                    class="w-full min-w-[120px] rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-6 py-2 text-white hover:brightness-110 sm:w-auto sm:py-3">
                    Add
                </button>
            </div>
        </form>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const addAdminPopup = document.getElementById('add-admin-popup');
        const q = (sel) => addAdminPopup.querySelector(sel);
        const qa = (sel) => addAdminPopup.querySelectorAll(sel);

        const addAdminForm = q('#add-admin-form');
        const closeBtn = q('#aaa-close-popup');
        const cancelBtn = q('#aaa-cancel-btn');
        const submitBtn = q('#aaa-add-admin-btn');
        const successModal = document.getElementById('admin-add-succ-m');
        const errorModal = document.getElementById('admin-add-fail-m');
        const errorMessage = document.getElementById('admin-add-fail-message');

        // Error elements
        const firstNameError = q('#aaa-first-name-error');
        const lastNameError = q('#aaa-last-name-error');
        const emailError = q('#aaa-email-error');
        const permissionsError = q('#permissions-error');

        // Input fields
        const firstNameInput = q('#aaa-first-name');
        const lastNameInput = q('#aaa-last-name');
        const emailInput = q('#aaa-usep-email');

        // Permission checkboxes
        const permissionCheckboxes = qa('.permission-checkbox');
        const hiddenPermissionsInput = q('#permissions');
        const toggleAllBtn = q('#toggle-all-permissions');
        const toggleAllText = q('#toggle-all-text');

        // Regular expressions
        const nameRegex = /^[A-Za-z\s'\-]+$/;
        const emailRegex = /^[^\s@]+@usep\.edu\.ph$/;

        // Update the label of the toggle-all button based on current state
        function updateToggleAllLabel() {
            const checkboxes = Array.from(permissionCheckboxes);
            const allChecked = checkboxes.length > 0 && checkboxes.every(cb => cb.checked);
            toggleAllText.textContent = allChecked ? '[Uncheck All]' : '[Check All]';
        }

        // Add input sanitization for first name
        firstNameInput.addEventListener('input', (e) => {
            let value = e.target.value;

            // Remove risky characters - allow only letters, spaces, apostrophes, and hyphens
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .replace(/[^A-Za-z\s'\-]/g,
                    ''); // Remove any character that's not letter, space, apostrophe, or hyphen

            // Update the input value if it was modified
            if (e.target.value !== value) {
                e.target.value = value;
            }

            validateNameField(firstNameInput, firstNameError);
        });
        firstNameInput.addEventListener('blur', () => {
            validateNameField(firstNameInput, firstNameError);
        });

        // Add input sanitization for last name
        lastNameInput.addEventListener('input', (e) => {
            let value = e.target.value;

            // Remove risky characters - allow only letters, spaces, apostrophes, and hyphens
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .replace(/[^A-Za-z\s'\-]/g,
                    ''); // Remove any character that's not letter, space, apostrophe, or hyphen

            // Update the input value if it was modified
            if (e.target.value !== value) {
                e.target.value = value;
            }

            validateNameField(lastNameInput, lastNameError);
        });
        lastNameInput.addEventListener('blur', () => {
            validateNameField(lastNameInput, lastNameError);
        });

        // Add input sanitization for email
        emailInput.addEventListener('input', (e) => {
            let value = e.target.value;

            // Remove risky characters - allow letters, numbers, @, ., and hyphens
            value = value
                .replace(/[<>]/g, '') // Remove HTML tags
                .replace(/javascript:/gi, '') // Remove javascript: protocol
                .replace(/on\w+=/gi, '') // Remove event handlers
                .replace(/[^A-Za-z0-9@.\-_]/g,
                    ''); // Remove any character that's not letter, number, @, ., -, or _

            // Update the input value if it was modified
            if (e.target.value !== value) {
                e.target.value = value;
            }

            validateEmailField(emailInput, emailError);
        });
        emailInput.addEventListener('blur', () => {
            validateEmailField(emailInput, emailError);
        });

        // Add paste event protection for first name
        firstNameInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .replace(/[^A-Za-z\s'\-]/g, '');

            const currentValue = firstNameInput.value;
            const newValue = currentValue.substring(0, firstNameInput.selectionStart) +
                cleanPaste +
                currentValue.substring(firstNameInput.selectionEnd);

            firstNameInput.value = newValue;
            firstNameInput.dispatchEvent(new Event('input'));
        });

        // Add paste event protection for last name
        lastNameInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .replace(/[^A-Za-z\s'\-]/g, '');

            const currentValue = lastNameInput.value;
            const newValue = currentValue.substring(0, lastNameInput.selectionStart) +
                cleanPaste +
                currentValue.substring(lastNameInput.selectionEnd);

            lastNameInput.value = newValue;
            lastNameInput.dispatchEvent(new Event('input'));
        });

        // Add paste event protection for email
        emailInput.addEventListener('paste', (e) => {
            e.preventDefault();
            const paste = (e.clipboardData || window.clipboardData).getData('text');
            const cleanPaste = paste
                .replace(/[<>]/g, '')
                .replace(/javascript:/gi, '')
                .replace(/on\w+=/gi, '')
                .replace(/[^A-Za-z0-9@.\-_]/g, '');

            const currentValue = emailInput.value;
            const newValue = currentValue.substring(0, emailInput.selectionStart) +
                cleanPaste +
                currentValue.substring(emailInput.selectionEnd);

            emailInput.value = newValue;
            emailInput.dispatchEvent(new Event('input'));
        });

        // Name validation
        function validateNameField(field, errorElement) {
            const value = field.value.trim();
            if (value === "") {
                errorElement.classList.add('hidden');
                return false;
            }
            if (!nameRegex.test(value)) {
                errorElement.textContent = 'Only letters, spaces, apostrophes, and hyphens are allowed';
                errorElement.classList.remove('hidden');
                return false;
            }
            errorElement.classList.add('hidden');
            return true;
        }

        // Email validation
        function validateEmailField(field, errorElement) {
            const value = field.value.trim();
            if (value === "") {
                errorElement.classList.add('hidden');
                return false;
            }
            if (!emailRegex.test(value)) {
                errorElement.textContent = 'Please enter a valid USeP email (@usep.edu.ph)';
                errorElement.classList.remove('hidden');
                return false;
            }
            errorElement.classList.add('hidden');
            return true;
        }


        // New function to highlight permissions error
        function highlightPermissionsError() {
            // Add asterisk to error message
            permissionsError.textContent = 'Please select at least one permission*';
            permissionsError.classList.remove('hidden');

            // Add bold styling
            permissionsError.classList.add('font-bold');

            // Remove highlight after 3 seconds
            setTimeout(() => {
                permissionsError.classList.remove('font-bold');
                // Remove asterisk but keep message visible
                permissionsError.textContent = 'Please select at least one permission';
            }, 3000);
        }


        // Update your existing validatePermissionsField function
        function validatePermissionsField() {
            const selectedPermissions = Array.from(permissionCheckboxes)
                .filter(cb => cb.checked);

            if (selectedPermissions.length === 0) {
                permissionsError.textContent = 'Please select at least one permission';
                return false;
            }

            permissionsError.classList.add('hidden');
            return true;
        }

        function updatePermissions() {
            const selectedPermissions = Array.from(permissionCheckboxes)
                .filter(cb => cb.checked)
                .map(cb => cb.value);
            hiddenPermissionsInput.value = JSON.stringify(selectedPermissions);

            // Do not auto-update toggle text here to avoid overriding click handler
            updateToggleAllLabel();
        }

        // Initialize permissions
        updatePermissions();
        enforceGroupViewRules();
        updateToggleAllLabel();

        function enforceGroupViewRules() {
            const viewDashboardCheckbox = q('#view-dashboard-cb');

            // Apply group-specific rules where any "view-*" within the group enables actions
            const applyGroupRules = () => {
                // Find all groups
                const groups = Array.from(new Set(Array.from(permissionCheckboxes)
                    .map(cb => cb.dataset.group)
                    .filter(g => !!g)));

                groups.forEach(group => {
                    const groupCheckboxes = Array.from(permissionCheckboxes).filter(cb => cb.dataset
                        .group === group);
                    const groupViewCheckboxes = groupCheckboxes.filter(cb => cb.id.includes(
                        'view-'));
                    const groupActionCheckboxes = groupCheckboxes.filter(cb => !cb.id.includes(
                        'view-'));

                    // Special-case: in submissions group, tie each action to its corresponding view
                    if (group === 'submissions') {
                        const viewThesis = q('#view-thesis-submissions-cb');
                        const viewForms = q('#view-forms-submissions-cb');
                        const actThesis = q('#acc-rej-thesis-submissions-cb');
                        const actForms = q('#acc-rej-forms-submissions-cb');

                        const updateThesis = () => {
                            const allowed = !!(viewThesis && viewThesis.checked &&
                                viewDashboardCheckbox.checked);
                            if (actThesis) {
                                actThesis.disabled = !allowed;
                                if (!allowed) actThesis.checked = false;
                                if (allowed) {
                                    actThesis.classList.remove('disabled:opacity-50',
                                        'disabled:cursor-not-allowed');
                                    actThesis.classList.add('text-blue-600',
                                        'focus:ring-blue-500');
                                } else {
                                    actThesis.classList.add('disabled:opacity-50',
                                        'disabled:cursor-not-allowed');
                                }
                            }
                            updatePermissions();
                        };

                        const updateForms = () => {
                            const allowed = !!(viewForms && viewForms.checked &&
                                viewDashboardCheckbox.checked);
                            if (actForms) {
                                actForms.disabled = !allowed;
                                if (!allowed) actForms.checked = false;
                                if (allowed) {
                                    actForms.classList.remove('disabled:opacity-50',
                                        'disabled:cursor-not-allowed');
                                    actForms.classList.add('text-blue-600',
                                        'focus:ring-blue-500');
                                } else {
                                    actForms.classList.add('disabled:opacity-50',
                                        'disabled:cursor-not-allowed');
                                }
                            }
                            updatePermissions();
                        };

                        // Initialize and bind
                        updateThesis();
                        updateForms();
                        if (viewThesis) viewThesis.addEventListener('change', updateThesis);
                        if (viewForms) viewForms.addEventListener('change', updateForms);
                        viewDashboardCheckbox.addEventListener('change', () => {
                            updateThesis();
                            updateForms();
                        });
                        return; // skip default behavior for this group
                    }

                    // Default behavior for other groups: any view enables all actions
                    const updateGroup = () => {
                        const isGroupViewChecked = groupViewCheckboxes.some(cb => cb.checked);
                        const isAllowed = isGroupViewChecked && viewDashboardCheckbox.checked;

                        groupActionCheckboxes.forEach(cb => {
                            cb.disabled = !isAllowed;
                            if (!isAllowed) cb.checked = false;

                            if (isAllowed) {
                                cb.classList.remove('disabled:opacity-50',
                                    'disabled:cursor-not-allowed');
                                cb.classList.add('text-blue-600',
                                    'focus:ring-blue-500');
                            } else {
                                cb.classList.add('disabled:opacity-50',
                                    'disabled:cursor-not-allowed');
                            }
                        });
                        updatePermissions();
                    };

                    // Initialize and bind listeners
                    updateGroup();
                    groupViewCheckboxes.forEach(cb => cb.addEventListener('change', updateGroup));
                    viewDashboardCheckbox.addEventListener('change', updateGroup);
                });
            };

            // Global View Dashboard rule
            const applyGlobalDashboardRule = () => {
                const isDashboardChecked = viewDashboardCheckbox.checked;

                if (!isDashboardChecked) {
                    permissionCheckboxes.forEach(cb => {
                        if (cb !== viewDashboardCheckbox) {
                            cb.checked = false;
                            cb.disabled = true;
                            cb.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                        }
                    });
                } else {
                    permissionCheckboxes.forEach(cb => {
                        if (cb !== viewDashboardCheckbox) {
                            cb.disabled = false; // will be refined by group rules
                        }
                    });
                    applyGroupRules();
                }

                updatePermissions();
            };

            // Initialize
            applyGlobalDashboardRule();
            viewDashboardCheckbox.addEventListener('change', applyGlobalDashboardRule);
            applyGroupRules();
        }


        // Event listeners for real-time validation (already handled above with sanitization)

        // Add event listeners to all permission checkboxes
        permissionCheckboxes.forEach(cb => {
            cb.addEventListener('change', () => {
                updatePermissions();
                validatePermissionsField();
                updateToggleAllLabel();
            });
        });


        // Toggle all permissions
        toggleAllBtn.addEventListener('click', () => {
            const allCurrentlyChecked = Array.from(permissionCheckboxes).every(cb => cb.checked);
            const newState = !allCurrentlyChecked;

            // First, remove disabled state from everything before changing
            permissionCheckboxes.forEach(cb => {
                cb.disabled = false;
                cb.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            });

            // Set the new check state
            permissionCheckboxes.forEach(cb => {
                cb.checked = newState;

                // Respect view-dashboard rule after initial check
                if (cb.id.includes('view-') && cb.dataset.group) {
                    const event = new Event('change');
                    cb.dispatchEvent(event);
                }
            });

            // After setting all, reapply rules so only valid ones stay enabled
            enforceGroupViewRules();
            updatePermissions();

            // Set label based on intended state (user action), not post-rule outcome
            toggleAllText.textContent = newState ? '[Uncheck All]' : '[Check All]';
        });

        // Form submission
        // In your form submission handler:
        addAdminForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            clearAllErrors();

            // Frontend validation
            const isNameValid = validateNameField(firstNameInput, firstNameError);
            const isLastNameValid = validateNameField(lastNameInput, lastNameError);
            const isEmailValid = validateEmailField(emailInput, emailError);
            const arePermissionsValid = validatePermissionsField();

            if (!arePermissionsValid) {
                highlightPermissionsError();
                return;
            }

            if (!isNameValid || !isLastNameValid || !isEmailValid) {
                return;
            }

            const submitBtn = document.getElementById('aaa-add-admin-btn');
            const originalBtnText = submitBtn.innerHTML;

            // Show loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = `
                                        <span class="inline-block h-4 w-4 animate-spin rounded-full border-2 border-solid border-white border-r-transparent"></span>
                                        Adding...
                                    `;

            try {
                const formData = new FormData(this);

                // Convert permissions to comma-separated string
                const selectedPermissions = Array.from(permissionCheckboxes)
                    .filter(cb => cb.checked)
                    .map(cb => cb.value);
                formData.set('permissions', selectedPermissions.join(', '));

                const response = await fetch(this.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content,
                    },
                    body: formData,
                });

                const data = await response.json();

                if (!response.ok) {
                    // Handle specific error cases
                    if (data.errors?.email) {
                        // Close add-admin popup first
                        addAdminPopup.style.display = 'none';

                        // Email validation error (including uniqueness)
                        emailError.textContent = data.errors.email[0];
                        emailError.classList.remove('hidden');

                        // Show modal with specific message
                        errorModal.style.display = 'flex';
                        document.getElementById('admin-add-fail-message').textContent =
                            'The email address is already registered. Please use a different USeP email.';
                        return;
                    }

                    // Handle other validation errors
                    if (data.errors) {
                        throw {
                            message: 'Validation failed',
                            errors: data.errors
                        };
                    }

                    // Handle generic API errors
                    throw {
                        message: data.message || 'Failed to add admin',
                        errors: null
                    };
                }

                // On success
                addAdminPopup.style.display = 'none';
                successModal.style.display = 'flex';

                // Set the email in the success modal
                document.getElementById('added-admin-email').textContent = emailInput.value.trim();

                // Reload after 1.5 seconds
                setTimeout(() => window.location.reload(), 1500);

            } catch (error) {
                console.error('Submission error:', error);

                // Handle validation errors
                if (error.errors) {
                    if (error.errors.first_name) {
                        firstNameError.textContent = error.errors.first_name[0];
                        firstNameError.classList.remove('hidden');
                    }
                    if (error.errors.last_name) {
                        lastNameError.textContent = error.errors.last_name[0];
                        lastNameError.classList.remove('hidden');
                    }
                    if (error.errors.email) {
                        // Show email error in both inline and modal
                        emailError.textContent = error.errors.email[0];
                        emailError.classList.remove('hidden');

                        // Also show in modal
                        errorModal.style.display = 'flex';
                        document.getElementById('admin-add-fail-message').textContent = error.errors
                            .email[0];
                    }
                    if (error.errors.permissions) {
                        permissionsError.textContent = error.errors.permissions[0];
                        permissionsError.classList.remove('hidden');
                    }
                } else {
                    // Show generic error in modal
                    errorModal.style.display = 'flex';
                    document.getElementById('admin-add-fail-message').textContent =
                        error.message || 'An unexpected error occurred while adding the admin.';
                }
            } finally {
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            }
        });

        function clearAllErrors() {
            firstNameError.classList.add('hidden');
            lastNameError.classList.add('hidden');
            emailError.classList.add('hidden');
            permissionsError.classList.add('hidden');
        }

        // Close popup handlers
        function closePopup() {
            addAdminPopup.style.display = 'none';
        }

        closeBtn.addEventListener('click', closePopup);
        cancelBtn.addEventListener('click', closePopup);
    });
</script>
