@extends('layouts.landing')

@section('title', 'Dashboard Siswa ')

@section('content')

{{-- ==============================================================================
     LOGIKA QUERY (Dikembalikan seperti semula dengan perbaikan format waktu)
     ============================================================================== --}}
@php
    $userId = Auth::id();
    
    // 1. DATA TABEL RIWAYAT (SEMUA KUIS & LAB DARI AWAL TERMASUK REMEDIAL)
    $allQuizzes = \App\Models\QuizAttempt::where('user_id', $userId)
        ->whereNotNull('completed_at')
        ->latest('completed_at')
        ->get()
        ->map(function($q) {
            return [
                'id' => 'quiz-'.$q->id,
                'name' => 'Evaluasi Bab ' . $q->chapter_id,
                'type' => 'kuis',
                'score' => $q->score,
                'date' => $q->completed_at,
                'time' => \Carbon\Carbon::parse($q->completed_at)->diffForHumans(), // <-- Ditambahkan di sini
                'status' => $q->score >= 70 ? 'Lulus' : 'Remedial',
                'exp' => $q->score,
                'icon' => '📝'
            ];
        });

    $allLabs = \App\Models\LabHistory::where('user_id', $userId)
        ->whereIn('status', ['passed', 'failed', 'completed'])
        ->with('lab')
        ->latest('updated_at')
        ->get()
        ->map(function($l) {
            return [
                'id' => 'lab-'.$l->id,
                'name' => $l->lab->title ?? 'Praktik Lab',
                'type' => 'lab',
                'score' => $l->final_score,
                'date' => $l->updated_at,
                'time' => \Carbon\Carbon::parse($l->updated_at)->diffForHumans(), // <-- Ditambahkan di sini
                'status' => $l->final_score >= 70 ? 'Lulus' : 'Remedial',
                'exp' => $l->final_score >= 70 ? 50 : 0,
                'icon' => '💻'
            ];
        });

    $tableHistory = collect($allQuizzes)->merge($allLabs)->sortByDesc('date')->values();

    // 2. DATA LIVE LOG GAMIFIKASI (KESELURUHAN YANG MENGHASILKAN EXP)
    $allLessons = \App\Models\UserLessonProgress::where('user_id', $userId)
        ->where('completed', true)
        ->latest('updated_at')
        ->get()
        ->map(function($m) {
            return [
                'name' => 'Membaca Materi Modul',
                'type' => 'materi',
                'date' => $m->updated_at,
                'time' => \Carbon\Carbon::parse($m->updated_at)->diffForHumans(), // <-- Ditambahkan di sini
                'status' => 'Selesai',
                'exp' => 10,
                'icon' => '📖'
            ];
        });

    $allBadgesLog = \Illuminate\Support\Facades\DB::table('user_badges')
        ->join('badges', 'user_badges.badge_id', '=', 'badges.id')
        ->where('user_badges.user_id', $userId)
        ->select('badges.name', 'user_badges.created_at')
        ->latest('user_badges.created_at')
        ->get()
        ->map(function($b) {
            return [
                'name' => 'Lencana: ' . $b->name,
                'type' => 'badge',
                'date' => $b->created_at,
                'time' => \Carbon\Carbon::parse($b->created_at)->diffForHumans(), // <-- Ditambahkan di sini
                'status' => 'Didapatkan',
                'exp' => 0, 
                'icon' => '🎖️'
            ];
        });

    // Gabungkan Semua untuk Live Log, filter KHUSUS YANG BERHASIL MENDAPAT EXP / REWARD
    $liveLogData = collect($allLessons)
        ->merge($tableHistory)
        ->merge($allBadgesLog)
        ->filter(function($item) {
            return $item['exp'] > 0 || $item['type'] === 'badge';
        })
        ->sortByDesc('date')
        ->values();
@endphp

