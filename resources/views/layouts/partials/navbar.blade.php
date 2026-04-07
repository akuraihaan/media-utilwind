{{-- NAVBAR COMPONENT (TALL & ELEGANT SAAS EDITION) --}}
<nav id="navbar" class="fixed top-0 left-0 right-0 z-[100] w-full h-[76px] md:h-[88px] bg-white/90 dark:bg-[#020617]/90 backdrop-blur-2xl border-b border-slate-200/80 dark:border-white/[0.05] transition-all duration-500">
    
    <div class="w-full h-full px-5 md:px-10 lg:px-16 max-w-[1440px] mx-auto">
        <div class="flex items-center justify-between h-full">
            
            {{-- 1. KIRI: LOGO BRAND (Ukuran Besar) --}}
            <div class="flex items-center shrink-0 h-full py-3">
                <a href="{{ url('/') }}" class="block h-full flex items-center focus:outline-none group">
                    <img src="{{ asset('images/logo.png') }}" 
                         alt="Utilwind Logo" 
                         {{-- Logo dikembalikan ke ukuran besar dan proporsional --}}
                         class="h-12 md:h-[60px] w-auto object-contain invert dark:invert-0 transition-transform duration-300 group-hover:scale-105"
                         onerror="this.style.display='none'">
                </a>
            </div>

            {{-- 2. TENGAH: NAVIGASI (Teks lebih besar dan berjarak) --}}
            <div class="hidden md:flex absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 items-center gap-8 z-20">
                @foreach(['Beranda' => route('landing'), 'Materi' => route('courses.curriculum'), 'Sandbox' => route('sandbox')] as $label => $link)
                    <a href="{{ $link }}" class="text-[14px] md:text-[15px] font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-300 tracking-wide focus:outline-none">
                        {{ $label }}
                    </a>
                @endforeach

                {{-- DROPDOWN RESOURCES --}}
                <div class="relative" id="resource-menu-container">
                    <button onclick="toggleResourceMenu()" class="flex items-center gap-1.5 text-[14px] md:text-[15px] font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors duration-300 tracking-wide focus:outline-none group">
                        Resources
                        <svg id="resource-chevron" class="w-4 h-4 text-slate-400 group-hover:text-slate-600 dark:group-hover:text-white transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>

                    {{-- DROPDOWN PANEL --}}
                    <div id="resource-dropdown" class="hidden absolute top-[calc(100%+18px)] left-1/2 -translate-x-1/2 w-[260px] bg-white dark:bg-[#0a0e17] border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-xl dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.8)] overflow-hidden transform transition-all duration-200 opacity-0 scale-95 origin-top p-1.5 z-50">
                        <a href="{{ route('cheatsheet.index') }}" class="flex items-start gap-3.5 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-colors focus:outline-none group">
                            <div class="mt-0.5 text-fuchsia-500 dark:text-fuchsia-400 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            </div>
                            <div>
                                <span class="block text-[14px] font-bold text-slate-900 dark:text-white leading-none mb-1.5">Cheat Sheet</span>
                                <span class="block text-[12px] text-slate-500 dark:text-slate-400 leading-snug">Kamus referensi utilitas CSS.</span>
                            </div>
                        </a>
                        <a href="{{ route('gallery.index') }}" class="flex items-start gap-3.5 p-3 rounded-xl hover:bg-slate-50 dark:hover:bg-white/5 transition-colors focus:outline-none group mt-1">
                            <div class="mt-0.5 text-cyan-500 dark:text-cyan-400 group-hover:scale-110 transition-transform">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            </div>
                            <div>
                                <span class="block text-[14px] font-bold text-slate-900 dark:text-white leading-none mb-1.5">UI Gallery</span>
                                <span class="block text-[12px] text-slate-500 dark:text-slate-400 leading-snug">Inspirasi komponen antarmuka.</span>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            {{-- 3. KANAN: THEME, AUTH & USER --}}
            <div class="flex items-center gap-2 md:gap-4 shrink-0 z-30">
                
                {{-- THEME SWITCHER (Lebih Besar) --}}
                <button class="theme-toggle-btn flex items-center justify-center w-10 h-10 rounded-full text-slate-400 hover:text-slate-800 dark:text-slate-500 dark:hover:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition-all focus:outline-none cursor-pointer">
                    <svg class="hidden dark:block w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <svg class="block dark:hidden w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/></svg>
                </button>

                {{-- VERTICAL DIVIDER --}}
                <div class="hidden md:block w-px h-6 bg-slate-200 dark:bg-white/10 mx-1"></div>

                {{-- DESKTOP AUTH / USER --}}
                <div class="hidden md:flex items-center">
                    @auth
                        <div class="relative" id="desktop-user-menu">
                            <button onclick="toggleUserMenu()" class="flex items-center gap-3 py-1.5 pl-1.5 pr-3.5 rounded-full hover:bg-slate-100 dark:hover:bg-white/10 transition-colors focus:outline-none group">
                                <div class="relative">
                                    @if(Auth::user()->avatar)
                                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-9 h-9 rounded-full object-cover border border-slate-200 dark:border-white/10">
                                    @else
                                        <div class="w-9 h-9 rounded-full bg-slate-900 dark:bg-white flex items-center justify-center text-xs font-bold text-white dark:text-slate-900">
                                            {{ substr(Auth::user()->name, 0, 2) }}
                                        </div>
                                    @endif
                                    <span class="absolute -bottom-0.5 -right-0.5 w-3 h-3 bg-emerald-500 border-2 border-white dark:border-[#020617] rounded-full"></span>
                                </div>
                                <div class="text-left">
                                    <p class="text-[14px] font-bold text-slate-800 dark:text-slate-200 truncate max-w-[120px]">{{ explode(' ', Auth::user()->name)[0] }}</p>
                                </div>
                                <svg id="menu-chevron" class="w-4 h-4 text-slate-400 transition-transform duration-200" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                            </button>

                            {{-- USER DROPDOWN --}}
                            <div id="user-dropdown" class="hidden absolute right-0 top-[calc(100%+16px)] w-64 bg-white dark:bg-[#0f141e] border border-slate-200/80 dark:border-white/10 rounded-2xl shadow-xl dark:shadow-[0_16px_40px_-8px_rgba(0,0,0,0.8)] overflow-hidden transform transition-all duration-200 opacity-0 scale-95 origin-top-right z-50">
                                <div class="px-5 py-4 border-b border-slate-100 dark:border-white/5 bg-slate-50/50 dark:bg-transparent">
                                    <p class="text-[14px] font-bold text-slate-900 dark:text-white truncate">{{ Auth::user()->name }}</p>
                                    <p class="text-[12px] font-medium text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                                </div>

                                <div class="p-2 space-y-1">
                                    @if(auth()->user()->role === 'admin')
                                        <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold text-slate-700 dark:text-slate-300 hover:text-indigo-600 dark:hover:text-indigo-400 hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                            <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                            Panel Admin
                                        </a>
                                    @endif
                                    
                                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                                        Dasbor Belajar
                                    </a>

                                    <a href="{{ route('profile.edit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold text-slate-700 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /></svg>
                                        Pengaturan Akun
                                    </a>
                                </div>
                                <div class="p-2 border-t border-slate-100 dark:border-white/5">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="w-full text-left flex items-center gap-3 px-3 py-2.5 rounded-xl text-[13px] font-semibold text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-500/10 transition-colors">
                                            <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                            Keluar
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="px-4 py-2 text-[14px] font-semibold text-slate-600 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white transition-colors focus:outline-none">Masuk</a>
                        <a href="{{ route('register') }}" class="ml-2 h-[42px] px-6 flex items-center justify-center bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[14px] font-bold rounded-full hover:scale-105 transition-transform duration-300 shadow-md focus:outline-none">Daftar</a>
                    @endauth
                </div>

                {{-- MOBILE HAMBURGER --}}
                <div class="md:hidden">
                    <button onclick="toggleMobileMenu()" class="flex items-center justify-center w-11 h-11 rounded-full text-slate-600 hover:bg-slate-100 dark:text-slate-300 dark:hover:bg-white/10 transition-colors focus:outline-none relative z-[110]">
                        <svg id="hamburger-icon" class="w-6 h-6 transition-transform duration-300 absolute" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16" /></svg>
                        <svg id="close-icon" class="w-6 h-6 transition-transform duration-300 absolute opacity-0 scale-50" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>
                </div>

            </div>
        </div>
    </div>

    {{-- ========================================== --}}
    {{-- MOBILE MENU DRAWER (FULLSCREEN) --}}
    {{-- ========================================== --}}
    <div id="mobile-menu" class="hidden md:hidden fixed inset-0 top-[76px] bg-white dark:bg-[#020617] transition-all duration-300 opacity-0 transform translate-y-4 h-[calc(100dvh-76px)] overflow-y-auto z-[90] border-t border-slate-200 dark:border-white/5">
        
        <div class="flex flex-col min-h-full px-5 py-8">
            
            @auth
                <div class="flex items-center gap-5 mb-8 p-5 rounded-[1.5rem] border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#0a0e17] shadow-sm">
                    @if(Auth::user()->avatar)
                        <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-16 h-16 rounded-full object-cover border border-slate-200 dark:border-white/10">
                    @else
                        <div class="w-16 h-16 rounded-full bg-slate-900 dark:bg-white flex items-center justify-center font-bold text-white dark:text-slate-900 text-xl">
                            {{ substr(Auth::user()->name, 0, 2) }}
                        </div>
                    @endif
                    <div class="flex-1 min-w-0">
                        <p class="text-slate-900 dark:text-white font-black text-xl truncate">{{ Auth::user()->name }}</p>
                        <p class="text-[13px] text-slate-500 dark:text-slate-400 truncate mt-0.5">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            @endauth

            <div class="flex flex-col gap-2 flex-1">
                @if(auth()->check() && auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="flex items-center justify-between p-4 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 font-bold text-[16px] transition-colors mb-4 border border-indigo-100 dark:border-indigo-500/20">
                        <div class="flex items-center gap-4">
                            <svg class="w-6 h-6 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                            Panel Admin
                        </div>
                        <span>→</span>
                    </a>
                @endif

                @foreach([
                    ['label' => 'Beranda', 'link' => route('landing')],
                    ['label' => 'Materi Belajar', 'link' => route('courses.curriculum')],
                    ['label' => 'Live Sandbox', 'link' => route('sandbox')]
                ] as $menu)
                    <a href="{{ $menu['link'] }}" class="flex items-center justify-between p-4 rounded-2xl text-slate-800 dark:text-slate-200 font-bold text-[18px] hover:bg-slate-50 dark:hover:bg-white/5 transition-colors border border-transparent dark:hover:border-white/5">
                        {{ $menu['label'] }}
                        <span class="text-slate-400 dark:text-slate-500">→</span>
                    </a>
                @endforeach

                <div class="mt-4 pt-6 border-t border-slate-200 dark:border-white/10">
                    <p class="text-[11px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-4 ml-4">Resources</p>
                    <div class="flex flex-col gap-2">
                        <a href="{{ route('cheatsheet.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-700 dark:text-slate-300 font-semibold text-[16px] hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-6 h-6 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                            Cheat Sheet
                        </a>
                        <a href="{{ route('gallery.index') }}" class="flex items-center gap-4 p-4 rounded-2xl text-slate-700 dark:text-slate-300 font-semibold text-[16px] hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                            <svg class="w-6 h-6 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" /></svg>
                            UI Gallery
                        </a>
                    </div>
                </div>
            </div>

            <div class="mt-10 pt-6 border-t border-slate-200 dark:border-white/10 pb-6">
                @auth
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('dashboard') }}" class="w-full py-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-[16px] text-center shadow-lg">Buka Dasbor Belajar</a>
                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <button class="w-full py-4 rounded-xl bg-red-50 dark:bg-red-500/10 border border-red-200 dark:border-red-500/20 text-red-600 dark:text-red-400 font-bold text-[16px] text-center transition-colors">Keluar Akun</button>
                        </form>
                    </div>
                @else
                    <div class="flex flex-col gap-3">
                        <a href="{{ route('register') }}" class="w-full py-4 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-center font-bold text-[16px] shadow-lg">Daftar Sekarang - Gratis</a>
                        <a href="{{ route('login') }}" class="w-full py-4 rounded-xl border border-slate-300 dark:border-white/20 text-slate-700 dark:text-white text-center font-bold text-[16px]">Masuk ke Akun</a>
                    </div>
                @endauth
            </div>

        </div>
    </div>
