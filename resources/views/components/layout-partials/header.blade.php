<!-- Top Gradient -->
<div class="h-1 w-full bg-gradient-to-r from-[#D56C6C] to-[#9D3E3E]"></div>

<!-- Responsive Navbar -->
<nav class="z-50 border-b border-b-[#dddddd] bg-[#fdfdfd] shadow-sm">
    <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-2 sm:px-6 lg:px-24">

        <!-- Left Side (Logos + Text) -->
        <div class="flex items-center space-x-3">
            <!-- Circle Logos -->
            <a href="{{ url('/') }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-[#575757]">
                <img src="{{ asset('assets/usep-logo.png') }}" alt="USeP Logo"
                    class="h-full w-full rounded-full object-cover" />
            </a>

            <div class="h-5 w-px bg-[#dddddd]"></div>

            <a href="{{ url('/') }}" class="flex h-8 w-8 items-center justify-center rounded-full bg-[#575757]">
                <img src="{{ asset('assets/ctet-logo.png') }}" alt="CTET Logo"
                    class="h-full w-full rounded-full object-cover" />
            </a>

            <!-- Text -->
            <a href="{{ url('/') }}" class="flex-col sm:flex">
                <span class="hidden md:block text-[clamp(9px,1vw,12px)] font-semibold leading-tight text-[#575757]">
                    College of Teacher Education and Technology
                </span>
                <span class="hidden md:block text-[clamp(8px,0.9vw,11px)] font-normal leading-tight text-[#575757]">
                    Research Office
                </span>
                <span class="block md:hidden text-sm font-semibold text-[#575757]">CTET</span>
            </a>
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

        <!-- Right Side (Desktop) -->
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
                        <a href="{{ url('/') }}"
                            class="block w-full px-4 py-2 text-left text-sm text-[#575757] hover:bg-[#fdfdfd] hover:text-[#9D3E3E]">
                            <div class="flex items-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="mr-2 h-4 w-4" fill="none"
                                    viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l9-9 9 9" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 10v10h6v-6h4v6h6V10" />
                                </svg>
                                Go to Home
                            </div>
                        </a>

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

    <!-- Mobile Menu (Hidden by Default) -->
    <div id="mobile-menu" class="hidden px-4 pb-4 mt-3 md:hidden">
        <div class="space-y-2">
            @guest
                <a href="{{ url('/') }}" class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Home
                </a>
                <a href="{{ route('login') }}" class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Login
                </a>
            @endguest

            @auth
                <span class="block text-sm font-semibold text-[#575757]">
                    Welcome, {{ Auth::user()->decrypted_first_name }}
                </span>
                <a href="{{ url('/') }}" class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E]">
                    Go to Home
                </a>
                <button onclick="document.getElementById('logout-popup').style.display = 'flex';"
                    class="block text-sm font-semibold text-[#575757] hover:text-[#9D3E3E] hover:cursor-pointer">
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
