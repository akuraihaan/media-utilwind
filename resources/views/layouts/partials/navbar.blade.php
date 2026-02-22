<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 w-full h-20 bg-[#020617]/90 backdrop-blur-2xl border-b border-white/5 transition-all duration-300">
    
    <div class="w-full h-full px-6 md:px-10 lg:px-16">
        <div class="flex items-center justify-between h-full relative">
            
            {{-- 1. LOGO (DIMAKSIMALKAN) --}}
            <div class="flex items-center shrink-0 z-30">
                <a href="{{ url('/') }}" class="block group relative">
                    {{-- Efek Glow di belakang logo --}}
                    <div class="absolute -inset-2 bg-indigo-500/20 rounded-full blur-xl opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo Brand" 
                         {{-- Ukuran diperbesar: h-16 (mobile) dan md:h-[76px] (desktop) --}}
                         class="relative h-16 md:h-[76px] w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-[0_0_15px_rgba(255,255,255,0.15)]">
                </a>
            </div>

            {{-- 2. NAVIGASI TENGAH (DESKTOP) --}}
            <div class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 items-center gap-8 text-sm font-medium z-20">
                
                @foreach(['Beranda' => route('landing'), 'Materi' => route('courses.curriculum'), 'Sandbox' => route('sandbox')] as $label => $link)
                    <a href="{{ $link }}" class="relative text-white/70 hover:text-white transition-colors duration-300 py-2 group font-semibold tracking-wide">
                        {{ $label }}
                        <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-gradient-to-r from-fuchsia-500 to-cyan-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @endforeach

                {{-- DROPDOWN CHEAT SHEET & GALLERY (BARU) --}}
                <div class="relative" id="resource-menu-container">
                    <button onclick="toggleResourceMenu()" class="flex items-center gap-2 text-white/70 hover:text-white transition-colors duration-300 py-2 group font-semibold tracking-wide focus:outline-none">
                        <span class="text-fuchsia-400 group-hover:text-fuchsia-300 transition animate-pulse-slow">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                        </span>
                        Resources
                        <svg id="resource-chevron" class="w-3 h-3 text-white/40 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                        
                        {{-- Badge New --}}
                        <span class="absolute -top-2 -right-5 text-[8px] bg-fuchsia-500 text-white px-1.5 py-0.5 rounded font-bold shadow-lg shadow-fuchsia-500/50">NEW</span>
                    </button>

                    {{-- Dropdown Menu --}}
                    <div id="resource-dropdown" class="hidden absolute top-full left-1/2 -translate-x-1/2 mt-4 w-56 bg-[#0f141e] border border-white/10 rounded-2xl shadow-2xl shadow-black/80 overflow-hidden transform transition-all duration-200 opacity-0 scale-95 ring-1 ring-white/5 origin-top">
                        <div class="p-1.5 space-y-1">
                            {{-- Menu 1: Cheat Sheet --}}
                            <a href="{{ route('cheatsheet.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-white hover:bg-white/10 transition group">
                                <span class="w-8 h-8 rounded-lg bg-fuchsia-500/10 flex items-center justify-center text-fuchsia-400 group-hover:bg-fuchsia-500/20 transition border border-fuchsia-500/10">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                </span>
                                <div>
                                    <span class="block font-bold">Cheat Sheet</span>
                                    <span class="block text-[10px] text-white/40">Kumpulan Syntax Cepat</span>
                                </div>
                            </a>

                            {{-- Menu 2: UI Gallery --}}
                            <a href="{{ route('gallery.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm text-white/80 hover:text-white hover:bg-white/10 transition group">
                                <span class="w-8 h-8 rounded-lg bg-cyan-500/10 flex items-center justify-center text-cyan-400 group-hover:bg-cyan-500/20 transition border border-cyan-500/10">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                                </span>
                                <div>
                                    <span class="block font-bold">UI Gallery</span>
                                    <span class="block text-[10px] text-white/40">Inspirasi Komponen</span>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

            </div>

            {{-- 3. KANAN: USER PROFILE & ACTIONS --}}
            <div class="flex items-center gap-5 shrink-0 z-30">
                
                {{-- DESKTOP: User Menu --}}
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <div class="relative" id="desktop-user-menu">
                            <button onclick="toggleUserMenu()" class="flex items-center gap-3 p-1.5 pr-4 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-300 group focus:outline-none ring-1 ring-white/5">
                                <div class="relative">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-10 h-10 rounded-full border border-white/20 object-cover shadow-lg">
                                    @else
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-600 to-fuchsia-600 flex items-center justify-center text-xs font-bold text-white border border-white/20 shadow-lg">
                                            {{ substr(Auth::user()->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <span class="absolute bottom-0 right-0 w-2.5 h-2.5 bg-emerald-500 border-2 border-[#020617] rounded-full"></span>
                                </div>
                                <div class="text-left">
                                    <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider leading-none mb-1">Akun</p>
                                    <p class="text-sm font-bold text-white leading-none group-hover:text-fuchsia-400 transition max-w-[100px] truncate">{{ Auth::user()->name }}</p>
                                </div>
                                <svg id="menu-chevron" class="w-4 h-4 text-white/40 group-hover:text-white transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- User Dropdown --}}
                            <div id="user-dropdown" class="hidden absolute right-0 mt-4 w-60 origin-top-right bg-[#0f141e] border border-white/10 rounded-2xl shadow-2xl shadow-black/80 overflow-hidden transform transition-all duration-200 opacity-0 scale-95 ring-1 ring-white/5">
                                <div class="px-5 py-4 border-b border-white/5 bg-gradient-to-r from-white/[0.02] to-transparent">
                                    <p class="text-xs text-white/50 uppercase font-bold tracking-wider mb-1">Signed in as</p>
                                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="p-2 space-y-1">
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/90 hover:text-white hover:bg-white/10 transition border border-transparent hover:border-white/5 bg-white/[0.02]">
                                            <span class="w-7 h-7 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500/30 transition">⚡</span>
                                            <div class="flex flex-col"><span class="font-bold">Panel Admin</span><span class="text-[10px] text-white/40 font-normal">Kelola Sistem</span></div>
                                        </a>
                                    @endif
                                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/70 hover:text-white hover:bg-white/5 transition">
                                        <span class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center text-fuchsia-400 group-hover:bg-fuchsia-500/20 transition">⚙️</span> Edit Profil
                                    </a>
                                    @if(auth()->user()->role === 'student')
                                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/70 hover:text-white hover:bg-white/5 transition">
                                            <span class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center text-blue-400 group-hover:bg-blue-500/20 transition">📊</span> Dashboard Belajar
                                        </a>
                                    @endif
                                </div>
                                <div class="p-2 border-t border-white/5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition text-left">
                                            <span class="w-7 h-7 rounded-lg bg-red-500/10 flex items-center justify-center group-hover:bg-red-500/20 transition">🚪</span> Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-white/70 hover:text-white transition">Masuk</a>
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl bg-white text-[#020617] text-sm font-bold hover:bg-gray-200 transition transform hover:scale-105 shadow-[0_0_20px_rgba(255,255,255,0.2)]">Mulai Belajar</a>
                        </div>
                    @endauth
                </div>

                {{-- MOBILE HAMBURGER --}}
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition focus:outline-none active:scale-95">
                        <svg id="hamburger-icon" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg id="close-icon" class="w-8 h-8 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- MOBILE MENU DRAWER --}}
    <div id="mobile-menu" class="hidden md:hidden absolute top-20 left-0 w-full bg-[#020617]/95 backdrop-blur-2xl border-b border-white/5 shadow-2xl transition-all duration-300 origin-top transform scale-y-95 opacity-0 h-screen overflow-y-auto">
        <div class="px-6 py-8 space-y-6 pb-40">
            @auth
                <div class="flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-16 h-16 rounded-full border-2 border-white/20 object-cover shadow-lg z-10">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-600 to-fuchsia-600 flex items-center justify-center font-bold text-white text-2xl shadow-lg z-10">{{ substr(Auth::user()->name, 0, 2) }}</div>
                    @endif
                    <div class="overflow-hidden z-10">
                        <p class="text-white font-black text-xl truncate">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-white/50 truncate font-medium">{{ Auth::user()->email }}</p>
                        <div class="mt-3 flex gap-2"><a href="{{ route('profile.edit') }}" class="text-[10px] font-bold px-4 py-1.5 rounded-full bg-fuchsia-500/20 text-fuchsia-300 border border-fuchsia-500/30">Edit Profil</a></div>
                    </div>
                </div>
            @endauth

            <div class="space-y-3">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-4 rounded-2xl bg-indigo-600/20 border border-indigo-500/40 text-indigo-300 font-bold text-lg transition flex justify-between items-center group mb-4">
                        ⚡ Panel Admin <span class="text-indigo-400 group-hover:translate-x-1 transition">→</span>
                    </a>
                @endif

                @foreach(['Beranda' => route('landing'), 'Materi' => route('courses.curriculum'), 'Sandbox' => route('sandbox')] as $label => $link)
                    <a href="{{ $link }}" class="block px-6 py-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/10 hover:border-white/10 text-white font-bold text-lg transition flex justify-between items-center group">
                        {{ $label }} <span class="text-white/20 group-hover:text-white group-hover:translate-x-1 transition">→</span>
                    </a>
                @endforeach
                
                {{-- Mobile Resources Link --}}
                <div class="grid grid-cols-2 gap-3 mt-4">
                    <a href="{{ route('cheatsheet.index') }}" class="block px-4 py-4 rounded-2xl bg-white/[0.02] border border-white/5 text-white font-bold text-center hover:bg-white/10">
                        <span class="block text-fuchsia-400 mb-1">⚡</span> Cheat Sheet
                    </a>
                    <a href="{{ route('gallery.index') }}" class="block px-4 py-4 rounded-2xl bg-white/[0.02] border border-white/5 text-white font-bold text-center hover:bg-white/10">
                        <span class="block text-cyan-400 mb-1">🎨</span> UI Gallery
                    </a>
                </div>
            </div>

            <div class="pt-6 border-t border-white/10">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button class="w-full py-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 font-bold text-lg hover:bg-red-500/20 transition flex items-center justify-center gap-3">Keluar Aplikasi</button>
                    </form>
                @else
                    <div class="grid grid-cols-1 gap-4">
                        <a href="{{ route('register') }}" class="py-4 rounded-2xl bg-gradient-to-r from-fuchsia-600 to-purple-600 text-center text-white font-black text-lg shadow-xl">Daftar Sekarang 🚀</a>
                        <a href="{{ route('login') }}" class="py-4 rounded-2xl border-2 border-white/10 text-center text-white font-bold text-lg hover:bg-white/5 transition">Masuk Akun</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</nav>

{{-- SCRIPT PENGENDALI --}}
<script>
    // --- 1. USER MENU TOGGLE ---
    function toggleUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
        
        // Tutup menu lain jika ada
        closeResourceMenu();

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                chevron.classList.add('rotate-180');
            }, 10);
        } else {
            closeUserMenu();
        }
    }
    function closeUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
        if(!menu) return;
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }

    // --- 2. RESOURCE MENU TOGGLE (NEW) ---
    function toggleResourceMenu() {
        const menu = document.getElementById('resource-dropdown');
        const chevron = document.getElementById('resource-chevron');

        // Tutup menu user jika ada
        closeUserMenu();

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                menu.classList.add('opacity-100', 'scale-100');
                chevron.classList.add('rotate-180');
            }, 10);
        } else {
            closeResourceMenu();
        }
    }
    function closeResourceMenu() {
        const menu = document.getElementById('resource-dropdown');
        const chevron = document.getElementById('resource-chevron');
        if(!menu) return;
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }

    // --- 3. MOBILE MENU TOGGLE ---
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburger = document.getElementById('hamburger-icon');
        const close = document.getElementById('close-icon');
        if (mobileMenu.classList.contains('hidden')) {
            mobileMenu.classList.remove('hidden');
            hamburger.classList.add('hidden');
            close.classList.remove('hidden');
            setTimeout(() => {
                mobileMenu.classList.remove('scale-y-95', 'opacity-0');
                mobileMenu.classList.add('scale-y-100', 'opacity-100');
            }, 10);
        } else {
            mobileMenu.classList.remove('scale-y-100', 'opacity-100');
            mobileMenu.classList.add('scale-y-95', 'opacity-0');
            setTimeout(() => {
                mobileMenu.classList.add('hidden');
                hamburger.classList.remove('hidden');
                close.classList.add('hidden');
            }, 300);
        }
    }

    // --- 4. CLICK OUTSIDE HANDLER ---
    document.addEventListener('click', function(event) {
        // Handle User Dropdown
        const desktopMenu = document.getElementById('desktop-user-menu');
        const userDropdown = document.getElementById('user-dropdown');
        if (desktopMenu && !desktopMenu.contains(event.target) && userDropdown && !userDropdown.classList.contains('hidden')) {
            closeUserMenu();
        }

        // Handle Resource Dropdown
        const resourceContainer = document.getElementById('resource-menu-container');
        const resourceDropdown = document.getElementById('resource-dropdown');
        if (resourceContainer && !resourceContainer.contains(event.target) && resourceDropdown && !resourceDropdown.classList.contains('hidden')) {
            closeResourceMenu();
        }
    });
</script>