@extends('layouts.template.base', ['cssClass' => 'bg-[#fdfdfd] text-gray-900'])
@section('title', 'Faculty Dashboard')

@section('childContent')
    <x-layout-partials.header />
    <x-popups.logout-m />
    <x-popups.confirm-deactivation-m />
    <x-popups.faculty-add-submission-m :facultyAdvisers="$facultyAdvisers" />
    <x-popups.faculty-edit-acc-m :faculty="$faculty" :undergraduate="$undergraduate" :graduate="$graduate" />
    <x-popups.password-change-success-m />
    <x-popups.password-change-fail-m />

    <!-- Delete Pending Form Confirmation Modal -->
    <div id="faculty-delete-form-popup" style="display: none;"
        class="fixed inset-0 z-50 flex items-center justify-center bg-black/50">
        <div
            class="relative max-h-[90vh] w-[90%] max-w-sm rounded-2xl bg-[#fdfdfd] p-4 shadow-xl sm:max-w-md sm:p-6 md:min-w-[21vw] md:max-w-[25vw] md:p-8">
            <!-- Close Button -->
            <button id="close-delete-form-btn"
                class="absolute right-4 top-4 text-[#575757] hover:cursor-pointer hover:text-red-500" aria-label="Close">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                    stroke="currentColor" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>

            <!-- Header -->
            <div class="text-center">
                <div class="mt-4 flex justify-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="#575757" class="h-14 w-14 rotate-[7.5deg] sm:h-16 sm:w-16 md:h-20 md:w-20">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6 10v10a2 2 0 002 2h8a2 2 0 002-2V10M9 10v8m6-8v8M4 6h16M10 6V4a1 1 0 011-1h2a1 1 0 011 1v2" />
                    </svg>
                </div>
                <div class="mt-6 text-center text-base font-semibold sm:mt-7 sm:text-lg md:mt-8 md:text-xl">
                    <span class="text-[#575757]">Do you want to</span>
                    <span class="text-[#ED2828]"> delete </span>
                    <span class="text-[#575757]">this submission?</span>
                </div>
                <div class="mt-6 text-center text-sm font-light sm:mt-7 sm:text-base md:mt-8 md:text-base">
                    <span class="text-[#575757]">This action cannot be undone.</span>
                </div>
            </div>

            <div
                class="mt-8 flex flex-col space-y-4 sm:flex-row sm:justify-center sm:space-x-4 sm:space-y-0 md:mt-10 md:space-x-6">
                <button id="cancel-delete-form-btn"
                    class="w-full cursor-pointer rounded-full border border-[#575757] px-6 py-2 text-[#575757] hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-[#575757] sm:w-auto">
                    Cancel
                </button>
                <button id="confirm-delete-form-btn"
                    class="w-full cursor-pointer rounded-full bg-gradient-to-r from-[#FF5656] to-[#DF0606] px-6 py-2 text-white hover:brightness-110 focus:outline-none focus:ring-2 focus:ring-red-300 sm:w-auto">
                    Delete
                </button>
            </div>
        </div>
    </div>

    <div id="faculty-dashboard-wrapper" class="flex w-full flex-grow justify-center">
        <div id="faculty-dashboard-container" class="mx-auto w-full max-w-screen-xl">
            <div id="faculty-dashboard"
                class="pb-15 flex w-full max-w-screen-xl flex-col gap-6 p-4 pt-10 md:flex-row md:items-stretch">

                <!-- Left Side: Pending Submissions -->
                <div class="flex h-full w-full flex-col space-y-2 md:w-1/2 md:flex-1">
                    <div class="flex flex-col space-y-3 sm:flex-row sm:items-center sm:justify-between sm:space-y-0">
                        <span class="text-2xl font-semibold text-[#575757] sm:text-3xl">Form Submissions</span>
                        <div class="flex flex-col space-y-2 sm:flex-row sm:space-x-2 sm:space-y-0">
                            <button id="faculty-add-submission-btn"
                                class="rounded bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-4 py-2 text-sm font-semibold text-[#fdfdfd] shadow hover:cursor-pointer hover:brightness-110 sm:px-2 sm:py-1">Submit</button>
                            <button id="faculty-history-btn"
                                class="rounded bg-gradient-to-r from-[#FFC360] to-[#FFA104] px-4 py-2 text-sm font-semibold text-[#fdfdfd] shadow hover:cursor-pointer hover:brightness-110 sm:px-2 sm:py-1">Track</button>
                        </div>
                    </div>

                    <!-- Make this a vertical flex container -->
                    <div
                        class="border-1 flex h-full min-h-[832px] flex-1 flex-col overflow-hidden rounded-lg border-[#a1a1a1] p-4 sm:p-6 md:h-full">

                        <!-- Submission content can grow -->
                        <div id="submission-content" class="flex-1 space-y-3.5">
                            <!-- JavaScript will inject content here -->
                        </div>

                        <!-- Dot Pagination anchored to the bottom -->
                        <div id="pagination-dots" class="mt-auto flex justify-center space-x-2 pt-6">
                            <!-- Dots inserted by JS -->
                        </div>
                    </div>
                </div>

                <!-- Right Side: Personal Info + Change Password -->
                <div class="flex h-full w-full flex-col gap-4 md:w-1/2 md:flex-1">

                    <!-- Personal Information -->
                    <div class="flex flex-col space-y-2">
                        <div class="flex items-center justify-between">
                            <span class="text-3xl font-semibold text-[#575757]">Personal information</span>
                            <div class="space-x-2">
                                <button id="edit-faculty-btn"
                                    class="cursor-pointer rounded bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-2 py-1 text-sm font-semibold text-[#fdfdfd] shadow hover:brightness-110">Edit</button>
                            </div>
                        </div>
                        <div class="border-1 rounded-lg border-[#a1a1a1] p-6">
                            <div class="flex-col">
                                <span class="font-light text-[#8a8a8a]">Name</span><br>
                                <span class="inline-block max-w-full break-words text-2xl font-bold text-[#575757]">
                                    {{ strtoupper(auth()->user()->decrypted_last_name) }},
                                    {{ auth()->user()->decrypted_first_name }}
                                </span>
                            </div>
                            <div class="h-3"></div>
                            <div class="flex-col">
                                <span class="font-light text-[#8a8a8a]">Email address</span><br>
                                <span
                                    class="inline-block max-w-full break-all text-2xl font-bold text-[#575757] sm:break-words">{{ auth()->user()->email }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Change Password -->
                    <div class="border-1 rounded-lg border-[#a1a1a1] p-6">

                        <div>
                            <span class="text-2xl font-bold text-[#575757]">Change
                                password</span>
                        </div>
                        @if (session('show_password_reminder'))
                            <div
                                class="my-3 flex items-center gap-2 rounded bg-yellow-100 px-4 py-3 text-sm text-yellow-800">
                                <svg class="h-5 w-5 text-yellow-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                                For your security, please change your password.
                            </div>
                        @else
                            <div class="my-3 flex items-center gap-2 rounded bg-green-100 px-4 py-3 text-sm text-green-800">
                                <svg class="h-5 w-5 text-green-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M12 20a8 8 0 100-16 8 8 0 000 16z" />
                                </svg>
                                Security tip: Consider updating your password every few months.
                            </div>
                        @endif
                        <div class="h-3"></div>
                        <form id="password-change-form" method="POST" action="{{ route('password.update') }}">
                            @csrf
                            @method('PUT')

                            <div class="flex flex-col gap-6 px-6 md:flex-row">
                                <!-- Left: Inputs -->
                                <div id="input-fields" class="flex w-full flex-col space-y-4 md:w-1/2">
                                    <input id="current-password" name="current_password" type="password"
                                        placeholder="Current password" required
                                        class="h-[50px] rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none" />
                                    <div id="current-password-error" class="hidden text-sm text-red-500"></div>

                                    <input id="new-password" name="new_password" type="password"
                                        placeholder="New password" required
                                        class="h-[50px] rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none" />
                                    <div id="new-password-error" class="hidden text-sm text-red-500"></div>

                                    <input id="confirm-password" name="new_password_confirmation" type="password"
                                        placeholder="Confirm password" required
                                        class=":border-[#D56C6C] h-[50px] rounded-[10px] border border-[#575757] px-4 font-light text-[#575757] placeholder-[#575757] transition-colors duration-200 focus:outline-none" />
                                    <div id="confirm-password-error" class="hidden text-sm text-red-500"></div>

                                    <label
                                        class="flex items-center justify-end space-x-2 text-sm font-light text-[#575757]">
                                        <input type="checkbox" id="show-password-toggle"
                                            class="h-4 w-4 accent-[#575757] hover:cursor-pointer" />
                                        <span class="hover:cursor-pointer">Show password</span>
                                    </label>
                                </div>

                                <!-- Right: Requirements -->
                                <div id="requirements" class="flex w-full flex-col space-y-4 rounded-lg pl-7 md:w-1/2">
                                    <span class="text-sm font-semibold text-[#575757]">New password must contain the
                                        following:</span>
                                    <div id="password-requirements"
                                        class="ml-4 space-y-2 text-sm font-light text-[#575757]">
                                        <div class="flex items-center space-x-2">
                                            <div id="circle-length" class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <span>Minimum of 8 characters</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div id="circle-uppercase" class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <span>An uppercase letter</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div id="circle-lowercase" class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <span>A lowercase letter</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div id="circle-number" class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <span>A number</span>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            <div id="circle-special" class="h-3 w-3 rounded-full bg-gray-300"></div>
                                            <span>A special character</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="mt-5 flex justify-center">
                                <button id="faculty-submit-btn" type="submit"
                                    class="w-full max-w-xs rounded-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-6 py-3 font-semibold text-[#fdfdfd] transition duration-200 hover:cursor-pointer hover:brightness-110">
                                    Change password
                                </button>
                            </div>
                        </form>
                    </div>

                    <!-- Downloads Section -->
                    <div class="border-1 rounded-lg border-[#a1a1a1] p-6">
                        <div class="flex items-center justify-between">
                            <span class="text-2xl font-bold text-[#575757]">Downloads</span>
                            <div class="space-x-2">
                                <a href="{{ route('downloads') }}"
                                    class="rounded bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-4 py-2 text-sm font-semibold text-[#fdfdfd] shadow transition duration-200 hover:brightness-110">
                                    <svg class="mr-1 inline h-4 w-4" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Browse Forms
                                </a>
                            </div>
                        </div>
                        <div class="mt-4">
                            <p class="mb-3 text-sm text-[#8a8a8a]">
                                Access downloadable resources, templates, and documents for your submissions.
                            </p>
                            <div class="space-y-2">
                                <div class="flex items-center text-sm text-[#575757]">
                                    <svg class="mr-2 h-4 w-4 text-[#9D3E3E]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    R&DD Forms, Templates, and References
                                </div>
                                <div class="flex items-center text-sm text-[#575757]">
                                    <svg class="mr-2 h-4 w-4 text-[#9D3E3E]" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    R&DD MOA Forms, Samples, and References
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div id="faculty-history-container"
        class="mx-auto hidden w-full max-w-screen-2xl flex-grow bg-[#fdfdfd] px-4 sm:px-0">
        <!-- faculty history table -->
        <main id="faculty-history-table"
            class="flex w-full flex-col px-4 pt-6 transition-all duration-300 ease-in-out sm:px-0 sm:pt-10">
            <div class="mb-4 flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div class="w-full">
                    <div class="mb-4 flex w-full items-center justify-between">
                        <h1 class="text-xl font-bold text-[#575757] sm:text-2xl">Form Submission Tracker</h1>
                        <button id="faculty-back-btn"
                            class="rounded bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E] px-4 py-2 text-sm font-semibold text-[#fdfdfd] shadow hover:cursor-pointer hover:brightness-110 sm:px-2 sm:py-1">Back</button>
                    </div>

                    <!-- Responsive Actions Wrapper (mirrors inventory page layout) -->
                    <div class="flex flex-col gap-2 sm:flex-row sm:justify-between sm:gap-4">
                        <input type="text" id="faculty-history-search" name="faculty-history-search"
                            placeholder="Search..."
                            class="w-full rounded-lg border border-gray-300 bg-white px-4 py-2 text-[#575757] placeholder-gray-400 focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-[300px] md:w-[400px]" />
                        <div class="flex flex-wrap justify-end gap-2 sm:gap-4">
                            <div class="relative">
                                <select id="faculty-history-status" name="faculty-history-status"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                    <option value="all">All Submissions</option>
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
                            <div class="relative">
                                <select id="faculty-history-form-type" name="faculty-history-form-type"
                                    class="w-full appearance-none rounded-lg border border-gray-300 bg-white px-4 py-2 pr-10 text-[#575757] hover:cursor-pointer focus:outline-none focus:ring focus:ring-[#FFA104] sm:w-auto">
                                    <option value="all">All Form Types</option>
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

            <div class="flex-grow overflow-x-auto rounded-lg bg-[#fdfdfd] p-2 shadow sm:p-4">
                <table class="w-full divide-y divide-gray-200">
                    <thead class="bg-[#fdfdfd]">
                        <tr>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Form Type
                            </th>
                            <th class="cursor-pointer whitespace-normal break-words px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Note
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Attachment
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Date Submitted
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Date Reviewed
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Status
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Forwarded To
                            </th>
                            <th class="cursor-pointer px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500"
                                data-column="0" data-order="asc" onclick="sortTable(this)">
                                Review Remarks
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium uppercase tracking-wider text-gray-500">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody id="submission-table-body" class="bg-[#fdfdfd]] divide-y divide-gray-200 text-[#575757]">
                    </tbody>
                </table>
            </div>

            <div id="pagination-controls-logs" class="my-4 flex justify-end space-x-2">
                <button onclick="changePage('logs', -1)"
                    class="rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&lt;</button>
                <span id="pagination-info-logs" class="px-3 py-1 text-[#575757]">Page 1</span>
                <button onclick="changePage('logs', 1)"
                    class="rounded bg-gray-300 px-3 py-1 hover:bg-gray-400">&gt;</button>
            </div>

        </main>
    </div>
    <x-layout-partials.footer />
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Dashboard/History Toggle
            const historyBtn = document.getElementById('faculty-history-btn');
            const backBtn = document.getElementById('faculty-back-btn');
            const facultyDashboard = document.getElementById('faculty-dashboard-wrapper');
            const facultyHistory = document.getElementById('faculty-history-container');

            historyBtn.addEventListener('click', () => {
                facultyDashboard.classList.add('hidden');
                facultyHistory.classList.remove('hidden');
                fetchSubmissionHistory();
            });

            backBtn.addEventListener('click', () => {
                facultyHistory.classList.add('hidden');
                facultyDashboard.classList.remove('hidden');
            });

            // Add Submission Popup
            const addSubmissionBtn = document.getElementById('faculty-add-submission-btn');
            const addSubmissionPopup = document.getElementById('faculty-add-submission-popup');
            addSubmissionBtn.addEventListener('click', () => {
                addSubmissionPopup.style.display = 'flex';
            });

            // Password Change Functionality
            const passwordForm = document.getElementById('password-change-form');
            const currentPassword = document.getElementById('current-password');
            const newPassword = document.getElementById('new-password');
            const confirmPassword = document.getElementById('confirm-password');
            const showPasswordToggle = document.getElementById('show-password-toggle');
            const submitBtn = document.getElementById('faculty-submit-btn');

            // Password requirement circles
            const lengthCircle = document.getElementById('circle-length');
            const upperCircle = document.getElementById('circle-uppercase');
            const lowerCircle = document.getElementById('circle-lowercase');
            const numberCircle = document.getElementById('circle-number');
            const specialCircle = document.getElementById('circle-special');

            // Error divs
            const currentError = document.getElementById('current-password-error');
            const newError = document.getElementById('new-password-error');
            const confirmError = document.getElementById('confirm-password-error');

            // Toggle password visibility for all password fields
            showPasswordToggle.addEventListener('change', function() {
                const type = this.checked ? 'text' : 'password';
                currentPassword.type = type;
                newPassword.type = type;
                confirmPassword.type = type;
            });

            // Validate password in real-time
            newPassword.addEventListener('input', function() {
                const password = this.value;

                // Check requirements
                const hasLength = password.length >= 8;
                const hasUpper = /[A-Z]/.test(password);
                const hasLower = /[a-z]/.test(password);
                const hasNumber = /[0-9]/.test(password);
                const hasSpecial = /[^A-Za-z0-9]/.test(password);

                // Update circles
                updateCircle(lengthCircle, hasLength);
                updateCircle(upperCircle, hasUpper);
                updateCircle(lowerCircle, hasLower);
                updateCircle(numberCircle, hasNumber);
                updateCircle(specialCircle, hasSpecial);

                // Clear error when typing
                newError.classList.add('hidden');
            });

            // Confirm password match
            confirmPassword.addEventListener('input', function() {
                if (this.value && newPassword.value !== this.value) {
                    confirmError.textContent = 'Passwords do not match';
                    confirmError.classList.remove('hidden');
                } else {
                    confirmError.classList.add('hidden');
                }
            });

            // Form submission
            passwordForm.addEventListener('submit', async function(e) {
                e.preventDefault();

                // Reset errors
                currentError.classList.add('hidden');
                newError.classList.add('hidden');
                confirmError.classList.add('hidden');

                // Basic client-side validation
                if (!currentPassword.value) {
                    currentError.textContent = 'Current password is required';
                    currentError.classList.remove('hidden');
                    return;
                }

                if (!newPassword.value) {
                    newError.textContent = 'New password is required';
                    newError.classList.remove('hidden');
                    return;
                }

                if (newPassword.value !== confirmPassword.value) {
                    confirmError.textContent = 'Passwords do not match';
                    confirmError.classList.remove('hidden');
                    return;
                }

                // Disable button during submission
                submitBtn.disabled = true;
                submitBtn.innerHTML = 'Changing...';

                try {
                    const response = await fetch(passwordForm.action, {
                        method: 'PUT',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                .content,
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            current_password: currentPassword.value,
                            new_password: newPassword.value,
                            new_password_confirmation: confirmPassword.value
                        })
                    });

                    const data = await response.json();

                    if (!response.ok) {
                        throw data;
                    }

                    // Show success modal
                    document.getElementById('password-change-success-popup').style.display = 'flex';

                    const successButton = document.getElementById('success-passChange-btn');
                    if (successButton) {
                        successButton.addEventListener('click', () => {
                            document.getElementById('password-change-success-popup').style
                                .display = 'none';
                            location.reload();
                        }, {
                            once: true
                        });
                    }

                    // Reset form
                    passwordForm.reset();
                    resetCircles();

                } catch (error) {
                    // Show fail modal with error message
                    const failModal = document.getElementById('password-change-fail-popup');
                    const failMessage = document.getElementById('password-fail-message');

                    if (error.errors) {
                        if (error.errors.current_password) {
                            currentError.textContent = error.errors.current_password[0];
                            currentError.classList.remove('hidden');
                        }
                        if (error.errors.new_password) {
                            newError.textContent = error.errors.new_password[0];
                            newError.classList.remove('hidden');
                        }

                        failMessage.textContent = 'Password change failed.';
                    } else {
                        failMessage.textContent = error.message ||
                            'An error occurred while changing your password';
                    }

                    failModal.style.display = 'flex';
                } finally {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = 'Change password';
                }
            });

            function updateCircle(circle, isValid) {
                circle.style.backgroundColor = isValid ? '#10B981' : '#D1D5DB';
            }

            function resetCircles() {
                [lengthCircle, upperCircle, lowerCircle, numberCircle, specialCircle].forEach(circle => {
                    circle.style.backgroundColor = '#D1D5DB';
                });
            }

            // Form Submissions Display
            let submissions = [];
            let formPageIndex = 0;
            const formsPerPage = 3;

            async function fetchPendingSubmissions() {
                try {
                    const response = await fetch('/forms/pending');

                    if (!response.ok) {
                        throw new Error('Failed to fetch submissions');
                    }

                    submissions = await response.json();

                    if (submissions.length > 0) {
                        formPageIndex = 0;
                        renderSubmissionsList(formPageIndex);
                        renderPagination();
                    } else {
                        const content = document.getElementById('submission-content');
                        content.innerHTML = `
                            <div class=\"flex min-h-[758px] items-center justify-center py-8 h\">
                                <span class=\"text-lg text-gray-500\">No pending form submissions found</span>
                            </div>
                        `;
                        document.getElementById('pagination-dots').innerHTML = '';
                    }
                } catch (error) {
                    console.error('Error fetching submissions:', error);
                    document.getElementById('submission-content').innerHTML = `
                    <div class="flex h-full items-center justify-center">
                        <span class="text-lg text-red-500">Error loading submissions</span>
                    </div>
                `;
                }
            }

            async function fetchSubmissionHistory(page = 1, highlightId = null) {
                try {
                    const search = document.getElementById('faculty-history-search')?.value?.trim() || '';
                    const status = document.getElementById('faculty-history-status')?.value || 'all';
                    const formType = document.getElementById('faculty-history-form-type')?.value || 'all';
                    const params = new URLSearchParams({
                        page,
                        status,
                        form_type: formType
                    });
                    if (search) params.set('search', search);
                    if (highlightId) params.set('highlight_id', String(highlightId));
                    const response = await fetch(`/submissions/history?${params.toString()}`);
                    const data = await response.json();

                    if (!response.ok) {
                        throw new Error('Failed to fetch submission history');
                    }

                    // Update history table with data
                    const tbody = document.getElementById('submission-table-body');
                    tbody.innerHTML = '';

                    if (data.data.length === 0) {
                        tbody.innerHTML = `
                                            <tr>
                                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                    No submission history found
                                                </td>
                                            </tr>
                                          `;
                        return;
                    }

                    data.data.forEach(submission => {
                        const row = document.createElement('tr');
                        row.className = 'hover:bg-gray-50';
                        row.id = `history-row-${submission.id}`;

                        // Format dates
                        const submittedDate = submission.submitted_at ? new Date(submission
                            .submitted_at) : null;
                        const reviewedDate = submission.reviewed_at ? new Date(submission.reviewed_at) :
                            null;

                        // IDs for note/remarks toggle
                        const noteRowId = `note-row-${submission.id}`;
                        const noteToggleBtnId = `toggle-note-${submission.id}`;
                        const remarksRowId = `remarks-row-${submission.id}`;
                        const remarksBtnId = `toggle-remarks-${submission.id}`;

                        const attachmentHtml = submission.document_filename ?
                            `<a href="/forms/${submission.id}/view" target="_blank" rel="noopener" class="text-[#9D3E3E] hover:underline" title="${submission.document_filename}">${submission.document_filename}</a>` :
                            '<span class="text-gray-500">None</span>';

                        const statusMap = {
                            approved: 'bg-green-100 text-green-800',
                            accepted: 'bg-green-100 text-green-800',
                            rejected: 'bg-red-100 text-red-800',
                            forwarded: 'bg-blue-100 text-blue-800',
                            pending: 'bg-yellow-100 text-yellow-800',
                        };
                        const normalizedStatus = (submission.status || 'pending').toLowerCase();
                        const statusClass = statusMap[normalizedStatus] || statusMap.pending;

                        // Use the dedicated forwarded_to column
                        const forwardedTo = submission.forwarded_to || 'N/A';

                        // Use review_remarks as-is (no need to parse/clean)
                        const cleanedRemarks = submission.review_remarks;

                        row.innerHTML = `
                                            <td class="px-6 py-4 min-w-[180px] max-w-[220px]">${submission.form_type || '—'}</td>
                                            <td class="items-center px-4 py-2">
                                                <button type="button"
                                                        id="${noteToggleBtnId}"
                                                        class="flex items-center font-semibold text-sm text-[#9D3E3E] hover:underline cursor-pointer"
                                                        onclick="toggleAbstract('${noteRowId}', '${noteToggleBtnId}')">
                                                    View Note
                                                </button>
                                            </td>
                                            <td class="px-6 py-4 min-w-[160px] max-w-[260px] truncate">${attachmentHtml}</td>
                                            <td class="px-6 py-4 min-w-[120px] max-w-[150px]">${submittedDate ? submittedDate.toLocaleDateString() : 'N/A'}</td>
                                            <td class="px-6 py-4 min-w-[120px] max-w-[150px]">${reviewedDate ? reviewedDate.toLocaleDateString() : 'N/A'}</td>
                                            <td class="px-6 py-4 min-w-[150px] max-w-[180px]">
                                                <span class="inline-flex px-2 text-xs leading-5 font-semibold rounded-full capitalize ${statusClass}">
                                                    ${normalizedStatus}
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 min-w-[120px]">
                                                <span class="text-sm ${forwardedTo !== 'N/A' ? 'text-blue-600' : 'text-gray-500'}">
                                                    ${forwardedTo}
                                                </span>
                                            </td>
                                            <td class="items-center px-4 py-2">
                                                ${submission.review_remarks && submission.review_remarks.trim().length > 0
                                                    ? `<button type=\"button\"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            id=\"${remarksBtnId}\"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            class=\"flex items-center font-semibold text-sm text-[#9D3E3E] hover:underline cursor-pointer\"
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                            onclick=\"toggleRemarks('${remarksRowId}', '${remarksBtnId}')\">View Remarks</button>`
                                                    : '<span class=\"text-gray-500\">N/A</span>'
                                                }
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                ${normalizedStatus === 'rejected'
                                                    ? (submission.has_been_resubmitted
                                                        ? '<span class="text-gray-400 text-sm">Resubmitted</span>'
                                                        : `<button class="text-red-600 hover:underline hover:cursor-pointer faculty-resubmit-btn" data-id="${submission.id}">Resubmit</button>`
                                                    )
                                                    : '<span class="text-gray-500">—</span>'
                                                }
                                            </td>
                                        `;

                        tbody.appendChild(row);

                        // Note row (initially hidden)
                        const noteRow = document.createElement('tr');
                        noteRow.id = noteRowId;
                        noteRow.className = 'hidden';
                        noteRow.innerHTML = `
                                <td colspan="9" class="min-w-[20vw] max-w-[20vw] px-6 py-3 text-base text-gray-700 bg-gray-50">
                                    <div class="break-words overflow-wrap-break-word text-justify"> ${submission.note || 'No note'} </div>
                                </td>
                            `;
                        tbody.appendChild(noteRow);

                        // Remarks row (initially hidden)
                        if (submission.review_remarks && submission.review_remarks.trim().length > 0) {
                            const remarksRow = document.createElement('tr');
                            remarksRow.id = remarksRowId;
                            remarksRow.className = 'hidden';
                            remarksRow.innerHTML = `
                                    <td colspan="9" class="min-w-[20vw] max-w-[20vw] px-6 py-3 text-base text-gray-700 bg-gray-50">
                                        <div class="break-words overflow-wrap-break-word text-justify"> ${cleanedRemarks} </div>
                                    </td>
                                `;
                            tbody.appendChild(remarksRow);
                        }
                    });

                    // Update pagination controls
                    updatePaginationControls(data);

                    // Highlight if requested
                    if (highlightId) {
                        const targetRow = document.getElementById(`history-row-${highlightId}`);
                        if (targetRow) {
                            targetRow.classList.add('bg-orange-100', 'transition-colors');
                            targetRow.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            setTimeout(() => {
                                targetRow.classList.remove('bg-orange-100');
                            }, 2000);
                        }
                    }

                } catch (error) {
                    console.error('Error fetching submission history:', error);
                    document.getElementById('submission-table-body').innerHTML = `
                                                                                <tr>
                                                                                    <td colspan="8" class="px-6 py-4 text-center text-red-500">
                                                                                        Error loading submission history: ${error.message}
                                                                                    </td>
                                                                                </tr>
                                                                            `;
                }
            }

            // Debounced search like admin inventory
            const facultySearchInput = document.getElementById('faculty-history-search');
            const facultyStatusSelect = document.getElementById('faculty-history-status');
            const facultyFormTypeSelect = document.getElementById('faculty-history-form-type');
            let facultySearchDebounce;
            facultySearchInput?.addEventListener('input', () => {
                clearTimeout(facultySearchDebounce);
                facultySearchDebounce = setTimeout(() => {
                    fetchSubmissionHistory(1);
                }, 300);
            });
            facultyStatusSelect?.addEventListener('change', () => fetchSubmissionHistory(1));
            facultyFormTypeSelect?.addEventListener('change', () => fetchSubmissionHistory(1));

            function truncateAbstract(text, wordCount = 50) {
                const words = text.split(' ');
                if (words.length <= wordCount) return text;

                const truncated = words.slice(0, wordCount).join(' ');
                return `${truncated}...`;
            }

            function updatePaginationControls(data) {
                const paginationDiv = document.getElementById('pagination-controls-logs');
                paginationDiv.innerHTML = '';

                // Previous button
                const prevButton = document.createElement('button');
                prevButton.innerHTML = '&lt;';
                prevButton.className =
                    'px-2 py-1 mx-1 border rounded hover:bg-[#f0f0f0] hover:border-[#575757] hover:text-[#333] disabled:opacity-50';
                prevButton.disabled = data.current_page === 1;
                prevButton.onclick = () => {
                    if (data.current_page > 1) {
                        fetchSubmissionHistory(data.current_page - 1);
                    }
                };

                // Page info
                const pageInfo = document.createElement('span');
                pageInfo.className = 'px-3 py-1 text-[#575757]';
                pageInfo.textContent = `Page ${data.current_page} of ${data.last_page}`;
                pageInfo.id = 'pagination-info-logs';

                // Next button
                const nextButton = document.createElement('button');
                nextButton.innerHTML = '&gt;';
                nextButton.className =
                    'px-2 py-1 mx-1 border rounded hover:bg-[#f0f0f0] hover:border-[#575757] hover:text-[#333] disabled:opacity-50';
                nextButton.disabled = data.current_page === data.last_page;
                nextButton.onclick = () => {
                    if (data.current_page < data.last_page) {
                        fetchSubmissionHistory(data.current_page + 1);
                    }
                };

                paginationDiv.appendChild(prevButton);
                paginationDiv.appendChild(pageInfo);
                paginationDiv.appendChild(nextButton);
            }

            // Toggle abstract helper (mirrors admin behavior)
            window.toggleAbstract = function(rowId, btnId) {
                const row = document.getElementById(rowId);
                const btn = document.getElementById(btnId);
                if (!row || !btn) return;
                const isHidden = row.classList.contains('hidden');
                if (isHidden) {
                    row.classList.remove('hidden');
                    btn.textContent = 'Hide Abstract';
                } else {
                    row.classList.add('hidden');
                    btn.textContent = 'View Abstract';
                }
            };

            window.toggleRemarks = function(rowId, btnId) {
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
            };

            // Placeholders no longer needed when using full-height flex centering

            function renderPlaceholders(count) {
                if (!count || count <= 0) return '';
                const card = `
                    <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-300 flex flex-col invisible pointer-events-none select-none min-h-[170px]">
                        <div class="flex items-start justify-between">
                            <div>
                                <div class="text-xs font-light">&nbsp;</div>
                                <div class="text-lg font-bold">&nbsp;</div>
                            </div>
                        </div>
                        <div class="mt-2 flex-1 min-h-0">
                            <div class="text-xs font-light">&nbsp;</div>
                            <div class="text-sm font-medium">&nbsp;</div>
                        </div>
                        <div class="mt-2 flex items-center gap-3 w-full">
                            <span class="text-sm">&nbsp;</span>
                        </div>
                        <div class="mt-2 text-xs">&nbsp;</div>
                    </div>
                `;
                return new Array(count).fill(card).join('');
            }

            function getNoteWordLimit() {
                try {
                    if (window.matchMedia('(max-width: 640px)').matches) return 32; // mobile
                    if (window.matchMedia('(max-width: 768px)').matches) return 32; // small tablets
                } catch (e) {}
                return 40; // default for larger screens
            }

            function renderSubmissionsList(pageIndex = 0) {
                const content = document.getElementById('submission-content');
                // When there are no submissions, show a persistent centered empty state and exit
                if (!Array.isArray(submissions) || submissions.length === 0) {
                    content.innerHTML = `
                        <div class=\"flex min-h-[758px] items-center justify-center py-8\">
                            <span class=\"text-lg text-gray-500\">No pending form submissions found</span>
                        </div>`;
                    const dots = document.getElementById('pagination-dots');
                    if (dots) dots.innerHTML = '';
                    return;
                }
                const start = pageIndex * formsPerPage;
                const end = start + formsPerPage;
                const pageItems = submissions.slice(start, end);
                // Helper to truncate note with responsive limits
                const truncateWords = (text, limit = getNoteWordLimit()) => {
                    if (!text) return '—';
                    const words = text.split(/\s+/).filter(Boolean);
                    if (words.length <= limit) return text;
                    return words.slice(0, limit).join(' ') + '...';
                };

                const itemsHtml = pageItems.map(s => {
                    const submitted = s.submitted_at ? new Date(s.submitted_at).toLocaleDateString() : '';
                    const notePreview = (s.note || '—');
                    const snappedNote = truncateWords(notePreview);
                    const fileHtml = s.document_filename ?
                        `<span class=\"text-sm font-semibold text-[#9D3E3E] truncate max-w-full block\" title=\"${s.document_filename}\" style=\"white-space: nowrap; overflow: hidden; text-overflow: ellipsis;\">${s.document_filename}</span>` :
                        '<span class="text-sm text-gray-500">No attachment</span>';

                    return `
                        <div class="rounded-lg bg-gray-50 p-4 ring-1 ring-gray-300 hover:bg-gray-100 h-58 flex flex-col">
                            <div class="flex items-start justify-between">
                                <div>
                                    <div class="text-xs font-light text-[#8a8a8a]">Form Type</div>
                                    <div class="text-lg font-bold text-[#575757]">${s.form_type || '—'}</div>
                                </div>
                                <div class="flex items-center">
                                    <button class="track-form-btn p-1 rounded text-[#9D3E3E] hover:bg-red-50 hover:cursor-pointer" aria-label="Track" title="Track">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 10l4.553 2.276a1 1 0 010 1.788L15 16.34M4 6h16M4 10h6m-6 4h6" />
                                        </svg>
                                    </button>
                                    <span class="mx-2 text-[#a1a1a1]">|</span>
                                    <button data-id="${s.id}" class="delete-form-btn p-1 rounded text-[#9D3E3E] hover:bg-red-50 hover:cursor-pointer" aria-label="Delete" title="Delete">
                                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6M9 7h6m-7 0a2 2 0 002-2h2a2 2 0 002 2m-7 0H5m11 0h3" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div class="mt-2 flex-1 min-h-0">
                                <div class="text-xs font-light text-[#8a8a8a]">Note</div>
                                <div class="text-sm font-medium text-[#575757] break-words text-justify overflow-hidden">${snappedNote}</div>
                            </div>
                            <div class="mt-2 flex items-center gap-3 w-full">
                                ${fileHtml}
                                ${s.document_filename ? `<a href="/forms/${s.id}/view" target="_blank" rel="noopener" class="ml-auto text-[#9D3E3E] hover:underline flex items-center gap-1">
                                                                                                                                                                                                                                                            <svg class=\"h-5 w-5\" fill=\"none\" stroke=\"currentColor\" viewBox=\"0 0 24 24\">
                                                                                                                                                                                                                                                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M15 12a3 3 0 11-6 0 3 3 0 016 0z\" />
                                                                                                                                                                                                                                                                <path stroke-linecap=\"round\" stroke-linejoin=\"round\" stroke-width=\"2\" d=\"M2.458 12C3.732 7.943 7.523 5 12 5s8.268 2.943 9.542 7c-1.274 4.057-5.065 7-9.542 7s-8.268-2.943-9.542-7z\" />
                                                                                                                                                                                                                                                            </svg>
                                                                                                                                                                                                                                                            <span class=\"text-sm\">Preview</span>
                                                                                                                                                                                                                                                        </a>` : ''}
                            </div>
                            <div class="mt-2 text-xs text-gray-500">Submitted: ${submitted}</div>
                        </div>
                    `;
                }).join('');

                let html = itemsHtml;
                const missing = formsPerPage - pageItems.length;
                if (missing > 0) {
                    html += renderPlaceholders(missing);
                }
                content.innerHTML = html || `
                    <div class=\"flex min-h-[758px] items-center justify-center py-8\">
                        <span class=\"text-lg text-gray-500\">No pending form submissions found</span>
                    </div>`;

                // Attach delete handlers with modal confirmation
                const deleteModal = document.getElementById('faculty-delete-form-popup');
                const confirmDeleteBtn = document.getElementById('confirm-delete-form-btn');
                const cancelDeleteBtn = document.getElementById('cancel-delete-form-btn');
                let formToDeleteId = null;

                document.querySelectorAll('.delete-form-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        formToDeleteId = e.currentTarget.getAttribute('data-id');
                        if (!formToDeleteId) return;
                        deleteModal.style.display = 'flex';
                    });
                });

                const closeDeleteBtn = document.getElementById('close-delete-form-btn');

                cancelDeleteBtn?.addEventListener('click', () => {
                    deleteModal.style.display = 'none';
                    formToDeleteId = null;
                });

                closeDeleteBtn?.addEventListener('click', () => {
                    deleteModal.style.display = 'none';
                    formToDeleteId = null;
                });

                // Track action: switch to history view
                document.querySelectorAll('.track-form-btn').forEach(btn => {
                    btn.addEventListener('click', (e) => {
                        const facultyDashboard = document.getElementById(
                            'faculty-dashboard-wrapper');
                        const facultyHistory = document.getElementById('faculty-history-container');
                        facultyDashboard.classList.add('hidden');
                        facultyHistory.classList.remove('hidden');
                        // Find the ID for highlighting
                        const card = e.currentTarget.closest('div');
                        const id = card?.querySelector('.delete-form-btn')?.getAttribute('data-id');
                        fetchSubmissionHistory(1, id);
                    });
                });

                confirmDeleteBtn?.addEventListener('click', async () => {
                    if (!formToDeleteId) return;
                    try {
                        const res = await fetch(`/forms/${formToDeleteId}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector(
                                    'meta[name="csrf-token"]').content,
                                'Accept': 'application/json',
                            },
                        });

                        if (!res.ok) {
                            const data = await res.json().catch(() => ({}));
                            throw new Error(data.message || 'Failed to delete.');
                        }

                        deleteModal.style.display = 'none';
                        formToDeleteId = null;
                        await fetchPendingSubmissions();
                    } catch (err) {
                        deleteModal.style.display = 'none';
                        formToDeleteId = null;
                        alert(err.message || 'Error deleting submission');
                    }
                });
            }

            // Re-render cards on resize to apply responsive truncation
            let resizeDebounce;
            window.addEventListener('resize', () => {
                clearTimeout(resizeDebounce);
                resizeDebounce = setTimeout(() => {
                    renderSubmissionsList(formPageIndex);
                }, 150);
            });

            // Helper function to format file size
            function formatFileSize(bytes) {
                if (!bytes) return '0 Bytes';
                const k = 1024;
                const sizes = ['Bytes', 'KB', 'MB', 'GB'];
                const i = Math.floor(Math.log(bytes) / Math.log(k));
                return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
            }

            function renderPagination() {
                const dotsContainer = document.getElementById('pagination-dots');
                if (!dotsContainer) return;
                dotsContainer.innerHTML = '';

                const totalItems = submissions.length;
                const totalPages = Math.max(1, Math.ceil(totalItems / formsPerPage));

                // Hide pagination if no submissions
                if (!Array.isArray(submissions) || totalItems === 0) {
                    return;
                }

                const prevButton = document.createElement('button');
                prevButton.textContent = '<';
                prevButton.className =
                    'px-2 py-1 mx-1 border rounded hover:bg-[#f0f0f0] hover:border-[#575757] hover:text-[#333] hover:cursor-pointer disabled:opacity-50';
                prevButton.disabled = formPageIndex === 0;
                prevButton.onclick = () => {
                    if (formPageIndex > 0) {
                        formPageIndex--;
                        renderSubmissionsList(formPageIndex);
                        renderPagination();
                    }
                };

                const pageInfo = document.createElement('span');
                pageInfo.textContent = `${Math.min(formPageIndex + 1, totalPages)} of ${totalPages}`;
                pageInfo.className = 'mx-2 mt-1';

                const nextButton = document.createElement('button');
                nextButton.textContent = '>';
                nextButton.className =
                    'px-2 py-1 mx-1 border rounded hover:bg-[#f0f0f0] hover:border-[#575757] hover:text-[#333] hover:cursor-pointer disabled:opacity-50';
                nextButton.disabled = formPageIndex >= totalPages - 1;
                nextButton.onclick = () => {
                    if (formPageIndex < totalPages - 1) {
                        formPageIndex++;
                        renderSubmissionsList(formPageIndex);
                        renderPagination();
                    }
                };

                dotsContainer.appendChild(prevButton);
                dotsContainer.appendChild(pageInfo);
                dotsContainer.appendChild(nextButton);
            }

            // Resubmit button handler
            document.addEventListener('click', async function(e) {
                if (e.target.classList.contains('faculty-resubmit-btn')) {
                    const submissionId = e.target.dataset.id;

                    try {
                        // Fetch existing form submission data
                        const response = await fetch(`/forms/${submissionId}/resubmit-data`);
                        if (!response.ok) {
                            throw new Error('Failed to fetch form submission data');
                        }

                        const data = await response.json();

                        // Open the add submission popup
                        const addSubmissionPopup = document.getElementById(
                            'faculty-add-submission-popup');
                        if (addSubmissionPopup) {
                            addSubmissionPopup.style.display = 'flex';

                            // Pre-populate the form fields (excluding file)
                            const formTypeSelect = document.getElementById('fas-form-type');
                            const noteInput = document.getElementById('fas-note');

                            if (formTypeSelect) {
                                formTypeSelect.value = data.form_type || '';
                            }
                            if (noteInput) {
                                noteInput.value = data.note || '';

                                // Update word count
                                const noteWords = document.getElementById('fas-note-words');
                                if (noteWords) {
                                    const wordCount = (noteInput.value.trim().split(/\s+/).filter(
                                        Boolean)).length;
                                    noteWords.textContent = `${wordCount}/150 words`;
                                }
                            }

                            // Update form action to use resubmit endpoint
                            const form = document.getElementById('form-submission-form');
                            if (form) {
                                form.action = `/forms/${submissionId}/resubmit`;
                            }

                            // Show review remarks if available
                            if (data.review_remarks) {
                                // You can add a section to display previous review remarks if needed
                                console.log('Previous review remarks:', data.review_remarks);
                            }
                        }
                    } catch (error) {
                        console.error('Error fetching form submission data:', error);
                        alert('Failed to load form submission data for resubmission');
                    }
                }
            });

            // Initialize
            fetchPendingSubmissions();
        });
    </script>
@endsection