<div id="appRoot" class="relative h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30 pt-20 transition-colors duration-300">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-fuchsia-300/30 dark:bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse transition-colors duration-300"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-300/30 dark:bg-cyan-900/10 rounded-full blur-[100px] transition-colors duration-300"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.03] mix-blend-overlay transition-opacity duration-300"></div>
    </div>

    @include('layouts.partials.navbar')
    
    {{-- WRAPPER UTAMA DENGAN ALPINEJS --}}
    <div class="flex flex-1 overflow-hidden relative" 
         x-data="{ 
            sidebarOpen: false, showJoinModal: false, showLessonModal: false,
            showLabModal: false, showQuizModal: false, showChapterModal: false,
            showTitleModal: false, showBadgeModal: false, activeBadge: null
         }"
         @keydown.escape.window="sidebarOpen = false; showJoinModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showChapterModal = false; showTitleModal = false; showBadgeModal = false;">

        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        {{-- SIDEBAR MENU DASHBOARD --}}
        <aside class="w-[280px] bg-white/80 dark:bg-[#050912]/80 backdrop-blur-xl border-r border-slate-200 dark:border-white/5 flex-col shrink-0 z-[100] fixed lg:relative h-full transition-all duration-300 transform lg:translate-x-0 flex shadow-xl lg:shadow-none" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <p class="text-xs font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-4 pl-2 transition-colors">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-slate-100 dark:bg-white/10 border border-slate-200 dark:border-white/10 text-slate-900 dark:text-white font-bold shadow-sm dark:shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-[1.02]">
                        <span class="text-fuchsia-500 dark:text-fuchsia-400 group-hover:scale-110 transition text-lg drop-shadow-sm dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]">📊</span>
                        Overview
                    </a>
                    
                    {{-- Navigasi Materi Kembali ke Asal --}}
                    @php $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group)); @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                            <span class="grayscale group-hover:grayscale-0 transition text-lg">📚</span> Materi Belajar
                        </a>
                    @else
                        <button class="w-full text-left group flex items-center justify-between px-4 py-3 rounded-xl bg-red-50 dark:bg-red-500/5 text-red-600 dark:text-red-400/80 cursor-not-allowed border border-transparent transition-colors">
                            <div class="flex items-center gap-3"><span class="grayscale opacity-50 text-lg">📚</span> <span class="font-medium">Materi Belajar</span></div>
                            <svg class="w-4 h-4 text-red-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </button>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">⚙️</span> Pengaturan
                    </a>
                    
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-slate-100 dark:hover:bg-white/5 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition border border-transparent hover:border-slate-200 dark:hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">👨‍💻</span> Informasi
                    </a>
                </nav>
            </div>
            <div class="mt-auto p-6 shrink-0">
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
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
                     HEADER PAGE & STATUS KELAS
                     ========================================================= --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        {{-- BREADCRUMB START --}}
                        <nav class="flex items-center gap-2 mb-4 text-[10px] md:text-xs font-bold uppercase tracking-widest text-slate-400 dark:text-white/40 transition-colors" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Home
                            </a>
                            <span class="text-slate-300 dark:text-white/20 transition-colors">/</span>
                            <span class="text-fuchsia-600 dark:text-fuchsia-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(217,70,239,0.5)] transition-colors">Dashboard</span>
                        </nav>
                        {{-- BREADCRUMB END --}}

                        <h1 class="text-4xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">
                            Overview <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-500 to-cyan-500 dark:from-fuchsia-400 dark:to-cyan-400">Dashboard</span>
                        </h1>
                        <p class="text-slate-600 dark:text-white/60 text-lg transition-colors">Pantau pencapaian materi, XP, koleksi lencana, dan analitik Anda.</p>
                        
                        <div class="mt-6 inline-flex items-center gap-4 px-4 py-2.5 rounded-2xl bg-white dark:bg-white/[0.02] border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-inner w-full md:w-auto transition-colors">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white shadow-lg text-lg shrink-0">
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
                    
                    <div class="flex flex-col items-start md:items-end gap-4 w-full md:w-auto">
                        <div class="hidden md:block text-right">
                            <p class="text-xs text-slate-500 dark:text-white/30 uppercase tracking-widest mb-1 transition-colors">Tanggal Hari Ini</p>
                            <p class="text-xl font-mono font-bold text-slate-900 dark:text-white transition-colors">{{ date('d M Y') }}</p>
                        </div>
                        
                        @empty(Auth::user()->class_group)
                            <button @click="showJoinModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white text-sm font-bold shadow-lg dark:shadow-[0_0_20px_rgba(99,102,241,0.4)] transition transform hover:-translate-y-0.5 border border-indigo-500 dark:border-indigo-400 group">
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                Gabung Kelas
                            </button>
                        @else
                            <div class="px-5 py-3 rounded-xl bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20 shadow-sm dark:shadow-inner flex items-center justify-center md:justify-start gap-3 w-full md:w-auto transition-colors">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-emerald-600 dark:text-emerald-400 uppercase tracking-widest transition-colors">Tergabung di Kelas</span>
                            </div>
                        @endempty
                    </div>
                </div>

                {{-- ALERT LAB AKTIF (Resume) --}}
                @if(isset($activeSession) && $activeSession)
                <div class="rounded-2xl bg-indigo-50 dark:bg-indigo-900/40 border border-indigo-200 dark:border-indigo-500/30 p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-sm dark:shadow-lg dark:shadow-indigo-900/20 mb-2 animate-pulse-slow transition-colors">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center animate-pulse shadow-md dark:shadow-[0_0_15px_#6366f1] shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-indigo-900 dark:text-white transition-colors">Lab Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktikum' }}</h3>
                            <p class="text-indigo-600 dark:text-indigo-200 text-xs transition-colors">Aktivitas koding Anda belum diselesaikan.</p>
                        </div>
                    </div>
                    <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="w-full md:w-auto px-5 py-2 bg-indigo-600 dark:bg-indigo-500 hover:bg-indigo-700 dark:hover:bg-indigo-400 text-white text-center font-bold rounded-lg text-sm transition shadow-md hover:shadow-lg dark:hover:shadow-indigo-500/50 flex items-center justify-center gap-2">
                        Lanjut Coding <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                @endif

                {{-- =========================================================
                     GAMIFIKASI ZONE
                     ========================================================= --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8 reveal">
                    {{-- 1. LEVEL & XP CARD --}}
                    <div @click="showTitleModal = true" class="xl:col-span-3 glass-card rounded-[2rem] p-6 md:p-10 border-t-2 border-t-indigo-500/50 relative overflow-hidden flex flex-col md:flex-row items-center gap-8 shadow-xl dark:shadow-2xl cursor-pointer hover:border-indigo-400 dark:hover:border-indigo-500/80 transition duration-300 group">
                        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-200/50 dark:bg-indigo-500/10 rounded-full blur-[80px] pointer-events-none group-hover:bg-indigo-300/50 dark:group-hover:bg-indigo-500/20 transition-colors"></div>
                        <div class="relative shrink-0 text-center">
                            <div class="w-28 h-28 rounded-full bg-white dark:bg-[#020617] border-[4px] border-indigo-500 flex items-center justify-center flex-col shadow-lg dark:shadow-[0_0_40px_rgba(99,102,241,0.3)] relative z-10 overflow-hidden transition-colors">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-100 to-cyan-100 dark:from-indigo-500/20 dark:to-cyan-500/20 animate-spin-slow opacity-0 group-hover:opacity-100 transition-opacity"></div>
                                <span class="text-[10px] text-indigo-600 dark:text-indigo-400 font-bold uppercase tracking-widest mt-2 relative z-10 transition-colors">Total XP</span>
                                <span class="text-2xl font-black text-slate-900 dark:text-white leading-none relative z-10 transition-colors">{{ number_format($user->xp ?? 0) }}</span>
                            </div>
                            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-indigo-500 text-white dark:text-[#020617] text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full z-20 whitespace-nowrap shadow-lg">{{ $user->developer_title ?? 'Intern Coder' }}</div>
                        </div>
                        <div class="flex-1 w-full relative z-10 mt-4 md:mt-0">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-3 gap-2">
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">Jejak Karir Developer <div class="w-4 h-4 rounded-full bg-indigo-100 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/30 text-[9px] transition-colors">?</div></h3>
                                    <p class="text-xs text-slate-600 dark:text-slate-400 mt-1 transition-colors">Kumpulkan XP untuk mencapai title Tailwind Architect.</p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <span class="inline-block px-3 py-1 rounded-md bg-indigo-50 dark:bg-indigo-500/10 border border-indigo-200 dark:border-indigo-500/20 text-xs font-bold text-indigo-700 dark:text-indigo-400 transition-colors">Next Target: {{ number_format($user->next_level_xp ?? 500) }} XP</span>
                                </div>
                            </div>
                            <div class="w-full h-3.5 bg-slate-200 dark:bg-[#020617] rounded-full overflow-hidden border border-slate-300 dark:border-white/10 shadow-inner relative transition-colors">
                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-10 dark:opacity-20"></div>
                                <div class="h-full bg-gradient-to-r from-cyan-400 via-indigo-500 to-fuchsia-500 shadow-none dark:shadow-[0_0_15px_#818cf8] transition-all duration-[2s] ease-out rounded-full" style="width: {{ $user->xp_progress ?? 0 }}%;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. BADGES COLLECTION --}}
                    <div class="xl:col-span-2 glass-card rounded-[2rem] p-6 md:p-8 relative overflow-hidden shadow-sm dark:shadow-none">
                        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white flex items-center gap-3 transition-colors">
                                <div class="w-8 h-8 rounded-lg bg-fuchsia-100 dark:bg-fuchsia-500/20 text-fuchsia-600 dark:text-fuchsia-400 flex items-center justify-center border border-fuchsia-200 dark:border-fuchsia-500/30 transition-colors"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg></div> Digital Badges
                            </h3>
                            <span class="px-3 py-1 bg-white dark:bg-white/5 border border-slate-200 dark:border-white/10 rounded-lg text-xs font-bold text-slate-600 dark:text-slate-300 font-mono transition-colors">{{ count($unlockedBadges ?? []) }} / {{ count($allBadges ?? []) }} Terbuka</span>
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-4 overflow-y-auto custom-scrollbar pr-1 max-h-[300px]">
                            @forelse($allBadges ?? [] as $badge)
                                @php
                                    $isUnlocked = in_array($badge->id, $unlockedBadges ?? []);
                                    $c = $badge->color ?? 'indigo';
                                    $badgeData = json_encode(['name'=>$badge->name, 'description'=>$badge->description, 'color'=>$c, 'icon'=>$badge->icon, 'status'=>$isUnlocked?'Unlocked':'Locked']);
                                @endphp
                                <div @click="activeBadge = {{ $badgeData }}; showBadgeModal = true" class="aspect-square bg-white dark:bg-[#0a0e17] border {{ $isUnlocked ? 'border-'.$c.'-300 dark:border-'.$c.'-500/40 shadow-sm dark:shadow-[0_0_20px_rgba(var(--color-'.$c.'-500),0.15)] group hover:-translate-y-1' : 'border-slate-200 dark:border-white/5 opacity-50 grayscale hover:grayscale-[0.5]' }} p-3 md:p-4 rounded-2xl flex flex-col items-center text-center transition cursor-pointer relative overflow-hidden">
                                    @if($isUnlocked)<div class="absolute inset-0 bg-gradient-to-b from-{{$c}}-100 dark:from-{{$c}}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>@endif
                                    <div class="w-8 h-8 md:w-10 md:h-10 {{ $isUnlocked ? 'text-'.$c.'-600 dark:text-'.$c.'-400 group-hover:scale-110 drop-shadow-none dark:drop-shadow-[0_0_15px_rgba(var(--color-'.$c.'-500),0.8)]' : 'text-slate-400 dark:text-white/40' }} mb-2 transition relative z-10 flex justify-center">{!! $badge->icon !!}</div>
                                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest {{ $isUnlocked ? 'text-'.$c.'-700 dark:text-'.$c.'-400' : 'text-slate-500 dark:text-white/50' }} relative z-10 transition-colors">{{ $badge->name }}</p>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center border-2 border-dashed border-slate-300 dark:border-white/10 rounded-2xl bg-slate-50 dark:bg-white/[0.02] transition-colors"><p class="text-xs text-slate-500 dark:text-slate-400 italic">No badges found.</p></div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 3. LEADERBOARD KELAS --}}
                    <div class="xl:col-span-1 glass-card rounded-[2rem] p-6 md:p-8 border-t-2 border-t-yellow-400 dark:border-t-yellow-500/50 flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors">
                        <div class="absolute right-0 top-0 w-40 h-40 bg-yellow-200/50 dark:bg-yellow-500/10 rounded-full blur-[60px] pointer-events-none transition-colors"></div>
                        <div class="flex items-center gap-3 mb-2 relative z-10">
                            <div class="w-8 h-8 rounded-lg bg-yellow-100 dark:bg-yellow-500/20 text-yellow-600 dark:text-yellow-400 flex items-center justify-center border border-yellow-200 dark:border-yellow-500/30 text-lg shadow-sm dark:shadow-[0_0_15px_rgba(234,179,8,0.2)] transition-colors">🏆</div>
                            <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Leaderboard</h3>
                        </div>
                        <p class="text-[10px] text-slate-500 dark:text-slate-400 mb-6 border-b border-slate-200 dark:border-white/5 pb-4 relative z-10 transition-colors">Top 5 Coder di kelas {{ $user->class_group ?? 'Anda' }}</p>
                        
                        <div class="space-y-3 relative z-10 flex-1 overflow-y-auto custom-scrollbar pr-1">
                            @forelse($leaderboard ?? [] as $index => $boardUser)
                                @php
                                    $isMe = $boardUser->id === Auth::id();
                                    $bg = $isMe ? 'bg-indigo-50 dark:bg-indigo-500/20 border-indigo-200 dark:border-indigo-500/40 shadow-sm dark:shadow-lg' : 'bg-white dark:bg-white/[0.02] border-slate-200 dark:border-white/5';
                                    $rankColor = match($index) { 0 => 'bg-yellow-400 text-black', 1 => 'bg-slate-300 text-black', 2 => 'bg-amber-600 text-white', default => 'bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/50 border border-slate-200 dark:border-white/10' };
                                    $textColor = $isMe ? 'text-indigo-700 dark:text-indigo-300' : ($index == 0 ? 'text-yellow-600 dark:text-yellow-400' : 'text-slate-800 dark:text-white');
                                @endphp
                                <div class="flex items-center gap-3 p-3.5 rounded-xl border {{ $bg }} transition hover:scale-[1.02]">
                                    <span class="w-7 h-7 rounded-full {{ $rankColor }} flex items-center justify-center text-xs font-black shrink-0 transition-colors">{{ $index + 1 }}</span>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-bold {{ $textColor }} truncate transition-colors">{{ $isMe ? 'Anda ('.$boardUser->name.')' : $boardUser->name }}</p></div>
                                    <span class="text-xs font-black font-mono text-slate-500 dark:text-white/50 {{ $isMe ? 'text-indigo-600 dark:text-indigo-400' : '' }} {{ $index == 0 && !$isMe ? 'text-yellow-600 dark:text-yellow-500' : '' }} transition-colors">{{ number_format($boardUser->xp) }} XP</span>
                                </div>
                            @empty
                                <div class="text-center py-8 text-slate-400 dark:text-white/30 text-xs italic transition-colors">Leaderboard belum tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- VISUAL SEPARATOR --}}
                <div class="flex items-center gap-4 py-4">
                    <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                    <span class="text-[10px] font-black text-slate-500 dark:text-slate-600 uppercase tracking-[0.2em] bg-slate-50 dark:bg-[#020617] px-3 py-1 border border-slate-200 dark:border-white/5 rounded-full whitespace-nowrap transition-colors">Academic Analytics</span>
                    <div class="h-px bg-slate-200 dark:bg-white/10 flex-1 transition-colors"></div>
                </div>

                {{-- =========================================================
                     GRID STATISTIK AKADEMIK (INSIGHT HERO MODAL ASLI)
                     ========================================================= --}}
                @php 
                    $pctLesson = ($totalLessons > 0) ? round(($lessonsCompleted / $totalLessons) * 100) : 0; 
                    $pctLab = ($totalLabs > 0) ? round(($labsCompleted / $totalLabs) * 100) : 0;
                @endphp
                <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6 reveal" style="animation-delay: 0.2s;">
                    {{-- CARD 1: MATERI --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-fuchsia-400 dark:hover:border-fuchsia-500/40 transition duration-300 cursor-pointer shadow-sm hover:shadow-md dark:shadow-lg" @click="showLessonModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-100 dark:from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">Materi Bacaan</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-400 transition-colors">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-white/40 font-bold text-sm md:text-lg transition-colors">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 dark:bg-white/5 rounded-full mt-3 md:mt-4 overflow-hidden border border-slate-200 dark:border-white/5 transition-colors">
                                <div class="h-full bg-fuchsia-500 shadow-none dark:shadow-[0_0_10px_#d946ef] transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-blue-400 dark:hover:border-blue-500/40 transition duration-300 cursor-pointer shadow-sm hover:shadow-md dark:shadow-lg" @click="showLabModal = true">
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">Hands-on Labs</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $labsCompleted ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-white/40 font-bold text-sm md:text-lg transition-colors">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-slate-100 dark:bg-white/5 rounded-full mt-3 md:mt-4 overflow-hidden border border-slate-200 dark:border-white/5 transition-colors">
                                <div class="h-full bg-blue-500 shadow-none dark:shadow-[0_0_10px_#3b82f6] transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-cyan-400 dark:hover:border-cyan-500/40 transition duration-300 cursor-pointer shadow-sm hover:shadow-md dark:shadow-lg" @click="showQuizModal = true">
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Rata-rata Kuis</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">{{ round($quizAverage ?? 0, 1) }}</span>
                                <span class="text-slate-400 dark:text-white/40 font-bold text-sm md:text-lg transition-colors">pts</span>
                            </div>
                            <p class="text-[9px] md:text-[10px] text-slate-400 dark:text-white/30 mt-3 md:mt-4 font-mono transition-colors">Dari {{ $quizzesCompleted ?? 0 }} x percobaan evaluasi.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-slate-200 dark:border-white/10 bg-white dark:bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-emerald-400 dark:hover:border-emerald-500/40 transition duration-300 cursor-pointer shadow-sm hover:shadow-md dark:shadow-lg" @click="showChapterModal = true">
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Bab Lulus</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-slate-900 dark:text-white group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">{{ $chaptersPassed ?? 0 }}</span>
                                <span class="text-slate-400 dark:text-white/40 font-bold text-sm md:text-lg transition-colors">Bab</span>
                            </div>
                            <p class="text-[9px] md:text-[10px] text-emerald-600/70 dark:text-emerald-400/50 mt-3 md:mt-4 font-bold uppercase tracking-wider transition-colors">Keep Going!</p>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     CHART & LOGS DENGAN FILTER & EXP GAMIFIKASI ULTIMATE
                     ========================================================= --}}
                <div class="grid lg:grid-cols-3 gap-6 md:gap-8 reveal" style="animation-delay: 0.3s;">
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        
                        {{-- GRAFIK KUIS --}}
                        <div class="rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl shadow-sm dark:shadow-lg relative overflow-hidden transition-colors duration-500">
                            <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white transition-colors">Grafik Perkembangan Nilai</h3>
                            <p class="text-[10px] md:text-xs text-slate-500 dark:text-white/40 mt-0.5 mb-6 transition-colors">Visualisasi hasil evaluasi kuis terbaik Anda per bab.</p>
                            <div class="relative h-[200px] md:h-[250px] w-full z-10">
                                @if(isset($chartData['scores']) && count($chartData['scores']) > 0)
                                    <canvas id="quizChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-xl bg-slate-50 dark:bg-white/[0.01] transition-colors">
                                        <p class="text-xs font-semibold text-slate-400">Belum Ada Data Kuis</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TABEL HISTORY (FILTER ONLY LAB & KUIS WITH EXP - BISA SCROLL SEMUA) --}}
                        <div class="rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl flex flex-col h-[450px] shadow-sm dark:shadow-lg transition-colors duration-500" x-data="{ filterTable: 'all' }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 md:mb-6 shrink-0 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
                                <h3 class="text-base md:text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-xl">🕒</span> Riwayat Pengerjaan
                                </h3>
                                
                                {{-- Filter Interaktif AlpineJS --}}
                                <div class="flex items-center bg-slate-100 dark:bg-[#020617] p-1 rounded-lg border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                                    <button @click="filterTable = 'all'" :class="filterTable === 'all' ? 'bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Semua</button>
                                    <button @click="filterTable = 'kuis'" :class="filterTable === 'kuis' ? 'bg-fuchsia-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Kuis</button>
                                    <button @click="filterTable = 'lab'" :class="filterTable === 'lab' ? 'bg-blue-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Lab</button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto custom-scrollbar -mx-6 md:mx-0 px-6 md:px-0 flex-1 relative">
                                <div class="absolute top-0 bottom-0 right-0 w-4 bg-gradient-to-l from-white dark:from-[#0f141e] to-transparent pointer-events-none md:hidden z-30 transition-colors duration-500"></div>
                                <div class="max-h-[300px] overflow-y-auto custom-scrollbar pr-2 h-full pb-10">
                                    <table class="w-full text-left border-collapse min-w-[400px] relative">
                                        <thead class="sticky top-0 z-20 bg-white dark:bg-[#0f141e] shadow-sm dark:shadow-md after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-slate-200 dark:after:bg-white/10 transition-colors duration-500">
                                            <tr class="text-[10px] md:text-xs text-slate-500 dark:text-white/30 uppercase tracking-widest transition-colors">
                                                <th class="py-3 pl-2">Aktivitas</th>
                                                <th class="py-3 hidden sm:table-cell">Waktu</th>
                                                <th class="py-3 text-right pr-2">Skor & EXP</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs md:text-sm text-slate-700 dark:text-white/70 transition-colors">
                                            @forelse($tableHistory as $item)
                                                @php
                                                    $typeLower = isset($item['type']) ? strtolower($item['type']) : '';
                                                    $typeLabel = 'Aktivitas';
                                                    $typeColor = 'text-slate-500 dark:text-slate-400';
                                                    $gainedXp = isset($item['exp']) ? $item['exp'] : 0;
                                                    $iconBg = 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/50 border-slate-200 dark:border-white/10';
                                                    $icon = '✓';

                                                    if ($typeLower == 'kuis' || $typeLower == 'quiz') {
                                                        $typeLabel = 'Evaluasi Kuis';
                                                        $typeColor = 'text-fuchsia-600 dark:text-fuchsia-400';
                                                        $iconBg = 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-200 dark:border-fuchsia-500/20';
                                                        $icon = '📝';
                                                        if(!$gainedXp) $gainedXp = isset($item['score']) ? $item['score'] : 0;
                                                    } elseif ($typeLower == 'lab') {
                                                        $typeLabel = 'Praktik Lab';
                                                        $typeColor = 'text-blue-600 dark:text-blue-400';
                                                        $iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20';
                                                        $icon = '💻';
                                                        if(!$gainedXp) $gainedXp = (isset($item['score']) && $item['score'] >= 70) ? 50 : 0;
                                                    }
                                                @endphp
                                                <tr x-show="filterTable === 'all' || filterTable === '{{ $typeLower }}'" class="group hover:bg-slate-50 dark:hover:bg-white/5 transition-colors border-b border-slate-100 dark:border-white/5 last:border-0" x-transition>
                                                    <td class="py-3 md:py-4 pl-2 font-medium text-slate-800 dark:text-white flex items-center gap-3 transition-colors">
                                                        <div class="w-6 h-6 md:w-8 md:h-8 rounded flex items-center justify-center text-[10px] md:text-xs font-bold shadow-sm dark:shadow-lg shrink-0 border {{ $iconBg }} transition-colors">
                                                            {{ $icon }}
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="truncate text-xs font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                                <span class="text-[9px] md:text-[10px] uppercase font-bold tracking-wider {{ $typeColor }} transition-colors">{{ $typeLabel }}</span>
                                                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/20 sm:hidden transition-colors"></span>
                                                                <span class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/40 font-mono sm:hidden transition-colors">{{ \Carbon\Carbon::parse($item['date'])->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 md:py-4 text-[10px] md:text-xs font-mono text-slate-500 dark:text-white/50 hidden sm:table-cell transition-colors">
                                                        {{ \Carbon\Carbon::parse($item['date'])->diffForHumans() }}
                                                    </td>
                                                    <td class="py-3 md:py-4 text-right pr-2 shrink-0">
                                                        <div class="flex flex-col items-end gap-1.5">
                                                            @if(isset($item['score']))
                                                                <span class="px-2 md:px-3 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold border transition-colors {{ $item['score'] >= 70 ? 'text-emerald-700 bg-emerald-50 dark:text-emerald-400 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' : 'text-red-700 bg-red-50 dark:text-red-400 dark:bg-red-500/10 border-red-200 dark:border-red-500/20' }}">{{ $item['score'] }} pts</span>
                                                            @endif
                                                            
                                                            @if($gainedXp > 0)
                                                                <span class="text-[8px] md:text-[9px] font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/20 shadow-sm transition-colors" title="Mendapatkan {{ $gainedXp }} XP dari {{ $typeLabel }}">+{{ $gainedXp }} XP</span>
                                                            @endif
                                                        </div>
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

                    <div class="lg:col-span-1 space-y-6 md:space-y-8">
                        {{-- Heatmap --}}
                        <div class="rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl shadow-sm dark:shadow-none transition-colors duration-500">
                            <div class="flex items-center gap-2 mb-4">
                                <h3 class="text-xs md:text-sm font-bold text-slate-700 dark:text-white/70 uppercase tracking-wider transition-colors">Konsistensi Belajar</h3>
                                <span class="text-lg">🔥</span>
                            </div>
                            <div id="heatmap" class="flex flex-wrap gap-1 md:gap-1.5 content-start min-h-[100px] md:min-h-[150px]"></div>
                            <div class="mt-4 flex gap-3 md:gap-4 text-[9px] md:text-[10px] text-slate-500 dark:text-white/30 uppercase tracking-wider font-bold transition-colors">
                                <span class="flex items-center gap-1.5"><div class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-[2px] bg-slate-200 dark:bg-white/5 transition-colors"></div> 0</span>
                                <span class="flex items-center gap-1.5"><div class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-[2px] bg-cyan-400 dark:bg-cyan-500/50 transition-colors"></div> 1-2</span>
                                <span class="flex items-center gap-1.5"><div class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-[2px] bg-fuchsia-500 transition-colors"></div> 3+</span>
                            </div>
                        </div>

                        {{-- Log Real-time (SELURUH AKTIVITAS GAMIFIKASI MENGGUNAKAN ALPINEJS UNTUK FILTER) --}}
                        <div class="rounded-3xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 p-6 md:p-8 backdrop-blur-xl h-[450px] flex flex-col relative overflow-hidden shadow-sm dark:shadow-none transition-colors duration-500" x-data="{ logFilter: 'all' }">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-4 relative z-10 border-b border-slate-200 dark:border-white/5 pb-4 shrink-0 gap-4 transition-colors">
                                <div>
                                    <h3 class="text-xs md:text-sm font-bold text-slate-700 dark:text-white/70 uppercase tracking-wider mb-2 transition-colors">Live Log EXP</h3>
                                    <div class="flex items-center bg-slate-100 dark:bg-[#020617] p-1 rounded-lg border border-slate-200 dark:border-white/5 shadow-inner w-max transition-colors">
                                        <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Semua</button>
                                        <button @click="logFilter = 'materi'" :class="logFilter === 'materi' ? 'bg-cyan-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Materi</button>
                                        <button @click="logFilter = 'kuis'" :class="logFilter === 'kuis' ? 'bg-fuchsia-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Kuis</button>
                                        <button @click="logFilter = 'lab'" :class="logFilter === 'lab' ? 'bg-blue-500 text-white shadow-md' : 'text-slate-500 dark:text-white/40 hover:text-slate-800 dark:hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Lab</button>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right" id="liveLogInsight">
                                    {{-- Insight akan dirender lewat JS --}}
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
                    <p class="text-slate-500 dark:text-white/20 text-[10px] md:text-xs">&copy; {{ date('Y') }} Utilwind CSS E-Learning</p>
                </div>
            </div>

            {{-- =========================================================================
                 HERO MODALS (GAMIFICATION & INSIGHTS)
                 ========================================================================= --}}

            {{-- 0. Modal Title & XP --}}
            <div x-show="showTitleModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showTitleModal = false"></div>
                
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] transition-colors"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="showTitleModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-slate-800 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 z-20">
                        <svg class="w-4 md:w-5 h-4 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 md:w-32 h-24 md:h-32 rounded-full bg-indigo-400 dark:bg-indigo-500 blur-[30px] md:blur-[40px] opacity-20 pointer-events-none"></div>

                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 flex items-center justify-center border border-indigo-200 dark:border-indigo-500/30 mb-4 md:mb-6 relative z-10 shadow-sm dark:shadow-[0_0_15px_rgba(99,102,241,0.5)] transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors">Sistem Pangkat & XP</h3>
                        <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-6 transition-colors">Kumpulkan Experience Points (XP) dari aktivitas belajar untuk membuka titel developer baru.</p>

                        <div class="w-full space-y-2 text-left relative z-10">
                            <div class="flex justify-between items-center p-3 rounded-xl border transition-colors {{ ($user->xp ?? 0) >= 4000 ? 'bg-indigo-50 dark:bg-indigo-500/20 border-indigo-300 dark:border-indigo-500/50 text-indigo-900 dark:text-white shadow-sm dark:shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400' }}">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-rose-500 dark:text-rose-400 text-sm md:text-base">💎</span> Tailwind Architect</span>
                                <span class="text-[9px] md:text-[10px] font-mono">4000+ XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border transition-colors {{ (($user->xp ?? 0) >= 2500 && ($user->xp ?? 0) < 4000) ? 'bg-indigo-50 dark:bg-indigo-500/20 border-indigo-300 dark:border-indigo-500/50 text-indigo-900 dark:text-white shadow-sm dark:shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400' }}">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-amber-500 dark:text-amber-400 text-sm md:text-base">🥇</span> Component Crafter</span>
                                <span class="text-[9px] md:text-[10px] font-mono">2500 XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border transition-colors {{ (($user->xp ?? 0) >= 1000 && ($user->xp ?? 0) < 2500) ? 'bg-indigo-50 dark:bg-indigo-500/20 border-indigo-300 dark:border-indigo-500/50 text-indigo-900 dark:text-white shadow-sm dark:shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400' }}">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-slate-400 dark:text-slate-300 text-sm md:text-base">🥈</span> Frontend Stylist</span>
                                <span class="text-[9px] md:text-[10px] font-mono">1000 XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border transition-colors {{ (($user->xp ?? 0) >= 300 && ($user->xp ?? 0) < 1000) ? 'bg-indigo-50 dark:bg-indigo-500/20 border-indigo-300 dark:border-indigo-500/50 text-indigo-900 dark:text-white shadow-sm dark:shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400' }}">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-orange-500 dark:text-orange-400 text-sm md:text-base">🥉</span> Utility Apprentice</span>
                                <span class="text-[9px] md:text-[10px] font-mono">300 XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border transition-colors {{ ($user->xp ?? 0) < 300 ? 'bg-indigo-50 dark:bg-indigo-500/20 border-indigo-300 dark:border-indigo-500/50 text-indigo-900 dark:text-white shadow-sm dark:shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-slate-50 dark:bg-white/5 border-slate-200 dark:border-white/10 text-slate-500 dark:text-slate-400' }}">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-slate-400 dark:text-slate-500 text-sm md:text-base">⚪</span> CSS Novice</span>
                                <span class="text-[9px] md:text-[10px] font-mono">0 XP</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 0. Modal INFO BADGE GAMIFIKASI --}}
            <div x-show="showBadgeModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showBadgeModal = false"></div>
                
                <div class="relative w-full max-w-sm bg-white dark:bg-[#0f141e] border rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-2xl transition-colors duration-300"
                     :class="'border-' + activeBadge?.color + '-200 dark:border-' + activeBadge?.color + '-500/40 dark:shadow-[0_20px_70px_rgba(var(--color-' + activeBadge?.color + '-500),0.15)]'"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="showBadgeModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 z-20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 md:w-32 h-24 md:h-32 rounded-full blur-[30px] md:blur-[40px] pointer-events-none transition-colors duration-300 opacity-20"
                             :class="'bg-' + activeBadge?.color + '-400 dark:bg-' + activeBadge?.color + '-500'"></div>

                        <div class="mb-4 md:mb-6 relative z-10 transition-colors duration-300 w-12 h-12 md:w-16 md:h-16 flex items-center justify-center" :class="'text-' + activeBadge?.color + '-600 dark:text-' + activeBadge?.color + '-400 drop-shadow-none dark:drop-shadow-[0_0_15px_rgba(var(--color-' + activeBadge?.color + '-500),0.8)]'" x-html="activeBadge?.icon"></div>
                        
                        <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 tracking-tight transition-colors" x-text="activeBadge?.name"></h3>
                        
                        <div class="mb-4 md:mb-6">
                            <span class="px-3 py-1 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-widest border transition-colors duration-300"
                                  :class="activeBadge?.status === 'Unlocked' ? 'bg-' + activeBadge?.color + '-50 dark:bg-' + activeBadge?.color + '-500/10 text-' + activeBadge?.color + '-700 dark:text-' + activeBadge?.color + '-400 border-' + activeBadge?.color + '-200 dark:border-' + activeBadge?.color + '-500/20' : 'bg-slate-100 dark:bg-slate-500/10 text-slate-500 dark:text-slate-400 border-slate-200 dark:border-slate-500/20'"
                                  x-text="activeBadge?.status === 'Unlocked' ? 'Berhasil Didapatkan' : 'Lencana Terkunci'">
                            </span>
                        </div>
                        
                        <div class="bg-slate-50 dark:bg-[#020617] w-full rounded-2xl p-4 md:p-5 border border-slate-200 dark:border-white/5 shadow-inner transition-colors">
                            <p class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/40 uppercase font-bold tracking-widest mb-2 border-b border-slate-200 dark:border-white/5 pb-2 text-left transition-colors">Syarat Perolehan</p>
                            <p class="text-slate-700 dark:text-slate-300 text-xs md:text-sm leading-relaxed text-left transition-colors" x-text="activeBadge?.description"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. Modal Insight Materi (Fuchsia) --}}
            <div x-show="showLessonModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showLessonModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(217,70,239,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-fuchsia-50 dark:bg-fuchsia-500/20 text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <button @click="showLessonModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Progres Teori</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Statistik ini menghitung jumlah slide/halaman materi teori yang telah Anda baca secara utuh.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-fuchsia-600 dark:text-fuchsia-400 transition-colors">{{ $lessonsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-slate-400 dark:text-white/30 font-bold transition-colors">/ {{ $totalLessons ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-fuchsia-600/70 dark:text-fuchsia-400/50 uppercase tracking-widest font-bold mt-2 transition-colors">Materi Diselesaikan ({{ $pctLesson ?? 0 }}%)</p>
                    </div>
                </div>
            </div>

            {{-- 2. Modal Insight Lab (Blue) --}}
            <div x-show="showLabModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showLabModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-blue-200 dark:border-blue-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(59,130,246,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-blue-50 dark:bg-blue-500/20 text-blue-600 dark:text-blue-400 border border-blue-200 dark:border-blue-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <button @click="showLabModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Hands-on Labs</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Sebuah lab dinyatakan <span class="text-emerald-600 dark:text-emerald-400 font-bold">Lulus (Passed)</span> jika Anda mendapatkan skor <b>70 ke atas</b> saat validasi kode.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-blue-600 dark:text-blue-400 transition-colors">{{ $labsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-slate-400 dark:text-white/30 font-bold transition-colors">/ {{ $totalLabs ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-blue-600/70 dark:text-blue-400/50 uppercase tracking-widest font-bold mt-2 transition-colors">Modul Praktikum Lulus</p>
                    </div>
                </div>
            </div>

            {{-- 3. Modal Insight Kuis (Cyan) --}}
            <div x-show="showQuizModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showQuizModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(34,211,238,0.15)] transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-cyan-50 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <button @click="showQuizModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Rata-rata Kuis</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Kalkulasi nilai ini diambil dari nilai rata-rata seluruh pengerjaan evaluasi teori (kuis) Anda.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-cyan-600 dark:text-cyan-400 transition-colors">{{ round($quizAverage ?? 0, 1) }}</span>
                        <p class="text-[9px] md:text-[10px] text-cyan-600/70 dark:text-cyan-400/50 uppercase tracking-widest font-bold mt-3 transition-colors">Rata-rata Poin (Pts)</p>
                    </div>
                </div>
            </div>

            {{-- 4. Modal Insight Bab Lulus (Emerald) --}}
            <div x-show="showChapterModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-sm transition-colors" @click="showChapterModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-3xl p-6 md:p-8 shadow-xl dark:shadow-[0_20px_70px_rgba(16,185,129,0.15)] transition-colors" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-emerald-50 dark:bg-emerald-500/20 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 transition-colors">
                            <svg class="w-6 h-6 md:w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <button @click="showChapterModal = false" class="text-slate-500 hover:text-slate-900 dark:hover:text-white transition p-2 rounded-lg bg-slate-100 dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-slate-900 dark:text-white mb-2 transition-colors">Bab Lulus</h3>
                    <p class="text-slate-600 dark:text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6 transition-colors">Satu bab teori dinyatakan terlewati apabila nilai akhir evaluasi Anda mencapai ambang batas <b>>= 70</b>.</p>
                    
                    <div class="bg-slate-50 dark:bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-slate-200 dark:border-white/5 shadow-inner text-center transition-colors">
                        <span class="text-4xl md:text-5xl font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $chaptersPassed ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-emerald-600/70 dark:text-emerald-400/50 uppercase tracking-widest font-bold mt-3 transition-colors">Total Bab Terselesaikan</p>
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

                        {{-- Kartu Info Akun --}}
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

{{-- Styles Dinamis Tailwind dari DB Badge (dengan penyesuaian dark/light mode via selector CSS induk `.dark`) --}}
<style>
    /* Menyiasati rendering dinamis Badge DB */
    .glass-card { background: rgba(255, 255, 255, 0.7); border: 1px solid rgba(0, 0, 0, 0.05); backdrop-filter: blur(16px); }
    .dark .glass-card { background: rgba(10, 14, 23, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); }

    .border-emerald-500\/40 { border-color: rgba(16, 185, 129, 0.4); } .from-emerald-500\/10 { --tw-gradient-from: rgba(16, 185, 129, 0.1); } .text-emerald-400 { color: rgba(52, 211, 153, 1); }
    .border-blue-500\/40 { border-color: rgba(59, 130, 246, 0.4); } .from-blue-500\/10 { --tw-gradient-from: rgba(59, 130, 246, 0.1); } .text-blue-400 { color: rgba(96, 165, 250, 1); }
    .border-indigo-500\/40 { border-color: rgba(99, 102, 241, 0.4); } .from-indigo-500\/10 { --tw-gradient-from: rgba(99, 102, 241, 0.1); } .text-indigo-400 { color: rgba(129, 140, 248, 1); }
    .border-cyan-500\/40 { border-color: rgba(6, 182, 212, 0.4); } .from-cyan-500\/10 { --tw-gradient-from: rgba(6, 182, 212, 0.1); } .text-cyan-400 { color: rgba(34, 211, 238, 1); }
    .border-fuchsia-500\/40 { border-color: rgba(217, 70, 239, 0.4); } .from-fuchsia-500\/10 { --tw-gradient-from: rgba(217, 70, 239, 0.1); } .text-fuchsia-400 { color: rgba(232, 121, 249, 1); }
    .border-amber-500\/40 { border-color: rgba(245, 158, 11, 0.4); } .from-amber-500\/10 { --tw-gradient-from: rgba(245, 158, 11, 0.1); } .text-amber-400 { color: rgba(251, 191, 36, 1); }
    .border-yellow-500\/40 { border-color: rgba(234, 179, 8, 0.4); } .from-yellow-500\/10 { --tw-gradient-from: rgba(234, 179, 8, 0.1); } .text-yellow-400 { color: rgba(250, 204, 21, 1); }
    .border-rose-500\/40 { border-color: rgba(244, 63, 94, 0.4); } .from-rose-500\/10 { --tw-gradient-from: rgba(244, 63, 94, 0.1); } .text-rose-400 { color: rgba(251, 113, 133, 1); }
    .border-slate-500\/40 { border-color: rgba(100, 116, 139, 0.4); } .from-slate-500\/10 { --tw-gradient-from: rgba(100, 116, 139, 0.1); } .text-slate-400 { color: rgba(148, 163, 184, 1); }
    .border-red-500\/40 { border-color: rgba(239, 68, 68, 0.4); } .from-red-500\/10 { --tw-gradient-from: rgba(239, 68, 68, 0.1); } .text-red-400 { color: rgba(248, 113, 113, 1); }
    
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; } 
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } 
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; } 
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
    .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); } 
    .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    #animated-bg { background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.08), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.08), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.08), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; } 
    .dark #animated-bg { background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%); }
    
    @keyframes bgMove{to{transform:scale(1.15)}}
    .animate-spin-slow { animation: spin 8s linear infinite; } @keyframes spin { 100% { transform: rotate(360deg); } }
    
    [data-title]:hover::after { content: attr(data-title); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background: #1e293b; color: #fff; padding: 4px 8px; font-size: 10px; border-radius: 4px; white-space: nowrap; pointer-events: none; z-index: 50; border: 1px solid rgba(255,255,255,0.1); box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
    .dark [data-title]:hover::after { background: #000; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
    
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } 
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    [x-cloak] { display: none !important; }

    /* SISTEM TOOLTIP SUPER SOLID */
    .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
    .tooltip-container:hover { z-index: 99999; }
    .tooltip-trigger { width: 18px; height: 18px; border-radius: 50%; color: white; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.1); }
    .dark .tooltip-trigger { border: 1px solid rgba(255,255,255,0.2); }
    .tooltip-trigger:hover { transform: scale(1.15); }
    .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 200px; max-width: 280px; white-space: normal; text-align: left; background-color: #ffffff; color: #1e293b; border: 1px solid #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,0.1); z-index: 99999; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .dark .tooltip-content { background-color: #020617; color: #e2e8f0; border: none; box-shadow: 0 20px 60px rgba(0,0,0,1); }
    .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); } .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; } .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #ffffff transparent; } 
    .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #020617 transparent; }
    .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); } .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); } .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }
    
    .tooltip-blue .tooltip-trigger { background-color: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.3); } .tooltip-blue .tooltip-trigger:hover { background-color: #60a5fa; box-shadow: 0 0 15px rgba(59,130,246,0.6); }
    .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.3); } .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.6); } .dark .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }
    .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.3); } .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.6); } .dark .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }
    .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.3); } .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.6); } .dark .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }
