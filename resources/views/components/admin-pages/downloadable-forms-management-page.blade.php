<!-- Downloadable Forms Management -->
<main id="downloadable-forms-management-page"
    class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    @if (auth()->user() && auth()->user()->hasPermission('modify-downloadable-forms'))
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full">
                <div class="mb-5 flex w-full items-center justify-between">
                    <h1 class="text-2xl font-bold text-[#575757]">Downloadable Forms Management</h1>
                    <div class="flex flex-wrap justify-end gap-1 sm:gap-2">
                        <!-- Add Form Button -->
                        <button id="add-form-btn"
                            class="w-full max-w-[150px] cursor-pointer rounded-lg bg-gradient-to-r from-[#CE6767] to-[#A44444] px-4 py-2 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto">
                            Add
                        </button>
                    </div>
                </div>

                <!-- Responsive Actions Wrapper -->
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
                    <input type="text" id="forms-search" name="forms-search" placeholder="Search forms..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
                    <div class="flex flex-wrap justify-end gap-2 sm:gap-4">
                        <!-- Category Filter Dropdown -->
                        <div class="relative w-fit">
                            <select name="forms-dd-category" id="forms-category-filter"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                <option value="">All Categories</option>
                                <option value="rndd_forms">R&DD Forms</option>
                                <option value="moa_forms">MOA Forms</option>
                            </select>
                            <div
                                class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 transform text-[#575757]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="overflow-x-auto rounded-lg bg-[#fdfdfd] p-4 shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#fdfdfd]">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Form
                            Type & Link
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Category</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Action</th>
                    </tr>
                </thead>
                <tbody id="forms-table-body" class="divide-y divide-gray-200 bg-[#fdfdfd] text-[#575757]">
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div id="pagination-controls-forms" class="mt-4 flex justify-end space-x-2">
            <button onclick="changePage('forms', -1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
            <span id="pagination-info-forms" class="px-3 py-1 text-[#575757]">Page 1</span>
            <button onclick="changePage('forms', 1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
        </div>

        <!-- Universal Modals -->
        <x-popups.universal-ok-m />
        <x-popups.universal-x-m />
        <x-popups.universal-option-m />

        <!-- Add Downloadable Form Popup -->
        <x-popups.add-downloadable-form-m />
    @else
        <div class="text-center">
            <h1 class="text-2xl font-bold text-[#575757]">Access Denied</h1>
            <p class="mt-2 text-gray-600">You don't have permission to manage downloadable forms.</p>
        </div>
    @endif
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const pageRoot = document.getElementById('downloadable-forms-management-page');
        const tbody = document.getElementById('forms-table-body');
        const searchInput = document.getElementById('forms-search');
        const categoryFilter = document.getElementById('forms-category-filter');
        const addFormBtn = document.getElementById('add-form-btn');

        if (!tbody) return;

        let allForms = [];

        // Pagination variables and functions
        const rowsPerPage = 18;
        const currentPages = {};

        function showPage(tableKey, page) {
            const tbody = document.getElementById(`${tableKey}-table-body`);
            const rows = tbody?.querySelectorAll('tr') || [];
            const totalPages = Math.ceil(rows.length / rowsPerPage);
            if (page < 1) page = 1;
            if (page > totalPages) page = totalPages;
            currentPages[tableKey] = page;

            rows.forEach((row, i) => {
                row.style.display = (i >= (page - 1) * rowsPerPage && i < page * rowsPerPage) ? '' :
                    'none';
            });

            const info = document.getElementById(`pagination-info-${tableKey}`);
            if (info) info.textContent = `Page ${page} of ${totalPages}`;
        }

        function changePage(tableKey, offset) {
            const current = currentPages[tableKey] || 1;
            showPage(tableKey, current + offset);
        }

        // Input sanitization helpers
        function sanitizeFormTitle(value) {
            return value
                .replace(/<|>|javascript:|on\w+=/gi, '')
                .replace(/[^A-Za-z0-9 \-&\/().]/g, '')
                .replace(/\s{2,}/g, ' ')
                .trimStart();
        }

        function sanitizeFormUrl(value) {
            return value
                .replace(/<|>|javascript:|on\w+=/gi, '')
                .trim();
        }

        function isValidUrl(string) {
            try {
                new URL(string);
                return true;
            } catch (_) {
                return false;
            }
        }


        function loadForms() {
            console.log('Loading forms...');
            fetch('/admin/downloadable-forms', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => {
                    console.log('Forms response status:', r.status);
                    if (r.ok) {
                        return r.json();
                    } else {
                        throw new Error(`HTTP error! status: ${r.status}`);
                    }
                })
                .then(list => {
                    console.log('Forms loaded:', list);
                    allForms = list;
                    displayForms(list);
                })
                .catch(error => {
                    console.error('Error loading forms:', error);
                    showError('Failed to load forms. Please refresh the page.');
                });
        }

        function displayForms(forms) {
            tbody.innerHTML = '';
            forms.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-2 py-3 sm:px-6">
                        <input class="w-full min-w-0 rounded border px-2 py-1 text-xs sm:text-sm" value="${item.title}" data-field="title" data-id="${item.id}">
                        <input class="w-full min-w-0 rounded border px-2 py-1 text-xs sm:text-sm mt-1" value="${item.url}" data-field="url" data-id="${item.id}" placeholder="URL">
                    </td>
                    <td class="px-2 py-3 sm:px-6">
                        <div class="relative w-fit">
                            <select class="w-full min-w-0 appearance-none rounded border px-2 py-1 pr-8 text-xs sm:text-sm" data-field="category" data-id="${item.id}">
                                <option value="rndd_forms" ${item.category==='rndd_forms'?'selected':''}>R&DD Forms</option>
                                <option value="moa_forms" ${item.category==='moa_forms'?'selected':''}>MOA Forms</option>
                            </select>
                            <div class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 transform text-[#575757]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                    </td>
                    <td class="pl-2 pr-6 py-3 whitespace-nowrap">
                        <button class="update-form text-green-700 hover:underline hover:cursor-pointer text-xs sm:text-sm" data-id="${item.id}">Update</button>
                        <button class="delete-form ml-1 sm:ml-3 text-red-600 hover:underline hover:cursor-pointer text-xs sm:text-sm" data-id="${item.id}" data-title="${item.title}">Delete</button>
                    </td>`;
                tbody.appendChild(tr);
            });

            // Show first page after displaying forms
            showPage('forms', 1);
        }

        // Delegate sanitization for inline edits
        tbody.addEventListener('input', (e) => {
            const target = e.target;
            if (target && target.matches('input[data-field="title"]')) {
                const clean = sanitizeFormTitle(target.value).substring(0, 255);
                if (target.value !== clean) target.value = clean;
            }
            if (target && target.matches('input[data-field="url"]')) {
                const clean = sanitizeFormUrl(target.value).substring(0, 500);
                if (target.value !== clean) target.value = clean;
            }
        });

        tbody.addEventListener('paste', (e) => {
            const target = e.target;
            if (target && target.matches('input[data-field="title"]')) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleanPaste = sanitizeFormTitle(paste).substring(0, 255);
                const start = target.selectionStart;
                const end = target.selectionEnd;
                const newValue = (target.value.substring(0, start) + cleanPaste + target.value
                    .substring(end)).substring(0, 255);
                target.value = newValue;
                target.dispatchEvent(new Event('input'));
            }
            if (target && target.matches('input[data-field="url"]')) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleanPaste = sanitizeFormUrl(paste).substring(0, 500);
                const start = target.selectionStart;
                const end = target.selectionEnd;
                const newValue = (target.value.substring(0, start) + cleanPaste + target.value
                    .substring(end)).substring(0, 500);
                target.value = newValue;
                target.dispatchEvent(new Event('input'));
            }
        });

        function filterForms() {
            const searchTerm = searchInput.value.toLowerCase();
            const categoryFilterValue = categoryFilter.value;

            let filtered = allForms.filter(form => {
                const matchesSearch = form.title.toLowerCase().includes(searchTerm) ||
                    form.url.toLowerCase().includes(searchTerm);
                const matchesCategory = !categoryFilterValue || form.category === categoryFilterValue;
                return matchesSearch && matchesCategory;
            });

            displayForms(filtered);
        }

        // Search functionality
        searchInput.addEventListener('input', filterForms);
        categoryFilter.addEventListener('change', filterForms);

        // Helper functions for universal modals
        function showSuccess(message) {
            console.log('Showing success modal:', message);
            const okTop = pageRoot?.querySelector('#OKtopText');
            const okSub = pageRoot?.querySelector('#OKsubText');
            const okPopup = pageRoot?.querySelector('#universal-ok-popup');
            if (okTop) okTop.textContent = "Success!";
            if (okSub) okSub.textContent = message;
            if (okPopup) okPopup.style.display = 'flex';
        }

        function showError(message) {
            console.log('Showing error modal:', message);
            const xTop = pageRoot?.querySelector('#x-topText');
            const xSub = pageRoot?.querySelector('#x-subText');
            const xPopup = pageRoot?.querySelector('#universal-x-popup');
            if (xTop) xTop.textContent = "Error!";
            if (xSub) xSub.textContent = message;
            if (xPopup) xPopup.style.display = 'flex';
        }

        function showConfirm(title, message, onConfirm) {
            const optTop = pageRoot?.querySelector('#opt-topText');
            const optSub = pageRoot?.querySelector('#opt-subText');
            const optPopup = pageRoot?.querySelector('#universal-option-popup');
            if (optTop) optTop.textContent = title;
            if (optSub) optSub.textContent = message;
            if (optPopup) optPopup.style.display = 'flex';

            // Remove existing listeners safely within this page scope
            const confirmBtn = pageRoot?.querySelector('#uniOpt-confirm-btn');
            if (!confirmBtn) return;
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            // Add new listener
            newConfirmBtn.addEventListener('click', () => {
                const optPopupLocal = pageRoot?.querySelector('#universal-option-popup');
                if (optPopupLocal) optPopupLocal.style.display = 'none';
                onConfirm();
            });
        }

        // Add Form button click handler (popup will be handled by the popup component)

        // Duplication check function
        function checkDuplicateForm(title, category, url, excludeId = null) {
            const trimmedTitle = title.trim().toLowerCase();
            const trimmedUrl = url.trim().toLowerCase();

            // Title must be unique globally (independent of category)
            const exactTitleDuplicate = allForms.some(form =>
                form.title.toLowerCase() === trimmedTitle &&
                form.id != excludeId
            );

            const exactUrlDuplicate = allForms.some(form =>
                form.url.toLowerCase() === trimmedUrl &&
                form.id != excludeId
            );

            console.log('Checking duplicate form:', {
                title: trimmedTitle,
                category,
                url: trimmedUrl,
                excludeId,
                exactTitleDuplicate,
                exactUrlDuplicate,
                allForms
            });

            return {
                exactTitle: exactTitleDuplicate,
                exactUrl: exactUrlDuplicate,
                hasAny: exactTitleDuplicate || exactUrlDuplicate
            };
        }


        // Universal modal close handlers
        pageRoot?.querySelector('#uniOK-confirm-btn')?.addEventListener('click', () => {
            const okPopup = pageRoot?.querySelector('#universal-ok-popup');
            if (okPopup) okPopup.style.display = 'none';
        });

        pageRoot?.querySelector('#uniX-confirm-btn')?.addEventListener('click', () => {
            const xPopup = pageRoot?.querySelector('#universal-x-popup');
            if (xPopup) xPopup.style.display = 'none';
        });

        pageRoot?.querySelector('#uniOpt-cancel-btn')?.addEventListener('click', () => {
            const optPopup = pageRoot?.querySelector('#universal-option-popup');
            if (optPopup) optPopup.style.display = 'none';
        });

        // Table row click handlers
        tbody.addEventListener('click', e => {
            const delBtn = e.target.closest('.delete-form');
            const updBtn = e.target.closest('.update-form');

            if (delBtn) {
                const id = delBtn.dataset.id;
                const title = delBtn.dataset.title;

                showConfirm(
                    'Delete Form',
                    `Are you sure you want to delete "${title}"? This action cannot be undone.`,
                    () => {
                        console.log('Deleting form with ID:', id);
                        fetch(`/admin/downloadable-forms/${id}`, {
                            method: 'DELETE',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        }).then(async r => {
                            console.log('Delete response status:', r.status);

                            if (r.ok) {
                                try {
                                    const responseData = await r.json();
                                    console.log('Delete response data:', responseData);
                                    showSuccess('Form deleted successfully!');
                                    loadForms();
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showSuccess('Form deleted successfully!');
                                    loadForms();
                                }
                            } else {
                                try {
                                    const responseData = await r.json();
                                    const errorMessage = responseData?.message ||
                                        'Failed to delete form. Please try again.';
                                    showError(errorMessage);
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showError(
                                        'Failed to delete form. Please try again.'
                                    );
                                }
                            }
                        }).catch(error => {
                            console.error('Network error:', error);
                            showError(
                                'Network error. Please check your connection and try again.'
                            );
                        });
                    }
                );
            }

            if (updBtn) {
                const id = updBtn.dataset.id;
                const title = sanitizeFormTitle(
                    tbody.querySelector(`input[data-id="${id}"][data-field="title"]`)?.value || ''
                ).trim();
                const url = sanitizeFormUrl(
                    tbody.querySelector(`input[data-id="${id}"][data-field="url"]`)?.value || ''
                ).trim();
                const category = tbody.querySelector(`select[data-id="${id}"][data-field="category"]`)
                    ?.value;

                if (!title || !url) {
                    showError('Form type and URL cannot be empty');
                    return;
                }

                // Validate URL format
                if (!isValidUrl(url)) {
                    showError('Please enter a valid URL (e.g., https://example.com)');
                    return;
                }

                // Check for duplicates
                const duplicateCheck = checkDuplicateForm(title, category, url, id);
                if (duplicateCheck.exactTitle) {
                    showError('A form with this form type already exists.');
                    return;
                }
                if (duplicateCheck.exactUrl) {
                    showError('A form with this URL already exists.');
                    return;
                }

                showConfirm(
                    'Update Form',
                    `Are you sure you want to update this form to "${title}" (${category})?`,
                    () => {
                        // Check if any changes were made
                        const originalForm = allForms.find(f => f.id == id);
                        if (originalForm && originalForm.title === title && originalForm.url ===
                            url &&
                            originalForm.category === category) {
                            showError('No changes detected.');
                            return; // Do nothing if no changes
                        }

                        const body = new URLSearchParams({
                            title,
                            url,
                            category
                        });
                        console.log('Form update data:', {
                            id,
                            title,
                            url,
                            category,
                            body: body.toString()
                        });
                        fetch(`/admin/downloadable-forms/${id}`, {
                            method: 'PUT',
                            credentials: 'same-origin',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                        'meta[name="csrf-token"]')
                                    .content,
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'Accept': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body
                        }).then(async r => {
                            console.log('Form update response status:', r.status);
                            console.log('Form update response ok:', r.ok);

                            if (r.ok) {
                                try {
                                    const responseData = await r.json();
                                    console.log('Form update response data:',
                                        responseData);
                                    showSuccess('Form updated successfully!');
                                    loadForms();
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showSuccess('Form updated successfully!');
                                    loadForms();
                                }
                            } else {
                                try {
                                    const responseData = await r.json();
                                    const errorMessage = responseData?.message ||
                                        'Failed to update form. Please try again.';
                                    showError(errorMessage);
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showError(
                                        'Failed to update form. Please try again.'
                                    );
                                }
                            }
                        }).catch(error => {
                            console.error('Network error:', error);
                            showError(
                                'Network error. Please check your connection and try again.'
                            );
                        });
                    }
                );
            }
        });

        loadForms();
    });
</script>
