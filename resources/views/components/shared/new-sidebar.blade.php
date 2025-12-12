<!-- Toggle Button (optional for mobile) -->
<div class="flex w-[10%] items-center justify-between p-2 md:w-[15%]">
    <button data-drawer-target="mobile-sidebar" data-drawer-toggle="mobile-sidebar" aria-controls="mobile-sidebar"
        type="button"
        class="group ms-3 mt-2 inline-flex items-center rounded-lg from-[#D56C6C] to-[#C96262] p-2 text-sm text-gray-500 hover:bg-gradient-to-b focus:outline-none focus:ring-2 focus:ring-gray-200 md:hidden dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
        <span class="sr-only">Toggle sidebar</span>
        <svg class="h-6 w-6 fill-current text-gray-500 group-hover:text-[#fdfdfd]" aria-hidden="true" viewBox="0 0 20 20">
            <path clip-rule="evenodd" fill-rule="evenodd"
                d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z" />
        </svg>
    </button>
</div>

<!-- Sidebar -->
<div
    class="group fixed left-0 top-0 z-40 hidden h-screen w-16 bg-gradient-to-b from-[#D56C6C] to-[#C96262] transition-all duration-300 hover:w-64 md:block">
    <aside class="h-full px-3 py-4 text-white">
        <div
            class="group fixed left-0 top-0 z-40 h-screen w-16 bg-gradient-to-b from-[#D56C6C] to-[#C96262] transition-all duration-300 hover:w-64">
            <aside id="logo-sidebar" class="h-full px-3 py-4 text-white">
                <div class="flex items-center space-x-3 text-[#fffff0]">
                    <svg class="h-6 w-6 flex-shrink-0 text-[#fffff0]" fill="currentColor" viewBox="0 0 24 24">
                        <path
                            d="M12 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 12c-4.418 0-8 2.239-8 5v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1c0-2.761-3.582-5-8-5Z" />
                    </svg>
                    <div class="edit-admin flex space-x-2">
                        <div
                            class="invisible w-40 whitespace-nowrap opacity-0 transition-opacity delay-100 duration-300 group-hover:visible group-hover:opacity-100">
                            <a href="#"
                                class="username-admin block whitespace-normal break-words font-semibold hover:underline">User
                                Name</a>
                            <div class="text-sm font-light">Admin Title</div>
                        </div>
                        <button type="button"
                            class="edit-account-btn ml-2 cursor-pointer text-[#fffff0] hover:text-[#f0f0f0]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-4 w-4">
                                <path
                                    d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                                <path
                                    d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                            </svg>
                        </button>
                    </div>

                </div>

                <!-- Nav items -->
                <ul class="space-y-2 font-medium">
                    <li class="mt-10"></li>
                    @if (auth()->user()->hasPermission('view-thesis-submissions'))
                        <li>
                            <a href="#" id="thesis-submissions-tab"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path fill-rule="evenodd"
                                        d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM12 9.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5H12Zm-.75-2.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5H12a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5H6Zm-.75 3.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5H6a.75.75 0 0 1-.75-.75ZM6 6.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-3A.75.75 0 0 0 9 6.75H6Z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 0 1-3 0V6.75Z" />
                                </svg>

                                <span
                                    class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                    Thesis Submissions
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('view-forms-submissions'))
                        <li>
                            <a href="#" id="forms-submissions-tab"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path fill-rule="evenodd"
                                        d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM8.25 7.5A.75.75 0 0 1 9 6.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM8.25 10.5A.75.75 0 0 1 9 9.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM8.25 13.5A.75.75 0 0 1 9 12.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span
                                    class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                    Form Submissions
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('view-inventory'))
                        <li>
                            <a href="#" id="inventory-tab"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path
                                        d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 0 0-3-3h-3.879a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H6a3 3 0 0 0-3 3v3.162A3.756 3.756 0 0 1 4.094 9h15.812ZM4.094 10.5a2.25 2.25 0 0 0-2.227 2.568l.857 6A2.25 2.25 0 0 0 4.951 21H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-2.227-2.568H4.094Z" />
                                </svg>

                                <span
                                    class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                    Inventory
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('view-accounts'))
                        <li>
                            <a href="#" id="users-tab"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path
                                        d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                                </svg>

                                <span
                                    class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                    Users
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('view-logs'))
                        <li>
                            <a href="#" id="logs-tab"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path fill-rule="evenodd"
                                        d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z"
                                        clip-rule="evenodd" />
                                    <path fill-rule="evenodd"
                                        d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span
                                    class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                    Logs
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('view-backup'))
                        <li>
                            <a href="#" id="backup-tab"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path
                                        d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                    <path fill-rule="evenodd"
                                        d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087ZM12 10.5a.75.75 0 0 1 .75.75v4.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72v-4.94a.75.75 0 0 1 .75-.75Z"
                                        clip-rule="evenodd" />
                                </svg>

                                <span
                                    class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                    Backup
                                </span>
                            </a>
                        </li>
                    @endif

                    @if (auth()->user()->hasPermission('modify-programs-list') || auth()->user()->hasPermission('modify-advisers-list'))
                        <li class="pt-2">
                            <div
                                class="invisible ms-3 whitespace-nowrap text-xs uppercase tracking-wide text-white/80 opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                Data List Management
                            </div>
                        </li>
                        @if (auth()->user()->hasPermission('modify-programs-list'))
                            <li>
                                <a href="#" id="programs-list-tab"
                                    class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="h-5 w-5 shrink-0 text-white">
                                        <path fill-rule="evenodd"
                                            d="M4.5 6.75A2.25 2.25 0 0 1 6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v10.5A2.25 2.25 0 0 1 17.25 19.5H6.75A2.25 2.25 0 0 1 4.5 17.25V6.75Zm3 1.5A.75.75 0 0 0 6.75 9v6a.75.75 0 0 0 1.5 0V9A.75.75 0 0 0 7.5 8.25Zm3 0A.75.75 0 0 0 9.75 9v6a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0-.75-.75Zm3 0A.75.75 0 0 0 12.75 9v6a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0-.75-.75Zm3 0A.75.75 0 0 0 15.75 9v6a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0-.75-.75Z"
                                            clip-rule="evenodd" />
                                    </svg>
                                    <span
                                        class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">Programs</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('modify-advisers-list'))
                            <li>
                                <a href="#" id="advisers-list-tab"
                                    class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="h-5 w-5 shrink-0 text-white">
                                        <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0Z" />
                                        <path
                                            d="M1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122Z" />
                                    </svg>
                                    <span
                                        class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">Advisers</span>
                                </a>
                            </li>
                        @endif
                        @if (auth()->user()->hasPermission('modify-downloadable-forms'))
                            <li>
                                <a href="#" id="downloadable-forms-tab"
                                    class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                        class="h-5 w-5 shrink-0 text-white">
                                        <path fill-rule="evenodd"
                                            d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z"
                                            clip-rule="evenodd" />
                                        <path
                                            d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                    </svg>
                                    <span
                                        class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">Forms</span>
                                </a>
                            </li>
                        @endif
                    @endif

                    <li>
                        <a href="#"
                            class="logout-btn group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path fill-rule="evenodd"
                                    d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z"
                                    clip-rule="evenodd" />
                            </svg>

                            <span
                                class="invisible ms-3 whitespace-nowrap opacity-0 transition-opacity duration-300 group-hover:visible group-hover:opacity-100">
                                Logout
                            </span>
                        </a>
                    </li>

                </ul>
            </aside>
        </div>
    </aside>