</style>

{{-- Script SweetAlert (Menggunakan pengecekan Theme) --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const swalBg = isDark ? '#0f141e' : '#ffffff';
        const swalColor = isDark ? '#fff' : '#1e293b';

        @if(session('success')) Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#10b981' }); @endif
        @if(session('error')) Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: swalBg, color: swalColor, iconColor: '#ef4444' }); @endif
        @if(session('info')) Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: swalBg, color: swalColor, iconColor: '#3b82f6' }); @endif
    });
</script>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const isDarkMode = document.documentElement.classList.contains('dark');
        
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
                        pointBackgroundColor: isDarkMode ? '#020617' : '#ffffff', 
                        pointBorderColor: '#e879f9', 
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
                        x: { 
                            grid: { display: false }, 
                            ticks: { color: 'rgba(150, 150, 150, 0.8)', font: { family: 'monospace' } } 
                        }, 
                        y: { 
                            beginAtZero: true, 
                            max: 100, 
                            grid: { color: 'rgba(150, 150, 150, 0.15)' }, 
                            ticks: { color: 'rgba(150, 150, 150, 0.8)' } 
                        } 
                    } 
                } 
            });
        }
        
        // Render Live Log langsung dari PHP agar sempurna tanpa delay fetch API
        const liveLogs = @json($liveLogData);
        renderActivityLog(liveLogs);
        
        // Fetch heatmap secara asynchronous agar tidak membebani render
        fetchHeatmapData();
    });

    async function fetchHeatmapData() {
        try {
            const response = await fetch("{{ route('api.dashboard.progress') }}", { headers: { 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('API Error');
            const data = await response.json();
            renderHeatmap(data.activity_timeline || []);
        } catch (error) { 
            console.error("Sync Error:", error);
        }
    }

    function renderHeatmap(timeline) {
        const el = document.getElementById('heatmap'); if(!el) return; el.innerHTML = '';
        const map = {}; timeline.forEach(t => map[t.date] = t.count);
        for(let i=83; i>=0; i--) {
            const d = new Date(); d.setDate(d.getDate()-i); const k = d.toISOString().split('T')[0]; const v = map[k]||0;
            let c = 'bg-slate-200 dark:bg-white/5'; 
            if(v>=1) c='bg-cyan-400 dark:bg-cyan-500/40 shadow-none dark:shadow-[0_0_5px_#22d3ee]'; 
            if(v>=3) c='bg-fuchsia-500 shadow-none dark:shadow-[0_0_8px_#d946ef]';
            const div = document.createElement('div'); div.className = `w-2 md:w-2.5 h-2 md:h-2.5 rounded-[2px] ${c} relative cursor-pointer hover:scale-150 transition hover:z-20 hover:border hover:border-white`; div.setAttribute('data-title', `${k}: ${v} Aktivitas`); el.appendChild(div);
        }
    }

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList'); if(!list) return; list.innerHTML = '';
        const insightContainer = document.getElementById('liveLogInsight');
        const globalUserXp = {{ $user->xp ?? 0 }};
        
        if (logs.length === 0) { 
            list.innerHTML = `<li class="text-slate-400 dark:text-white/30 text-center text-xs italic py-10 transition-colors">Belum ada aktivitas gamifikasi.</li>`; 
            if(insightContainer) insightContainer.innerHTML = '';
            return; 
        }
        
        let totalExpFromLogs = 0;

        logs.forEach((item, index) => {
            let typeLower = item.type ? item.type.toLowerCase() : '';
            let activityName = item.activity || item.name || '';
            
            // Konfigurasi Default (Materi)
            let icon = '📖'; 
            let iconBg = 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border-cyan-200 dark:border-cyan-500/20';
            let typeLabel = 'Membaca Materi';
            let typeColor = 'text-cyan-600 dark:text-cyan-400';
            let expGained = 10; 
            let criteria = 'Selesai Membaca';

            // Logika Jenis Aktivitas Gamifikasi (Sama persis seperti orisinal)
            if (typeLower === 'kuis' || typeLower === 'quiz' || activityName.toLowerCase().includes('evaluasi')) { 
                icon = '📝'; 
                iconBg = 'bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 border-fuchsia-200 dark:border-fuchsia-500/20'; 
                typeLabel = 'Evaluasi Kuis';
                typeColor = 'text-fuchsia-600 dark:text-fuchsia-400';
                expGained = item.score || item.exp || 0;
                criteria = '1 Pts = 1 XP';
            } else if (typeLower === 'lab' || activityName.toLowerCase().includes('lab'))  { 
                icon = '💻'; 
                iconBg = 'bg-blue-50 dark:bg-blue-500/10 text-blue-600 dark:text-blue-400 border-blue-200 dark:border-blue-500/20'; 
                typeLabel = 'Praktik Lab';
                typeColor = 'text-blue-600 dark:text-blue-400';
                expGained = (item.status === 'Lulus' || item.status === 'Passed' || (item.score && item.score >= 70)) ? 50 : 0;
                criteria = 'Lulus KKM (>=70)';
            } else if (typeLower === 'badge' || typeLower === 'lencana' || activityName.toLowerCase().includes('lencana')) {
                icon = '🎖️'; 
                iconBg = 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border-amber-200 dark:border-amber-500/20'; 
                typeLabel = 'Lencana Didapat';
                typeColor = 'text-amber-600 dark:text-amber-400';
                expGained = 0; 
                criteria = 'Reward Milestone';
            }

            // Fallback jika API membawa nilai eksp eksplisit
            if (item.exp !== undefined) expGained = item.exp; 
            
            // Akumulasi total XP untuk Insight Card
            totalExpFromLogs += parseInt(expGained);

            // Bangun Lencana EXP Interaktif
            let expBadge = '';
            if (expGained > 0) {
                expBadge = `
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] font-black text-indigo-700 dark:text-indigo-400 bg-indigo-50 dark:bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-200 dark:border-indigo-500/20 shadow-sm dark:shadow-[0_0_8px_rgba(99,102,241,0.3)] transition-colors">+${expGained} XP</span>
                        <span class="text-[7px] text-indigo-500/80 dark:text-indigo-400/50 mt-1 font-mono uppercase tracking-widest leading-none transition-colors">${criteria}</span>
                    </div>
                `;
            } else if (typeLower === 'badge' || typeLower === 'lencana' || activityName.toLowerCase().includes('lencana')) {
                expBadge = `
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] font-black text-amber-700 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-200 dark:border-amber-500/20 shadow-sm dark:shadow-[0_0_8px_rgba(251,191,36,0.3)] transition-colors">REWARD</span>
                        <span class="text-[7px] text-amber-600/80 dark:text-amber-400/50 mt-1 font-mono uppercase tracking-widest leading-none transition-colors">${criteria}</span>
                    </div>
                `;
            } else {
                expBadge = `
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] font-black text-slate-500 bg-slate-100 dark:bg-white/5 px-1.5 py-0.5 rounded border border-slate-200 dark:border-white/10 transition-colors">+0 XP</span>
                        <span class="text-[7px] text-slate-500/80 dark:text-slate-500/50 mt-1 font-mono uppercase tracking-widest leading-none transition-colors">Remedial</span>
                    </div>
                `;
            }

            // Batasi delay agar performa tetap cepat
            const delay = (index > 15 ? 0 : index) * 100;
            const statusDisplay = item.status === 'Lulus' || item.status === 'Passed' || item.status === 'Didapatkan' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border border-emerald-200 dark:border-transparent' : (item.status === 'Selesai' ? 'bg-cyan-50 dark:bg-cyan-500/10 text-cyan-700 dark:text-cyan-400 border border-cyan-200 dark:border-transparent' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border border-red-200 dark:border-transparent');

            list.insertAdjacentHTML('beforeend', `
                <li x-show="logFilter === 'all' || logFilter === '${typeLower}'" x-transition class="group flex items-center gap-3 p-3 rounded-xl bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.05] transition-colors border border-slate-200 dark:border-white/5 animate-fade-in-up" style="animation-delay: ${delay}ms">
                    <div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0 font-bold text-[10px] md:text-xs shadow-sm dark:shadow-inner transition-colors">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <h4 class="text-[10px] md:text-xs font-bold text-slate-800 dark:text-white truncate w-32 md:w-40 transition-colors" title="${activityName}">${activityName}</h4>
                            ${item.status ? `<span class="text-[8px] md:text-[9px] font-bold px-1.5 py-0.5 rounded transition-colors ${statusDisplay}">${item.status}</span>` : ''}
                        </div>
                        <div class="flex justify-between items-end mt-1">
                            <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-mono">
                                <span class="uppercase tracking-wider font-bold transition-colors ${typeColor}">${typeLabel}</span>
                                <span class="w-1 h-1 rounded-full bg-slate-300 dark:bg-white/20 transition-colors"></span>
                                <span class="text-slate-500 dark:text-white/40 transition-colors">${item.time || item.date || ''}</span>
                            </div>
                            ${expBadge}
                        </div>
                    </div>
                </li>
            `);
        });

        // Tampilkan insight total XP dari histori aktivitas terkini
        if(insightContainer) {
            insightContainer.innerHTML = `
                <div class="flex items-center gap-3 justify-end transition-colors">
                    <div class="text-right">
                        <p class="text-[8px] uppercase tracking-widest text-slate-500 dark:text-slate-500 font-bold mb-0.5 transition-colors">XP Log Terakhir</p>
                        <p class="text-xs font-bold text-emerald-600 dark:text-emerald-400 transition-colors">+${totalExpFromLogs}</p>
                    </div>
                    <div class="w-px h-6 bg-slate-200 dark:bg-white/10 transition-colors"></div>
                    <div class="text-right">
                        <p class="text-[8px] uppercase tracking-widest text-indigo-500 dark:text-indigo-400/80 font-bold mb-0.5 transition-colors">Total Keseluruhan</p>
                        <p class="text-xs font-black text-indigo-600 dark:text-indigo-400 drop-shadow-none dark:drop-shadow-[0_0_5px_rgba(99,102,241,0.8)] transition-colors">${new Intl.NumberFormat('id-ID').format(globalUserXp)} XP</p>
                    </div>
                </div>
            `;
        }
    }
</script>
@endsection