@extends('layouts.landing')

@section('title', 'Dashboard Akademik')

@section('content')

{{-- ==============================================================================
     LOGIKA QUERY (Data Presisi & Waktu Jelas)
     ============================================================================== --}}
@php
    $userId = Auth::id();
    
    // Kalkulasi Progress Total
    $totalTasks = ($totalLessons ?? 0) + ($totalLabs ?? 0);
    $completedTasks = ($lessonsCompleted ?? 0) + ($labsCompleted ?? 0);
    $overallProgress = $totalTasks > 0 ? round(($completedTasks / $totalTasks) * 100) : 0;

    $pctLesson = ($totalLessons > 0) ? round(($lessonsCompleted / $totalLessons) * 100) : 0; 
    $pctLab = ($totalLabs > 0) ? round(($labsCompleted / $totalLabs) * 100) : 0;

    // 1. DATA KUIS
    $allQuizzes = \App\Models\QuizAttempt::where('user_id', $userId)->whereNotNull('completed_at')->latest('completed_at')->get()
        ->map(fn($q) => [
            'name' => 'Evaluasi Teori: Bab ' . $q->chapter_id, 
            'type' => 'kuis', 
            'score' => $q->score, 
            'date' => $q->completed_at, 
            'full_date' => \Carbon\Carbon::parse($q->completed_at)->format('d M Y, H:i'), // Format presisi
            'time' => \Carbon\Carbon::parse($q->completed_at)->diffForHumans(), // Format relatif
            'status' => $q->score >= 70 ? 'Lulus' : 'Remedial'
        ]);
    
    // 2. DATA LAB (Menarik Judul Lab)
    $allLabs = \App\Models\LabHistory::where('user_id', $userId)->whereIn('status', ['passed', 'failed', 'completed'])->with('lab')->latest('updated_at')->get()
        ->map(fn($l) => [
            'name' => 'Praktik Lab: ' . ($l->lab->title ?? 'Modul ' . $l->lab_id), 
            'type' => 'lab', 
            'score' => $l->final_score, 
            'date' => $l->updated_at, 
            'full_date' => \Carbon\Carbon::parse($l->updated_at)->format('d M Y, H:i'),
            'time' => \Carbon\Carbon::parse($l->updated_at)->diffForHumans(),
            'status' => $l->final_score >= 70 ? 'Lulus' : 'Remedial'
        ]);
    
    // 3. DATA MATERI (Menarik Judul Materi Asli)
    $allLessons = \App\Models\UserLessonProgress::where('user_id', $userId)->where('completed', true)->with('lesson')->latest('updated_at')->get()
        ->map(fn($m) => [
            'name' => 'Materi Bacaan: ' . ($m->lesson->title ?? 'Modul ' . $m->lesson_id), 
            'type' => 'materi', 
            'date' => $m->updated_at, 
            'full_date' => \Carbon\Carbon::parse($m->updated_at)->format('d M Y, H:i'),
            'time' => \Carbon\Carbon::parse($m->updated_at)->diffForHumans(),
            'status' => 'Tuntas'
        ]);

    // Data Gabungan
    $historyCombined = collect($allQuizzes)->merge($allLabs)->sortByDesc('date')->values();
    $liveLogData = collect($allLessons)->merge($allQuizzes)->merge($allLabs)->sortByDesc('date')->values()->take(25); // Ambil 25 Log Terakhir
@endphp

