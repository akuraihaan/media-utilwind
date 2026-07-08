<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Analitik Kuis pengguna · {{ $user->name ?? 'pengguna' }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: { extend: { fontFamily: { sans: ['Inter', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'] } } }
        }
    </script>
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        :root {
            --bg-main:#f8fafc;
            --text-main:#0f172a;
            --glass-bg:rgba(255,255,255,.85);
            --glass-border:rgba(0,0,0,.05);
            --glass-sidebar:rgba(255,255,255,.95);
            --glass-header:rgba(255,255,255,.85);
            --input-bg:rgba(0,0,0,.03);
            --input-border:rgba(0,0,0,.1);
            --nav-text:#64748b;
            --nav-hover-bg:rgba(0,0,0,.03);
            --table-hover:rgba(0,0,0,.02);
            --chart-grid:rgba(0,0,0,.05);
            --chart-tick:rgba(15,23,42,.5);
            --accent:#6366f1;
        }
        .dark {
            --bg-main:#020617;
            --text-main:#e2e8f0;
            --glass-bg:rgba(10,14,23,.85);
            --glass-border:rgba(255,255,255,.08);
            --glass-sidebar:rgba(5,8,16,.95);
            --glass-header:rgba(2,6,23,.85);
            --input-bg:rgba(255,255,255,.03);
            --input-border:rgba(255,255,255,.1);
            --nav-text:#94a3b8;
            --nav-hover-bg:rgba(255,255,255,.03);
            --table-hover:rgba(255,255,255,.05);
            --chart-grid:rgba(255,255,255,.05);
            --chart-tick:rgba(255,255,255,.4);
        }
        body { font-family:'Inter',sans-serif; background-color:var(--bg-main); color:var(--text-main); overflow-x:hidden; transition:background-color .3s,color .3s; }
        .font-mono { font-family:'JetBrains Mono',monospace; }
        .custom-scrollbar::-webkit-scrollbar { width:5px; height:5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background:transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background:rgba(150,150,150,.3); border-radius:10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background:var(--accent); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background:#334155; }
        .glass-sidebar { background:var(--glass-sidebar); backdrop-filter:blur(20px); border-right:1px solid var(--glass-border); z-index:50; transition:background .3s,border .3s; }
        .glass-header { background:var(--glass-header); backdrop-filter:blur(12px); border-bottom:1px solid var(--glass-border); z-index:40; transition:background .3s,border .3s; }
        .glass-card { background:var(--glass-bg); border:1px solid var(--glass-border); backdrop-filter:blur(10px); transition:all .3s cubic-bezier(.4,0,.2,1); position:relative; overflow:visible !important; z-index:10; box-shadow:0 4px 30px rgba(0,0,0,.03); }
        .dark .glass-card { box-shadow:0 4px 30px rgba(0,0,0,.2); }
        .glass-card:hover { border-color:rgba(99,102,241,.4); transform:translateY(-3px); z-index:30; box-shadow:0 10px 40px -10px rgba(0,0,0,.1); }
        .dark .glass-card:hover { box-shadow:0 10px 40px -10px rgba(0,0,0,.5); }
        .nav-link { display:flex; align-items:center; gap:12px; padding:12px 16px; border-radius:12px; color:var(--nav-text); font-weight:500; font-size:.875rem; transition:all .2s; border:1px solid transparent; }
        .nav-link:hover { background:var(--nav-hover-bg); color:var(--text-main); }
        .nav-link.active { background:linear-gradient(90deg,rgba(99,102,241,.1) 0%,rgba(99,102,241,0) 100%); color:#818cf8; border-left:3px solid #818cf8; border-radius:4px 12px 12px 4px; }
        html:not(.dark) .nav-link.active { color:#6366f1; border-left-color:#6366f1; }
        .reveal { opacity:0; transform:translateY(15px); animation:revealAnim .5s forwards ease-out; }
        @keyframes revealAnim { to { opacity:1; transform:translateY(0); } }
        .table-row { transition:background .2s; border-bottom:1px solid var(--glass-border); }
        .table-row:hover { background:var(--table-hover); }
        .modal-open { overflow:hidden; padding-right:5px; }
        [x-cloak] { display:none !important; }
    </style>
</head>
<body x-data="{ sidebarOpen:false, isFullscreen:false, showDashboardInfoModal:false }"
      @keydown.escape.window="isFullscreen = false; document.exitFullscreen?.(); showDashboardInfoModal = false;"
      :class="{ 'modal-open': sidebarOpen || showDashboardInfoModal }">
@php
    $attempts = isset($attempts) ? collect($attempts) : collect();
    $totalQuizAttempts = $totalQuizAttempts ?? 0;
    $passedQuizzes = $passedQuizzes ?? 0;
    $failedQuizzes = $failedQuizzes ?? 0;
    $globalAvgScore = $globalAvgScore ?? 0;
    $quizCompletionRate = $quizCompletionRate ?? 0;
    $totalTimeSpent = $totalTimeSpent ?? '0s';
    $totalFocusLost = $totalFocusLost ?? 0;
    $totalFlagged = $totalFlagged ?? 0;
    $totalUnanswered = $totalUnanswered ?? 0;
    $chartLabels = $chartLabels ?? [];
    $chartScores = $chartScores ?? [];
@endphp

<div class="flex h-screen w-full relative">
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display:none;" x-cloak></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
            <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain block dark:hidden" style="filter:brightness(.1);" alt="Logo">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain hidden dark:block drop-shadow-sm" alt="Logo Dark">
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none transition-colors">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                    <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Panel Admin</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>

        @include('admin.partials.sidebar-nav')

        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">Administrator Sistem</p>
                </div>
            </div>

            <button id="theme-toggle-sidebar" type="button" class="w-full mb-2 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 transition-colors border border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                <span id="theme-toggle-text-sidebar">Ubah Tema</span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-700 dark:hover:text-white transition-colors text-xs font-bold border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500 group shadow-sm dark:shadow-none">
                    <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Keluar
                </button>
            </form>
        </div>
    </aside>

    <main id="admin-main-content" class="flex-1 flex flex-col relative z-10 h-full overflow-y-auto overflow-x-hidden custom-scrollbar">
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/30 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-300/30 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors"></div>
        </div>

        <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-300 dark:hover:bg-white/10 transition-colors shadow-sm dark:shadow-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
                    </button>

                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 p-[1px] shadow-md dark:shadow-lg hidden sm:block">
                            <div class="w-full h-full bg-slate-50 dark:bg-[#0f141e] rounded-[11px] flex items-center justify-center font-black text-slate-900 dark:text-white text-sm transition-colors">
                                {{ substr($user->name ?? 'S', 0, 2) }}
                            </div>
                        </div>
                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dasbor</a></li>
                                    <li class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg><a href="{{ route('admin.analytics.questions') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Analitik Kuis</a></li>
                                    <li class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-slate-900 dark:text-white">Analitik Kuis pengguna</span></li>
                                </ol>
                            </nav>
                            <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 transition-colors">
                                {{ $user->name ?? 'Profil pengguna' }}
                                <button @click="showDashboardInfoModal = true" class="w-6 h-6 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-[10px] font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/50 dark:bg-white/5 hover:bg-white dark:hover:bg-white/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all shadow-sm focus:outline-none" title="Panduan Analitik Kuis pengguna">?</button>
                            </h2>
                            <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 font-mono transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                {{ $user->email ?? 'Email belum tercatat' }}
                            </p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center gap-3 sm:gap-6">
                    <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 group hidden sm:block border border-transparent dark:hover:border-white/10" title="Segarkan">
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Mode Layar Penuh">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display:none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <div class="border-l border-slate-300 dark:border-white/10 pl-5 ml-1 hidden lg:block transition-colors">
                        <a href="{{ route('admin.analytics.questions') }}#quiz-students" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-200 dark:bg-white/5 hover:bg-slate-300 dark:hover:bg-white/10 border border-slate-300 dark:border-white/10 text-slate-700 dark:text-white text-xs font-bold transition-colors group shadow-sm dark:shadow-none">
                            <svg class="w-3.5 h-3.5 text-slate-500 dark:text-white/50 group-hover:text-slate-900 dark:group-hover:text-white transition-transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                            <span>Kembali ke Analitik</span>
                        </a>
                    </div>
                    <a href="{{ route('admin.analytics.questions') }}#quiz-students" class="lg:hidden p-2 rounded-lg bg-slate-200 dark:bg-white/5 text-slate-700 dark:text-white shadow-sm dark:shadow-lg border border-slate-300 dark:border-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    </a>
                </div>
            </div>
        </header>

        <div class="flex-1 p-6 md:p-10 relative z-10">
            <div class="max-w-7xl mx-auto space-y-8 md:space-y-12">
                @php
                    $analyticsTitle = 'Ringkasan Kuis pengguna';
                    $analyticsSubtitle = null;
                    $analyticsItems = [
                        ['label' => 'Rata-rata Skor', 'value' => ($globalAvgScore ?? 0) . '/100', 'hint' => 'Rerata skor dari riwayat kuis pengguna.', 'tone' => ($globalAvgScore ?? 0) >= 70 ? 'emerald' : 'amber'],
                        ['label' => 'Percobaan', 'value' => number_format($totalQuizAttempts ?? 0) . 'x', 'hint' => 'Seluruh pengerjaan dan pengulangan kuis.', 'tone' => 'indigo'],
                        ['label' => 'Ketuntasan', 'value' => $quizCompletionRate . '%', 'hint' => number_format($passedQuizzes ?? 0) . ' tuntas, ' . number_format($failedQuizzes ?? 0) . ' belum tuntas.', 'tone' => $quizCompletionRate >= 70 ? 'emerald' : 'rose'],
                        ['label' => 'Gangguan Fokus', 'value' => number_format($totalFocusLost ?? 0), 'hint' => number_format($totalFlagged ?? 0) . ' ragu, ' . number_format($totalUnanswered ?? 0) . ' kosong.', 'tone' => ($totalFocusLost ?? 0) > 0 ? 'amber' : 'cyan'],
                    ];
                    $analyticsActions = [];
                @endphp
                @include('admin.partials.compact_analytics_strip')

                <div class="grid md:grid-cols-3 gap-6 reveal" style="animation-delay:.2s;">
                    <div class="md:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden transition-colors">
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">Riwayat Nilai Terakhir</h3>
                        </div>
                        <div class="flex-1 w-full h-[280px] relative z-10">
                            <canvas id="scoreChart"></canvas>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-100/50 dark:from-indigo-900/10 to-transparent pointer-events-none transition-colors"></div>
                    </div>

                    <div class="glass-card rounded-2xl p-6 flex flex-col justify-center items-center relative overflow-hidden transition-colors">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 w-full text-left transition-colors">Rasio Percobaan Kuis</h3>
                        <div class="relative w-48 h-48">
                            <canvas id="statusChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none flex-col">
                                <span class="text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $totalQuizAttempts ?? 0 }}x</span>
                                <span class="text-[10px] text-slate-500 dark:text-white/40 uppercase tracking-widest font-bold transition-colors">Percobaan</span>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-6 text-xs">
                            <div class="flex items-center gap-2 font-bold text-slate-600 dark:text-white/60 transition-colors"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span> Tuntas</div>
                            <div class="flex items-center gap-2 font-bold text-slate-600 dark:text-white/60 transition-colors"><span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_10px_#ef4444]"></span> Belum</div>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-2xl overflow-hidden reveal transition-colors flex flex-col" style="animation-delay:.3s;">
                    <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 transition-colors shrink-0">
                        <div class="flex items-center gap-3">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors">Log Pengerjaan Kuis</h3>
                            <span class="px-2 py-0.5 rounded text-[10px] bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/60 font-mono transition-colors">{{ $attempts->count() }} Records</span>
                        </div>
                    </div>

                    <div class="overflow-x-auto overflow-y-auto custom-scrollbar max-h-[450px] relative">
                        <table class="w-full text-sm text-left border-collapse min-w-[900px]">
                            <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold sticky top-0 z-20 shadow-sm dark:shadow-md transition-colors after:absolute after:inset-x-0 after:bottom-0 after:border-b after:border-slate-200 dark:after:border-white/5">
                                <tr>
                                    <th class="px-6 py-4">Evaluasi</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Skor</th>
                                    <th class="px-6 py-4 text-center">Durasi</th>
                                    <th class="px-6 py-4 text-center">Fokus Terganggu</th>
                                    <th class="px-6 py-4 text-center">Tanggal</th>
                                    <th class="px-6 py-4 text-right">Tinjauan</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/5 bg-white/50 dark:bg-[#0a0e17]/30 transition-colors">
                                @forelse($attempts as $attempt)
                                    @php
                                        $isPassed = ((float) ($attempt->score ?? 0)) >= 70;
                                    @endphp
                                    <tr class="table-row transition-colors group">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-slate-900 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">{{ $attempt->quiz_title ?? 'Kuis' }}</div>
                                            <div class="text-[10px] text-slate-500 dark:text-white/30 font-mono mt-0.5 transition-colors">ID: {{ $attempt->id ?? '-' }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[9px] font-bold uppercase tracking-widest border transition-colors {{ $isPassed ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20' }}">
                                                {{ $isPassed ? 'Tuntas' : 'Belum Tuntas' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-base font-black transition-colors {{ $isPassed ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-white/40' }}">{{ $attempt->score ?? 0 }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-600 dark:text-white/60 font-mono text-xs transition-colors">{{ $attempt->duration_label ?? '0s' }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs font-black {{ ($attempt->focus_lost_count ?? 0) > 0 ? 'text-amber-600 dark:text-amber-300' : 'text-slate-500 dark:text-white/40' }}">{{ $attempt->focus_lost_count ?? 0 }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-slate-500 dark:text-white/40 text-xs transition-colors">{{ $attempt->completed_label ?? '-' }}</td>
                                        <td class="px-6 py-4 text-right">
                                            <a href="{{ route('admin.quiz.results.show', $attempt->id) }}" class="inline-flex items-center justify-center px-4 py-2 rounded-lg bg-emerald-50 dark:bg-emerald-600/10 hover:bg-emerald-600 dark:hover:bg-emerald-600 text-emerald-700 dark:text-emerald-400 hover:text-white border border-emerald-200 dark:border-emerald-600/30 text-[10px] font-bold transition-all shadow-sm dark:shadow-inner transform hover:-translate-y-0.5">
                                                Tinjau Analisis
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="py-20 text-center">
                                            <div class="flex flex-col items-center justify-center opacity-60">
                                                <svg class="w-12 h-12 text-slate-400 dark:text-white/20 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2"/></svg>
                                                <p class="text-slate-500 dark:text-white/40 text-sm font-bold transition-colors">Belum ada riwayat pengerjaan kuis.</p>
                                                <p class="text-slate-400 dark:text-white/30 text-xs mt-1 transition-colors">pengguna ini belum menyelesaikan kuis atau evaluasi.</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

<div x-show="showDashboardInfoModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display:none;">
    <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
    <div class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto bg-white/95 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-all custom-scrollbar">
        <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>
        @php
            $guideTitle = 'Panduan Analitik Kuis pengguna';
            $guideSubtitle = 'Membaca detail kuis per pengguna';
            $guideImage = 'images/guides/current-admin-student-detail.png';
            $guideIntro = 'Gunakan pola baca yang sama seperti analitik lab pengguna: ringkasan, grafik, lalu riwayat pengerjaan.';
            $guidePoints = [
                ['x' => 52, 'y' => 28, 'title' => 'Ringkasan capaian', 'description' => 'Mulai dari skor rata-rata, jumlah percobaan, ketuntasan, dan gangguan fokus.'],
                ['x' => 48, 'y' => 58, 'title' => 'Pola pengerjaan', 'description' => 'Grafik menunjukkan perubahan skor pada kuis terakhir.'],
                ['x' => 86, 'y' => 52, 'title' => 'Tindak lanjut', 'description' => 'Gunakan tabel riwayat untuk membuka tinjauan jawaban dan data per attempt.'],
            ];
        @endphp
        @include('admin.partials.analytics_guide_mockup')
        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
            <button @click="showDashboardInfoModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-xl transition-colors shadow-md focus:outline-none">
                Mengerti, Tutup Panduan
            </button>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const themeToggleBtnSidebar = document.getElementById('theme-toggle-sidebar');
    const themeToggleTextSidebar = document.getElementById('theme-toggle-text-sidebar');

    const syncText = (isDark) => {
        if (themeToggleTextSidebar) themeToggleTextSidebar.textContent = isDark ? 'Tema Terang' : 'Tema Gelap';
    };
    syncText(document.documentElement.classList.contains('dark'));

    themeToggleBtnSidebar?.addEventListener('click', function() {
        const willBeDark = !document.documentElement.classList.contains('dark');
        document.documentElement.classList.toggle('dark', willBeDark);
        localStorage.setItem('color-theme', willBeDark ? 'dark' : 'light');
        syncText(willBeDark);
        window.dispatchEvent(new Event('theme-toggled'));
    });

    const ctxScore = document.getElementById('scoreChart');
    const ctxStatus = document.getElementById('statusChart');
    let scoreChart, statusChart;

    function initCharts() {
        const isDark = document.documentElement.classList.contains('dark');
        const gridColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-grid').trim();
        const tickColor = getComputedStyle(document.documentElement).getPropertyValue('--chart-tick').trim();
        const donutBorder = isDark ? '#020617' : '#ffffff';
        const trendPointBg = isDark ? '#0f141e' : '#ffffff';
        const trendLineColor = isDark ? '#818cf8' : '#6366f1';

        if (ctxScore) {
            const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, isDark ? 'rgba(129,140,248,.4)' : 'rgba(99,102,241,.2)');
            gradient.addColorStop(1, 'rgba(99,102,241,0)');
            if (scoreChart) scoreChart.destroy();
            scoreChart = new Chart(ctxScore.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($chartLabels ?? [])->values()) !!},
                    datasets: [{
                        label: 'Nilai Kuis',
                        data: {!! json_encode(collect($chartScores ?? [])->values()) !!},
                        borderColor: trendLineColor,
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: trendPointBg,
                        pointBorderColor: trendLineColor,
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: .4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { display: false },
                        y: { beginAtZero: true, max: 100, grid: { color: gridColor }, ticks: { color: tickColor, font: { size: 10, family: 'JetBrains Mono' } } }
                    },
                    interaction: { mode: 'index', intersect: false }
                }
            });
        }

        if (ctxStatus) {
            if (statusChart) statusChart.destroy();
            statusChart = new Chart(ctxStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Tuntas', 'Belum Tuntas'],
                    datasets: [{
                        data: [{{ $passedQuizzes ?? 0 }}, {{ $failedQuizzes ?? 0 }}],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderColor: donutBorder,
                        borderWidth: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, cutout: '75%', plugins: { legend: { display: false } } }
            });
        }
    }

    initCharts();
    window.addEventListener('theme-toggled', initCharts);
});
</script>
</body>
</html>
