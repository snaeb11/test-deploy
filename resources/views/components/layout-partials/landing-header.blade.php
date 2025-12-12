<!-- Top Gradient -->
<div class="h-1 w-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E]"></div>

<!-- Navbar -->
<nav class="z-50 border-b border-b-[#dddddd] bg-[#fdfdfd] shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 sm:px-6 lg:px-24">

        <!-- Left Side Links -->
        <div class="hidden space-x-5 md:flex">
            <a href="{{ route('home') }}"
                class="{{ Route::currentRouteName() === 'home' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} text-sm font-semibold">
                Home
            </a>
            <a href="https://sites.google.com/usep.edu.ph/ctet-ctsul-research-center/home" target="_blank"
                class="text-sm font-semibold hover:text-[#9D3E3E]">Announcements</a>
            @auth
                @if (Auth::user()->account_type !== 'student')
                    <a href="{{ route('downloads') }}"
                        class="{{ Route::currentRouteName() === 'downloads' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} text-sm font-semibold">
                        Downloadable Forms
                    </a>
                @endif
            @endauth
            @guest
                <a href="{{ route('user.dashboard') }}"
                    class="{{ Route::currentRouteName() === 'user.dashboard' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} text-sm font-semibold">
                    Submit Thesis
                </a>
            @endguest
            @auth
                @if (Auth::user()->account_type !== 'faculty')
                    <a href="{{ route('user.dashboard') }}"
                        class="{{ Route::currentRouteName() === 'user.dashboard' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} text-sm font-semibold">
                        Submit Thesis
                    </a>
                @endif
            @endauth

        </div>

        <!-- Mobile Hamburger -->
        <div class="flex md:hidden">
            <button id="mobile-menu-toggle"
                class="inline-flex items-center justify-center rounded-md p-2 text-[#575757] hover:text-[#9D3E3E] focus:outline-none">
                <!-- Icon: Hamburger -->
                <svg id="hamburger-icon" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
                <!-- Icon: Close -->
                <svg id="close-icon" class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none"
                    viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
        </div>

        <!-- Right Side -->
        <div class="hidden md:block">
            @guest
                <a href="{{ route('login') }}" class="text-sm font-semibold hover:text-[#9D3E3E]">
                    Login
                </a>
            @endguest

            @auth
                <div class="group relative flex items-center space-x-4">
                    <div class="flex cursor-pointer items-center">
                        <span class="text-sm font-semibold">
                            Welcome, {{ Auth::user()->decrypted_first_name }}
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="ml-1 h-4 w-4 text-[#575757] transition-colors group-hover:text-[#9D3E3E]" fill="none"
                            viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>

                    <!-- Dropdown Menu -->
                    <div
                        class="invisible absolute right-0 top-full z-50 mt-2 w-48 rounded-md bg-white py-1 opacity-0 shadow-lg transition-all duration-200 group-hover:visible group-hover:opacity-100">

                        <!-- Dashboard Option -->
                        @if (Auth::user()->hasPermission('view-dashboard'))
                            <a href="{{ route('admin.dashboard') }}"
                                class="block w-full px-4 py-2 text-left text-sm text-[#575757] hover:bg-[#fdfdfd] hover:text-[#9D3E3E]">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                                    </svg>
                                    Go to Dashboard
                                </div>
                            </a>
                        @elseif (Auth::user()->account_type === 'faculty')
                            <a href="{{ route('faculty.dashboard') }}"
                                class="block w-full px-4 py-2 text-left text-sm text-[#575757] hover:bg-[#fdfdfd] hover:text-[#9D3E3E]">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                                    </svg>
                                    Go to Dashboard
                                </div>
                            </a>
                        @else
                            <a href="{{ route('user.dashboard') }}"
                                class="block w-full px-4 py-2 text-left text-sm text-[#575757] hover:bg-[#fdfdfd] hover:text-[#9D3E3E]">
                                <div class="flex items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z" />
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M8 5a2 2 0 012-2h4a2 2 0 012 2v6H8V5z" />
                                    </svg>
                                    Go to Dashboard
                                </div>
                            </a>
                        @endif

                        <!-- Logout Option -->
                        <button onclick="document.getElementById('logout-popup').style.display = 'flex';"
                            class="block w-full cursor-pointer px-4 py-2 text-left text-sm text-[#575757] hover:bg-[#fdfdfd] hover:text-[#9D3E3E]">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15m3 0l3-3m0 0l-3-3m3 3H9" />
                                </svg>
                                Logout
                            </div>
                        </button>
                    </div>
                </div>
            @endauth
        </div>
    </div>

    <!-- Mobile Menu (hidden by default) -->
    <div id="mobile-menu" class="hidden px-4 pb-4 md:hidden">
        <div class="space-y-2">
            <a href="{{ route('home') }}" class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                Home
            </a>
            <a href="https://sites.google.com/usep.edu.ph/ctet-ctsul-research-center/home" target="_blank"
                class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">Announcements</a>
            @auth
                @if (Auth::user()->account_type !== 'student')
                    <a href="{{ route('downloads') }}"
                        class="{{ Route::currentRouteName() === 'downloads' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} text-sm font-semibold">
                        Downloadable Forms
                    </a>
                @endif
            @endauth
            @guest
                <a href="{{ route('user.dashboard') }}"
                    class="{{ Route::currentRouteName() === 'user.dashboard' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Submit Thesis
                </a>
            @endguest
            @auth
                @if (Auth::user()->account_type !== 'faculty')
                    <a href="{{ route('user.dashboard') }}"
                        class="{{ Route::currentRouteName() === 'user.dashboard' ? 'underline text-[#9D3E3E]' : 'hover:text-[#9D3E3E]' }} text-sm font-semibold">
                        Submit Thesis
                    </a>
                @endif
            @endauth

            @guest
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Login
                </a>
            @endguest

            @auth
                <a href="{{ Auth::user()->hasPermission('view-dashboard') ? route('admin.dashboard') : (Auth::user()->account_type === 'faculty' ? route('faculty.dashboard') : route('user.dashboard')) }}"
                    class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Go to Dashboard
                </a>
                <button onclick="document.getElementById('logout-popup').style.display = 'flex';"
                    class="block w-full text-left text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Logout
                </button>
            @endauth
        </div>
    </div>
</nav>

<!-- Toggle Script -->
<script>
    const toggleBtn = document.getElementById('mobile-menu-toggle');
    const mobileMenu = document.getElementById('mobile-menu');
    const hamburgerIcon = document.getElementById('hamburger-icon');
    const closeIcon = document.getElementById('close-icon');

    toggleBtn.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
        hamburgerIcon.classList.toggle('hidden');
        closeIcon.classList.toggle('hidden');
    });
</script>
