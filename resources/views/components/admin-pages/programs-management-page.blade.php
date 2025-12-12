<!-- Programs Management -->
<main id="programs-management-page"
    class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    @if (auth()->user() && auth()->user()->hasPermission('modify-programs-list'))
        <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="w-full">
                <div class="mb-5 flex w-full items-center justify-between">
                    <h1 class="text-2xl font-bold text-[#575757]">Programs Management</h1>
                    <div class="flex flex-wrap justify-end gap-1 sm:gap-2">
                        <!-- Add Program Button -->
                        <button id="add-program-btn"
                            class="w-full max-w-[150px] cursor-pointer rounded-lg bg-gradient-to-r from-[#CE6767] to-[#A44444] px-4 py-2 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto">
                            Add
                        </button>
                    </div>
                </div>

                <!-- Responsive Actions Wrapper -->
                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
                    <input type="text" id="programs-search" name="programs-search" placeholder="Search programs..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
                    <div class="flex flex-wrap justify-end gap-2 sm:gap-4">
                        <!-- Degree Filter Dropdown -->
                        <div class="relative w-fit">
                            <select name="programs-dd-degree" id="programs-degree-filter"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                <option value="">All Degrees</option>
                                <option value="Undergraduate">Undergraduate</option>
                                <option value="Graduate">Graduate</option>
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
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">Name
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Degree</th>
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Action</th>
                    </tr>
                </thead>
                <tbody id="programs-table-body" class="divide-y divide-gray-200 bg-[#fdfdfd] text-[#575757]">
                </tbody>
            </table>
        </div>

        <!-- Pagination Controls -->
        <div id="pagination-controls-programs" class="mt-4 flex justify-end space-x-2">
            <button onclick="changePage('programs', -1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
            <span id="pagination-info-programs" class="px-3 py-1 text-[#575757]">Page 1</span>
            <button onclick="changePage('programs', 1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
        </div>

        <!-- Universal Modals -->
        <x-popups.universal-ok-m />
        <x-popups.universal-x-m />
        <x-popups.universal-option-m />

        <!-- Add Program Popup -->
        <x-popups.add-program-m />
    @else
        <p class="text-red-600">You don't have permission to modify programs.</p>
    @endif
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const root = document.getElementById('programs-management-page');
        const tbody = document.getElementById('programs-table-body');
        const searchInput = document.getElementById('programs-search');
        const degreeFilter = document.getElementById('programs-degree-filter');
        const addProgramBtn = document.getElementById('add-program-btn');

        if (!tbody) return;

        let allPrograms = [];

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

        // Input sanitization helpers (restrict risky inputs at source)
        function sanitizeProgramName(value) {
            // Allow letters, numbers, spaces, hyphen, ampersand, slash, parentheses, and period
            return value
                .replace(/<|>|javascript:|on\w+=/gi, '')
                .replace(/[^A-Za-z0-9 \-&\/().]/g, '')
                .replace(/\s{2,}/g, ' ')
                .trimStart();
        }


        function loadPrograms() {
            console.log('Loading programs...');
            fetch('/admin/programs', {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(r => {
                    console.log('Programs response status:', r.status);
                    if (r.ok) {
                        return r.json();
                    } else {
                        throw new Error(`HTTP error! status: ${r.status}`);
                    }
                })
                .then(list => {
                    console.log('Programs loaded:', list);
                    allPrograms = list;
                    displayPrograms(list);
                })
                .catch(error => {
                    console.error('Error loading programs:', error);
                    showError('Failed to load programs. Please refresh the page.');
                });
        }

        function displayPrograms(programs) {
            tbody.innerHTML = '';
            programs.forEach(item => {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-2 py-3 sm:px-6">
                        <input class="w-full min-w-0 rounded border px-2 py-1 text-xs sm:text-sm" value="${item.name}" data-field="name" data-id="${item.id}">
                        </td>
                    <td class="px-2 py-3 sm:px-6">
                        <div class="relative w-fit">
                            <select class="w-full min-w-0 appearance-none rounded border px-2 py-1 pr-8 text-xs sm:text-sm" data-field="degree" data-id="${item.id}">
                                <option value="Undergraduate" ${item.degree==='Undergraduate'?'selected':''}>Undergraduate</option>
                                <option value="Graduate" ${item.degree==='Graduate'?'selected':''}>Graduate</option>
                            </select>
                            <div class="pointer-events-none absolute right-2 top-1/2 -translate-y-1/2 transform text-[#575757]">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </div>
                        </div>
                        </td>
                    <td class="pl-2 pr-6 py-3 whitespace-nowrap">
                        <button class="update-program text-green-700 hover:underline hover:cursor-pointer text-xs sm:text-sm" data-id="${item.id}">Update</button>
                        <button class="delete-program ml-1 sm:ml-3 text-red-600 hover:underline hover:cursor-pointer text-xs sm:text-sm" data-id="${item.id}" data-name="${item.name}">Delete</button>
                        </td>`;
                tbody.appendChild(tr);
            });

            // Show first page after displaying programs
            showPage('programs', 1);
        }

        // Delegate sanitization for inline edits
        tbody.addEventListener('input', (e) => {
            const target = e.target;
            if (target && target.matches('input[data-field="name"]')) {
                const clean = sanitizeProgramName(target.value).substring(0, 100);
                if (target.value !== clean) target.value = clean;
            }
        });

        tbody.addEventListener('paste', (e) => {
            const target = e.target;
            if (target && target.matches('input[data-field="name"]')) {
                e.preventDefault();
                const paste = (e.clipboardData || window.clipboardData).getData('text');
                const cleanPaste = sanitizeProgramName(paste).substring(0, 100);
                const start = target.selectionStart;
                const end = target.selectionEnd;
                const newValue = (target.value.substring(0, start) + cleanPaste + target.value
                    .substring(end)).substring(0, 100);
                target.value = newValue;
                target.dispatchEvent(new Event('input'));
            }
        });

        function filterPrograms() {
            const searchTerm = searchInput.value.toLowerCase();
            const degreeFilterValue = degreeFilter.value;

            let filtered = allPrograms.filter(program => {
                const matchesSearch = program.name.toLowerCase().includes(searchTerm);
                const matchesDegree = !degreeFilterValue || program.degree === degreeFilterValue;
                return matchesSearch && matchesDegree;
            });

            displayPrograms(filtered);
        }

        // Search functionality
        searchInput.addEventListener('input', filterPrograms);
        degreeFilter.addEventListener('change', filterPrograms);

        // Helper functions for universal modals
        function showSuccess(message) {
            console.log('Showing success modal:', message);
            const okTop = root?.querySelector('#OKtopText');
            const okSub = root?.querySelector('#OKsubText');
            const okPopup = root?.querySelector('#universal-ok-popup');
            if (okTop) okTop.textContent = "Success!";
            if (okSub) okSub.textContent = message;
            if (okPopup) okPopup.style.display = 'flex';
        }

        function showError(message) {
            console.log('Showing error modal:', message);
            const xTop = root?.querySelector('#x-topText');
            const xSub = root?.querySelector('#x-subText');
            const xPopup = root?.querySelector('#universal-x-popup');
            if (xTop) xTop.textContent = "Error!";
            if (xSub) xSub.textContent = message;
            if (xPopup) xPopup.style.display = 'flex';
        }

        function showConfirm(title, message, onConfirm) {
            const optTop = root?.querySelector('#opt-topText');
            const optSub = root?.querySelector('#opt-subText');
            const optPopup = root?.querySelector('#universal-option-popup');
            if (optTop) optTop.textContent = title;
            if (optSub) optSub.textContent = message;
            if (optPopup) optPopup.style.display = 'flex';

            // Remove existing listeners
            const confirmBtn = root?.querySelector('#uniOpt-confirm-btn');
            const newConfirmBtn = confirmBtn.cloneNode(true);
            confirmBtn.parentNode.replaceChild(newConfirmBtn, confirmBtn);

            // Add new listener
            newConfirmBtn.addEventListener('click', () => {
                const popup = root?.querySelector('#universal-option-popup');
                if (popup) popup.style.display = 'none';
                onConfirm();
            });
        }

        // Add Program button click handler (popup will be handled by the popup component)

        // Duplication check function
        function checkDuplicateProgram(name, degree) {
            const trimmedName = name.trim().toLowerCase();
            const exactDuplicate = allPrograms.some(program =>
                program.name.toLowerCase() === trimmedName && program.degree === degree
            );
            const crossDegreeDuplicate = allPrograms.some(program =>
                program.name.toLowerCase() === trimmedName && program.degree !== degree
            );

            console.log('Checking duplicate:', {
                name: trimmedName,
                degree,
                exactDuplicate,
                crossDegreeDuplicate,
                allPrograms
            });

            return {
                exact: exactDuplicate,
                crossDegree: crossDegreeDuplicate,
                hasAny: exactDuplicate || crossDegreeDuplicate
            };
        }


        // Universal modal close handlers
        const okConfirmBtn = root?.querySelector('#uniOK-confirm-btn');
        if (okConfirmBtn) {
            okConfirmBtn.addEventListener('click', () => {
                const okPopup = root?.querySelector('#universal-ok-popup');
                if (okPopup) okPopup.style.display = 'none';
            });
        }

        const xConfirmBtn = root?.querySelector('#uniX-confirm-btn');
        if (xConfirmBtn) {
            xConfirmBtn.addEventListener('click', () => {
                const xPopup = root?.querySelector('#universal-x-popup');
                if (xPopup) xPopup.style.display = 'none';
            });
        }

        const optCancelBtn = root?.querySelector('#uniOpt-cancel-btn');
        if (optCancelBtn) {
            optCancelBtn.addEventListener('click', () => {
                const optPopup = root?.querySelector('#universal-option-popup');
                if (optPopup) optPopup.style.display = 'none';
            });
        }

        // Table row click handlers
        tbody.addEventListener('click', e => {
            const delBtn = e.target.closest('.delete-program');
            const updBtn = e.target.closest('.update-program');

            if (delBtn) {
                const id = delBtn.dataset.id;
                const name = delBtn.dataset.name;

                showConfirm(
                    'Delete Program',
                    `Are you sure you want to delete "${name}"? This action cannot be undone.`,
                    () => {
                        console.log('Deleting program with ID:', id);
                        fetch(`/admin/programs/${id}`, {
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
                                    showSuccess('Program deleted successfully!');
                                    loadPrograms();
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showSuccess('Program deleted successfully!');
                                    loadPrograms();
                                }
                            } else {
                                try {
                                    const responseData = await r.json();
                                    const errorMessage = responseData?.message ||
                                        'Failed to delete program. Please try again.';
                                    showError(errorMessage);
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showError(
                                        'Failed to delete program. Please try again.'
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
                const name = sanitizeProgramName(
                    tbody.querySelector(`input[data-id="${id}"][data-field="name"]`)?.value || ''
                ).trim();
                const degree = tbody.querySelector(`select[data-id="${id}"][data-field="degree"]`)
                    ?.value;

                if (!name) {
                    showError('Program name cannot be empty');
                    return;
                }

                showConfirm(
                    'Update Program',
                    `Are you sure you want to update this program to "${name}" (${degree})?`,
                    () => {
                        // Check if any changes were made
                        const originalProgram = allPrograms.find(p => p.id == id);
                        if (originalProgram && originalProgram.name === name && originalProgram
                            .degree === degree) {
                            showError('No changes detected.');
                            return; // Do nothing if no changes
                        }

                        const body = new URLSearchParams({
                            name,
                            degree
                        });
                        console.log('Program update data:', {
                            id,
                            name,
                            degree,
                            body: body.toString()
                        });
                        fetch(`/admin/programs/${id}`, {
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
                            console.log('Program update response status:', r.status);
                            console.log('Program update response ok:', r.ok);

                            if (r.ok) {
                                try {
                                    const responseData = await r.json();
                                    console.log('Program update response data:',
                                        responseData);
                                    showSuccess('Program updated successfully!');
                                    loadPrograms();
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showSuccess('Program updated successfully!');
                                    loadPrograms();
                                }
                            } else {
                                try {
                                    const responseData = await r.json();
                                    const errorMessage = responseData?.message ||
                                        'Failed to update program. Please try again.';
                                    showError(errorMessage);
                                } catch (jsonError) {
                                    console.error('JSON parse error:', jsonError);
                                    showError(
                                        'Failed to update program. Please try again.'
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

        loadPrograms();
    });
</script>
