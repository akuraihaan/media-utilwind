<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analytics Dashboard · Utilwind</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- KONFIGURASI DARK MODE TAILWIND --}}
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['JetBrains Mono', 'monospace'],
                    }
                }
            }
        }
    </script>

    {{-- SCRIPT PENGECEKAN TEMA OTOMATIS --}}
    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* --- GLASS COMPONENTS THEME RESPONSIVE --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(0,0,0,0.05); z-index: 50; }
        .dark .glass-sidebar { background: rgba(5, 8, 16, 0.95); border-right: 1px solid rgba(255,255,255,0.05); }

        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 40; }
        .dark .glass-header { background: rgba(2, 6, 23, 0.8); border-bottom: 1px solid rgba(255,255,255,0.05); }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; 
        }
        .dark .glass-card {
            background: rgba(10, 14, 23, 0.85); border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); z-index: 30; }
        .dark .glass-card:hover { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); }
        
        .card-bg-gfx { position: absolute; inset: 0; overflow: hidden; border-radius: 1rem; pointer-events: none; z-index: 0; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.1); color: #0f172a; transition: 0.3s; }
        .dark .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .glass-input:focus { border-color: #6366f1; background: rgba(0, 0, 0, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .dark .glass-input:focus { background: rgba(255, 255, 255, 0.05); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #64748b; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(0, 0, 0, 0.03); color: #0f172a; }
        .dark .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #6366f1; border-left: 3px solid #6366f1; border-radius: 4px 12px 12px 4px; }
        .dark .nav-link.active { color: #818cf8; border-left-color: #818cf8; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(0,0,0,0.03); }
        .table-row:hover { background: rgba(0,0,0,0.02); }
        .dark .table-row { border-bottom: 1px solid rgba(255,255,255,0.03); }
        .dark .table-row:hover { background: rgba(255,255,255,0.02); }

        /* =========================================================================
           SISTEM TOOLTIP SOLID
           ========================================================================= */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { 
            width: 18px; height: 18px; border-radius: 50%; color: #64748b; 
            font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; 
            cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.1);
        }
        .dark .tooltip-trigger { color: white; border-color: rgba(255,255,255,0.2); }
        .tooltip-trigger:hover { transform: scale(1.15); }
        
        .tooltip-content { 
            opacity: 0; visibility: hidden; position: absolute; pointer-events: none; 
            width: max-content; min-width: 220px; max-width: 280px; white-space: normal; text-align: left; 
            background-color: #ffffff; color: #1e293b; font-size: 11px; padding: 12px 14px; line-height: 1.5;
            border-radius: 12px; box-shadow: 0 20px 50px rgba(0,0,0,0.15); z-index: 99999; border: 1px solid #e2e8f0;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
        }
        .dark .tooltip-content { background-color: #0f141e; color: #e2e8f0; box-shadow: 0 20px 50px rgba(0,0,0,0.9); border: none; }

        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #ffffff transparent; }
        .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #0f141e transparent; }

        .tooltip-up .tooltip-content { bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(10px); }
        .tooltip-up:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-up .tooltip-content::after { content: ''; position: absolute; top: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: #ffffff transparent transparent transparent; }
        .dark .tooltip-up .tooltip-content::after { border-color: #0f141e transparent transparent transparent; }

        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        /* Varian Warna Tooltip */
        .tooltip-indigo .tooltip-trigger { background-color: #e0e7ff; color: #4f46e5; border-color: #c7d2fe; }
        .dark .tooltip-indigo .tooltip-trigger { background-color: #6366f1; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        .tooltip-cyan .tooltip-trigger { background-color: #cffafe; color: #0891b2; border-color: #a5f3fc; }
        .dark .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
        .tooltip-emerald .tooltip-trigger { background-color: #d1fae5; color: #059669; border-color: #a7f3d0; }
        .dark .tooltip-emerald .tooltip-trigger { background-color: #10b981; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
        .tooltip-red .tooltip-trigger { background-color: #fecaca; color: #dc2626; border-color: #fca5a5; }
        .dark .tooltip-red .tooltip-trigger { background-color: #ef4444; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(239,68,68,0.5); }
        .tooltip-fuchsia .tooltip-trigger { background-color: #fae8ff; color: #c026d3; border-color: #f5d0fe; }
        .dark .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(217,70,239,0.5); }
        .tooltip-yellow .tooltip-trigger { background-color: #fef08a; color: #a16207; border-color: #fde047; }
        .dark .tooltip-yellow .tooltip-trigger { background-color: #eab308; color: #020617; border-color: transparent; box-shadow: 0 0 10px rgba(234,179,8,0.5); }
        .tooltip-blue .tooltip-trigger { background-color: #dbeafe; color: #2563eb; border-color: #bfdbfe; }
        .dark .tooltip-blue .tooltip-trigger { background-color: #3b82f6; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(59,130,246,0.5); }

        .modal-open { overflow: hidden; padding-right: 5px; } 
    </style>
</head>
<body class="flex h-screen w-full bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 transition-colors duration-500" x-data="{ 
    sidebarOpen: false, showImport: false, showAdd: false, 
    showLabModal: false, showStudentModal: false, showQuizModal: false, 
    showAvgModal: false, showRemedialModal: false, showPassedModal: false, isFullscreen: false 
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showLabModal = false; showStudentModal = false; showQuizModal = false; showAvgModal = false; showRemedialModal = false; showPassedModal = false;" :class="{'modal-open': showStudentModal || showQuizModal || showLabModal || showAvgModal || showRemedialModal || showPassedModal || showAdd || showImport}">

    {{-- ==============================================================================
         LOGIKA DATA BLADE TERPISAH DENGAN DETAIL KALKULASI YANG JELAS
         ============================================================================== --}}
    @php
        // Variabel Controller Utama
        $totalStudents = isset($totalStudents) ? $totalStudents : \App\Models\User::where('role', 'student')->count();
        $totalAttempts = isset($totalAttempts) ? $totalAttempts : \Illuminate\Support\Facades\DB::table('quiz_attempts')->count();
        $globalAverage = isset($globalAverage) ? $globalAverage : round(\Illuminate\Support\Facades\DB::table('quiz_attempts')->avg('score') ?? 0, 1);

        $totalPassedQuizzesCount = 0;
        $passedQuizzesDetail = collect();
        $passRate = 0;
        
        $realRemedialCount = 0;
        $remedialRate = 0;
        $trueRemedialList = collect();
        
        $realLabCount = 0;
        $avgLabScore = 0; 
        $passedLabsDetail = collect();
        
        $chapterAverages = collect();
        $highestGlobalScore = 0; 
        $lowestGlobalScore = 0;  

        // Perhitungan Waktu
        $avgQuizDuration = 0;
        $avgLabDuration = 0;

        // --- DATA ADMIN & AUDIT LOG ---
        $totalAdmins = isset($totalAdmins) ? $totalAdmins : \App\Models\User::where('role', 'admin')->count();
        $auditLogs = isset($auditLogs) ? $auditLogs : collect();

        // 1. KUIS LULUS & STATISTIK UMUM KUIS
        try {
            $passedQuery = DB::table('quiz_attempts')
                ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
                ->select('users.name', 'quiz_attempts.score', 'quiz_attempts.chapter_id', 'quiz_attempts.created_at')
                ->where('quiz_attempts.score', '>=', 70)
                ->orderByDesc('quiz_attempts.created_at')
                ->get();
            
            $passedQuizzesDetail = $passedQuery->unique(function ($item) { return $item->name . '-' . $item->chapter_id; });
            $totalPassedQuizzesCount = DB::table('quiz_attempts')->where('score', '>=', 70)->count();
            
            if($totalAttempts > 0) {
                $passRate = round(($totalPassedQuizzesCount / $totalAttempts) * 100);
            }

            $highestGlobalScore = DB::table('quiz_attempts')->max('score') ?? 0;
            $lowestGlobalScore = DB::table('quiz_attempts')->min('score') ?? 0;
            $avgQuizDuration = DB::table('quiz_attempts')->avg('time_spent_seconds') ?? 0;

        } catch(\Exception $e) {}

        // 2. REMEDIAL (Siswa Belum Memenuhi KKM)
        try {
            $passedUserIds = DB::table('quiz_attempts')->where('score', '>=', 70)->pluck('user_id')->toArray();
            $remQuery = DB::table('quiz_attempts')
                ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
                ->select('users.name', 'quiz_attempts.score', 'quiz_attempts.chapter_id', 'quiz_attempts.created_at')
                ->whereNotIn('quiz_attempts.user_id', $passedUserIds)
                ->where('quiz_attempts.score', '<', 70)
                ->orderByDesc('quiz_attempts.created_at')
                ->get()
                ->unique('name'); 

            $trueRemedialList = $remQuery->take(10);
            $realRemedialCount = $remQuery->count();
            
            if($totalStudents > 0){
                $remedialRate = round(($realRemedialCount / $totalStudents) * 100);
            }
        } catch(\Exception $e) {}

        // 3. PRAKTIKUM LAB
        try {
            $labQuery = DB::table('lab_histories')
                ->join('users', 'lab_histories.user_id', '=', 'users.id')
                ->leftJoin('labs', 'lab_histories.lab_id', '=', 'labs.id')
                ->select('users.name as student_name', 'labs.title as lab_title', 'lab_histories.lab_id', 'lab_histories.final_score', 'lab_histories.created_at')
                ->where('lab_histories.status', 'passed');

            $realLabCount = $labQuery->count();
            $avgLabScore = $labQuery->avg('lab_histories.final_score') ?? 0; 
            $passedLabsDetail = $labQuery->orderByDesc('lab_histories.created_at')->take(50)->get();
            $avgLabDuration = DB::table('lab_histories')->avg('duration_seconds') ?? 0;
        } catch (\Exception $e) {
            if(isset($totalLabsCompleted)) $realLabCount = $totalLabsCompleted;
        }

        // 4. RATA-RATA PER BAB
        try {
            $chapterAverages = DB::table('quiz_attempts')
                ->select('chapter_id', DB::raw('ROUND(AVG(score),1) as avg_score'), DB::raw('COUNT(*) as total'))
                ->groupBy('chapter_id')
                ->orderBy('chapter_id')
                ->get();
        } catch (\Exception $e) {}

        // 5. UNIFIED RECENT ACTIVITIES (KUIS + LAB)
        $unifiedActivities = collect();
        try {
            // Ambil Kuis Terbaru
            $recentQuizzes = DB::table('quiz_attempts')
                ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
                ->select('users.name', 'quiz_attempts.score', 'quiz_attempts.chapter_id', 'quiz_attempts.time_spent_seconds', 'quiz_attempts.created_at')
                ->orderByDesc('quiz_attempts.created_at')
                ->limit(20)
                ->get();
            
            foreach($recentQuizzes as $q) {
                $unifiedActivities->push([
                    'type' => 'kuis',
                    'user_name' => $q->name,
                    'title' => $q->chapter_id == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $q->chapter_id,
                    'score' => $q->score,
                    'is_passed' => $q->score >= 70,
                    'duration' => $q->time_spent_seconds,
                    'created_at' => $q->created_at,
                    'timestamp' => strtotime($q->created_at)
                ]);
            }

            // Ambil Lab Terbaru
            $recentLabs = DB::table('lab_histories')
                ->join('users', 'lab_histories.user_id', '=', 'users.id')
                ->leftJoin('labs', 'lab_histories.lab_id', '=', 'labs.id')
                ->select('users.name', 'lab_histories.final_score as score', 'labs.title as lab_title', 'lab_histories.status', 'lab_histories.duration_seconds', 'lab_histories.created_at')
                ->orderByDesc('lab_histories.created_at')
                ->limit(20)
                ->get();

            foreach($recentLabs as $l) {
                $unifiedActivities->push([
                    'type' => 'lab',
                    'user_name' => $l->name,
                    'title' => $l->lab_title ?? 'Sesi Lab Virtual',
                    'score' => $l->score,
                    'is_passed' => $l->status === 'passed',
                    'duration' => $l->duration_seconds,
                    'created_at' => $l->created_at,
                    'timestamp' => strtotime($l->created_at)
                ]);
            }

            // Urutkan berdasarkan waktu terbaru dan ambil top 25
            $unifiedActivities = $unifiedActivities->sortByDesc('timestamp')->take(25)->values();
        } catch (\Exception $e) {}

        // 6. FALLBACK AUDIT LOGS (MENGGUNAKAN NAMA TABEL admin_audit_logs)
        if($auditLogs->isEmpty()) {
            try {
                $auditLogs = DB::table('admin_audit_logs')
                    ->join('users', 'admin_audit_logs.admin_id', '=', 'users.id')
                    ->select('admin_audit_logs.id', 'admin_audit_logs.action', 'admin_audit_logs.target_type', 'admin_audit_logs.target_id', 'admin_audit_logs.before', 'admin_audit_logs.after', 'admin_audit_logs.created_at', 'users.name as admin_name')
                    ->orderByDesc('admin_audit_logs.created_at')
                    ->limit(15)
                    ->get()
                    ->map(function($log) {
                        $log->action_label = ucwords(str_replace('_', ' ', $log->action));
                        $log->before_formatted = $log->before ? json_encode(json_decode($log->before), JSON_PRETTY_PRINT) : null;
                        $log->after_formatted = $log->after ? json_encode(json_decode($log->after), JSON_PRETTY_PRINT) : null;
                        return $log;
                    });
            } catch (\Exception $e) {}
        }
    @endphp

    {{-- ==================== 1. SIDEBAR ==================== --}}
    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-colors" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
            <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-20 h-20 bg-indigo-200/50 dark:bg-indigo-500/20 rounded-full blur-[40px] opacity-0 group-hover:opacity-100 transition duration-500"></div>
            
            <a href="{{ route('landing') }}" class="flex items-center gap-3 relative z-10">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain block dark:hidden" style="filter: brightness(0.1);" alt="Logo">
                <img src="{{ asset('images/logo.png') }}" class="h-8 w-auto object-contain hidden dark:block drop-shadow-sm" alt="Logo Dark">
                <div>
                    <h1 class="text-xl font-black text-slate-900 dark:text-white tracking-tight leading-none transition-colors">Util<span class="text-indigo-600 dark:text-indigo-400">wind</span></h1>
                    <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Admin Panel</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        <nav class="flex-1 overflow-y-auto custom-scrollbar py-8 px-4 space-y-8">
            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Overview</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.dashboard') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                        Dashboard
                    </a>
                </div>
            </div>

            <div>
                <p class="px-4 text-[10px] font-extrabold text-slate-400 dark:text-white/30 uppercase tracking-widest mb-3 transition-colors">Academic</p>
                <div class="space-y-1">
                    <a href="{{ route('admin.analytics.questions') }}" class="nav-link {{ request()->routeIs('admin.analytics.questions') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.analytics.questions') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                        Quiz Management
                    </a>
                    <a href="{{ route('admin.labs.index') }}" class="nav-link {{ request()->routeIs('admin.labs.index') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.labs.index') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                        Lab Configuration
                    </a>
                    <a href="{{ route('admin.lab.analytics') }}" class="nav-link {{ request()->routeIs('admin.lab.analytics') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.lab.analytics') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                        Lab Analytics
                    </a>
                    <a href="{{ route('admin.classes.index') }}" class="nav-link {{ request()->routeIs('admin.classes.*') ? 'active' : '' }}">
                        <svg class="w-5 h-5 {{ request()->routeIs('admin.classes.*') ? 'text-indigo-600 dark:text-indigo-400' : 'text-slate-400 dark:text-slate-500' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                        Class Management
                    </a>
                </div>
            </div>
        </nav>

        {{-- USER PROFILE Bawah Sidebar --}}
        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">System Admin</p>
                </div>
            </div>
            
            {{-- THEME TOGGLE BUTTON --}}
            <button id="theme-toggle-sidebar" type="button" class="w-full mb-2 flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 transition-colors border border-transparent dark:border-transparent text-xs font-bold shadow-sm dark:shadow-none">
                <svg id="theme-toggle-dark-icon-sidebar" class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"></path></svg>
                <svg id="theme-toggle-light-icon-sidebar" class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zm2.12-10.607a1 1 0 010 1.414l-.706.707a1 1 0 11-1.414-1.414l.707-.707a1 1 0 011.414 0zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zm-7 4a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM5.05 6.464A1 1 0 106.465 5.05l-.708-.707a1 1 0 00-1.414 1.414l.707.707zm1.414 8.486l-.707.707a1 1 0 01-1.414-1.414l.707-.707a1 1 0 011.414 1.414zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"></path></svg>
                <span id="theme-toggle-text-sidebar">Ubah Tema</span>
            </button>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="w-full flex items-center justify-center gap-2 px-4 py-2.5 rounded-lg bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 hover:bg-red-100 dark:hover:bg-red-500 hover:text-red-700 dark:hover:text-white transition-colors text-xs font-bold border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500 group shadow-sm dark:shadow-none">
                    <svg class="w-3.5 h-3.5 transition group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    Logout
                </button>
            </form>
        </div>
    </aside>

    {{-- ==================== MAIN CONTENT ==================== --}}
    <main class="flex-1 flex flex-col relative z-10 h-full overflow-y-auto overflow-x-hidden">
        
        {{-- Background FX Main --}}
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-300/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay transition-opacity duration-500"></div>
        </div>

        {{-- HEADER RESPONSIVE & BREADCRUMB --}}
        <header class="h-24 glass-header flex flex-col justify-center px-6 md:px-10 shrink-0 sticky top-0 z-40 transition-colors duration-500">
            <div class="flex items-center justify-between w-full">
                <div class="flex items-center gap-4">
                    {{-- Hamburger Menu --}}
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-100 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-white/10 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    
                    {{-- Judul & Breadcrumb --}}
                    <div class="flex items-center gap-3">
                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dashboard </a></li>
                                </ol>
                            </nav>
                            <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight transition-colors">Analytics Overview</h2>
                            <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                Live Data Monitoring
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6">
                    <button onclick="window.location.reload()" class="p-2.5 text-slate-400 hover:text-slate-700 dark:text-white/40 dark:hover:text-white transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-white/5 group hidden sm:block border border-transparent dark:hover:border-white/10" title="Refresh Data">
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>

                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-400 hover:text-slate-700 dark:text-white/40 dark:hover:text-white transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Fullscreen Mode">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="border-l border-slate-200 dark:border-white/10 pl-3 md:pl-5 ml-1 hidden lg:block transition-colors">
                        <button @click="showAdd = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-500 dark:border-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Enroll Student
                        </button>
                    </div>

                    <div class="text-right hidden lg:block border-l border-slate-200 dark:border-white/10 pl-5 ml-1 transition-colors">
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono mt-0.5 transition-colors">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>

                    <button @click="showAdd = true" class="lg:hidden p-2 rounded-lg bg-indigo-600 text-white shadow-[0_0_10px_rgba(99,102,241,0.5)]">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- Scroll Area Data --}}
        <div class="flex-1 p-4 md:p-8 lg:p-10 relative z-10">
            <div class="max-w-7xl mx-auto space-y-8">

                {{-- =======================================================
                     1. HERO INSIGHT SECTION (3 KARTU ATAS)
                     ======================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 reveal">
                    
                    {{-- Card 1: Passed Quizzes --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between overflow-visible cursor-pointer" @click="showQuizModal = true">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-[0.05] dark:opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                            </div>
                        </div>

                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between transition-colors">
                                Kuis Lulus
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left" @click.stop>
                                    <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-cyan-600 dark:text-cyan-400 block mb-1">Perhitungan:</span>
                                        Total pengumpulan kuis yang berhasil mencapai batas KKM (Skor ≥ 70). Diambil berdasarkan kuis unik tiap siswa.
                                        <br><br>
                                        <span class="text-slate-500 dark:text-slate-400 font-mono text-[9px]">Total Seluruh Percobaan: {{ $totalAttempts }}</span>
                                    </div>
                                </div>
                            </h3>
                            
                            <div class="space-y-3 flex-1 mb-4">
                                @forelse($passedQuizzesDetail->take(3) as $act)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-cyan-50 dark:bg-cyan-500/20 flex items-center justify-center text-xs font-bold text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 transition-colors">
                                        {{ substr($act->name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate transition-colors">{{ $act->name }}</p>
                                        <p class="text-[9px] text-slate-500 dark:text-white/40 mt-0.5 transition-colors">Nilai: <span class="text-cyan-600 dark:text-cyan-400 font-bold">{{ $act->score }}</span> • {{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</p>
                                    </div>
                                </div>
                                @empty
                                <p class="text-[10px] text-slate-400 dark:text-white/30 italic text-center py-6 transition-colors border border-dashed border-slate-200 dark:border-white/10 rounded-xl">Belum ada kuis yang lulus.</p>
                                @endforelse
                            </div>

                            <div class="pt-3 border-t border-slate-200 dark:border-white/5 mt-auto transition-colors flex items-center justify-between">
                                <button class="text-[10px] font-bold text-cyan-600 dark:text-cyan-400 hover:text-cyan-800 dark:hover:text-white transition flex items-center gap-1 w-max">
                                    Lihat Daftar Kelulusan &rarr;
                                </button>
                                <div class="flex items-center gap-1.5" title="Pass Rate">
                                    <div class="w-16 h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden">
                                        <div class="h-full bg-cyan-500" style="width: {{ $passRate }}%"></div>
                                    </div>
                                    <span class="text-[9px] font-bold text-cyan-600 dark:text-cyan-400">{{ $passRate }}%</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Card 2: Remedial Warning --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between overflow-visible cursor-pointer" @click="showRemedialModal = true">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-[0.05] dark:opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                            </div>
                        </div>

                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between transition-colors">
                                Peringatan Remedial
                                <div class="tooltip-container tooltip-red tooltip-down tooltip-left" @click.stop>
                                    <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-red-500 dark:text-red-400 block mb-1">Perhitungan Murni:</span>
                                        Siswa dihitung butuh remedial JIKA nilainya < 70 DAN belum pernah mendapatkan nilai di atas KKM pada evaluasi tersebut.
                                    </div>
                                </div>
                            </h3>

                            <div class="space-y-3 flex-1 mb-4">
                                @forelse($trueRemedialList->take(3) as $act)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-500/20 flex items-center justify-center text-xs font-bold text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30 transition-colors">!</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate transition-colors">{{ $act->name }}</p>
                                        <p class="text-[9px] text-red-500 dark:text-red-400 mt-0.5 transition-colors">Skor: {{ $act->score }} (Kurang: <span class="font-bold">{{ 70 - $act->score }}</span>)</p>
                                    </div>
                                </div>
                                @empty
                                <div class="flex flex-col items-center justify-center py-4 text-emerald-600 dark:text-emerald-400 transition-colors border border-dashed border-emerald-200 dark:border-emerald-500/20 rounded-xl bg-emerald-50 dark:bg-emerald-500/5">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Semua Aman!</span>
                                    <svg class="w-5 h-5 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                @endforelse
                            </div>

                            <div class="pt-3 border-t border-slate-200 dark:border-white/5 mt-auto transition-colors flex items-center justify-between">
                                <button class="text-[10px] font-bold text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-white transition flex items-center gap-1 w-max">
                                    Cek Daftar Tindakan &rarr;
                                </button>
                                @if($remedialRate > 0)
                                    <span class="text-[9px] bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 font-bold px-2 py-0.5 rounded shadow-sm">Tingkat Kritis: {{ $remedialRate }}%</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Lab Completion --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between overflow-visible cursor-pointer" @click="showLabModal = true">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-[0.05] dark:opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                        </div>
                        
                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between transition-colors">
                                Penyelesaian Lab
                                <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left" @click.stop>
                                    <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400 block mb-1">Sumber Data:</span>
                                        Total sesi praktikum (Lab History) yang tervalidasi dengan status 'passed' (Lulus) dari semua siswa.
                                    </div>
                                </div>
                            </h3>
                            
                            <div class="flex items-center justify-between mt-2 flex-1">
                                <div>
                                    <span class="text-4xl font-black text-slate-900 dark:text-white drop-shadow-sm dark:drop-shadow-md transition-colors">{{ $realLabCount ?? 0 }}</span>
                                    <span class="text-sm text-slate-500 dark:text-white/40 block mt-1 transition-colors">Total Lulus</span>
                                </div>
                                <div class="text-right">
                                    <button class="px-3 py-1.5 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-600/20 text-fuchsia-600 dark:text-fuchsia-400 text-[10px] font-bold border border-fuchsia-200 dark:border-fuchsia-600/30 hover:bg-fuchsia-600 hover:text-white transition-colors shadow-sm dark:shadow-none">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-3 border-t border-slate-200 dark:border-white/5 transition-colors flex items-center justify-between">
                                <div class="flex-1 mr-3 bg-slate-200 dark:bg-white/10 h-1.5 rounded-full overflow-hidden shadow-inner border border-slate-300 dark:border-white/5 transition-colors" title="Rata-rata Skor: {{ round($avgLabScore, 1) }}">
                                    <div class="h-full bg-gradient-to-r from-fuchsia-400 to-fuchsia-600 dark:from-fuchsia-600 dark:to-fuchsia-400 transition-all duration-1000" style="width: {{ min(100, $avgLabScore) }}%"></div>
                                </div>
                                <p class="text-[9px] text-slate-500 dark:text-white/40 font-mono transition-colors">Skor Avg: <strong class="text-fuchsia-600 dark:text-fuchsia-400">{{ round($avgLabScore, 1) }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     2. STATS GRID (4 KARTU TENGAH)
                     ======================================================= --}}
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 reveal" style="animation-delay: 0.2s;">
                    
                    {{-- Total Students --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all overflow-visible" @click="showStudentModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Total Siswa</p>
                            <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">?</div>
                                <div class="tooltip-content">Menghitung seluruh pengguna aktif yang memiliki role 'student' (Bukan admin atau tutor). Terdapat <b>{{ count($availableClasses ?? []) }} Kelas Aktif</b> saat ini.</div>
                            </div>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ number_format($totalStudents ?? 0) }}</h3>
                        <p class="text-[9px] text-indigo-600 dark:text-indigo-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Daftar &rarr;</p>
                    </div>

                    {{-- Passed Quizzes --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all overflow-visible" @click="showQuizModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Kuis Lulus</p>
                            <div class="tooltip-container tooltip-cyan tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-cyan-600 dark:group-hover:text-cyan-400">?</div>
                                <div class="tooltip-content">Kalkulasi total percobaan kuis yang diselesaikan dengan skor akhirnya memenuhi KKM (Nilai ≥ 70).</div>
                            </div>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ number_format($totalPassedQuizzesCount ?? 0) }}</h3>
                        <p class="text-[9px] text-cyan-600 dark:text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Riwayat &rarr;</p>
                    </div>

                    {{-- Global Avg --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all overflow-visible" @click="showAvgModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Rata-rata Kuis</p>
                            <div class="tooltip-container tooltip-emerald tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">?</div>
                                <div class="tooltip-content">
                                    Total akumulasi nilai seluruh kuis dibagi dengan Total Seluruh Percobaan Kuis ({{ $totalAttempts }} Percobaan).
                                    <hr class="my-2 border-slate-200/20">
                                    <span class="text-emerald-400">Skor Tertinggi Global: {{ $highestGlobalScore }}</span><br>
                                    <span class="text-red-400">Skor Terendah Global: {{ $lowestGlobalScore }}</span><br>
                                    <span class="text-slate-400">Avg Durasi: {{ gmdate("i:s", $avgQuizDuration) }} Menit</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $globalAverage ?? 0 }}</h3>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-500 font-bold transition-colors"></span>
                        </div>
                        <p class="text-[9px] text-emerald-600 dark:text-emerald-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Detail Bab &rarr;</p>
                    </div>

                    {{-- Pass Rate --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-violet-500 cursor-pointer group transition-all overflow-visible" @click="showPassedModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Tingkat Kelulusan</p>
                            <div class="tooltip-container tooltip-violet tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-violet-600 dark:group-hover:text-violet-400">?</div>
                                <div class="tooltip-content">
                                    <span class="font-bold text-violet-400">Rumus Persentase:</span><br>
                                    (Total Kuis Lulus: {{ $totalPassedQuizzesCount }}) ÷ (Total Percobaan: {{ $totalAttempts }}) × 100%
                                </div>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $passRate ?? 0 }}</h3>
                            <span class="text-lg text-violet-600 dark:text-violet-400 font-bold transition-colors">%</span>
                        </div>
                        <p class="text-[9px] text-violet-600 dark:text-violet-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Distribusi &rarr;</p>
                    </div>
                </div>

                {{-- =======================================================
                     3. CHART & AUDIT LOG (MENGGANTIKAN LEADERBOARD)
                     ======================================================= --}}
                <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.3s;">
                    
                    {{-- Chart --}}
                    <div class="lg:col-span-2 glass-card rounded-2xl p-6 flex flex-col relative z-20">
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                Tren Performa (7 Hari)
                                <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                    <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                    <div class="tooltip-content">Melacak pergerakan rata-rata skor evaluasi secara harian untuk memantau performa pembelajaran siswa 7 hari terakhir.</div>
                                </div>
                            </h3>
                            <div class="flex p-1 bg-slate-100 dark:bg-[#0a0e17] rounded-lg border border-slate-200 dark:border-white/5 shadow-inner transition-colors" x-data="{ currentType: 'line' }">
                                <button @click="currentType = 'line'; updateChartType('line')" :class="currentType === 'line' ? 'bg-indigo-600 dark:bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-3 py-1.5 text-[9px] font-bold rounded transition-colors">Line</button>
                                <button @click="currentType = 'bar'; updateChartType('bar')" :class="currentType === 'bar' ? 'bg-indigo-600 dark:bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-3 py-1.5 text-[9px] font-bold rounded transition-colors">Bar</button>
                            </div>
                        </div>
                        <div class="flex-1 w-full h-[300px] relative z-10">
                            <canvas id="quizChart"></canvas>
                        </div>
                    </div>

                    {{-- SYSTEM AUDIT LOG --}}
                    <div class="glass-card rounded-2xl p-6 flex flex-col z-20 border-t-2 border-t-blue-500 dark:border-t-blue-500/50">
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-1 flex items-center justify-between transition-colors">
                            <span class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-blue-600 dark:text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                Log Audit Sistem
                            </span>
                            <div class="tooltip-container tooltip-blue tooltip-down tooltip-left">
                                <div class="tooltip-trigger text-slate-500 dark:text-[#020617]">?</div>
                                <div class="tooltip-content">
                                    <span class="font-bold text-blue-600 dark:text-blue-400 block mb-1">Aktivitas Administrator:</span>
                                    Merekam seluruh aksi perubahan yang dilakukan oleh Admin di dalam sistem platform ini demi transparansi data. Klik item untuk melihat perbandingan perubahannya (JSON).
                                </div>
                            </div>
                        </h3>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 mb-4 pb-2 border-b border-slate-200 dark:border-white/5 transition-colors">Mengelola <b class="text-slate-700 dark:text-slate-300"> {{ $totalAdmins ?? 0 }}  Admin</b> aktif.</p>
                        
                        {{-- KUNCI SCROLLABLE: Menggunakan tinggi spesifik (h-[350px] atau h-[400px]) agar scrollbar selalu terkunci di dalam kotak --}}
                        <div class="h-[350px] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                            @forelse($auditLogs ?? [] as $log)
                            <div x-data="{ expanded: false }" class="p-3 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/50 border border-slate-200 dark:border-white/5 hover:border-blue-300 dark:hover:border-blue-500/30 transition-colors group cursor-pointer" @click="expanded = !expanded">
                                <div class="flex items-start gap-3">
                                    <div class="w-8 h-8 rounded-full bg-blue-100 dark:bg-blue-500/20 flex items-center justify-center font-bold text-blue-700 dark:text-blue-400 text-xs shrink-0 transition-colors shadow-inner">
                                        {{ substr($log->admin_name, 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center justify-between mb-0.5">
                                            <p class="text-[11px] font-bold text-slate-900 dark:text-white truncate group-hover:text-blue-600 dark:group-hover:text-blue-400 transition-colors">{{ $log->admin_name }}</p>
                                            <span class="px-1.5 py-0.5 bg-slate-200 dark:bg-[#0f141e] text-slate-500 dark:text-white/60 rounded text-[8px] font-mono border border-slate-300 dark:border-white/5 transition-colors shrink-0">#{{ $log->id }}</span>
                                        </div>
                                        <p class="text-[10px] text-slate-600 dark:text-slate-400 leading-snug transition-colors">
                                            <span class="font-bold text-blue-600 dark:text-blue-400">{{ $log->action_label }}</span>
                                            pada {{ $log->target_type }} <span class="font-mono text-[9px] bg-slate-200 dark:bg-white/10 px-1 rounded text-slate-700 dark:text-white/80">ID:{{ $log->target_id }}</span>
                                        </p>
                                        <div class="flex justify-between items-center mt-1.5">
                                            <p class="text-[8px] text-slate-400 dark:text-white/30 font-mono transition-colors">
                                                ⏱ {{ \Carbon\Carbon::parse($log->created_at)->diffForHumans() }}
                                            </p>
                                            <span class="text-[8px] text-blue-500 flex items-center gap-0.5" x-text="expanded ? 'Tutup Detail' : 'Lihat Detail'"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ekstraksi JSON (Hanya muncul saat di-klik) --}}
                                <div x-show="expanded" style="display: none;" x-transition class="mt-3 pt-3 border-t border-slate-200 dark:border-white/5">
                                    <div class="grid grid-cols-1 gap-2 text-[9px] font-mono">
                                        @if($log->before != 'null' && $log->before != null)
                                        <div class="bg-red-50 dark:bg-red-900/10 p-2 rounded-lg border border-red-100 dark:border-red-900/30 overflow-x-auto custom-scrollbar">
                                            <p class="font-bold text-red-600 dark:text-red-400 mb-1">Data Lama (Before):</p>
                                            <pre class="text-slate-600 dark:text-slate-400">{{ $log->before_formatted }}</pre>
                                        </div>
                                        @endif
                                        
                                        @if($log->after != 'null' && $log->after != null)
                                        <div class="bg-emerald-50 dark:bg-emerald-900/10 p-2 rounded-lg border border-emerald-100 dark:border-emerald-900/30 overflow-x-auto custom-scrollbar">
                                            <p class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">Data Baru (After):</p>
                                            <pre class="text-slate-600 dark:text-slate-400">{{ $log->after_formatted }}</pre>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @empty
                            <div class="text-center py-10 flex flex-col items-center justify-center border border-dashed border-slate-300 dark:border-white/10 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/50 transition-colors">
                                <svg class="w-6 h-6 text-slate-300 dark:text-white/20 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                <span class="text-slate-500 dark:text-white/30 text-[10px] italic">Belum ada riwayat log audit.</span>
                            </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     4. QUESTION ANALYSIS & RECENT ACTIVITIES
                     ======================================================= --}}
                <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.4s;">
                    
                    {{-- QUESTION ANALYSIS --}}
                    <div class="lg:col-span-2 glass-card rounded-2xl p-6 relative z-10 flex flex-col h-full">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Analisis Soal 
                                    <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                        <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                        <div class="tooltip-content">
                                            <span class="font-bold text-indigo-400 block">Indikator Kesulitan:</span>
                                            Dihitung berdasarkan rasio (Jumlah Jawaban Benar) ÷ (Total Seluruh Jawaban Masuk) × 100.
                                        </div>
                                    </div>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Metrik untuk meninjau tingkat kesulitan kurikulum berdasarkan interaksi siswa.</p>
                            </div>
                            
                            <a href="{{ route('admin.analytics.questions') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition flex items-center gap-1.5 border border-indigo-500 dark:border-indigo-400 shrink-0">
                                Analisis Lengkap <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                        
                        <div class="overflow-x-auto custom-scrollbar border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/50 transition-colors duration-500 flex-1">
                            <table class="w-full text-sm text-left whitespace-nowrap md:whitespace-normal h-full">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 transition-colors sticky top-0">
                                    <tr>
                                        <th class="px-5 py-4 w-[50%]">Kutipan Teks Soal</th>
                                        <th class="px-5 py-4 text-center">Jawaban Terkumpul</th>
                                        <th class="px-5 py-4 text-center">Rasio Akurasi</th>
                                        <th class="px-5 py-4 text-right">Tingkat Kesulitan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 transition-colors">
                                    @forelse($questionStats ?? [] as $q)
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group table-row">
                                        <td class="px-5 py-4 text-[11px] text-slate-700 dark:text-white/80 font-medium group-hover:text-slate-900 dark:group-hover:text-white transition-colors" title="{{ $q->question_text }}">
                                            <div class="flex items-center gap-2.5">
                                                <div class="w-1.5 h-1.5 rounded-full shadow-sm dark:shadow-lg {{ $q->accuracy >= 70 ? 'bg-emerald-500 dark:shadow-[0_0_5px_#10b981]' : ($q->accuracy >= 40 ? 'bg-yellow-500 dark:shadow-[0_0_5px_#eab308]' : 'bg-red-500 dark:shadow-[0_0_5px_#ef4444]') }}"></div>
                                                <span class="truncate max-w-[200px] md:max-w-[300px]">{{ \Illuminate\Support\Str::limit($q->question_text, 65) }}</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-center text-slate-500 dark:text-white/50 text-[10px] font-mono transition-colors">{{ $q->total_answers }} Siswa</td>
                                        <td class="px-5 py-4 text-center">
                                            <div class="flex items-center justify-center gap-3">
                                                <div class="flex-1 max-w-[100px] h-1.5 bg-slate-200 dark:bg-[#020617] rounded-full overflow-hidden border border-slate-300 dark:border-white/5 hidden lg:block shadow-inner transition-colors">
                                                    <div class="h-full rounded-full transition-all duration-1000 
                                                        {{ $q->accuracy >= 70 ? 'bg-emerald-500' : ($q->accuracy >= 40 ? 'bg-yellow-500' : 'bg-red-500') }}" 
                                                        style="width: {{ $q->accuracy }}%"></div>
                                                </div>
                                                <span class="font-black text-[11px] w-8 text-right {{ $q->accuracy >= 70 ? 'text-emerald-600 dark:text-emerald-400' : ($q->accuracy >= 40 ? 'text-yellow-600 dark:text-yellow-400' : 'text-red-600 dark:text-red-400') }} transition-colors">{{ $q->accuracy }}%</span>
                                            </div>
                                        </td>
                                        <td class="px-5 py-4 text-right">
                                            <span class="px-3 py-1.5 rounded-lg text-[8px] font-bold border uppercase tracking-wider transition-colors
                                                {{ ($q->difficulty ?? '') == 'Mudah' ? 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-700 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20' : 
                                                  (($q->difficulty ?? '') == 'Sedang' ? 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-700 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/20' : 'bg-red-50 dark:bg-red-500/10 text-red-700 dark:text-red-400 border-red-200 dark:border-red-500/20') }}">
                                                {{ $q->difficulty ?? 'Sulit' }}
                                            </span>
                                            <p class="text-[9px] text-slate-400 dark:text-white/30 mt-1.5 font-mono transition-colors">{{ $q->correct_count ?? 0 }} dari {{ $q->total_answers ?? 0 }} Benar</p>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr><td colspan="4" class="text-center py-10 text-slate-500 dark:text-white/30 text-[10px] italic transition-colors">Belum ada data analitik soal yang tersedia.</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- LIVE LOG AKTIVITAS (WITH FILTERS) --}}
                    <div class="lg:col-span-1 glass-card rounded-2xl p-6 flex flex-col h-full z-20 border-t-2 border-t-fuchsia-500/50 relative overflow-hidden" x-data="{ logFilter: 'all' }">
                        <div class="absolute -right-10 -top-10 w-32 h-32 bg-fuchsia-400/20 dark:bg-fuchsia-500/10 rounded-full blur-[40px] pointer-events-none transition-colors"></div>
                        
                        <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-4 border-b border-slate-200 dark:border-white/5 pb-4 relative z-10 transition-colors gap-3">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <svg class="w-5 h-5 text-fuchsia-600 dark:text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    Log Aktivitas Terbaru
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Menampilkan pengerjaan kuis dan lab real-time.</p>
                            </div>

                            {{-- Alpine Filters --}}
                            <div class="flex gap-1.5 bg-slate-100 dark:bg-[#0a0e17] p-1 rounded-lg border border-slate-200 dark:border-white/5 shadow-inner">
                                <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-fuchsia-500 text-white shadow' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-2 py-1 text-[9px] font-bold rounded transition-all">Semua</button>
                                <button @click="logFilter = 'kuis'" :class="logFilter === 'kuis' ? 'bg-cyan-500 text-white shadow' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-2 py-1 text-[9px] font-bold rounded transition-all">Kuis</button>
                                <button @click="logFilter = 'lab'" :class="logFilter === 'lab' ? 'bg-purple-500 text-white shadow' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-2 py-1 text-[9px] font-bold rounded transition-all">Lab</button>
                            </div>
                        </div>

                        {{-- Tinggi container log diperbesar menjadi max-h-[550px] --}}
                        <div class="flex-1 overflow-y-auto custom-scrollbar space-y-3 pr-2 relative z-10 max-h-[550px]">
                            @forelse($unifiedActivities ?? [] as $act)
                                <div x-show="logFilter === 'all' || logFilter === '{{ $act['type'] }}'" x-transition 
                                     class="p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-fuchsia-300 dark:hover:border-fuchsia-500/30 transition-colors group">
                                    <div class="flex justify-between items-start mb-2 gap-2">
                                        <div class="flex items-center gap-2 min-w-0">
                                            <div class="w-6 h-6 rounded-full bg-slate-200 dark:bg-white/10 text-slate-600 dark:text-white/80 flex items-center justify-center text-[10px] font-bold shrink-0 transition-colors shadow-inner">
                                                {{ substr($act['user_name'] ?? 'U', 0, 1) }}
                                            </div>
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate transition-colors">{{ $act['user_name'] ?? 'Unknown User' }}</p>
                                        </div>
                                        <span class="text-[8px] font-bold px-2 py-0.5 rounded border transition-colors shrink-0 {{ $act['is_passed'] ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20' : 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20' }}">
                                            {{ $act['is_passed'] ? 'Lulus' : 'Gagal' }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex items-start gap-3 mt-1.5">
                                        @if($act['type'] == 'kuis')
                                            <div class="w-8 h-8 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/20 flex items-center justify-center shrink-0 shadow-sm dark:shadow-inner transition-colors" title="Aktivitas Kuis">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                            </div>
                                        @else
                                            <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20 flex items-center justify-center shrink-0 shadow-sm dark:shadow-inner transition-colors" title="Aktivitas Praktikum Lab">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] text-slate-600 dark:text-white/80 font-medium line-clamp-1 leading-snug transition-colors">
                                                {{ $act['title'] }} 
                                                <span class="{{ $act['is_passed'] ? 'text-emerald-500' : 'text-red-500' }} font-bold ml-1">(Skor: {{ $act['score'] }})</span>
                                            </p>
                                            <div class="flex items-center gap-1.5 mt-1.5">
                                                <p class="text-[9px] text-slate-500 dark:text-white/40 font-mono transition-colors">
                                                    {{ \Carbon\Carbon::parse($act['created_at'])->diffForHumans() }} <span class="hidden sm:inline-block text-slate-300 dark:text-white/20 px-1">•</span> <span class="hidden sm:inline-block">{{ \Carbon\Carbon::parse($act['created_at'])->translatedFormat('H:i') }} WIB</span>
                                                </p>
                                                {{-- Menampilkan durasi pengerjaan jika ada --}}
                                                @if(isset($act['duration']) && $act['duration'] > 0)
                                                    <span class="text-[8px] px-1.5 py-0.5 rounded bg-slate-100 dark:bg-white/10 text-slate-500 dark:text-white/60 ml-auto whitespace-nowrap border border-slate-200 dark:border-white/5" title="Durasi Pengerjaan">
                                                        ⏱ {{ gmdate("i:s", $act['duration']) }} m
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="flex flex-col items-center justify-center py-10">
                                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Belum ada aktivitas yang direkam.</p>
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     5. STUDENT DIRECTORY
                     ======================================================= --}}
                <div class="glass-card rounded-2xl relative z-10 reveal flex flex-col mt-6" style="animation-delay: 0.5s;" x-data="{ searchQuery: '' }">
                    <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                        <div>
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                <span class="text-indigo-600 dark:text-indigo-400">👥</span> Direktori Siswa
                                <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                    <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-indigo-600 dark:text-indigo-400 block mb-1">Status Akun:</span>
                                        Menampilkan semua akun yang berstatus "student" secara real-time. Titik hijau menandakan akun terverifikasi.
                                    </div>
                                </div>
                            </h3>
                            <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Mengelola total {{ $totalStudents ?? 0 }} data profil siswa di platform.</p>
                        </div>
                        
                        <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                            <div class="relative w-full sm:w-64 group">
                                <input type="text" x-model="searchQuery" placeholder="Cari berdasarkan nama atau email..." 
                                    class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            </div>
                            
                            <div class="flex gap-2 w-full sm:w-auto relative z-50">
                                <div class="relative flex-1 sm:flex-none" x-data="{ exportOpen: false }">
                                    <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="w-full justify-center flex items-center gap-2 px-4 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white text-[11px] font-bold transition-colors shadow-sm dark:shadow-none">
                                        Ekspor <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                                    </button>
                                    <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-xl shadow-lg dark:shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden transition-colors" style="display: none;" x-transition>
                                        <div class="px-4 py-2 border-b border-slate-100 dark:border-white/5 text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest bg-slate-50 dark:bg-[#0a0e17] transition-colors">Pilih Format</div>
                                        <a href="{{ route('admin.user.export.csv') }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition-colors border-b border-slate-100 dark:border-white/5">
                                            <svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Ekspor CSV
                                        </a>
                                        <a href="{{ route('admin.user.export.pdf') }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition-colors">
                                            <svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Cetak PDF
                                        </a>
                                    </div>
                                </div>
                                <button @click="showImport = true" class="px-3 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-slate-200 dark:border-white/10 text-slate-700 dark:text-white transition-colors text-xs shadow-sm dark:shadow-none" title="Impor Data">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                </button>
                                <button @click="showAdd = true" class="flex-1 sm:flex-none flex items-center justify-center gap-2 px-5 py-2.5 rounded-xl bg-emerald-600 hover:bg-emerald-500 border border-emerald-500 dark:border-emerald-400 text-white text-[11px] font-bold shadow-md dark:shadow-[0_0_15px_rgba(16,185,129,0.3)] transition hover:-translate-y-0.5">
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg> Tambah Baru
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                        <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                            <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                <tr>
                                    <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Profil Siswa (Aktif)</th> 
                                    <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Grup Kelas</th>
                                    <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Waktu Bergabung</th>
                                    <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Aksi Panel</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-slate-200 dark:divide-white/5 transition-colors">
                                @forelse($users ?? [] as $user)
                                @if($user->role == 'student')
                                <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group table-row" 
                                    x-show="searchQuery === '' || '{{ strtolower($user->name) }}'.includes(searchQuery.toLowerCase()) || '{{ strtolower($user->email) }}'.includes(searchQuery.toLowerCase())">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-purple-600 flex items-center justify-center font-bold text-white text-sm shadow-md dark:shadow-inner border border-transparent dark:border-white/10 relative group-hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] transition">
                                                {{ substr($user->name, 0, 2) }}
                                                {{-- GREEN DOT INDICATOR --}}
                                                <span class="absolute -bottom-1 -right-1 w-3.5 h-3.5 bg-emerald-500 border-2 border-white dark:border-[#020617] rounded-full shadow-[0_0_5px_#10b981] transition-colors" title="Akun Aktif"></span>
                                            </div>
                                            <div>
                                                <p class="font-bold text-slate-900 dark:text-white text-xs group-hover:text-indigo-600 dark:group-hover:text-indigo-300 transition-colors">{{ $user->name }}</p>
                                                <p class="text-[9px] text-slate-500 dark:text-white/40 font-mono mt-0.5 tracking-wider transition-colors">{{ $user->email }}</p>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="bg-indigo-50 dark:bg-[#020617] text-indigo-700 dark:text-indigo-300 border border-indigo-200 dark:border-white/10 px-3 py-1.5 rounded-lg text-[10px] font-bold shadow-sm dark:shadow-inner uppercase tracking-wider transition-colors">
                                            {{ $user->class_group ?? 'N/A' }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-col">
                                            <span class="text-slate-600 dark:text-white/80 text-[11px] font-medium transition-colors">{{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d M Y') }}</span>
                                            <span class="text-[9px] text-slate-400 dark:text-white/40 font-mono mt-0.5">{{ \Carbon\Carbon::parse($user->created_at)->diffForHumans() }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 text-right">
                                        <a href="{{ route('admin.student.detail', $user->id) }}" class="inline-flex items-center gap-1.5 px-4 py-2.5 rounded-xl bg-white dark:bg-[#020617] hover:bg-indigo-600 border border-slate-200 dark:border-white/10 hover:border-indigo-500 text-slate-700 hover:text-white dark:text-white text-[10px] font-bold transition-all shadow-sm dark:shadow-inner hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] group/btn relative z-30">
                                            <svg class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400 group-hover/btn:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                            Lihat Insight
                                        </a>
                                    </td>
                                </tr>
                                @endif
                                @empty
                                <tr><td colspan="4" class="text-center py-16 text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl m-4 block border border-dashed border-slate-300 dark:border-white/10 transition-colors">Belum ada data siswa ditemukan di direktori.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== ALL HERO MODALS (WITH INSIGHT HEADERS) ==================== --}}

    {{-- 1. MODAL: DATA SISWA TERDAFTAR (INDIGO HERO) --}}
    <div x-show="showStudentModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showStudentModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-indigo-600 to-blue-500 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-indigo-100 mb-1">Daftar Pengguna Aktif</p>
                    <h3 class="text-3xl font-black mb-1">{{ number_format($totalStudents ?? 0) }} Siswa Aktif</h3>
                    <p class="text-xs text-indigo-100 opacity-90">Total akun dengan wewenang "Student" di seluruh kelas.</p>
                </div>
                <button @click="showStudentModal = false" class="text-indigo-100 hover:text-white transition bg-white/10 hover:bg-red-500/80 rounded-full p-2 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
                @forelse($users->where('role', 'student')->take(10) as $usr)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 shrink-0 transition-colors">{{ substr($usr->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $usr->name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-0.5 transition-colors">{{ $usr->email }}</p>
                    </div>
                    <div class="text-right shrink-0 flex flex-col items-end">
                        <span class="text-[9px] font-bold text-indigo-700 dark:text-indigo-300 bg-indigo-50 dark:bg-[#020617] px-3 py-1.5 rounded-lg border border-indigo-200 dark:border-white/10 uppercase tracking-widest transition-colors">Kelas: {{ $usr->class_group ?? 'N/A' }}</span>
                        <span class="text-[8px] text-slate-400 dark:text-white/30 mt-1">Join: {{ \Carbon\Carbon::parse($usr->created_at)->format('d/m/Y') }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data siswa.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 2. MODAL: DATA UJIAN KUIS (CYAN HERO) --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(6,182,212,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-cyan-100 mb-1">Pengumpulan Kuis Valid</p>
                    <h3 class="text-3xl font-black mb-1">{{ number_format($totalPassedQuizzesCount ?? 0) }} Kuis Lulus</h3>
                    <p class="text-xs text-cyan-100 opacity-90">Evaluasi unik yang telah berhasil memenuhi syarat nilai KKM (Skor ≥ 70).</p>
                </div>
                <button @click="showQuizModal = false" class="text-cyan-100 hover:text-white transition bg-white/10 hover:bg-red-500/80 rounded-full p-2 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($passedQuizzesDetail as $act)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-cyan-300 dark:hover:border-cyan-500/30 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-cyan-50 dark:bg-cyan-500/10 flex items-center justify-center text-sm font-bold text-cyan-600 dark:text-cyan-400 border border-cyan-200 dark:border-cyan-500/30 shrink-0 transition-colors">{{ substr($act->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $act->name }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors">
                            {{ $act->chapter_id == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $act->chapter_id }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-emerald-600 dark:text-emerald-400 transition-colors">Nilai {{ $act->score }}</span>
                        <span class="text-[9px] text-slate-400 dark:text-white/30 hidden sm:inline-block font-mono mt-1 transition-colors">{{ \Carbon\Carbon::parse($act->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Belum ada data kuis lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 3. MODAL: RATA RATA GLOBAL (EMERALD HERO) --}}
    <div x-show="showAvgModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAvgModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(16,185,129,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-emerald-500 to-teal-500 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-emerald-100 mb-1">Rata-Rata Global Seluruh Kuis</p>
                    <h3 class="text-4xl font-black mb-1">{{ $globalAverage ?? 0 }} <span class="text-lg font-bold text-emerald-200">Pts</span></h3>
                    <p class="text-[10px] text-emerald-100 opacity-90 mt-1">
                        Skor gabungan total dari <b>{{ $totalAttempts }}</b> seluruh percobaan kuis dibagi rata.
                    </p>
                </div>
                <button @click="showAvgModal = false" class="text-emerald-100 hover:text-white transition bg-white/10 hover:bg-red-500/80 rounded-full p-2 relative z-10">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($chapterAverages ?? [] as $avg)
                <div class="flex items-center justify-between p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-emerald-300 dark:hover:border-emerald-500/30 transition-colors group">
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">
                            {{ $avg->chapter_id == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $avg->chapter_id }}
                        </p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors">Terkumpul: <span class="text-slate-700 dark:text-white font-bold">{{ $avg->total }} Percobaan</span></p>
                    </div>
                    <div class="text-right flex items-center gap-3">
                        <div class="w-24 h-1.5 bg-slate-200 dark:bg-slate-800 rounded-full overflow-hidden hidden sm:block shadow-inner">
                            <div class="h-full {{ $avg->avg_score >= 70 ? 'bg-emerald-500' : 'bg-red-500' }}" style="width: {{ min(100, $avg->avg_score) }}%"></div>
                        </div>
                        <span class="text-xl font-black w-10 text-right {{ $avg->avg_score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }} transition-colors">{{ $avg->avg_score }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data rata-rata per bab.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 4. MODAL: PASS RATE (VIOLET HERO) --}}
    <div x-show="showPassedModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showPassedModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-violet-200 dark:border-violet-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(139,92,246,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-violet-500 to-indigo-600 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10 w-full pr-8">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-violet-200 mb-1">Tingkat Kesuksesan Evaluasi (Pass Rate)</p>
                    <h3 class="text-4xl font-black mb-3">{{ $passRate ?? 0 }}<span class="text-2xl font-bold text-violet-200">%</span></h3>
                    <div class="w-full bg-violet-800/50 rounded-full h-1.5 mb-2 overflow-hidden border border-white/10">
                        <div class="bg-white h-full" style="width: {{ $passRate ?? 0 }}%"></div>
                    </div>
                    <p class="text-[10px] text-violet-200 opacity-90 mt-2 font-mono">
                        (Total Lulus: <b>{{ $totalPassedQuizzesCount }}</b>) ÷ (Total Percobaan: <b>{{ $totalAttempts }}</b>) × 100%
                    </p>
                </div>
                <button @click="showPassedModal = false" class="text-violet-100 hover:text-white transition bg-white/10 hover:bg-red-500/80 rounded-full p-2 relative z-10 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <h4 class="text-xs font-bold text-slate-500 dark:text-white/40 uppercase tracking-widest mb-3 transition-colors">Distribusi Pengumpulan Kuis Lulus</h4>
            <div class="max-h-[40vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($passedQuizzesDetail as $act)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-violet-300 dark:hover:border-violet-500/30 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-violet-50 dark:bg-violet-500/10 flex items-center justify-center text-sm font-bold text-violet-600 dark:text-violet-400 border border-violet-200 dark:border-violet-500/30 shrink-0 transition-colors">{{ substr($act->name, 0, 2) }}</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $act->name }}</p>
                        <p class="text-[11px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors">
                            {{ $act->chapter_id == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $act->chapter_id }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $act->score }} Pts</span>
                        <span class="text-[9px] text-slate-400 dark:text-white/30 hidden sm:inline-block font-mono mt-1 transition-colors">{{ \Carbon\Carbon::parse($act->created_at)->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Belum ada data siswa lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 5. MODAL: DATA REMEDIAL (RED HERO) --}}
    <div x-show="showRemedialModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showRemedialModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-red-200 dark:border-red-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(239,68,68,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-red-500 to-rose-600 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10 w-full pr-8">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-red-100 mb-1">Status Kritis Kelas</p>
                    <h3 class="text-3xl font-black mb-1">{{ number_format($realRemedialCount ?? 0) }} Peringatan Remedial</h3>
                    <p class="text-xs text-red-100 opacity-90">Siswa di bawah ini mendapatkan skor < 70 dan belum pernah mencapai KKM di evaluasi tersebut.</p>
                    @if($remedialRate > 0)
                        <div class="mt-3 inline-block px-3 py-1 bg-red-900/30 rounded-lg border border-red-100/20">
                            <span class="text-[10px] font-bold text-red-100">Menjangkiti {{ $remedialRate }}% dari total {{ $totalStudents }} Siswa Aktif</span>
                        </div>
                    @endif
                </div>
                <button @click="showRemedialModal = false" class="text-red-100 hover:text-white transition bg-white/10 hover:bg-red-500/80 rounded-full p-2 relative z-10 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($trueRemedialList as $act)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-red-200 dark:border-red-500/20 hover:border-red-300 dark:hover:border-red-500/40 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-red-50 dark:bg-red-500/20 flex items-center justify-center text-sm font-black text-red-600 dark:text-red-500 border border-red-200 dark:border-red-500/30 shrink-0 transition-colors">!</div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $act->name }}</p>
                        <p class="text-[10px] text-red-500 dark:text-red-400/80 mt-0.5 transition-colors">
                            {{ $act->chapter_id == 99 ? 'Evaluasi Akhir' : 'Kuis Bab ' . $act->chapter_id }}
                        </p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-red-600 dark:text-red-500 transition-colors">{{ $act->score }} Pts</span>
                        <span class="text-[9px] text-red-700 dark:text-red-300 font-bold bg-red-100 dark:bg-red-500/10 px-2 py-0.5 rounded mt-1 hidden sm:inline-block transition-colors">Kurang {{ 70 - $act->score }} Poin</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-emerald-600 dark:text-emerald-400 transition-colors">
                    <span class="text-xs font-bold tracking-widest uppercase text-center">Aman Terkendali!<br>Tidak Ada Tindakan Diperlukan.</span>
                    <svg class="w-6 h-6 text-emerald-600 dark:text-emerald-400 mt-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 6. MODAL: DATA LAB COMPLETION (FUCHSIA HERO) - NEW --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(217,70,239,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-fuchsia-500 to-purple-600 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10 w-full pr-8">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-fuchsia-200 mb-1">Riwayat Praktikum Lab</p>
                    <h3 class="text-4xl font-black mb-1">{{ $realLabCount }} <span class="text-xl">Lulus</span></h3>
                    <p class="text-[10px] text-fuchsia-100 opacity-90 mt-1">
                        Sesi Lab yang berhasil divalidasi dengan status 'Passed'. Rata-rata Skor Keseluruhan: <b>{{ round($avgLabScore, 1) }}</b>
                    </p>
                </div>
                <button @click="showLabModal = false" class="text-fuchsia-100 hover:text-white transition bg-white/10 hover:bg-red-500/80 rounded-full p-2 relative z-10 shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
                @forelse($passedLabsDetail as $lab)
                <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-fuchsia-300 dark:hover:border-fuchsia-500/30 transition-colors group">
                    <div class="w-10 h-10 rounded-full bg-fuchsia-50 dark:bg-fuchsia-500/10 flex items-center justify-center text-sm font-bold text-fuchsia-600 dark:text-fuchsia-400 border border-fuchsia-200 dark:border-fuchsia-500/30 shrink-0 transition-colors">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $lab->student_name }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors line-clamp-1" title="{{ $lab->lab_title }}">{{ $lab->lab_title }}</p>
                    </div>
                    <div class="text-right shrink-0">
                        <span class="block text-sm font-black text-emerald-600 dark:text-emerald-400 transition-colors">{{ $lab->final_score }} Pts</span>
                        <span class="text-[9px] text-slate-400 dark:text-white/30 hidden sm:inline-block font-mono mt-1 transition-colors">{{ \Carbon\Carbon::parse($lab->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data penyelesaian praktikum.</p>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ==================== MODALS DATA ENTRY ==================== --}}
    
    {{-- 1. IMPORT CSV --}}
    <div x-show="showImport" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-colors" @click="showImport = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] p-6 transition-colors duration-500" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 transition-colors">Impor Data Siswa</h3>
            <p class="text-[10px] text-slate-500 dark:text-white/50 mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">Header CSV yang Dibutuhkan: <code class="bg-slate-100 dark:bg-[#0a0e17] px-1.5 py-0.5 rounded text-indigo-600 dark:text-indigo-300 font-mono font-bold mt-1 inline-block border border-slate-200 dark:border-white/5 transition-colors">Name, Email, Class, Institution, Password</code></p>
            <form action="{{ route('admin.user.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="relative w-full h-32 border-2 border-dashed border-slate-300 dark:border-white/10 rounded-xl flex flex-col items-center justify-center hover:border-indigo-400 dark:hover:border-indigo-500/50 bg-slate-50 dark:bg-[#0a0e17] group cursor-pointer mb-5 shadow-inner transition-colors">
                    <input type="file" name="file" class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" required onchange="document.getElementById('fileName').innerText = this.files[0].name">
                    <svg class="w-8 h-8 text-slate-400 dark:text-white/30 group-hover:text-indigo-500 dark:group-hover:text-indigo-400 mb-2 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/></svg>
                    <span id="fileName" class="text-[10px] font-bold text-slate-500 dark:text-white/50 group-hover:text-slate-900 dark:group-hover:text-white transition-colors">Klik untuk memilih (.csv)</span>
                </div>
                <div class="flex justify-end gap-3 mt-2">
                    <button type="button" @click="showImport = false" class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white text-xs font-bold transition-colors border border-transparent dark:hover:border-white/10">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white text-xs font-bold transition shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-500 dark:border-indigo-400">Jalankan Impor</button>
                </div>
            </form>
        </div>
    </div>

    {{-- 2. ADD USER --}}
    <div x-show="showAdd" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-colors" @click="showAdd = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] p-6 transition-colors duration-500" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-white/5 pb-3 transition-colors">Daftarkan Siswa Baru</h3>
            <form action="{{ route('admin.user.store') }}" method="POST" class="space-y-4">
                @csrf
                <div><label class="text-[9px] font-bold text-slate-500 dark:text-white/50 uppercase mb-1.5 block tracking-widest transition-colors">Nama Lengkap</label><input type="text" name="name" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-white dark:focus:bg-[#0a0e17] shadow-inner" required></div>
                <div><label class="text-[9px] font-bold text-slate-500 dark:text-white/50 uppercase mb-1.5 block tracking-widest transition-colors">Alamat Email</label><input type="email" name="email" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-white dark:focus:bg-[#0a0e17] shadow-inner" required></div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="text-[10px] font-bold text-slate-500 dark:text-slate-400 uppercase tracking-widest block mb-2 transition-colors">Grup Kelas</label>
                        <div class="relative">
                            <select name="class_group" class="w-full glass-input rounded-xl px-4 py-3 text-sm font-medium focus:ring-2 ring-indigo-500/20 appearance-none cursor-pointer shadow-inner">
                                <option value="" class="bg-white dark:bg-[#0f141e] text-slate-500 dark:text-slate-400">-- Pilih Kelas --</option>
                                
                                {{-- Loop data kelas dari database --}}
                                @foreach($availableClasses ?? [] as $cls)
                                    <option value="{{ $cls->name }}" class="bg-white dark:bg-[#0f141e] text-slate-900 dark:text-white">
                                        {{ $cls->name }} {{ $cls->major ? ' - '.$cls->major : '' }}
                                    </option>
                                @endforeach

                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-500">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-[9px] font-bold text-slate-500 dark:text-white/50 uppercase mb-1.5 block tracking-widest transition-colors">Institusi</label>
                        <input type="text" name="institution" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-white dark:focus:bg-[#0a0e17] shadow-inner">
                    </div>
                </div>
                <div><label class="text-[9px] font-bold text-slate-500 dark:text-white/50 uppercase mb-1.5 block tracking-widest transition-colors">Kata Sandi</label><input type="password" name="password" class="w-full glass-input rounded-xl px-4 py-3 text-sm focus:bg-white dark:focus:bg-[#0a0e17] shadow-inner" required></div>
                <div class="flex justify-end gap-3 pt-5 border-t border-slate-200 dark:border-white/5 mt-5 transition-colors">
                    <button type="button" @click="showAdd = false" class="px-5 py-2.5 rounded-xl bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white text-xs font-bold transition-colors border border-transparent dark:hover:border-white/10">Batal</button>
                    <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-700 dark:hover:bg-indigo-500 text-white text-xs font-bold transition shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.4)] border border-indigo-500 dark:border-indigo-400">Simpan Data</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- SCRIPT THEME TOGGLE --}}
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const themeToggleBtnSidebar = document.getElementById('theme-toggle-sidebar');
        const themeToggleDarkIconSidebar = document.getElementById('theme-toggle-dark-icon-sidebar');
        const themeToggleLightIconSidebar = document.getElementById('theme-toggle-light-icon-sidebar');
        const themeToggleTextSidebar = document.getElementById('theme-toggle-text-sidebar');

        // Fungsi sinkronisasi ikon berdasarkan tema saat ini
        const syncIcons = (isDark) => {
            if (isDark) {
                themeToggleLightIconSidebar?.classList.remove('hidden');
                themeToggleDarkIconSidebar?.classList.add('hidden');
                if(themeToggleTextSidebar) themeToggleTextSidebar.textContent = "Tema Terang";
            } else {
                themeToggleLightIconSidebar?.classList.add('hidden');
                themeToggleDarkIconSidebar?.classList.remove('hidden');
                if(themeToggleTextSidebar) themeToggleTextSidebar.textContent = "Tema Gelap";
            }
        };

        // Inisialisasi awal
        const isDarkTheme = document.documentElement.classList.contains('dark');
        syncIcons(isDarkTheme);

        // Event listener saat tombol diklik
        themeToggleBtnSidebar?.addEventListener('click', function() {
            const willBeDark = !document.documentElement.classList.contains('dark');
            
            if (willBeDark) {
                document.documentElement.classList.add('dark');
                localStorage.setItem('color-theme', 'dark');
            } else {
                document.documentElement.classList.remove('dark');
                localStorage.setItem('color-theme', 'light');
            }
            
            syncIcons(willBeDark);
            window.dispatchEvent(new Event('theme-toggled'));
        });
    });
</script>

{{-- SCRIPT JS UNTUK CHART & SWAL --}}
<script>
    let myChart = null;

    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('quizChart');
        if(ctx) {
            initChart();
        }

        // Listener jika tema diubah, render ulang chart
        window.addEventListener('theme-toggled', () => {
            if(myChart) {
                myChart.destroy();
                initChart();
            }
        });

        function initChart() {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const textColor = isDark ? '#94a3b8' : '#64748b';
            const tooltipBg = isDark ? 'rgba(15, 20, 30, 0.9)' : 'rgba(255, 255, 255, 0.9)';
            const tooltipText = isDark ? '#fff' : '#1e293b';

            // Ambil data dari Controller
            let labels = {!! json_encode($chartLabels ?? []) !!};
            let dataScores = {!! json_encode($chartScores ?? []) !!};

            // Beri Fallback jika belum ada yang mengerjakan kuis dalam 7 hari
            if (labels.length === 0) {
                labels = ['Belum Ada Data'];
                dataScores = [0];
            }

            const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); 
            gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
            
            myChart = new Chart(ctx.getContext('2d'), {
                type: 'line', 
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Skor Rata-rata', 
                        data: dataScores,
                        borderColor: '#818cf8', backgroundColor: gradient, borderWidth: 3,
                        pointBackgroundColor: isDark ? '#0f141e' : '#ffffff', 
                        pointBorderColor: '#818cf8', pointBorderWidth: 2, pointRadius: 5, fill: true, tension: 0.4,
                        borderRadius: 4 
                    }]
                },
                options: { 
                    responsive: true, maintainAspectRatio: false, 
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: tooltipBg,
                            titleColor: tooltipText,
                            bodyColor: tooltipText,
                            borderColor: gridColor,
                            borderWidth: 1,
                            titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            displayColors: false
                        }
                    }, 
                    scales: { 
                        x: { grid: { display: false }, ticks: { color: textColor, font: {size: 10, family: 'JetBrains Mono'} } }, 
                        y: { beginAtZero: true, max: 100, grid: { color: gridColor }, ticks: { color: textColor, font: {size: 10, family: 'JetBrains Mono'} } } 
                    }, interaction: { mode: 'index', intersect: false }
                }
            });
        }
    });

    function updateChartType(type) {
        if(myChart) {
            myChart.config.type = type;
            if(type === 'bar') {
                myChart.data.datasets[0].backgroundColor = '#818cf8';
            } else {
                const gradient = myChart.ctx.createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(99, 102, 241, 0.5)'); gradient.addColorStop(1, 'rgba(99, 102, 241, 0)');
                myChart.data.datasets[0].backgroundColor = gradient;
            }
            myChart.update();
        }
    }

    // SWAL ALERTS THEME RESPONSIVE
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const swalBg = isDark ? '#0f141e' : '#ffffff';
        const swalColor = isDark ? '#fff' : '#1e293b';

        @if(session('success')) Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', background: swalBg, color: swalColor, confirmButtonColor: '#6366f1', customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-[0_10px_50px_rgba(0,0,0,0.8)]' } }); @endif
        @if(session('error')) Swal.fire({ title: 'Error!', text: "{{ session('error') }}", icon: 'error', background: swalBg, color: swalColor, confirmButtonColor: '#ef4444', customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-[0_10px_50px_rgba(0,0,0,0.8)]' } }); @endif
    });
</script>

</body>
</html>