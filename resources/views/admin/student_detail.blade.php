<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Insight: {{ $user->name }} · Utilwind</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    {{-- AlpineJS untuk Interaksi Modal --}}
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    <style>
        /* --- CORE THEME --- */
        :root { 
            --glass-bg: rgba(10, 14, 23, 0.85); 
            --glass-border: rgba(255, 255, 255, 0.08); 
            --accent: #6366f1; 
        }
        body { font-family: 'Inter', sans-serif; background-color: #020617; color: #e2e8f0; overflow: hidden; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: var(--accent); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: rgba(5, 8, 16, 0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); }
        .glass-header { background: rgba(2, 6, 23, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); }
        .glass-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); }
        
        .glass-input {
            background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; transition: 0.3s;
        }
        .glass-input:focus { border-color: var(--accent); background: rgba(255, 255, 255, 0.05); outline: none; }

        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(255,255,255,0.03); }
        .table-row:hover { background: rgba(255,255,255,0.02); }
    </style>
</head>
<body class="flex h-screen w-full" x-data="{ showEdit: false }">

    {{-- ==================== SIDEBAR ==================== --}}
    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-50 hidden md:flex">
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
        <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
            <div>
                <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link active">
                        <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                </div>
            </div>
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
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link">
                        <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
                </div>
            </div>
        </nav>
        <div class="p-4 border-t border-white/5 bg-[#05080f]/50">
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
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[100px]"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-600/10 rounded-full blur-[100px]"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        </div>

        {{-- HEADER --}}
        <header class="h-20 glass-header flex items-center justify-between px-10 shrink-0 z-20">
            <div class="flex items-center gap-4">
                {{-- AVATAR DI HEADER --}}
                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-600 p-0.5 shadow-lg overflow-hidden">
                    <div class="w-full h-full bg-[#0f141e] rounded-[10px] flex items-center justify-center font-black text-white text-sm overflow-hidden">
                        @if($user->avatar)
                            <img src="{{ asset('uploads/avatars/'.$user->avatar) }}" class="w-full h-full object-cover">
                        @else
                            {{ substr($user->name, 0, 2) }}
                        @endif
                    </div>
                </div>
                <div>
                    <h2 class="text-white font-bold text-lg flex items-center gap-2">
                        {{ $user->name }}
                        <span class="px-2 py-0.5 rounded text-[10px] bg-white/10 border border-white/10 text-white/60 uppercase tracking-wide">Student</span>
                    </h2>
                    <p class="text-xs text-white/40 font-mono">{{ $user->email }}</p>
                </div>
            </div>
            
            <a href="{{ route('admin.dashboard') }}" class="group flex items-center gap-2 px-4 py-2 rounded-lg bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition">
                <svg class="w-4 h-4 text-white/50 group-hover:text-white transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                Back to Dashboard
            </a>
        </header>

        {{-- CONTENT SCROLL AREA --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-10 relative z-10">
            <div class="max-w-7xl mx-auto space-y-8">

                {{-- HERO PROFILE SECTION --}}
                <div class="relative rounded-3xl p-8 overflow-hidden bg-gradient-to-r from-[#0f141e] to-indigo-900/20 border border-white/10 shadow-2xl reveal">
                    <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.05] mix-blend-overlay"></div>
                    
                    <div class="relative z-10 flex flex-col md:flex-row gap-8 items-start md:items-center">
                        {{-- Avatar Besar --}}
                        <div class="relative shrink-0">
                            <div class="w-24 h-24 rounded-full border-4 border-white/10 bg-[#0f141e] flex items-center justify-center overflow-hidden shadow-2xl group">
                                @if($user->avatar)
                                    <img src="{{ asset('uploads/avatars/'.$user->avatar) }}" class="w-full h-full object-cover">
                                @else
                                    <span class="text-3xl font-black text-white/20">{{ substr($user->name, 0, 1) }}</span>
                                @endif
                            </div>
                            {{-- Tombol Edit Floating --}}
                            <button @click="showEdit = true" class="absolute bottom-0 right-0 p-2 bg-indigo-600 rounded-full text-white hover:bg-indigo-500 shadow-lg border border-[#0f141e] transition transform hover:scale-110">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                            </button>
                        </div>

                        <div class="flex-1 w-full">
                            <div class="flex flex-col md:flex-row justify-between items-start gap-4">
                                <div>
                                    <h3 class="text-2xl font-black text-white flex items-center gap-3">
                                        {{ $user->name }}
                                        <span class="text-xs font-bold bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded border border-emerald-500/20 uppercase tracking-widest">Active</span>
                                    </h3>
                                    
                                    {{-- Data Diri Lengkap --}}
                                    <div class="flex flex-wrap gap-x-6 gap-y-2 mt-3 text-sm text-white/60">
                                        
                                        {{-- 1. KELAS --}}
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                            <span class="font-bold text-white">{{ $user->class_group ?? 'Kelas (-)' }}</span>
                                        </div>

                                        {{-- 2. INSTITUSI --}}
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 14v3m4-3v3m4-3v3M3 21h18M3 10h18M3 7l9-4 9 4M4 10h16v11H4V10z"/></svg>
                                            <span>{{ $user->institution ?? 'Institusi (-)' }}</span>
                                        </div>

                                        {{-- 3. PRODI --}}
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-fuchsia-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                                            <span>{{ $user->study_program ?? 'Prodi (-)' }}</span>
                                        </div>

                                        {{-- 4. PHONE --}}
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/></svg>
                                            <span class="font-mono">{{ $user->phone ?? '-' }}</span>
                                        </div>
                                    </div>
                                </div>
                                <button @click="showEdit = true" class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-white text-xs font-bold transition flex items-center gap-2">
                                    Edit Profile
                                </button>
                            </div>
                            
                            {{-- Quick Stats Row --}}
                            <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
                                <div class="p-3 rounded-xl bg-black/20 border border-white/5">
                                    <p class="text-[10px] text-white/40 uppercase font-bold">Total Labs</p>
                                    <p class="text-xl font-black text-indigo-400">{{ $labStats['total'] }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-black/20 border border-white/5">
                                    <p class="text-[10px] text-white/40 uppercase font-bold">Quizzes</p>
                                    <p class="text-xl font-black text-fuchsia-400">{{ $quizStats['total'] }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-black/20 border border-white/5">
                                    <p class="text-[10px] text-white/40 uppercase font-bold">Avg Score</p>
                                    <p class="text-xl font-black text-emerald-400">{{ round(($labStats['avg_score'] + $quizStats['avg_score']) / 2, 1) }}</p>
                                </div>
                                <div class="p-3 rounded-xl bg-black/20 border border-white/5">
                                    <p class="text-[10px] text-white/40 uppercase font-bold">Joined</p>
                                    <p class="text-xl font-black text-white">{{ $user->created_at->format('M Y') }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- 1. CHARTS & DISTRIBUTION --}}
                <div class="grid md:grid-cols-3 gap-6 reveal" style="animation-delay: 0.1s;">
                    {{-- Score Trend --}}
                    <div class="md:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative overflow-hidden">
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <h3 class="text-lg font-bold text-white">Progres Nilai Lab (10 Terakhir)</h3>
                            <span class="text-[10px] bg-white/5 border border-white/10 px-2 py-1 rounded text-white/50">Performance Trend</span>
                        </div>
                        <div class="flex-1 w-full h-[280px] relative z-10">
                            <canvas id="scoreChart"></canvas>
                        </div>
                        <div class="absolute bottom-0 left-0 right-0 h-32 bg-gradient-to-t from-indigo-900/10 to-transparent pointer-events-none"></div>
                    </div>

                    {{-- Activity Distribution --}}
                    <div class="glass-card rounded-2xl p-6 flex flex-col justify-center items-center relative overflow-hidden">
                        <h3 class="text-lg font-bold text-white mb-4 w-full text-left">Distribusi Aktivitas</h3>
                        <div class="relative w-48 h-48">
                            <canvas id="activityChart"></canvas>
                            <div class="absolute inset-0 flex items-center justify-center pointer-events-none flex-col">
                                <span class="text-3xl font-black text-white">{{ $labStats['total'] + $quizStats['total'] }}</span>
                                <span class="text-[10px] text-white/40 uppercase tracking-widest">Total</span>
                            </div>
                        </div>
                        <div class="flex gap-4 mt-6 text-xs text-white/60">
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-indigo-500"></span> Labs</div>
                            <div class="flex items-center gap-2"><span class="w-3 h-3 rounded-full bg-fuchsia-500"></span> Quizzes</div>
                        </div>
                    </div>
                </div>

                {{-- 2. DATA TABLE: LAB HISTORIES --}}
                <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.2s;">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="text-indigo-400">⚡</span> Riwayat Lab Coding
                        </h3>
                        <span class="text-xs bg-indigo-500/10 text-indigo-300 px-2 py-1 rounded font-bold border border-indigo-500/20">{{ $labStats['total'] }} Sesi</span>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="bg-[#05080f] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                <tr>
                                    <th class="px-6 py-4">Lab Module</th>
                                    <th class="px-6 py-4 text-center">Status</th>
                                    <th class="px-6 py-4 text-center">Score</th>
                                    <th class="px-6 py-4 text-center">Duration</th>
                                    <th class="px-6 py-4 text-right">Date</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($labHistories as $h)
                                <tr class="hover:bg-white/5 transition group table-row">
                                    <td class="px-6 py-4">
                                        <div class="font-bold text-white group-hover:text-indigo-400 transition">{{ $h->lab_title }}</div>
                                        <div class="text-[10px] text-white/30 font-mono">ID: {{ $h->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wide border {{ $h->status == 'passed' ? 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20' : 'bg-red-500/10 text-red-400 border-red-500/20' }}">
                                            {{ ucfirst($h->status) }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-black text-lg {{ $h->final_score >= 50 ? 'text-emerald-400' : 'text-white/50' }}">
                                            {{ $h->final_score }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-center text-white/60 font-mono text-xs">
                                        {{ gmdate("i:s", $h->duration_seconds) }}s
                                    </td>
                                    <td class="px-6 py-4 text-right text-white/40 text-xs">
                                        {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="py-12 text-center text-white/30 italic">Belum ada data riwayat lab.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                {{-- 3. DATA TABLE: QUIZ ATTEMPTS --}}
                <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.3s;">
                    <div class="p-6 border-b border-white/5 bg-white/5 flex justify-between items-center">
                        <h3 class="text-lg font-bold text-white flex items-center gap-2">
                            <span class="text-fuchsia-400">📝</span> Riwayat Kuis Akademik
                        </h3>
                        <span class="text-xs bg-fuchsia-500/10 text-fuchsia-300 px-2 py-1 rounded font-bold border border-fuchsia-500/20">{{ $quizStats['total'] }} Sesi</span>
                    </div>
                    <div class="overflow-x-auto custom-scrollbar">
                        <table class="w-full text-sm text-left border-collapse">
                            <thead class="bg-[#05080f] text-white/40 text-[10px] uppercase font-bold border-b border-white/5">
                                <tr>
                                    <th class="px-6 py-4">Quiz Info</th>
                                    <th class="px-6 py-4 text-center">Result</th>
                                    <th class="px-6 py-4 text-center">Score</th>
                                    <th class="px-6 py-4 text-right">Completed At</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/5">
                                @forelse($quizAttempts as $q)
                                <tr class="hover:bg-white/5 transition group table-row">
                                    <td class="px-6 py-4">
                                        <div class="text-white font-bold group-hover:text-fuchsia-400 transition">
                                            Evaluasi Bab {{ $q->chapter_id ?? '-' }}
                                        </div>
                                        <div class="text-[10px] text-white/30">Attempt #{{ $q->id }}</div>
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        @if(($q->score ?? 0) >= 70)
                                            <span class="text-emerald-400 text-xs font-bold bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/10">LULUS</span>
                                        @else
                                            <span class="text-red-400 text-xs font-bold bg-red-500/10 px-2 py-1 rounded border border-red-500/10">REMEDIAL</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-center">
                                        <span class="font-black text-lg {{ ($q->score ?? 0) >= 70 ? 'text-emerald-400' : 'text-white/50' }}">
                                            {{ $q->score ?? 0 }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-right text-white/40 text-xs">
                                        {{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }}
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" class="py-12 text-center text-white/30 italic">Belum ada data riwayat kuis.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== MODAL EDIT PROFILE (LENGKAP) ==================== --}}
    <div x-show="showEdit" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm transition-opacity" @click="showEdit = false"></div>
        <div class="relative w-full max-w-2xl bg-[#0f141e] border border-white/10 rounded-3xl shadow-2xl p-8 transform transition-all flex flex-col max-h-[90vh]">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                Edit Student Profile
                <span class="text-sm bg-indigo-500/20 text-indigo-400 px-2 py-0.5 rounded border border-indigo-500/20 font-normal">Administrator Mode</span>
            </h3>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-2">
                <form action="{{ route('admin.student.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Full Name</label>
                            <input type="text" name="name" value="{{ $user->name }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm" required>
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Email Address</label>
                            <input type="email" name="email" value="{{ $user->email }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm" required>
                        </div>
                    </div>

                    {{-- KOLOM KELAS (BARU) --}}
                    <div class="grid grid-cols-2 gap-6">
    <div class="col-span-2 md:col-span-1">
        <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Class Group / Kelas</label>
        
        {{-- GANTI INPUT TEXT MENJADI SELECT --}}
        <div class="relative">
            <select name="class_group" class="w-full glass-input rounded-xl px-4 py-3 text-sm appearance-none cursor-pointer">
                <option value="" disabled class="bg-[#0f141e] text-gray-500">Pilih Kelas</option>
                <option value="A1" class="bg-[#0f141e] text-white" {{ $user->class_group == 'A1' ? 'selected' : '' }}>Kelas A1</option>
                <option value="A2" class="bg-[#0f141e] text-white" {{ $user->class_group == 'A2' ? 'selected' : '' }}>Kelas A2</option>
                <option value="A3" class="bg-[#0f141e] text-white" {{ $user->class_group == 'A3' ? 'selected' : '' }}>Kelas A3</option>
            </select>
            <div class="absolute inset-y-0 right-0 flex items-center px-3 pointer-events-none text-white/50">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </div>
        </div>

    </div>
    <div class="col-span-2 md:col-span-1">
        <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Institution / Sekolah</label>
        <input type="text" name="institution" value="{{ $user->institution }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
    </div>
</div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Phone Number</label>
                            <input type="text" name="phone" value="{{ $user->phone }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Study Program</label>
                            <input type="text" name="study_program" value="{{ $user->study_program }}" class="w-full glass-input rounded-xl px-4 py-3 text-sm">
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-6">
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">New Password <span class="text-white/30 text-[9px] lowercase">(optional)</span></label>
                            <input type="password" name="password" class="w-full glass-input rounded-xl px-4 py-3 text-sm" placeholder="••••••••">
                        </div>
                        <div class="col-span-2 md:col-span-1">
                            <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Avatar Upload</label>
                            <div class="relative w-full h-[46px] border border-white/10 rounded-xl flex items-center px-4 bg-white/5 group cursor-pointer hover:bg-white/10 transition">
                                <input type="file" name="avatar" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer">
                                <span class="text-xs text-white/50 group-hover:text-white transition">Click to browse file...</span>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end gap-3 pt-4 border-t border-white/10">
                        <button type="button" @click="showEdit = false" class="px-5 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/5 text-xs font-bold transition">Cancel</button>
                        <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-lg shadow-indigo-500/20 transition">Save Changes</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- CHART SCRIPTS --}}
    <script>
        // 1. LAB SCORE CHART (Line)
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
                        label: 'Nilai Lab',
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

        // 2. ACTIVITY DISTRIBUTION (Doughnut)
        const ctxActivity = document.getElementById('activityChart');
        if(ctxActivity) {
            new Chart(ctxActivity.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Lab Coding', 'Kuis Akademik'],
                    datasets: [{
                        data: [{{ $labStats['total'] }}, {{ $quizStats['total'] }}],
                        backgroundColor: ['#6366f1', '#d946ef'], // Indigo, Fuchsia
                        borderColor: '#0f141e',
                        borderWidth: 4,
                        hoverOffset: 4
                    }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    cutout: '70%',
                    plugins: { legend: {display: false} }
                }
            });
        }

        // 3. SweetAlert Feedback
        @if(session('success'))
            Swal.fire({
                title: 'Success!', text: "{{ session('success') }}", icon: 'success',
                background: '#0f141e', color: '#fff', confirmButtonColor: '#6366f1'
            });
        @endif
        @if(session('error'))
            Swal.fire({
                title: 'Error!', text: "{{ session('error') }}", icon: 'error',
                background: '#0f141e', color: '#fff', confirmButtonColor: '#ef4444'
            });
        @endif
    </script>

</body>
</html>