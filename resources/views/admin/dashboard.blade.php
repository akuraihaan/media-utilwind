<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dasbor Admin</title>
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
        [x-cloak] { display: none !important; }

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

        .chart-zoom-card { cursor: pointer; }
        .chart-zoom-card .chart-zoom-button { opacity: 0; transform: translateY(-4px); transition: all .2s ease; }
        .chart-zoom-card:hover .chart-zoom-button { opacity: 1; transform: translateY(0); }
        .chart-hero-backdrop { background: radial-gradient(circle at 20% 10%, rgba(99,102,241,.25), transparent 32%), radial-gradient(circle at 80% 20%, rgba(217,70,239,.18), transparent 32%), rgba(2,6,23,.78); }
        .delete-instant-btn { transition: all .2s ease; }
        .delete-instant-btn:hover { transform: translateY(-1px); box-shadow: 0 10px 24px rgba(239,68,68,.20); }
        .analytics-card-text {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
        .analytics-source-key {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            max-width: 100%;
        }
    </style>
</head>
<body class="flex h-screen w-full bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 transition-colors duration-500" x-data="{ 
    sidebarOpen: false, showImport: false, showAdd: false, 
    showLabModal: false, showQuizModal: false,
    showAvgModal: false, showRemedialModal: false, showPassedModal: false, isFullscreen: false,
    showDashboardInfoModal: false, showLearningInsightModal: false,
    learningInsight: { label: '', value: '', valueCaption: '', primaryCount: '', primaryCaption: '', secondaryCount: '', secondaryCaption: '', insight: '', sourceName: '', sourceKey: '' }
}" @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showLabModal = false; showQuizModal = false; showAvgModal = false; showRemedialModal = false; showPassedModal = false; showDashboardInfoModal = false; showLearningInsightModal = false;" :class="{'modal-open': showQuizModal || showLabModal || showAvgModal || showRemedialModal || showPassedModal || showAdd || showImport || showDashboardInfoModal || showLearningInsightModal}">

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
            $avgQuizDuration = (int) round(DB::table('quiz_attempts')->avg('time_spent_seconds') ?? 0);

        } catch(\Exception $e) {}

        // 2. Remedial pengguna yang belum memenuhi KKM
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

            // Ambil praktik terbaru
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
                    'title' => $l->lab_title ?? 'Sesi Praktik',
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

        // 7. DATA GRAF TAMBAHAN TANPA MENGUBAH FITUR LAMA
        $userRoleChartLabels = [];
        $userRoleChartData = [];
        $studentClassChartLabels = [];
        $studentClassChartData = [];
        $chapterAverageLabels = [];
        $chapterAverageScores = [];
        $chapterAttemptTotals = [];
        $activityTrendLabels = [];
        $activityQuizCounts = [];
        $activityLabCounts = [];

        try {
            $roleDistribution = DB::table('users')
                ->select('role', DB::raw('COUNT(*) as total'))
                ->groupBy('role')
                ->orderByDesc('total')
                ->get();

            $userRoleChartLabels = $roleDistribution->pluck('role')->map(function ($role) {
                return ucfirst($role ?? 'unknown');
            })->toArray();

            $userRoleChartData = $roleDistribution->pluck('total')->map(fn($total) => (int) $total)->toArray();
        } catch (\Exception $e) {}

        try {
            $classDistribution = DB::table('users')
                ->join('class_groups', 'users.class_group', '=', 'class_groups.name')
                ->where('users.role', 'student')
                ->whereNotNull('class_groups.token')
                ->where('class_groups.token', '<>', '')
                ->select('class_groups.name as class_name', DB::raw('COUNT(users.id) as total'))
                ->groupBy('class_groups.name')
                ->orderByDesc('total')
                ->limit(8)
                ->get();

            $studentClassChartLabels = $classDistribution->pluck('class_name')->toArray();
            $studentClassChartData = $classDistribution->pluck('total')->map(fn($total) => (int) $total)->toArray();
        } catch (\Exception $e) {}

        try {
            $chapterAverageLabels = $chapterAverages->map(function ($item) {
                return $item->chapter_id == 99 ? 'Evaluasi Akhir' : 'Bab ' . $item->chapter_id;
            })->toArray();

            $chapterAverageScores = $chapterAverages->pluck('avg_score')->map(fn($score) => (float) $score)->toArray();
            $chapterAttemptTotals = $chapterAverages->pluck('total')->map(fn($total) => (int) $total)->toArray();
        } catch (\Exception $e) {}

        try {
            for ($i = 6; $i >= 0; $i--) {
                $date = now()->subDays($i)->toDateString();

                $activityTrendLabels[] = now()->subDays($i)->translatedFormat('d M');

                $activityQuizCounts[] = (int) DB::table('quiz_attempts')
                    ->whereDate('created_at', $date)
                    ->count();

                $activityLabCounts[] = (int) DB::table('lab_histories')
                    ->whereDate('created_at', $date)
                    ->count();
            }
        } catch (\Exception $e) {
            $activityTrendLabels = ['Sen', 'Sel', 'Rab', 'Kam', 'Jum', 'Sab', 'Min'];
            $activityQuizCounts = [0, 0, 0, 0, 0, 0, 0];
            $activityLabCounts = [0, 0, 0, 0, 0, 0, 0];
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
                    <span class="text-[9px] font-bold text-slate-500 dark:text-white/40 tracking-[0.2em] uppercase transition-colors">Panel Admin</span>
                </div>
            </a>
            <button @click="sidebarOpen = false" class="md:hidden text-slate-500 dark:text-white/50 hover:text-slate-800 dark:hover:text-white relative z-10 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        @include('admin.partials.sidebar-nav')

        {{-- USER PROFILE Bawah Sidebar --}}
        <div class="p-4 border-t border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#05080f]/50 transition-colors">
            <div class="flex items-center gap-3 mb-4 px-2">
                <div class="w-8 h-8 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">AD</div>
                <div class="overflow-hidden">
                    <p class="text-xs font-bold text-slate-900 dark:text-white truncate transition-colors">{{ Auth::user()->name ?? 'Administrator' }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/40 truncate transition-colors">Administrator Sistem</p>
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
                                    <li class="inline-flex items-center"><a href="#" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dasbor </a></li>
                                </ol>
                            </nav>
                            <div class="flex flex-wrap items-center gap-2">
                                <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight transition-colors">Ringkasan Analitik</h2>
                                
                                {{-- TOMBOL TRIGGER HERO MODAL PANDUAN --}}
                                <button @click="showDashboardInfoModal = true" class="w-5 h-5 md:w-6 md:h-6 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-[10px] md:text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none" title="Panduan Dasbor">
                                    ?
                                </button>
                                <a href="{{ route('admin.guide') }}" class="inline-flex items-center gap-1.5 rounded-full border border-indigo-100 dark:border-indigo-500/20 bg-indigo-50/80 dark:bg-indigo-500/10 px-3 py-1 text-[10px] font-black text-indigo-600 dark:text-indigo-300 hover:bg-indigo-100 dark:hover:bg-indigo-500/20 hover:border-indigo-200 dark:hover:border-indigo-400/30 transition-colors shadow-sm">
                                    Panduan Admin
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.6"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                </a>
                            </div>
                            <p class="text-[9px] md:text-xs text-slate-500 dark:text-white/40 flex items-center gap-1.5 mt-0.5 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                Pemantauan Data Langsung
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6">
                    <button onclick="window.location.reload()" class="p-2.5 text-slate-400 hover:text-slate-700 dark:text-white/40 dark:hover:text-white transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-white/5 group hidden sm:block border border-transparent dark:hover:border-white/10" title="Perbarui Data">
                        <svg class="w-4 h-4 group-hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>

                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-400 hover:text-slate-700 dark:text-white/40 dark:hover:text-white transition-colors rounded-full hover:bg-slate-100 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Mode Layar Penuh">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    <div class="border-l border-slate-200 dark:border-white/10 pl-3 md:pl-5 ml-1 hidden lg:block transition-colors">
                        <button @click="showAdd = true" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-500 dark:border-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Tambah pengguna
                        </button>
                    </div>

                    <div class="text-right hidden lg:block border-l border-slate-200 dark:border-white/10 pl-5 ml-1 transition-colors">
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono mt-0.5 transition-colors">{{ \Carbon\Carbon::now()->format('H:i') }} WITA</p>
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

                @php
                    $minimumScore = $minimumScore ?? 70;
                    $overviewTotalLessons = 0;
                    $overviewCompletedLessons = 0;
                    $overviewMaterialUsers = 0;
                    $overviewMaterialRate = 0;
                    $overviewEvaluationAttempts = 0;
                    $overviewEvaluationAverage = 0;
                    $overviewEvaluationUsers = 0;
                    $overviewLabAttempts = 0;
                    $overviewLabUsers = 0;

                    try {
                        if (\Illuminate\Support\Facades\Schema::hasTable('course_lessons')) {
                            $overviewTotalLessons = \App\Models\CourseLesson::count();
                        }

                        if (\Illuminate\Support\Facades\Schema::hasTable('user_lesson_progress')) {
                            $overviewCompletedLessons = DB::table('user_lesson_progress')
                                ->where('completed', true)
                                ->count();
                            $overviewMaterialUsers = DB::table('user_lesson_progress')
                                ->where('completed', true)
                                ->distinct()
                                ->count('user_id');
                        }

                        if (\Illuminate\Support\Facades\Schema::hasTable('quiz_attempts')) {
                            $evaluationQuery = DB::table('quiz_attempts')
                                ->where('chapter_id', 99)
                                ->whereNotNull('score');
                            $overviewEvaluationAttempts = (clone $evaluationQuery)->count();
                            $overviewEvaluationUsers = (clone $evaluationQuery)
                                ->distinct()
                                ->count('user_id');
                            $overviewEvaluationAverage = $overviewEvaluationAttempts > 0
                                ? round((float) ((clone $evaluationQuery)->avg('score') ?? 0), 1)
                                : 0;
                        }

                        if (\Illuminate\Support\Facades\Schema::hasTable('lab_histories')) {
                            $overviewLabAttempts = DB::table('lab_histories')->count();
                            $overviewLabUsers = DB::table('lab_histories')
                                ->distinct()
                                ->count('user_id');
                        }
                    } catch (\Exception $e) {
                        $overviewTotalLessons = $overviewTotalLessons ?? 0;
                    }

                    $overviewMaterialTarget = max(1, ($totalStudents ?? 0) * max(1, $overviewTotalLessons));
                    $overviewMaterialRate = (($totalStudents ?? 0) > 0 && $overviewTotalLessons > 0)
                        ? min(100, round(($overviewCompletedLessons / $overviewMaterialTarget) * 100))
                        : 0;
                    $overviewQuizRate = min(100, max(0, (float) ($passRate ?? 0)));
                    $overviewLabRate = $overviewLabAttempts > 0
                        ? min(100, round((($realLabCount ?? 0) / $overviewLabAttempts) * 100))
                        : 0;
                    $overviewEvaluationRate = min(100, max(0, (float) $overviewEvaluationAverage));
                    $overviewActivityDays = collect($activityTrendLabels ?? [])->values()->map(function ($label, $index) use ($activityQuizCounts, $activityLabCounts) {
                        $quiz = (int) ($activityQuizCounts[$index] ?? 0);
                        $lab = (int) ($activityLabCounts[$index] ?? 0);

                        return [
                            'label' => $label,
                            'quiz' => $quiz,
                            'lab' => $lab,
                            'total' => $quiz + $lab,
                        ];
                    });
                    $overviewActivityPeak = max(1, (int) ($overviewActivityDays->max('total') ?? 0));
                    $overviewQuizActivityTotal = array_sum($activityQuizCounts ?? []);
                    $overviewLabActivityTotal = array_sum($activityLabCounts ?? []);
                    $overviewActivityTotal = $overviewQuizActivityTotal + $overviewLabActivityTotal;
                    $overviewMetrics = [
                        [
                            'label' => 'Akses materi',
                            'value' => $overviewMaterialRate,
                            'value_display' => number_format($overviewMaterialRate),
                            'unit' => '%',
                            'value_caption' => 'persentase materi selesai',
                            'primary_count' => number_format($overviewCompletedLessons),
                            'primary_label' => 'submateri selesai',
                            'primary_caption' => 'submateri selesai',
                            'secondary_count' => number_format($overviewMaterialUsers),
                            'secondary_label' => 'pengguna selesai',
                            'secondary_caption' => 'pengguna dengan materi selesai',
                            'insight' => $overviewCompletedLessons > 0 ? 'Data materi dihitung dari submateri yang ditandai selesai.' : 'Belum ada submateri selesai.',
                            'source_name' => 'Data akses materi',
                            'source_key' => 'user_lesson_progress.completed = true',
                            'bar' => 'bg-indigo-500',
                            'border' => 'border-l-indigo-500',
                        ],
                        [
                            'label' => 'Kuis',
                            'value' => $overviewQuizRate,
                            'value_display' => number_format($overviewQuizRate),
                            'unit' => '%',
                            'value_caption' => 'persentase kuis lulus',
                            'primary_count' => number_format($totalPassedQuizzesCount ?? 0) . 'x',
                            'primary_label' => 'percobaan lulus',
                            'primary_caption' => 'percobaan kuis lulus',
                            'secondary_count' => number_format($totalAttempts ?? 0) . 'x',
                            'secondary_label' => 'percobaan kuis',
                            'secondary_caption' => 'seluruh percobaan kuis',
                            'insight' => ($totalAttempts ?? 0) > 0 ? 'Data kuis dihitung dari percobaan dengan nilai minimal KKM.' : 'Belum ada percobaan kuis.',
                            'source_name' => 'Data kuis',
                            'source_key' => 'quiz_attempts.score >= ' . $minimumScore,
                            'bar' => 'bg-cyan-500',
                            'border' => 'border-l-cyan-500',
                        ],
                        [
                            'label' => 'Praktik',
                            'value' => $overviewLabRate,
                            'value_display' => number_format($overviewLabRate),
                            'unit' => '%',
                            'value_caption' => 'persentase praktik lulus',
                            'primary_count' => number_format($realLabCount ?? 0) . 'x',
                            'primary_label' => 'riwayat lulus',
                            'primary_caption' => 'praktik lulus',
                            'secondary_count' => number_format($overviewLabAttempts) . 'x',
                            'secondary_label' => 'riwayat praktik',
                            'secondary_caption' => 'seluruh riwayat praktik',
                            'insight' => $overviewLabAttempts > 0 ? 'Data praktik dihitung dari riwayat praktik berstatus lulus.' : 'Belum ada riwayat praktik.',
                            'source_name' => 'Data praktik',
                            'source_key' => 'lab_histories.status = "passed"',
                            'bar' => 'bg-fuchsia-500',
                            'border' => 'border-l-fuchsia-500',
                        ],
                        [
                            'label' => 'Evaluasi Akhir',
                            'value' => $overviewEvaluationRate,
                            'value_display' => rtrim(rtrim(number_format($overviewEvaluationRate, 1, '.', ''), '0'), '.') ?: '0',
                            'unit' => '',
                            'value_caption' => 'rata-rata nilai evaluasi',
                            'primary_count' => number_format($overviewEvaluationAttempts) . 'x',
                            'primary_label' => 'percobaan evaluasi',
                            'primary_caption' => 'percobaan evaluasi akhir',
                            'secondary_count' => number_format($overviewEvaluationUsers),
                            'secondary_label' => 'pengguna mengerjakan',
                            'secondary_caption' => 'pengguna mengerjakan evaluasi',
                            'insight' => $overviewEvaluationAttempts > 0 ? 'Data evaluasi dihitung dari percobaan evaluasi akhir.' : 'Belum ada evaluasi akhir.',
                            'source_name' => 'Data evaluasi akhir',
                            'source_key' => 'quiz_attempts.chapter_id = 99',
                            'bar' => 'bg-amber-500',
                            'border' => 'border-l-amber-500',
                        ],
                    ];

                    $teacherPassTarget = 70;
                    $teacherAverageTarget = 70;
                    $teacherActivityTarget = 1;
                    $lowestChapter = collect($chapterAverages ?? [])->sortBy('avg_score')->first();
                    $lowestChapterLabel = $lowestChapter
                        ? (($lowestChapter->chapter_id ?? null) == 99 ? 'Evaluasi Akhir' : 'Bab ' . ($lowestChapter->chapter_id ?? '-'))
                        : null;
                    $lowestChapterScore = $lowestChapter ? round((float) ($lowestChapter->avg_score ?? 0), 1) : null;

                    if (($totalAttempts ?? 0) === 0 && $overviewLabAttempts === 0 && $overviewCompletedLessons === 0) {
                        $teacherConditionTitle = 'Data kelas belum terbentuk';
                        $teacherConditionBody = 'Belum ada aktivitas tercatat.';
                        $teacherConditionTone = 'slate';
                    } elseif (($realRemedialCount ?? 0) > 0 || ($globalAverage ?? 0) < $teacherAverageTarget) {
                        $teacherConditionTitle = 'Ada area yang perlu intervensi';
                        $teacherConditionBody = 'Nilai atau ketuntasan belum stabil.';
                        $teacherConditionTone = 'amber';
                    } elseif (($passRate ?? 0) >= $teacherPassTarget && ($globalAverage ?? 0) >= $teacherAverageTarget) {
                        $teacherConditionTitle = 'Kelas relatif stabil';
                        $teacherConditionBody = 'Ketuntasan dan nilai memenuhi target.';
                        $teacherConditionTone = 'emerald';
                    } else {
                        $teacherConditionTitle = 'Pembelajaran sedang berjalan';
                        $teacherConditionBody = 'Aktivitas kelas sudah tercatat.';
                        $teacherConditionTone = 'cyan';
                    }

                    $teacherToneClasses = [
                        'slate' => [
                            'text' => 'text-slate-600 dark:text-slate-300',
                            'soft' => 'bg-slate-100 text-slate-600 border-slate-200 dark:bg-white/5 dark:text-slate-300 dark:border-white/10',
                            'bar' => 'bg-slate-400',
                            'dot' => 'bg-slate-400',
                        ],
                        'cyan' => [
                            'text' => 'text-cyan-600 dark:text-cyan-300',
                            'soft' => 'bg-cyan-50 text-cyan-700 border-cyan-100 dark:bg-cyan-500/10 dark:text-cyan-300 dark:border-cyan-500/20',
                            'bar' => 'bg-cyan-500',
                            'dot' => 'bg-cyan-500 shadow-[0_0_8px_#06b6d4]',
                        ],
                        'emerald' => [
                            'text' => 'text-emerald-600 dark:text-emerald-300',
                            'soft' => 'bg-emerald-50 text-emerald-700 border-emerald-100 dark:bg-emerald-500/10 dark:text-emerald-300 dark:border-emerald-500/20',
                            'bar' => 'bg-emerald-500',
                            'dot' => 'bg-emerald-500 shadow-[0_0_8px_#10b981]',
                        ],
                        'amber' => [
                            'text' => 'text-amber-600 dark:text-amber-300',
                            'soft' => 'bg-amber-50 text-amber-700 border-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:border-amber-500/20',
                            'bar' => 'bg-amber-500',
                            'dot' => 'bg-amber-500 shadow-[0_0_8px_#f59e0b]',
                        ],
                        'indigo' => [
                            'text' => 'text-indigo-600 dark:text-indigo-300',
                            'soft' => 'bg-indigo-50 text-indigo-700 border-indigo-100 dark:bg-indigo-500/10 dark:text-indigo-300 dark:border-indigo-500/20',
                            'bar' => 'bg-indigo-500',
                            'dot' => 'bg-indigo-500 shadow-[0_0_8px_#6366f1]',
                        ],
                        'fuchsia' => [
                            'text' => 'text-fuchsia-600 dark:text-fuchsia-300',
                            'soft' => 'bg-fuchsia-50 text-fuchsia-700 border-fuchsia-100 dark:bg-fuchsia-500/10 dark:text-fuchsia-300 dark:border-fuchsia-500/20',
                            'bar' => 'bg-fuchsia-500',
                            'dot' => 'bg-fuchsia-500 shadow-[0_0_8px_#d946ef]',
                        ],
                    ];

                    $teacherReferenceCards = [
                        [
                            'label' => 'Ketuntasan kuis',
                            'value' => number_format($passRate ?? 0) . '%',
                            'target' => 'Target ' . $teacherPassTarget . '%',
                            'caption' => number_format($totalPassedQuizzesCount ?? 0) . ' dari ' . number_format($totalAttempts ?? 0) . ' percobaan lulus',
                            'percent' => $passRate ?? 0,
                            'tone' => ($passRate ?? 0) >= $teacherPassTarget ? 'emerald' : (($passRate ?? 0) > 0 ? 'amber' : 'slate'),
                        ],
                        [
                            'label' => 'Rata-rata kelas',
                            'value' => number_format($globalAverage ?? 0, 1),
                            'target' => 'Batas ' . $teacherAverageTarget,
                            'caption' => ($globalAverage ?? 0) >= $teacherAverageTarget ? 'Sudah memenuhi batas ketuntasan' : 'Masih perlu penguatan konsep',
                            'percent' => min(100, max(0, (float) ($globalAverage ?? 0))),
                            'tone' => ($globalAverage ?? 0) >= $teacherAverageTarget ? 'emerald' : 'amber',
                        ],
                        [
                            'label' => 'Aktivitas 7 hari',
                            'value' => number_format($overviewActivityTotal),
                            'target' => 'Minimal aktif',
                            'caption' => number_format($overviewQuizActivityTotal) . ' kuis dan ' . number_format($overviewLabActivityTotal) . ' praktik',
                            'percent' => $overviewActivityTotal > 0 ? 100 : 0,
                            'tone' => $overviewActivityTotal >= $teacherActivityTarget ? 'cyan' : 'slate',
                        ],
                    ];

                    $teacherRecommendations = collect();

                    if (($realRemedialCount ?? 0) > 0) {
                        $teacherRecommendations->push([
                            'title' => 'Prioritaskan pengguna remedial',
                            'body' => number_format($realRemedialCount ?? 0) . ' pengguna perlu ditinjau.',
                            'tone' => 'amber',
                        ]);
                    }

                    if ($lowestChapterScore !== null && $lowestChapterScore < $teacherAverageTarget) {
                        $teacherRecommendations->push([
                            'title' => 'Perkuat ' . $lowestChapterLabel,
                            'body' => 'Rata-rata nilai ' . $lowestChapterScore . '.',
                            'tone' => 'indigo',
                        ]);
                    }

                    if ($overviewMaterialRate < 70 && ($totalStudents ?? 0) > 0) {
                        $teacherRecommendations->push([
                            'title' => 'Dorong penyelesaian materi',
                            'body' => 'Akses materi belum merata.',
                            'tone' => 'fuchsia',
                        ]);
                    }

                    if ($overviewActivityTotal === 0) {
                        $teacherRecommendations->push([
                            'title' => 'Aktifkan aktivitas awal',
                            'body' => 'Belum ada aktivitas 7 hari.',
                            'tone' => 'slate',
                        ]);
                    }

                    if ($teacherRecommendations->isEmpty()) {
                        $teacherRecommendations->push([
                            'title' => 'Pertahankan pemantauan berkala',
                            'body' => 'Pantau nilai dan aktivitas.',
                            'tone' => 'emerald',
                        ]);
                    }

                    $teacherRecommendations = $teacherRecommendations->take(3)->values();
                @endphp

                {{-- =======================================================
                     DASHBOARD PENDIDIK
                     ======================================================= --}}
                @php
                    $teacherConditionToneClasses = $teacherToneClasses[$teacherConditionTone] ?? $teacherToneClasses['slate'];
                @endphp

                <section class="reveal grid gap-5 xl:grid-cols-12" style="animation-delay: 0.08s;">
                    <article class="glass-card rounded-[1.5rem] border border-slate-200/80 bg-white p-5 shadow-sm transition-colors duration-300 dark:border-white/[0.05] dark:bg-[#0f141e] md:p-6 xl:col-span-8">
                        <div class="flex flex-col gap-6 lg:flex-row lg:items-start lg:justify-between">
                            <div class="max-w-2xl">
                                <div class="flex flex-wrap items-center gap-2">
                                    <span class="inline-flex items-center gap-2 rounded-full border px-3 py-1 text-[10px] font-black uppercase tracking-widest {{ $teacherConditionToneClasses['soft'] }}">
                                        <i class="h-2 w-2 rounded-full {{ $teacherConditionToneClasses['dot'] }}"></i>
                                        {{ $teacherConditionTitle }}
                                    </span>
                                    <span class="rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-white/[0.03] dark:text-white/40">
                                        {{ number_format($totalStudents ?? 0) }} pengguna
                                    </span>
                                </div>

                                <h3 class="mt-5 text-2xl font-black leading-tight text-slate-950 dark:text-white md:text-3xl">
                                    Dashboard Pendidik
                                </h3>
                                <p class="mt-2 max-w-xl text-sm font-medium leading-7 text-slate-500 dark:text-white/45">
                                    Pantau ketuntasan materi, praktik, evaluasi, dan aktivitas kelas dari data yang tercatat.
                                </p>
                            </div>

                            <div class="grid min-w-full gap-3 sm:grid-cols-3 lg:min-w-[380px]">
                                <button type="button" @click="showQuizModal = true" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left transition hover:border-cyan-200 hover:bg-cyan-50 dark:border-white/5 dark:bg-white/[0.03] dark:hover:border-cyan-500/30 dark:hover:bg-cyan-500/10">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">Kuis Lulus</p>
                                    <p class="mt-2 text-2xl font-black text-slate-950 dark:text-white">{{ number_format($totalPassedQuizzesCount ?? 0) }}x</p>
                                    <p class="mt-1 text-[11px] font-bold text-cyan-600 dark:text-cyan-300">{{ number_format($passRate ?? 0) }}%</p>
                                </button>

                                <button type="button" @click="showAvgModal = true" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left transition hover:border-emerald-200 hover:bg-emerald-50 dark:border-white/5 dark:bg-white/[0.03] dark:hover:border-emerald-500/30 dark:hover:bg-emerald-500/10">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">Rata-rata</p>
                                    <p class="mt-2 text-2xl font-black text-slate-950 dark:text-white">{{ number_format($globalAverage ?? 0, 1) }}</p>
                                    <p class="mt-1 text-[11px] font-bold text-emerald-600 dark:text-emerald-300">Batas {{ $teacherAverageTarget }}</p>
                                </button>

                                <button type="button" @click="showRemedialModal = true" class="rounded-2xl border border-slate-200 bg-slate-50 p-4 text-left transition hover:border-amber-200 hover:bg-amber-50 dark:border-white/5 dark:bg-white/[0.03] dark:hover:border-amber-500/30 dark:hover:bg-amber-500/10">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">Remedial</p>
                                    <p class="mt-2 text-2xl font-black text-slate-950 dark:text-white">{{ number_format($realRemedialCount ?? 0) }}</p>
                                    <p class="mt-1 text-[11px] font-bold text-amber-600 dark:text-amber-300">{{ number_format($remedialRate ?? 0) }}%</p>
                                </button>
                            </div>
                        </div>

                        <div class="mt-6 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                            @foreach($overviewMetrics as $metric)
                                <button type="button"
                                    @click='showLearningInsightModal = true; learningInsight = {
                                        label: @json($metric['label']),
                                        value: @json($metric['value_display'] . $metric['unit']),
                                        valueCaption: @json($metric['value_caption']),
                                        primaryCount: @json($metric['primary_count']),
                                        primaryCaption: @json($metric['primary_caption']),
                                        secondaryCount: @json($metric['secondary_count']),
                                        secondaryCaption: @json($metric['secondary_caption']),
                                        insight: @json($metric['insight']),
                                        sourceName: @json($metric['source_name']),
                                        sourceKey: @json($metric['source_key'])
                                    }'
                                    class="group w-full rounded-2xl border border-slate-200 bg-white p-4 text-left shadow-sm transition hover:-translate-y-0.5 hover:border-indigo-200 hover:shadow-md focus:outline-none focus:ring-2 focus:ring-indigo-400/30 dark:border-white/[0.06] dark:bg-[#0b1220] dark:hover:border-indigo-400/30">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="truncate text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">{{ $metric['label'] }}</p>
                                            <div class="mt-2 flex items-baseline gap-1">
                                                <span class="text-2xl font-black text-slate-950 dark:text-white">{{ $metric['value_display'] }}</span>
                                                @if($metric['unit'] !== '')
                                                    <span class="text-xs font-black text-slate-400 dark:text-white/35">{{ $metric['unit'] }}</span>
                                                @endif
                                            </div>
                                        </div>
                                        <span class="h-2.5 w-2.5 rounded-full {{ $metric['bar'] }}"></span>
                                    </div>
                                    <div class="mt-4 h-2 overflow-hidden rounded-full bg-slate-100 dark:bg-white/10">
                                        <div class="h-full rounded-full {{ $metric['bar'] }}" style="width: {{ $metric['value'] }}%"></div>
                                    </div>
                                    <div class="mt-4 grid grid-cols-2 gap-2">
                                        <div class="rounded-xl bg-slate-50 px-3 py-2 dark:bg-white/[0.03]">
                                            <p class="truncate text-sm font-black text-slate-950 dark:text-white">{{ $metric['primary_count'] }}</p>
                                            <p class="truncate text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30">{{ $metric['primary_label'] }}</p>
                                        </div>
                                        <div class="rounded-xl bg-slate-50 px-3 py-2 dark:bg-white/[0.03]">
                                            <p class="truncate text-sm font-black text-slate-950 dark:text-white">{{ $metric['secondary_count'] }}</p>
                                            <p class="truncate text-[9px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/30">{{ $metric['secondary_label'] }}</p>
                                        </div>
                                    </div>
                                </button>
                            @endforeach
                        </div>
                    </article>

                    <aside class="glass-card rounded-[1.5rem] border border-slate-200/80 bg-white p-5 shadow-sm transition-colors duration-300 dark:border-white/[0.05] dark:bg-[#0f141e] md:p-6 xl:col-span-4">
                        <div class="flex items-start justify-between gap-3">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-widest text-indigo-600 dark:text-indigo-300">Tindak Lanjut</p>
                                <h3 class="mt-2 text-xl font-black text-slate-950 dark:text-white">Prioritas Kelas</h3>
                            </div>
                            <button @click="showDashboardInfoModal = true" class="grid h-9 w-9 place-items-center rounded-full border border-slate-200 bg-slate-50 text-xs font-black text-slate-500 transition hover:border-indigo-200 hover:text-indigo-600 dark:border-white/10 dark:bg-white/[0.03] dark:text-white/45 dark:hover:border-indigo-500/30 dark:hover:text-indigo-300">?</button>
                        </div>

                        <div class="mt-5 space-y-3">
                            @foreach($teacherRecommendations as $recommendation)
                                @php $recommendationTone = $teacherToneClasses[$recommendation['tone']] ?? $teacherToneClasses['slate']; @endphp
                                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/5 dark:bg-white/[0.03]">
                                    <div class="flex items-start gap-3">
                                        <span class="mt-1 h-2.5 w-2.5 shrink-0 rounded-full {{ $recommendationTone['dot'] }}"></span>
                                        <div class="min-w-0">
                                            <h4 class="text-sm font-black text-slate-950 dark:text-white">{{ $recommendation['title'] }}</h4>
                                            <p class="mt-1 text-xs font-medium leading-6 text-slate-500 dark:text-white/45">{{ $recommendation['body'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="mt-5 rounded-2xl border border-slate-200 bg-slate-50 p-4 dark:border-white/5 dark:bg-[#020617]/70">
                            <div class="mb-3 flex items-center justify-between">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/30">Aktivitas 7 Hari</p>
                                <div class="flex items-center gap-2 text-[10px] font-black text-slate-500 dark:text-white/40">
                                    <span class="inline-flex items-center gap-1"><i class="h-2 w-2 rounded-full bg-cyan-500"></i>{{ number_format($overviewQuizActivityTotal) }}</span>
                                    <span class="inline-flex items-center gap-1"><i class="h-2 w-2 rounded-full bg-fuchsia-500"></i>{{ number_format($overviewLabActivityTotal) }}</span>
                                </div>
                            </div>
                            <div class="flex h-24 items-end gap-2">
                                @forelse($overviewActivityDays as $day)
                                    @php
                                        $barHeight = $day['total'] > 0 ? max(8, round(($day['total'] / $overviewActivityPeak) * 100)) : 4;
                                        $quizRatio = $day['total'] > 0 ? round(($day['quiz'] / max(1, $day['total'])) * 100) : 0;
                                    @endphp
                                    <div class="flex flex-1 flex-col items-center gap-2" title="{{ $day['label'] }}: {{ $day['quiz'] }}x kuis, {{ $day['lab'] }}x praktik">
                                        <div class="flex h-20 w-full max-w-[26px] items-end overflow-hidden rounded-full bg-white shadow-inner dark:bg-white/10">
                                            <div class="flex w-full flex-col justify-end overflow-hidden rounded-full" style="height: {{ $barHeight }}%">
                                                @if($day['total'] > 0)
                                                    <span class="block w-full bg-cyan-500" style="height: {{ $quizRatio }}%"></span>
                                                    <span class="block w-full bg-fuchsia-500" style="height: {{ 100 - $quizRatio }}%"></span>
                                                @else
                                                    <span class="block h-full w-full bg-slate-300 dark:bg-white/15"></span>
                                                @endif
                                            </div>
                                        </div>
                                        <b class="text-[9px] font-black text-slate-400 dark:text-white/30">{{ $day['label'] }}</b>
                                    </div>
                                @empty
                                    <p class="w-full self-center text-center text-xs font-semibold text-slate-500 dark:text-white/40">Belum ada aktivitas.</p>
                                @endforelse
                            </div>
                        </div>
                    </aside>
                </section>

                {{-- =======================================================
                     DETAIL ANALITIK PENDIDIK
                     ======================================================= --}}
                <section class="hidden">
                    @php $teacherConditionToneClasses = $teacherToneClasses[$teacherConditionTone] ?? $teacherToneClasses['slate']; @endphp

                    <div class="mb-5 flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between border-b border-slate-200 dark:border-white/5 pb-4">
                        <div>
                            <p class="text-[10px] uppercase font-black tracking-widest text-slate-400 dark:text-white/35">Analitik Kelas</p>
                            <h3 class="text-[15px] md:text-[16px] font-bold text-slate-900 dark:text-white">Detail Kelas</h3>
                        </div>
                        <span class="w-fit rounded-lg border px-3 py-1.5 text-[10px] font-black uppercase tracking-widest {{ $teacherConditionToneClasses['soft'] }}">
                            {{ $teacherConditionTitle }}
                        </span>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        @foreach($teacherReferenceCards as $referenceCard)
                            @php
                                $referenceTone = $teacherToneClasses[$referenceCard['tone']] ?? $teacherToneClasses['slate'];
                                $referencePercent = min(100, max(0, (int) $referenceCard['percent']));
                            @endphp
                            <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 dark:border-white/5 dark:bg-[#020617]/70">
                                <div class="flex items-start justify-between gap-3 mb-2">
                                    <div>
                                        <p class="text-sm font-black text-slate-900 dark:text-white">{{ $referenceCard['label'] }}</p>
                                    </div>
                                    <span class="text-xs font-black {{ $referenceTone['text'] }}">{{ $referenceCard['value'] }}</span>
                                </div>
                                <div class="h-2 rounded-full bg-slate-100 dark:bg-white/5 overflow-hidden border border-slate-200/60 dark:border-white/5">
                                    <div class="h-full {{ $referenceTone['bar'] }} transition-all duration-1000" style="width: {{ $referencePercent }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </section>

                {{-- =======================================================
                     1. HERO INSIGHT SECTION (3 KARTU ATAS)
                     ======================================================= --}}
                <div class="hidden">
                    
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
                                        Total pengumpulan kuis dengan nilai minimal KKM (Nilai ≥ 70). Diambil berdasarkan kuis unik tiap pengguna.
                                        <br><br>
                                        <span class="text-slate-500 dark:text-slate-400 font-mono text-[9px]">Total percobaan kuis: {{ number_format($totalAttempts ?? 0) }}x</span>
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
                                <div class="flex items-center gap-1.5" title="Rasio Kelulusan">
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
                                        <span class="font-bold text-red-500 dark:text-red-400 block mb-1">Perhitungan:</span>
                                        Pengguna dihitung butuh remedial jika nilainya < 70 dan belum pernah mendapatkan nilai di atas KKM pada evaluasi tersebut.
                                    </div>
                                </div>
                            </h3>

                            <div class="space-y-3 flex-1 mb-4">
                                @forelse($trueRemedialList->take(3) as $act)
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-red-50 dark:bg-red-500/20 flex items-center justify-center text-xs font-bold text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/30 transition-colors">!</div>
                                    <div class="flex-1 min-w-0">
                                        <p class="text-xs font-bold text-slate-800 dark:text-white truncate transition-colors">{{ $act->name }}</p>
                                        <p class="text-[9px] text-red-500 dark:text-red-400 mt-0.5 transition-colors">Nilai: {{ $act->score }} (Kurang: <span class="font-bold">{{ 70 - $act->score }}</span>)</p>
                                    </div>
                                </div>
                                @empty
                                <div class="flex flex-col items-center justify-center py-4 text-emerald-600 dark:text-emerald-400 transition-colors border border-dashed border-emerald-200 dark:border-emerald-500/20 rounded-xl bg-emerald-50 dark:bg-emerald-500/5">
                                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Tidak ada peringatan</span>
                                    <svg class="w-5 h-5 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                </div>
                                @endforelse
                            </div>

                            <div class="pt-3 border-t border-slate-200 dark:border-white/5 mt-auto transition-colors flex items-center justify-between">
                                <button class="text-[10px] font-bold text-red-600 dark:text-red-400 hover:text-red-800 dark:hover:text-white transition flex items-center gap-1 w-max">
                                    Tinjau Daftar Tindakan &rarr;
                                </button>
                                @if($remedialRate > 0)
                                    <span class="text-[9px] bg-red-100 dark:bg-red-500/20 text-red-600 dark:text-red-400 font-bold px-2 py-0.5 rounded shadow-sm">Remedial: {{ $remedialRate }}%</span>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Card 3: Praktik --}}
                    <div class="glass-card rounded-2xl group/card flex flex-col justify-between overflow-visible cursor-pointer" @click="showLabModal = true">
                        <div class="card-bg-gfx">
                            <div class="absolute right-0 top-0 p-4 opacity-[0.05] dark:opacity-10 transition-transform duration-500 group-hover/card:scale-110">
                                <svg class="w-20 h-20 text-fuchsia-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                            </div>
                        </div>
                        
                        <div class="p-6 relative z-20 flex flex-col h-full">
                            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-4 flex items-center justify-between transition-colors">
                                Penyelesaian Praktik
                                <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left" @click.stop>
                                    <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                    <div class="tooltip-content">
                                        <span class="font-bold text-fuchsia-600 dark:text-fuchsia-400 block mb-1">Sumber Data:</span>
                                        Total riwayat praktik dengan status lulus dari semua pengguna.
                                    </div>
                                </div>
                            </h3>
                            
                            <div class="flex items-center justify-between mt-2 flex-1">
                                <div>
                                    <span class="text-4xl font-black text-slate-900 dark:text-white drop-shadow-sm dark:drop-shadow-md transition-colors">{{ number_format($realLabCount ?? 0) }}x</span>
                                    <span class="text-sm text-slate-500 dark:text-white/40 block mt-1 transition-colors">Praktik Lulus</span>
                                </div>
                                <div class="text-right">
                                    <button class="px-3 py-1.5 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-600/20 text-fuchsia-600 dark:text-fuchsia-400 text-[10px] font-bold border border-fuchsia-200 dark:border-fuchsia-600/30 hover:bg-fuchsia-600 hover:text-white transition-colors shadow-sm dark:shadow-none">
                                        Lihat Rincian
                                    </button>
                                </div>
                            </div>
                            
                            <div class="mt-6 pt-3 border-t border-slate-200 dark:border-white/5 transition-colors flex items-center justify-between">
                                <div class="flex-1 mr-3 bg-slate-200 dark:bg-white/10 h-1.5 rounded-full overflow-hidden shadow-inner border border-slate-300 dark:border-white/5 transition-colors" title="Rata-rata Nilai: {{ round($avgLabScore, 1) }}">
                                    <div class="h-full bg-gradient-to-r from-fuchsia-400 to-fuchsia-600 dark:from-fuchsia-600 dark:to-fuchsia-400 transition-all duration-1000" style="width: {{ min(100, $avgLabScore) }}%"></div>
                                </div>
                                <p class="text-[9px] text-slate-500 dark:text-white/40 font-mono transition-colors">Rata-rata Nilai: <strong class="text-fuchsia-600 dark:text-fuchsia-400">{{ round($avgLabScore, 1) }}</strong></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     2. STATS GRID (4 KARTU TENGAH)
                     ======================================================= --}}
                <div class="hidden">
                    
                    {{-- Total Pengguna --}}
                    <a href="{{ route('admin.students.index') }}" class="glass-card rounded-2xl p-5 border-l-4 border-l-indigo-500 cursor-pointer group transition-all overflow-visible block">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Total Pengguna</p>
                            <div class="tooltip-container tooltip-indigo tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-indigo-600 dark:group-hover:text-indigo-400">?</div>
                                <div class="tooltip-content">Menghitung akun pengguna pembelajaran dengan role 'student'. Admin dan tutor tidak dihitung. Terdapat <b>{{ count($availableClasses ?? []) }} kelas aktif</b> saat ini.</div>
                            </div>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ number_format($totalStudents ?? 0) }}</h3>
                        <p class="text-[9px] text-indigo-600 dark:text-indigo-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Daftar &rarr;</p>
                    </a>

                    {{-- Passed Quizzes --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-cyan-500 cursor-pointer group transition-all overflow-visible" @click="showQuizModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors">Kuis Lulus</p>
                            <div class="tooltip-container tooltip-cyan tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-cyan-600 dark:group-hover:text-cyan-400">?</div>
                                <div class="tooltip-content">Kalkulasi total percobaan kuis dengan nilai akhir minimal KKM (Nilai ≥ 70).</div>
                            </div>
                        </div>
                        <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white mt-2 transition-colors">{{ number_format($totalPassedQuizzesCount ?? 0) }}x</h3>
                        <p class="text-[9px] text-cyan-600 dark:text-cyan-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Riwayat &rarr;</p>
                    </div>

                    {{-- Global Avg --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-emerald-500 cursor-pointer group transition-all overflow-visible" @click="showAvgModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-emerald-600 dark:group-hover:text-emerald-400 transition-colors">Rata-rata Kuis</p>
                            <div class="tooltip-container tooltip-emerald tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-emerald-600 dark:group-hover:text-emerald-400">?</div>
                                <div class="tooltip-content">
                                    Total akumulasi nilai seluruh kuis dibagi dengan total percobaan kuis ({{ number_format($totalAttempts ?? 0) }}x).
                                    <hr class="my-2 border-slate-200/20">
                                    <span class="text-emerald-400">Nilai tertinggi: {{ $highestGlobalScore }}</span><br>
                                    <span class="text-red-400">Nilai terendah: {{ $lowestGlobalScore }}</span><br>
                                    <span class="text-slate-400">Rata-rata durasi: {{ gmdate("i:s", $avgQuizDuration) }} menit</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-baseline gap-1 mt-2">
                            <h3 class="text-2xl md:text-3xl font-black text-slate-900 dark:text-white transition-colors">{{ $globalAverage ?? 0 }}</h3>
                            <span class="text-[10px] text-emerald-600 dark:text-emerald-500 font-bold transition-colors"></span>
                        </div>
                        <p class="text-[9px] text-emerald-600 dark:text-emerald-400 mt-2 opacity-0 group-hover:opacity-100 transition translate-y-2 group-hover:translate-y-0 flex items-center gap-1">Lihat Rincian Bab &rarr;</p>
                    </div>

                    {{-- Rasio Kelulusan --}}
                    <div class="glass-card rounded-2xl p-5 border-l-4 border-l-violet-500 cursor-pointer group transition-all overflow-visible" @click="showPassedModal = true">
                        <div class="flex justify-between items-start">
                            <p class="text-[10px] uppercase font-bold text-slate-500 dark:text-white/40 tracking-widest group-hover:text-violet-600 dark:group-hover:text-violet-400 transition-colors">Tingkat Kelulusan</p>
                            <div class="tooltip-container tooltip-violet tooltip-up tooltip-left">
                                <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-slate-400 dark:text-white/30 group-hover:text-violet-600 dark:group-hover:text-violet-400">?</div>
                                <div class="tooltip-content">
                                    <span class="font-bold text-violet-400">Rumus Persentase:</span><br>
                                    (kuis lulus: {{ number_format($totalPassedQuizzesCount ?? 0) }}x) ÷ (percobaan kuis: {{ number_format($totalAttempts ?? 0) }}x) × 100%
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
                                    <div class="tooltip-content">Melacak pergerakan rata-rata nilai evaluasi secara harian untuk memantau performa pembelajaran pengguna 7 hari terakhir.</div>
                                </div>
                            </h3>
                            <div class="flex p-1 bg-slate-100 dark:bg-[#0a0e17] rounded-lg border border-slate-200 dark:border-white/5 shadow-inner transition-colors" x-data="{ currentType: 'line' }">
                                <button @click="currentType = 'line'; updateChartType('line')" :class="currentType === 'line' ? 'bg-indigo-600 dark:bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-3 py-1.5 text-[9px] font-bold rounded transition-colors">Garis</button>
                                <button @click="currentType = 'bar'; updateChartType('bar')" :class="currentType === 'bar' ? 'bg-indigo-600 dark:bg-indigo-500 text-white shadow-md' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-3 py-1.5 text-[9px] font-bold rounded transition-colors">Batang</button>
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
                                    Merekam perubahan yang dilakukan admin untuk menjaga transparansi data. Klik item untuk melihat perbandingan perubahan.
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
                                            <span class="text-[8px] text-blue-500 flex items-center gap-0.5" x-text="expanded ? 'Tutup Rincian' : 'Lihat Rincian'"></span>
                                        </div>
                                    </div>
                                </div>

                                {{-- Ekstraksi JSON (Hanya muncul saat di-klik) --}}
                                <div x-show="expanded" style="display: none;" x-transition class="mt-3 pt-3 border-t border-slate-200 dark:border-white/5">
                                    <div class="grid grid-cols-1 gap-2 text-[9px] font-mono">
                                        @if($log->before != 'null' && $log->before != null)
                                        <div class="bg-red-50 dark:bg-red-900/10 p-2 rounded-lg border border-red-100 dark:border-red-900/30 overflow-x-auto custom-scrollbar">
                                            <p class="font-bold text-red-600 dark:text-red-400 mb-1">Data Lama (Sebelum):</p>
                                            <pre class="text-slate-600 dark:text-slate-400">{{ $log->before_formatted }}</pre>
                                        </div>
                                        @endif
                                        
                                        @if($log->after != 'null' && $log->after != null)
                                        <div class="bg-emerald-50 dark:bg-emerald-900/10 p-2 rounded-lg border border-emerald-100 dark:border-emerald-900/30 overflow-x-auto custom-scrollbar">
                                            <p class="font-bold text-emerald-600 dark:text-emerald-400 mb-1">Data Baru (Sesudah):</p>
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
                     3B. GRAF INSIGHT TAMBAHAN
                     Tidak mengubah fitur lama, hanya menambah visual data.
                     ======================================================= --}}
                <div class="grid grid-cols-1 xl:grid-cols-4 gap-6 reveal" style="animation-delay: 0.35s;">

                    {{-- Distribusi Peran Pengguna --}}
                    <div class="glass-card chart-zoom-card rounded-2xl p-6 flex flex-col min-h-[320px] group/chart" onclick="openHeroChart('role', 'Distribusi Peran Pengguna', 'Komposisi akun berdasarkan peran pengguna di dalam sistem.')">
                        <div class="flex items-start justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Distribusi Peran
                                    <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                        <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                        <div class="tooltip-content">
                                            Perbandingan jumlah akun berdasarkan peran pengguna, seperti pengguna dan admin.
                                        </div>
                                    </div>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1">Komposisi akun sistem.</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[9px] font-black border border-indigo-100 dark:border-indigo-500/20">Akun</span>
                            <button type="button" class="chart-zoom-button px-2.5 py-1 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black shadow-sm" onclick="event.stopPropagation(); this.closest('.chart-zoom-card').click();">Perbesar</button>
                        </div>
                        <div class="flex-1 min-h-[220px] relative">
                            <canvas id="roleDistributionChart"></canvas>
                        </div>
                    </div>

                    {{-- Distribusi Kelas Pengguna --}}
                    <div class="glass-card chart-zoom-card rounded-2xl p-6 flex flex-col min-h-[320px] group/chart" onclick="openHeroChart('class', 'Distribusi Pengguna per Kelas', 'Sebaran jumlah pengguna pada tiap kelas.')">
                        <div class="flex items-start justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Pengguna per Kelas
                                    <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                        <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                        <div class="tooltip-content">
                                            Menampilkan sebaran jumlah pengguna pada setiap kelas. Data diambil dari kolom class_group pada akun pengguna.
                                        </div>
                                    </div>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1">Kelas dengan pengguna terbanyak.</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-cyan-50 dark:bg-cyan-500/10 text-cyan-600 dark:text-cyan-400 text-[9px] font-black border border-cyan-100 dark:border-cyan-500/20">Kelas</span>
                            <button type="button" class="chart-zoom-button px-2.5 py-1 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black shadow-sm" onclick="event.stopPropagation(); this.closest('.chart-zoom-card').click();">Perbesar</button>
                        </div>
                        <div class="flex-1 min-h-[220px] relative">
                            <canvas id="classDistributionChart"></canvas>
                        </div>
                    </div>

                    {{-- Rata-rata Kuis per Bab --}}
                    <div class="glass-card chart-zoom-card rounded-2xl p-6 flex flex-col min-h-[320px] group/chart" onclick="openHeroChart('chapter', 'Rata-rata Kuis per Bab', 'Rata-rata nilai kuis yang dikelompokkan berdasarkan bab.')">
                        <div class="flex items-start justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Rata-rata per Bab
                                    <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                        <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                        <div class="tooltip-content">
                                            Rata-rata nilai kuis dikelompokkan berdasarkan chapter_id.
                                        </div>
                                    </div>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1">Performa kuis tiap bab.</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 text-[9px] font-black border border-emerald-100 dark:border-emerald-500/20">Nilai</span>
                            <button type="button" class="chart-zoom-button px-2.5 py-1 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black shadow-sm" onclick="event.stopPropagation(); this.closest('.chart-zoom-card').click();">Perbesar</button>
                        </div>
                        <div class="flex-1 min-h-[220px] relative">
                            <canvas id="chapterAverageChart"></canvas>
                        </div>
                    </div>

                    {{-- Aktivitas Kuis dan Praktik --}}
                    <div class="glass-card chart-zoom-card rounded-2xl p-6 flex flex-col min-h-[320px] group/chart" onclick="openHeroChart('activity', 'Aktivitas Kuis dan Praktik 7 Hari', 'Perbandingan jumlah pengerjaan kuis dan praktik dalam tujuh hari terakhir.')">
                        <div class="flex items-start justify-between gap-3 mb-5">
                            <div>
                                <h3 class="text-sm font-black text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Aktivitas 7 Hari
                                    <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left">
                                        <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                        <div class="tooltip-content">
                                            Membandingkan jumlah pengerjaan kuis dan praktik dalam tujuh hari terakhir.
                                        </div>
                                    </div>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1">Jumlah kuis dan praktik terbaru.</p>
                            </div>
                            <span class="px-2.5 py-1 rounded-lg bg-fuchsia-50 dark:bg-fuchsia-500/10 text-fuchsia-600 dark:text-fuchsia-400 text-[9px] font-black border border-fuchsia-100 dark:border-fuchsia-500/20">Aktivitas</span>
                            <button type="button" class="chart-zoom-button px-2.5 py-1 rounded-lg bg-slate-900 dark:bg-white text-white dark:text-slate-900 text-[9px] font-black shadow-sm" onclick="event.stopPropagation(); this.closest('.chart-zoom-card').click();">Perbesar</button>
                        </div>
                        <div class="flex-1 min-h-[220px] relative">
                            <canvas id="activityVolumeChart"></canvas>
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     4. QUESTION ANALYSIS & RECENT ACTIVITIES
                     ======================================================= --}}
                <div class="grid lg:grid-cols-3 gap-8 reveal" style="animation-delay: 0.4s;">
                    
                    {{-- QUESTION ANALYSIS --}}
                    <div class="lg:col-span-2 glass-card rounded-2xl p-4 sm:p-6 relative z-10 flex flex-col min-h-[560px]">
                        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-5 gap-4">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Analisis Soal 
                                    <div class="tooltip-container tooltip-indigo tooltip-down tooltip-left">
                                        <div class="tooltip-trigger text-slate-500 dark:text-white">?</div>
                                        <div class="tooltip-content">
                                            <span class="font-bold text-indigo-400 block">Indikator Kesulitan:</span>
                                            Dihitung dari rasio jawaban benar terhadap seluruh jawaban yang terkumpul.
                                        </div>
                                    </div>
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Metrik untuk meninjau tingkat kesulitan soal berdasarkan jawaban pengguna.</p>
                            </div>
                            
                            <a href="{{ route('admin.analytics.questions') }}" class="px-5 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-[11px] font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition flex items-center gap-1.5 border border-indigo-500 dark:border-indigo-400 shrink-0">
                                Lihat analisis lengkap <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                            </a>
                        </div>
                        
                        <div class="max-h-[460px] lg:max-h-[520px] overflow-auto custom-scrollbar border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/50 transition-colors duration-500 flex-1">
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
                                        <td class="px-5 py-4 text-center text-slate-500 dark:text-white/50 text-[10px] font-mono transition-colors">{{ number_format($q->total_answers) }} jawaban</td>
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
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Menampilkan pengerjaan kuis dan praktik terbaru.</p>
                            </div>

                            {{-- Alpine Filters --}}
                            <div class="flex gap-1.5 bg-slate-100 dark:bg-[#0a0e17] p-1 rounded-lg border border-slate-200 dark:border-white/5 shadow-inner">
                                <button @click="logFilter = 'all'" :class="logFilter === 'all' ? 'bg-fuchsia-500 text-white shadow' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-2 py-1 text-[9px] font-bold rounded transition-all">Semua</button>
                                <button @click="logFilter = 'kuis'" :class="logFilter === 'kuis' ? 'bg-cyan-500 text-white shadow' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-2 py-1 text-[9px] font-bold rounded transition-all">Kuis</button>
                                <button @click="logFilter = 'lab'" :class="logFilter === 'lab' ? 'bg-purple-500 text-white shadow' : 'text-slate-500 dark:text-white/50 hover:text-slate-900 dark:hover:text-white'" class="px-2 py-1 text-[9px] font-bold rounded transition-all">Praktik</button>
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
                                            <p class="text-xs font-bold text-slate-800 dark:text-white truncate transition-colors">{{ $act['user_name'] ?? 'Pengguna tidak dikenal' }}</p>
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
                                            <div class="w-8 h-8 rounded-lg bg-purple-50 dark:bg-purple-500/10 text-purple-600 dark:text-purple-400 border border-purple-200 dark:border-purple-500/20 flex items-center justify-center shrink-0 shadow-sm dark:shadow-inner transition-colors" title="Aktivitas Praktik">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                            </div>
                                        @endif

                                        <div class="flex-1 min-w-0">
                                            <p class="text-[11px] text-slate-600 dark:text-white/80 font-medium line-clamp-1 leading-snug transition-colors">
                                                {{ $act['title'] }} 
                                                <span class="{{ $act['is_passed'] ? 'text-emerald-500' : 'text-red-500' }} font-bold ml-1">(Nilai: {{ $act['score'] }})</span>
                                            </p>
                                            <div class="flex items-center gap-1.5 mt-1.5">
                                                <p class="text-[9px] text-slate-500 dark:text-white/40 font-mono transition-colors">
                                                    {{ \Carbon\Carbon::parse($act['created_at'])->diffForHumans() }} <span class="hidden sm:inline-block text-slate-300 dark:text-white/20 px-1">•</span> <span class="hidden sm:inline-block">{{ \Carbon\Carbon::parse($act['created_at'])->translatedFormat('H:i') }} WITA</span>
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

            </div>
        </div>
    </main>

    {{-- ==================== HERO MODAL CHART PREVIEW ==================== --}}
    <div id="chartHeroModal" class="fixed inset-0 z-[999999] hidden items-center justify-center p-4 sm:p-6">
        <div class="absolute inset-0 chart-hero-backdrop backdrop-blur-md cursor-pointer" onclick="closeHeroChart()"></div>
        <div class="relative w-full max-w-6xl max-h-[92vh] overflow-hidden rounded-[2rem] bg-white/95 dark:bg-[#0f141e]/95 border border-slate-200 dark:border-white/10 shadow-2xl dark:shadow-[0_30px_120px_rgba(0,0,0,.75)]">
            <div class="relative overflow-hidden border-b border-slate-200 dark:border-white/10 bg-slate-50/90 dark:bg-[#020617]/70 px-6 md:px-8 py-6">
                <div class="absolute right-0 top-0 w-72 h-72 bg-indigo-500/10 dark:bg-indigo-500/20 blur-[90px] rounded-full pointer-events-none"></div>
                <div class="relative z-10 flex items-start justify-between gap-5">
                    <div>
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-600 dark:text-indigo-400 mb-2">Expanded analytics view</p>
                        <h3 id="chartHeroTitle" class="text-2xl md:text-4xl font-black text-slate-900 dark:text-white leading-tight">Pratinjau Grafik</h3>
                        <p id="chartHeroDescription" class="mt-2 text-xs md:text-sm text-slate-500 dark:text-slate-400 max-w-3xl leading-relaxed">Tampilan grafik diperbesar untuk pembacaan data yang lebih nyaman.</p>
                    </div>
                    <button type="button" onclick="closeHeroChart()" class="shrink-0 p-3 rounded-2xl bg-white dark:bg-white/5 hover:bg-red-50 dark:hover:bg-red-500/10 border border-slate-200 dark:border-white/10 text-slate-500 hover:text-red-600 dark:text-slate-300 dark:hover:text-red-300 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>
            <div class="p-4 md:p-8 bg-white dark:bg-[#0a0e17]">
                <div class="h-[62vh] min-h-[360px] rounded-[1.5rem] border border-slate-200 dark:border-white/10 bg-slate-50 dark:bg-[#020617] p-4 md:p-6 shadow-inner">
                    <canvas id="heroChartCanvas"></canvas>
                </div>
                <div class="mt-4 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 text-[11px] text-slate-500 dark:text-slate-400">
                    <p>Tip: gunakan tampilan besar ini untuk membaca distribusi data ketika label atau nilai pada kartu kecil terasa padat.</p>
                    <button type="button" onclick="closeHeroChart()" class="px-4 py-2 rounded-xl bg-slate-900 dark:bg-white text-white dark:text-slate-900 font-black text-[10px]">Tutup Tampilan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ==================== HERO MODAL INSIGHT LEARNING ANALYTICS ==================== --}}
    <div x-show="showLearningInsightModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-cloak x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/70 dark:bg-[#020617]/90 backdrop-blur-md cursor-pointer transition-opacity" @click="showLearningInsightModal = false"></div>

        <div class="relative w-full max-w-3xl overflow-hidden rounded-[2rem] border border-indigo-200 bg-white shadow-2xl dark:border-indigo-500/30 dark:bg-[#0f141e]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            <div class="relative overflow-hidden bg-gradient-to-r from-indigo-600 via-slate-900 to-cyan-600 px-6 py-6 text-white sm:px-8">
                <div class="absolute -right-12 -top-16 h-40 w-40 rounded-full bg-white/10 blur-3xl"></div>
                <div class="relative z-10 flex items-start justify-between gap-4">
                    <div class="min-w-0">
                        <p class="text-[10px] font-black uppercase tracking-[0.22em] text-indigo-100">Rincian Analitik</p>
                        <h3 class="mt-2 truncate text-2xl font-black sm:text-3xl" x-text="learningInsight.label"></h3>
                        <p class="mt-2 analytics-card-text max-w-xl text-xs font-semibold text-indigo-100/85" x-text="learningInsight.insight"></p>
                    </div>
                    <button @click="showLearningInsightModal = false" class="shrink-0 rounded-full bg-white/10 p-2 text-white/80 transition hover:bg-red-500/80 hover:text-white focus:outline-none focus:ring-2 focus:ring-white/50">
                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                </div>
            </div>

            <div class="space-y-5 p-5 sm:p-7">
                <div class="grid gap-4 md:grid-cols-[.85fr_1.15fr]">
                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Angka utama</p>
                        <div class="mt-2 flex items-end gap-2">
                            <p class="text-5xl font-black leading-none text-slate-900 dark:text-white" x-text="learningInsight.value"></p>
                        </div>
                        <p class="mt-3 analytics-card-text text-xs font-bold uppercase tracking-widest text-slate-500 dark:text-white/45" x-text="learningInsight.valueCaption"></p>
                    </div>

                    <div class="grid grid-cols-2 gap-3">
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/5 dark:bg-white/[0.03]">
                            <p class="analytics-card-text text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35" x-text="learningInsight.primaryCaption"></p>
                            <p class="mt-2 truncate text-3xl font-black text-slate-900 dark:text-white" x-text="learningInsight.primaryCount"></p>
                        </div>
                        <div class="rounded-2xl border border-slate-200 bg-white p-4 dark:border-white/5 dark:bg-white/[0.03]">
                            <p class="analytics-card-text text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35" x-text="learningInsight.secondaryCaption"></p>
                            <p class="mt-2 truncate text-3xl font-black text-slate-900 dark:text-white" x-text="learningInsight.secondaryCount"></p>
                        </div>
                    </div>
                </div>

                <div class="rounded-2xl border border-slate-200 bg-slate-50 p-5 dark:border-white/5 dark:bg-[#020617]/70">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div class="min-w-0">
                            <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Sumber data</p>
                            <h4 class="mt-1 truncate text-lg font-black text-slate-900 dark:text-white" x-text="learningInsight.sourceName"></h4>
                        </div>
                    </div>
                    <p class="mt-4 overflow-x-auto rounded-xl bg-white px-3 py-3 font-mono text-[11px] font-bold text-slate-600 dark:bg-white/5 dark:text-white/55" x-text="learningInsight.sourceKey"></p>
                </div>

                <button @click="showLearningInsightModal = false" class="w-full rounded-xl bg-slate-900 px-4 py-3 text-sm font-black text-white shadow-md transition hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400/40 dark:bg-white dark:text-slate-950 dark:hover:bg-indigo-100">
                    Tutup Rincian
                </button>
            </div>
        </div>
    </div>

    {{-- ==================== HERO MODAL PANDUAN DASBOR ==================== --}}
    <div x-show="showDashboardInfoModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
        
        <div class="relative w-full max-w-xl bg-white/90 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-8 md:p-10 shadow-2xl transition-all text-center" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
            
            <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>

            <!-- Hero Logo Section -->
            <div class="relative w-4 h-4 mx-auto mb-6">
                
            </div>
            
            <h3 class="text-2xl font-black text-slate-900 dark:text-white leading-tight mb-2">Panduan <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-cyan-500 dark:from-indigo-400 dark:to-cyan-400">Dasbor Analitik</span></h3>
            <p class="text-[10px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest mb-6">Ringkasan Data Pembelajaran</p>
            
            <div class="text-sm text-slate-600 dark:text-slate-300 leading-relaxed font-medium text-justify space-y-4">
                <p>Halaman ini menampilkan data pembelajaran, aktivitas pengguna, dan hasil evaluasi.</p>
                
                <div class="space-y-3 mt-4 text-left">
                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">01</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Ringkasan Metrik dan Evaluasi</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Rekapitulasi tingkat kelulusan, tren mingguan, dan tingkat kesulitan butir soal.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">02</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Log Aktivitas & Audit</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Pemantauan progres pengguna secara waktu-nyata dan pencatatan transparansi tindakan yang dilakukan oleh administrator.</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-3 bg-slate-50/50 dark:bg-slate-800/30 rounded-xl border border-slate-100 dark:border-white/5">
                        <span class="text-slate-400 dark:text-slate-500 mt-0.5 font-mono text-xs">03</span>
                        <div>
                            <h4 class="text-xs font-bold text-slate-800 dark:text-slate-200">Direktori Pengguna</h4>
                            <p class="text-[11px] text-slate-500 dark:text-slate-400 mt-1">Kelola data pengguna, impor dan ekspor dokumen, serta pantau capaian tiap individu.</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
                <button @click="showDashboardInfoModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-xl transition-colors shadow-md focus:outline-none">
                    Mengerti, Tutup Panduan
                </button>
            </div>
        </div>
    </div>


    {{-- ==================== ALL HERO MODALS LAINNYA (DATA ENTRY, LIST, DLL) ==================== --}}

    {{-- 2. MODAL: DATA UJIAN KUIS (CYAN HERO) --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(6,182,212,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-cyan-500 to-blue-500 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-cyan-100 mb-1">Riwayat Kuis Lulus</p>
                    <h3 class="text-3xl font-black mb-1">{{ number_format($totalPassedQuizzesCount ?? 0) }}x Kuis Lulus</h3>
                    <p class="text-xs text-cyan-100 opacity-90">Data kuis lulus dihitung dari nilai minimal KKM (Nilai ≥ 70).</p>
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
                    <p class="text-[10px] uppercase font-bold tracking-widest text-emerald-100 mb-1">Rata-rata Seluruh Kuis</p>
                    <h3 class="text-4xl font-black mb-1">{{ $globalAverage ?? 0 }}</h3>
                    <p class="text-[10px] text-emerald-100 opacity-90 mt-1">
                        Nilai gabungan dari <b>{{ number_format($totalAttempts ?? 0) }}x</b> percobaan kuis dibagi rata.
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
                        <p class="text-[10px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors">Terkumpul: <span class="text-slate-700 dark:text-white font-bold">{{ number_format($avg->total) }}x</span></p>
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
                    <p class="text-[10px] uppercase font-bold tracking-widest text-violet-200 mb-1">Rasio Kelulusan Kuis</p>
                    <h3 class="text-4xl font-black mb-3">{{ $passRate ?? 0 }}<span class="text-2xl font-bold text-violet-200">%</span></h3>
                    <div class="w-full bg-violet-800/50 rounded-full h-1.5 mb-2 overflow-hidden border border-white/10">
                        <div class="bg-white h-full" style="width: {{ $passRate ?? 0 }}%"></div>
                    </div>
                    <p class="text-[10px] text-violet-200 opacity-90 mt-2 font-mono">
                        (kuis lulus: <b>{{ number_format($totalPassedQuizzesCount ?? 0) }}x</b>) ÷ (percobaan kuis: <b>{{ number_format($totalAttempts ?? 0) }}x</b>) × 100%
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
                        <span class="block text-sm font-black text-emerald-600 dark:text-emerald-400 transition-colors">Nilai {{ $act->score }}</span>
                        <span class="text-[9px] text-slate-400 dark:text-white/30 hidden sm:inline-block font-mono mt-1 transition-colors">{{ \Carbon\Carbon::parse($act->created_at)->translatedFormat('d M Y') }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10">
                    <p class="text-[11px] text-slate-500 dark:text-white/40 italic transition-colors">Belum ada data pengguna lulus.</p>
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
                    <p class="text-[10px] uppercase font-bold tracking-widest text-red-100 mb-1">Status Prioritas Kelas</p>
                    <h3 class="text-3xl font-black mb-1">{{ number_format($realRemedialCount ?? 0) }} Peringatan Remedial</h3>
                    <p class="text-xs text-red-100 opacity-90">Pengguna berikut mendapatkan nilai < 70 dan belum pernah mencapai KKM pada evaluasi tersebut.</p>
                    @if($remedialRate > 0)
                        <div class="mt-3 inline-block px-3 py-1 bg-red-900/30 rounded-lg border border-red-100/20">
                            <span class="text-[10px] font-bold text-red-100">Mencakup {{ $remedialRate }}% dari total {{ number_format($totalStudents ?? 0) }} pengguna aktif</span>
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
                        <span class="block text-sm font-black text-red-600 dark:text-red-500 transition-colors">Nilai {{ $act->score }}</span>
                        <span class="text-[9px] text-red-700 dark:text-red-300 font-bold bg-red-100 dark:bg-red-500/10 px-2 py-0.5 rounded mt-1 hidden sm:inline-block transition-colors">Kurang {{ 70 - $act->score }}</span>
                    </div>
                </div>
                @empty
                <div class="flex flex-col items-center justify-center py-10 text-emerald-600 dark:text-emerald-400 transition-colors border border-dashed border-emerald-200 dark:border-emerald-500/20 rounded-xl bg-emerald-50 dark:bg-emerald-500/5">
                    <span class="text-[10px] font-bold uppercase tracking-widest text-center">Tidak ada peringatan</span>
                    <svg class="w-5 h-5 mt-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- 6. MODAL: DATA PRAKTIK COMPLETION (FUCHSIA HERO) --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(217,70,239,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            
            {{-- Header Hero Modal --}}
            <div class="bg-gradient-to-r from-fuchsia-500 to-purple-600 -mx-6 -mt-6 p-6 md:p-8 mb-6 text-white flex justify-between items-start shadow-inner relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-[40px] pointer-events-none"></div>
                <div class="relative z-10 w-full pr-8">
                    <p class="text-[10px] uppercase font-bold tracking-widest text-fuchsia-200 mb-1">Riwayat Praktik</p>
                    <h3 class="text-4xl font-black mb-1">{{ number_format($realLabCount ?? 0) }}x <span class="text-xl">Praktik Lulus</span></h3>
                    <p class="text-[10px] text-fuchsia-100 opacity-90 mt-1">
                        Riwayat praktik dengan status lulus. Rata-rata nilai keseluruhan: <b>{{ round($avgLabScore, 1) }}</b>
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
                        <span class="block text-sm font-black text-emerald-600 dark:text-emerald-400 transition-colors">Nilai {{ $lab->final_score }}</span>
                        <span class="text-[9px] text-slate-400 dark:text-white/30 hidden sm:inline-block font-mono mt-1 transition-colors">{{ \Carbon\Carbon::parse($lab->created_at)->diffForHumans() }}</span>
                    </div>
                </div>
                @empty
                <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data penyelesaian praktik.</p>
                @endforelse
            </div>
        </div>
    </div>


    {{-- ==================== MODALS DATA ENTRY ==================== --}}
    
    {{-- 1. IMPORT CSV --}}
    <div x-show="showImport" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/90 backdrop-blur-md transition-colors" @click="showImport = false"></div>
        <div class="relative w-full max-w-md bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] p-6 transition-colors duration-500" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-2 transition-colors">Impor Data Pengguna</h3>
            <p class="text-[10px] text-slate-500 dark:text-white/50 mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">Header CSV yang Diperlukan: <code class="bg-slate-100 dark:bg-[#0a0e17] px-1.5 py-0.5 rounded text-indigo-600 dark:text-indigo-300 font-mono font-bold mt-1 inline-block border border-slate-200 dark:border-white/5 transition-colors">Nama, Email, Kelas, Institusi, Kata Sandi</code></p>
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
            <h3 class="text-lg font-bold text-slate-900 dark:text-white mb-6 border-b border-slate-200 dark:border-white/5 pb-3 transition-colors">Daftarkan Pengguna Baru</h3>
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
    let roleDistributionChart = null;
    let classDistributionChart = null;
    let chapterAverageChart = null;
    let activityVolumeChart = null;
    let heroChartInstance = null;

    document.addEventListener("DOMContentLoaded", function() {
        const ctx = document.getElementById('quizChart');
        if(ctx) {
            initChart();
        }

        initDashboardGraphCharts();

        // Listener jika tema diubah, render ulang chart
        window.addEventListener('theme-toggled', () => {
            if(myChart) {
                myChart.destroy();
                initChart();
            }

            destroyDashboardGraphCharts();
            initDashboardGraphCharts();
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
                labels = ['Belum ada data'];
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
                        label: 'Nilai Rata-rata',
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

        function destroyDashboardGraphCharts() {
            [roleDistributionChart, classDistributionChart, chapterAverageChart, activityVolumeChart].forEach(chart => {
                if (chart) chart.destroy();
            });

            roleDistributionChart = null;
            classDistributionChart = null;
            chapterAverageChart = null;
            activityVolumeChart = null;
        }

        function getChartThemeConfig() {
            const isDark = document.documentElement.classList.contains('dark');

            return {
                isDark,
                gridColor: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.06)',
                textColor: isDark ? '#94a3b8' : '#64748b',
                tooltipBg: isDark ? 'rgba(15, 20, 30, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                tooltipText: isDark ? '#fff' : '#1e293b',
                borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(15,23,42,0.08)',
                palette: ['#6366f1', '#06b6d4', '#10b981', '#f59e0b', '#ef4444', '#d946ef', '#8b5cf6', '#14b8a6']
            };
        }

        function emptyDataset(labels, data, fallbackLabel = 'Belum ada data') {
            if (!Array.isArray(labels) || labels.length === 0 || !Array.isArray(data) || data.length === 0) {
                return {
                    labels: [fallbackLabel],
                    data: [0]
                };
            }

            return { labels, data };
        }

        function initDashboardGraphCharts() {
            const theme = getChartThemeConfig();
            const defaultPlugins = {
                legend: {
                    labels: {
                        color: theme.textColor,
                        boxWidth: 10,
                        boxHeight: 10,
                        font: { size: 10, family: 'Inter', weight: '700' }
                    }
                },
                tooltip: {
                    backgroundColor: theme.tooltipBg,
                    titleColor: theme.tooltipText,
                    bodyColor: theme.tooltipText,
                    borderColor: theme.borderColor,
                    borderWidth: 1,
                    padding: 12,
                    titleFont: { family: 'Inter', size: 12, weight: 'bold' },
                    bodyFont: { family: 'Inter', size: 11 }
                }
            };

            const roleCanvas = document.getElementById('roleDistributionChart');
            if (roleCanvas) {
                const raw = emptyDataset(
                    {!! json_encode($userRoleChartLabels ?? []) !!},
                    {!! json_encode($userRoleChartData ?? []) !!}
                );

                roleDistributionChart = new Chart(roleCanvas.getContext('2d'), {
                    type: 'doughnut',
                    data: {
                        labels: raw.labels,
                        datasets: [{
                            data: raw.data,
                            backgroundColor: theme.palette,
                            borderColor: theme.isDark ? '#0f141e' : '#ffffff',
                            borderWidth: 3,
                            hoverOffset: 8
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        cutout: '68%',
                        plugins: defaultPlugins
                    }
                });
            }

            const classCanvas = document.getElementById('classDistributionChart');
            if (classCanvas) {
                const raw = emptyDataset(
                    {!! json_encode($studentClassChartLabels ?? []) !!},
                    {!! json_encode($studentClassChartData ?? []) !!}
                );

                classDistributionChart = new Chart(classCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: raw.labels,
                        datasets: [{
                            label: 'Jumlah Pengguna',
                            data: raw.data,
                            backgroundColor: '#06b6d4',
                            borderRadius: 10,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        indexAxis: 'y',
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            ...defaultPlugins,
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                beginAtZero: true,
                                grid: { color: theme.gridColor },
                                ticks: { color: theme.textColor, precision: 0, font: { size: 10, family: 'JetBrains Mono' } }
                            },
                            y: {
                                grid: { display: false },
                                ticks: { color: theme.textColor, font: { size: 10, family: 'Inter', weight: '700' } }
                            }
                        }
                    }
                });
            }

            const chapterCanvas = document.getElementById('chapterAverageChart');
            if (chapterCanvas) {
                const raw = emptyDataset(
                    {!! json_encode($chapterAverageLabels ?? []) !!},
                    {!! json_encode($chapterAverageScores ?? []) !!}
                );

                chapterAverageChart = new Chart(chapterCanvas.getContext('2d'), {
                    type: 'bar',
                    data: {
                        labels: raw.labels,
                        datasets: [{
                            label: 'Rata-rata Nilai',
                            data: raw.data,
                            backgroundColor: '#10b981',
                            borderRadius: 10,
                            borderSkipped: false
                        }]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            ...defaultPlugins,
                            legend: { display: false }
                        },
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: theme.textColor, font: { size: 10, family: 'Inter', weight: '700' } }
                            },
                            y: {
                                beginAtZero: true,
                                max: 100,
                                grid: { color: theme.gridColor },
                                ticks: { color: theme.textColor, font: { size: 10, family: 'JetBrains Mono' } }
                            }
                        }
                    }
                });
            }

            const activityCanvas = document.getElementById('activityVolumeChart');
            if (activityCanvas) {
                let activityLabels = {!! json_encode($activityTrendLabels ?? []) !!};
                let quizCounts = {!! json_encode($activityQuizCounts ?? []) !!};
                let labCounts = {!! json_encode($activityLabCounts ?? []) !!};

                if (!Array.isArray(activityLabels) || activityLabels.length === 0) {
                    activityLabels = ['Belum ada data'];
                    quizCounts = [0];
                    labCounts = [0];
                }

                activityVolumeChart = new Chart(activityCanvas.getContext('2d'), {
                    type: 'line',
                    data: {
                        labels: activityLabels,
                        datasets: [
                            {
                                label: 'Kuis',
                                data: quizCounts,
                                borderColor: '#6366f1',
                                backgroundColor: 'rgba(99,102,241,0.12)',
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: theme.isDark ? '#0f141e' : '#ffffff',
                                pointBorderColor: '#6366f1',
                                fill: true,
                                tension: 0.4
                            },
                            {
                                label: 'Praktik',
                                data: labCounts,
                                borderColor: '#d946ef',
                                backgroundColor: 'rgba(217,70,239,0.10)',
                                borderWidth: 3,
                                pointRadius: 4,
                                pointBackgroundColor: theme.isDark ? '#0f141e' : '#ffffff',
                                pointBorderColor: '#d946ef',
                                fill: true,
                                tension: 0.4
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: defaultPlugins,
                        scales: {
                            x: {
                                grid: { display: false },
                                ticks: { color: theme.textColor, font: { size: 10, family: 'JetBrains Mono' } }
                            },
                            y: {
                                beginAtZero: true,
                                grid: { color: theme.gridColor },
                                ticks: { color: theme.textColor, precision: 0, font: { size: 10, family: 'JetBrains Mono' } }
                            }
                        },
                        interaction: { mode: 'index', intersect: false }
                    }
                });
            }
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

    function getSwalTheme() {
        const isDark = document.documentElement.classList.contains('dark');
        return {
            background: isDark ? '#0f141e' : '#ffffff',
            color: isDark ? '#fff' : '#1e293b'
        };
    }

    function getChartInstanceByKey(key) {
        return {
            role: roleDistributionChart,
            class: classDistributionChart,
            chapter: chapterAverageChart,
            activity: activityVolumeChart,
            trend: myChart
        }[key] || null;
    }

    function cloneChartData(chart) {
        return {
            labels: [...(chart.data.labels || [])],
            datasets: (chart.data.datasets || []).map(dataset => ({
                ...dataset,
                data: Array.isArray(dataset.data) ? [...dataset.data] : dataset.data,
                backgroundColor: Array.isArray(dataset.backgroundColor) ? [...dataset.backgroundColor] : dataset.backgroundColor,
                borderColor: Array.isArray(dataset.borderColor) ? [...dataset.borderColor] : dataset.borderColor
            }))
        };
    }

    function buildHeroChartOptions(sourceChart) {
        const isDark = document.documentElement.classList.contains('dark');
        const theme = {
            isDark,
            gridColor: isDark ? 'rgba(255,255,255,0.06)' : 'rgba(15,23,42,0.06)',
            textColor: isDark ? '#94a3b8' : '#64748b',
            tooltipBg: isDark ? 'rgba(15, 20, 30, 0.95)' : 'rgba(255, 255, 255, 0.95)',
            tooltipText: isDark ? '#fff' : '#1e293b',
            borderColor: isDark ? 'rgba(255,255,255,0.08)' : 'rgba(15,23,42,0.08)'
        };
        const type = sourceChart.config.type;
        const isHorizontalBar = sourceChart.config.options?.indexAxis === 'y';

        const basePlugins = {
            legend: {
                display: type === 'doughnut' || (sourceChart.data.datasets || []).length > 1,
                position: 'bottom',
                labels: {
                    color: theme.textColor,
                    boxWidth: 12,
                    boxHeight: 12,
                    padding: 18,
                    font: { family: 'Inter', size: 12, weight: '800' }
                }
            },
            tooltip: {
                backgroundColor: theme.tooltipBg,
                titleColor: theme.tooltipText,
                bodyColor: theme.tooltipText,
                borderColor: theme.borderColor,
                borderWidth: 1,
                padding: 14,
                titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                bodyFont: { family: 'Inter', size: 12 }
            }
        };

        if (type === 'doughnut' || type === 'pie') {
            return {
                responsive: true,
                maintainAspectRatio: false,
                cutout: type === 'doughnut' ? '58%' : undefined,
                plugins: basePlugins
            };
        }

        return {
            responsive: true,
            maintainAspectRatio: false,
            indexAxis: isHorizontalBar ? 'y' : 'x',
            plugins: basePlugins,
            scales: {
                x: {
                    beginAtZero: true,
                    grid: { color: isHorizontalBar ? theme.gridColor : 'transparent' },
                    ticks: { color: theme.textColor, precision: 0, font: { family: 'Inter', size: 11, weight: '700' } }
                },
                y: {
                    beginAtZero: true,
                    max: sourceChart.config.options?.scales?.y?.max || undefined,
                    grid: { color: isHorizontalBar ? 'transparent' : theme.gridColor },
                    ticks: { color: theme.textColor, precision: 0, font: { family: 'JetBrains Mono', size: 11 } }
                }
            },
            interaction: { mode: 'index', intersect: false }
        };
    }

    function openHeroChart(key, title, description) {
        const sourceChart = getChartInstanceByKey(key);
        const modal = document.getElementById('chartHeroModal');
        const titleEl = document.getElementById('chartHeroTitle');
        const descEl = document.getElementById('chartHeroDescription');
        const canvas = document.getElementById('heroChartCanvas');

        if (!sourceChart || !modal || !canvas) return;

        titleEl.textContent = title || 'Pratinjau Grafik';
        descEl.textContent = description || 'Tampilan grafik diperbesar untuk pembacaan data yang lebih nyaman.';

        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.classList.add('modal-open');

        if (heroChartInstance) {
            heroChartInstance.destroy();
            heroChartInstance = null;
        }

        heroChartInstance = new Chart(canvas.getContext('2d'), {
            type: sourceChart.config.type,
            data: cloneChartData(sourceChart),
            options: buildHeroChartOptions(sourceChart)
        });
    }

    function closeHeroChart() {
        const modal = document.getElementById('chartHeroModal');
        if (!modal) return;

        modal.classList.add('hidden');
        modal.classList.remove('flex');
        document.body.classList.remove('modal-open');

        if (heroChartInstance) {
            heroChartInstance.destroy();
            heroChartInstance = null;
        }
    }

    document.addEventListener('keydown', function(event) {
        if (event.key === 'Escape') closeHeroChart();
    });

    // SWAL ALERTS THEME RESPONSIVE
    document.addEventListener('DOMContentLoaded', () => {
        const isDark = document.documentElement.classList.contains('dark');
        const swalBg = isDark ? '#0f141e' : '#ffffff';
        const swalColor = isDark ? '#fff' : '#1e293b';

        @if(session('success')) Swal.fire({ title: 'Berhasil!', text: "{{ session('success') }}", icon: 'success', background: swalBg, color: swalColor, confirmButtonColor: '#6366f1', customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-[0_10px_50px_rgba(0,0,0,0.8)]' } }); @endif
        @if(session('error')) Swal.fire({ title: 'Gagal!', text: "{{ session('error') }}", icon: 'error', background: swalBg, color: swalColor, confirmButtonColor: '#ef4444', customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-[0_10px_50px_rgba(0,0,0,0.8)]' } }); @endif

        document.querySelectorAll('.delete-directory-form').forEach(form => {
            form.addEventListener('submit', function(event) {
                event.preventDefault();

                const theme = getSwalTheme();
                const userName = this.dataset.userName || 'pengguna ini';
                const userEmail = this.dataset.userEmail || '';
                const action = this.getAttribute('action') || '#';

                if (action === '#') {
                    Swal.fire({
                        title: 'Route hapus belum tersedia',
                        text: 'Tambahkan route admin.users.delete, admin.users.destroy, atau admin.user.destroy agar fitur hapus dapat berjalan.',
                        icon: 'warning',
                        background: theme.background,
                        color: theme.color,
                        confirmButtonColor: '#6366f1',
                        customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-[0_10px_50px_rgba(0,0,0,0.8)]' }
                    });
                    return;
                }

                Swal.fire({
                    title: 'Hapus pengguna ini?',
                    html: `<div class="text-sm leading-6">Data akun <b>${userName}</b><br><span class="text-xs opacity-70">${userEmail}</span><br><br>Tindakan ini akan menghapus akun pengguna dan data terkait dari dashboard.</div>`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Ya, hapus sekarang',
                    cancelButtonText: 'Batal',
                    reverseButtons: true,
                    background: theme.background,
                    color: theme.color,
                    confirmButtonColor: '#ef4444',
                    cancelButtonColor: '#64748b',
                    customClass: { popup: 'rounded-2xl border border-slate-200 dark:border-white/10 shadow-xl dark:shadow-[0_10px_50px_rgba(0,0,0,0.8)]' }
                }).then(result => {
                    if (result.isConfirmed) {
                        this.submit();
                    }
                });
            });
        });
    });
</script>

</body>
</html>
