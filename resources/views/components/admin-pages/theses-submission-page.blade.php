<!-- Submission Table -->
<main id="submission-table" class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        @if (auth()->user() && auth()->user()->hasPermission('view-thesis-submissions'))
            <div class="w-full">
                <div class="mb-5 flex items-center justify-between">
                    <h1 class="text-2xl font-bold text-[#575757]">Thesis Submissions</h1>
                    <!-- History Button -->
                    <button id="history-btn"
                        class="flex w-full max-w-[150px] items-center justify-center rounded-lg bg-gradient-to-r from-[#FFC360] to-[#FFA104] px-4 py-2 text-[#fdfdfd] shadow transition-colors duration-200 hover:cursor-pointer hover:brightness-110 sm:w-auto">
                        History
                    </button>
                </div>

                <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
                    <input type="text" id="submission-search" name="submission-search" placeholder="Search..."
                        class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
                    <div class="flex flex-wrap justify-end gap-2 sm:gap-4">
                        <!-- submissions -->
                        <div class="relative">
                            <select name="subs-dd-status"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                <option value="">All Submissions</option>
                                <option value="pending">Pending</option>
                                <option value="accepted">Accepted</option>
                                <option value="rejected">Rejected</option>
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
                        <!-- Program Dropdown -->
                        <div class="relative">
                            <select name="subs-dd-program"
                                class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                <option value="">All Programs</option>
                                @if ($undergraduate->isNotEmpty())
                                    <optgroup label="Undergraduate Programs">
                                        @foreach ($undergraduate as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif

                                @if ($graduate->isNotEmpty())
                                    <optgroup label="Graduate Programs">
                                        @foreach ($graduate as $program)
                                            <option value="{{ $program->id }}">{{ $program->name }}</option>
                                        @endforeach
                                    </optgroup>
                                @endif
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
        <style>
            /* Keep columns from Adviser onward on a single line; show full text, except Program column */
            .thesis-submissions-table th:nth-child(n+5):not(:nth-child(6)),
            .thesis-submissions-table td:nth-child(n+5):not(:nth-child(6)) {
                white-space: nowrap;
            }

            /* Program column specific styling for submissions table */
            .thesis-submissions-table th:nth-child(6),
            .thesis-submissions-table td:nth-child(6) {
                white-space: normal !important;
                word-wrap: break-word;
                max-width: 300px;
                min-width: 200px;
            }

            /* History table from Adviser onward except Program (5th column) and Remarks (10th column) */
            .thesis-history-table th:nth-child(n+4):not(:nth-child(5)):not(:nth-child(10)),
            .thesis-history-table td:nth-child(n+4):not(:nth-child(5)):not(:nth-child(10)) {
                white-space: nowrap;
            }

            /* Program column specific styling for history table */
            .thesis-history-table th:nth-child(5),
            .thesis-history-table td:nth-child(5) {
                white-space: normal !important;
                word-wrap: break-word;
                max-width: 300px;
                min-width: 200px;
            }
        </style>
        <table class="thesis-submissions-table min-w-full divide-y divide-gray-200">
            <thead class="bg-[#fdfdfd]">
                <tr>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Title
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Author/s
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Abstract
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Uploaded File
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Adviser
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Program
                    </th>
                    <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                        data-column="0" data-order="asc" onclick="sortTable(this)">
                        Year
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
                    @if (auth()->user() &&
                            (auth()->user()->hasPermission('acc-rej-thesis-submissions') ||
                                auth()->user()->hasPermission('acc-rej-submissions')))
                        <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                            Action</th>
                    @endif
                </tr>
            </thead>
            <tbody id="submission-table-body" class="divide-y divide-gray-200 bg-[#fdfdfd] text-[#575757]">

            </tbody>
        </table>

        <!-- PDF Preview Modal -->
        <div id="pdf-preview-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50">
            <div class="relative w-full max-w-7xl rounded-lg bg-white px-2 pb-2 pt-2 shadow-lg">
                <div class="flex items-center justify-between pb-1 pl-2 pr-2">
                    <p class="text-sm text-gray-500" id="pdf-prev-fn">Filename</p>
                    <button id="close-preview-modal"
                        class="text-2xl font-bold text-black hover:text-red-600">X</button>
                </div>
                <iframe id="pdf-preview-iframe" class="h-[70vh] w-full rounded-lg border shadow"
                    src=""></iframe>
            </div>
        </div>
    </div>
    <div id="pagination-controls-submission" class="mt-4 flex justify-end space-x-2">
        <button onclick="changePage('submission', -1)"
            class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
        <span id="pagination-info-submission" class="px-3 py-1 text-[#575757]">Page 1</span>
        <button onclick="changePage('submission', 1)"
            class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
    </div>
@else
    <p class="text-red-600">You have no view permissions for Submissions.</p>

    <select name="subs-dd-program" class="hidden">
        <option value="">N/A</option>
    </select>

    <select name="subs-dd-academic_year" class="hidden">
        <option value="">N/A</option>
    </select>
    @endif
</main>

<!-- History Table -->
<main id="history-table" class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <h1 class="text-2xl font-bold text-[#575757]">Thesis Submissions History</h1>

        <div class="flex flex-wrap justify-end gap-2 sm:gap-4">
            <!-- Program Dropdown -->
            <div class="relative">
                <select name="history-dd-program"
                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                    <option value="">All Programs</option>
                    @if ($undergraduate->isNotEmpty())
                        <optgroup label="Undergraduate Programs">
                            @foreach ($undergraduate as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif

                    @if ($graduate->isNotEmpty())
                        <optgroup label="Graduate Programs">
                            @foreach ($graduate as $program)
                                <option value="{{ $program->id }}">{{ $program->name }}</option>
                            @endforeach
                        </optgroup>
                    @endif
                </select>
                <div class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 transform text-[#575757]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            </div>

            <!-- Pending Button -->
            <button id="pending-btn"
                class="w-full cursor-pointer rounded-lg bg-gradient-to-r from-[#FFC360] to-[#FFA104] px-4 py-2 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto">
                All
            </button>
        </div>
    </div>

    @if (auth()->user() && auth()->user()->hasPermission('view-thesis-submissions'))
        <div class="overflow-x-auto rounded-lg bg-[#fdfdfd] p-4 shadow">
            <table class="thesis-history-table min-w-full divide-y divide-gray-200">
                <thead class="bg-[#fdfdfd]">
                    <tr>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Title
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Author/s
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Abstract
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Adviser
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Program
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
                            Remarks
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Reviewed at
                        </th>
                    </tr>
                </thead>
                <tbody id="history-table-body" class="divide-y divide-gray-200 bg-[#fdfdfd] text-[#575757]">
                    {{-- <tr>
                        <td class="px-6 py-4 whitespace-normal">
                            <div class="max-w-[10vw] break-words">
                                SmartFarm: An IoT-Based Monitoring System for Sustainable Agriculture
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">Maria L. Santos<br>John P. Dela Cruz<br>Angela M. Reyes</td>
                        <td class="px-6 py-4 whitespace-normal">
                            <div class="max-w-[20vw] break-words">
                                This study presents SmartFarm, an IoT-based solution designed to monitor soil moisture, temperature, and humidity in real time to aid small-scale Filipino farmers. Utilizing sensor nodes and a cloud-based dashboard, the system provides timely data for crop management. The goal is to improve yield prediction and resource efficiency through smart agriculture practices.
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">Moana L. Tefiti</td>
                        <td class="px-6 py-4 whitespace-nowrap">BSIT</td>
                        <td class="px-6 py-4 whitespace-nowrap">2021</td>
                        <td class="px-6 py-4 whitespace-nowrap">Maria L. Santos</td>
                        <td class="px-6 py-4 whitespace-nowrap">July 02, 2025 13:45</td> --}}
                    </tr>
                </tbody>
            </table>

        </div>

        <div id="pagination-controls-history" class="mt-4 flex justify-end space-x-2">
            <button onclick="changePage('history', -1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
            <span id="pagination-info-history" class="px-3 py-1 text-[#575757]">Page 1</span>
            <button onclick="changePage('history', 1)"
                class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
        </div>
    @else
        <p class="text-red-600">You have no view permissions for Submissions.</p>
    @endif
</main>
