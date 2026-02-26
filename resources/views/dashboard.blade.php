@extends('layouts.landing')

@section('title', 'Dashboard Siswa · TailwindLearn')

@section('content')
<div id="appRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30 pt-20">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')
    
    <div class="flex flex-1 overflow-hidden relative">

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        {{-- SIDEBAR MENU DASHBOARD --}}
        <aside class="w-[280px] bg-[#050912]/80 backdrop-blur-xl border-r border-white/5 flex-col shrink-0 z-[100] fixed lg:relative h-full transition-transform duration-300 transform lg:translate-x-0 flex" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <p class="text-xs font-bold text-white/30 uppercase tracking-widest mb-4 pl-2">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white font-bold shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-[1.02]">
                        <span class="text-fuchsia-400 group-hover:scale-110 transition text-lg drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]">📊</span>
                        Overview
                    </a>
                    
                    {{-- Logic Kelas: Materi Belajar --}}
                    @php
                        $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group));
                    @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                            <span class="grayscale group-hover:grayscale-0 transition text-lg">📚</span>
                            Materi Belajar
                        </a>
                    @else
                        <button class="w-full text-left group flex items-center justify-between px-4 py-3 rounded-xl bg-red-500/5 text-red-400/80 cursor-not-allowed border border-transparent">
                            <div class="flex items-center gap-3"><span class="grayscale opacity-50 text-lg">📚</span> <span class="font-medium">Materi Belajar</span></div>
                            <svg class="w-4 h-4 text-red-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </button>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">⚙️</span>
                        Pengaturan
                    </a>
                    
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">👨‍💻</span>
                        Informasi
                    </a>
                </nav>
            </div>
            
            
        </aside>

        {{-- MAIN CONTENT DENGAN X-DATA MODALS --}}
        <main x-data="{ 
                showJoinModal: false,
                showLessonModal: false,
                showLabModal: false,
                showQuizModal: false,
                showChapterModal: false,
                showBadgeModal: false,
                activeBadge: null
              }" 
              @keydown.escape.window="showJoinModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showChapterModal = false; showBadgeModal = false;" 
              class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10">
            
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                {{-- =========================================================
                     HEADER PAGE & STATUS KELAS
                     ========================================================= --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <h1 class="text-4xl font-black text-white mb-2 tracking-tight">
                            Dashboard<span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400"> Analitik</span>
                        </h1>
                        <p class="text-white/60 text-lg">Pantau pencapaian materi, XP, koleksi lencana, dan analitik Anda.</p>
                        
                        <div class="mt-6 inline-flex items-center gap-4 px-4 py-2.5 rounded-2xl bg-white/[0.02] border border-white/10 shadow-inner">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white shadow-lg text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] text-white/40 uppercase tracking-widest font-bold mb-0.5">Status Kelas</p>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ Auth::user()->class_group ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-yellow-500 shadow-[0_0_8px_#eab308] animate-pulse' }}"></span>
                                    <span class="text-sm font-bold text-white">{{ Auth::user()->class_group ?? 'Belum Terhubung ke Kelas' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-4 w-full md:w-auto">
                        <div class="hidden md:block text-right">
                            <p class="text-xs text-white/30 uppercase tracking-widest mb-1">Tanggal Hari Ini</p>
                            <p class="text-xl font-mono font-bold text-white">{{ date('d M Y') }}</p>
                        </div>
                        
                        @empty(Auth::user()->class_group)
                            <button @click="showJoinModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-[0_0_20px_rgba(99,102,241,0.4)] transition transform hover:-translate-y-0.5 border border-indigo-400 group">
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                Gabung Kelas
                            </button>
                        @else
                            <div class="px-5 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 shadow-inner flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Tergabung di Kelas</span>
                            </div>
                        @endempty
                    </div>
                </div>

                {{-- ALERT LAB AKTIF (Resume) --}}
                @if(isset($activeSession) && $activeSession)
                <div class="rounded-2xl bg-indigo-900/40 border border-indigo-500/30 p-4 flex items-center justify-between shadow-lg shadow-indigo-900/20 mb-2 animate-pulse-slow">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center animate-pulse shadow-[0_0_15px_#6366f1]">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white">Lab Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktikum' }}</h3>
                            <p class="text-indigo-200 text-xs">Aktivitas koding Anda belum diselesaikan.</p>
                        </div>
                    </div>
                    <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="px-5 py-2 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-lg text-sm transition shadow-lg hover:shadow-indigo-500/50 flex items-center gap-2">
                        Lanjut Coding <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                @endif

                {{-- =========================================================
                     GAMIFIKASI 1: LEVEL & XP PROGRESS (Top Card)
                     ========================================================= --}}
                <div class="glass-card rounded-[2rem] p-6 md:p-10 border-t-2 border-t-indigo-500/50 relative overflow-hidden flex flex-col md:flex-row items-center gap-8 shadow-2xl reveal">
                    <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px] pointer-events-none"></div>
                    
                    {{-- Developer Title (Level Badge) --}}
                    <div class="relative shrink-0 text-center">
                        <div class="w-28 h-28 rounded-full bg-[#020617] border-[4px] border-indigo-500 flex items-center justify-center flex-col shadow-[0_0_40px_rgba(99,102,241,0.3)] relative z-10 overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-cyan-500/20 animate-spin-slow"></div>
                            <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mt-2 relative z-10">Total XP</span>
                            <span class="text-2xl font-black text-white leading-none relative z-10">{{ number_format($user->xp ?? 0) }}</span>
                        </div>
                        <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-indigo-500 text-[#020617] text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full z-20 whitespace-nowrap shadow-lg">
                            {{ $user->developer_title ?? 'Intern Coder' }}
                        </div>
                    </div>

                    {{-- XP Bar --}}
                    <div class="flex-1 w-full relative z-10 mt-4 md:mt-0">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-3 gap-2">
                            <div>
                                <h3 class="text-xl font-bold text-white">Jejak Karir Developer</h3>
                                <p class="text-xs text-slate-400 mt-1">Kumpulkan XP untuk mencapai title Tailwind Architect.</p>
                            </div>
                            <div class="text-left sm:text-right">
                                <span class="inline-block px-3 py-1 rounded-md bg-indigo-500/10 border border-indigo-500/20 text-xs font-bold text-indigo-400">
                                    Next Target: {{ number_format($user->next_level_xp ?? 500) }} XP
                                </span>
                            </div>
                        </div>
                        
                        <div class="w-full h-3.5 bg-[#020617] rounded-full overflow-hidden border border-white/10 shadow-inner relative">
                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                            <div class="h-full bg-gradient-to-r from-cyan-400 via-indigo-500 to-fuchsia-500 shadow-[0_0_15px_#818cf8] transition-all duration-[2s] ease-out rounded-full" style="width: {{ $user->xp_progress ?? 0 }}%;"></div>
                        </div>
                        <div class="flex justify-between mt-3 text-[10px] font-mono text-slate-500">
                            <span>Baca Modul: +10 XP</span>
                            <span>Praktik Lab: +50 XP</span>
                            <span>Nilai Kuis: 1 XP / Point</span>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     GAMIFIKASI 2: BADGES & LEADERBOARD GRID
                     ========================================================= --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8 reveal" style="animation-delay: 0.1s;">
                    
                    {{-- A. KOTAK BADGE (LENCANA) PENCAPAIAN --}}
                    <div class="xl:col-span-2 glass-card rounded-[2rem] p-6 md:p-8 relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-fuchsia-500/20 text-fuchsia-400 flex items-center justify-center border border-fuchsia-500/30">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg>
                                </div> 
                                Digital Badges
                            </h3>
                            <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-bold text-slate-300 font-mono">
                                {{ count($unlockedBadges ?? []) }} / {{ count($allBadges ?? []) }} Terbuka
                            </span>
                        </div>
                        
                        <div class="grid grid-cols-2 sm:grid-cols-4 gap-4">
                            @forelse($allBadges ?? [] as $badge)
                                @php
                                    $isUnlocked = in_array($badge->id, $unlockedBadges ?? []);
                                    $c = $badge->color ?? 'indigo';
                                    
                                    // Serialize data badge untuk dikirim ke modal AlpineJS
                                    $badgeData = json_encode([
                                        'name' => $badge->name,
                                        'description' => $badge->description,
                                        'color' => $c,
                                        'icon' => $badge->icon,
                                        'status' => $isUnlocked ? 'Unlocked' : 'Locked'
                                    ]);
                                @endphp

                                @if($isUnlocked)
                                    {{-- BADGE TERBUKA (BERCAHAYA & BISA DIKLIK) --}}
                                    <div @click="activeBadge = {{ $badgeData }}; showBadgeModal = true" class="bg-[#0a0e17] border border-{{$c}}-500/40 p-4 rounded-2xl flex flex-col items-center text-center shadow-[0_0_20px_rgba(var(--color-{{$c}}-500),0.15)] group hover:-translate-y-1 transition cursor-pointer relative overflow-hidden">
                                        <div class="absolute inset-0 bg-gradient-to-b from-{{$c}}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>
                                        <div class="text-{{$c}}-400 mb-3 mt-2 group-hover:scale-110 transition drop-shadow-[0_0_15px_rgba(var(--color-{{$c}}-500),0.8)] relative z-10 flex justify-center w-10 h-10">
                                            {!! $badge->icon !!}
                                        </div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-{{$c}}-400 relative z-10">{{ $badge->name }}</p>
                                    </div>
                                @else
                                    {{-- BADGE TERKUNCI (GELAP & BISA DIKLIK) --}}
                                    <div @click="activeBadge = {{ $badgeData }}; showBadgeModal = true" class="bg-[#020617] border border-white/5 p-4 rounded-2xl flex flex-col items-center text-center opacity-40 grayscale hover:grayscale-[0.5] transition cursor-pointer">
                                        <div class="text-white/40 mb-3 mt-2 flex justify-center w-10 h-10">
                                            {!! $badge->icon !!}
                                        </div>
                                        <p class="text-[10px] font-bold text-white/50 mb-1">{{ $badge->name }}</p>
                                        <span class="text-[8px] text-white/30 uppercase tracking-widest font-mono bg-white/5 px-2 py-0.5 rounded">Terkunci</span>
                                    </div>
                                @endif
                            @empty
                                <div class="col-span-full py-8 text-center border-2 border-dashed border-white/10 rounded-2xl bg-white/[0.02]">
                                    <p class="text-xs text-slate-400 italic">Sistem lencana sedang dipersiapkan.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- B. KOTAK LEADERBOARD KELAS --}}
                    <div class="xl:col-span-1 glass-card rounded-[2rem] p-6 md:p-8 relative overflow-hidden border-t-2 border-t-yellow-500/50">
                        <div class="absolute right-0 top-0 w-40 h-40 bg-yellow-500/10 rounded-full blur-[60px] pointer-events-none"></div>
                        
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-lg bg-yellow-500/20 text-yellow-400 flex items-center justify-center border border-yellow-500/30 text-lg shadow-[0_0_15px_rgba(234,179,8,0.2)]">🏆</div>
                            <h3 class="text-xl font-bold text-white">Leaderboard</h3>
                        </div>
                        <p class="text-[10px] text-slate-400 mb-6 border-b border-white/5 pb-4">Top 5 Coder di kelas {{ $user->class_group ?? 'Anda' }}</p>
                        
                        <div class="space-y-3 relative z-10">
                            @forelse($leaderboard ?? [] as $index => $boardUser)
                                @php
                                    $isMe = $boardUser->id === Auth::id();
                                    $bg = 'bg-white/[0.02] border-white/5';
                                    $numColor = 'bg-[#0f141e] border border-white/10 text-white/50';
                                    $textColor = 'text-white';
                                    $xpColor = 'text-slate-400';
                                    
                                    if($index == 0) { $bg = 'bg-gradient-to-r from-yellow-500/10 to-transparent border-yellow-500/30'; $numColor = 'bg-yellow-500 text-[#020617] shadow-[0_0_10px_#eab308] border-none'; $textColor = 'text-yellow-400'; $xpColor = 'text-yellow-500'; }
                                    elseif($index == 1) { $bg = 'bg-gradient-to-r from-slate-300/10 to-transparent border-slate-300/20'; $numColor = 'bg-slate-300 text-[#020617] border-none'; }
                                    elseif($index == 2) { $bg = 'bg-gradient-to-r from-amber-700/10 to-transparent border-amber-700/20'; $numColor = 'bg-amber-600 text-white border-none'; }
                                    
                                    if($isMe) {
                                        $bg = 'bg-indigo-500/20 border-indigo-500/40 shadow-[0_0_15px_rgba(99,102,241,0.2)]';
                                        $textColor = 'text-indigo-300';
                                        $numColor = 'bg-indigo-500 text-white border-none';
                                        $xpColor = 'text-indigo-400';
                                    }
                                @endphp
                                
                                <div class="flex items-center gap-3 p-3.5 rounded-xl border {{ $bg }} transition hover:scale-[1.02]">
                                    <span class="w-7 h-7 rounded-full {{ $numColor }} flex items-center justify-center text-xs font-black shrink-0">{{ $index + 1 }}</span>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-sm font-bold {{ $textColor }} truncate">{{ $isMe ? 'Anda ('.$boardUser->name.')' : $boardUser->name }}</p>
                                    </div>
                                    <span class="text-xs font-black {{ $xpColor }} font-mono">{{ number_format($boardUser->xp) }} XP</span>
                                </div>
                            @empty
                                <div class="text-center py-8 bg-white/[0.02] rounded-xl border border-dashed border-white/5">
                                    <p class="text-xs text-slate-400 italic">Leaderboard terkunci.<br>Gabung kelas untuk bersaing.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- DIVIDER ACADEMIC --}}
                <div class="flex items-center gap-4 py-8">
                    <div class="h-px bg-white/10 flex-1"></div>
                    <span class="text-[10px] font-black text-white/40 uppercase tracking-[0.2em] bg-[#020617] px-3 py-1 border border-white/5 rounded-full">Academic Analytics</span>
                    <div class="h-px bg-white/10 flex-1"></div>
                </div>

                {{-- =========================================================
                     3. GRID STATISTIK AKADEMIK (Materi, Lab, Kuis, Bab)
                     ========================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 reveal" style="animation-delay: 0.2s;">
                    
                    {{-- CARD 1: MATERI --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-fuchsia-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showLessonModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-fuchsia-400 transition">Materi Bacaan</p>
                                <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-fuchsia-400">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-fuchsia-400 mb-1 border-b border-white/10 pb-1">Klik untuk Detail</span>
                                        Lihat insight materi yang telah Anda selesaikan.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-fuchsia-400 transition drop-shadow-[0_0_8px_rgba(217,70,239,0.3)]">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-lg">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                            @php $pctLesson = ($totalLessons > 0) ? ($lessonsCompleted / $totalLessons) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-fuchsia-500 shadow-[0_0_10px_#d946ef] transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-blue-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showLabModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-blue-400 transition">Hands-on Labs</p>
                                <div class="tooltip-container tooltip-blue tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-blue-400">?</div>
                                    <div class="tooltip-content border-blue-glow" style="border-color: rgba(59, 130, 246, 0.5);">
                                        <span class="block font-bold text-blue-400 mb-1 border-b border-white/10 pb-1">Klik untuk Detail</span>
                                        Lihat jumlah modul coding/praktikum yang berhasil lulus (KKM 70).
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-blue-400 transition drop-shadow-[0_0_8px_rgba(59,130,246,0.3)]">
                                    {{ $labsCompleted ?? 0 }}
                                </span>
                                <span class="text-white/40 font-bold text-lg">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                            @php $pctLab = ($totalLabs > 0) ? ($labsCompleted / $totalLabs) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-blue-500 shadow-[0_0_10px_#3b82f6] transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-cyan-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showQuizModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-cyan-400 transition">Rata-rata Kuis</p>
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-cyan-400">?</div>
                                    <div class="tooltip-content border-cyan-glow">
                                        <span class="block font-bold text-cyan-400 mb-1 border-b border-white/10 pb-1">Klik untuk Detail</span>
                                        Insight mengenai akumulasi nilai rata-rata Anda dari seluruh evaluasi.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-cyan-400 transition drop-shadow-[0_0_8px_rgba(34,211,238,0.3)]">
                                    {{ round($quizAverage ?? 0, 1) }}
                                </span>
                                <span class="text-white/40 font-bold text-lg">pts</span>
                            </div>
                            <p class="text-[10px] text-white/30 mt-4 font-mono">Dari {{ $quizzesCompleted ?? 0 }} evaluasi selesai.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-emerald-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showChapterModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-emerald-400 transition">Bab Lulus</p>
                                <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-emerald-400">?</div>
                                    <div class="tooltip-content border-emerald-glow">
                                        <span class="block font-bold text-emerald-400 mb-1 border-b border-white/10 pb-1">Klik untuk Detail</span>
                                        Insight mengenai progress ketuntasan bab materi secara keseluruhan.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-emerald-400 transition drop-shadow-[0_0_8px_rgba(16,185,129,0.3)]">{{ $chaptersPassed ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-lg">Bab</span>
                            </div>
                            <p class="text-[10px] text-emerald-400/50 mt-4 font-bold uppercase tracking-wider">Keep Going!</p>
                        </div>
                    </div>

                </div>

                {{-- =========================================================
                     4. BAGIAN BAWAH: CHART & LOG
                     ========================================================= --}}
                <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.3s;">
                    
                    {{-- GRAFIK --}}
                    <div class="lg:col-span-2 space-y-8">
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl shadow-lg relative overflow-hidden">
                            <div class="flex items-center justify-between mb-6 relative z-10">
                                <div>
                                    <h3 class="text-lg font-bold text-white">Grafik Perkembangan Nilai</h3>
                                    <p class="text-xs text-white/40">Visualisasi hasil evaluasi kuis terbaik Anda per bab.</p>
                                </div>
                            </div>
                            <div class="relative h-[250px] w-full z-10">
                                @if(isset($chartData['scores']) && count($chartData['scores']) > 0)
                                    <canvas id="quizChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-xl bg-white/[0.01]">
                                        <p class="text-xs font-semibold text-slate-400">Belum Ada Data Kuis</p>
                                        <p class="text-[10px] text-slate-500 mt-1">Selesaikan kuis untuk melihat perkembangan nilai Anda.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#0f141e] to-transparent pointer-events-none"></div>
                        </div>

                        {{-- TABEL HISTORY --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">🕒</span> Riwayat Pengerjaan Terakhir
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs text-white/30 uppercase tracking-widest border-b border-white/5">
                                            <th class="pb-3 pl-2">Aktivitas</th>
                                            <th class="pb-3">Waktu</th>
                                            <th class="pb-3 text-right pr-2">Skor/Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-white/70">
                                        @forelse($historyCombined as $item)
                                            <tr class="group hover:bg-white/5 transition border-b border-white/5 last:border-0">
                                                <td class="py-4 pl-2 font-medium text-white flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg
                                                        {{ $item['type'] == 'lab' ? 'bg-blue-600 shadow-blue-900/20' : 
                                                          ($item['type'] == 'quiz' ? 'bg-fuchsia-600 shadow-fuchsia-900/20' : 'bg-gray-600') }}">
                                                        {{ $item['icon'] }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span>{{ $item['name'] }}</span>
                                                        <span class="text-[10px] text-white/30 uppercase">{{ ucfirst($item['type']) }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-xs font-mono text-white/50">
                                                    {{ \Carbon\Carbon::parse($item['date'])->diffForHumans() }}
                                                </td>
                                                <td class="py-4 text-right pr-2">
                                                    @if(isset($item['score']))
                                                        <span class="px-3 py-1 rounded-full text-xs font-bold border 
                                                            {{ $item['score'] >= 70 ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-red-400 bg-red-500/10 border-red-500/20' }}">
                                                            {{ $item['score'] }} pts
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-8 text-center text-white/30 italic">Belum ada data aktivitas. Mulai belajar sekarang!</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR KANAN --}}
                    <div class="lg:col-span-1 space-y-8">
                        {{-- Heatmap --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Konsistensi Belajar</h3>
                            <div id="heatmap" class="flex flex-wrap gap-1.5 content-start min-h-[150px]"></div>
                            <div class="mt-4 flex gap-4 text-[10px] text-white/30 uppercase tracking-wider font-bold">
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-white/5"></div> 0</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-cyan-500/50"></div> 1-2</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-fuchsia-500"></div> 3+</span>
                            </div>
                        </div>

                        {{-- Log Real-time --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl h-[400px] flex flex-col relative overflow-hidden">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4 relative z-10">Live Log</h3>
                            <ul id="activityLogList" class="space-y-3 overflow-y-auto custom-scrollbar pr-2 flex-1 relative z-10">
                                <li class="text-center text-white/20 text-xs italic py-10">Memuat log aktivitas...</li>
                            </ul>
                            <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-[#0f141e] to-transparent pointer-events-none z-20"></div>
                        </div>
                    </div>

                </div>

                {{-- Footer Dashboard --}}
                <div class="border-t border-white/5 pt-8 mt-10 text-center">
                    <p class="text-white/20 text-xs">&copy; {{ date('Y') }} Utilwind CSS E-Learning</p>
                </div>

            </div>

            {{-- =========================================================================
                 HERO MODALS (INSIGHT DATA UNTUK KARTU STATISTIK & BADGES)
                 ========================================================================= --}}

            {{-- 0. Modal INFO BADGE GAMIFIKASI --}}
            <div x-show="showBadgeModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showBadgeModal = false"></div>
                
                <div class="relative w-full max-w-sm bg-[#0f141e] border rounded-3xl p-8 shadow-2xl transition-colors duration-300"
                     :class="'border-' + activeBadge?.color + '-500/40 shadow-[0_20px_70px_rgba(var(--color-' + activeBadge?.color + '-500),0.15)]'"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="showBadgeModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full blur-[40px] pointer-events-none transition-colors duration-300 opacity-20"
                             :class="'bg-' + activeBadge?.color + '-500'"></div>

                        <div class="mb-6 relative z-10 transition-colors duration-300 w-16 h-16" :class="'text-' + activeBadge?.color + '-400 drop-shadow-[0_0_15px_rgba(var(--color-' + activeBadge?.color + '-500),0.8)]'" x-html="activeBadge?.icon"></div>
                        
                        <h3 class="text-2xl font-black text-white mb-2 tracking-tight" x-text="activeBadge?.name"></h3>
                        
                        <div class="mb-6">
                            <span class="px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-widest border transition-colors duration-300"
                                  :class="activeBadge?.status === 'Unlocked' ? 'bg-' + activeBadge?.color + '-500/10 text-' + activeBadge?.color + '-400 border-' + activeBadge?.color + '-500/20' : 'bg-slate-500/10 text-slate-400 border-slate-500/20'"
                                  x-text="activeBadge?.status === 'Unlocked' ? 'Berhasil Didapatkan' : 'Lencana Terkunci'">
                            </span>
                        </div>
                        
                        <div class="bg-[#020617] w-full rounded-2xl p-5 border border-white/5 shadow-inner">
                            <p class="text-[10px] text-white/40 uppercase font-bold tracking-widest mb-2 border-b border-white/5 pb-2 text-left">Syarat Perolehan</p>
                            <p class="text-slate-300 text-sm leading-relaxed text-left" x-text="activeBadge?.description"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. Modal Insight Materi (Fuchsia) --}}
            <div x-show="showLessonModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showLessonModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-fuchsia-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(217,70,239,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <button @click="showLessonModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full bg-fuchsia-500 blur-[40px] opacity-20 pointer-events-none"></div>
                        <div class="w-16 h-16 rounded-2xl bg-fuchsia-500/10 text-fuchsia-400 flex items-center justify-center border border-fuchsia-500/30 mb-6 relative z-10 shadow-[0_0_15px_rgba(217,70,239,0.5)]">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Insight Materi</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">Materi ini mencakup teori fundamental Tailwind. Progres Anda dihitung berdasarkan jumlah halaman yang telah dibaca hingga selesai.</p>
                        <div class="bg-[#020617] w-full rounded-2xl p-6 border border-white/5 shadow-inner text-center relative z-10">
                            <span class="text-5xl font-black text-fuchsia-400">{{ $lessonsCompleted ?? 0 }}</span><span class="text-xl text-white/30 font-bold">/{{ $totalLessons ?? 0 }}</span>
                            <p class="text-[10px] text-fuchsia-400/50 uppercase tracking-widest font-bold mt-2">Materi Selesai ({{ $pctLesson ?? 0 }}%)</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. Modal Insight Lab (Blue) --}}
            <div x-show="showLabModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showLabModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-blue-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(59,130,246,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <button @click="showLabModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full bg-blue-500 blur-[40px] opacity-20 pointer-events-none"></div>
                        <div class="w-16 h-16 rounded-2xl bg-blue-500/10 text-blue-400 flex items-center justify-center border border-blue-500/30 mb-6 relative z-10 shadow-[0_0_15px_rgba(59,130,246,0.5)]">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Insight Praktikum</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">Hands-on Labs adalah sesi koding nyata di sandbox. Status "Lulus" diberikan apabila validator otomatis menilai kode Anda minimal 70.</p>
                        <div class="bg-[#020617] w-full rounded-2xl p-6 border border-white/5 shadow-inner text-center relative z-10">
                            <span class="text-5xl font-black text-blue-400">{{ $labsCompleted ?? 0 }}</span><span class="text-xl text-white/30 font-bold">/{{ $totalLabs ?? 0 }}</span>
                            <p class="text-[10px] text-blue-400/50 uppercase tracking-widest font-bold mt-2">Modul Praktikum Lulus</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 3. Modal Insight Kuis (Cyan) --}}
            <div x-show="showQuizModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showQuizModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-cyan-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(34,211,238,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <button @click="showQuizModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full bg-cyan-500 blur-[40px] opacity-20 pointer-events-none"></div>
                        <div class="w-16 h-16 rounded-2xl bg-cyan-500/10 text-cyan-400 flex items-center justify-center border border-cyan-500/30 mb-6 relative z-10 shadow-[0_0_15px_rgba(34,211,238,0.5)]">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Insight Evaluasi</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">Nilai ini adalah rata-rata akumulatif dari seluruh kuis teori Anda. Pertahankan di atas 70 untuk mempermudah pencapaian lencana.</p>
                        <div class="bg-[#020617] w-full rounded-2xl p-6 border border-white/5 shadow-inner text-center relative z-10">
                            <span class="text-5xl font-black text-cyan-400">{{ round($quizAverage ?? 0, 1) }}</span><span class="text-xl text-white/30 font-bold">pts</span>
                            <p class="text-[10px] text-cyan-400/50 uppercase tracking-widest font-bold mt-2">Dari {{ $quizzesCompleted ?? 0 }} Kuis Selesai</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 4. Modal Insight Bab Lulus (Emerald) --}}
            <div x-show="showChapterModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showChapterModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-emerald-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(16,185,129,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <button @click="showChapterModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-32 h-32 rounded-full bg-emerald-500 blur-[40px] opacity-20 pointer-events-none"></div>
                        <div class="w-16 h-16 rounded-2xl bg-emerald-500/10 text-emerald-400 flex items-center justify-center border border-emerald-500/30 mb-6 relative z-10 shadow-[0_0_15px_rgba(16,185,129,0.5)]">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <h3 class="text-2xl font-black text-white mb-2 tracking-tight">Insight Bab</h3>
                        <p class="text-slate-400 text-sm leading-relaxed mb-6">Satu bab teori dinyatakan terlewati apabila nilai akhir evaluasi kuis Anda mencapai ambang batas kelulusan (>= 70).</p>
                        <div class="bg-[#020617] w-full rounded-2xl p-6 border border-white/5 shadow-inner text-center relative z-10">
                            <span class="text-5xl font-black text-emerald-400">{{ $chaptersPassed ?? 0 }}</span><span class="text-xl text-white/30 font-bold">Bab</span>
                            <p class="text-[10px] text-emerald-400/50 uppercase tracking-widest font-bold mt-2">Total Bab Tuntas</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- JOIN CLASS MODAL --}}
            @empty(Auth::user()->class_group)
            <div x-show="showJoinModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showJoinModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            Gabung ke Kelas
                        </h3>
                        <button @click="showJoinModal = false" class="text-slate-500 hover:text-white transition p-1.5 rounded-lg bg-white/5 hover:bg-white/10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <p class="text-xs text-slate-400 mb-6 mt-2">Mintalah kode token akses (6 karakter) kepada instruktur Anda, lalu masukkan di bawah ini.</p>

                    <form action="{{ route('student.join_class') }}" method="POST" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Token Kelas <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <input type="text" name="token" required maxlength="6" style="text-transform: uppercase;" placeholder="Contoh: A7X9YM" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl px-4 py-4 text-xl font-mono tracking-[0.3em] font-bold text-white focus:ring-2 ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-700 placeholder:tracking-normal placeholder:font-sans placeholder:font-normal shadow-inner text-center">
                            </div>
                        </div>

                        {{-- Kartu Info Akun --}}
                        <div class="bg-white/[0.02] border border-white/5 rounded-xl p-4 flex items-center gap-4 shadow-inner">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white font-bold shadow-lg text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Masuk Sebagai:</p>
                                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400 font-mono truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/5 mt-6">
                            <button type="button" @click="showJoinModal = false" class="px-5 py-2.5 rounded-xl text-slate-400 hover:text-white font-bold text-xs transition border border-transparent hover:border-white/10">Batal</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] transition flex items-center gap-2 border border-indigo-400" :disabled="isSubmitting" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''">
                                <svg x-show="isSubmitting" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="isSubmitting ? 'Memverifikasi...' : 'Gabung Kelas'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endempty

        </main>
    </div>
</div>

{{-- Styles Dinamis Tailwind dari DB Badge (Dideklarasikan di CSS agar tidak di-purge oleh compiler) --}}
<style>
    /* Emerald */
    .border-emerald-500\/40 { border-color: rgba(16, 185, 129, 0.4); }
    .border-emerald-500\/20 { border-color: rgba(16, 185, 129, 0.2); }
    .from-emerald-500\/10 { --tw-gradient-from: rgba(16, 185, 129, 0.1); }
    .bg-emerald-500\/10 { background-color: rgba(16, 185, 129, 0.1); }
    .text-emerald-400 { color: rgba(52, 211, 153, 1); }
    .bg-emerald-500 { background-color: rgba(16, 185, 129, 1); }
    
    /* Blue */
    .border-blue-500\/40 { border-color: rgba(59, 130, 246, 0.4); }
    .border-blue-500\/20 { border-color: rgba(59, 130, 246, 0.2); }
    .from-blue-500\/10 { --tw-gradient-from: rgba(59, 130, 246, 0.1); }
    .bg-blue-500\/10 { background-color: rgba(59, 130, 246, 0.1); }
    .text-blue-400 { color: rgba(96, 165, 250, 1); }
    .bg-blue-500 { background-color: rgba(59, 130, 246, 1); }

    /* Indigo */
    .border-indigo-500\/40 { border-color: rgba(99, 102, 241, 0.4); }
    .border-indigo-500\/20 { border-color: rgba(99, 102, 241, 0.2); }
    .from-indigo-500\/10 { --tw-gradient-from: rgba(99, 102, 241, 0.1); }
    .bg-indigo-500\/10 { background-color: rgba(99, 102, 241, 0.1); }
    .text-indigo-400 { color: rgba(129, 140, 248, 1); }
    .bg-indigo-500 { background-color: rgba(99, 102, 241, 1); }

    /* Cyan */
    .border-cyan-500\/40 { border-color: rgba(6, 182, 212, 0.4); }
    .border-cyan-500\/20 { border-color: rgba(6, 182, 212, 0.2); }
    .from-cyan-500\/10 { --tw-gradient-from: rgba(6, 182, 212, 0.1); }
    .bg-cyan-500\/10 { background-color: rgba(6, 182, 212, 0.1); }
    .text-cyan-400 { color: rgba(34, 211, 238, 1); }
    .bg-cyan-500 { background-color: rgba(6, 182, 212, 1); }

    /* Fuchsia */
    .border-fuchsia-500\/40 { border-color: rgba(217, 70, 239, 0.4); }
    .border-fuchsia-500\/20 { border-color: rgba(217, 70, 239, 0.2); }
    .from-fuchsia-500\/10 { --tw-gradient-from: rgba(217, 70, 239, 0.1); }
    .bg-fuchsia-500\/10 { background-color: rgba(217, 70, 239, 0.1); }
    .text-fuchsia-400 { color: rgba(232, 121, 249, 1); }
    .bg-fuchsia-500 { background-color: rgba(217, 70, 239, 1); }

    /* Amber */
    .border-amber-500\/40 { border-color: rgba(245, 158, 11, 0.4); }
    .border-amber-500\/20 { border-color: rgba(245, 158, 11, 0.2); }
    .from-amber-500\/10 { --tw-gradient-from: rgba(245, 158, 11, 0.1); }
    .bg-amber-500\/10 { background-color: rgba(245, 158, 11, 0.1); }
    .text-amber-400 { color: rgba(251, 191, 36, 1); }
    .bg-amber-500 { background-color: rgba(245, 158, 11, 1); }
    
    /* Yellow */
    .border-yellow-500\/40 { border-color: rgba(234, 179, 8, 0.4); }
    .border-yellow-500\/20 { border-color: rgba(234, 179, 8, 0.2); }
    .from-yellow-500\/10 { --tw-gradient-from: rgba(234, 179, 8, 0.1); }
    .bg-yellow-500\/10 { background-color: rgba(234, 179, 8, 0.1); }
    .text-yellow-400 { color: rgba(250, 204, 21, 1); }
    .bg-yellow-500 { background-color: rgba(234, 179, 8, 1); }

    /* Rose */
    .border-rose-500\/40 { border-color: rgba(244, 63, 94, 0.4); }
    .border-rose-500\/20 { border-color: rgba(244, 63, 94, 0.2); }
    .from-rose-500\/10 { --tw-gradient-from: rgba(244, 63, 94, 0.1); }
    .bg-rose-500\/10 { background-color: rgba(244, 63, 94, 0.1); }
    .text-rose-400 { color: rgba(251, 113, 133, 1); }
    .bg-rose-500 { background-color: rgba(244, 63, 94, 1); }

    /* Slate (Default Locked) */
    .border-slate-500\/40 { border-color: rgba(100, 116, 139, 0.4); }
    .border-slate-500\/20 { border-color: rgba(100, 116, 139, 0.2); }
    .from-slate-500\/10 { --tw-gradient-from: rgba(100, 116, 139, 0.1); }
    .bg-slate-500\/10 { background-color: rgba(100, 116, 139, 0.1); }
    .text-slate-400 { color: rgba(148, 163, 184, 1); }
    .bg-slate-500 { background-color: rgba(100, 116, 139, 1); }
    
    .border-red-500\/40 { border-color: rgba(239, 68, 68, 0.4); }
    .from-red-500\/10 { --tw-gradient-from: rgba(239, 68, 68, 0.1); }
    .text-red-400 { color: rgba(248, 113, 113, 1); }

    /* CSS Umum */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* Animated BG */
    #animated-bg { background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}
    .animate-spin-slow { animation: spin 8s linear infinite; }
    @keyframes spin { 100% { transform: rotate(360deg); } }

    /* Tooltip Heatmap */
    [data-title]:hover::after { content: attr(data-title); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 4px 8px; font-size: 10px; border-radius: 4px; white-space: nowrap; pointer-events: none; z-index: 50; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 4px 6px rgba(0,0,0,0.3); }

    /* Animation Fade Up */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    
    [x-cloak] { display: none !important; }

    /* SISTEM TOOLTIP SUPER SOLID */
    .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
    .tooltip-container:hover { z-index: 99999; }
    .tooltip-trigger { 
        width: 18px; height: 18px; border-radius: 50%; color: white; 
        font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; 
        cursor: help; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.2);
    }
    .tooltip-trigger:hover { transform: scale(1.15); }
    .tooltip-content { 
        opacity: 0; visibility: hidden; position: absolute; pointer-events: none; 
        width: max-content; min-width: 200px; max-width: 280px; white-space: normal; text-align: left; 
        background-color: #020617; 
        color: #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5;
        border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,1); z-index: 99999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }
    .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
    .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
    .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #020617 transparent; }
    .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
    .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
    .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

    .tooltip-blue .tooltip-trigger { background-color: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.5); }
    .tooltip-blue .tooltip-trigger:hover { background-color: #60a5fa; box-shadow: 0 0 15px rgba(59,130,246,0.8); }
    
    .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); }
    .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); }
    .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }

    .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
    .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); }
    .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }

    .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
    .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); }
    .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }
    .glass-card { background: rgba(10, 14, 23, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(16px); }
</style>

{{-- Script SweetAlert untuk Menangkap Response dari Controller --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#10b981' });
    });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: '#0f141e', color: '#fff', iconColor: '#ef4444' });
    });