<div id="appRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 font-sans overflow-hidden flex flex-col selection:bg-cyan-500/30 selection:text-cyan-900 dark:selection:text-white pt-16 md:pt-20 transition-colors duration-500">

    {{-- ======================================================================
         1. BACKGROUND EFFECTS
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40 transition-colors duration-500"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-indigo-300/30 dark:bg-indigo-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-500"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-500"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] mix-blend-overlay transition-opacity duration-500"></div>
    </div>

    @include('layouts.partials.navbar')
    
    {{-- WRAPPER UTAMA DENGAN ALPINEJS --}}
    <div class="flex flex-1 overflow-hidden relative" 
         x-data="{ 
            sidebarOpen: false, showJoinModal: false, showLessonModal: false,
            showLabModal: false, showQuizModal: false, showChapterModal: false
         }"
         @keydown.escape.window="sidebarOpen = false; showJoinModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showChapterModal = false;">

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        {{-- ======================================================================
             SIDEBAR MENU
             ====================================================================== --}}
        <aside class="w-[280px] bg-white/90 dark:bg-[#050912]/90 backdrop-blur-2xl border-r border-slate-200 dark:border-white/5 flex flex-col shrink-0 z-[100] absolute lg:relative inset-y-0 left-0 h-full transition-transform duration-300 transform lg:translate-x-0 shadow-2xl lg:shadow-none" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            
            <button @click="sidebarOpen = false" class="lg:hidden absolute top-4 right-4 p-2 bg-slate-100 dark:bg-white/5 rounded-xl text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-white/10 transition-colors z-50">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <div class="p-6 pt-8 lg:pt-6 overflow-y-auto custom-scrollbar flex-1 relative z-10">
                <p class="text-[10px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-4 pl-2 transition-colors">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white font-bold shadow-sm dark:shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-[1.02]">
                        <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-400 group-hover:scale-110 transition-transform drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(6,182,212,0.5)]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" /></svg>
                        Overview
                    </a>
                    
                    @php $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group)); @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                            <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:text-white/40 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                            Materi Belajar
                        </a>
                    @else
                        <div class="w-full group flex items-center justify-between px-4 py-3 rounded-xl bg-slate-50 dark:bg-white/[0.02] text-slate-400 dark:text-white/30 cursor-not-allowed border border-slate-200 dark:border-white/5 transition-colors relative overflow-hidden" title="Anda belum bergabung di kelas manapun">
                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
                            <div class="flex items-center gap-3 relative z-10">
                                <svg class="w-5 h-5 opacity-50" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                <span class="font-medium text-sm">Materi Belajar</span>
                            </div>
                            <svg class="w-4 h-4 opacity-50 relative z-10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </div>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:text-white/40 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                        Pengaturan
                    </a>
                    
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                        <svg class="w-5 h-5 text-slate-400 group-hover:text-slate-700 dark:text-white/40 dark:group-hover:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        Informasi
                    </a>
                </nav>
            </div>

            {{-- Kartu Profil User Bawah --}}
            <div class="mt-auto p-5 shrink-0 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-white/[0.02] transition-colors relative z-10">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center text-white font-bold shadow-md shrink-0 border-2 border-white dark:border-[#0f141e] transition-colors">
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ substr(Auth::user()->name, 0, 1) }}
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors font-mono">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-xl bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 font-bold text-xs hover:bg-red-100 dark:hover:bg-red-500/20 transition-colors border border-red-100 dark:border-red-500/20 shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar Akun
                    </button>
                </form>
            </div>
        </aside>

        {{-- MAIN CONTENT (Scrollable Area) --}}
        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                {{-- TOMBOL HAMBURGER MOBILE --}}
                <div class="flex items-center gap-4 mb-2 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 bg-white dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-100 dark:hover:bg-white/10 transition border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="text-sm font-bold text-slate-500 dark:text-white uppercase tracking-widest opacity-50 transition-colors">Menu Dasbor</span>
                </div>

                 {{-- =========================================================
                     1. HERO SECTION & OVERALL PROGRESS
                     ========================================================= --}}
                <div class="flex flex-col xl:flex-row justify-between items-start gap-8">
                    <div class="flex-1">
                        {{-- BREADCRUMB --}}
                        <nav class="flex items-center gap-2 mb-4 text-[10px] md:text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-white/40 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Home
                            </a>
                            <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                            <span class="text-cyan-600 dark:text-cyan-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(34,211,238,0.5)] transition-colors">Dashboard Akademik</span>
                        </nav>

                        <h1 class="text-4xl lg:text-5xl font-black text-slate-900 dark:text-white mb-3 tracking-tight transition-colors reveal-up">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-600 to-indigo-600 dark:from-cyan-400 dark:to-indigo-400">Dashboard</span> 
                        </h1>
                        <p class="text-slate-600 dark:text-white/60 text-base lg:text-lg transition-colors max-w-2xl reveal-up delay-100">Pantau pencapaian materi, hasil evaluasi, dan analitik kinerja belajar.</p>
                        
                        <div class="mt-6 inline-flex items-center gap-4 px-4 py-2.5 rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-inner w-full md:w-auto transition-colors reveal-up delay-200">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-500 flex items-center justify-center font-bold text-white shadow-lg text-lg shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold mb-0.5 transition-colors">Status Kelas</p>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full shrink-0 {{ Auth::user()->class_group ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-yellow-500 shadow-[0_0_8px_#eab308] animate-pulse' }}"></span>
                                    <span class="text-sm font-bold text-slate-800 dark:text-white truncate transition-colors">{{ Auth::user()->class_group ?? 'Belum Terhubung ke Kelas' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    {{-- OVERALL ACADEMIC PROGRESS CARD --}}
                    <div class="w-full xl:w-96 glass-card rounded-3xl p-6 md:p-8 relative group hover:border-cyan-400 dark:hover:border-cyan-500/30 transition-colors duration-500 shrink-0 reveal-up delay-100 shadow-md dark:shadow-xl">
                        <div class="absolute inset-0 rounded-3xl overflow-hidden pointer-events-none">
                            <div class="absolute -right-12 -top-12 w-40 h-40 bg-cyan-400/20 dark:bg-cyan-500/10 rounded-full blur-[40px] group-hover:bg-cyan-400/30 dark:group-hover:bg-cyan-500/20 transition-colors"></div>
                        </div>
                        
                        <div class="flex justify-between items-end mb-4 relative z-10">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="text-[10px] uppercase tracking-widest text-slate-500 dark:text-white/40 font-bold transition-colors">Total Progress</p>
                                    <div class="tooltip-container tooltip-blue tooltip-down">
                                        <div class="tooltip-trigger">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                        </div>
                                        <div class="tooltip-content">Kalkulasi dari persentase penyelesaian seluruh <b>Materi Bacaan</b> dan <b>Modul Lab Praktikum</b> yang wajib diselesaikan.</div>
                                    </div>
                                </div>
                                <h3 class="text-4xl font-black text-slate-900 dark:text-white tracking-tight transition-colors"><span class="counter-value">{{ $overallProgress }}</span><span class="text-xl text-slate-400 dark:text-slate-500">%</span></h3>
                            </div>
                            <div class="text-right">
                                <svg class="w-8 h-8 text-cyan-500 opacity-80" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            </div>
                        </div>

                        <div class="w-full h-2.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden border border-slate-300 dark:border-white/5 relative z-10 transition-colors">
                            <div class="h-full bg-gradient-to-r from-cyan-400 to-blue-500 dark:from-cyan-500 dark:to-blue-600 shadow-none dark:shadow-[0_0_10px_#06b6d4] transition-all duration-1000 relative" style="width: {{ $overallProgress }}%">
                                <div class="absolute inset-0 bg-white/30 animate-[shimmer_2s_infinite]"></div>
                            </div>
                        </div>
                        <div class="flex justify-between items-center mt-3 text-[10px] font-bold text-slate-500 dark:text-white/40 relative z-10 transition-colors">
                            <span>0%</span>
                            <span><span class="counter-value">{{ $completedTasks }}</span> / {{ $totalTasks }} Modul</span>
                            <span>100%</span>
                        </div>
                    </div>
                </div>

                {{-- ACTION BAR: GABUNG KELAS ATAU ALERT LAB --}}
                <div class="flex flex-col md:flex-row items-center gap-4 reveal-up delay-200">
                    @empty(Auth::user()->class_group)
                        <button @click="showJoinModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white text-sm font-bold shadow-md dark:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition transform hover:-translate-y-0.5 border border-indigo-500 dark:border-indigo-400 group">
                            <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                            Gabung Kelas Sekarang
                        </button>
                    @endempty

                    @if(isset($activeSession) && $activeSession)
                        <div class="w-full rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-200 dark:border-indigo-500/30 p-4 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 shadow-sm dark:shadow-lg dark:shadow-indigo-900/20 animate-pulse-slow transition-colors">
                            <div class="flex items-center gap-4">
                                <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center animate-pulse shadow-md dark:shadow-[0_0_15px_#6366f1] shrink-0">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                <div>
                                    <h3 class="font-bold text-indigo-900 dark:text-white transition-colors">Lab Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktikum' }}</h3>
                                    <p class="text-indigo-600 dark:text-indigo-300 text-xs transition-colors">Sesi ngoding Anda masih menggantung. Lanjutkan sekarang!</p>
                                </div>
                            </div>
                            <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="w-full sm:w-auto px-5 py-2.5 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white text-center font-bold rounded-lg text-sm transition shadow-md hover:shadow-lg dark:hover:shadow-indigo-500/50 flex items-center justify-center gap-2 shrink-0">
                                Lanjut Coding <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                    @endif
                </div>

                {{-- VISUAL SEPARATOR --}}
                <div class="flex items-center gap-4 py-4 reveal-up delay-300">
                    <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] bg-slate-50 dark:bg-[#020617] px-3 py-1 border border-slate-200 dark:border-white/5 rounded-full whitespace-nowrap transition-colors">Analitik Akademik</span>
                    <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                </div>

                {{-- =========================================================
                     2. GRID STATISTIK AKADEMIK (ULTIMATE CARDS)
                     ========================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 reveal-up delay-300">
                    
                    {{-- CARD 1: MATERI --}}
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-3xl p-6 relative flex flex-col justify-between border border-slate-200 dark:border-white/10 hover:border-fuchsia-400 dark:hover:border-fuchsia-500/40 shadow-sm hover:shadow-xl dark:shadow-none dark:hover:shadow-lg transition-all duration-300 cursor-pointer" @click="showLessonModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-50 dark:from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none rounded-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center border border-fuchsia-200 dark:border-fuchsia-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">Materi Bacaan</p>
                                </div>
                                <div class="tooltip-container tooltip-fuchsia tooltip-down" @click.stop>
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Menghitung jumlah halaman/slide materi teori yang telah Anda baca hingga tuntas.</div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-4">
                                <span class="text-4xl font-black text-slate-900 dark:text-white group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors counter-value">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-white/40 font-bold text-sm transition-colors">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-white/5 rounded-full mt-4 overflow-hidden border border-slate-200 dark:border-white/5 relative z-10 transition-colors">
                            <div class="h-full bg-fuchsia-500 shadow-none dark:shadow-[0_0_10px_#d946ef] transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-3xl p-6 relative flex flex-col justify-between border border-slate-200 dark:border-white/10 hover:border-blue-400 dark:hover:border-blue-500/40 shadow-sm hover:shadow-xl dark:shadow-none dark:hover:shadow-lg transition-all duration-300 cursor-pointer" @click="showLabModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 dark:from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none rounded-3xl"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <div class="w-8 h-8 rounded-lg bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 flex items-center justify-center border border-blue-200 dark:border-blue-500/20 transition-colors">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Hands-on Labs</p>
                                </div>
                                <div class="tooltip-container tooltip-blue tooltip-down" @click.stop>
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Praktikum koding yang berhasil diselesaikan dengan nilai kelulusan minimal 70.</div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1 mt-4">
                                <span class="text-4xl font-black text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors counter-value">{{ $labsCompleted ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-white/40 font-bold text-sm transition-colors">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                        </div>
                        <div class="w-full h-1.5 bg-slate-100 dark:bg-white/5 rounded-full mt-4 overflow-hidden border border-slate-200 dark:border-white/5 relative z-10 transition-colors">
                            <div class="h-full bg-blue-500 shadow-none dark:shadow-[0_0_10px_#3b82f6] transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-3xl p-6 relative flex flex-col justify-between border border-slate-200 dark:border-white/10 hover:border-cyan-400 dark:hover:border-cyan-500/40 shadow-sm hover:shadow-xl dark:shadow-none dark:hover:shadow-lg transition-all duration-300 cursor-pointer" @click="showQuizModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-50 dark:from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none rounded-3xl"></div>
                        <div class="relative z-10 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 flex items-center justify-center border border-cyan-200 dark:border-cyan-500/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Rata-rata Kuis</p>
                                    </div>
                                    <div class="tooltip-container tooltip-cyan tooltip-left" @click.stop>
                                        <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                        <div class="tooltip-content">Nilai rata-rata dari seluruh percobaan evaluasi teori (Kuis) yang pernah Anda ikuti.</div>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-1 mt-4">
                                    <span class="text-4xl font-black text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors counter-value">{{ round($quizAverage ?? 0, 1) }}</span>
                                    <span class="text-slate-400 dark:text-white/40 font-bold text-sm transition-colors">pts</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-slate-400 dark:text-white/40 mt-4 font-mono transition-colors border-t border-slate-100 dark:border-white/5 pt-3">Dari <span class="counter-value">{{ $quizzesCompleted ?? 0 }}</span> evaluasi.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="academic-card group bg-white dark:bg-[#0f141e] rounded-3xl p-6 relative flex flex-col justify-between border border-slate-200 dark:border-white/10 hover:border-emerald-400 dark:hover:border-emerald-500/40 shadow-sm hover:shadow-xl dark:shadow-none dark:hover:shadow-lg transition-all duration-300 cursor-pointer" @click="showChapterModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-50 dark:from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none rounded-3xl"></div>
                        <div class="relative z-10 h-full flex flex-col justify-between">
                            <div>
                                <div class="flex justify-between items-start mb-2">
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 flex items-center justify-center border border-emerald-200 dark:border-emerald-500/20 transition-colors">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        </div>
                                        <p class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Bab Lulus</p>
                                    </div>
                                    <div class="tooltip-container tooltip-emerald tooltip-left" @click.stop>
                                        <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                        <div class="tooltip-content">Jumlah bab teori yang berhasil diselesaikan dengan nilai kuis akhir minimal 70.</div>
                                    </div>
                                </div>
                                <div class="flex items-baseline gap-1 mt-4">
                                    <span class="text-4xl font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors counter-value">{{ $chaptersPassed ?? 0 }}</span>
                                    <span class="text-slate-400 dark:text-white/40 font-bold text-sm transition-colors">Bab</span>
                                </div>
                            </div>
                            <p class="text-[10px] text-emerald-600/80 dark:text-emerald-400/70 mt-4 font-bold uppercase tracking-wider transition-colors border-t border-slate-100 dark:border-white/5 pt-3 group-hover:tracking-widest duration-300">Keep Going! 🚀</p>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     3. CHART & LOGS PURE ACADEMIC
                     ========================================================= --}}
                <div class="grid lg:grid-cols-3 gap-6 md:gap-8 reveal-up delay-400 mt-6">
                    
                    {{-- KIRI: GRAFIK & TABEL (2 Kolom) --}}
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        
                        {{-- GRAFIK KUIS --}}
                        <div class="academic-card rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl shadow-sm dark:shadow-lg relative transition-colors duration-500">
                            <div class="flex justify-between items-start mb-6 relative z-10">
                                <div>
                                    <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white transition-colors">Grafik Perkembangan Nilai</h3>
                                    <p class="text-[10px] md:text-xs text-slate-500 dark:text-white/40 mt-0.5 transition-colors">Visualisasi nilai tertinggi yang Anda capai pada setiap evaluasi bab.</p>
                                </div>
                                <div class="tooltip-container tooltip-cyan tooltip-left z-50">
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Grafik ini mencatat skor <b>tertinggi</b> yang Anda peroleh jika Anda melakukan pengulangan (remedial) pada suatu kuis bab.</div>
                                </div>
                            </div>
                            <div class="relative h-[200px] md:h-[250px] w-full z-10">
                                @if(isset($chartData['scores']) && count($chartData['scores']) > 0)
                                    <canvas id="quizChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-xl bg-slate-50 dark:bg-white/[0.01] transition-colors">
                                        <svg class="w-8 h-8 text-slate-300 dark:text-white/20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                        <p class="text-xs font-semibold text-slate-400 dark:text-white/40 mt-2">Belum Ada Data Kuis Tersedia</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TABEL HISTORY EVALUASI --}}
                        <div class="rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl flex flex-col h-[450px] shadow-sm dark:shadow-lg transition-colors duration-500 relative" x-data="{ filterTable: 'all' }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 md:mb-6 shrink-0 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors relative z-10">
                                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Riwayat Evaluasi
                                </h3>
                                
                                {{-- Filter Interaktif AlpineJS --}}
                                <div class="flex items-center bg-slate-100 dark:bg-[#020617] p-1 rounded-lg border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                                    <button @click="filterTable = 'all'" :class="filterTable === 'all' ? 'bg-cyan-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Semua</button>
                                    <button @click="filterTable = 'kuis'" :class="filterTable === 'kuis' ? 'bg-fuchsia-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Kuis</button>
                                    <button @click="filterTable = 'lab'" :class="filterTable === 'lab' ? 'bg-blue-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Lab</button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto custom-scrollbar -mx-6 md:mx-0 px-6 md:px-0 flex-1 relative z-10">
                                <div class="absolute top-0 bottom-0 right-0 w-4 bg-gradient-to-l from-white dark:from-[#0f141e] to-transparent pointer-events-none md:hidden z-30 transition-colors duration-500"></div>
                                <div class="max-h-[300px] overflow-y-auto custom-scrollbar pr-2 h-full pb-10">
                                    <table class="w-full text-left border-collapse min-w-[400px] relative">
                                        <thead class="sticky top-0 z-20 bg-white dark:bg-[#0f141e] shadow-sm dark:shadow-md after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-slate-200 dark:after:bg-white/10 transition-colors duration-500">
                                            <tr class="text-[10px] md:text-xs text-slate-500 dark:text-white/30 uppercase tracking-widest transition-colors">
                                                <th class="py-3 pl-2">Aktivitas Ujian</th>
                                                <th class="py-3 hidden sm:table-cell">Waktu Submit</th>
                                                <th class="py-3 text-right pr-2">Skor Akhir</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs md:text-sm text-slate-700 dark:text-white/70 transition-colors">
                                            @forelse(collect($historyCombined)->whereIn('type', ['kuis', 'lab', 'quiz']) as $item)
                                                @php
                                                    $typeLower = strtolower($item['type']);
                                                    $typeLabel = 'Aktivitas';
                                                    $typeColor = 'text-slate-500 dark:text-slate-400';
                                                    $iconBg = 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/50 border-slate-200 dark:border-white/10';
                                                    $icon = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>';

                                                    if ($typeLower == 'kuis' || $typeLower == 'quiz') {
                                                        $typeLabel = 'Evaluasi Teori';
                                                        $typeColor = 'text-fuchsia-600 dark:text-fuchsia-400';
                                                        $iconBg = 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-200 dark:border-fuchsia-500/20';
                                                        $icon = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>';
                                                    } elseif ($typeLower == 'lab') {
                                                        $typeLabel = 'Praktik Lab';
                                                        $typeColor = 'text-blue-600 dark:text-blue-400';
                                                        $iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20';
                                                        $icon = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>';
                                                    }
                                                @endphp
                                                <tr x-show="filterTable === 'all' || filterTable === '{{ $typeLower === 'quiz' ? 'kuis' : $typeLower }}'" class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-colors border-b border-slate-100 dark:border-white/5 last:border-0" x-transition>
                                                    <td class="py-3 md:py-4 pl-2 font-medium text-slate-800 dark:text-white flex items-center gap-3 transition-colors">
                                                        <div class="w-6 h-6 md:w-8 md:h-8 rounded flex items-center justify-center text-[10px] md:text-xs font-bold shadow-sm dark:shadow-lg shrink-0 border {{ $iconBg }} transition-colors">
                                                            {!! $icon !!}
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="line-clamp-2 text-xs font-bold text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors leading-snug pr-2" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                                                            <div class="flex items-center gap-1.5 mt-1">
                                                                <span class="text-[9px] md:text-[10px] uppercase font-bold tracking-wider {{ $typeColor }} transition-colors">{{ $typeLabel }}</span>
                                                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/20 sm:hidden transition-colors"></span>
                                                                <span class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/40 font-mono sm:hidden transition-colors">{{ $item['time'] ?? '' }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 md:py-4 hidden sm:table-cell transition-colors">
                                                        <p class="text-[10px] md:text-[11px] font-mono text-slate-800 dark:text-white/90">{{ $item['full_date'] ?? '' }}</p>
                                                        <p class="text-[9px] text-slate-500 dark:text-white/40 mt-0.5">{{ $item['time'] ?? '' }}</p>
                                                    </td>
                                                    <td class="py-3 md:py-4 text-right pr-2 shrink-0">
                                                        @if(isset($item['score']))
                                                            <span class="px-2 md:px-3 py-1 rounded-full text-[10px] font-bold border transition-colors {{ $item['score'] >= 70 ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' : 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-500/10 border-red-200 dark:border-red-500/20' }}">{{ $item['score'] }} pts</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="py-6 text-center text-slate-400 dark:text-white/30 italic text-xs transition-colors">Belum ada riwayat pengerjaan kuis atau lab.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- KANAN: AKTIVITAS PIE CHART & LIVE LOG (1 Kolom) --}}
                    <div class="lg:col-span-1 space-y-6 md:space-y-8">
                        
                        {{-- Komposisi Aktivitas --}}
                        <div class="academic-card rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl shadow-sm dark:shadow-none transition-colors duration-500 relative">
                            <div class="flex justify-between items-start mb-4 relative z-10">
                                <h3 class="text-xs md:text-sm font-bold text-slate-700 dark:text-white/70 uppercase tracking-wider transition-colors flex items-center gap-2">
                                    <svg class="w-5 h-5 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                                    Komposisi Aktivitas
                                </h3>
                                <div class="tooltip-container tooltip-blue tooltip-left z-50">
                                    <div class="tooltip-trigger"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg></div>
                                    <div class="tooltip-content">Persentase distribusi aktivitas belajar Anda berdasarkan penyelesaian materi, praktik lab, dan evaluasi kuis.</div>
                                </div>
                            </div>
                            <div class="relative h-[180px] w-full flex justify-center relative z-10">
                                @if(($lessonsCompleted ?? 0) > 0 || ($labsCompleted ?? 0) > 0 || ($quizzesCompleted ?? 0) > 0)
                                    <canvas id="activityPieChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-xl bg-slate-50 dark:bg-white/[0.01] transition-colors">
                                        <p class="text-xs font-semibold text-slate-400 dark:text-white/40">Mulai belajar untuk melihat data.</p>
                                    </div>
                                @endif
                            </div>
                            <div class="mt-4 grid grid-cols-3 gap-2 text-center border-t border-slate-200 dark:border-white/5 pt-4 relative z-10">
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold">Materi</p>
                                    <p class="text-xs font-black text-fuchsia-500 counter-value">{{ $lessonsCompleted ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold">Lab</p>
                                    <p class="text-xs font-black text-blue-500 counter-value">{{ $labsCompleted ?? 0 }}</p>
                                </div>
                                <div>
                                    <p class="text-[9px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold">Kuis</p>
                                    <p class="text-xs font-black text-cyan-500 counter-value">{{ $quizzesCompleted ?? 0 }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Log Real-time Aktivitas Keseluruhan --}}
                        <div class="rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl h-[450px] flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors duration-500" x-data="{ logFilter: 'all' }">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-4 relative z-10 border-b border-slate-200 dark:border-white/5 pb-4 shrink-0 gap-4 transition-colors">
                                <div>
                                    <h3 class="text-xs md:text-sm font-bold text-slate-700 dark:text-white/70 uppercase tracking-wider mb-2 transition-colors flex items-center gap-2">
                                        <svg class="w-4 h-4 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                                        Log Terkini
                                    </h3>
                                    <div class="flex items-center bg-slate-100 dark:bg-[#020617] p-1 rounded-lg border border-slate-200 dark:border-white/5 shadow-inner w-max transition-colors">
                                        <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-cyan-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Semua</button>
                                        <button @click="logFilter = 'materi'" :class="logFilter === 'materi' ? 'bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Materi</button>
                                        <button @click="logFilter = 'kuis'" :class="logFilter === 'kuis' ? 'bg-fuchsia-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Kuis</button>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right" id="liveLogInsight">
                                    {{-- Total Aktivitas di-render via JS --}}
                                </div>
                            </div>

                            <ul id="activityLogList" class="space-y-2 md:space-y-3 overflow-y-auto custom-scrollbar pr-2 flex-1 relative z-10 pb-10">
                                {{-- Render via JS --}}
                            </ul>
                            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-white dark:from-[#0f141e] to-transparent pointer-events-none z-20 transition-colors duration-500"></div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-slate-200 dark:border-white/5 pt-6 md:pt-8 mt-8 md:mt-10 text-center transition-colors">
                    <p class="text-slate-500 dark:text-white/20 text-[10px] md:text-xs">&copy; {{ date('Y') }} Utilwind CSS Academic Platform</p>
                </div>
            </div>

            {{-- =========================================================================
                 MODAL INSIGHT ANALITIK (DARI CONTROLLER)
                 ========================================================================= --}}

            {{-- 1. Modal Insight Materi --}}
            <div x-show="showLessonModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showLessonModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(217,70,239,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-fuchsia-50 dark:bg-fuchsia-500/20 text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <button @click="showLessonModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Detail Materi Bacaan</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Statistik ini menghitung jumlah modul / halaman teori yang telah Anda selesaikan dari keseluruhan materi kurikulum.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-fuchsia-600 dark:text-fuchsia-400 transition-colors counter-modal">{{ $lessonsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-slate-400 dark:text-white/30 font-bold transition-colors">/ {{ $totalLessons ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-fuchsia-600/70 dark:text-fuchsia-400/50 uppercase tracking-widest font-bold mt-2 transition-colors">Tingkat Penyelesaian ({{ $pctLesson ?? 0 }}%)</p>
                    </div>
                </div>
            </div>

            {{-- 2. Modal Insight Lab --}}
            <div x-show="showLabModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showLabModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-blue-200 dark:border-blue-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(59,130,246,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <button @click="showLabModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Detail Hands-on Labs</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Sebuah lab dinyatakan <span class="text-emerald-600 dark:text-emerald-400 font-bold">Lulus (Passed)</span> secara sistem jika validasi kode Anda mencapai KKM minimal <b>70</b>.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-blue-600 dark:text-blue-400 transition-colors counter-modal">{{ $labsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-slate-400 dark:text-white/30 font-bold transition-colors">/ {{ $totalLabs ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-blue-600/70 dark:text-blue-400/50 uppercase tracking-widest font-bold mt-2 transition-colors">Modul Praktikum Lulus</p>
                    </div>
                </div>
            </div>

            {{-- 3. Modal Insight Kuis --}}
            <div x-show="showQuizModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showQuizModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(34,211,238,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-cyan-50 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <button @click="showQuizModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Rincian Rata-rata Kuis</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Ini adalah akumulasi total nilai dibagi dengan frekuensi pengerjaan Anda. Anda telah melakukan submit evaluasi sebanyak <b>{{ $quizzesCompleted ?? 0 }} kali</b>.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-cyan-600 dark:text-cyan-400 transition-colors counter-modal">{{ round($quizAverage ?? 0, 1) }}</span>
                        <p class="text-[9px] md:text-[10px] text-cyan-600/70 dark:text-cyan-400/50 uppercase tracking-widest font-bold mt-3 transition-colors">Total Poin Rata-Rata</p>
                    </div>
                </div>
            </div>

            {{-- 4. Modal Insight Bab Lulus --}}
            <div x-show="showChapterModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showChapterModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(16,185,129,0.15)] transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <button @click="showChapterModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Syarat Kelulusan Bab</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Satu bab kurikulum dinyatakan lulus dan terbuka untuk bab selanjutnya apabila nilai akhir evaluasi (kuis) Anda pada bab tersebut mencapai <b>>= 70</b>.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-emerald-600 dark:text-emerald-400 transition-colors counter-modal">{{ $chaptersPassed ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-emerald-600/70 dark:text-emerald-400/50 uppercase tracking-widest font-bold mt-3 transition-colors">Bab Berhasil Ditaklukkan</p>
                    </div>
                </div>
            </div>

            {{-- =========================================================================
                 MODAL GABUNG KELAS
                 ========================================================================= --}}
            @empty(Auth::user()->class_group)
            <div x-show="showJoinModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showJoinModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3 transition-colors">
                            <div class="p-2 md:p-2.5 rounded-xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 transition-colors">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            Gabung Kelas
                        </h3>
                        <button @click="showJoinModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-1.5 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <p class="text-[10px] md:text-xs text-slate-500 dark:text-slate-400 mb-6 mt-2 transition-colors">Mintalah kode token akses (6 karakter) kepada instruktur Anda, lalu masukkan di bawah ini.</p>

                    <form action="{{ route('student.join_class') }}" method="POST" class="space-y-4 md:space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                        @csrf
                        <div>
                            <label class="text-[9px] md:text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Token Kelas <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <input type="text" name="token" required maxlength="6" style="text-transform: uppercase;" placeholder="Contoh: A7X9YM" class="w-full bg-slate-50 dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl px-3 md:px-4 py-3 md:py-4 text-lg md:text-xl font-mono tracking-[0.2em] md:tracking-[0.3em] font-bold text-slate-900 dark:text-white focus:ring-2 ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-400 dark:placeholder:text-slate-700 placeholder:tracking-normal placeholder:font-sans placeholder:font-normal shadow-inner text-center">
                            </div>
                        </div>

                        <div class="bg-slate-50 dark:bg-white/[0.02] border border-slate-200 dark:border-white/5 rounded-xl p-3 md:p-4 flex items-center gap-3 md:gap-4 shadow-sm dark:shadow-inner transition-colors">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white font-bold shadow-lg text-sm md:text-lg shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[8px] md:text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-0.5 transition-colors">Masuk Sebagai:</p>
                                <p class="text-xs md:text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name }}</p>
                                <p class="text-[9px] md:text-[10px] text-slate-500 dark:text-slate-400 font-mono truncate transition-colors">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 md:gap-3 pt-4 border-t border-slate-200 dark:border-white/5 mt-4 md:mt-6 transition-colors">
                            <button type="button" @click="showJoinModal = false" class="px-4 md:px-5 py-2 md:py-2.5 rounded-xl text-slate-500 dark:text-slate-400 hover:text-slate-900 dark:hover:text-white font-bold text-[10px] md:text-xs transition border border-transparent hover:bg-slate-100 dark:hover:bg-white/5 hover:border-slate-300 dark:hover:border-white/10">Batal</button>
                            <button type="submit" class="px-5 md:px-6 py-2 md:py-2.5 rounded-xl bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white font-bold text-[10px] md:text-xs shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] transition flex items-center gap-2 border border-indigo-500 dark:border-indigo-400" :disabled="isSubmitting" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''">
                                <svg x-show="isSubmitting" class="animate-spin h-3 md:h-3.5 w-3 md:w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
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

{{-- ======================================================================
     STYLES (TOOLTIPS, SCROLLBAR, ANIMATIONS)
     ====================================================================== --}}
<style>
    /* Custom Scrollbar Dinamis */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; } 
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); } 
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* Animasi Utama */
    .animate-spin-slow { animation: spin 8s linear infinite; } 
    @keyframes spin { 100% { transform: rotate(360deg); } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } 
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    [x-cloak] { display: none !important; }

    /* Mengembalikan Glass Card Premium */
    .glass-card { background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.05); backdrop-filter: blur(16px); }
    .dark .glass-card { background: rgba(10, 14, 23, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); }
    
    /* Hover Z-index Fixer untuk Tooltip (Mencegah Tooltip Terpotong Kartu Lain) */
    .academic-card { z-index: 10; overflow: visible !important; }
    .academic-card:hover { z-index: 50; }

    /* =======================================
       TOOLTIP ULTIMATE CSS (ANTI POTONG)
       ======================================= */
    .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 60; }
    .tooltip-container:hover { z-index: 99999; }
    
    .tooltip-trigger { width: 16px; height: 16px; border-radius: 50%; color: white; font-size: 10px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.1); }
    .dark .tooltip-trigger { border: 1px solid rgba(255,255,255,0.2); }
    .tooltip-trigger:hover { transform: scale(1.15); }
    
    .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 200px; max-width: 250px; white-space: normal; text-align: left; background-color: #ffffff; color: #1e293b; border: 1px solid #e2e8f0; font-size: 11px; padding: 12px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.15); z-index: 99999; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); font-family: 'Inter', sans-serif;}
    .dark .tooltip-content { background-color: #020617; color: #e2e8f0; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 20px 60px rgba(0,0,0,1); }
    
    /* Arah Bawah */
    .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); } 
    .tooltip-down:hover .tooltip-content, .tooltip-container:hover > .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; } 
    .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #ffffff transparent; } 
    .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #020617 transparent; }
    
    /* Arah Kiri */
    .tooltip-left .tooltip-content { left: auto; right: calc(100% + 12px); top: 50%; transform: translateY(-50%) translateX(10px); } 
    .tooltip-left:hover .tooltip-content, .tooltip-container:hover > .tooltip-content { transform: translateY(-50%) translateX(0); opacity: 1; visibility: visible; } 
    .tooltip-left .tooltip-content::after { left: 100%; top: 50%; border-width: 6px; border-style: solid; border-color: transparent transparent transparent #ffffff; margin-top: -6px; }
    .dark .tooltip-left .tooltip-content::after { border-color: transparent transparent transparent #020617; }
    
    /* Warna Variants Trigger */
    .tooltip-blue .tooltip-trigger { background-color: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.3); } .tooltip-blue .tooltip-trigger:hover { background-color: #60a5fa; box-shadow: 0 0 15px rgba(59,130,246,0.6); }
    .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.3); } .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.6); } .dark .tooltip-fuchsia .tooltip-content { border-color: rgba(217,70,239,0.5); }
    .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.3); } .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.6); } .dark .tooltip-cyan .tooltip-content { border-color: rgba(6,182,212,0.5); }
    .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.3); } .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.6); } .dark .tooltip-emerald .tooltip-content { border-color: rgba(16,185,129,0.5); }
