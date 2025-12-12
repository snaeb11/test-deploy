<!-- Forms Submission Table -->
<main id="forms-submission-table"
    class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        @if (auth()->user() && auth()->user()->hasPermission('view-forms-submissions'))
            <div class="w-full">
                <div class="mb-5 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-[#575757]">Form Submissions</h1>
                    <!-- History Button -->
                    <button id="forms-history-btn"
                        class="flex w-full max-w-[150px] items-center justify-center rounded-lg bg-gradient-to-r from-[#FFC360] to-[#FFA104] px-4 py-2 text-[#fdfdfd] shadow transition-colors duration-200 hover:brightness-110 sm:w-auto">
                        History
                    </button>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
                    <input type="text" id="forms-submission-search" name="forms-submission-search"
                        placeholder="Search..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
                    <div class="flex flex-wrap justify-end gap-2 sm:gap-4">

                        <!-- Status Dropdown: All Submissions / Pending / Accepted -->
                        <div class="relative">
                            <select name="forms-subs-dd-status"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                <option value="">All Submissions</option>
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
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

                        <!-- Form Type Dropdown (mirrors faculty form types) -->
                        <div class="relative">
                            <select name="forms-subs-dd-form-type"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                <option value="">All Form Types</option>
                                <optgroup label="R&DD Forms, Templates, and References">
                                    <option>Research Proposal Form</option>
                                    <option>Monthly Accomplishment Report</option>
                                    <option>Quarterly Progress Report</option>
                                    <option>Monitoting and Evaluation Form</option>
                                    <option>Monitoring and Performance Evaluation Form</option>
                                    <option>Monitoring Minutes</option>
                                    <option>Terminal Report Form</option>
                                    <option>SETI Scorecard</option>
                                    <option>SETI for SDGs Scorecard Guide</option>
                                    <option>GAD Assessment Checklist</option>
                                    <option>Special Order Template</option>
                                    <option>Notice of Engagement Template</option>
                                    <option>Request Letter for Extension Template</option>
                                    <option>Updated Workplan Template</option>
                                </optgroup>
                                <optgroup label="R&DD MOA Forms, Samples, and Referneces">
                                    <option>Routing Slip for Agreements (RSA)</option>
                                    <option>MOA Sample</option>
                                    <option>MOU Sample</option>
                                    <option>Supplemental MOA Sample</option>
                                </optgroup>
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
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Form Type
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Note
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Uploaded File
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Submitted by
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Submitted at
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Status
                    </th>
                    @if (auth()->user() && auth()->user()->hasPermission('acc-rej-forms-submissions'))
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Action
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody id="forms-submission-table-body" class="divide-y divide-gray-200 bg-[#fdfdfd] text-[#575757]">

            </tbody>
        </table>

        <!-- PDF Preview Modal -->
        <div id="forms-pdf-preview-modal"
            class="shadow-xl/30 backdrop-blur-xs fixed inset-0 z-50 hidden items-center justify-center">
            <div class="relative w-full max-w-7xl rounded-lg bg-white px-2 pb-2 pt-2 shadow-lg">
                <div class="flex items-center justify-between pb-1 pl-2 pr-2">
                    <p class="text-sm text-gray-500" id="forms-pdf-prev-fn">Filename</p>
                    <button id="forms-close-preview-modal"
                        class="text-2xl font-bold text-black hover:text-red-600">X</button>
                </div>
                <iframe id="forms-pdf-preview-iframe" class="h-[70vh] w-full rounded-lg border shadow"
                    src=""></iframe>
            </div>
        </div>
    </div>
    <div id="pagination-controls-forms-submission" class="mt-4 flex justify-end space-x-2">
        <button onclick="changePage('forms-submission', -1)"
            class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
        <span id="pagination-info-forms-submission" class="px-3 py-1 text-[#575757]">Page 1</span>
        <button onclick="changePage('forms-submission', 1)"
            class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
    </div>
