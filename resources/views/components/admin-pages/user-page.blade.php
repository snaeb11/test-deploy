@props(['undergraduate', 'graduate'])
<!-- Users Table -->
<main id="users-table" class="ml-[4vw] hidden p-8 transition-all duration-300 ease-in-out group-hover:ml-[18vw]">
    <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
        <div class="w-full">
            <div class="mb-5 flex w-full items-center justify-between">
                <h1 class="text-2xl font-bold text-[#575757]">Users</h1>
                <!-- Button -->
                @if (auth()->user() && auth()->user()->hasPermission('add-admin'))
                    <button id="add-admin-btn"
                        class="w-full max-w-[150px] cursor-pointer rounded-lg bg-gradient-to-r from-[#CE6767] to-[#A44444] px-4 py-2 text-[#fdfdfd] shadow hover:brightness-110 sm:w-auto">
                        Add admin
                    </button>
                @else
                    <button id="add-admin-btn" class="hidden"></button>
                @endif
            </div>

            <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
                <input type="text" id="user-search" name="user-search" placeholder="Search..."
                    class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
                <!-- Dropdown -->
                @if (auth()->user() && auth()->user()->hasPermission('view-accounts'))
                    <div class="relative">
                        <select name="accounts-dd"
                            class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                            <option value="">All Accounts</option>
                        </select>
                        <div
                            class="pointer-events-none absolute right-4 top-1/2 -translate-y-1/2 transform text-[#575757]">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                @else
                    <select name="accounts-dd" class="hidden"></select>
                @endif

            </div>
        </div>

    </div>

    <div class="overflow-x-auto rounded-lg bg-[#fdfdfd] p-4 shadow">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#fdfdfd]">
                <tr>
                    @if (auth()->user() && auth()->user()->hasPermission('view-accounts'))
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Name
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Email
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Account Type
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Program
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Degree
                        </th>
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Status
                        </th>
                    @else
                        <p class="text-red-600">You have no view permissions for Users.</p>
                    @endif
                    @if (auth()->user() && auth()->user()->hasPermission('edit-permissions'))
                        <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                            data-column="0" data-order="asc" onclick="sortTable(this)">
                            Action
                        </th>
                    @endif
                </tr>
            </thead>
            <tbody id="users-table-body" class="bg-[#fdfdfd]] divide-y divide-gray-200 text-[#575757]">
            </tbody>
        </table>
        @if (auth()->user() && auth()->user()->hasPermission('view-accounts'))
            <div id="pagination-controls-users" class="mt-4 flex justify-end space-x-2">
                <button onclick="changePage('users', -1)"
                    class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
                <span id="pagination-info-users" class="px-3 py-1 text-[#575757]">Page 1</span>
                <button onclick="changePage('users', 1)"
                    class="cursor-pointer rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
            </div>
        @endif
    </div>
</main>
