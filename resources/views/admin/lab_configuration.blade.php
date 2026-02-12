<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Lab Configuration · Utilwind</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&family=JetBrains+Mono:wght@400&display=swap" rel="stylesheet">
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
                        <a href="{{ route('admin.analytics.questions') }}" class="nav-link">
                            <svg class="w-5 h-5 text-slate-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                            Bank Soal & Kuis
                        </a>
                        {{-- ACTIVE STATE --}}
                        <a href="{{ route('admin.labs.index') }}" class="nav-link active">
                            <svg class="w-5 h-5 text-indigo-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
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
                <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-500/5 rounded-full blur-[100px]"></div>
                <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-600/10 rounded-full blur-[100px]"></div>
                <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
            </div>

            {{-- Top Header (Minimal) --}}
            <header class="h-20 glass-header flex items-center justify-between px-10 shrink-0 z-20">
                <div>
                    <h2 class="text-white font-bold text-lg">Lab Configuration</h2>
                    <p class="text-xs text-white/40 flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span> Database Connected
                    </p>
                </div>
                
            </header>

            {{-- Content Scroll Area --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar p-10 relative z-10">
                <div class="max-w-7xl mx-auto space-y-8">

                    {{-- HERO SECTION --}}
                    <div class="relative rounded-3xl p-10 overflow-hidden bg-gradient-to-r from-indigo-900/60 via-[#0f141e] to-purple-900/40 border border-white/10 shadow-2xl group reveal">
                        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.07] mix-blend-overlay"></div>
                        <div class="absolute -right-20 -top-20 w-80 h-80 bg-indigo-500/20 rounded-full blur-[100px] group-hover:bg-indigo-500/30 transition duration-1000"></div>
                        
                        <div class="relative z-10 flex flex-col md:flex-row justify-between items-start md:items-center gap-6">
                            <div>
                                <h1 class="text-4xl font-black text-white tracking-tight mb-3">Lab Ecosystem <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-cyan-400">Control</span></h1>
                                <p class="text-indigo-200/60 max-w-xl text-sm leading-relaxed mb-6">
                                    Pusat manajemen modul praktikum. Tambahkan lab baru, atur durasi pengerjaan, dan kelola langkah-langkah (tasks) secara terstruktur.
                                </p>
                                
                                <div class="flex items-center gap-3">
                                    <div class="flex items-center gap-2 px-4 py-2 rounded-lg bg-black/20 border border-white/5 backdrop-blur-sm">
                                        <span class="text-2xl font-bold text-white">{{ $totalLabs ?? 0 }}</span>
                                        <span class="text-[10px] text-white/40 uppercase tracking-widest font-bold">Total Modules</span>
                                    </div>
                                </div>
                            </div>
                            
                            <button onclick="openLabModal()" class="group relative px-8 py-4 rounded-2xl bg-white text-indigo-950 font-bold text-sm shadow-[0_0_20px_rgba(255,255,255,0.1)] hover:shadow-[0_0_40px_rgba(255,255,255,0.3)] hover:scale-105 transition transform overflow-hidden">
                                <div class="absolute inset-0 bg-gradient-to-r from-indigo-50 to-white opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                <span class="relative flex items-center gap-3">
                                    <svg class="w-5 h-5 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    Create New Module
                                </span>
                            </button>
                        </div>
                    </div>

                    {{-- SEARCH --}}
                    <div class="flex flex-col md:flex-row justify-between items-center gap-4 reveal" style="animation-delay: 0.1s;">
                        <div class="relative w-full md:w-96 group">
                            <input id="searchLab" type="text" placeholder="Cari berdasarkan judul atau slug..." 
                                class="w-full bg-[#0a0e17] border border-white/10 rounded-xl pl-10 pr-4 py-3 text-sm text-white focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 outline-none transition shadow-lg hover:border-white/20">
                            <div class="absolute left-3 top-3.5 text-white/30 group-focus-within:text-indigo-400 transition">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                        </div>
                    </div>

                    {{-- TABLE --}}
                    <div class="glass-card rounded-2xl overflow-hidden reveal" style="animation-delay: 0.2s;">
                        <div class="overflow-x-auto custom-scrollbar">
                            <table class="w-full text-sm text-left border-collapse">
                                <thead class="bg-[#05080f] text-white/40 text-[10px] uppercase font-bold border-b border-white/5 sticky top-0 z-10">
                                    <tr>
                                        <th class="px-6 py-4 w-[35%]">Module Info</th>
                                        <th class="px-6 py-4">Slug Identifier</th>
                                        <th class="px-6 py-4 text-center">Duration</th>
                                        <th class="px-6 py-4 text-center">Pass Grade</th>
                                        <th class="px-6 py-4 text-right">Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-white/5" id="labTableBody">
                                    @forelse($labs ?? [] as $lab)
                                    <tr class="table-row group hover:bg-white/5 transition">
                                        <td class="px-6 py-4">
                                            <div class="flex items-center gap-4">
                                                <div class="w-10 h-10 rounded-lg bg-indigo-500/10 border border-indigo-500/20 flex items-center justify-center text-indigo-400 font-bold text-lg shrink-0">
                                                    {{ substr($lab->title, 0, 1) }}
                                                </div>
                                                <div class="min-w-0">
                                                    <p class="font-bold text-white text-sm group-hover:text-indigo-400 transition truncate">{{ $lab->title }}</p>
                                                    <p class="text-[10px] text-white/40 line-clamp-1">{{ $lab->description }}</p>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="font-mono text-xs text-white/60 bg-white/5 px-2 py-1 rounded border border-white/5">{{ $lab->slug }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-white/80 font-bold">{{ $lab->duration_minutes }}</span> <span class="text-[10px] text-white/40">mins</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold">
                                                Min {{ $lab->passing_grade }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            <div class="flex justify-end gap-2 opacity-60 group-hover:opacity-100 transition">
                                                {{-- TASKS BUTTON --}}
                                                <button onclick="openTaskManager({{ $lab->id }}, '{{ $lab->title }}')" class="px-3 py-1.5 rounded-lg bg-indigo-600/20 text-indigo-400 hover:bg-indigo-600 hover:text-white border border-indigo-600/30 text-xs font-bold transition flex items-center gap-1.5" title="Manage Steps">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                                                    Steps
                                                </button>
                                                
                                                {{-- EDIT BUTTON --}}
                                                <button onclick="openLabModal('edit', {{ json_encode($lab) }})" class="p-1.5 rounded-lg bg-white/5 hover:bg-white/10 hover:text-white transition text-white/50 border border-transparent hover:border-white/10">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </button>
                                                
                                                {{-- DELETE BUTTON --}}
                                                <button onclick="deleteLab({{ $lab->id }})" class="p-1.5 rounded-lg bg-white/5 hover:bg-red-500/20 text-white/50 hover:text-red-400 transition border border-transparent hover:border-red-500/30">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="py-20 text-center">
                                            <div class="flex flex-col items-center opacity-30">
                                                <div class="text-5xl mb-4 grayscale">📦</div>
                                                <h3 class="text-lg font-bold text-white">No Labs Found</h3>
                                                <p class="text-xs text-white/50">Start by clicking "Create New Module".</p>
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

    {{-- 1. LAB CONFIG MODAL (Sesuai Tabel Labs) --}}
    <div id="labModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm transition-opacity" onclick="closeLabModal()"></div>
        <div id="labModalContent" class="relative w-full max-w-lg bg-[#0f141e] border border-white/10 rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5 rounded-t-3xl">
                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                    <span class="p-1.5 bg-indigo-500/20 rounded-lg text-indigo-400 text-xs">MODULE</span> <span id="modalTitle">New Lab</span>
                </h3>
                <button onclick="closeLabModal()" class="text-white/40 hover:text-white transition">✕</button>
            </div>
            <div class="p-6 space-y-5">
                <form id="labForm">
                    @csrf
                    <input type="hidden" id="labId" name="id">
                    <div>
                        <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Lab Title</label>
                        <input type="text" id="labTitle" name="title" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none" placeholder="e.g. Advanced CSS Layout" required>
                    </div>
                    <div>
                        <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Description</label>
                        <textarea id="labDesc" name="description" rows="2" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none resize-none" placeholder="Brief objectives..."></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Duration (Mins)</label>
                            <input type="number" id="labDuration" name="duration" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none" placeholder="60" value="60">
                        </div>
                        <div>
                            <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-2 block">Passing Grade</label>
                            <input type="number" id="labGrade" name="passing_grade" class="w-full glass-input rounded-xl px-4 py-3 text-sm outline-none" placeholder="100" value="100">
                        </div>
                    </div>
                </form>
            </div>
            <div class="p-6 border-t border-white/5 bg-[#05080f] flex justify-end gap-3 rounded-b-3xl">
                <button onclick="closeLabModal()" class="px-5 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/5 font-bold text-xs transition">Cancel</button>
                <button onclick="saveLab()" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-lg shadow-indigo-500/20 transition transform active:scale-95">Save Module</button>
            </div>
        </div>
    </div>

    {{-- 2. TASK MANAGER (Auto Index Logic) --}}
    <div id="taskModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center p-4">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm transition-opacity" onclick="closeTaskManager()"></div>
        <div id="taskContent" class="relative w-full max-w-6xl bg-[#0f141e] border border-white/10 rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 flex flex-col h-[85vh]">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5 rounded-t-3xl">
                <div>
                    <h3 class="text-xl font-bold text-white flex items-center gap-2">
                        <span class="p-1 bg-indigo-500/20 rounded text-indigo-400 text-sm">STEPS MANAGER</span> 
                        <span id="modalLabTitle">Lab Title</span>
                    </h3>
                    <p class="text-xs text-white/40 mt-1">Configure step-by-step instructions and validations.</p>
                </div>
                <button onclick="closeTaskManager()" class="text-white/40 hover:text-white transition">✕</button>
            </div>
            <div class="flex flex-1 overflow-hidden">
                {{-- LEFT: LIST --}}
                <div class="w-1/3 border-r border-white/5 flex flex-col bg-[#0a0e17]/50">
                    <div class="p-4 border-b border-white/5"><h4 class="text-xs font-bold text-white/50 uppercase tracking-wider">Existing Steps</h4></div>
                    <div id="taskListContainer" class="flex-1 overflow-y-auto custom-scrollbar p-4 space-y-2"></div>
                </div>
                {{-- RIGHT: FORM --}}
                <div class="w-2/3 flex flex-col bg-[#0f141e]">
                    <div class="p-4 border-b border-white/5"><h4 class="text-xs font-bold text-white/50 uppercase tracking-wider">Step Editor</h4></div>
                    <div class="flex-1 overflow-y-auto custom-scrollbar p-6">
                        <form id="taskForm">
                            @csrf
                            <input type="hidden" name="lab_id" id="taskLabId">
                            <div class="grid grid-cols-6 gap-4 mb-4">
                                <div class="col-span-5">
                                    <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Step Title</label>
                                    <input type="text" name="title" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm outline-none" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Index</label>
                                    {{-- READONLY UNTUK MENCEGAH DUPLIKAT MANUAL --}}
                                    <input type="number" name="order_index" id="taskOrderIndex" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm outline-none text-center font-bold text-indigo-400" readonly>
                                </div>
                            </div>
                            <div class="mb-4">
                                <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Instruction (Markdown Support)</label>
                                <textarea name="instruction" rows="2" class="w-full glass-input rounded-xl px-4 py-2.5 text-sm outline-none resize-none" required></textarea>
                            </div>
                            <div class="grid grid-cols-2 gap-4 mb-4">
                                <div>
                                    <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Initial Code</label>
                                    <textarea name="initial_code" rows="6" class="w-full glass-input rounded-xl px-4 py-2.5 text-xs outline-none font-mono bg-[#05080f] leading-relaxed" required></textarea>
                                </div>
                                <div>
                                    <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Validation Rules (JSON Array)</label>
                                    <textarea name="validation_rules" rows="6" class="w-full glass-input rounded-xl px-4 py-2.5 text-xs outline-none font-mono bg-[#05080f] leading-relaxed" placeholder='"bg-red-500", "p-4"' required></textarea>
                                    <p class="text-[9px] text-white/30 mt-1">Separate rules with comma.</p>
                                </div>
                            </div>
                            <div>
                                <label class="text-[10px] font-bold text-white/50 uppercase mb-2 block">Points</label>
                                <input type="number" name="points" class="w-32 glass-input rounded-xl px-4 py-2.5 text-sm outline-none" value="25" required>
                            </div>
                        </form>
                    </div>
                    <div class="p-4 border-t border-white/5 bg-[#0a0e17] flex justify-end">
                        <button onclick="submitTask()" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs transition shadow-lg shadow-indigo-500/20">Save Step Configuration</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- SCRIPT LOGIC --}}
    <script>
        // --- LAB CRUD ---
        function openLabModal(mode = 'create', data = null) {
            const modal = document.getElementById('labModal');
            const content = document.getElementById('labModalContent');
            const title = document.getElementById('modalTitle');
            document.getElementById('labForm').reset();

            if (mode === 'edit' && data) {
                title.innerText = 'Edit Module';
                document.getElementById('labId').value = data.id;
                document.getElementById('labTitle').value = data.title;
                document.getElementById('labDesc').value = data.description;
                document.getElementById('labDuration').value = data.duration_minutes; // Sesuai DB
                document.getElementById('labGrade').value = data.passing_grade; // Sesuai DB
            } else { title.innerText = 'New Module'; }

            modal.classList.remove('hidden');
            setTimeout(() => { content.classList.remove('scale-95', 'opacity-0'); content.classList.add('scale-100', 'opacity-100'); }, 10);
        }
        function closeLabModal() {
            const modal = document.getElementById('labModal');
            const content = document.getElementById('labModalContent');
            content.classList.remove('scale-100', 'opacity-100'); content.classList.add('scale-95', 'opacity-0');
            setTimeout(() => { modal.classList.add('hidden'); }, 300);
        }
        function saveLab() {
            const form = $('#labForm');
            const id = $('#labId').val();
            let url = "{{ route('admin.labs.store') }}";
            let method = "POST";
            if (id) { url = `/admin/labs/${id}`; method = "PUT"; }

            Swal.fire({ title: 'Processing...', didOpen: () => { Swal.showLoading() }, background: '#0f141e', color: '#fff' });
            
            $.ajax({
                url: url, type: "POST", data: form.serialize() + (id ? "&_method=PUT" : ""),
                success: function(res) {
                    Swal.fire({ title: 'Success!', text: 'Module saved successfully.', icon: 'success', background: '#0f141e', color: '#fff' }).then(() => { closeLabModal(); location.reload(); });
                },
                error: function() { Swal.fire({ title: 'Error!', text: 'Failed to save module.', icon: 'error', background: '#0f141e', color: '#fff' }); }
            });
        }

        // --- TASK MANAGER (AUTO INDEX LOGIC) ---
        let currentLabId = null;
        function openTaskManager(labId, labTitle) {
            currentLabId = labId;
            $('#modalLabTitle').text(labTitle);
            $('#taskLabId').val(labId);
            $('#taskForm')[0].reset();
            loadTasks(labId); // Logic auto-index ada di dalam success function ini
            $('#taskModal').removeClass('hidden');
            setTimeout(() => { $('#taskContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        }
        function closeTaskManager() {
            $('#taskContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
            setTimeout(() => { $('#taskModal').addClass('hidden'); }, 300);
        }
        
        function loadTasks(labId) {
            $('#taskListContainer').html('<div class="text-center py-10 text-white/30 text-xs animate-pulse">Fetching data...</div>');
            
            $.get(`/admin/labs/${labId}/tasks`, function(tasks) {
                // ================================
                // AUTO INCREMENT LOGIC (NO DUPLICATE)
                // ================================
                let nextIndex = 1;
                if(tasks.length > 0) {
                    const maxOrder = Math.max(...tasks.map(t => t.order_index));
                    nextIndex = maxOrder + 1;
                }
                $('#taskOrderIndex').val(nextIndex); // Set Auto Value

                // Render List
                if(tasks.length === 0) { $('#taskListContainer').html('<div class="text-center py-10 text-white/20 text-xs italic">No steps created yet.</div>'); return; }
                
                let html = '';
                tasks.forEach(task => {
                    html += `<div class="p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition group relative mb-2">
                        <div class="flex justify-between items-start">
                            <div>
                                <span class="text-[9px] font-bold text-indigo-400 bg-indigo-500/10 px-1.5 py-0.5 rounded border border-indigo-500/20">Step ${task.order_index}</span>
                                <h5 class="text-sm font-bold text-white mt-1 line-clamp-1">${task.title}</h5>
                                <p class="text-[10px] text-white/40 mt-0.5 font-mono">${task.points} Points</p>
                            </div>
                            <button onclick="deleteTask(${task.id})" class="text-white/20 hover:text-red-400 transition p-1"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg></button>
                        </div>
                    </div>`;
                });
                $('#taskListContainer').html(html);
            });
        }

        function submitTask() {
            const form = $('#taskForm');
            if(!form[0].checkValidity()) { form[0].reportValidity(); return; }
            $.post("{{ route('admin.labs.tasks.store') }}", form.serialize())
                .done((res) => {
                    const Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 3000, background: '#0f141e', color: '#fff', iconColor: '#10b981' });
                    Toast.fire({ icon: 'success', title: 'Step added successfully' });
                    loadTasks(currentLabId); // Reload & Recalculate Index
                    
                    // Reset Form Kecuali Lab ID
                    const labId = $('#taskLabId').val();
                    form[0].reset(); 
                    $('#taskLabId').val(labId);
                });
        }
        function deleteTask(id) {
            if(!confirm('Permanently delete this step?')) return;
            $.ajax({ url: `/admin/labs/tasks/${id}`, type: 'DELETE', data: { _token: "{{ csrf_token() }}" }, success: function() { loadTasks(currentLabId); } });
        }
        function deleteLab(id) {
            Swal.fire({ title: 'Delete Module?', text: "This action cannot be undone!", icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', confirmButtonText: 'Delete', background: '#0f141e', color: '#fff' }).then((result) => {
                if (result.isConfirmed) { $.ajax({ url: `/admin/labs/${id}`, type: 'DELETE', data: { _token: "{{ csrf_token() }}" }, success: function() { location.reload(); } }); }
            });
        }

        // --- SEARCH ---
        $('#searchLab').on('keyup', function() {
            const val = $(this).val().toLowerCase();
            $('#labTableBody tr').filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1) });
        });

        $(document).keydown(e => { if(e.key === "Escape") { closeLabModal(); closeTaskManager(); } });
    </script>

</body>
</html>