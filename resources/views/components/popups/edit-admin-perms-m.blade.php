<div id="edit-admin-perms-popup" style="display: none;"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 px-4 sm:px-0">
    <form class="relative max-h-[90vh] w-full max-w-md overflow-y-auto rounded-2xl bg-[#fdfdfd] p-6 shadow-xl">
        <!-- Close Button -->
        <button id="eap-close-popup" type="button" class="absolute right-4 top-4 text-[#575757] hover:text-red-500">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                stroke="currentColor" class="h-6 w-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>

        <!-- Header -->
        <div class="text-center">
            <h2 class="text-xl font-bold text-gray-900">Edit Permissions</h2>
        </div>

        <div class="mt-4 space-y-4">
            <!-- Admin Management with Check All button inline -->
            <div class="flex items-center justify-between">
                <h5 class="text-sm font-semibold text-gray-700">Admin Management</h5>
                <button type="button" id="eap-toggle-all-permissions" class="text-xs text-red-600 hover:underline">
                    <span id="eap-toggle-all-text">[Check All]</span>
                </button>
            </div>

            <div class="space-y-4">
                <!-- Admin Management -->
                <div class="grid w-full grid-cols-1 gap-2">
                    <div class="flex items-center">
                        <input id="edit-perms-view-dashboard" type="checkbox"
                            class="view-dashboard-checkbox h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                        <label for="edit-perms-view-dashboard" class="ml-2 text-sm text-gray-700">
                            View Dashboard
                        </label>
                    </div>
                </div>

                <!-- Submissions Management -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Submissions Management</h5>
                    <div class="mt-1 grid w-full grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <input id="edit-perms-view-thesis-submissions" data-group="submissions" type="checkbox"
                                class="view-checkbox h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-view-thesis-submissions" class="ml-2 text-sm text-gray-700">
                                View Thesis Submissions
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-view-forms-submissions" data-group="submissions" type="checkbox"
                                class="view-checkbox h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-view-forms-submissions" class="ml-2 text-sm text-gray-700">
                                View Forms Submissions
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-acc-rej-thesis-submissions" data-group="submissions" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-acc-rej-thesis-submissions" class="ml-2 text-sm text-gray-700">
                                Accept/Reject Thesis Submissions
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-acc-rej-forms-submissions" data-group="submissions" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-acc-rej-forms-submissions" class="ml-2 text-sm text-gray-700">
                                Accept/Reject Forms Submissions
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Inventory Management -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Inventory Management</h5>
                    <div class="mt-1 grid w-full grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <input id="edit-perms-view-inventory" data-group="inventory-management" type="checkbox"
                                class="view-checkbox h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-view-inventory" class="ml-2 text-sm text-gray-700">
                                View Inventory
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-add-inventory" data-group="inventory-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-add-inventory" class="ml-2 text-sm text-gray-700">
                                Add Inventory
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-edit-inventory" data-group="inventory-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-edit-inventory" class="ml-2 text-sm text-gray-700">
                                Edit Inventory
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-import-inventory" data-group="inventory-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-import-inventory" class="ml-2 text-sm text-gray-700">
                                Import Inventory
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-export-inventory" data-group="inventory-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-export-inventory" class="ml-2 text-sm text-gray-700">
                                Export Inventory
                            </label>
                        </div>
                    </div>
                </div>

                <!-- User Management -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">User Management</h5>
                    <div class="mt-1 grid w-full grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <input id="edit-perms-view-accounts" data-group="user-management" type="checkbox"
                                class="view-checkbox h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-view-accounts" class="ml-2 text-sm text-gray-700">
                                View Accounts
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-edit-permissions" data-group="user-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-edit-permissions" class="ml-2 text-sm text-gray-700">
                                Edit Permissions
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-add-admin" data-group="user-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-add-admin" class="ml-2 text-sm text-gray-700">
                                Add Admin
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Data List Management -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Data List Management</h5>
                    <div class="mt-1 grid w-full grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <input id="edit-perms-modify-programs-list" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-modify-programs-list" class="ml-2 text-sm text-gray-700">
                                Modify Programs List
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-modify-advisers-list" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-modify-advisers-list" class="ml-2 text-sm text-gray-700">
                                Modify Advisers List
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-modify-downloadable-forms" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-modify-downloadable-forms" class="ml-2 text-sm text-gray-700">
                                Modify Downloadable Forms
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Logs Management -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Logs Management</h5>
                    <div class="mt-1 grid w-full grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <input id="edit-perms-view-logs" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-view-logs" class="ml-2 text-sm text-gray-700">
                                View Logs
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Backup Management -->
                <div>
                    <h5 class="text-sm font-semibold text-gray-700">Backup Management</h5>
                    <div class="mt-1 grid w-full grid-cols-2 gap-2">
                        <div class="flex items-center">
                            <input id="edit-perms-view-backup" data-group="backup-management" type="checkbox"
                                class="view-checkbox h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500" />
                            <label for="edit-perms-view-backup" class="ml-2 text-sm text-gray-700">
                                View Backup
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-download-backup" data-group="backup-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-download-backup" class="ml-2 text-sm text-gray-700">
                                Download Backup
                            </label>
                        </div>
                        <div class="flex items-center">
                            <input id="edit-perms-allow-restore" data-group="backup-management" type="checkbox"
                                class="h-4 w-4 rounded border-gray-300 bg-white text-blue-600 focus:ring-blue-500 disabled:cursor-not-allowed disabled:opacity-50" />
                            <label for="edit-perms-allow-restore" class="ml-2 text-sm text-gray-700">
                                Allow Restore
                            </label>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Confirm Button -->
            <div class="mt-6 flex justify-end space-x-3">
                <button id="eap-cancel-btn" type="button"
                    class="min-w-[100px] rounded-full bg-gradient-to-r from-[#A4A2A2] to-[#575757] px-4 py-2 text-sm text-[#fdfdfd] hover:brightness-110">
                    Cancel
                </button>
                <button id="eap-confirm-btn" type="submit"
                    class="min-w-[100px] rounded-full bg-gradient-to-r from-[#27C50D] to-[#1CA506] px-4 py-2 text-sm text-[#fdfdfd] hover:brightness-110">
                    Confirm
                </button>
            </div>
        </div>
    </form>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const popup = document.getElementById('edit-admin-perms-popup');
        const q = (sel) => popup.querySelector(sel);
        const qa = (sel) => popup.querySelectorAll(sel);

        // Close popup handlers
        q('#eap-close-popup').addEventListener('click', () => {
            popup.style.display = 'none';
        });

        q('#eap-cancel-btn').addEventListener('click', () => {
            popup.style.display = 'none';
        });

        // Toggle all permissions
        const toggleAllBtn = q('#eap-toggle-all-permissions');
        const toggleAllText = q('#eap-toggle-all-text');
        const checkboxes = qa('input[type="checkbox"]');
        const viewDashboardCheckbox = q('#edit-perms-view-dashboard');

        function updateToggleAllLabel() {
            const allChecked = Array.from(checkboxes).length > 0 && Array.from(checkboxes).every(cb => cb
                .checked);
            toggleAllText.textContent = allChecked ? '[Uncheck All]' : '[Check All]';
        }

        toggleAllBtn.addEventListener('click', () => {
            const allCurrentlyChecked = Array.from(checkboxes).every(cb => cb.checked);
            const newState = !allCurrentlyChecked;

            // First, remove disabled state from everything before changing
            checkboxes.forEach(cb => {
                cb.disabled = false;
                cb.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
            });

            // Set the new check state
            checkboxes.forEach(cb => {
                cb.checked = newState;

                // Respect view-dashboard rule after initial check
                if (cb.classList.contains('view-checkbox')) {
                    const event = new Event('change');
                    cb.dispatchEvent(event);
                }
            });

            // After setting all, reapply rules so only valid ones stay enabled
            applyGlobalDashboardRule();
            updateToggleAllLabel();
        });

        // Global View Dashboard rule
        function applyGlobalDashboardRule() {
            const isDashboardChecked = viewDashboardCheckbox.checked;

            // If unchecked, disable and uncheck everything except View Dashboard
            if (!isDashboardChecked) {
                checkboxes.forEach(cb => {
                    if (cb !== viewDashboardCheckbox) {
                        cb.checked = false;
                        cb.disabled = true;
                        cb.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    }
                });
            } else {
                // Re-enable checkboxes that follow their group "view" rules
                checkboxes.forEach(cb => {
                    if (cb !== viewDashboardCheckbox) {
                        cb.disabled = false; // will be further refined by group rules
                    }
                });
                enforceGroupViewRules(); // reapply group-level enabling logic
            }
        }

        // Enforce view checkbox dependencies
        function enforceGroupViewRules() {
            const viewCheckboxes = qa('.view-checkbox');

            // Submissions group per-permission pairing
            const viewThesis = q('#edit-perms-view-thesis-submissions');
            const viewForms = q('#edit-perms-view-forms-submissions');
            const actThesis = q('#edit-perms-acc-rej-thesis-submissions');
            const actForms = q('#edit-perms-acc-rej-forms-submissions');

            const updateThesis = () => {
                const allowed = !!(viewThesis && viewThesis.checked && viewDashboardCheckbox.checked);
                if (actThesis) {
                    actThesis.disabled = !allowed;
                    if (!allowed) actThesis.checked = false;
                    if (allowed) {
                        actThesis.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                        actThesis.classList.add('text-blue-600', 'focus:ring-blue-500');
                    } else {
                        actThesis.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    }
                }
            };

            const updateForms = () => {
                const allowed = !!(viewForms && viewForms.checked && viewDashboardCheckbox.checked);
                if (actForms) {
                    actForms.disabled = !allowed;
                    if (!allowed) actForms.checked = false;
                    if (allowed) {
                        actForms.classList.remove('disabled:opacity-50', 'disabled:cursor-not-allowed');
                        actForms.classList.add('text-blue-600', 'focus:ring-blue-500');
                    } else {
                        actForms.classList.add('disabled:opacity-50', 'disabled:cursor-not-allowed');
                    }
                }
            };

            updateThesis();
            updateForms();
            if (viewThesis) viewThesis.addEventListener('change', updateThesis);
            if (viewForms) viewForms.addEventListener('change', updateForms);
            viewDashboardCheckbox.addEventListener('change', () => {
                updateThesis();
                updateForms();
            });

            // Default behavior for other groups
            viewCheckboxes.forEach(viewCb => {
                const group = viewCb.dataset.group;
                if (!group || group === 'submissions') return;

                const dependentCheckboxes = document.querySelectorAll(
                    `#edit-admin-perms-popup input[data-group="${group}"]:not(.view-checkbox)`
                );

                const updateDependents = () => {
                    const isViewChecked = viewCb.checked && viewDashboardCheckbox.checked;
                    dependentCheckboxes.forEach(cb => {
                        cb.disabled = !isViewChecked;
                        if (!isViewChecked) cb.checked = false;

                        if (isViewChecked) {
                            cb.classList.remove('disabled:opacity-50',
                                'disabled:cursor-not-allowed');
                            cb.classList.add('text-blue-600', 'focus:ring-blue-500');
                        } else {
                            cb.classList.add('disabled:opacity-50',
                                'disabled:cursor-not-allowed');
                        }
                    });
                };

                updateDependents();
                viewCb.addEventListener('change', updateDependents);
            });
        }

        // Initialize the rules
        viewDashboardCheckbox.addEventListener('change', applyGlobalDashboardRule);
        applyGlobalDashboardRule();
        enforceGroupViewRules();
        updateToggleAllLabel();

        // Keep label in sync on individual changes
        checkboxes.forEach(cb => {
            cb.addEventListener('change', updateToggleAllLabel);
        });

        // Form submission
        q('#eap-confirm-btn').addEventListener('click', async function(e) {
            e.preventDefault();

            const form = q('form');
            const userId = form.dataset.userId;

            try {
                const permissions = [];
                qa('input[type="checkbox"]:checked')
                    .forEach(checkbox => {
                        const permission = checkbox.id.replace('edit-perms-', '').replace(/-/g,
                            '_');
                        permissions.push(permission);
                    });

                if (permissions.length === 0) {
                    throw new Error('Please select at least one permission.');
                }

                const response = await fetch(`/admin/users/${userId}/update-permissions`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            .content
                    },
                    body: JSON.stringify({
                        permissions
                    })
                });

                if (!response.ok) {
                    let message = 'Failed to update permissions.';
                    try {
                        const err = await response.json();
                        message = err?.message || err?.errors?.permissions?.[0] || message;
                    } catch (_) {}
                    throw new Error(message);
                }

                // Show success popup
                const kpopup = document.getElementById('universal-ok-popup');
                const kTopText = document.getElementById('OKtopText');
                const kSubText = document.getElementById('OKsubText');
                const kConfirmBtn = document.getElementById('uniOK-confirm-btn');

                kTopText.textContent = "Successful!";
                kSubText.textContent = 'Permissions updated successfully!';
                kpopup.style.display = 'flex';
                document.getElementById('edit-admin-perms-popup').style.display = 'none';

                if (!kConfirmBtn._hasHandler) {
                    kConfirmBtn.addEventListener('click', function() {
                        if (typeof fetchUserData === 'function') {
                            fetchUserData();
                        } else {
                            location.reload();
                        }
                        kpopup.style.display = 'none';
                    });
                    kConfirmBtn._hasHandler = true;
                }

            } catch (error) {
                console.error('Update error:', error);
                const popup = document.getElementById('universal-x-popup');
                const xTopText = document.getElementById('x-topText');
                const xSubText = document.getElementById('x-subText');
                xTopText.textContent = "Error!";
                xSubText.textContent = error.message;
                popup.style.display = 'flex';
            }
        });
    });
</script>
