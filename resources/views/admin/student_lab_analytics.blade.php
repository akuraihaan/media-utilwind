<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Deep Dive · {{ $user->name }}</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        /* --- CORE THEME (KONSISTEN) --- */
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

        /* --- CODE VIEWER --- */
        .code-block {
            background: #0d1117; border: 1px solid #30363d; border-radius: 8px;
            padding: 1rem; color: #c9d1d9; font-size: 0.85rem; line-height: 1.5; overflow-x: auto;
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

        {{-- ==================== 1. SIDEBAR (KONSISTEN) ==================== --}}
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
                        {{-- ACTIVE STATE: Indikator kita sedang di area Analytics --}}
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
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-fuchsia-600/10 rounded-full blur-[120px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- HEADER KHUSUS STUDENT INSIGHT --}}
            <header class="h-20 glass-header flex items-center justify-between px-10 shrink-0 z-20">
                <div class="flex items-center gap-4">
                    {{-- Avatar Siswa --}}
                    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 p-0.5 shadow-lg">
                        <div class="w-full h-full bg-[#0f141e] rounded-[10px] flex items-center justify-center font-black text-white text-sm">
                            {{ substr($user->name, 0, 2) }}
                        </div>
                    </div>
                    <div>
                        <h2 class="text-white font-bold text-lg flex items-center gap-2">
                            {{ $user->name }}
                            <span class="px-2 py-0.5 rounded text-[10px] bg-white/10 border border-white/10 text-white/60 uppercase tracking-wide">Student Profile</span>
                        </h2>
                        <p class="text-xs text-white/40 font-mono">{{ $user->email }}</p>
                    </div>
                </div>
                
                {{-- Tombol Kembali --}}
                <a href="{{ route('admin.lab.analytics') }}" class="group flex items-center gap-2 px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition">
                    <svg class="w-4 h-4 text-white/50 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                    Back to Analytics
                </a>
            </header>

            {{-- Content Scroll Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8">

                    {{-- A. PERSONAL STATS --}}
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 reveal" style="animation-delay: 0.1s;">
                        {{-- Card 1: Avg Score --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                             <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-yellow-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 001.902 0l1.519-4.674z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Rata-rata Nilai</p>
                            <h3 class="text-3xl font-black text-white mt-2">{{ $globalAvgScore }} <span class="text-sm text-white/30 font-normal">/ 100</span></h3>
                            <div class="w-full bg-white/10 h-1 mt-3 rounded-full overflow-hidden">
                                <div class="h-full {{ $globalAvgScore >= 70 ? 'bg-yellow-400' : 'bg-white/20' }}" style="width: {{ $globalAvgScore }}%"></div>
                            </div>
                        </div>

                        {{-- Card 2: Total Labs --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                             <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Total Percobaan</p>
                            <h3 class="text-3xl font-black text-white mt-2">{{ $totalLabsAttempted }}x</h3>
                            <p class="text-xs text-white/30 mt-2">Kali mengerjakan lab</p>
                        </div>

                        {{-- Card 3: Passed --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                             <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Lulus (Passed)</p>
                            <h3 class="text-3xl font-black text-emerald-400 mt-2">{{ $passedLabs }}</h3>
                            <p class="text-xs text-white/30 mt-2">Modul terselesaikan</p>
                        </div>

                         {{-- Card 4: Total Time --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                             <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Total Coding Time</p>
                            <h3 class="text-3xl font-black text-cyan-400 mt-2 font-mono tracking-tight">{{ $totalTimeSpent }}</h3>
                            <p class="text-xs text-white/30 mt-2">Jam terbang coding</p>
                        </div>
                    </div>

                    {{-- B. PERSONAL CHARTS --}}
                    <div class="grid md:grid-cols-3 gap-6 reveal" style="animation-delay: 0.2s;">
                        
                        {{-- Score Trend --}}
                        <div class="md:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden">
                            <div class="flex justify-between items-center mb-6 relative z-10">
                                <h3 class="text-lg font-bold text-white">Riwayat Nilai (10 Terakhir)</h3>
                                <span class="text-[10px] bg-indigo-500/10 text-indigo-400 px-3 py-1 rounded-full border border-indigo-500/20 font-bold">Progression</span>
                            </div>
                            <div class="flex-1 w-full h-[280px] relative z-10">
                                <canvas id="scoreChart"></canvas>
                            </div>
                             <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-900/10 to-transparent pointer-events-none"></div>
                        </div>

                        {{-- Pass/Fail Ratio --}}
                        <div class="glass-card rounded-2xl p-6 flex flex-col justify-center items-center relative overflow-hidden">
                             <h3 class="text-lg font-bold text-white mb-4 w-full text-left">Rasio Keberhasilan</h3>
                            <div class="relative w-48 h-48">
                                <canvas id="statusChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center pointer-events-none flex-col">
                                    <span class="text-3xl font-black text-white">{{ $totalLabsAttempted }}</span>
                                    <span class="text-[10px] text-white/40 uppercase tracking-widest">Attempts</span>
                                </div>
                            </div>
                            <div class="flex gap-4 mt-6 text-xs">
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-emerald-500 shadow-[0_0_10px_#10b981]"></span> Passed</div>
                                <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-red-500 shadow-[0_0_10px_#ef4444]"></span> Failed</div>
                            </div>
                        </div>
                    </div>

                    {{-- C. HISTORY TABLE --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.3s;">
                        <div class="p-6 border-b border-white/5 bg-white/5 flex items-center gap-2">
                             <h3 class="text-lg font-bold text-white">Log Pengerjaan Lengkap</h3>
                             <span class="px-2 py-0.5 rounded text-[10px] bg-white/10 text-white/60 font-mono">{{ $histories->count() }} Records</span>
                        </div>
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-[#05080f] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                    <tr>
                                        <th class="px-6 py-4">Module Name</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-center">Score</th>
                                        <th class="px-6 py-4 text-center">Duration</th>
                                        <th class="px-6 py-4 text-center">Date</th>
                                        <th class="px-6 py-4 text-right">Last Code Snapshot</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5">
                                    @forelse($histories as $h)
                                    <tr class="table-row hover:bg-white/5 transition group">
                                        <td class="px-6 py-4">
                                            <div class="font-bold text-white group-hover:text-indigo-400 transition">{{ $h->lab_title }}</div>
                                            <div class="text-[10px] text-white/30 font-mono">ID: {{ $h->id }}</div>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $h->status == 'passed' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                                {{ $h->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-lg font-black {{ $h->final_score >= 50 ? 'text-emerald-400' : 'text-white/40' }}">
                                                {{ $h->final_score }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center text-white/60 font-mono text-xs">
                                            {{ gmdate("i:s", $h->duration_seconds) }}s
                                        </td>
                                        <td class="px-6 py-4 text-center text-white/40 text-xs">
                                            {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($h->last_code_snapshot)
                                                <button onclick="openCodeModal(`{{ base64_encode($h->last_code_snapshot) }}`, '{{ $h->lab_title }}')" 
                                                    class="px-3 py-1.5 rounded-lg bg-indigo-600/10 hover:bg-indigo-600 text-indigo-400 hover:text-white border border-indigo-600/30 text-xs font-bold transition">
                                                    View Code
                                                </button>
                                            @else
                                                <span class="text-[10px] text-white/20 italic">No Snapshot</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="6" class="py-12 text-center text-white/30 italic">Belum ada riwayat pengerjaan.</td>
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

    {{-- MODAL: CODE VIEWER --}}
    <div id="codeModal" class="fixed inset-0 z-50 hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-black/90 backdrop-blur-sm transition-opacity" onclick="closeCodeModal()"></div>
        <div class="relative w-full max-w-4xl glass-card rounded-2xl flex flex-col max-h-[85vh] overflow-hidden transform transition-all scale-95 opacity-0" id="codeModalContent">
            
            <div class="p-4 border-b border-white/10 flex justify-between items-center bg-[#0d1117]">
                <div class="flex items-center gap-3">
                    <div class="flex gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        <span class="w-3 h-3 rounded-full bg-yellow-500"></span>
                        <span class="w-3 h-3 rounded-full bg-emerald-500"></span>
                    </div>
                    <span class="text-xs font-mono text-white/60 ml-2" id="modalTitle">snapshot.html</span>
                </div>
                <button onclick="closeCodeModal()" class="text-white/40 hover:text-white transition">✕</button>
            </div>

            <div class="flex-1 overflow-auto bg-[#0d1117] custom-scrollbar p-0">
                <pre class="code-block m-0 min-h-full border-0 rounded-none"><code id="codeContainer" class="language-html"></code></pre>
            </div>
        </div>
    </div>

    {{-- SCRIPTS --}}
    <script>
        // 1. SCORE CHART (Line)
        const ctxScore = document.getElementById('scoreChart');
        if(ctxScore) {
            const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); // Indigo Fade
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');

            new Chart(ctxScore.getContext('2d'), {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartLabels) !!},
                    datasets: [{
                        label: 'Nilai Akhir',
                        data: {!! json_encode($chartScores) !!},
                        borderColor: '#818cf8',
                        backgroundColor: gradient,
                        borderWidth: 2,
                        pointBackgroundColor: '#0f141e',
                        pointBorderColor: '#818cf8',
                        pointBorderWidth: 2,
                        pointRadius: 4,
                        fill: true,
                        tension: 0.3
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    plugins: { legend: {display: false} },
                    scales: {
                        x: { display: false },
                        y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)' } }
                    }
                }
            });
        }

        // 2. STATUS CHART (Doughnut)
        const ctxStatus = document.getElementById('statusChart');
        if(ctxStatus) {
            new Chart(ctxStatus.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Passed', 'Failed'],
                    datasets: [{
                        data: [{{ $passedLabs }}, {{ $failedLabs }}],
                        backgroundColor: ['#10b981', '#ef4444'],
                        borderColor: '#0d1117',
                        borderWidth: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '75%',
                    plugins: { legend: {display: false} }
                }
            });
        }

        // 3. MODAL LOGIC
        function openCodeModal(encodedCode, title) {
            const code = atob(encodedCode); // Decode Base64
            document.getElementById('codeContainer').textContent = code;
            document.getElementById('modalTitle').innerText = title + " - Snapshot";
            
            const modal = document.getElementById('codeModal');
            const content = document.getElementById('codeModalContent');
            modal.classList.remove('hidden');
            setTimeout(() => {
                content.classList.remove('scale-95', 'opacity-0');
                content.classList.add('scale-100', 'opacity-100');
            }, 10);
        }

        function closeCodeModal() {
            const modal = document.getElementById('codeModal');
            const content = document.getElementById('codeModalContent');
            content.classList.remove('scale-100', 'opacity-100');
            content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === "Escape") closeCodeModal();
        });
    </script>

</body>
</html>