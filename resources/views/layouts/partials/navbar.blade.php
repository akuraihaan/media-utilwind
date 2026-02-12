{{-- 
    PERUBAHAN UTAMA:
    1. Menambahkan tombol "Panel Admin" jika user login sebagai admin.
    2. Tombol ini muncul di Dropdown Profil (Desktop) & Menu Drawer (Mobile).
--}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-50 w-full h-20 bg-[#020617]/90 backdrop-blur-2xl border-b border-white/5 transition-all duration-300">
    
    <div class="w-full h-full px-6 md:px-10 lg:px-16">
        <div class="flex items-center justify-between h-full relative">
            
            {{-- 1. LOGO --}}
            <div class="flex items-center shrink-0 z-30">
                <a href="{{ url('/') }}" class="block group">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Logo Brand" 
                         class="h-14 md:h-[68px] w-auto object-contain transition-transform duration-300 group-hover:scale-105 drop-shadow-[0_0_15px_rgba(255,255,255,0.15)]">
                </a>
            </div>

            {{-- 2. NAVIGASI TENGAH (ABSOLUTE CENTER) --}}
            <div class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 items-center gap-8 text-sm font-medium z-20">
                
                @foreach(['Beranda' => route('landing'), 'Materi' => route('courses.curriculum'), 'Sandbox' => route('sandbox')] as $label => $link)
                    <a href="{{ $link }}" class="relative text-white/70 hover:text-white transition-colors duration-300 py-2 group font-semibold tracking-wide">
                        {{ $label }}
                        <span class="absolute bottom-0 left-0 w-0 h-[2px] bg-gradient-to-r from-fuchsia-500 to-cyan-500 transition-all duration-300 group-hover:w-full"></span>
                    </a>
                @endforeach

                {{-- MENU CHEAT SHEET --}}
                <a href="{{ route('cheatsheet.index') }}" class="relative flex items-center gap-2 text-white transition-colors duration-300 py-2 group font-bold tracking-wide">
                    <span class="text-fuchsia-400 group-hover:text-fuchsia-300 transition animate-pulse-slow">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </span>
                    Cheat Sheet
                    <span class="absolute -top-1.5 -right-6 text-[9px] bg-fuchsia-500 text-white px-1.5 py-0.5 rounded font-bold shadow-lg shadow-fuchsia-500/50">NEW</span>
                </a>

            </div>

            {{-- 3. KANAN: USER PROFILE & ACTIONS --}}
            <div class="flex items-center gap-5 shrink-0 z-30">
                
                {{-- DESKTOP: User Menu --}}
                <div class="hidden md:flex items-center gap-4">
                    @auth
                        <div class="relative" id="desktop-user-menu">
                            <button onclick="toggleUserMenu()" class="flex items-center gap-3 p-1.5 pr-4 rounded-full border border-white/10 bg-white/5 hover:bg-white/10 hover:border-white/20 transition-all duration-300 group focus:outline-none ring-1 ring-white/5">
                                {{-- Avatar --}}
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
                                
                                {{-- User Text --}}
                                <div class="text-left">
                                    <p class="text-[10px] text-white/50 font-bold uppercase tracking-wider leading-none mb-1">Akun</p>
                                    <p class="text-sm font-bold text-white leading-none group-hover:text-fuchsia-400 transition max-w-[100px] truncate">{{ Auth::user()->name }}</p>
                                </div>

                                <svg id="menu-chevron" class="w-4 h-4 text-white/40 group-hover:text-white transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                </svg>
                            </button>

                            {{-- Dropdown --}}
                            <div id="user-dropdown" class="hidden absolute right-0 mt-4 w-60 origin-top-right bg-[#0f141e] border border-white/10 rounded-2xl shadow-2xl shadow-black/80 overflow-hidden transform transition-all duration-200 opacity-0 scale-95 ring-1 ring-white/5">
                                <div class="px-5 py-4 border-b border-white/5 bg-gradient-to-r from-white/[0.02] to-transparent">
                                    <p class="text-xs text-white/50 uppercase font-bold tracking-wider mb-1">Signed in as</p>
                                    <p class="text-sm font-bold text-white truncate">{{ Auth::user()->email }}</p>
                                </div>
                                <div class="p-2 space-y-1">
                                    
                                    {{-- [NEW] PANEL ADMIN LINK (Hanya muncul jika admin) --}}
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/90 hover:text-white hover:bg-white/10 transition border border-transparent hover:border-white/5 bg-white/[0.02]">
                                            <span class="w-7 h-7 rounded-lg bg-indigo-500/20 flex items-center justify-center text-indigo-400 group-hover:bg-indigo-500/30 transition">⚡</span>
                                            <div class="flex flex-col">
                                                <span class="font-bold">Panel Admin</span>
                                                <span class="text-[10px] text-white/40 font-normal">Kelola Sistem</span>
                                            </div>
                                        </a>
                                    @endif

                                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/70 hover:text-white hover:bg-white/5 transition">
                                        <span class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center text-fuchsia-400 group-hover:bg-fuchsia-500/20 transition">⚙️</span>
                                        Edit Profil
                                    </a>

                                    @if(auth()->user()->role === 'student')
                                        <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-white/70 hover:text-white hover:bg-white/5 transition">
                                            <span class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center text-blue-400 group-hover:bg-blue-500/20 transition">📊</span>
                                            Dashboard Belajar
                                        </a>
                                    @endif
                                </div>
                                <div class="p-2 border-t border-white/5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full group flex items-center gap-3 px-4 py-3 rounded-xl text-sm text-red-400 hover:text-red-300 hover:bg-red-500/10 transition text-left">
                                            <span class="w-7 h-7 rounded-lg bg-red-500/10 flex items-center justify-center group-hover:bg-red-500/20 transition">🚪</span>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-4">
                            <a href="{{ route('login') }}" class="text-sm font-bold text-white/70 hover:text-white transition">Masuk</a>
                            <a href="{{ route('register') }}" class="px-6 py-2.5 rounded-xl bg-white text-[#020617] text-sm font-bold hover:bg-gray-200 transition transform hover:scale-105 shadow-[0_0_20px_rgba(255,255,255,0.2)]">
                                Mulai Belajar
                            </a>
                        </div>
                    @endauth
                </div>

                {{-- MOBILE HAMBURGER --}}
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="p-2.5 rounded-xl bg-white/5 border border-white/10 text-white hover:bg-white/10 transition focus:outline-none active:scale-95">
                        <svg id="hamburger-icon" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg id="close-icon" class="w-8 h-8 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- MOBILE MENU DRAWER --}}
    <div id="mobile-menu" class="hidden md:hidden absolute top-20 left-0 w-full bg-[#020617]/95 backdrop-blur-2xl border-b border-white/5 shadow-2xl transition-all duration-300 origin-top transform scale-y-95 opacity-0 h-screen overflow-y-auto">
        <div class="px-6 py-8 space-y-6 pb-40">
            
            {{-- User Info Mobile --}}
            @auth
                <div class="flex items-center gap-4 p-5 rounded-2xl bg-white/5 border border-white/5 relative overflow-hidden">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-16 h-16 rounded-full border-2 border-white/20 object-cover shadow-lg z-10">
                    @else
                        <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-600 to-fuchsia-600 flex items-center justify-center font-bold text-white text-2xl shadow-lg z-10">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="overflow-hidden z-10">
                        <p class="text-white font-black text-xl truncate">{{ Auth::user()->name }}</p>
                        <p class="text-sm text-white/50 truncate font-medium">{{ Auth::user()->email }}</p>
                        <div class="mt-3 flex gap-2">
                            <a href="{{ route('profile.edit') }}" class="text-[10px] font-bold px-4 py-1.5 rounded-full bg-fuchsia-500/20 text-fuchsia-300 border border-fuchsia-500/30">Edit Profil</a>
                        </div>
                    </div>
                </div>
            @endauth

            {{-- Mobile Links --}}
            <div class="space-y-3">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="block px-6 py-4 rounded-2xl bg-indigo-600/20 border border-indigo-500/40 text-indigo-300 font-bold text-lg transition flex justify-between items-center group mb-4">
                        ⚡ Panel Admin
                        <span class="text-indigo-400 group-hover:translate-x-1 transition">→</span>
                    </a>
                @endif

                @foreach(['Beranda' => route('landing'), 'Materi' => route('courses.curriculum'), 'Sandbox' => route('sandbox')] as $label => $link)
                    <a href="{{ $link }}" class="block px-6 py-4 rounded-2xl bg-white/[0.02] border border-white/5 hover:bg-white/10 hover:border-white/10 text-white font-bold text-lg transition flex justify-between items-center group">
                        {{ $label }}
                        <span class="text-white/20 group-hover:text-white group-hover:translate-x-1 transition">→</span>
                    </a>
                @endforeach
                
                <a href="{{ route('cheatsheet.index') }}" class="block px-6 py-4 rounded-2xl bg-gradient-to-r from-fuchsia-900/40 to-purple-900/40 border border-fuchsia-500/40 text-white font-black text-lg transition flex justify-between items-center group shadow-lg shadow-fuchsia-900/10">
                    <span class="flex items-center gap-3">
                        <span class="text-fuchsia-300 text-2xl">⚡</span> Cheat Sheet
                    </span>
                    <span class="text-xs bg-fuchsia-500 text-white px-3 py-1 rounded-full font-extrabold tracking-wider">NEW</span>
                </a>
            </div>

            {{-- Action Buttons --}}
            <div class="pt-6 border-t border-white/10">
                @auth
                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <button class="w-full py-4 rounded-2xl bg-red-500/10 border border-red-500/20 text-red-400 font-bold text-lg hover:bg-red-500/20 transition flex items-center justify-center gap-3">
                            Keluar Aplikasi
                        </button>
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
    function toggleUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
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
        menu.classList.remove('opacity-100', 'scale-100');
        menu.classList.add('opacity-0', 'scale-95');
        chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }
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
    document.addEventListener('click', function(event) {
        const desktopMenu = document.getElementById('desktop-user-menu');
        const dropdown = document.getElementById('user-dropdown');
        if (desktopMenu && !desktopMenu.contains(event.target) && !dropdown.classList.contains('hidden')) {
            closeUserMenu();
        }
    });
</script>