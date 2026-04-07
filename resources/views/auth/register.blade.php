@extends('layouts.auth')

@section('title', 'Daftar - Utilwind')

@section('content')

{{-- GOOGLE FONTS --}}
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500;600&family=Plus+Jakarta+Sans:ital,wght@0,400;0,500;0,600;0,700;0,800;1,400&display=swap" rel="stylesheet">

<style>
    body, .font-sans { font-family: 'Plus Jakarta Sans', sans-serif; }
    .font-mono { font-family: 'Fira Code', monospace; }

    /* PREMIUM AURORA MESH BACKGROUND */
    .aurora-wrapper {
        position: fixed; inset: 0; overflow: hidden; pointer-events: none;
        background-color: #f8fafc; transition: background-color 0.5s ease;
    }
    .dark .aurora-wrapper { background-color: #020617; }
    
    .aurora-bg {
        position: absolute; width: 150vw; height: 150vh; top: -25vh; left: -25vw;
        background-image: 
            radial-gradient(ellipse at 10% 20%, rgba(217, 70, 239, 0.15) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 10%, rgba(6, 182, 212, 0.15) 0%, transparent 50%),
            radial-gradient(ellipse at 40% 80%, rgba(99, 102, 241, 0.15) 0%, transparent 50%),
            radial-gradient(ellipse at 90% 90%, rgba(217, 70, 239, 0.1) 0%, transparent 50%);
        filter: blur(60px);
        animation: aurora-move 20s cubic-bezier(0.4, 0, 0.2, 1) infinite alternate;
        will-change: transform;
    }
    .dark .aurora-bg {
        background-image: 
            radial-gradient(ellipse at 10% 20%, rgba(217, 70, 239, 0.2) 0%, transparent 50%),
            radial-gradient(ellipse at 80% 10%, rgba(6, 182, 212, 0.2) 0%, transparent 50%),
            radial-gradient(ellipse at 40% 80%, rgba(99, 102, 241, 0.2) 0%, transparent 50%),
            radial-gradient(ellipse at 90% 90%, rgba(217, 70, 239, 0.15) 0%, transparent 50%);
    }
    
    @keyframes aurora-move {
        0% { transform: rotate(0deg) scale(1) translate(0, 0); }
        50% { transform: rotate(2deg) scale(1.05) translate(2%, 3%); }
        100% { transform: rotate(-2deg) scale(1) translate(-2%, -2%); }
    }

    /* GRID PATTERN OVERLAY */
    .bg-grid-pattern { background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h40v40H0V0zm1 1h38v38H1V1z' fill='%2394a3b8' fill-opacity='0.05' fill-rule='evenodd'/%3E%3C/svg%3E"); }
    .dark .bg-grid-pattern { background-image: url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cpath d='M0 0h40v40H0V0zm1 1h38v38H1V1z' fill='%23ffffff' fill-opacity='0.02' fill-rule='evenodd'/%3E%3C/svg%3E"); }

    /* PREMIUM INPUT FOCUS EFFECT */
    .input-luxury { transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .input-luxury:focus-within { box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1); transform: translateY(-2px); }
    .dark .input-luxury:focus-within { box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.15); }

    /* CUSTOM CHECKBOX */
    .custom-checkbox { appearance: none; background-color: transparent; margin: 0; font: inherit; color: currentColor; width: 1.25em; height: 1.25em; border: 1.5px solid currentColor; border-radius: 0.35em; display: grid; place-content: center; transition: all 0.2s ease-in-out; }
    .custom-checkbox::before { content: ""; width: 0.7em; height: 0.7em; transform: scale(0); transition: 120ms transform ease-in-out; box-shadow: inset 1em 1em white; background-color: transform; transform-origin: center; clip-path: polygon(14% 44%, 0 65%, 50% 100%, 100% 16%, 80% 0%, 43% 62%); }
    .custom-checkbox:checked { background-color: #06b6d4; border-color: #06b6d4; }
    .custom-checkbox:checked::before { transform: scale(1); }

    /* FADE IN ANIMATION */
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    /* MODAL TRANSITION CLASSES & SCROLLBAR */
    .modal-backdrop.show { opacity: 1; pointer-events: auto; }
    .modal-panel.show { opacity: 1; transform: scale(1) translateY(0); }
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 4px; }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
</style>

<div class="relative min-h-screen flex items-center justify-center text-slate-800 dark:text-slate-200 overflow-hidden selection:bg-cyan-500/30 selection:text-cyan-900 dark:selection:text-white py-20 px-4 sm:p-6 lg:p-8">

    {{-- AURORA BACKGROUND --}}
    <div class="aurora-wrapper z-0">
        <div class="aurora-bg"></div>
        <div class="absolute inset-0 bg-grid-pattern mix-blend-overlay"></div>
        <canvas id="starsCanvas" class="absolute inset-0 z-10 opacity-0 dark:opacity-40 transition-opacity duration-500"></canvas>
    </div>

    {{-- TOMBOL KEMBALI --}}
    <nav class="absolute top-4 left-4 sm:top-6 sm:left-6 md:top-8 md:left-10 z-50 animate-fade-in-up">
        <a href="{{ url('/') }}" class="group flex items-center gap-2 sm:gap-3 px-3 py-2 sm:px-4 sm:py-2.5 rounded-full bg-white/70 dark:bg-[#0a0e17]/70 border border-slate-200 dark:border-white/10 hover:bg-white dark:hover:bg-[#161b22] backdrop-blur-md transition-all duration-300 shadow-sm hover:shadow-md">
            <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 text-slate-500 dark:text-slate-400 group-hover:-translate-x-1 transition-transform duration-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            <span class="text-[11px] sm:text-xs font-bold text-slate-600 dark:text-slate-300">Kembali</span>
        </a>
    </nav>

    {{-- WRAPPER KARTU DAFTAR --}}
    <div class="w-full max-w-[1050px] flex flex-col md:flex-row bg-white/90 dark:bg-[#0a0e17]/90 backdrop-blur-2xl border border-slate-200/80 dark:border-white/10 rounded-[1.5rem] sm:rounded-[2rem] md:rounded-[2.5rem] shadow-2xl shadow-slate-300/50 dark:shadow-black/80 overflow-hidden relative z-20 animate-fade-in-up" style="animation-delay: 0.1s;">
        
        {{-- BAGIAN KIRI: FORM REGISTER --}}
        <div class="w-full md:w-1/2 p-6 sm:p-10 lg:p-14 xl:p-16 flex flex-col justify-center relative z-20">
            <div class="text-left mb-8 sm:mb-10">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-slate-900 dark:text-white tracking-tight mb-2">Buat Akun.</h1>
                <p class="text-slate-500 dark:text-slate-400 text-xs sm:text-sm font-medium leading-relaxed max-w-sm">Daftar untuk mengakses materi pembelajaran dan workspace interaktif Utilwind.</p>
            </div>

            <form method="POST" action="{{ route('register') }}" class="space-y-4 sm:space-y-5">
                @csrf

                {{-- INPUT NAMA --}}
                <div class="input-luxury group relative bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-[1.25rem] overflow-hidden">
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-400 dark:text-slate-500 group-focus-within:text-cyan-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" />
                        </svg>
                    </div>
                    <input name="name" type="text" required autofocus value="{{ old('name') }}"
                        class="w-full pl-10 sm:pl-12 pr-4 pt-5 sm:pt-6 pb-2 sm:pb-2.5 bg-transparent text-slate-900 dark:text-white focus:outline-none text-[13px] sm:text-sm font-medium peer placeholder-transparent"
                        placeholder="John Doe">
                    <label class="absolute left-10 sm:left-12 top-3.5 sm:top-4 text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider transition-all peer-focus:-translate-y-2 peer-focus:text-[9px] sm:peer-focus:text-[10px] peer-focus:text-cyan-500 peer-placeholder-shown:translate-y-0.5 sm:peer-placeholder-shown:translate-y-1 peer-placeholder-shown:text-[11px] sm:peer-placeholder-shown:text-xs pointer-events-none">
                        Nama Lengkap
                    </label>
                </div>
                @error('name')
                    <p class="text-rose-500 text-[11px] sm:text-xs mt-1 flex items-center gap-1 font-medium"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                @enderror

                {{-- INPUT EMAIL --}}
                <div class="input-luxury group relative bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-[1.25rem] overflow-hidden">
                    <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                        <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-400 dark:text-slate-500 group-focus-within:text-cyan-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 01-2.25 2.25h-15a2.25 2.25 0 01-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25m19.5 0v.243a2.25 2.25 0 01-1.07 1.916l-7.5 4.615a2.25 2.25 0 01-2.36 0L3.32 8.91a2.25 2.25 0 01-1.07-1.916V6.75" />
                        </svg>
                    </div>
                    <input name="email" type="email" required value="{{ old('email') }}"
                        class="w-full pl-10 sm:pl-12 pr-4 pt-5 sm:pt-6 pb-2 sm:pb-2.5 bg-transparent text-slate-900 dark:text-white focus:outline-none text-[13px] sm:text-sm font-medium peer placeholder-transparent"
                        placeholder="nama@email.com">
                    <label class="absolute left-10 sm:left-12 top-3.5 sm:top-4 text-[10px] sm:text-xs font-bold text-slate-400 uppercase tracking-wider transition-all peer-focus:-translate-y-2 peer-focus:text-[9px] sm:peer-focus:text-[10px] peer-focus:text-cyan-500 peer-placeholder-shown:translate-y-0.5 sm:peer-placeholder-shown:translate-y-1 peer-placeholder-shown:text-[11px] sm:peer-placeholder-shown:text-xs pointer-events-none">
                        Alamat Email
                    </label>
                </div>
                @error('email')
                    <p class="text-rose-500 text-[11px] sm:text-xs mt-1 flex items-center gap-1 font-medium"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                @enderror

                {{-- PASSWORD & KONFIRMASI (GRID) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-5">
                    {{-- INPUT PASSWORD --}}
                    <div class="input-luxury group relative bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-[1.25rem] overflow-hidden">
                        <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-400 dark:text-slate-500 group-focus-within:text-fuchsia-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input id="password" name="password" type="password" required
                            class="w-full pl-10 sm:pl-12 pr-10 pt-5 sm:pt-6 pb-2 sm:pb-2.5 bg-transparent text-slate-900 dark:text-white focus:outline-none text-[13px] sm:text-sm font-medium peer placeholder-transparent"
                            placeholder="••••••••">
                        <label class="absolute left-10 sm:left-12 top-3.5 sm:top-4 text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider transition-all peer-focus:-translate-y-2 peer-focus:text-[8px] sm:peer-focus:text-[9px] peer-focus:text-fuchsia-500 peer-placeholder-shown:translate-y-0.5 sm:peer-placeholder-shown:translate-y-1 peer-placeholder-shown:text-[10px] sm:peer-placeholder-shown:text-[11px] pointer-events-none">
                            Password
                        </label>
                        <button type="button" onclick="toggleVisibility('password', 'eye1', 'eyeClosed1')" class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none transition-colors">
                            <svg id="eyeClosed1" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                            <svg id="eye1" class="h-4 w-4 sm:h-5 sm:w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </button>
                    </div>

                    {{-- INPUT KONFIRMASI PASSWORD --}}
                    <div class="input-luxury group relative bg-slate-50 dark:bg-slate-900/40 border border-slate-200 dark:border-white/10 rounded-xl sm:rounded-[1.25rem] overflow-hidden">
                        <div class="absolute inset-y-0 left-0 pl-3.5 sm:pl-4 flex items-center pointer-events-none">
                            <svg class="h-4 w-4 sm:h-5 sm:w-5 text-slate-400 dark:text-slate-500 group-focus-within:text-fuchsia-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 10-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 002.25-2.25v-6.75a2.25 2.25 0 00-2.25-2.25H6.75a2.25 2.25 0 00-2.25 2.25v6.75a2.25 2.25 0 002.25 2.25z" />
                            </svg>
                        </div>
                        <input id="password_confirmation" name="password_confirmation" type="password" required
                            class="w-full pl-10 sm:pl-12 pr-10 pt-5 sm:pt-6 pb-2 sm:pb-2.5 bg-transparent text-slate-900 dark:text-white focus:outline-none text-[13px] sm:text-sm font-medium peer placeholder-transparent"
                            placeholder="••••••••">
                        <label class="absolute left-10 sm:left-12 top-3.5 sm:top-4 text-[9px] sm:text-[11px] font-bold text-slate-400 uppercase tracking-wider transition-all peer-focus:-translate-y-2 peer-focus:text-[8px] sm:peer-focus:text-[9px] peer-focus:text-fuchsia-500 peer-placeholder-shown:translate-y-0.5 sm:peer-placeholder-shown:translate-y-1 peer-placeholder-shown:text-[10px] sm:peer-placeholder-shown:text-[11px] pointer-events-none">
                            Konfirmasi
                        </label>
                        <button type="button" onclick="toggleVisibility('password_confirmation', 'eye2', 'eyeClosed2')" class="absolute inset-y-0 right-0 pr-3 sm:pr-4 flex items-center text-slate-400 hover:text-slate-600 dark:hover:text-slate-200 focus:outline-none transition-colors">
                            <svg id="eyeClosed2" class="h-4 w-4 sm:h-5 sm:w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 001.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.45 10.45 0 0112 4.5c4.756 0 8.773 3.162 10.065 7.498a10.523 10.523 0 01-4.293 5.774M6.228 6.228L3 3m3.228 3.228l3.65 3.65m7.894 7.894L21 21m-3.228-3.228l-3.65-3.65m0 0a3 3 0 10-4.243-4.243m4.242 4.242L9.88 9.88" /></svg>
                            <svg id="eye2" class="h-4 w-4 sm:h-5 sm:w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" /><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        </button>
                    </div>
                </div>
                @error('password')
                    <p class="text-rose-500 text-[11px] sm:text-xs mt-1 flex items-center gap-1 font-medium"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>{{ $message }}</p>
                @enderror

                {{-- SYARAT DAN KETENTUAN --}}
                <div class="flex items-start pt-1 pb-1">
                    <div class="flex items-center h-4 sm:h-5 mt-1 sm:mt-0.5">
                        <input id="terms" name="terms" type="checkbox" required class="custom-checkbox text-slate-400 dark:text-slate-500 border-slate-300 dark:border-white/30 cursor-pointer">
                    </div>
                    <label for="terms" class="ml-2.5 sm:ml-3 text-[11px] sm:text-xs font-semibold text-slate-500 dark:text-slate-400 leading-relaxed cursor-pointer select-none">
                        Saya menyetujui <button type="button" onclick="openModal('tncModal')" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 transition-colors focus:outline-none">Syarat & Ketentuan</button> serta <button type="button" onclick="openModal('privacyModal')" class="text-cyan-600 dark:text-cyan-400 hover:text-cyan-700 dark:hover:text-cyan-300 transition-colors focus:outline-none">Kebijakan Privasi</button>.
                    </label>
                </div>

                {{-- SUBMIT BUTTON --}}
                <button type="submit" class="relative w-full py-3.5 sm:py-4 mt-3 sm:mt-2 rounded-xl sm:rounded-[1.25rem] bg-gradient-to-r from-slate-900 to-slate-800 dark:from-white dark:to-slate-200 text-white dark:text-slate-900 font-bold text-[13.5px] sm:text-sm shadow-xl shadow-slate-900/20 dark:shadow-white/10 hover:-translate-y-1 hover:shadow-2xl transition-all duration-300 overflow-hidden group">
                    <div class="absolute inset-0 bg-white/20 dark:bg-black/10 translate-x-[-100%] group-hover:translate-x-[100%] transition-transform duration-700 ease-in-out"></div>
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Daftar Akun
                        <svg class="w-3.5 h-3.5 sm:w-4 sm:h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" /></svg>
                    </span>
                </button>
            </form>

            <div class="mt-6 sm:mt-8 text-center pt-5 sm:pt-6 border-t border-slate-200 dark:border-white/5">
                <p class="text-[12px] sm:text-sm text-slate-500 dark:text-slate-400 font-medium">
                    Sudah memiliki akun?
                    <a href="{{ route('login') }}" class="text-cyan-600 dark:text-cyan-400 font-bold hover:text-cyan-700 dark:hover:text-cyan-300 transition-colors ml-1">Masuk di sini</a>
                </p>
            </div>
        </div>

        {{-- BAGIAN KANAN: SHOWCASE VISUAL (Diambil persis dari Login Page) --}}
        <div class="hidden md:flex w-1/2 relative bg-[#0a0e17] overflow-hidden flex-col items-center justify-center border-l border-slate-200/50 dark:border-white/5 p-8 lg:p-12">
            
            {{-- Background Aura Internal Panel Kanan --}}
            <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 via-fuchsia-500/10 to-indigo-500/10 z-0"></div>
            <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDUpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] z-0 opacity-20"></div>
            
            {{-- Top Badge Indicator --}}
            <div class="absolute top-8 right-8 lg:top-10 lg:right-10 z-10 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-400 animate-pulse shadow-[0_0_8px_rgba(52,211,153,0.8)]"></span>
                <span class="text-white/50 text-[10px] font-mono tracking-widest uppercase font-bold">Interactive Sandbox</span>
            </div>

            {{-- 3D GLASSMORPHISM CARD (Tilted View) --}}
            <div class="relative z-10 w-[90%] lg:w-[85%] max-w-md flex flex-col rounded-3xl bg-[#0d1117]/60 backdrop-blur-xl border border-white/10 shadow-2xl transform rotate-2 hover:rotate-0 transition-transform duration-500 overflow-hidden">
                
                {{-- Mac Header Style --}}
                <div class="flex items-center px-5 lg:px-6 py-3.5 lg:py-4 border-b border-white/5 bg-white/5">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 lg:w-3 lg:h-3 rounded-full bg-rose-500"></div>
                        <div class="w-2.5 h-2.5 lg:w-3 lg:h-3 rounded-full bg-amber-500"></div>
                        <div class="w-2.5 h-2.5 lg:w-3 lg:h-3 rounded-full bg-emerald-500"></div>
                    </div>
                    <div class="mx-auto text-[9px] lg:text-[10px] font-mono text-slate-500">button.html</div>
                </div>

                {{-- Code Editor Layer (Tailwind Representation) --}}
                <div class="p-6 lg:p-8 pb-5 lg:pb-6 font-mono text-[11px] lg:text-[13px] text-slate-300 leading-loose">
                    <div class="flex"><span class="text-pink-400">&lt;button</span></div>
                    <div class="flex pl-4 lg:pl-6"><span class="text-sky-400">class=</span><span class="text-emerald-300">"px-8 py-4 bg-gradient-to-r"</span></div>
                    <div class="flex pl-4 lg:pl-6"><span class="text-emerald-300">"from-cyan-500 to-indigo-500"</span></div>
                    <div class="flex pl-4 lg:pl-6"><span class="text-emerald-300">"text-white font-bold rounded-full"</span></div>
                    <div class="flex pl-4 lg:pl-6"><span class="text-emerald-300">"shadow-lg hover:scale-105"</span></div>
                    <div class="flex pl-4 lg:pl-6"><span class="text-emerald-300">"transition-transform"</span><span class="text-pink-400">&gt;</span></div>
                    <div class="flex pl-4 lg:pl-6"><span class="text-white">Hover Me</span></div>
                    <div class="flex"><span class="text-pink-400">&lt;/button&gt;</span></div>
                </div>

                {{-- Visual Preview Layer (Hasil Render Code) --}}
                <div class="p-6 lg:p-8 flex flex-col items-center justify-center bg-[#060a14]/60 border-t border-white/5">
                    <button type="button" class="px-6 py-3 lg:px-8 lg:py-4 bg-gradient-to-r from-cyan-500 to-indigo-500 text-white font-bold rounded-full shadow-[0_10px_20px_-10px_rgba(6,182,212,0.8)] hover:scale-110 transition-transform duration-300 cursor-default text-xs lg:text-base">
                        Hover Me
                    </button>
                </div>
                
            </div>
            
        </div>

    </div>

    {{-- MODAL SYARAT & KETENTUAN --}}
    <div id="tncModal" class="modal-backdrop fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm" onclick="closeModal('tncModal')"></div>
        <div class="modal-panel relative w-full max-w-sm bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 shadow-2xl opacity-0 transform scale-95 translate-y-4 transition-all duration-300">
            <div class="flex justify-center mb-5 sm:mb-6">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-[1rem] sm:rounded-2xl bg-cyan-50 dark:bg-cyan-500/10 border border-cyan-100 dark:border-cyan-500/20 flex items-center justify-center text-cyan-600 dark:text-cyan-400">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                </div>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-center text-slate-900 dark:text-white mb-4 tracking-tight">Syarat Penggunaan</h2>
            <div class="text-[12px] sm:text-[13px] text-slate-600 dark:text-slate-400 space-y-3 sm:space-y-4 font-medium leading-relaxed mb-6 sm:mb-8 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                <p>Akses ke platform Utilwind disediakan untuk tujuan pembelajaran antarmuka.</p>
                <p>Penggunaan fasilitas yang tersedia, termasuk lingkungan eksekusi kode, diatur untuk mencegah aktivitas yang berpotensi membebani infrastruktur server secara tidak wajar.</p>
                <p>Pemeliharaan ekosistem belajar yang stabil merupakan prioritas. Akses dapat dibatasi apabila ditemukan indikasi penyalahgunaan layanan.</p>
            </div>
            <button type="button" onclick="closeModal('tncModal')" class="w-full py-3 sm:py-3.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-[12px] sm:text-[13px] shadow-lg hover:-translate-y-0.5 transition-transform duration-200">
                Tutup Dokumen
            </button>
        </div>
    </div>

    {{-- MODAL KEBIJAKAN PRIVASI --}}
    <div id="privacyModal" class="modal-backdrop fixed inset-0 z-[100] flex items-center justify-center opacity-0 pointer-events-none transition-opacity duration-300 p-4">
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-black/60 backdrop-blur-sm" onclick="closeModal('privacyModal')"></div>
        <div class="modal-panel relative w-full max-w-sm bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-[1.5rem] sm:rounded-[2rem] p-6 sm:p-8 shadow-2xl opacity-0 transform scale-95 translate-y-4 transition-all duration-300">
            <div class="flex justify-center mb-5 sm:mb-6">
                <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-[1rem] sm:rounded-2xl bg-slate-100 dark:bg-slate-800 border border-slate-200 dark:border-slate-700 flex items-center justify-center text-slate-600 dark:text-slate-400">
                    <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" /></svg>
                </div>
            </div>
            <h2 class="text-lg sm:text-xl font-bold text-center text-slate-900 dark:text-white mb-4 tracking-tight">Kebijakan Privasi</h2>
            <div class="text-[12px] sm:text-[13px] text-slate-600 dark:text-slate-400 space-y-3 sm:space-y-4 font-medium leading-relaxed mb-6 sm:mb-8 max-h-48 overflow-y-auto pr-2 custom-scrollbar">
                <p>Data autentikasi yang dikumpulkan hanya digunakan untuk verifikasi akses dan pencatatan riwayat sesi belajar.</p>
                <p>Informasi kredensial disimpan menggunakan protokol enkripsi standar industri untuk meminimalkan risiko akses yang tidak sah.</p>
                <p>Data yang tersimpan di dalam sistem tidak didistribusikan kepada pihak ketiga tanpa persetujuan eksplisit, kecuali jika diwajibkan oleh prosedur hukum yang berlaku.</p>
            </div>
            <button type="button" onclick="closeModal('privacyModal')" class="w-full py-3 sm:py-3.5 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-bold text-[12px] sm:text-[13px] shadow-lg hover:-translate-y-0.5 transition-transform duration-200">
                Tutup Dokumen
            </button>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // --- THEME CHECK ---
    document.addEventListener('DOMContentLoaded', function() {
        const htmlEl = document.documentElement;
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            htmlEl.classList.add('dark');
        } else {
            htmlEl.classList.remove('dark');
        }
    });

    // --- LOGIC TOGGLE PASSWORD (Multiple Inputs) ---
    function toggleVisibility(inputId, iconOpenId, iconClosedId) {
        const input = document.getElementById(inputId);
        const iconOpen = document.getElementById(iconOpenId);
        const iconClosed = document.getElementById(iconClosedId);

        if (input.type === 'password') {
            input.type = 'text';
            iconOpen.classList.remove('hidden');
            iconClosed.classList.add('hidden');
        } else {
            input.type = 'password';
            iconOpen.classList.add('hidden');
            iconClosed.classList.remove('hidden');
        }
    }

    // --- LOGIC MODAL INTERAKTIF ---
    function openModal(modalId) {
        const modal = document.getElementById(modalId);
        const panel = modal.querySelector('.modal-panel');
        
        modal.classList.add('show');
        setTimeout(() => {
            panel.classList.add('show');
        }, 10);
    }

    function closeModal(modalId) {
        const modal = document.getElementById(modalId);
        const panel = modal.querySelector('.modal-panel');
        
        panel.classList.remove('show');
        setTimeout(() => {
            modal.classList.remove('show');
        }, 300);
    }

    // --- CANVAS STARS ANIMATION (KHUSUS DARK MODE) ---
    $(document).ready(function() {
        const c = document.getElementById('starsCanvas');
        if(c) {
            const x = c.getContext('2d');
            let s = [];
            function initStars() { 
                c.width = window.innerWidth; 
                c.height = window.innerHeight; 
                s=[]; 
                for(let i=0;i<60;i++) s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.3-0.15, h:Math.random()*0.3-0.15}); 
            }
            function animateStars() { 
                x.clearRect(0,0,c.width,c.height); 
                x.fillStyle = $('html').hasClass('dark') ? 'rgba(255,255,255,0.4)' : 'transparent'; 
                s.forEach(t=>{ 
                    x.beginPath(); x.arc(t.x,t.y,t.r,0,Math.PI*2); x.fill(); 
                    t.y+=t.v; t.x+=t.h;
                    if(t.y<0 || t.y>c.height) t.v *= -1; 
                    if(t.x<0 || t.x>c.width) t.h *= -1; 
                }); 
                requestAnimationFrame(animateStars); 
            }
            window.addEventListener('resize', initStars); 
            initStars(); 
            animateStars();
        }
    });
</script>
@endsection