@else
    <p class="text-red-600">You have no view permissions for Forms Submissions.</p>

    <select name="forms-subs-dd-program" class="hidden">
        <option value="">N/A</option>
    </select>

    <select name="forms-subs-dd-academic_year" class="hidden">
        <option value="">N/A</option>
    </select>
    @endif
</main>

<!-- Forms History Table -->
<main id="forms-history-table"
    class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-[#575757]">Form Submissions History</h1>

        <div class="flex flex-wrap justify-end gap-2 sm:gap-4">
            <input type="text" id="forms-history-search" name="forms-history-search" placeholder="Search..."
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
            <!-- Status Dropdown (no Pending) -->
            <div class="relative">
                <select name="forms-history-dd-status"
                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                    <option value="">All</option>
                    <option value="accepted">Accepted</option>
                    <option value="rejected">Rejected</option>
                    <option value="forwarded">Forwarded</option>
                </select>
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 transform text-[#575757]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Form Type Dropdown -->
            <div class="relative">
                <select name="forms-history-dd-form-type"
                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                    <option value="">All Form Types</option>
                    <optgroup label="R&DD Forms, Templates, and References">
                        <option>Research Proposal Form</option>
                        <option>Monthly Accomplishment Report</option>
                        <option>Quarterly Progress Report</option>
                        <option>Monitoting and Evaluation Form</option>
                        <option>Monitoring and Performance Evaluation Form</option>
                        <option>Monitoring Minutes</option>
                        <option>Terminal Report Form</option>
                        <option>SETI Scorecard</option>
                        <option>SETI for SDGs Scorecard Guide</option>
                        <option>GAD Assessment Checklist</option>
                        <option>Special Order Template</option>
                        <option>Notice of Engagement Template</option>
                        <option>Request Letter for Extension Template</option>
                        <option>Updated Workplan Template</option>
                    </optgroup>
                    <optgroup label="R&DD MOA Forms, Samples, and Referneces">
                        <option>Routing Slip for Agreements (RSA)</option>
                        <option>MOA Sample</option>
                        <option>MOU Sample</option>
                        <option>Supplemental MOA Sample</option>
                    </optgroup>
                </select>
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 transform text-[#575757]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Pending Button -->
            <button id="forms-pending-btn"
                class="w-full cursor-pointer rounded-lg bg-gradient-to-r from-[#FFC360] to-[#FFA104] px-4 py-2 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto">
                All
            </button>
        </div>
    </div>

    @if (auth()->user() && auth()->user()->hasPermission('view-forms-submissions'))
        <div class="overflow-x-auto rounded-lg bg-[#fdfdfd] p-4 shadow">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#fdfdfd]">
                    <tr>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Form Type
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Note
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Submitted by
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Submitted at
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Status
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Reviewed by
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Forwarded To
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Remarks
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Reviewed at
                        </th>
                    </tr>
                </thead>
                <tbody id="forms-history-table-body" class="divide-y divide-gray-200 bg-[#fdfdfd] text-[#575757]">
                </tbody>
            </table>

        </div>

        <div id="pagination-controls-forms-history" class="mt-4 flex justify-end space-x-2">
            <button onclick="changePage('forms-history', -1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
            <span id="pagination-info-forms-history" class="px-3 py-1 text-[#575757]">Page 1</span>
            <button onclick="changePage('forms-history', 1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
        </div>
    @else
        <p class="text-red-600">You have no view permissions for Forms Submissions.</p>
    @endif
</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Fetch and render pending form submissions (current user)
        const formsSearchInput = document.getElementById('forms-submission-search');
        const formsStatusSelect = document.querySelector('select[name="forms-subs-dd-status"]');
        const formsTypeSelect = document.querySelector('select[name="forms-subs-dd-form-type"]');

        function renderFormsSubmissionRows(items) {
            console.log('Rendering forms submission rows with items:', items);
            const tbody = document.getElementById('forms-submission-table-body');
            if (!tbody) return;
            tbody.innerHTML = '';

            if (!Array.isArray(items) || items.length === 0) {
                tbody.innerHTML = `
                    <tr>
                        <td colspan="8" class="px-6 py-4 text-center text-gray-500 italic">No pending form submissions.</td>
                    </tr>`;
                return;
            }

            items.forEach((f, idx) => {
                const row = document.createElement('tr');
                const color = {
                    accepted: 'bg-green-100 text-green-800',
                    pending: 'bg-yellow-100 text-yellow-800',
                    rejected: 'bg-red-100   text-red-800',
                } [(f.status || '').toLowerCase()] || 'bg-gray-100 text-gray-800';

                const noteRowId = `note-row-${idx}`;
                const noteToggleBtnId = `note-toggle-btn-${idx}`;

                const fileCell = f.document_filename ? `
                    <td class="px-6 py-4 whitespace-nowrap min-w-[200px] max-w-[300px]">
                        <div class="flex items-center gap-2">
                            <button type="button" class="forms-preview-btn text-[#9D3E3E] hover:underline text-sm font-bold" data-url="/forms/${f.id}/admin-view" data-filename="${f.document_filename}">
                                <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                Preview
                            </button>
                            <span class="text-gray-400">|</span>
                            <a href="/forms/${f.id}/download" class="flex items-center font-semibold text-sm text-[#9D3E3E] hover:underline truncate">
                                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                </svg>
                                <span class="truncate" title="${f.document_filename}">${f.document_filename}</span>
                            </a>
                        </div>
                    </td>` : `<td class="px-6 py-4 whitespace-nowrap min-w-[200px]">
                        <div class="flex items-center justify-center text-gray-500">No file uploaded</div>
                    </td>`;

                const noteCell = f.note && f.note.trim() !== '' ? `
                    <td class="items-center px-4 py-2">
                        <button type="button"
                                id="${noteToggleBtnId}"
                                class="flex items-center font-semibold text-sm text-[#9D3E3E] hover:underline cursor-pointer"
                                onclick="toggleFormsNote('${noteRowId}', '${noteToggleBtnId}')">View Note</button>
                    </td>` : `<td class="px-6 py-4 whitespace-nowrap">
                        <span class="text-gray-500">—</span>
                    </td>`;

                const statusColumn = `
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${color} capitalize">${f.status || '—'}</span>
                    </td>`;

                let actionButtons = '';
                if ((f.status || '').toLowerCase() === 'pending') {
                    actionButtons = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="text-green-600 hover:underline hover:cursor-pointer forms-approve-btn" data-id="${f.id}" data-filename="${f.document_filename||''}">Accept</button>
                            <button class="text-red-600 hover:underline hover:cursor-pointer ml-2 forms-decline-btn" data-id="${f.id}">Decline</button>
                        </td>
                    `;
                } else if ((f.status || '').toLowerCase() === 'accepted' || (f.status || '')
                    .toLowerCase() === 'approved') {
                    actionButtons = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <button class="text-blue-600 hover:underline hover:cursor-pointer forms-forward-btn" data-id="${f.id}" data-filename="${f.document_filename||''}">Forward</button>
                        </td>
                    `;
                } else {
                    actionButtons = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500">—</span>
                        </td>
                    `;
                }

                row.innerHTML = `
                    <td class="px-6 py-4 whitespace-nowrap">${f.form_type || '—'}</td>
                    ${noteCell}
                    ${fileCell}
                    <td class="px-6 py-4 whitespace-nowrap">${f.submitted_by || '—'}</td>
                    <td class="px-6 py-4 whitespace-nowrap">${formatDate(f.submitted_at)}</td>
                    ${statusColumn}
                    @if (auth()->user() && auth()->user()->hasPermission('acc-rej-forms-submissions'))
                        ${actionButtons}
                    @endif
                `;
                tbody.appendChild(row);

                // Note row
                if (f.note && f.note.trim() !== '') {
                    const noteRow = document.createElement('tr');
                    noteRow.id = noteRowId;
                    noteRow.className = 'hidden';
                    noteRow.innerHTML = `
                        <td colspan="8" class="px-6 py-3 text-base text-gray-700 bg-gray-50">
                            <div class="text-justify break-words overflow-wrap-break-word">${f.note}</div>
                        </td>
                    `;
                    tbody.appendChild(noteRow);
                }
            });

            try {
                showPage('forms-submission', 1);
            } catch (e) {}
        }

        async function fetchFormsSubmissions() {
            try {
                const q = (formsSearchInput?.value || '').trim().toLowerCase();
                const status = (formsStatusSelect?.value || '').toLowerCase();
                const ftype = (formsTypeSelect?.value || '').toLowerCase();

                // Always fetch pending; optionally fetch accepted when requested or for "All Submissions"
                const fetches = [fetch('/forms/pending').then(r => r.json()).catch(() => [])];
                if (status === 'accepted' || status === '') {
                    const params = new URLSearchParams({
                        status: 'accepted',
                        per_page: '100'
                    });
                    fetches.push(fetch(`/forms/history?${params.toString()}`).then(r => r.json()).then(d =>
                        (Array.isArray(d?.data) ? d.data : (Array.isArray(d) ? d : []))).catch(
                        () => []));
                }

                const [pendingItems, acceptedItems] = await Promise.all(fetches);
                let combined = [];
                if (Array.isArray(pendingItems)) combined = combined.concat(pendingItems);
                if (Array.isArray(acceptedItems)) combined = combined.concat(acceptedItems);

                // Client-side filters
                let items = combined;
                if (q) {
                    items = items.filter(it =>
                        `${it.form_type||''} ${it.note||''} ${it.document_filename||''}`.toLowerCase()
                        .includes(q));
                }
                if (status) {
                    items = items.filter(it => (it.status || '').toLowerCase() === status);
                }
                if (ftype) {
                    items = items.filter(it => (it.form_type || '').toLowerCase() === ftype);
                }

                renderFormsSubmissionRows(items);
            } catch (e) {
                console.error('Failed to fetch forms submissions:', e);
                renderFormsSubmissionRows([]);
            }
        }

        formsSearchInput?.addEventListener('input', fetchFormsSubmissions);
        formsStatusSelect?.addEventListener('change', fetchFormsSubmissions);
        formsTypeSelect?.addEventListener('change', fetchFormsSubmissions);
        fetchFormsSubmissions();

        // Forms submission action buttons
        document.addEventListener('click', e => {
            const btn = e.target;
            const id = btn.dataset.id;

            if (!btn.classList.contains('forms-approve-btn') && !btn.classList.contains(
                    'forms-decline-btn')) {
                return;
            }

            // Set the form ID for the popup
            const formIdHolder = document.getElementById('forms-submission-id-holder');
            if (formIdHolder) {
                formIdHolder.value = id;
            }
            const filenameHolder = document.getElementById('forms-filename-holder');
            if (filenameHolder) {
                filenameHolder.value = btn.dataset.filename || '';
            }

            const step1 = document.getElementById(btn.classList.contains('forms-approve-btn') ?
                'forms-ca-step1' : 'forms-cr-step1');
            const step2 = document.getElementById(btn.classList.contains('forms-approve-btn') ?
                'forms-ca-step2' : 'forms-cr-step2');

            if (step1 && step2) {
                step1.classList.remove('hidden');
                step2.classList.add('hidden');
            }

            const popup = document.getElementById(btn.classList.contains('forms-approve-btn') ?
                'forms-confirm-approval-popup' : 'forms-confirm-rejection-popup');
            if (popup) popup.style.display = 'flex';
        });

        // Forward button handler (from Accepted rows)
        document.addEventListener('click', e => {
            const btn = e.target.closest('.forms-forward-btn');
            if (!btn) return;
            const id = btn.dataset.id;
            const filename = btn.dataset.filename || '';
            const holder = document.getElementById('forms-submission-id-holder');
            if (holder) holder.value = id;
            if (window.openFormsForwardModal) {
                window.openFormsForwardModal(id, filename);
            }
        });

        // Preview modal for forms
        document.addEventListener('click', e => {
            const btn = e.target.closest('.forms-preview-btn');
            if (!btn) return;
            const url = btn.dataset.url;
            const modal = document.getElementById('forms-pdf-preview-modal');
            const iframe = document.getElementById('forms-pdf-preview-iframe');
            const closeBtn = document.getElementById('forms-close-preview-modal');
            const nameEl = document.getElementById('forms-pdf-prev-fn');
            if (!modal || !iframe || !closeBtn) return;
            iframe.src = url;
            if (nameEl) nameEl.textContent = btn.dataset.filename || 'Filename';
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            closeBtn.onclick = () => {
                modal.classList.add('hidden');
                modal.classList.remove('flex');
                iframe.src = '';
            };
        });

        // Fetch and render forms history (current user)
        async function fetchFormsHistory(page = 1) {
            try {
                const statusSel = document.querySelector('select[name="forms-history-dd-status"]');
                const typeSel = document.querySelector('select[name="forms-history-dd-form-type"]');
                const searchInput = document.getElementById('forms-history-search');
                const params = new URLSearchParams({
                    page: String(page)
                });
                if (statusSel && statusSel.value) params.set('status', statusSel.value);
                if (typeSel && typeSel.value) params.set('form_type', typeSel.value);
                if (searchInput && searchInput.value.trim()) params.set('search', searchInput.value.trim());
                const res = await fetch(`/forms/history?${params.toString()}`);
                const data = await res.json();
                const items = Array.isArray(data?.data) ? data.data : (Array.isArray(data) ? data : []);

                const tbody = document.getElementById('forms-history-table-body');
                if (!tbody) return;
                tbody.innerHTML = '';

                if (items.length === 0) {
                    tbody.innerHTML = `
                        <tr>
                            <td colspan="9" class="px-6 py-4 text-center text-gray-500 italic">No form history found.</td>
                        </tr>`;
                    return;
                }

                items.forEach((f, idx) => {
                    const row = document.createElement('tr');
                    const key = (typeof f.id !== 'undefined' && f.id !== null) ? f.id : idx;
                    const noteRowId = `history-note-row-${key}`;
                    const noteToggleBtnId = `history-note-toggle-btn-${key}`;
                    const remarksRowId = `history-remarks-row-${key}`;
                    const remarksBtnId = `history-remarks-btn-${key}`;

                    const noteCell = f.note && f.note.trim() !== '' ? `
                        <td class="items-center px-4 py-2">
                            <button type="button"
                                    id="${noteToggleBtnId}"
                                    class="inline-flex flex-col items-center font-semibold text-sm text-[#9D3E3E] hover:underline cursor-pointer whitespace-normal leading-tight text-center"
                                    onclick="toggleFormsNote('${noteRowId}', '${noteToggleBtnId}')">View<br>Note</button>
                        </td>` : `<td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-gray-500">—</span>
                        </td>`;

                    // Use the dedicated forwarded_to column
                    const forwardedTo = f.forwarded_to || 'N/A';

                    // Use review_remarks as-is (no need to parse/clean)
                    const cleanedRemarks = f.review_remarks;

                    const remarksCell = `
                        <td class="items-center px-4 py-2">
                            <button type="button"
                                    id="${remarksBtnId}"
                                    class="flex items-center font-semibold text-sm text-[#9D3E3E] hover:underline cursor-pointer"
                                    onclick="toggleRemarks('${remarksRowId}', '${remarksBtnId}')">View Remarks</button>
                        </td>`;

                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">${f.form_type || '—'}</td>
                        ${noteCell}
                        <td class="px-6 py-4 whitespace-nowrap">${f.submitted_by || '—'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">${f.submitted_at ? formatDate(f.submitted_at) : '—'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full ${
                                (f.status||'').toLowerCase()==='accepted'||(f.status||'').toLowerCase()==='approved' ? 'bg-green-100 text-green-800' :
                                (f.status||'').toLowerCase()==='rejected' ? 'bg-red-100 text-red-800' :
                                (f.status||'').toLowerCase()==='pending' ? 'bg-yellow-100 text-yellow-800' :
                                (f.status||'').toLowerCase()==='forwarded' ? 'bg-blue-100 text-blue-800' : 'bg-gray-100 text-gray-800'
                            }">${(f.status || 'pending')}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">${f.reviewed_by || '—'}</td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm ${forwardedTo !== 'N/A' ? 'text-blue-600' : 'text-gray-500'}">
                                ${forwardedTo}
                            </span>
                        </td>
                        ${remarksCell}
                        <td class="px-6 py-4 whitespace-nowrap">${f.reviewed_at ? formatDate(f.reviewed_at) : '—'}</td>
                    `;
                    tbody.appendChild(row);

                    // Note row
                    if (f.note && f.note.trim() !== '') {
                        const noteRow = document.createElement('tr');
                        noteRow.id = noteRowId;
                        noteRow.className = 'hidden';
                        noteRow.innerHTML = `
                            <td colspan="9" class="px-6 py-3 text-base text-gray-700 bg-gray-50">
                                <div class="text-justify break-words overflow-wrap-break-word">${f.note}</div>
                            </td>
                        `;
                        tbody.appendChild(noteRow);
                    }

                    // Remarks row (always render; fallback to No remarks)
                    const remarksRow = document.createElement('tr');
                    remarksRow.id = remarksRowId;
                    remarksRow.className = 'hidden';
                    remarksRow.innerHTML = `
                        <td colspan="9" class="px-6 py-3 text-base text-gray-700 bg-gray-50">
                            <div class="text-justify break-words overflow-wrap-break-word">${String(cleanedRemarks ?? '').trim() !== '' ? cleanedRemarks : 'No remarks'}</div>
                        </td>
                    `;
                    tbody.appendChild(remarksRow);
                });

                try {
                    showPage('forms-history', 1);
                } catch (e) {}
            } catch (e) {
                console.error('Failed to fetch forms history:', e);
            }
        }

        document.querySelector('select[name="forms-history-dd-status"]')?.addEventListener('change', () =>
            fetchFormsHistory(1));
        document.querySelector('select[name="forms-history-dd-form-type"]')?.addEventListener('change', () =>
            fetchFormsHistory(1));
        document.getElementById('forms-history-search')?.addEventListener('input', () => fetchFormsHistory(1));
        fetchFormsHistory(1);
    });

    function formatDate(dateStr) {
        if (!dateStr) return '—';
        const d = new Date(dateStr);
        if (isNaN(d.getTime())) return String(dateStr);
        return d.toLocaleString('en-US', {
            month: 'long',
            day: '2-digit',
            year: 'numeric',
            hour: '2-digit',
            minute: '2-digit'
        });
    }

    // Toggle functions for note and remarks
    function toggleFormsNote(rowId, btnId) {
        const row = document.getElementById(rowId);
        const btn = document.getElementById(btnId);
        if (!row || !btn) return;

        const isHidden = row.classList.contains('hidden');
        if (isHidden) {
            row.classList.remove('hidden');
            btn.textContent = 'Hide Note';
        } else {
            row.classList.add('hidden');
            btn.textContent = 'View Note';
        }
    }

    function toggleRemarks(rowId, btnId) {
        const row = document.getElementById(rowId);
        const btn = document.getElementById(btnId);
        if (!row || !btn) return;

        const isHidden = row.classList.contains('hidden');
        if (isHidden) {
            row.classList.remove('hidden');
            btn.textContent = 'Hide Remarks';
        } else {
            row.classList.add('hidden');
            btn.textContent = 'View Remarks';
        }
    }
</script>
