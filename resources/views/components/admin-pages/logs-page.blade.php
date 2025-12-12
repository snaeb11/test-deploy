<!-- Logs Table -->
<main id="logs-table" class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    <div class="mb-4 flex items-center justify-between">
        <h1 class="text-2xl font-bold text-[#575757]">Logs</h1>
        <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
            <input type="text" id="logs-submission-search" name="logs-submission-search" placeholder="Search..."
                class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
        </div>
    </div>

    <div class="overflow-x-auto rounded-lg bg-[#fdfdfd] p-4 shadow">
        @if (auth()->user()->hasPermission('view-logs'))
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#fdfdfd]">
                    <tr>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Name
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Action
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Target table
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Target ID
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Timestamp
                        </th>
                    </tr>
                </thead>
                <tbody id="logs-table-body" class="bg-[#fdfdfd]] divide-y divide-gray-200 text-[#575757]">
                </tbody>
            </table>
        @else
            <p class="text-red-600">You have no view permissions for Logs.</p>
        @endif
    </div>

    @if (auth()->user()->hasPermission('view-logs'))
        <div id="pagination-controls-logs" class="mt-4 flex justify-end space-x-2">
            <button onclick="changePage('logs', -1)"
                class="rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
            <span id="pagination-info-logs" class="px-3 py-1 text-[#575757]">Page 1</span>
            <button onclick="changePage('logs', 1)"
                class="rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
        </div>
    @endif

