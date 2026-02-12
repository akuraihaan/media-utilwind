<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ultimate Command Center · Utilwind</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* --- THEME CONFIG --- */
        :root { 
            --glass-bg: rgba(10, 14, 23, 0.85); 
            --glass-border: rgba(255, 255, 255, 0.08); 
            --accent: #6366f1; /* Indigo-500 */
        }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: rgba(5, 8, 16, 0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); }
        .glass-header { background: rgba(2, 6, 23, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); }
        .glass-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); }
        
        /* --- INPUTS --- */
        .glass-input {
            background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; transition: 0.3s;
        }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }

        /* --- NAV --- */
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.02); }
    </style>
</head>
<body class="flex h-screen w-full" x-data="{ showImport: false, showAdd: false }">

    {{-- ==================== 1. SIDEBAR ==================== --}}
        <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-50 hidden md:flex">
            
            {{-- Header Logo --}}
            <div class="h-24 flex items-center px-8 border-b border-white/5 relative overflow-hidden group">
                <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
                <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                    <img src="{{ asset('images/logo.png') }}" onerror="this.src='https://via.placeholder.com/40'" class="h-9 w-auto object-contain">
                    <div>
                        <h1 class="text-lg font-black text-white tracking-tight leading-none">Util<span class="text-indigo-400">wind</span></h1>
                        <span class="text-[10px] font-bold text-white/40 tracking-[0.2em] uppercase">Admin Panel</span>
                    </div>
                </a>
            </div>

            {{-- Navigation --}}
            <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
                
                {{-- Group 1: Overview --}}
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Overview</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard
                        </a>
                    </div>
                </div>

                {{-- Group 2: Academic --}}
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Academic</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.analytics.questions') }}" class="nav-link">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Bank Soal & Kuis
                        </a>
                        <a href="{{ route('admin.labs.index') }}" class="nav-link">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            Lab Configuration
                        </a>
                        {{-- ACTIVE STATE --}}
                        <a href="{{ route('admin.lab.analytics') }}" class="nav-link">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
                    </div>
                </div>
            </nav>

            {{-- Footer --}}
            <div class="p-4 border-t border-white/5 bg-[#05080f]/50">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs border border-white/10 shadow-lg">AD</div>
                    <div class="overflow-hidden">
                        <p class="text-xs font-bold text-white truncate">{{ Auth::user()->name ?? 'Administrator' }}</p>
                        <p class="text-[10px] text-white/40 truncate">System Admin</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-500/10 text-red-400 hover:bg-red-500 hover:text-white transition text-xs font-bold border border-red-500/20 hover:border-red-500 group">
                        <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Logout
                    </button>
                </form>
            </div>
        </aside>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="flex-1 flex flex-col relative z-10 bg-[#020617] h-full overflow-hidden">
        
        {{-- Background FX --}}
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-fuchsia-600/10 rounded-full blur-[120px]"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        </div>

        {{-- Header --}}
        <header class="h-20 glass-header flex items-center justify-between px-10 shrink-0 z-20">
            <div>
                <h2 class="text-white font-bold text-lg">Dashboard Overview</h2>
                <p class="text-xs text-white/40 flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                    Live Data Monitoring
                </p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right hidden md:block">
                    <p class="text-sm font-bold text-white">{{ \Carbon\Carbon::now()->format('l, d M Y') }}</p>
                    <p class="text-[10px] text-white/40 font-mono">{{ \Carbon\Carbon::now()->format('H:i A') }}</p>
                </div>
                
            </div>
        </header>

        {{-- Scroll Area --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-10 relative z-10">
            <div class="max-w-7xl mx-auto space-y-8">

                {{-- 1. HERO INSIGHT SECTION (Visual) --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
                    
                    {{-- Card 1: Recent Submissions --}}
                    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-10">
                            <svg class="w-20 h-20 text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-4">Submission Terbaru</h3>
                        <div class="space-y-3 relative z-10">
                            @forelse($recentActivities->take(3) as $act)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-indigo-500/20 flex items-center justify-center text-xs font-bold text-indigo-400 border border-indigo-500/30">
                                    {{ substr($act->user->name, 0, 1) }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white truncate">{{ $act->user->name }}</p>
                                    <p class="text-[9px] text-white/40">Skor: {{ $act->score }} • {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</p>
                                </div>
                            </div>
                            @empty
                            <p class="text-xs text-white/30 italic">Belum ada submission.</p>
                            @endforelse
                        </div>
                    </div>

                    {{-- Card 2: Need Attention --}}
                    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-10">
                            <svg class="w-20 h-20 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-4">Need Attention</h3>
                        <div class="space-y-3 relative z-10">
                            @php $remedialList = $recentActivities->where('score', '<', 70)->take(3); @endphp
                            @forelse($remedialList as $act)
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-red-500/20 flex items-center justify-center text-xs font-bold text-red-400 border border-red-500/30">!</div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-xs font-bold text-white truncate">{{ $act->user->name }}</p>
                                    <p class="text-[9px] text-red-400">Score: {{ $act->score }} (Remedial)</p>
                                </div>
                            </div>
                            @empty
                            <div class="flex items-center gap-2 py-2">
                                <span class="text-emerald-400 text-xs font-bold">Semua Aman!</span>
                                <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                            </div>
                            @endforelse
                        </div>
                    </div>

                    {{-- Card 3: Lab Completion --}}
                    <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                        <div class="absolute right-0 top-0 p-4 opacity-10">
                            <svg class="w-20 h-20 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-4">Lab Completion</h3>
                        <div class="flex items-center justify-between mt-2">
                            <div>
                                <span class="text-4xl font-black text-white">{{ $totalLabsCompleted }}</span>
                                <span class="text-sm text-white/40 block">Sesi Selesai</span>
                            </div>
                            <div class="text-right">
                                <a href="{{ route('admin.lab.analytics') }}" class="px-3 py-1.5 rounded-lg bg-fuchsia-600/20 text-fuchsia-400 text-[10px] font-bold border border-fuchsia-600/30 hover:bg-fuchsia-600 hover:text-white transition">
                                    Lihat Detail Lab
                                </a>
                            </div>
                        </div>
                        <div class="mt-6">
                            <div class="w-full bg-white/10 h-1.5 rounded-full overflow-hidden">
                                <div class="h-full bg-fuchsia-500 w-3/4 animate-pulse"></div>
                            </div>
                            <p class="text-[9px] text-white/30 mt-2 text-right">Progress Global</p>
                        </div>
                    </div>
                </div>

                {{-- 2. STATS GRID (NUMBERS) --}}
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 reveal" style="animation-delay: 0.2s;">
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500">
                        <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Total Students</p>
                        <h3 class="text-3xl font-black text-white mt-2">{{ number_format($totalStudents) }}</h3>
                    </div>
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500">
                        <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Quiz Attempts</p>
                        <h3 class="text-3xl font-black text-white mt-2">{{ number_format($totalAttempts) }}</h3>
                    </div>
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500">
                        <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Global Avg Score</p>
                        <h3 class="text-3xl font-black text-white mt-2">{{ $globalAverage }}</h3>
                    </div>
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-red-500">
                        <p class="text-[10px] uppercase font-bold text-white/40 tracking-widest">Total Remedial</p>
                        <h3 class="text-3xl font-black text-white mt-2">{{ $remedialCount }}</h3>
                    </div>
                </div>

                {{-- 3. CHART & LEADERBOARD --}}
                <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.3s;">
                    <div class="lg:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <h3 class="text-lg font-bold text-white">Tren Nilai (7 Hari)</h3>
                            <span class="px-2 py-1 rounded bg-indigo-500/10 border border-indigo-500/20 text-[10px] text-indigo-400 font-bold">Analytics</span>
                        </div>
                        <div class="flex-1 w-full h-[300px] relative z-10">
                            <canvas id="quizChart"></canvas>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-900/10 to-transparent pointer-events-none"></div>
                    </div>

                    <div class="glass-card rounded-2xl p-6 flex flex-col h-full">
                        <h3 class="text-lg font-bold text-white mb-1 flex items-center gap-2">
                            <span class="text-yellow-400">🏆</span> Top 5 Students
                        </h3>
                        <p class="text-[10px] text-white/40 mb-4">Ranking berdasarkan akumulasi rata-rata nilai kuis.</p>
                        
                        <div class="flex-1 overflow-y-auto custom-scrollbar space-y-3 pr-2">
                            @forelse($topStudents as $index => $student)
                            <div class="flex items-center gap-4 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition group">
                                <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs
                                    {{ $index == 0 ? 'bg-yellow-500 text-black shadow-[0_0_15px_#eab308]' : 'bg-[#0f141e] text-white border border-white/10' }}">
                                    {{ $index + 1 }}
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-bold text-white truncate">{{ $student->name }}</p>
                                    <p class="text-[10px] text-white/40">{{ $student->email }}</p>
                                </div>
                                <div class="text-right">
                                    <span class="block text-sm font-black text-emerald-400">{{ round($student->avg_score, 1) }}</span>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-10 text-white/30 text-xs">Belum ada data nilai.</div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- 4. QUESTION ANALYSIS --}}
                <div class="glass-card rounded-2xl p-6 overflow-hidden reveal" style="animation-delay: 0.4s;">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-bold text-white">Analisis Soal (Top 10)</h3>
                        <a href="{{ route('admin.analytics.questions') }}" class="px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-500/20 transition flex items-center gap-2">
                            Full Analysis
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </a>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left">
                            <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                <tr>
                                    <th class="px-4 py-3">Soal</th>
                                    <th class="px-4 py-3 text-center">Jawaban</th>
                                    <th class="px-4 py-3 text-center">Akurasi</th>
                                    <th class="px-4 py-3 text-right">Tingkat</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($questionStats as $q)
                                <tr class="hover:bg-white/5 transition">
                                    <td class="px-4 py-3 max-w-[300px] truncate" title="{{ $q->question_text }}">
                                        {{ \Illuminate\Support\Str::limit($q->question_text, 50) }}
                                    </td>
                                    <td class="px-4 py-3 text-center text-white/60 text-xs">{{ $q->total_answers }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <span class="font-bold {{ $q->accuracy >= 70 ? 'text-emerald-400' : 'text-red-400' }}">
                                            {{ $q->accuracy }}%
                                        </span>
                                    </td>
                                    <td class="px-4 py-3 text-right">
                                        <span class="px-2 py-0.5 rounded text-[10px] font-bold border 
                                            {{ $q->difficulty == 'Mudah' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 
                                              ($q->difficulty == 'Sedang' ? 'bg-yellow-500/10 text-yellow-400 border-yellow-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20') }}">
                                            {{ $q->difficulty }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="text-center py-6 text-white/30 text-xs">Belum ada data soal.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 5. USER MANAGEMENT (PERBAIKAN: INSIGHT LINK & DATA KELAS) --}}
                <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.5s;" x-data="{ searchQuery: '' }">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex flex-col md:flex-row justify-between items-center gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                <span class="text-indigo-400">👥</span> Manajemen Siswa
                            </h3>
                            <p class="text-xs text-white/40 mt-1">Total {{ $totalStudents }} siswa terdaftar.</p>
                        </div>
                        
                        <div class="flex items-center gap-3 w-full md:w-auto">
                            <div class="relative group w-full md:w-64">
                                <input type="text" x-model="searchQuery" placeholder="Cari nama / email / kelas..." 
                                    class="w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-white focus:border-indigo-500 outline-none transition shadow-lg">
                                <svg class="w-4 h-4 absolute left-3 top-2.5 text-white/30 group-focus-within:text-indigo-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            
                            <div class="relative" x-data="{ exportOpen: false }">
                                <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="flex items-center gap-2 px-3 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg> Export
                                </button>
                                <div x-show="exportOpen" class="absolute right-0 mt-2 w-32 bg-[#0f141e] border border-white/10 rounded-xl shadow-xl z-50 overflow-hidden" style="display: none;">
                                    <a href="{{ route('admin.user.export.csv') }}" class="block px-4 py-2 text-xs text-white hover:bg-white/5 transition">CSV File</a>
                                    <a href="{{ route('admin.user.export.pdf') }}" target="_blank" class="block px-4 py-2 text-xs text-white hover:bg-white/5 transition">PDF Document</a>
                                </div>
                            </div>

                            <button @click="showImport = true" class="p-2.5 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white transition" title="Import CSV">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                            </button>
                            <button @click="showAdd = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 text-white text-xs font-bold shadow-lg shadow-emerald-500/20 transition">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Enroll
                            </button>
                        </div>
                    </div>

                    <div class="max-h-[600px] overflow-y-auto custom-scrollbar">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="bg-[#0f141e] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-10 shadow-md">
                                <tr>
                                    <th class="px-6 py-4">Student Profile</th>
                                    <th class="px-6 py-4">Class Group</th> {{-- Header Kelas --}}
                                    <th class="px-6 py-4">Institution</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-right">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($users as $user)
                                @if($user->role == 'student')
                                <tr class="hover:bg-white/5 transition group table-row" 
                                    x-show="searchQuery === '' || '{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($user->class_group ?? '') }}'.includes(searchQuery.toLowerCase())">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white text-xs">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <p class="font-bold text-white">{{ $user->name }}</p>
                                                <p class="text-[10px] text-white/40 font-mono">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    
                                    {{-- KOLOM KELAS (DIPERBAIKI) --}}
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 rounded bg-indigo-500/10 border border-indigo-500/20 text-indigo-300 text-xs font-bold">
                                            {{ $user->class_group ?? '-' }}
                                        </span>
                                    </td>

                                    <td class="px-6 py-4 text-white/60 text-xs">{{ $user->institution ?? '-' }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="text-emerald-400 text-[10px] font-bold uppercase">Active</span>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        {{-- TOMBOL INSIGHT MENUJU STUDENT DETAIL --}}
                                        <a href="{{ route('admin.student.detail', $user->id) }}" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition group/btn">
                                            Insight <svg class="w-3 h-3 text-white/30 group-hover/btn:text-indigo-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr><td colspan="5" class="text-center py-8 text-white/30 text-xs">Tidak ada data siswa.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== MODALS ==================== --}}

    {{-- 1. IMPORT CSV --}}
    <div x-show="showImport" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm transition-opacity" @click="showImport = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-2xl shadow-2xl p-6 transform transition-all">
            <h3 class="text-lg font-bold text-white mb-2">Import Data Siswa</h3>
            <p class="text-xs text-white/50 mb-6">Format CSV: Name, Email, Class, Institution, Password</p>
            <form action="{{ route('admin.user.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="relative w-full h-32 border-2 border-dashed border-white/10 rounded-xl flex flex-col items-center justify-center hover:border-indigo-500/50 transition bg-white/5 group cursor-pointer mb-6">
                    <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="document.getElementById('fileName').innerText = this.files[0].name">
                    <svg class="w-8 h-8 text-white/30 group-hover:text-indigo-400 mb-2 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span id="fileName" class="text-xs text-white/50 group-hover:text-white transition">Click to upload CSV</span>
                </div>
                <div class="flex justify-end gap-3">
                    <button type="button" @click="showImport = false" class="px-4 py-2 rounded-lg text-white/60 hover:text-white hover:bg-white/5 text-xs font-bold transition">Cancel</button>
                    <button type="submit" class="px-5 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg transition">Process</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. ADD USER --}}
    <div x-show="showAdd" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm transition-opacity" @click="showAdd = false"></div>
        <div class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-2xl shadow-2xl p-6 transform transition-all">
            <h3 class="text-lg font-bold text-white mb-4">Enroll New Student</h3>
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="text-[10px] font-bold text-white/50 uppercase mb-1 block">Full Name</label><input type="text" name="name" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm" required></div>
                <div><label class="text-[10px] font-bold text-white/50 uppercase mb-1 block">Email</label><input type="email" name="email" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm" required></div>
                <div class="grid grid-cols-2 gap-4">
    <div>
        <label class="text-[10px] font-bold text-white/50 uppercase mb-1 block">Class Group</label>
        
        {{-- GANTI INPUT TEXT MENJADI SELECT --}}
        <div class="relative">
            <select name="class_group" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm appearance-none cursor-pointer" required>
                <option value="" disabled selected class="bg-[#0f141e] text-gray-500">Pilih Kelas</option>
                <option value="A1" class="bg-[#0f141e] text-white">Kelas A1</option>
                <option value="A2" class="bg-[#0f141e] text-white">Kelas A2</option>
                <option value="A3" class="bg-[#0f141e] text-white">Kelas A3</option>
            </select>
            {{-- Icon Panah Dropdown --}}
            <div class="absolute inset-y-0 right-0 flex items-center px-2 pointer-events-none text-white/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>

    </div>
    <div>
        <label class="text-[10px] font-bold text-white/50 uppercase mb-1 block">Institution</label>
        <input type="text" name="institution" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm">
    </div>
</div>
                <div><label class="text-[10px] font-bold text-white/50 uppercase mb-1 block">Password</label><input type="password" name="password" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm" required></div>
                <div class="flex justify-end gap-3 pt-2">
                    <button type="button" @click="showAdd = false" class="px-4 py-2 text-white/60 hover:text-white hover:bg-white/5 text-xs font-bold transition">Cancel</button>
                    <button type="submit" class="px-6 py-2 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg transition">Save</button>
                </div>
            </form>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        const ctx = document.getElementById('quizChart');
        if(ctx) {
            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
            new Chart(ctx.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Average Score', data: {!! json_encode($chartScores) !!},
                        borderColor: '#818cf8', backgroundColor: gradient, borderWidth: 3,
                        pointBackgroundColor: '#0f141e', pointBorderColor: '#818cf8', pointBorderWidth: 2, pointRadius: 6, fill: true, tension: 0.4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } }, scales: { x: { grid: { display: false }, ticks: { color: '#94a3b8' } }, y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: '#94a3b8' } } } }
            });
        }
        @if(session('success')) Swal.fire({ title: 'Success!', text: "{{ session('success') }}", icon: 'success', background: '#0f141e', color: '#fff', confirmButtonColor: '#6366f1' }); @endif
        @if(session('error')) Swal.fire({ title: 'Error!', text: "{{ session('error') }}", icon: 'error', background: '#0f141e', color: '#fff', confirmButtonColor: '#ef4444' }); @endif
    </script>

</body>
</html>