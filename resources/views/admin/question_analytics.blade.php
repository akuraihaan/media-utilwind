<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Bank Soal & Analisis · Utilwind Admin</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        /* --- CORE THEME --- */
        :root { 
            --glass-bg: rgba(10, 14, 23, 0.85); 
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
        .glass-sidebar { background: rgba(5, 8, 16, 0.95); backdrop-filter: blur(20px); border-right: 1px solid var(--glass-border); }
        .glass-header { background: rgba(2, 6, 23, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid var(--glass-border); }
        .glass-card {
            background: var(--glass-bg); border: 1px solid var(--glass-border);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); }
        
        .glass-input {
            background: rgba(10, 14, 23, 0.6); border: 1px solid rgba(255, 255, 255, 0.1);
            color: white; transition: 0.3s;
        }
        .glass-input:focus { border-color: var(--accent); background: rgba(15, 20, 30, 0.9); outline: none; }
        .glass-input:read-only { opacity: 0.5; cursor: not-allowed; }

        /* --- UTILS --- */
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #94a3b8; font-weight: 500; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #818cf8; border-left: 3px solid #818cf8; border-radius: 4px 12px 12px 4px; }
        
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
                        <a href="{{ route('admin.dashboard') }}" class="nav-link">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                            Dashboard
                        </a>
                    </div>
                </div>
                <div>
                    <p class="px-4 text-[10px] font-extrabold text-white/30 uppercase tracking-widest mb-3">Academic</p>
                    <div class="space-y-1">
                        <a href="{{ route('admin.analytics.questions') }}" class="nav-link active">
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
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-cyan-500/5 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-fuchsia-500/5 rounded-full blur-[100px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
            </div>

            {{-- Top Header --}}
            <header class="h-20 glass-header flex items-center justify-between px-10 shrink-0 z-20">
                <div>
                    <h2 class="text-white font-bold text-lg">Analisis Butir Soal</h2>
                    <p class="text-xs text-white/40 flex items-center gap-2">
                        {{-- <span class="w-2 h-2 rounded-full bg-cyan-500 animate-pulse"></span> Data Realtime --}}
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Database Connected

                    </p>
                </div>
                <div class="flex items-center gap-4">
                    <button onclick="openCreateModal()" class="flex items-center gap-2 px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-lg shadow-indigo-500/20 transition transform hover:scale-105">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                        Buat Soal Baru
                    </button>
                </div>
            </header>

            {{-- Content Scroll Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8">

                    {{-- A. STATS CARDS --}}
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal" style="animation-delay: 0.1s;">
                        
                        {{-- Total Soal --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition transform group-hover:scale-110">
                                <svg class="w-16 h-16 text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Total Soal</p>
                            <h3 class="text-3xl font-black text-white mt-2">{{ $questions->count() ?? 0 }}</h3>
                            <div class="mt-2 text-xs text-cyan-400 font-bold bg-cyan-500/10 px-2 py-1 rounded w-fit border border-cyan-500/10">Active Items</div>
                        </div>

                        {{-- Rata-rata Akurasi --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Global Akurasi</p>
                            @php $avgAcc = $questions->avg('accuracy') ?? 0; @endphp
                            <h3 class="text-3xl font-black {{ $avgAcc >= 70 ? 'text-emerald-400' : 'text-yellow-400' }} mt-2">{{ round($avgAcc, 1) }}%</h3>
                            <p class="text-xs text-white/30 mt-2">Rata-rata tingkat pemahaman</p>
                        </div>

                        {{-- Soal Tersulit (Insight) --}}
                        <div class="glass-card rounded-2xl p-6 relative overflow-hidden group border-red-500/10 hover:border-red-500/30">
                            <div class="absolute right-0 top-0 p-4 opacity-10 group-hover:opacity-20 transition">
                                <svg class="w-16 h-16 text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Perlu Perhatian</p>
                            @php $hardest = $questions->sortBy('accuracy')->first(); @endphp
                            <div class="mt-2">
                                <span class="text-xs bg-red-500/10 text-red-400 px-2 py-1 rounded border border-red-500/20 font-bold">Akurasi: {{ $hardest->accuracy ?? 0 }}%</span>
                            </div>
                            <p class="text-sm font-medium text-white/80 mt-3 line-clamp-2 leading-snug">
                                "{{ $hardest->question_text ?? 'Belum ada data soal' }}"
                            </p>
                        </div>
                    </div>

                    {{-- B. SEARCH & FILTERS --}}
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 reveal" style="animation-delay: 0.2s;">
                        <div class="relative w-full md:w-96 group">
                            <input id="searchInput" type="text" placeholder="Cari pertanyaan... (Keywords)" 
                                class="w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition shadow-lg">
                            <div class="absolute left-3 top-3.5 text-white/30 group-focus-within:text-cyan-400 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>

                        <div class="flex gap-3 w-full md:w-auto">
                            <select id="filterDifficulty" class="bg-[#0a0e17] border border-white/10 text-white text-xs rounded-xl px-4 py-3 outline-none focus:border-cyan-500 cursor-pointer min-w-[160px]">
                                <option value="all">Semua Kesulitan</option>
                                <option value="Sulit">🔥 Sulit (< 50%)</option>
                                <option value="Sedang">⚖️ Sedang (50-79%)</option>
                                <option value="Mudah">✅ Mudah (≥ 80%)</option>
                            </select>
                        </div>
                    </div>

                    {{-- C. DATA TABLE (GAP ANALYSIS) --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.3s;">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-[#05080f] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-10 backdrop-blur-md">
                                    <tr>
                                        <th class="px-6 py-4 w-[40%]">Pertanyaan</th>
                                        <th class="px-6 py-4 text-center">Gap Penjawab (Visual)</th>
                                        <th class="px-6 py-4 text-center">Akurasi</th>
                                        <th class="px-6 py-4 text-center">Status</th>
                                        <th class="px-6 py-4 text-right">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody id="questionsTableBody" class="divide-y divide-white/5">
                                    @forelse($questions ?? [] as $q)
                                    <tr class="table-row group question-row" 
                                        data-search="{{ strtolower($q->question_text) }}"
                                        data-difficulty="{{ $q->status }}">
                                        
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col gap-1.5">
                                                <span class="w-fit px-2 py-0.5 rounded text-[9px] font-bold uppercase tracking-wider bg-white/5 text-white/50 border border-white/5">
                                                    BAB {{ $q->chapter_id }}
                                                </span>
                                                <p class="text-white font-medium line-clamp-2 group-hover:text-cyan-400 transition cursor-default leading-snug" title="{{ $q->question_text }}">
                                                    {{ $q->question_text }}
                                                </p>
                                            </div>
                                        </td>

                                        {{-- VISUALISASI GAP --}}
                                        <td class="px-6 py-4">
                                            <div class="flex flex-col items-center gap-1.5">
                                                <div class="w-32 h-2.5 bg-white/10 rounded-full overflow-hidden flex relative">
                                                    @if($q->total_attempts > 0)
                                                        {{-- Correct Bar --}}
                                                        <div class="h-full bg-emerald-500 gap-bar" style="width: {{ $q->accuracy }}%" title="Benar"></div>
                                                        {{-- Wrong Bar --}}
                                                        <div class="h-full bg-red-500 gap-bar" style="width: {{ 100 - $q->accuracy }}%" title="Salah"></div>
                                                    @else
                                                        <div class="w-full h-full bg-white/5"></div>
                                                    @endif
                                                </div>
                                                <div class="flex justify-between w-32 text-[9px] font-bold">
                                                    <span class="text-emerald-400">{{ $q->correct_count }} Benar</span>
                                                    <span class="text-red-400">{{ $q->wrong_count }} Salah</span>
                                                </div>
                                            </div>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            <span class="text-lg font-black {{ $q->accuracy >= 80 ? 'text-emerald-400' : ($q->accuracy >= 50 ? 'text-yellow-400' : 'text-red-400') }}">
                                                {{ $q->accuracy }}%
                                            </span>
                                        </td>

                                        <td class="px-6 py-4 text-center">
                                            @if($q->status == 'Sulit')
                                                <span class="px-2 py-1 rounded bg-red-500/10 text-red-400 border border-red-500/20 text-[10px] font-bold uppercase">Sulit</span>
                                            @elseif($q->status == 'Sedang')
                                                <span class="px-2 py-1 rounded bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[10px] font-bold uppercase">Sedang</span>
                                            @else
                                                <span class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold uppercase">Mudah</span>
                                            @endif
                                        </td>

                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition">
                                                <button onclick='openInsightModal(@json($q->list_correct), @json($q->list_wrong))' 
                                                    class="p-2 rounded-lg bg-white/5 hover:bg-indigo-500/20 text-white/50 hover:text-indigo-400 transition border border-transparent hover:border-indigo-500/30" 
                                                    title="Lihat Detail Penjawab">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="py-20 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <div class="text-5xl mb-4 grayscale">📊</div>
                                                <h3 class="text-lg font-bold text-white">Belum ada data</h3>
                                                <p class="text-xs text-white/50">Silakan tambahkan kuis baru.</p>
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

    {{-- ==================== MODALS ==================== --}}

    {{-- 1. CREATE QUIZ MODAL --}}
    <div id="createQuizModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[#020617]/80 backdrop-blur-sm transition-opacity" onclick="closeCreateModal()"></div>
        <div id="createContent" class="relative w-full max-w-2xl bg-[#0f141e] border border-white/10 rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
            
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <h3 class="text-xl font-bold text-white flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-500/20 rounded-lg text-indigo-400 text-xs">NEW</span> Buat Soal Baru
                </h3>
                <button onclick="closeCreateModal()" class="text-white/40 hover:text-white transition">✕</button>
            </div>

            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <form id="createQuizForm" action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="space-y-6">
                        <div>
                            <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Pertanyaan Utama</label>
                            <textarea name="question_text" rows="3" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl p-4 text-white outline-none focus:border-cyan-500 transition resize-none placeholder-white/20" placeholder="Tulis pertanyaan di sini..." required></textarea>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Bab Materi</label>
                                <select name="chapter_id" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl px-4 py-3 text-white outline-none focus:border-cyan-500 appearance-none cursor-pointer">
                                    <option value="1">Bab 1 - Fundamental</option>
                                    <option value="2">Bab 2 - Layouting</option>
                                    <option value="3">Bab 3 - Components</option>
                                    <option value="4">Bab 4 - Advanced</option>
                                </select>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Kunci Jawaban</label>
                                <select name="correct_answer" class="w-full bg-[#0a0e17] border border-emerald-500/30 rounded-xl px-4 py-3 text-emerald-400 font-bold outline-none focus:border-emerald-500 appearance-none cursor-pointer">
                                    <option value="option_a">Opsi A</option>
                                    <option value="option_b">Opsi B</option>
                                    <option value="option_c">Opsi C</option>
                                    <option value="option_d">Opsi D</option>
                                </select>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider block">Opsi Jawaban</label>
                            @foreach(['a','b','c','d'] as $opt)
                            <div class="flex items-center gap-3">
                                <span class="w-9 h-9 rounded-lg bg-white/5 border border-white/10 flex items-center justify-center font-bold text-white/50 text-xs uppercase">{{ $opt }}</span>
                                <input type="text" name="option_{{ $opt }}" class="flex-1 bg-[#0a0e17] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-cyan-500 text-sm placeholder-white/20" placeholder="Jawaban {{ strtoupper($opt) }}" required>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </form>
            </div>

            <div class="p-6 border-t border-white/5 bg-[#05080f] flex justify-end gap-3 rounded-b-3xl">
                <button onclick="closeCreateModal()" class="px-6 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/5 font-bold text-xs transition">Batal</button>
                <button onclick="submitQuizForm()" class="px-8 py-2.5 rounded-xl bg-gradient-to-r from-cyan-600 to-blue-600 hover:from-cyan-500 hover:to-blue-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/20 transition transform active:scale-95 flex items-center gap-2">
                    Simpan Data
                </button>
            </div>
        </div>
    </div>

    {{-- 2. INSIGHT MODAL --}}
    <div id="insightModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm transition-opacity" onclick="closeInsightModal()"></div>
        <div id="insightContent" class="relative w-full max-w-sm bg-[#0f141e] border border-white/10 rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[80vh]">
            
            <div class="p-5 border-b border-white/5 bg-white/5 flex justify-between items-center">
                <h3 class="font-bold text-white text-lg">Detail Penjawab</h3>
                <button onclick="closeInsightModal()" class="text-white/40 hover:text-white transition">✕</button>
            </div>

            <div class="p-5 overflow-y-auto custom-scrollbar flex-1 space-y-6">
                
                {{-- Correct List --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-bold text-emerald-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse"></span> Benar
                        </p>
                        <span id="countCorrect" class="text-[10px] bg-emerald-500/10 text-emerald-400 px-2 py-0.5 rounded">0 Siswa</span>
                    </div>
                    <div id="listCorrect" class="space-y-2"></div>
                </div>

                <div class="h-px bg-white/5 w-full"></div>

                {{-- Wrong List --}}
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <p class="text-[10px] font-bold text-red-400 uppercase tracking-widest flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full bg-red-500 animate-pulse"></span> Salah
                        </p>
                        <span id="countWrong" class="text-[10px] bg-red-500/10 text-red-400 px-2 py-0.5 rounded">0 Siswa</span>
                    </div>
                    <div id="listWrong" class="space-y-2"></div>
                </div>

            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC --}}
    <script>
        // --- SEARCH & FILTER ---
        $('#searchInput, #filterDifficulty').on('input change', function() {
            const searchVal = $('#searchInput').val().toLowerCase();
            const difficultyVal = $('#filterDifficulty').val();

            $('.question-row').filter(function() {
                const textMatch = $(this).data('search').includes(searchVal);
                const diffMatch = difficultyVal === 'all' || $(this).data('difficulty') === difficultyVal;
                $(this).toggle(textMatch && diffMatch);
            });
        });

        // --- CREATE MODAL ---
        function openCreateModal() {
            $('#createQuizModal').removeClass('hidden');
            setTimeout(() => { $('#createContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        }
        function closeCreateModal() {
            $('#createContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => { $('#createQuizModal').addClass('hidden'); }, 300);
        }
        function submitQuizForm() {
            const form = $('#createQuizForm');
            if(!form[0].checkValidity()) { form[0].reportValidity(); return; }
            
            // SweetAlert Loading
            Swal.fire({ title: 'Menyimpan...', didOpen: () => { Swal.showLoading() }, background: '#0f141e', color: '#fff' });

            $.post(form.attr('action'), form.serialize())
                .done(() => { 
                    Swal.fire({
                        title: 'Berhasil!', text: 'Soal ditambahkan', icon: 'success', 
                        background: '#0f141e', color: '#fff', confirmButtonColor: '#2563eb'
                    }).then(() => location.reload()); 
                })
                .fail(() => { 
                    Swal.fire({
                        title: 'Gagal', text: 'Terjadi kesalahan sistem', icon: 'error',
                        background: '#0f141e', color: '#fff'
                    }); 
                });
        }

        // --- INSIGHT MODAL ---
        function openInsightModal(correct, wrong) {
            // Update Counts
            $('#countCorrect').text(correct.length + ' Siswa');
            $('#countWrong').text(wrong.length + ' Siswa');

            // Render Correct List
            const correctHtml = correct.length ? correct.map(name => 
                `<div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-emerald-500/5 border border-emerald-500/10 text-xs text-white">
                    <div class="w-6 h-6 rounded-lg bg-emerald-500 text-[#0f141e] flex items-center justify-center font-bold text-[10px]">${name.charAt(0)}</div>
                    <span class="font-medium">${name}</span>
                </div>`
            ).join('') : '<p class="text-xs text-white/20 italic pl-1">Tidak ada data</p>';
            $('#listCorrect').html(correctHtml);

            // Render Wrong List
            const wrongHtml = wrong.length ? wrong.map(name => 
                `<div class="flex items-center gap-3 px-3 py-2 rounded-xl bg-red-500/5 border border-red-500/10 text-xs text-white">
                    <div class="w-6 h-6 rounded-lg bg-red-500 text-[#0f141e] flex items-center justify-center font-bold text-[10px]">${name.charAt(0)}</div>
                    <span class="font-medium">${name}</span>
                </div>`
            ).join('') : '<p class="text-xs text-white/20 italic pl-1">Tidak ada data</p>';
            $('#listWrong').html(wrongHtml);

            $('#insightModal').removeClass('hidden');
            setTimeout(() => { $('#insightContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        }
        function closeInsightModal() {
            $('#insightContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => { $('#insightModal').addClass('hidden'); }, 300);
        }

        // ESC Key Close
        $(document).keydown(e => { 
            if(e.key === "Escape") { closeCreateModal(); closeInsightModal(); } 
        });
    </script>

</body>
</html>