</nav>

{{-- SCRIPT PENGENDALI KHUSUS NAVBAR --}}
<script>
    // --- 1. LOGIKA THEME SWITCHER GLOBAL ---
    if (typeof window.themeSwitcherInitialized === 'undefined') {
        window.themeSwitcherInitialized = true;
        
        document.addEventListener('DOMContentLoaded', function() {
            const htmlEl = document.documentElement;
            const themeToggleBtns = document.querySelectorAll('.theme-toggle-btn');
            
            if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                htmlEl.classList.add('dark');
            } else {
                htmlEl.classList.remove('dark');
            }

            themeToggleBtns.forEach(btn => {
                btn.addEventListener('click', function(e) {
                    e.preventDefault(); 
                    htmlEl.classList.toggle('dark');
                    if (htmlEl.classList.contains('dark')) {
                        localStorage.setItem('color-theme', 'dark');
                    } else {
                        localStorage.setItem('color-theme', 'light');
                    }
                });
            });
        });
    }

    // --- 2. USER MENU DESKTOP ---
    function toggleUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
        closeResourceMenu();

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                if(chevron) chevron.classList.add('rotate-180');
            }, 10);
        } else { closeUserMenu(); }
    }
    
    function closeUserMenu() {
        const menu = document.getElementById('user-dropdown');
        const chevron = document.getElementById('menu-chevron');
        if(!menu) return;
        menu.classList.add('opacity-0', 'scale-95');
        if(chevron) chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }

    // --- 3. RESOURCE MENU DESKTOP ---
    function toggleResourceMenu() {
        const menu = document.getElementById('resource-dropdown');
        const chevron = document.getElementById('resource-chevron');
        closeUserMenu();

        if (menu.classList.contains('hidden')) {
            menu.classList.remove('hidden');
            setTimeout(() => {
                menu.classList.remove('opacity-0', 'scale-95');
                if(chevron) chevron.classList.add('rotate-180');
            }, 10);
        } else { closeResourceMenu(); }
    }
    
    function closeResourceMenu() {
        const menu = document.getElementById('resource-dropdown');
        const chevron = document.getElementById('resource-chevron');
        if(!menu) return;
        menu.classList.add('opacity-0', 'scale-95');
        if(chevron) chevron.classList.remove('rotate-180');
        setTimeout(() => { menu.classList.add('hidden'); }, 200);
    }

    // --- 4. MOBILE MENU DRAWER (APP-LIKE) ---
    function toggleMobileMenu() {
        const mobileMenu = document.getElementById('mobile-menu');
        const hamburger = document.getElementById('hamburger-icon');
        const close = document.getElementById('close-icon');
        const isHidden = mobileMenu.classList.contains('hidden');
        
        if (isHidden) {
            mobileMenu.classList.remove('hidden');
            
            // Animasi Icon
            hamburger.classList.add('opacity-0', 'rotate-90', 'scale-50');
            setTimeout(() => {
                hamburger.classList.add('hidden');
                close.classList.remove('hidden');
                setTimeout(() => {
                    close.classList.remove('opacity-0', 'scale-50', 'rotate-90');
                }, 10);
            }, 150);

            setTimeout(() => {
                mobileMenu.classList.remove('translate-y-4', 'opacity-0');
            }, 10);
            
            document.body.style.overflow = 'hidden'; 
        } else {
            mobileMenu.classList.add('translate-y-4', 'opacity-0');
            
            // Animasi Icon
            close.classList.add('opacity-0', 'scale-50', 'rotate-90');
            setTimeout(() => {
                close.classList.add('hidden');
                hamburger.classList.remove('hidden');
                setTimeout(() => {
                    hamburger.classList.remove('opacity-0', 'rotate-90', 'scale-50');
                }, 10);
            }, 150);

            setTimeout(() => {
                mobileMenu.classList.add('hidden');
            }, 300);
            
            document.body.style.overflow = '';
        }
    }

    // --- 5. KLIK DI LUAR UNTUK MENUTUP DROPDOWN ---
    document.addEventListener('click', function(event) {
        const desktopMenu = document.getElementById('desktop-user-menu');
        const userDropdown = document.getElementById('user-dropdown');
        if (desktopMenu && !desktopMenu.contains(event.target) && userDropdown && !userDropdown.classList.contains('hidden')) {
            closeUserMenu();
        }

        const resourceContainer = document.getElementById('resource-menu-container');
        const resourceDropdown = document.getElementById('resource-dropdown');
        if (resourceContainer && !resourceContainer.contains(event.target) && resourceDropdown && !resourceDropdown.classList.contains('hidden')) {
            closeResourceMenu();
        }
    });
</script>