</script>
@endif
@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#3b82f6' });
    });
</script>
@endif

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. CHART.JS CONFIGURATION
        const ctx = document.getElementById('quizChart')?.getContext('2d');
        if(ctx && {!! json_encode($chartData['scores'] ?? []) !!}.length > 0) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(232, 121, 249, 0.5)'); 
            gradient.addColorStop(1, 'rgba(232, 121, 249, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels'] ?? []) !!}, 
                    datasets: [{
                        label: 'Nilai Evaluasi Terakhir',
                        data: {!! json_encode($chartData['scores'] ?? []) !!},
                        borderColor: '#e879f9', 
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#020617',
                        pointBorderColor: '#fff',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 20, 30, 0.9)',
                            titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)', font: { family: 'monospace' } } },
                        y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)' } }
                    }
                }
            });
        }

        // 2. FETCH REAL-TIME DATA
        fetchDashboardData();
    });

    async function fetchDashboardData() {
        try {
            const response = await fetch("{{ route('api.dashboard.progress') }}", { headers: { 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('API Error');
            const data = await response.json();
            
            renderHeatmap(data.activity_timeline || []);
            renderActivityLog(data.activity_log || []);
        } catch (error) { 
            console.error("Sync Error:", error);
            document.getElementById('activityLogList').innerHTML = `<li class="text-center text-red-400/50 text-xs italic py-4">Gagal sinkronisasi data log harian.</li>`;
        }
    }

    function renderHeatmap(timeline) {
        const el = document.getElementById('heatmap');
        if(!el) return;
        el.innerHTML = '';
        const map = {}; timeline.forEach(t => map[t.date] = t.count);
        
        for(let i=83; i>=0; i--) {
            const d = new Date(); d.setDate(d.getDate()-i);
            const k = d.toISOString().split('T')[0];
            const v = map[k]||0;
            
            let c = 'bg-white/5'; 
            if(v>=1) c='bg-cyan-500/40 shadow-[0_0_5px_#22d3ee]'; 
            if(v>=3) c='bg-fuchsia-500 shadow-[0_0_8px_#d946ef]'; 
            
            const div = document.createElement('div');
            div.className = `w-2.5 h-2.5 rounded-[2px] ${c} relative cursor-pointer hover:scale-150 transition hover:z-20 hover:border hover:border-white`;
            div.setAttribute('data-title', `${k}: ${v} Aktivitas`);
            el.appendChild(div);
        }
    }

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList');
        if(!list) return;
        list.innerHTML = '';
        
        if (logs.length === 0) { 
            list.innerHTML = `<li class="text-white/30 text-center text-xs italic py-10">Belum ada aktivitas hari ini. Mulai belajar sekarang!</li>`; 
            return; 
        }
        
        logs.forEach((item, index) => {
            let icon = '✓';
            let iconBg = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            
            if (item.type === 'Kuis') { icon = '📝'; iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20'; }
            if (item.type === 'Lab')  { icon = '💻'; iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20'; }

            const delay = index * 100;

            list.insertAdjacentHTML('beforeend', `
                <li class="group flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] hover:bg-white/[0.05] transition border border-white/5 animate-fade-in-up" style="animation-delay: ${delay}ms">
                    <div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0 font-bold text-xs shadow-inner">
                        ${icon}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-0.5">
                            <h4 class="text-xs font-bold text-white truncate w-24" title="${item.activity}">${item.activity}</h4>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded ${item.status === 'Lulus' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400'}">
                                ${item.status}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-white/30 font-mono">${item.time}</span>
                        </div>
                    </div>
                </li>
            `);
        });
    }
</script>
@endsection