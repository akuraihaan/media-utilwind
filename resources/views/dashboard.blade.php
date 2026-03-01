@extends('layouts.landing')

@section('title', 'Dashboard Siswa ')

@section('content')

{{-- ==============================================================================
     LOGIKA QUERY 
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
                'status' => 'Didapatkan',
                'exp' => 0, // Ditandai sebagai REWARD di UI
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

<div id="appRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30 pt-20">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
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
                    
                    {{-- Navigasi Materi Kembali ke Asal --}}
                    @php $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group)); @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                            <span class="grayscale group-hover:grayscale-0 transition text-lg">📚</span> Materi Belajar
                        </a>
                    @else
                        <button class="w-full text-left group flex items-center justify-between px-4 py-3 rounded-xl bg-red-500/5 text-red-400/80 cursor-not-allowed border border-transparent">
                            <div class="flex items-center gap-3"><span class="grayscale opacity-50 text-lg">📚</span> <span class="font-medium">Materi Belajar</span></div>
                            <svg class="w-4 h-4 text-red-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </button>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">⚙️</span> Pengaturan
                    </a>
                    
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">👨‍💻</span> Informasi
                    </a>
                </nav>
            </div>
            <div class="mt-auto p-6 shrink-0">
                <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-900/20 to-purple-900/20 border border-white/5 text-center shadow-inner">
                    <p class="text-[10px] text-white/50 italic">"Code is like humor. When you have to explain it, it’s bad."</p>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10">
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                {{-- TOMBOL HAMBURGER MOBILE --}}
                <div class="flex items-center gap-4 mb-2 lg:hidden">
                    <button @click="sidebarOpen = true" class="p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition border border-white/10 shadow-lg">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <span class="text-sm font-bold text-white uppercase tracking-widest opacity-50">Menu Dasbor</span>
                </div>

                 {{-- =========================================================
                     HEADER PAGE & STATUS KELAS
                     ========================================================= --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        {{-- BREADCRUMB START --}}
                        <nav class="flex items-center gap-2 mb-4 text-[10px] md:text-xs font-bold uppercase tracking-widest text-white/40" aria-label="Breadcrumb">
                            <a href="/" class="hover:text-white transition-colors flex items-center gap-1.5">
                                <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                                Home
                            </a>
                            <span class="text-white/20">/</span>
                            <span class="text-fuchsia-400 drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]">Dashboard</span>
                        </nav>
                        {{-- BREADCRUMB END --}}

                        <h1 class="text-4xl font-black text-white mb-2 tracking-tight">
                            <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Dashboard</span>
                        </h1>
                        <p class="text-white/60 text-lg">Pantau pencapaian materi, XP, koleksi lencana, dan analitik Anda.</p>
                        
                        <div class="mt-6 inline-flex items-center gap-4 px-4 py-2.5 rounded-2xl bg-white/[0.02] border border-white/10 shadow-inner w-full md:w-auto">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white shadow-lg text-lg shrink-0">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] text-white/40 uppercase tracking-widest font-bold mb-0.5">Status Kelas</p>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full shrink-0 {{ Auth::user()->class_group ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-yellow-500 shadow-[0_0_8px_#eab308] animate-pulse' }}"></span>
                                    <span class="text-sm font-bold text-white truncate">{{ Auth::user()->class_group ?? 'Belum Terhubung ke Kelas' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-start md:items-end gap-4 w-full md:w-auto">
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
                            <div class="px-5 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 shadow-inner flex items-center justify-center md:justify-start gap-3 w-full md:w-auto">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Tergabung di Kelas</span>
                            </div>
                        @endempty
                    </div>
                </div>

                {{-- ALERT LAB AKTIF (Resume) --}}
                @if(isset($activeSession) && $activeSession)
                <div class="rounded-2xl bg-indigo-900/40 border border-indigo-500/30 p-4 flex flex-col md:flex-row items-start md:items-center justify-between gap-4 shadow-lg shadow-indigo-900/20 mb-2 animate-pulse-slow">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center animate-pulse shadow-[0_0_15px_#6366f1] shrink-0">
                            <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <div>
                            <h3 class="font-bold text-white">Lab Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktikum' }}</h3>
                            <p class="text-indigo-200 text-xs">Aktivitas koding Anda belum diselesaikan.</p>
                        </div>
                    </div>
                    <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="w-full md:w-auto px-5 py-2 bg-indigo-500 hover:bg-indigo-400 text-white text-center font-bold rounded-lg text-sm transition shadow-lg hover:shadow-indigo-500/50 flex items-center justify-center gap-2">
                        Lanjut Coding <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </a>
                </div>
                @endif

                {{-- =========================================================
                     GAMIFIKASI ZONE
                     ========================================================= --}}
                <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8 reveal">
                    {{-- 1. LEVEL & XP CARD --}}
                    <div @click="showTitleModal = true" class="xl:col-span-3 glass-card rounded-[2rem] p-6 md:p-10 border-t-2 border-t-indigo-500/50 relative overflow-hidden flex flex-col md:flex-row items-center gap-8 shadow-2xl cursor-pointer hover:border-indigo-500/80 transition duration-300 group">
                        <div class="absolute right-0 top-0 w-64 h-64 bg-indigo-500/10 rounded-full blur-[80px] pointer-events-none group-hover:bg-indigo-500/20 transition"></div>
                        <div class="relative shrink-0 text-center">
                            <div class="w-28 h-28 rounded-full bg-[#020617] border-[4px] border-indigo-500 flex items-center justify-center flex-col shadow-[0_0_40px_rgba(99,102,241,0.3)] relative z-10 overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/20 to-cyan-500/20 animate-spin-slow group-hover:opacity-100 transition"></div>
                                <span class="text-[10px] text-indigo-400 font-bold uppercase tracking-widest mt-2 relative z-10">Total XP</span>
                                <span class="text-2xl font-black text-white leading-none relative z-10">{{ number_format($user->xp ?? 0) }}</span>
                            </div>
                            <div class="absolute -bottom-3 left-1/2 -translate-x-1/2 bg-indigo-500 text-[#020617] text-[10px] font-black uppercase tracking-widest px-4 py-1.5 rounded-full z-20 whitespace-nowrap shadow-lg">{{ $user->developer_title ?? 'Intern Coder' }}</div>
                        </div>
                        <div class="flex-1 w-full relative z-10 mt-4 md:mt-0">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-3 gap-2">
                                <div>
                                    <h3 class="text-xl font-bold text-white flex items-center gap-2">Jejak Karir Developer <div class="w-4 h-4 rounded-full bg-indigo-500/20 text-indigo-400 flex items-center justify-center border border-indigo-500/30 text-[9px]">?</div></h3>
                                    <p class="text-xs text-slate-400 mt-1">Kumpulkan XP untuk mencapai title Tailwind Architect.</p>
                                </div>
                                <div class="text-left sm:text-right">
                                    <span class="inline-block px-3 py-1 rounded-md bg-indigo-500/10 border border-indigo-500/20 text-xs font-bold text-indigo-400">Next Target: {{ number_format($user->next_level_xp ?? 500) }} XP</span>
                                </div>
                            </div>
                            <div class="w-full h-3.5 bg-[#020617] rounded-full overflow-hidden border border-white/10 shadow-inner relative">
                                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-20"></div>
                                <div class="h-full bg-gradient-to-r from-cyan-400 via-indigo-500 to-fuchsia-500 shadow-[0_0_15px_#818cf8] transition-all duration-[2s] ease-out rounded-full" style="width: {{ $user->xp_progress ?? 0 }}%;"></div>
                            </div>
                        </div>
                    </div>

                    {{-- 2. BADGES COLLECTION --}}
                    <div class="xl:col-span-2 glass-card rounded-[2rem] p-6 md:p-8 relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 border-b border-white/5 pb-4">
                            <h3 class="text-xl font-bold text-white flex items-center gap-3">
                                <div class="w-8 h-8 rounded-lg bg-fuchsia-500/20 text-fuchsia-400 flex items-center justify-center border border-fuchsia-500/30"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/></svg></div> Digital Badges
                            </h3>
                            <span class="px-3 py-1 bg-white/5 border border-white/10 rounded-lg text-xs font-bold text-slate-300 font-mono">{{ count($unlockedBadges ?? []) }} / {{ count($allBadges ?? []) }} Terbuka</span>
                        </div>
                        <div class="grid grid-cols-3 sm:grid-cols-4 gap-4 overflow-y-auto custom-scrollbar pr-1 max-h-[300px]">
                            @forelse($allBadges ?? [] as $badge)
                                @php
                                    $isUnlocked = in_array($badge->id, $unlockedBadges ?? []);
                                    $c = $badge->color ?? 'indigo';
                                    $badgeData = json_encode(['name'=>$badge->name, 'description'=>$badge->description, 'color'=>$c, 'icon'=>$badge->icon, 'status'=>$isUnlocked?'Unlocked':'Locked']);
                                @endphp
                                <div @click="activeBadge = {{ $badgeData }}; showBadgeModal = true" class="aspect-square bg-[#0a0e17] border {{ $isUnlocked ? 'border-'.$c.'-500/40 shadow-[0_0_20px_rgba(var(--color-'.$c.'-500),0.15)] group hover:-translate-y-1' : 'border-white/5 opacity-40 grayscale hover:grayscale-[0.5]' }} p-3 md:p-4 rounded-2xl flex flex-col items-center text-center transition cursor-pointer relative overflow-hidden">
                                    @if($isUnlocked)<div class="absolute inset-0 bg-gradient-to-b from-{{$c}}-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-300"></div>@endif
                                    <div class="w-8 h-8 md:w-10 md:h-10 {{ $isUnlocked ? 'text-'.$c.'-400 group-hover:scale-110 drop-shadow-[0_0_15px_rgba(var(--color-'.$c.'-500),0.8)]' : 'text-white/40' }} mb-2 transition relative z-10 flex justify-center">{!! $badge->icon !!}</div>
                                    <p class="text-[8px] md:text-[10px] font-black uppercase tracking-widest {{ $isUnlocked ? 'text-'.$c.'-400' : 'text-white/50' }} relative z-10">{{ $badge->name }}</p>
                                </div>
                            @empty
                                <div class="col-span-full py-8 text-center border-2 border-dashed border-white/10 rounded-2xl bg-white/[0.02]"><p class="text-xs text-slate-400 italic">No badges found.</p></div>
                            @endforelse
                        </div>
                    </div>

                    {{-- 3. LEADERBOARD KELAS --}}
                    <div class="xl:col-span-1 glass-card rounded-[2rem] p-6 md:p-8 border-t-2 border-t-yellow-500/50 flex flex-col relative overflow-hidden">
                        <div class="absolute right-0 top-0 w-40 h-40 bg-yellow-500/10 rounded-full blur-[60px] pointer-events-none"></div>
                        <div class="flex items-center gap-3 mb-2 relative z-10">
                            <div class="w-8 h-8 rounded-lg bg-yellow-500/20 text-yellow-400 flex items-center justify-center border border-yellow-500/30 text-lg shadow-[0_0_15px_rgba(234,179,8,0.2)]">🏆</div>
                            <h3 class="text-xl font-bold text-white">Leaderboard</h3>
                        </div>
                        <p class="text-[10px] text-slate-400 mb-6 border-b border-white/5 pb-4 relative z-10">Top 5 Coder di kelas {{ $user->class_group ?? 'Anda' }}</p>
                        
                        <div class="space-y-3 relative z-10 flex-1 overflow-y-auto custom-scrollbar pr-1">
                            @forelse($leaderboard ?? [] as $index => $boardUser)
                                @php
                                    $isMe = $boardUser->id === Auth::id();
                                    $bg = $isMe ? 'bg-indigo-500/20 border-indigo-500/40 shadow-lg' : 'bg-white/[0.02] border-white/5';
                                    $rankColor = match($index) { 0 => 'bg-yellow-500 text-black', 1 => 'bg-slate-300 text-black', 2 => 'bg-amber-700 text-white', default => 'bg-[#0f141e] text-white/50 border border-white/10' };
                                    $textColor = $isMe ? 'text-indigo-300' : ($index == 0 ? 'text-yellow-400' : 'text-white');
                                @endphp
                                <div class="flex items-center gap-3 p-3.5 rounded-xl border {{ $bg }} transition hover:scale-[1.02]">
                                    <span class="w-7 h-7 rounded-full {{ $rankColor }} flex items-center justify-center text-xs font-black shrink-0">{{ $index + 1 }}</span>
                                    <div class="flex-1 min-w-0"><p class="text-sm font-bold {{ $textColor }} truncate">{{ $isMe ? 'Anda ('.$boardUser->name.')' : $boardUser->name }}</p></div>
                                    <span class="text-xs font-black font-mono text-white/50 {{ $isMe ? 'text-indigo-400' : '' }} {{ $index == 0 && !$isMe ? 'text-yellow-500' : '' }}">{{ number_format($boardUser->xp) }} XP</span>
                                </div>
                            @empty
                                <div class="text-center py-8 text-white/30 text-xs italic">Leaderboard belum tersedia.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- VISUAL SEPARATOR --}}
                <div class="flex items-center gap-4 py-4">
                    <div class="h-px bg-white/10 flex-1"></div>
                    <span class="text-[10px] font-black text-slate-600 uppercase tracking-[0.2em] bg-[#020617] px-3 py-1 border border-white/5 rounded-full whitespace-nowrap">Academic Analytics</span>
                    <div class="h-px bg-white/10 flex-1"></div>
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
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-white/10 bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-fuchsia-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showLessonModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-fuchsia-400 transition">Materi Bacaan</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-white group-hover:text-fuchsia-400 transition">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-sm md:text-lg">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-3 md:mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-fuchsia-500 shadow-[0_0_10px_#d946ef] transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-white/10 bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-blue-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showLabModal = true">
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-blue-400 transition">Hands-on Labs</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-white group-hover:text-blue-400 transition">{{ $labsCompleted ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-sm md:text-lg">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-3 md:mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-blue-500 shadow-[0_0_10px_#3b82f6] transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-white/10 bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-cyan-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showQuizModal = true">
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-cyan-400 transition">Rata-rata Kuis</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-white group-hover:text-cyan-400 transition">{{ round($quizAverage ?? 0, 1) }}</span>
                                <span class="text-white/40 font-bold text-sm md:text-lg">pts</span>
                            </div>
                            <p class="text-[9px] md:text-[10px] text-white/30 mt-3 md:mt-4 font-mono">Dari {{ $quizzesCompleted ?? 0 }} x percobaan evaluasi.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="relative overflow-visible rounded-2xl md:rounded-3xl border border-white/10 bg-[#0f141e] p-5 md:p-6 group hover:-translate-y-1 hover:border-emerald-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showChapterModal = true">
                        <div class="relative z-10">
                            <p class="text-[9px] md:text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-emerald-400 transition">Bab Lulus</p>
                            <div class="flex items-baseline gap-1 mt-2">
                                <span class="text-3xl md:text-4xl font-black text-white group-hover:text-emerald-400 transition">{{ $chaptersPassed ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-sm md:text-lg">Bab</span>
                            </div>
                            <p class="text-[9px] md:text-[10px] text-emerald-400/50 mt-3 md:mt-4 font-bold uppercase tracking-wider">Keep Going!</p>
                        </div>
                    </div>
                </div>

                {{-- =========================================================
                     CHART & LOGS DENGAN FILTER & EXP GAMIFIKASI ULTIMATE
                     ========================================================= --}}
                <div class="grid lg:grid-cols-3 gap-6 md:gap-8 reveal" style="animation-delay: 0.3s;">
                    <div class="lg:col-span-2 space-y-6 md:space-y-8">
                        
                        {{-- GRAFIK KUIS --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-6 md:p-8 backdrop-blur-xl shadow-lg relative overflow-hidden">
                            <h3 class="text-base md:text-lg font-bold text-white">Grafik Perkembangan Nilai</h3>
                            <p class="text-[10px] md:text-xs text-white/40 mt-0.5 mb-6">Visualisasi hasil evaluasi kuis terbaik Anda per bab.</p>
                            <div class="relative h-[200px] md:h-[250px] w-full z-10">
                                @if(isset($chartData['scores']) && count($chartData['scores']) > 0)
                                    <canvas id="quizChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-xl bg-white/[0.01]">
                                        <p class="text-xs font-semibold text-slate-400">Belum Ada Data Kuis</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        {{-- TABEL HISTORY (FILTER ONLY LAB & KUIS WITH EXP - BISA SCROLL SEMUA) --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-6 md:p-8 backdrop-blur-xl flex flex-col h-[450px]" x-data="{ filterTable: 'all' }">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-4 md:mb-6 shrink-0 border-b border-white/5 pb-4">
                                <h3 class="text-base md:text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">🕒</span> Riwayat Pengerjaan
                                </h3>
                                
                                {{-- Filter Interaktif AlpineJS --}}
                                <div class="flex items-center bg-[#020617] p-1 rounded-lg border border-white/5 shadow-inner">
                                    <button @click="filterTable = 'all'" :class="filterTable === 'all' ? 'bg-indigo-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Semua</button>
                                    <button @click="filterTable = 'kuis'" :class="filterTable === 'kuis' ? 'bg-fuchsia-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Kuis</button>
                                    <button @click="filterTable = 'lab'" :class="filterTable === 'lab' ? 'bg-blue-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[10px] font-bold transition">Lab</button>
                                </div>
                            </div>
                            
                            <div class="overflow-x-auto custom-scrollbar -mx-6 md:mx-0 px-6 md:px-0 flex-1 relative">
                                <div class="absolute top-0 bottom-0 right-0 w-4 bg-gradient-to-l from-[#0f141e] to-transparent pointer-events-none md:hidden z-30"></div>
                                <div class="max-h-[300px] overflow-y-auto custom-scrollbar pr-2 h-full pb-10">
                                    <table class="w-full text-left border-collapse min-w-[400px] relative">
                                        <thead class="sticky top-0 z-20 bg-[#0f141e] shadow-md after:absolute after:bottom-0 after:left-0 after:right-0 after:h-px after:bg-white/10">
                                            <tr class="text-[10px] md:text-xs text-white/30 uppercase tracking-widest">
                                                <th class="py-3 pl-2">Aktivitas</th>
                                                <th class="py-3 hidden sm:table-cell">Waktu</th>
                                                <th class="py-3 text-right pr-2">Skor & EXP</th>
                                            </tr>
                                        </thead>
                                        <tbody class="text-xs md:text-sm text-white/70">
                                            @forelse($tableHistory as $item)
                                                @php
                                                    $typeLower = isset($item['type']) ? strtolower($item['type']) : '';
                                                    $typeLabel = 'Aktivitas';
                                                    $typeColor = 'text-slate-400';
                                                    $gainedXp = isset($item['exp']) ? $item['exp'] : 0;
                                                    $iconBg = 'bg-white/5 text-white/50 border-white/10';
                                                    $icon = '✓';

                                                    if ($typeLower == 'kuis' || $typeLower == 'quiz') {
                                                        $typeLabel = 'Evaluasi Kuis';
                                                        $typeColor = 'text-fuchsia-400';
                                                        $iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20';
                                                        $icon = '📝';
                                                        if(!$gainedXp) $gainedXp = isset($item['score']) ? $item['score'] : 0;
                                                    } elseif ($typeLower == 'lab') {
                                                        $typeLabel = 'Praktik Lab';
                                                        $typeColor = 'text-blue-400';
                                                        $iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20';
                                                        $icon = '💻';
                                                        if(!$gainedXp) $gainedXp = (isset($item['score']) && $item['score'] >= 70) ? 50 : 0;
                                                    }
                                                @endphp
                                                <tr x-show="filterTable === 'all' || filterTable === '{{ $typeLower }}'" class="group hover:bg-white/5 transition border-b border-white/5 last:border-0" x-transition>
                                                    <td class="py-3 md:py-4 pl-2 font-medium text-white flex items-center gap-3">
                                                        <div class="w-6 h-6 md:w-8 md:h-8 rounded flex items-center justify-center text-[10px] md:text-xs font-bold shadow-lg shrink-0 border {{ $iconBg }}">
                                                            {{ $icon }}
                                                        </div>
                                                        <div class="flex flex-col min-w-0">
                                                            <span class="truncate text-xs font-bold text-white group-hover:text-indigo-300 transition" title="{{ $item['name'] }}">{{ $item['name'] }}</span>
                                                            <div class="flex items-center gap-1.5 mt-0.5">
                                                                <span class="text-[9px] md:text-[10px] uppercase font-bold tracking-wider {{ $typeColor }}">{{ $typeLabel }}</span>
                                                                <span class="w-1 h-1 rounded-full bg-white/20 sm:hidden"></span>
                                                                <span class="text-[9px] md:text-[10px] text-white/40 font-mono sm:hidden">{{ \Carbon\Carbon::parse($item['date'])->diffForHumans() }}</span>
                                                            </div>
                                                        </div>
                                                    </td>
                                                    <td class="py-3 md:py-4 text-[10px] md:text-xs font-mono text-white/50 hidden sm:table-cell">
                                                        {{ \Carbon\Carbon::parse($item['date'])->diffForHumans() }}
                                                    </td>
                                                    <td class="py-3 md:py-4 text-right pr-2 shrink-0">
                                                        <div class="flex flex-col items-end gap-1.5">
                                                            @if(isset($item['score']))
                                                                <span class="px-2 md:px-3 py-0.5 rounded-full text-[9px] md:text-[10px] font-bold border {{ $item['score'] >= 70 ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-red-400 bg-red-500/10 border-red-500/20' }}">{{ $item['score'] }} pts</span>
                                                            @endif
                                                            
                                                            @if($gainedXp > 0)
                                                                <span class="text-[8px] md:text-[9px] font-black text-indigo-400 bg-indigo-500/10 px-2 py-0.5 rounded border border-indigo-500/20 shadow-sm" title="Mendapatkan {{ $gainedXp }} XP dari {{ $typeLabel }}">+{{ $gainedXp }} XP</span>
                                                            @endif
                                                        </div>
                                                    </td>
                                                </tr>
                                            @empty
                                                <tr><td colspan="3" class="py-6 text-center text-white/30 italic text-xs">Belum ada riwayat pengerjaan kuis atau lab.</td></tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="lg:col-span-1 space-y-6 md:space-y-8">
                        {{-- Heatmap --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-6 md:p-8 backdrop-blur-xl">
                            <div class="flex items-center gap-2 mb-4">
                                <h3 class="text-xs md:text-sm font-bold text-white/70 uppercase tracking-wider">Konsistensi Belajar</h3>
                                <span class="text-lg">🔥</span>
                            </div>
                            <div id="heatmap" class="flex flex-wrap gap-1 md:gap-1.5 content-start min-h-[100px] md:min-h-[150px]"></div>
                            <div class="mt-4 flex gap-3 md:gap-4 text-[9px] md:text-[10px] text-white/30 uppercase tracking-wider font-bold">
                                <span class="flex items-center gap-1.5"><div class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-[2px] bg-white/5"></div> 0</span>
                                <span class="flex items-center gap-1.5"><div class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-[2px] bg-cyan-500/50"></div> 1-2</span>
                                <span class="flex items-center gap-1.5"><div class="w-2 h-2 md:w-2.5 md:h-2.5 rounded-[2px] bg-fuchsia-500"></div> 3+</span>
                            </div>
                        </div>

                        {{-- Log Real-time (SELURUH AKTIVITAS GAMIFIKASI MENGGUNAKAN ALPINEJS UNTUK FILTER) --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-6 md:p-8 backdrop-blur-xl h-[450px] flex flex-col relative overflow-hidden" x-data="{ logFilter: 'all' }">
                            <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end mb-4 relative z-10 border-b border-white/5 pb-4 shrink-0 gap-4">
                                <div>
                                    <h3 class="text-xs md:text-sm font-bold text-white/70 uppercase tracking-wider mb-2">Live Log EXP</h3>
                                    <div class="flex items-center bg-[#020617] p-1 rounded-lg border border-white/5 shadow-inner w-max">
                                        <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-indigo-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Semua</button>
                                        <button @click="logFilter = 'materi'" :class="logFilter === 'materi' ? 'bg-cyan-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Materi</button>
                                        <button @click="logFilter = 'kuis'" :class="logFilter === 'kuis' ? 'bg-fuchsia-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Kuis</button>
                                        <button @click="logFilter = 'lab'" :class="logFilter === 'lab' ? 'bg-blue-500 text-white shadow-md' : 'text-white/40 hover:text-white'" class="px-3 py-1.5 rounded-md text-[9px] font-bold transition">Lab</button>
                                    </div>
                                </div>
                                <div class="text-left sm:text-right">
                                    <p class="text-[8px] uppercase tracking-widest text-indigo-400/80 font-bold mb-0.5">Total Keseluruhan</p>
                                    <p class="text-xs font-black text-indigo-400 drop-shadow-[0_0_5px_rgba(99,102,241,0.8)]">{{ number_format($user->xp ?? 0) }} XP</p>
                                </div>
                            </div>

                            <ul class="space-y-2 md:space-y-3 overflow-y-auto custom-scrollbar pr-2 flex-1 relative z-10 pb-10">
                                @forelse($liveLogData as $index => $item)
                                    @php
                                        $typeLower = isset($item['type']) ? strtolower($item['type']) : '';
                                        $activityName = $item['activity'] ?? $item['name'] ?? '';
                                        
                                        $icon = '📖'; 
                                        $iconBg = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
                                        $typeLabel = 'Membaca Materi';
                                        $typeColor = 'text-cyan-400';
                                        $expGained = 10; 
                                        $criteria = 'Selesai Membaca';

                                        if ($typeLower === 'kuis' || $typeLower === 'quiz') { 
                                            $icon = '📝'; 
                                            $iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20'; 
                                            $typeLabel = 'Evaluasi Kuis';
                                            $typeColor = 'text-fuchsia-400';
                                            $expGained = $item['score'] ?? $item['exp'] ?? 0;
                                            $criteria = '1 Pts = 1 XP';
                                        } elseif ($typeLower === 'lab')  { 
                                            $icon = '💻'; 
                                            $iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20'; 
                                            $typeLabel = 'Praktik Lab';
                                            $typeColor = 'text-blue-400';
                                            $expGained = 50;
                                            $criteria = 'Lulus KKM (>=70)';
                                        } elseif ($typeLower === 'badge' || $typeLower === 'lencana') {
                                            $icon = '🎖️'; 
                                            $iconBg = 'bg-amber-500/10 text-amber-400 border-amber-500/20'; 
                                            $typeLabel = 'Lencana Didapat';
                                            $typeColor = 'text-amber-400';
                                            $expGained = 0; 
                                            $criteria = 'Reward Milestone';
                                        }

                                        if (isset($item['exp'])) $expGained = $item['exp'];
                                        
                                        $statusDisplay = in_array(strtolower($item['status'] ?? ''), ['lulus', 'passed', 'didapatkan']) ? 'bg-emerald-500/10 text-emerald-400' : 'bg-cyan-500/10 text-cyan-400';
                                    @endphp

                                    <li x-show="logFilter === 'all' || logFilter === '{{ $typeLower }}'" x-transition class="group flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] hover:bg-white/[0.05] transition border border-white/5">
                                        <div class="w-8 h-8 rounded-lg {{ $iconBg }} border flex items-center justify-center shrink-0 font-bold text-[10px] md:text-xs shadow-inner">{{ $icon }}</div>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start mb-0.5">
                                                <h4 class="text-[10px] md:text-xs font-bold text-white truncate w-32 md:w-40" title="{{ $activityName }}">{{ $activityName }}</h4>
                                                @if(isset($item['status']))
                                                    <span class="text-[8px] md:text-[9px] font-bold px-1.5 py-0.5 rounded {{ $statusDisplay }}">{{ $item['status'] }}</span>
                                                @endif
                                            </div>
                                            <div class="flex justify-between items-end mt-1">
                                                <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-mono">
                                                    <span class="uppercase tracking-wider font-bold {{ $typeColor }}">{{ $typeLabel }}</span>
                                                    <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                                    <span class="text-white/40">{{ \Carbon\Carbon::parse($item['date'] ?? $item['raw_date'])->diffForHumans() }}</span>
                                                </div>
                                                
                                                <div class="flex flex-col items-end">
                                                    @if($expGained > 0)
                                                        <span class="text-[9px] font-black text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-500/20 shadow-[0_0_8px_rgba(99,102,241,0.3)]">+{{ $expGained }} XP</span>
                                                    @else
                                                        <span class="text-[9px] font-black text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/20 shadow-[0_0_8px_rgba(251,191,36,0.3)]">REWARD</span>
                                                    @endif
                                                    <span class="text-[7px] text-indigo-400/50 mt-1 font-mono uppercase tracking-widest leading-none">{{ $criteria }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </li>
                                @empty
                                    <li class="text-center text-white/30 text-xs italic py-10">Belum ada aktivitas yang menghasilkan EXP.</li>
                                @endforelse
                            </ul>
                            <div class="absolute bottom-0 left-0 right-0 h-16 bg-gradient-to-t from-[#0f141e] to-transparent pointer-events-none z-20"></div>
                        </div>
                    </div>
                </div>

                <div class="border-t border-white/5 pt-6 md:pt-8 mt-8 md:mt-10 text-center">
                    <p class="text-white/20 text-[10px] md:text-xs">&copy; {{ date('Y') }} Utilwind CSS E-Learning</p>
                </div>
            </div>

            {{-- =========================================================================
                 HERO MODALS (GAMIFICATION & INSIGHTS)
                 ========================================================================= --}}

            {{-- 0. Modal Title & XP --}}
            <div x-show="showTitleModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showTitleModal = false"></div>
                
                <div class="relative w-full max-w-sm md:max-w-md bg-[#0f141e] border border-indigo-500/40 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(99,102,241,0.15)]"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="showTitleModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20">
                        <svg class="w-4 md:w-5 h-4 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 md:w-32 h-24 md:h-32 rounded-full bg-indigo-500 blur-[30px] md:blur-[40px] opacity-20 pointer-events-none"></div>

                        <div class="w-12 h-12 md:w-16 md:h-16 rounded-2xl bg-indigo-500/10 text-indigo-400 flex items-center justify-center border border-indigo-500/30 mb-4 md:mb-6 relative z-10 shadow-[0_0_15px_rgba(99,102,241,0.5)]">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </div>
                        
                        <h3 class="text-xl md:text-2xl font-black text-white mb-2 tracking-tight">Sistem Pangkat & XP</h3>
                        <p class="text-slate-400 text-xs md:text-sm leading-relaxed mb-6">Kumpulkan Experience Points (XP) dari aktivitas belajar untuk membuka titel developer baru.</p>

                        <div class="w-full space-y-2 text-left relative z-10">
                            <div class="flex justify-between items-center p-3 rounded-xl border {{ ($user->xp ?? 0) >= 4000 ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-rose-400 text-sm md:text-base">💎</span> Tailwind Architect</span>
                                <span class="text-[9px] md:text-[10px] font-mono">4000+ XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border {{ (($user->xp ?? 0) >= 2500 && ($user->xp ?? 0) < 4000) ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-amber-400 text-sm md:text-base">🥇</span> Component Crafter</span>
                                <span class="text-[9px] md:text-[10px] font-mono">2500 XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border {{ (($user->xp ?? 0) >= 1000 && ($user->xp ?? 0) < 2500) ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-slate-300 text-sm md:text-base">🥈</span> Frontend Stylist</span>
                                <span class="text-[9px] md:text-[10px] font-mono">1000 XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border {{ (($user->xp ?? 0) >= 300 && ($user->xp ?? 0) < 1000) ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-orange-400 text-sm md:text-base">🥉</span> Utility Apprentice</span>
                                <span class="text-[9px] md:text-[10px] font-mono">300 XP</span>
                            </div>
                            <div class="flex justify-between items-center p-3 rounded-xl border {{ ($user->xp ?? 0) < 300 ? 'bg-indigo-500/20 border-indigo-500/50 text-white shadow-[0_0_10px_rgba(99,102,241,0.3)]' : 'bg-white/5 border-white/10 text-slate-400' }} transition-colors">
                                <span class="text-[10px] md:text-xs font-bold flex items-center gap-2"><span class="text-slate-500 text-sm md:text-base">⚪</span> CSS Novice</span>
                                <span class="text-[9px] md:text-[10px] font-mono">0 XP</span>
                            </div>
                        </div>

                        <div class="mt-6 flex flex-col md:flex-row justify-between w-full text-[9px] md:text-[10px] font-mono font-bold text-slate-400 bg-[#020617] p-3.5 rounded-xl border border-white/5 shadow-inner gap-2 md:gap-0">
                            <span>Materi: +10 XP</span>
                            <span>Lab: +50 XP</span>
                            <span>Kuis: Max +100 XP</span>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 0. Modal INFO BADGE GAMIFIKASI --}}
            <div x-show="showBadgeModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showBadgeModal = false"></div>
                
                <div class="relative w-full max-w-sm bg-[#0f141e] border rounded-3xl p-6 md:p-8 shadow-2xl transition-colors duration-300"
                     :class="'border-' + activeBadge?.color + '-500/40 shadow-[0_20px_70px_rgba(var(--color-' + activeBadge?.color + '-500),0.15)]'"
                     x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <button @click="showBadgeModal = false" class="absolute top-4 right-4 text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-white/10 z-20">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="flex flex-col items-center text-center mt-2 relative">
                        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-24 md:w-32 h-24 md:h-32 rounded-full blur-[30px] md:blur-[40px] pointer-events-none transition-colors duration-300 opacity-20"
                             :class="'bg-' + activeBadge?.color + '-500'"></div>

                        <div class="mb-4 md:mb-6 relative z-10 transition-colors duration-300 w-12 h-12 md:w-16 md:h-16 flex items-center justify-center" :class="'text-' + activeBadge?.color + '-400 drop-shadow-[0_0_15px_rgba(var(--color-' + activeBadge?.color + '-500),0.8)]'" x-html="activeBadge?.icon"></div>
                        
                        <h3 class="text-xl md:text-2xl font-black text-white mb-2 tracking-tight" x-text="activeBadge?.name"></h3>
                        
                        <div class="mb-4 md:mb-6">
                            <span class="px-3 py-1 rounded-full text-[9px] md:text-[10px] font-bold uppercase tracking-widest border transition-colors duration-300"
                                  :class="activeBadge?.status === 'Unlocked' ? 'bg-' + activeBadge?.color + '-500/10 text-' + activeBadge?.color + '-400 border-' + activeBadge?.color + '-500/20' : 'bg-slate-500/10 text-slate-400 border-slate-500/20'"
                                  x-text="activeBadge?.status === 'Unlocked' ? 'Berhasil Didapatkan' : 'Lencana Terkunci'">
                            </span>
                        </div>
                        
                        <div class="bg-[#020617] w-full rounded-2xl p-4 md:p-5 border border-white/5 shadow-inner">
                            <p class="text-[9px] md:text-[10px] text-white/40 uppercase font-bold tracking-widest mb-2 border-b border-white/5 pb-2 text-left">Syarat Perolehan</p>
                            <p class="text-slate-300 text-xs md:text-sm leading-relaxed text-left" x-text="activeBadge?.description"></p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 1. Modal Insight Materi (Fuchsia) --}}
            <div x-show="showLessonModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showLessonModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-[#0f141e] border border-fuchsia-500/40 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(217,70,239,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-fuchsia-500/20 text-fuchsia-400 border border-fuchsia-500/30">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <button @click="showLessonModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-white mb-2">Progres Teori</h3>
                    <p class="text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6">Statistik ini menghitung jumlah slide/halaman materi teori yang telah Anda baca secara utuh.</p>
                    
                    <div class="bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-4xl md:text-5xl font-black text-fuchsia-400">{{ $lessonsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-white/30 font-bold">/ {{ $totalLessons ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-fuchsia-400/50 uppercase tracking-widest font-bold mt-2">Materi Diselesaikan ({{ $pctLesson ?? 0 }}%)</p>
                    </div>
                </div>
            </div>

            {{-- 2. Modal Insight Lab (Blue) --}}
            <div x-show="showLabModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showLabModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-[#0f141e] border border-blue-500/40 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(59,130,246,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <button @click="showLabModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-white mb-2">Hands-on Labs</h3>
                    <p class="text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6">Sebuah lab dinyatakan <span class="text-emerald-400 font-bold">Lulus (Passed)</span> jika Anda mendapatkan skor <b>70 ke atas</b> saat validasi kode.</p>
                    
                    <div class="bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-4xl md:text-5xl font-black text-blue-400">{{ $labsCompleted ?? 0 }}</span>
                        <span class="text-lg md:text-xl text-white/30 font-bold">/ {{ $totalLabs ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-blue-400/50 uppercase tracking-widest font-bold mt-2">Modul Praktikum Lulus</p>
                    </div>
                </div>
            </div>

            {{-- 3. Modal Insight Kuis (Cyan) --}}
            <div x-show="showQuizModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showQuizModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-[#0f141e] border border-cyan-500/40 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(34,211,238,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                            <svg class="w-6 h-6 md:w-8 md:h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <button @click="showQuizModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-white mb-2">Rata-rata Kuis</h3>
                    <p class="text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6">Kalkulasi nilai ini diambil dari nilai rata-rata seluruh pengerjaan evaluasi teori (kuis) Anda.</p>
                    
                    <div class="bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-4xl md:text-5xl font-black text-cyan-400">{{ round($quizAverage ?? 0, 1) }}</span>
                        <p class="text-[9px] md:text-[10px] text-cyan-400/50 uppercase tracking-widest font-bold mt-3">Rata-rata Poin (Pts)</p>
                    </div>
                </div>
            </div>

            {{-- 4. Modal Insight Bab Lulus (Emerald) --}}
            <div x-show="showChapterModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showChapterModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-[#0f141e] border border-emerald-500/40 rounded-3xl p-6 md:p-8 shadow-[0_20px_70px_rgba(16,185,129,0.15)]" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-4 md:mb-6">
                        <div class="p-2.5 md:p-3 rounded-xl md:rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <svg class="w-6 h-6 md:w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <button @click="showChapterModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-xl md:text-2xl font-black text-white mb-2">Bab Lulus</h3>
                    <p class="text-slate-400 text-xs md:text-sm leading-relaxed mb-4 md:mb-6">Satu bab teori dinyatakan terlewati apabila nilai akhir evaluasi Anda mencapai ambang batas <b>>= 70</b>.</p>
                    
                    <div class="bg-[#0a0e17] rounded-xl md:rounded-2xl p-5 md:p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-4xl md:text-5xl font-black text-emerald-400">{{ $chaptersPassed ?? 0 }}</span>
                        <p class="text-[9px] md:text-[10px] text-emerald-400/50 uppercase tracking-widest font-bold mt-3">Total Bab Terselesaikan</p>
                    </div>
                </div>
            </div>

            {{-- =========================================================================
                 MODAL GABUNG KELAS (GOOGLE CLASSROOM STYLE)
                 ========================================================================= --}}
            @empty(Auth::user()->class_group)
            <div x-show="showJoinModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showJoinModal = false"></div>
                <div class="relative w-full max-w-sm md:max-w-md bg-[#0f141e] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-lg md:text-xl font-bold text-white flex items-center gap-3">
                            <div class="p-2 md:p-2.5 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                                <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            Gabung Kelas
                        </h3>
                        <button @click="showJoinModal = false" class="text-slate-500 hover:text-white transition p-1.5 rounded-lg bg-white/5 hover:bg-white/10"><svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <p class="text-[10px] md:text-xs text-slate-400 mb-6 mt-2">Mintalah kode token akses (6 karakter) kepada instruktur Anda, lalu masukkan di bawah ini.</p>

                    <form action="{{ route('student.join_class') }}" method="POST" class="space-y-4 md:space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                        @csrf
                        <div>
                            <label class="text-[9px] md:text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Token Kelas <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <input type="text" name="token" required maxlength="6" style="text-transform: uppercase;" placeholder="Contoh: A7X9YM" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl px-3 md:px-4 py-3 md:py-4 text-lg md:text-xl font-mono tracking-[0.2em] md:tracking-[0.3em] font-bold text-white focus:ring-2 ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-700 placeholder:tracking-normal placeholder:font-sans placeholder:font-normal shadow-inner text-center">
                            </div>
                        </div>

                        {{-- Kartu Info Akun --}}
                        <div class="bg-white/[0.02] border border-white/5 rounded-xl p-3 md:p-4 flex items-center gap-3 md:gap-4 shadow-inner">
                            <div class="w-8 h-8 md:w-10 md:h-10 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white font-bold shadow-lg text-sm md:text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-[8px] md:text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Masuk Sebagai:</p>
                                <p class="text-xs md:text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[9px] md:text-[10px] text-slate-400 font-mono truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-2 md:gap-3 pt-4 border-t border-white/5 mt-4 md:mt-6">
                            <button type="button" @click="showJoinModal = false" class="px-4 md:px-5 py-2 md:py-2.5 rounded-xl text-slate-400 hover:text-white font-bold text-[10px] md:text-xs transition border border-transparent hover:border-white/10">Batal</button>
                            <button type="submit" class="px-5 md:px-6 py-2 md:py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-[10px] md:text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] transition flex items-center gap-2 border border-indigo-400" :disabled="isSubmitting" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''">
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

{{-- Styles Dinamis Tailwind dari DB Badge --}}
<style>
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
    
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; } .custom-scrollbar::-webkit-scrollbar-track { background: transparent; } .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; } .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
    #animated-bg { background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; } @keyframes bgMove{to{transform:scale(1.15)}}
    .animate-spin-slow { animation: spin 8s linear infinite; } @keyframes spin { 100% { transform: rotate(360deg); } }
    [data-title]:hover::after { content: attr(data-title); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 4px 8px; font-size: 10px; border-radius: 4px; white-space: nowrap; pointer-events: none; z-index: 50; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 4px 6px rgba(0,0,0,0.3); }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } } .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
    [x-cloak] { display: none !important; }

    /* SISTEM TOOLTIP SUPER SOLID */
    .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
    .tooltip-container:hover { z-index: 99999; }
    .tooltip-trigger { width: 18px; height: 18px; border-radius: 50%; color: white; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.2); }
    .tooltip-trigger:hover { transform: scale(1.15); }
    .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 200px; max-width: 280px; white-space: normal; text-align: left; background-color: #020617; color: #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,1); z-index: 99999; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
    .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); } .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; } .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #020617 transparent; } .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); } .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); } .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }
    .tooltip-blue .tooltip-trigger { background-color: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.5); } .tooltip-blue .tooltip-trigger:hover { background-color: #60a5fa; box-shadow: 0 0 15px rgba(59,130,246,0.8); }
    .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); } .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); } .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }
    .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); } .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); } .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }
    .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); } .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); } .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }
    .glass-card { background: rgba(10, 14, 23, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); backdrop-filter: blur(16px); }
</style>

{{-- Script SweetAlert --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#10b981' }); }); </script> @endif
@if(session('error')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: '#0f141e', color: '#fff', iconColor: '#ef4444' }); }); </script> @endif
@if(session('info')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#3b82f6' }); }); </script> @endif

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const ctx = document.getElementById('quizChart')?.getContext('2d');
        if(ctx && {!! json_encode($chartData['scores'] ?? []) !!}.length > 0) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(232, 121, 249, 0.5)'); gradient.addColorStop(1, 'rgba(232, 121, 249, 0)');
            new Chart(ctx, { type: 'line', data: { labels: {!! json_encode($chartData['labels'] ?? []) !!}, datasets: [{ label: 'Nilai Evaluasi Terakhir', data: {!! json_encode($chartData['scores'] ?? []) !!}, borderColor: '#e879f9', backgroundColor: gradient, borderWidth: 3, pointBackgroundColor: '#020617', pointBorderColor: '#fff', pointBorderWidth: 2, pointRadius: 6, pointHoverRadius: 8, fill: true, tension: 0.4 }] }, options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: { backgroundColor: 'rgba(15, 20, 30, 0.9)', titleFont: { family: 'Inter', size: 13, weight: 'bold' }, bodyFont: { family: 'Inter', size: 12 }, padding: 12, borderColor: 'rgba(255,255,255,0.1)', borderWidth: 1, displayColors: false } }, scales: { x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)', font: { family: 'monospace' } } }, y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)' } } } } });
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
            let c = 'bg-white/5'; if(v>=1) c='bg-cyan-500/40 shadow-[0_0_5px_#22d3ee]'; if(v>=3) c='bg-fuchsia-500 shadow-[0_0_8px_#d946ef]';
            const div = document.createElement('div'); div.className = `w-2 md:w-2.5 h-2 md:h-2.5 rounded-[2px] ${c} relative cursor-pointer hover:scale-150 transition hover:z-20 hover:border hover:border-white`; div.setAttribute('data-title', `${k}: ${v} Aktivitas`); el.appendChild(div);
        }
    }

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList'); if(!list) return; list.innerHTML = '';
        const insightContainer = document.getElementById('liveLogInsight');
        const globalUserXp = {{ $user->xp ?? 0 }};
        
        if (logs.length === 0) { 
            list.innerHTML = `<li class="text-white/30 text-center text-xs italic py-10">Belum ada aktivitas gamifikasi.</li>`; 
            if(insightContainer) insightContainer.innerHTML = '';
            return; 
        }
        
        let totalExpFromLogs = 0;

        logs.forEach((item, index) => {
            let typeLower = item.type ? item.type.toLowerCase() : '';
            let activityName = item.activity || item.name || '';
            
            // Konfigurasi Default (Materi)
            let icon = '📖'; 
            let iconBg = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            let typeLabel = 'Membaca Materi';
            let typeColor = 'text-cyan-400';
            let expGained = 10; 
            let criteria = 'Selesai Membaca';

            // Logika Jenis Aktivitas Gamifikasi
            if (typeLower === 'kuis' || typeLower === 'quiz' || activityName.toLowerCase().includes('evaluasi')) { 
                icon = '📝'; 
                iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20'; 
                typeLabel = 'Evaluasi Kuis';
                typeColor = 'text-fuchsia-400';
                expGained = item.score || item.exp || 0;
                criteria = '1 Pts = 1 XP';
            } else if (typeLower === 'lab' || activityName.toLowerCase().includes('lab'))  { 
                icon = '💻'; 
                iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20'; 
                typeLabel = 'Praktik Lab';
                typeColor = 'text-blue-400';
                expGained = (item.status === 'Lulus' || item.status === 'Passed' || (item.score && item.score >= 70)) ? 50 : 0;
                criteria = 'Lulus KKM (>=70)';
            } else if (typeLower === 'badge' || typeLower === 'lencana' || activityName.toLowerCase().includes('lencana')) {
                icon = '🎖️'; 
                iconBg = 'bg-amber-500/10 text-amber-400 border-amber-500/20'; 
                typeLabel = 'Lencana Didapat';
                typeColor = 'text-amber-400';
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
                        <span class="text-[9px] font-black text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-500/20 shadow-[0_0_8px_rgba(99,102,241,0.3)]">+${expGained} XP</span>
                        <span class="text-[7px] text-indigo-400/50 mt-1 font-mono uppercase tracking-widest leading-none">${criteria}</span>
                    </div>
                `;
            } else if (typeLower === 'badge' || typeLower === 'lencana' || activityName.toLowerCase().includes('lencana')) {
                expBadge = `
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] font-black text-amber-400 bg-amber-500/10 px-1.5 py-0.5 rounded border border-amber-500/20 shadow-[0_0_8px_rgba(251,191,36,0.3)]">REWARD</span>
                        <span class="text-[7px] text-amber-400/50 mt-1 font-mono uppercase tracking-widest leading-none">${criteria}</span>
                    </div>
                `;
            } else {
                expBadge = `
                    <div class="flex flex-col items-end">
                        <span class="text-[9px] font-black text-slate-500 bg-white/5 px-1.5 py-0.5 rounded border border-white/10">+0 XP</span>
                        <span class="text-[7px] text-slate-500/50 mt-1 font-mono uppercase tracking-widest leading-none">Remedial</span>
                    </div>
                `;
            }

            // Batasi delay agar performa tetap cepat
            const delay = (index > 15 ? 0 : index) * 100;
            const statusDisplay = item.status === 'Lulus' || item.status === 'Passed' || item.status === 'Didapatkan' ? 'bg-emerald-500/10 text-emerald-400' : (item.status === 'Selesai' ? 'bg-cyan-500/10 text-cyan-400' : 'bg-red-500/10 text-red-400');

            list.insertAdjacentHTML('beforeend', `
                <li x-show="logFilter === 'all' || logFilter === '${typeLower}'" x-transition class="group flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] hover:bg-white/[0.05] transition border border-white/5 animate-fade-in-up" style="animation-delay: ${delay}ms">
                    <div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0 font-bold text-[10px] md:text-xs shadow-inner">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-start mb-0.5">
                            <h4 class="text-[10px] md:text-xs font-bold text-white truncate w-32 md:w-40" title="${activityName}">${activityName}</h4>
                            ${item.status ? `<span class="text-[8px] md:text-[9px] font-bold px-1.5 py-0.5 rounded ${statusDisplay}">${item.status}</span>` : ''}
                        </div>
                        <div class="flex justify-between items-end mt-1">
                            <div class="flex items-center gap-1.5 text-[9px] md:text-[10px] font-mono">
                                <span class="uppercase tracking-wider font-bold ${typeColor}">${typeLabel}</span>
                                <span class="w-1 h-1 rounded-full bg-white/20"></span>
                                <span class="text-white/40">${item.time || item.date || ''}</span>
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
                <div class="flex items-center gap-3 justify-end">
                    <div class="text-right">
                        <p class="text-[8px] uppercase tracking-widest text-slate-500 font-bold mb-0.5">XP Log Terakhir</p>
                        <p class="text-xs font-bold text-emerald-400">+${totalExpFromLogs}</p>
                    </div>
                    <div class="w-px h-6 bg-white/10"></div>
                    <div class="text-right">
                        <p class="text-[8px] uppercase tracking-widest text-indigo-400/80 font-bold mb-0.5">Total Keseluruhan</p>
                        <p class="text-xs font-black text-indigo-400 drop-shadow-[0_0_5px_rgba(99,102,241,0.8)]">${new Intl.NumberFormat('id-ID').format(globalUserXp)} XP</p>
                    </div>
                </div>
            `;
        }
    }
</script>
@endsection