</main>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const logsMain = document.getElementById('logs-table');
        const tbody = document.getElementById('logs-table-body');
        const searchInput = document.getElementById('logs-submission-search');

        if (!logsMain || !tbody) return;

        // Store all logs data for filtering
        let allLogs = [];
        let currentRenderedContent = ''; // Track rendered content to prevent unnecessary re-renders

        function safeText(value) {
            return (value ?? '').toString();
        }

        function renderLogs(logs) {
            // Generate content string to compare
            const newContent = logs.map(item => {
                const userName = safeText(item.name ?? item.user_name ?? (item.user?.name ?? ''));
                const action = safeText(item.action_label || item.action || '');
                const targetTable = safeText(item.target_table || '');
                const targetId = safeText(item.target_id ?? '');
                const timestamp = safeText(item.performed_at ? new Date(item.performed_at)
                    .toLocaleString() : '');
                return `${userName}|${action}|${targetTable}|${targetId}|${timestamp}`;
            }).join('||');

            // Only re-render if content actually changed
            if (newContent === currentRenderedContent && tbody.children.length > 0) {
                return;
            }

            currentRenderedContent = newContent;
            tbody.innerHTML = '';

            if (logs.length === 0) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td colspan="5" class="px-6 py-3 text-center text-sm text-gray-500 italic">
                        No matching results found.
                    </td>
                `;
                tbody.appendChild(tr);
                return;
            }

            logs.forEach(item => {
                const tr = document.createElement('tr');
                const userName = safeText(item.name ?? item.user_name ?? (item.user?.name ?? ''));
                const action = safeText(item.action_label || item.action || '');
                const targetTable = safeText(item.target_table || '');
                const targetId = safeText(item.target_id ?? '');
                const timestamp = safeText(item.performed_at ? new Date(item.performed_at)
                    .toLocaleString() : '');

                tr.innerHTML = `
                    <td class="px-6 py-3 text-sm">${userName}</td>
                    <td class="px-6 py-3 text-sm">${action}</td>
                    <td class="px-6 py-3 text-sm">${targetTable}</td>
                    <td class="px-6 py-3 text-sm">${targetId}</td>
                    <td class="px-6 py-3 text-sm whitespace-nowrap">${timestamp}</td>
                `;
                tbody.appendChild(tr);
            });
        }

        function filterLogs(searchQuery) {
            if (!searchQuery || searchQuery.trim() === '') {
                renderLogs(allLogs);
                return;
            }

            const query = searchQuery.toLowerCase().trim();
            const filtered = allLogs.filter(item => {
                const userName = safeText(item.name ?? item.user_name ?? (item.user?.name ?? ''))
                    .toLowerCase();
                const action = safeText(item.action_label || item.action || '').toLowerCase();
                const targetTable = safeText(item.target_table || '').toLowerCase();
                const targetId = safeText(item.target_id ?? '').toLowerCase();
                const timestamp = safeText(item.performed_at ? new Date(item.performed_at)
                    .toLocaleString() : '').toLowerCase();

                return userName.includes(query) ||
                    action.includes(query) ||
                    targetTable.includes(query) ||
                    targetId.includes(query) ||
                    timestamp.includes(query);
            });

            renderLogs(filtered);
        }

        let loadTimeoutId;

        function showLoadingState() {
            tbody.innerHTML = '';
            for (let i = 0; i < 5; i++) {
                const tr = document.createElement('tr');
                tr.innerHTML = `
                    <td class="px-6 py-3 text-sm text-gray-400">Loading…</td>
                    <td class="px-6 py-3 text-sm text-gray-400">Loading…</td>
                    <td class="px-6 py-3 text-sm text-gray-400">Loading…</td>
                    <td class="px-6 py-3 text-sm text-gray-400">Loading…</td>
                    <td class="px-6 py-3 text-sm text-gray-400">Loading…</td>
                `;
                tbody.appendChild(tr);
            }
        }

        async function loadLogs(silent = false) {
            try {
                const r = await fetch('/logs/data?ts=' + Date.now(), {
                    method: 'GET',
                    credentials: 'same-origin',
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'Cache-Control': 'no-store'
                    },
                    cache: 'no-store'
                });
                if (!r.ok) throw new Error(`HTTP ${r.status}`);
                const data = await r.json();
                const list = Array.isArray(data) ? data : (data?.data || []);

                // Only update if data actually changed
                const newDataString = JSON.stringify(list);
                const oldDataString = JSON.stringify(allLogs);

                if (newDataString !== oldDataString) {
                    allLogs = list;
                    // Apply current search filter if any
                    const searchQuery = searchInput ? searchInput.value : '';
                    filterLogs(searchQuery);
                }
            } catch (err) {
                console.error('Failed to load logs:', err);
                // If universal error modal exists, use it; else, no-op
                const xTop = document.getElementById('x-topText');
                const xSub = document.getElementById('x-subText');
                const xPopup = document.getElementById('universal-x-popup');
                if (xTop && xSub && xPopup) {
                    xTop.textContent = 'Error!';
                    xSub.textContent = 'Failed to load logs. Please try again.';
                    xPopup.style.display = 'flex';
                }
            }
        }

        // Add search functionality
        if (searchInput) {
            let searchTimeout;
            searchInput.addEventListener('input', () => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    const searchQuery = searchInput.value;
                    filterLogs(searchQuery);
                }, 300); // Debounce search by 300ms
            });
        }

        function isVisible(el) {
            return !el.classList.contains('hidden');
        }

        // Load immediately if already visible (first render)
        if (isVisible(logsMain)) {
            loadLogs();
        }

        // Reload ONLY when the Logs tab/button is explicitly clicked to avoid flicker
        document.addEventListener('click', (e) => {
            const trigger = e.target.closest(
                '[href="#logs-table"], [data-target="#logs-table"], [data-target="logs-table"], [data-section="logs"], #logs-nav'
            );
            if (trigger) {
                // Short delay to allow tab switch DOM changes first
                setTimeout(() => {
                    if (isVisible(logsMain)) {
                        loadLogs();
                    }
                }, 50);
            }
        });

        // expose a programmatic way to refresh logs after actions elsewhere
        window.reloadLogs = function() {
            if (isVisible(logsMain)) {
                loadLogs(true); // silent refresh
            }
        };

        // Set up auto-refresh that respects search state and prevents flicker
        let autoRefreshInterval;

        function startAutoRefresh() {
            if (autoRefreshInterval) clearInterval(autoRefreshInterval);
            autoRefreshInterval = setInterval(() => {
                if (isVisible(logsMain)) {
                    loadLogs(true); // silent refresh
                }
            }, 3000); // refresh every 3s when visible
        }

        // Start auto-refresh
        if (isVisible(logsMain)) {
            startAutoRefresh();
        }

        // Restart auto-refresh when logs section becomes visible
        const observer = new MutationObserver(() => {
            if (isVisible(logsMain)) {
                if (!autoRefreshInterval) {
                    startAutoRefresh();
                }
            } else {
                if (autoRefreshInterval) {
                    clearInterval(autoRefreshInterval);
                    autoRefreshInterval = null;
                }
            }
        });

        if (logsMain) {
            observer.observe(logsMain, {
                attributes: true,
                attributeFilter: ['class']
            });
        }
    });
</script>
