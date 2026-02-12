<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lab Intelligence · Global Overview</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* --- CORE THEME --- */
        :root { 
            --glass-bg: rgba(10, 14, 23, 0.7); 
            --glass-border: rgba(255, 255, 255, 0.08); 
            --accent: #6366f1; /* Indigo */
        }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar {
            background: rgba(5, 8, 16, 0.95);
            backdrop-filter: blur(20px);
            border-right: 1px solid var(--glass-border);
        }
        .glass-header {
            background: rgba(2, 6, 23, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--glass-border);
        }
        .glass-card {
            background: var(--glass-bg);
            border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
            backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover {
            border-color: rgba(99, 102, 241, 0.4);
            transform: translateY(-4px);
            box-shadow: 0 20px 40px -10px rgba(0,0,0,0.6);
        }

        /* --- NAVIGATION LINKS --- */
        .nav-link {
            display: flex; align-items: center; gap: 12px;
            padding: 12px 16px; border-radius: 12px;
            color: #94a3b8; font-weight: 500; font-size: 0.9rem;
            transition: all 0.2s; border: 1px solid transparent;
        }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active {
            background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%);
            color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px;
        }

        /* --- ANIMATIONS --- */
        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.02); }
    </style>
</head>
<body>

    <div class="flex h-screen w-full">

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
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
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
                        <a href="{{ route('admin.lab.analytics') }}" class="nav-link active">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
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

        {{-- ==================== 2. MAIN CONTENT ==================== --}}
        <main class="flex-1 flex flex-col relative z-10 bg-[#020617] h-full overflow-hidden">
            
            {{-- Background FX --}}
            <div class="fixed inset-0 pointer-events-none z-0">
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-500/5 rounded-full blur-[100px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- Top Header --}}
            <header class="h-20 glass-header flex items-center justify-between px-10 shrink-0 z-20">
                <div>
                    <h2 class="text-white font-bold text-lg">Lab Analytics</h2>
                    <p class="text-xs text-white/40 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Data Monitoring Real-time
                    </p>
                </div>
                
                
            </header>

            {{-- Content Scroll Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8">

                    {{-- A. STATS GRID --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 reveal" style="animation-delay: 0.1s;">
                        
                        {{-- 1. Attempts --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                                <svg class="w-16 h-16 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Total Attempts</p>
                            <h3 class="text-4xl font-black text-white mt-2">{{ number_format($totalAttempts) }}</h3>
                            <div class="mt-4 flex gap-2">
                                <span class="bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded text-[10px] font-bold border border-emerald-500/10">{{ $passedCount }} Pass</span>
                                <span class="bg-red-500/10 text-red-400 px-2 py-0.5 rounded text-[10px] font-bold border border-red-500/10">{{ $failedCount }} Fail</span>
                            </div>
                        </div>

                        {{-- 2. Success Rate --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Success Rate</p>
                            <h3 class="text-4xl font-black {{ $completionRate >= 70 ? 'text-emerald-400' : ($completionRate >= 50 ? 'text-yellow-400' : 'text-red-400') }} mt-2">
                                {{ $completionRate }}<span class="text-xl">%</span>
                            </h3>
                            <div class="w-full bg-white/10 h-1.5 mt-3 rounded-full overflow-hidden">
                                <div class="h-full {{ $completionRate >= 70 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ $completionRate }}%"></div>
                            </div>
                        </div>

                        {{-- 3. Avg Score --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 001.902 0l1.519-4.674z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Avg Score</p>
                            <h3 class="text-4xl font-black text-white mt-2">{{ $avgScore }}</h3>
                            <p class="text-xs text-white/30 mt-2">Points out of 100</p>
                        </div>

                        {{-- 4. Avg Time --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Avg Time</p>
                            <h3 class="text-4xl font-black text-white mt-2">{{ $avgDuration }}</h3>
                            <p class="text-xs text-white/30 mt-2">Time per session</p>
                        </div>
                    </div>

                    {{-- B. DUAL CHARTS --}}
                    <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.2s;">
                        
                        {{-- Chart 1: Activity Trend --}}
                        <div class="lg:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden">
                            <div class="flex justify-between items-center mb-6 relative z-10">
                                <div>
                                    <h3 class="text-lg font-bold text-white">Tren Aktivitas Mingguan</h3>
                                    <p class="text-xs text-white/40">Jumlah percobaan siswa 7 hari terakhir.</p>
                                </div>
                                <span class="text-[10px] bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/20 font-bold animate-pulse">Live</span>
                            </div>
                            <div class="flex-1 w-full relative z-10 h-[300px]">
                                <canvas id="labTrendChart"></canvas>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-900/10 to-transparent pointer-events-none"></div>
                        </div>

                        {{-- Chart 2: Pass Ratio --}}
                        <div class="glass-card rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden">
                            <h3 class="text-lg font-bold text-white mb-4 w-full text-left">Rasio Kelulusan</h3>
                            <div class="relative w-48 h-48">
                                <canvas id="passFailChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center flex-col pointer-events-none">
                                    <span class="text-2xl font-black text-white">{{ $totalAttempts }}</span>
                                    <span class="text-[10px] text-white/40 uppercase tracking-widest">Total</span>
                                </div>
                            </div>
                            <div class="flex justify-center gap-6 mt-6 w-full text-xs">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500"></span> Passed</div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500"></span> Failed</div>
                            </div>
                        </div>
                    </div>

                    {{-- C. LEADERBOARD (UPDATED WITH STUDENT INSIGHT LINK) --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.3s;">
                        <div class="p-6 border-b border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 bg-white/5">
                            <div>
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">🏆</span> Top Performers
                                </h3>
                                <p class="text-xs text-white/40">Peringkat siswa berdasarkan nilai terbaik dan kecepatan.</p>
                            </div>
                        </div>
                        
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-[#05080f] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-4 w-16 text-center">Rank</th>
                                        <th class="px-6 py-4">Student Profile</th>
                                        <th class="px-6 py-4 text-center">Statistics</th>
                                        <th class="px-6 py-4 text-center">Last Active</th>
                                        <th class="px-6 py-4 text-right">Best Score</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($userPerformance as $index => $user)
                                    <tr class="table-row group">
                                        <td class="px-6 py-4 text-center">
                                            <div class="w-8 h-8 rounded-lg flex items-center justify-center font-black text-xs mx-auto
                                                {{ $index == 0 ? 'bg-gradient-to-br from-yellow-400 to-yellow-600 text-black shadow-lg' : 
                                                   ($index == 1 ? 'bg-gradient-to-br from-slate-300 to-slate-500 text-black shadow-lg' : 
                                                   ($index == 2 ? 'bg-gradient-to-br from-orange-400 to-orange-700 text-white shadow-lg' : 'bg-[#0f141e] border border-white/10 text-white')) }}">
                                                {{ $index + 1 }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col">
                                                {{-- LINK TO STUDENT INSIGHT --}}
                                                <a href="{{ route('admin.student.analytics', $user->student_id) }}" class="font-bold text-white hover:text-indigo-400 transition flex items-center gap-2 group-hover:translate-x-1 duration-200">
                                                    {{ $user->name }}
                                                    <svg class="w-3 h-3 opacity-0 group-hover:opacity-100 transition-opacity text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                                </a>
                                                <span class="text-[10px] text-white/30 font-mono">{{ $user->email }}</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex justify-center items-center gap-3">
                                                <span class="bg-white/5 px-2 py-1 rounded text-[10px] text-white/60 border border-white/5">
                                                    🔄 {{ $user->total_tries }}x
                                                </span>
                                                <span class="bg-cyan-500/10 px-2 py-1 rounded text-[10px] text-cyan-400 border border-cyan-500/20">
                                                    ⏱️ {{ gmdate("i:s", $user->avg_time) }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-xs text-white/50 bg-white/5 px-2 py-1 rounded-full">
                                                {{ \Carbon\Carbon::parse($user->last_attempt)->diffForHumans() }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <span class="text-lg font-black {{ $user->best_score >= 80 ? 'text-emerald-400' : 'text-white' }}">
                                                {{ $user->best_score }}
                                            </span>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="py-16 text-center text-white/30 text-xs italic">
                                            Belum ada data history pengerjaan.
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

    {{-- CHART SCRIPTS --}}
    <script>
        // 1. TREND CHART (Line)
        const ctxTrend = document.getElementById('labTrendChart');
        if(ctxTrend) {
            const gradient = ctxTrend.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo Fade
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctxTrend.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Percobaan',
                        data: {!! json_encode($chartData) !!},
                        borderColor: '#818cf8',
                        backgroundColor: gradient,
                        borderWidth: 3,
                        pointBackgroundColor: '#0f141e',
                        pointBorderColor: '#818cf8',
                        pointBorderWidth: 2,
                        pointRadius: 6,
                        pointHoverRadius: 8,
                        fill: true,
                        tension: 0.4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)', font: {size: 10} } },
                        y: { beginAtZero: true, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)', stepSize: 1 } }
                    }
                }
            });
        }

        // 2. PASS/FAIL CHART (Doughnut)
        const ctxPie = document.getElementById('passFailChart');
        if(ctxPie) {
            new Chart(ctxPie.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        data: [{{ $passedCount }}, {{ $failedCount }}],
                        backgroundColor: ['#10b981', '#ef4444'], // Emerald, Red
                        borderColor: '#0f141e',
                        borderWidth: 5,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '75%',
                    plugins: { legend: { display: false } }
                }
            });
        }
    </script>

</body>
</html>