</style>

{{-- ======================================================================
     SCRIPTS (ALPINE.JS, CHART.JS, JQUERY COUNTER)
     ====================================================================== --}}
<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        
        // --- 1. JQUERY COUNTER-UP ANIMATION ---
        $('.counter-value').each(function () {
            $(this).prop('Counter', 0).animate({
                Counter: $(this).text()
            }, {
                duration: 1500,
                easing: 'swing',
                step: function (now) {
                    if ($(this).text().indexOf('.') > -1) {
                        $(this).text((Math.round(now * 10) / 10).toFixed(1));
                    } else {
                        $(this).text(Math.ceil(now));
                    }
                }
            });
        });
        
        $('.counter-modal').each(function () {
            let val = $(this).text();
            $(this).text(val); // static for modal to avoid double animation bug when hidden
        });

        // --- 2. SWAL ALERTS ---
        const isDark = document.documentElement.classList.contains('dark');
        const swalBg = isDark ? '#0f141e' : '#ffffff';
        const swalColor = isDark ? '#fff' : '#1e293b';

        @if(session('success')) Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#10b981' }); @endif
        @if(session('error')) Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: swalBg, color: swalColor, iconColor: '#ef4444' }); @endif
        @if(session('info')) Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#3b82f6' }); @endif

        // --- 3. CHART JS (LINE CHART KUIS) ---
        const isDarkMode = document.documentElement.classList.contains('dark');
        const ctx = document.getElementById('quizChart')?.getContext('2d');
        if(ctx && {!! json_encode($chartData['scores'] ?? []) !!}.length > 0) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(6, 182, 212, 0.5)'); 
            gradient.addColorStop(1, 'rgba(6, 182, 212, 0)');
            
            new Chart(ctx, { 
                type: 'line', 
                data: { 
                    labels: {!! json_encode($chartData['labels'] ?? []) !!}, 
                    datasets: [{ 
                        label: 'Nilai Evaluasi', 
                        data: {!! json_encode($chartData['scores'] ?? []) !!}, 
                        borderColor: '#06b6d4', 
                        backgroundColor: gradient, 
                        borderWidth: 3, 
                        pointBackgroundColor: isDarkMode ? '#020617' : '#ffffff', 
                        pointBorderColor: '#06b6d4', 
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
                            backgroundColor: isDarkMode ? 'rgba(15, 20, 30, 0.9)' : 'rgba(255, 255, 255, 0.9)', 
                            titleColor: isDarkMode ? '#fff' : '#1e293b',
                            bodyColor: isDarkMode ? '#fff' : '#475569',
                            titleFont: { family: 'Inter', size: 13, weight: 'bold' }, 
                            bodyFont: { family: 'Inter', size: 12 }, 
                            padding: 12, 
                            borderColor: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)', 
                            borderWidth: 1, 
                            displayColors: false 
                        } 
                    }, 
                    scales: { 
                        x: { grid: { display: false }, ticks: { color: 'rgba(150, 150, 150, 0.8)', font: { family: 'monospace' } } }, 
                        y: { beginAtZero: true, max: 100, grid: { color: 'rgba(150, 150, 150, 0.15)' }, ticks: { color: 'rgba(150, 150, 150, 0.8)' } } 
                    } 
                } 
            });
        }
        
        // --- 4. PIE CHART ACTIVITY COMPOSITION ---
        const pieCtx = document.getElementById('activityPieChart')?.getContext('2d');
        if(pieCtx && ({{ $lessonsCompleted ?? 0 }} > 0 || {{ $labsCompleted ?? 0 }} > 0 || {{ $quizzesCompleted ?? 0 }} > 0)) {
            new Chart(pieCtx, {
                type: 'doughnut',
                data: {
                    labels: ['Materi Bacaan', 'Praktik Lab', 'Evaluasi Kuis'],
                    datasets: [{
                        data: [{{ $lessonsCompleted ?? 0 }}, {{ $labsCompleted ?? 0 }}, {{ $quizzesCompleted ?? 0 }}],
                        backgroundColor: [
                            'rgba(217, 70, 239, 0.8)', // Fuchsia
                            'rgba(59, 130, 246, 0.8)', // Blue
                            'rgba(6, 182, 212, 0.8)'   // Cyan
                        ],
                        borderColor: isDarkMode ? '#0f141e' : '#ffffff',
                        borderWidth: 2,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: {
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: isDarkMode ? 'rgba(15, 20, 30, 0.9)' : 'rgba(255, 255, 255, 0.9)',
                            titleColor: isDarkMode ? '#fff' : '#1e293b',
                            bodyColor: isDarkMode ? '#fff' : '#475569',
                            bodyFont: { family: 'Inter', size: 12, weight: 'bold' },
                            padding: 10,
                            borderColor: isDarkMode ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                            borderWidth: 1,
                            displayColors: true,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.raw + ' Selesai';
                                }
                            }
                        }
                    }
                }
            });
        }

        // Render Live Log Terkini (Tanpa Fetch API Tambahan)
        const liveLogs = @json($liveLogData ?? []);
        renderActivityLog(liveLogs);
    });

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList'); if(!list) return; list.innerHTML = '';
        const insightContainer = document.getElementById('liveLogInsight');
        
        if (logs.length === 0) { 
            list.innerHTML = `<li class="text-slate-400 dark:text-white/30 text-center text-xs italic py-10 transition-colors">Belum ada aktivitas akademik tercatat.</li>`; 
            if(insightContainer) insightContainer.innerHTML = '';
            return; 
        }

        logs.forEach((item, index) => {
            let typeLower = item.type ? item.type.toLowerCase() : '';
            let activityName = item.name || ''; 
            
            // Default: Materi SVG
            let icon = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>'; 
            let iconBg = 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-200 dark:border-fuchsia-500/20';
            let typeLabel = 'Membaca Materi';
            let typeColor = 'text-fuchsia-600 dark:text-fuchsia-400';

            // Kuis SVG
            if (typeLower === 'kuis' || typeLower === 'quiz') { 
                icon = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>'; 
                iconBg = 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-200 dark:border-cyan-500/20'; 
                typeLabel = 'Evaluasi Kuis';
                typeColor = 'text-cyan-600 dark:text-cyan-400';
            } 
            // Lab SVG
            else if (typeLower === 'lab')  { 
                icon = '<svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>'; 
                iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20'; 
                typeLabel = 'Praktik Lab';
                typeColor = 'text-blue-600 dark:text-blue-400';
            }

            const delay = (index > 15 ? 0 : index) * 100;
            const statusDisplay = item.status === 'Lulus' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-transparent' : (item.status === 'Selesai' || item.status === 'Tuntas' ? 'bg-indigo-50 dark:bg-indigo-500/10 text-indigo-700 dark:text-indigo-400 border border-indigo-200 dark:border-transparent' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-transparent');

            list.insertAdjacentHTML('beforeend', `
                <li x-show="logFilter === 'all' || logFilter === '${typeLower === 'quiz' ? 'kuis' : typeLower}'" x-transition class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.05] transition-colors border border-slate-200 dark:border-white/5 animate-fade-in-up" style="animation-delay: ${delay}ms">
                    <div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0 shadow-sm dark:shadow-inner transition-colors">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5 gap-2">
                            <h4 class="text-[10px] md:text-xs font-bold text-slate-800 dark:text-white line-clamp-2 transition-colors pr-2 leading-snug" title="${activityName}">${activityName}</h4>
                            ${item.status ? `<span class="text-[8px] md:text-[9px] font-bold px-1.5 py-0.5 rounded shrink-0 transition-colors ${statusDisplay}">${item.status}</span>` : ''}
                        </div>
                        <div class="flex justify-between items-end mt-1.5">
                            <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-mono">
                                <span class="uppercase tracking-wider font-bold transition-colors ${typeColor}">${typeLabel}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/20 transition-colors hidden sm:block"></span>
                                <span class="text-slate-500 dark:text-white/40 transition-colors hidden sm:block" title="${item.full_date || item.date || ''}">${item.time || ''} &bull; ${item.full_date || ''}</span>
                            </div>
                        </div>
                    </div>
                </li>
            `);
        });

        if(insightContainer) {
            insightContainer.innerHTML = `
                <div class="text-right">
                    <p class="text-[8px] uppercase tracking-widest text-slate-500 dark:text-slate-500 font-bold mb-0.5 transition-colors">Total Aktivitas</p>
                    <p class="text-xs font-bold text-cyan-600 dark:text-cyan-400 transition-colors">${logs.length} Log Tersimpan</p>
                </div>
            `;
        }
    }
</script>
@endsection