</div>

<div id="mobile-sidebar"
    class="w-70 fixed left-0 top-0 z-50 h-screen -translate-x-full transform bg-gradient-to-b from-[#D56C6C] to-[#C96262] transition-transform duration-300 md:hidden"
    tabindex="-1" aria-label="Sidebar">
    <aside class="h-full px-3 py-4 text-white">
        <aside id="logo-sidebar" class="h-full px-3 py-4 text-white">
            <div class="flex items-center space-x-3 text-[#fffff0]">
                <svg class="h-6 w-6 flex-shrink-0 text-[#fffff0]" fill="currentColor" viewBox="0 0 24 24">
                    <path
                        d="M12 2a5 5 0 1 1 0 10 5 5 0 0 1 0-10Zm0 12c-4.418 0-8 2.239-8 5v1a1 1 0 0 0 1 1h14a1 1 0 0 0 1-1v-1c0-2.761-3.582-5-8-5Z" />
                </svg>

                <div class="edit-admin mr-7 flex space-x-1">
                    <div class="ms-3 w-40 whitespace-normal transition-opacity duration-300">
                        <a href="#"
                            class="username-admin block whitespace-normal break-words font-semibold hover:underline">User
                            Name</a>
                        <div class="text-sm font-light">Admin Title</div>
                    </div>
                    <button type="button"
                        class="edit-account-btn ml-2 cursor-pointer text-[#fffff0] hover:text-[#f0f0f0]">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-4 w-4">
                            <path
                                d="M21.731 2.269a2.625 2.625 0 0 0-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 0 0 0-3.712ZM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 0 0-1.32 2.214l-.8 2.685a.75.75 0 0 0 .933.933l2.685-.8a5.25 5.25 0 0 0 2.214-1.32l8.4-8.4Z" />
                            <path
                                d="M5.25 5.25a3 3 0 0 0-3 3v10.5a3 3 0 0 0 3 3h10.5a3 3 0 0 0 3-3V13.5a.75.75 0 0 0-1.5 0v5.25a1.5 1.5 0 0 1-1.5 1.5H5.25a1.5 1.5 0 0 1-1.5-1.5V8.25a1.5 1.5 0 0 1 1.5-1.5h5.25a.75.75 0 0 0 0-1.5H5.25Z" />
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Nav items -->
            <ul class="space-y-2 font-medium">
                <li class="mt-10"></li>
                @if (auth()->user()->hasPermission('view-thesis-submissions'))
                    <li>
                        <a href="#" id="thesis-submissions-tab-mobile"
                            class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path fill-rule="evenodd"
                                    d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM12 9.75a.75.75 0 0 0 0 1.5h1.5a.75.75 0 0 0 0-1.5H12Zm-.75-2.25a.75.75 0 0 1 .75-.75h1.5a.75.75 0 0 1 0 1.5H12a.75.75 0 0 1-.75-.75ZM6 12.75a.75.75 0 0 0 0 1.5h7.5a.75.75 0 0 0 0-1.5H6Zm-.75 3.75a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5H6a.75.75 0 0 1-.75-.75ZM6 6.75a.75.75 0 0 0-.75.75v3c0 .414.336.75.75.75h3a.75.75 0 0 0 .75-.75v-3A.75.75 0 0 0 9 6.75H6Z"
                                    clip-rule="evenodd" />
                                <path d="M18.75 6.75h1.875c.621 0 1.125.504 1.125 1.125V18a1.5 1.5 0 0 1-3 0V6.75Z" />
                            </svg>
                            <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                                Theses Submissions
                            </span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermission('view-forms-submissions'))
                    <li>
                        <a href="#" id="forms-submissions-tab-mobile"
                            class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path fill-rule="evenodd"
                                    d="M4.125 3C3.089 3 2.25 3.84 2.25 4.875V18a3 3 0 0 0 3 3h15a3 3 0 0 1-3-3V4.875C17.25 3.839 16.41 3 15.375 3H4.125ZM8.25 7.5A.75.75 0 0 1 9 6.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM8.25 10.5A.75.75 0 0 1 9 9.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM8.25 13.5A.75.75 0 0 1 9 12.75h6a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                                Forms Submissions
                            </span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermission('view-inventory'))
                    <li>
                        <a href="#" id="inventory-tab-mobile"
                            class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path
                                    d="M19.906 9c.382 0 .749.057 1.094.162V9a3 3 0 0 0-3-3h-3.879a.75.75 0 0 1-.53-.22L11.47 3.66A2.25 2.25 0 0 0 9.879 3H6a3 3 0 0 0-3 3v3.162A3.756 3.756 0 0 1 4.094 9h15.812ZM4.094 10.5a2.25 2.25 0 0 0-2.227 2.568l.857 6A2.25 2.25 0 0 0 4.951 21H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-2.227-2.568H4.094Z" />
                            </svg>
                            <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                                Inventory
                            </span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermission('view-accounts'))
                    <li>
                        <a href="#" id="users-tab-mobile"
                            class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path
                                    d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0ZM14.25 8.625a3.375 3.375 0 1 1 6.75 0 3.375 3.375 0 0 1-6.75 0ZM1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122ZM17.25 19.128l-.001.144a2.25 2.25 0 0 1-.233.96 10.088 10.088 0 0 0 5.06-1.01.75.75 0 0 0 .42-.643 4.875 4.875 0 0 0-6.957-4.611 8.586 8.586 0 0 1 1.71 5.157v.003Z" />
                            </svg>
                            <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                                Users
                            </span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermission('view-logs'))
                    <li>
                        <a href="#" id="logs-tab-mobile"
                            class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path fill-rule="evenodd"
                                    d="M7.502 6h7.128A3.375 3.375 0 0 1 18 9.375v9.375a3 3 0 0 0 3-3V6.108c0-1.505-1.125-2.811-2.664-2.94a48.972 48.972 0 0 0-.673-.05A3 3 0 0 0 15 1.5h-1.5a3 3 0 0 0-2.663 1.618c-.225.015-.45.032-.673.05C8.662 3.295 7.554 4.542 7.502 6ZM13.5 3A1.5 1.5 0 0 0 12 4.5h4.5A1.5 1.5 0 0 0 15 3h-1.5Z"
                                    clip-rule="evenodd" />
                                <path fill-rule="evenodd"
                                    d="M3 9.375C3 8.339 3.84 7.5 4.875 7.5h9.75c1.036 0 1.875.84 1.875 1.875v11.25c0 1.035-.84 1.875-1.875 1.875h-9.75A1.875 1.875 0 0 1 3 20.625V9.375ZM6 12a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V12Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 15a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V15Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75ZM6 18a.75.75 0 0 1 .75-.75h.008a.75.75 0 0 1 .75.75v.008a.75.75 0 0 1-.75.75H6.75a.75.75 0 0 1-.75-.75V18Zm2.25 0a.75.75 0 0 1 .75-.75h3.75a.75.75 0 0 1 0 1.5H9a.75.75 0 0 1-.75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                                Logs
                            </span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermission('view-backup'))
                    <li>
                        <a href="#" id="backup-tab-mobile"
                            class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                class="h-5 w-5 shrink-0 text-white">
                                <path
                                    d="M3.375 3C2.339 3 1.5 3.84 1.5 4.875v.75c0 1.036.84 1.875 1.875 1.875h17.25c1.035 0 1.875-.84 1.875-1.875v-.75C22.5 3.839 21.66 3 20.625 3H3.375Z" />
                                <path fill-rule="evenodd"
                                    d="m3.087 9 .54 9.176A3 3 0 0 0 6.62 21h10.757a3 3 0 0 0 2.995-2.824L20.913 9H3.087ZM12 10.5a.75.75 0 0 1 .75.75v4.94l1.72-1.72a.75.75 0 1 1 1.06 1.06l-3 3a.75.75 0 0 1-1.06 0l-3-3a.75.75 0 1 1 1.06-1.06l1.72 1.72v-4.94a.75.75 0 0 1 .75-.75Z"
                                    clip-rule="evenodd" />
                            </svg>
                            <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                                Backup
                            </span>
                        </a>
                    </li>
                @endif

                @if (auth()->user()->hasPermission('modify-programs-list') || auth()->user()->hasPermission('modify-advisers-list'))
                    <li class="pt-2">
                        <div class="ms-3 whitespace-nowrap text-xs uppercase tracking-wide text-white/80">
                            Data List Management
                        </div>
                    </li>
                    @if (auth()->user()->hasPermission('modify-programs-list'))
                        <li>
                            <a href="#" id="programs-list-tab-mobile"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path fill-rule="evenodd"
                                        d="M4.5 6.75A2.25 2.25 0 0 1 6.75 4.5h10.5A2.25 2.25 0 0 1 19.5 6.75v10.5A2.25 2.25 0 0 1 17.25 19.5H6.75A2.25 2.25 0 0 1 4.5 17.25V6.75Zm3 1.5A.75.75 0 0 0 6.75 9v6a.75.75 0 0 0 1.5 0V9A.75.75 0 0 0 7.5 8.25Zm3 0A.75.75 0 0 0 9.75 9v6a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0-.75-.75Zm3 0A.75.75 0 0 0 12.75 9v6a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0-.75-.75Zm3 0A.75.75 0 0 0 15.75 9v6a.75.75 0 0 0 1.5 0V9a.75.75 0 0 0-.75-.75Z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ms-3 whitespace-nowrap transition-opacity duration-300">Programs</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('modify-advisers-list'))
                        <li>
                            <a href="#" id="advisers-list-tab-mobile"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path d="M4.5 6.375a4.125 4.125 0 1 1 8.25 0 4.125 4.125 0 0 1-8.25 0Z" />
                                    <path
                                        d="M1.5 19.125a7.125 7.125 0 0 1 14.25 0v.003l-.001.119a.75.75 0 0 1-.363.63 13.067 13.067 0 0 1-6.761 1.873c-2.472 0-4.786-.684-6.76-1.873a.75.75 0 0 1-.364-.63l-.001-.122Z" />
                                </svg>
                                <span class="ms-3 whitespace-nowrap transition-opacity duration-300">Advisers</span>
                            </a>
                        </li>
                    @endif
                    @if (auth()->user()->hasPermission('modify-downloadable-forms'))
                        <li>
                            <a href="#" id="downloadable-forms-tab-mobile"
                                class="group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                                    class="h-5 w-5 shrink-0 text-white">
                                    <path fill-rule="evenodd"
                                        d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0 0 16.5 9h-1.875a1.875 1.875 0 0 1-1.875-1.875V5.25A3.75 3.75 0 0 0 9 1.5H5.625ZM7.5 15a.75.75 0 0 1 .75-.75h7.5a.75.75 0 0 1 0 1.5h-7.5A.75.75 0 0 1 7.5 15Zm.75 2.25a.75.75 0 0 0 0 1.5H12a.75.75 0 0 0 0-1.5H8.25Z"
                                        clip-rule="evenodd" />
                                    <path
                                        d="M12.971 1.816A5.23 5.23 0 0 1 14.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 0 1 3.434 1.279 9.768 9.768 0 0 0-6.963-6.963Z" />
                                </svg>
                                <span class="ms-3 whitespace-nowrap transition-opacity duration-300">Forms</span>
                            </a>
                        </li>
                    @endif
                @endif

                <li>
                    <a href="#"
                        class="logout-btn group flex items-center rounded-lg p-2 text-gray-900 hover:bg-[#e97b7b] dark:text-white dark:hover:bg-[#e97b7b]">

                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                            class="h-5 w-5 shrink-0 text-white">
                            <path fill-rule="evenodd"
                                d="M7.5 3.75A1.5 1.5 0 0 0 6 5.25v13.5a1.5 1.5 0 0 0 1.5 1.5h6a1.5 1.5 0 0 0 1.5-1.5V15a.75.75 0 0 1 1.5 0v3.75a3 3 0 0 1-3 3h-6a3 3 0 0 1-3-3V5.25a3 3 0 0 1 3-3h6a3 3 0 0 1 3 3V9A.75.75 0 0 1 15 9V5.25a1.5 1.5 0 0 0-1.5-1.5h-6Zm5.03 4.72a.75.75 0 0 1 0 1.06l-1.72 1.72h10.94a.75.75 0 0 1 0 1.5H10.81l1.72 1.72a.75.75 0 1 1-1.06 1.06l-3-3a.75.75 0 0 1 0-1.06l3-3a.75.75 0 0 1 1.06 0Z"
                                clip-rule="evenodd" />
                        </svg>
                        <span class="ms-3 whitespace-nowrap transition-opacity duration-300">
                            Logout
                        </span>
                    </a>
                </li>

            </ul>
        </aside>
    </aside>
</div>

<script src="https://unpkg.com/flowbite@1.6.5/dist/flowbite.min.js"></script>
