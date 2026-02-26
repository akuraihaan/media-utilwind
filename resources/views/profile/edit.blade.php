@extends('layouts.landing')
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
@section('title', 'Profil Saya')

@section('content')
<div class="min-h-screen bg-[#020617] text-white font-sans selection:bg-fuchsia-500/30 pb-20 pt-28 md:pt-32">

    {{-- ==================== BACKGROUND FX ==================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-fuchsia-600/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-cyan-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')

    <main class="max-w-7xl mx-auto px-5 lg:px-8 relative z-10">
        
        {{-- ==================== HEADER & BREADCRUMB ==================== --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6 mb-8 md:mb-12 reveal-up">
            <div>
                {{-- Breadcrumb --}}
                <nav class="flex items-center gap-2 mb-3 text-[10px] md:text-xs font-bold uppercase tracking-widest text-white/40" aria-label="Breadcrumb">
                    <a href="/" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <span class="text-white/20">/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                    <span class="text-white/20">/</span>
                    <span class="text-fuchsia-400 drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]">Profil Saya</span>
                </nav>

                <h1 class="text-3xl md:text-5xl font-black text-white tracking-tight mb-2">
                    Profil <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Pengguna</span>
                </h1>
                <p class="text-white/50 text-sm md:text-base">Kelola informasi pribadi, identitas akademik, dan lihat jejak karir Anda.</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-12 gap-6 md:gap-8 items-start reveal-up delay-100">

            {{-- ==================== LEFT COLUMN: GAMIFICATION & IDENTITY ==================== --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- 1. Identity & Level Card (Ultimate Design) --}}
                <div class="relative bg-[#0f141e]/80 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-8 text-center overflow-hidden shadow-2xl group/profile">
                    <div class="absolute inset-0 bg-gradient-to-b from-white/[0.02] to-transparent pointer-events-none"></div>
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-cyan-500/10 rounded-full blur-[50px] group-hover/profile:bg-cyan-500/20 transition-all duration-700"></div>
                    
                    {{-- Avatar Wrapper --}}
                    <div class="relative w-32 h-32 mx-auto mb-6">
                        <div class="absolute inset-0 bg-gradient-to-tr from-fuchsia-500 to-cyan-500 rounded-full blur-md opacity-40 group-hover/profile:opacity-70 transition duration-500 animate-spin-slow"></div>
                        
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                 class="relative w-full h-full object-cover rounded-full border-4 border-[#0f141e] shadow-xl transition-transform duration-500 hover:scale-105 cursor-pointer" 
                                 alt="Profile Photo" id="avatar-display">
                        @else
                            <div class="relative w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-900 rounded-full border-4 border-[#0f141e] text-4xl font-black text-white shadow-xl transition-transform duration-500 hover:scale-105 cursor-pointer" id="avatar-display">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                        @endif

                        {{-- Role Badge --}}
                        <div class="absolute bottom-0 right-0 translate-x-2 translate-y-1 z-10">
                            <span class="flex items-center gap-1.5 px-3 py-1 bg-[#020617] border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-widest text-cyan-400 shadow-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                {{ Auth::user()->role ?? 'Student' }}
                            </span>
                        </div>
                    </div>

                    <h2 class="text-2xl font-black text-white truncate">{{ Auth::user()->name }}</h2>
                    <p class="text-xs text-white/40 font-mono mt-1 mb-8 truncate">{{ Auth::user()->email }}</p>

                    {{-- GAMIFICATION: Rank & Progress --}}
                    <div class="bg-[#020617] rounded-2xl p-5 border border-white/5 shadow-inner text-left relative overflow-hidden">
                        <div class="absolute top-0 right-0 w-32 h-32 bg-fuchsia-500/5 rounded-full blur-[30px] pointer-events-none"></div>
                        
                        <div class="flex justify-between items-end mb-2 relative z-10">
                            <div>
                                <p class="text-[9px] uppercase tracking-widest text-white/40 font-bold mb-1">Developer Rank</p>
                                <h3 class="text-lg font-black text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">
                                    {{ Auth::user()->developer_title ?? 'CSS Novice' }}
                                </h3>
                            </div>
                            <div class="text-right">
                                <span class="text-xl font-black text-white">{{ number_format(Auth::user()->xp ?? ($stats['xp'] ?? 0)) }}</span>
                                <span class="text-[10px] text-fuchsia-400 font-bold ml-1">XP</span>
                            </div>
                        </div>

                        {{-- Progress Bar Container --}}
                        <div class="w-full h-2.5 bg-slate-800 rounded-full overflow-hidden mt-4 relative z-10 border border-white/5">
                            @php
                                $xpPercent = Auth::user()->xp_progress ?? 0;
                            @endphp
                            <div id="xp-progress-bar" class="h-full bg-gradient-to-r from-cyan-400 to-fuchsia-500 rounded-full relative" style="width: 0%;" data-target="{{ $xpPercent }}">
                                <div class="absolute inset-0 bg-white/20 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        
                        <div class="flex justify-between items-center mt-2 relative z-10">
                            <span class="text-[9px] text-slate-500 font-mono font-bold">{{ $xpPercent }}% Selesai</span>
                            <span class="text-[9px] text-slate-400 font-bold">Target: {{ number_format(Auth::user()->next_level_xp ?? 300) }} XP</span>
                        </div>
                    </div>
                </div>

                {{-- 2. Insight Stats (Bento Style) --}}
                <div class="grid grid-cols-2 gap-4">
                    {{-- Avg Score --}}
                    <div class="p-5 rounded-[2rem] bg-[#0f141e]/80 backdrop-blur-md border border-white/10 shadow-xl flex flex-col justify-center items-center text-center group hover:border-cyan-500/30 transition-colors">
                        <div class="w-10 h-10 bg-cyan-500/10 text-cyan-400 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                        </div>
                        <p class="text-3xl font-black text-white">{{ $stats['avg_score'] ?? 0 }}</p>
                        <p class="text-[9px] text-white/40 uppercase font-bold tracking-widest mt-1">Rata-rata Kuis</p>
                    </div>

                    {{-- Completed Labs --}}
                    <div class="p-5 rounded-[2rem] bg-[#0f141e]/80 backdrop-blur-md border border-white/10 shadow-xl flex flex-col justify-center items-center text-center group hover:border-indigo-500/30 transition-colors">
                        <div class="w-10 h-10 bg-indigo-500/10 text-indigo-400 rounded-2xl flex items-center justify-center mb-3 group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <p class="text-3xl font-black text-white">{{ $stats['labs_done'] ?? 0 }}</p>
                        <p class="text-[9px] text-white/40 uppercase font-bold tracking-widest mt-1">Lab Diselesaikan</p>
                    </div>
                </div>
            </div>

            {{-- ==================== RIGHT COLUMN: FORMS ==================== --}}
            <div class="lg:col-span-8 space-y-6 md:space-y-8">

                {{-- Flash Message (jQuery animated) --}}
                @if(session('success'))
                    <div id="flash-message" class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-bold flex items-center justify-between shadow-lg">
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 bg-emerald-500/20 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
                            </div>
                            {{ session('success') }}
                        </div>
                        <button onclick="$('#flash-message').fadeOut()" class="hover:text-emerald-300 transition p-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                @endif

                {{-- FORM 1: UPDATE PROFILE --}}
                <div class="bg-[#0f141e]/90 backdrop-blur-xl border border-white/10 rounded-[2rem] p-6 md:p-10 shadow-2xl relative overflow-hidden">
                    <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center font-bold text-xl border border-cyan-500/20 shadow-inner">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl md:text-2xl font-black text-white tracking-tight">Informasi Dasar</h3>
                            <p class="text-xs md:text-sm text-white/40 mt-1">Perbarui foto profil dan data identitas Anda.</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" id="profile-form" class="space-y-6 md:space-y-8 relative z-10">
                        @csrf
                        @method('PATCH')

                        {{-- Custom Upload UI --}}
                        <div class="flex flex-col sm:flex-row items-center gap-6 p-5 rounded-2xl bg-[#020617] border border-white/10 shadow-inner">
                            <div class="shrink-0 relative group cursor-pointer" id="avatar-trigger">
                                <img id="preview-img" 
                                     src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name.'&background=random' }}" 
                                     class="h-20 w-20 object-cover rounded-full border-2 border-white/10 shadow-lg transition-all duration-300 group-hover:border-cyan-500/50" />
                                <div class="absolute inset-0 bg-black/60 rounded-full flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity">
                                    <svg class="w-6 h-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                </div>
                            </div>
                            <div class="flex-1 text-center sm:text-left">
                                <label class="block text-sm font-bold text-white mb-2">Foto Profil</label>
                                <p class="text-xs text-white/40 mb-4">Format yang didukung: JPG, PNG, GIF. Ukuran maksimal 2MB.</p>
                                <div class="flex justify-center sm:justify-start gap-3">
                                    <input type="file" name="avatar" id="avatar-input" class="hidden" accept="image/*"/>
                                    <button type="button" id="btn-upload" class="px-5 py-2.5 bg-white/5 hover:bg-white/10 text-white text-xs font-bold rounded-xl border border-white/10 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                        Pilih File Baru
                                    </button>
                                    <span id="file-name" class="text-[10px] text-cyan-400 font-mono self-center hidden"></span>
                                </div>
                            </div>
                        </div>

                        {{-- Grid Inputs --}}
                        <div class="grid md:grid-cols-2 gap-6">
                            
                            {{-- Nama --}}
                            <div class="input-group relative">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Nama Lengkap <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 icon-wrap text-white/30">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    </div>
                                    <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" required
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-white/20 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all shadow-inner focus:shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                </div>
                                @error('name') <span class="text-red-400 text-xs mt-1 absolute -bottom-5 left-0">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="input-group relative">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Alamat Email <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 icon-wrap text-white/30">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                    </div>
                                    <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" required
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-white/20 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all shadow-inner focus:shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                </div>
                                @error('email') <span class="text-red-400 text-xs mt-1 absolute -bottom-5 left-0">{{ $message }}</span> @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="input-group relative">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Nomor WhatsApp</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 icon-wrap text-white/30">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                    </div>
                                    <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="Contoh: 0812..." 
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-white/20 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all shadow-inner font-mono focus:shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                </div>
                            </div>

                            {{-- Institution --}}
                            <div class="input-group relative">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Asal Institusi / Sekolah</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 icon-wrap text-white/30">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                    </div>
                                    <input type="text" name="institution" value="{{ old('institution', Auth::user()->institution) }}" placeholder="Universitas..." 
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-white/20 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all shadow-inner focus:shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                </div>
                            </div>

                            {{-- Study Program (Full Width) --}}
                            <div class="input-group relative md:col-span-2">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Program Studi / Jurusan</label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none transition-colors duration-300 icon-wrap text-white/30">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                    </div>
                                    <input type="text" name="study_program" value="{{ old('study_program', Auth::user()->study_program) }}" placeholder="Pendidikan Komputer..." 
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-4 py-3.5 text-sm text-white placeholder-white/20 focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition-all shadow-inner focus:shadow-[0_0_15px_rgba(6,182,212,0.2)]">
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-white/5 mt-8">
                            <button type="submit" id="btn-save-profile" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 text-white font-bold shadow-[0_0_20px_rgba(6,182,212,0.3)] hover:shadow-[0_0_25px_rgba(6,182,212,0.5)] hover:scale-[1.02] active:scale-[0.98] transition-all flex items-center justify-center gap-2">
                                <span class="btn-text">Simpan Perubahan</span>
                                <svg class="w-5 h-5 btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"/></svg>
                                <svg class="w-5 h-5 animate-spin hidden spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

                {{-- FORM 2: SECURITY --}}
                <div class="bg-[#0f141e]/90 backdrop-blur-xl border border-red-500/20 rounded-[2rem] p-6 md:p-10 shadow-2xl relative overflow-hidden">
                    <div class="absolute top-0 right-0 w-64 h-64 bg-red-500/5 rounded-full blur-3xl pointer-events-none"></div>

                    <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6 relative z-10">
                        <div class="w-12 h-12 rounded-2xl bg-red-500/10 text-red-400 flex items-center justify-center font-bold text-xl border border-red-500/20 shadow-inner">
                            <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                        <div>
                            <h3 class="text-xl md:text-2xl font-black text-white tracking-tight">Keamanan Akun</h3>
                            <p class="text-xs md:text-sm text-white/40 mt-1">Ubah kata sandi secara berkala untuk menjaga keamanan data.</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.password') }}" method="POST" id="password-form" class="space-y-6 md:space-y-8 relative z-10">
                        @csrf
                        @method('PUT')

                        <div class="input-group relative">
                            <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Password Saat Ini <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 transition-colors duration-300 icon-wrap">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                </div>
                                <input type="password" name="current_password" required placeholder="••••••••"
                                    class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-12 py-3.5 text-sm text-white placeholder-white/20 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all shadow-inner font-mono tracking-widest focus:shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-white/30 hover:text-white transition">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                </button>
                            </div>
                            @error('current_password') <span class="text-red-400 text-xs mt-1 absolute -bottom-5 left-0">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="input-group relative">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Password Baru <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 transition-colors duration-300 icon-wrap">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                    </div>
                                    <input type="password" name="password" id="new-password" required placeholder="••••••••"
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-12 py-3.5 text-sm text-white placeholder-white/20 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all shadow-inner font-mono tracking-widest focus:shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                    <button type="button" class="toggle-password absolute inset-y-0 right-0 pr-4 flex items-center text-white/30 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                    </button>
                                </div>
                                {{-- Password Strength Meter --}}
                                <div class="mt-2 h-1 w-full bg-slate-800 rounded-full overflow-hidden">
                                    <div id="strength-bar" class="h-full bg-red-500 w-0 transition-all duration-300"></div>
                                </div>
                                <p id="strength-text" class="text-[9px] text-white/30 mt-1 uppercase font-bold tracking-widest text-right">Kekuatan: -</p>
                                @error('password') <span class="text-red-400 text-xs mt-1 absolute -bottom-5 left-0">{{ $message }}</span> @enderror
                            </div>

                            <div class="input-group relative">
                                <label class="block text-[10px] font-bold text-white/50 uppercase tracking-widest mb-2 transition-colors duration-300 label-text">Konfirmasi Password <span class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-white/30 transition-colors duration-300 icon-wrap">
                                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    </div>
                                    <input type="password" name="password_confirmation" id="confirm-password" required placeholder="••••••••"
                                        class="custom-input w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-12 pr-12 py-3.5 text-sm text-white placeholder-white/20 focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition-all shadow-inner font-mono tracking-widest focus:shadow-[0_0_15px_rgba(239,68,68,0.2)]">
                                    <div class="absolute inset-y-0 right-0 pr-4 flex items-center">
                                        <svg id="match-icon" class="w-5 h-5 text-emerald-500 hidden transition-all scale-in" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end pt-4 border-t border-white/5 mt-8">
                            <button type="submit" id="btn-save-password" class="w-full md:w-auto px-8 py-3.5 rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30 transition-all flex items-center justify-center gap-2">
                                <span class="btn-text">Update Password</span>
                                <svg class="w-5 h-5 btn-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                                <svg class="w-5 h-5 animate-spin hidden spinner" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>
</div>

{{-- CSS Tambahan & Animasi --}}
<style>
    /* Styling autofill browser */
    input:-webkit-autofill,
    input:-webkit-autofill:hover, 
    input:-webkit-autofill:focus, 
    input:-webkit-autofill:active{
        -webkit-box-shadow: 0 0 0 30px #0a0e17 inset !important;
        -webkit-text-fill-color: white !important;
        transition: background-color 5000s ease-in-out 0s;
    }
    
    .animate-spin-slow { animation: spin 8s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    .animate-fade-in-down { animation: fadeInDown 0.4s ease-out forwards; }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    .reveal-up { opacity: 0; transform: translateY(20px); animation: revealUp 0.6s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes revealUp { to { opacity: 1; transform: translateY(0); } }
    .delay-100 { animation-delay: 100ms; }
    
    .scale-in { animation: scaleIn 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards; }
    @keyframes scaleIn { from { transform: scale(0); opacity: 0; } to { transform: scale(1); opacity: 1; } }

    /* Focus Within Input Logic (Untuk mewarnai icon/label saat input aktif) */
    .input-group:focus-within .icon-wrap { color: var(--focus-color, #06b6d4); }
    .input-group:focus-within .label-text { color: var(--focus-color, #06b6d4); }
</style>

{{-- JQUERY SCRIPT UNTUK UX MAKSIMAL --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        
        // 1. ANIMASI PROGRESS BAR KETIKA LOAD
        setTimeout(() => {
            const bar = $('#xp-progress-bar');
            const targetWidth = bar.data('target');
            bar.css('width', targetWidth + '%');
        }, 500);

        // 2. TRIGGER UPLOAD FILE & PREVIEW
        $('#btn-upload, #avatar-trigger').on('click', function() {
            $('#avatar-input').click();
        });

        $('#avatar-input').on('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                if(file.size > 2 * 1024 * 1024) {
                    alert("Ukuran file terlalu besar! Maksimal 2MB.");
                    $(this).val('');
                    $('#file-name').addClass('hidden');
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const img = $('#preview-img');
                    img.attr('src', e.target.result);
                    // Add bounce effect
                    img.removeClass('scale-105').addClass('scale-95');
                    setTimeout(() => img.removeClass('scale-95').addClass('scale-105 transition-transform duration-300'), 150);
                }
                reader.readAsDataURL(file);
                
                $('#file-name').text(file.name).removeClass('hidden');
            }
        });

        // 3. PASSWORD STRENGTH METER
        $('#new-password').on('input', function() {
            const val = $(this).val();
            let strength = 0;
            
            if(val.length >= 8) strength += 25; // Length
            if(val.match(/[A-Z]/)) strength += 25; // Uppercase
            if(val.match(/[0-9]/)) strength += 25; // Number
            if(val.match(/[^a-zA-Z0-9]/)) strength += 25; // Special Char

            const bar = $('#strength-bar');
            const text = $('#strength-text');
            
            bar.css('width', strength + '%');
            
            if (strength === 0) {
                bar.removeClass().addClass('h-full w-0 transition-all duration-300');
                text.text('Kekuatan: -').css('color', 'rgba(255,255,255,0.3)');
            } else if (strength <= 25) {
                bar.removeClass().addClass('h-full bg-red-500 transition-all duration-300').css('width', strength + '%');
                text.text('Kekuatan: Sangat Lemah').css('color', '#ef4444');
            } else if (strength <= 50) {
                bar.removeClass().addClass('h-full bg-yellow-500 transition-all duration-300').css('width', strength + '%');
                text.text('Kekuatan: Lemah').css('color', '#eab308');
            } else if (strength <= 75) {
                bar.removeClass().addClass('h-full bg-blue-500 transition-all duration-300').css('width', strength + '%');
                text.text('Kekuatan: Sedang').css('color', '#3b82f6');
            } else {
                bar.removeClass().addClass('h-full bg-emerald-500 transition-all duration-300').css('width', strength + '%');
                text.text('Kekuatan: Sangat Kuat').css('color', '#10b981');
            }
            
            checkPasswordMatch();
        });

        // 4. PASSWORD MATCH CHECKER
        $('#confirm-password').on('input', checkPasswordMatch);

        function checkPasswordMatch() {
            const pass1 = $('#new-password').val();
            const pass2 = $('#confirm-password').val();
            const icon = $('#match-icon');
            
            if (pass2.length > 0 && pass1 === pass2) {
                icon.removeClass('hidden');
            } else {
                icon.addClass('hidden');
            }
        }

        // 5. TOGGLE PASSWORD VISIBILITY
        $('.toggle-password').on('click', function() {
            const input = $(this).siblings('input');
            const type = input.attr('type') === 'password' ? 'text' : 'password';
            input.attr('type', type);
            
            // Ubah icon
            if(type === 'text') {
                $(this).html('<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.29 3.29m0 0a10.05 10.05 0 015.71-1.29c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0l-3.29-3.29"/></svg>');
            } else {
                $(this).html('<svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>');
            }
        });

        // 6. FORM SUBMIT LOADING STATE (UX Button)
        $('#profile-form, #password-form').on('submit', function() {
            const btn = $(this).find('button[type="submit"]');
            btn.find('.btn-text').text('Menyimpan...');
            btn.find('.btn-icon').addClass('hidden');
            btn.find('.spinner').removeClass('hidden');
            btn.addClass('opacity-70 cursor-not-allowed pointer-events-none');
        });
        
        // Atur warna focus sesuai grup (UX coloring)
        $('.input-group').on('focusin', function() {
            const isRed = $(this).closest('form').attr('id') === 'password-form';
            $(this).css('--focus-color', isRed ? '#ef4444' : '#06b6d4');
        });
    });
</script>

@endsection