<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Siswa · {{ $user->name ?? 'Profil Siswa' }}</title>
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/@alpinejs/collapse@3.x.x/dist/cdn.min.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script> 
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
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
        /* --- THEME CONFIG --- */
        :root { --glass-bg: rgba(255, 255, 255, 0.85); --glass-border: rgba(0, 0, 0, 0.05); --accent: #6366f1; }
        .dark { --glass-bg: rgba(10, 14, 23, 0.65); --glass-border: rgba(255, 255, 255, 0.08); }
        
        body { font-family: 'Inter', sans-serif; background-color: #f8fafc; color: #1e293b; overflow: hidden; transition: background-color 0.3s, color 0.3s; }
        .dark body { background-color: #020617; color: #e2e8f0; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.05); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
        .dark .custom-scrollbar::-webkit-scrollbar-track { background: rgba(0,0,0,0.1); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.15); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.25); }

        /* --- GLASS COMPONENTS --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(0,0,0,0.05); z-index: 50; }
        .dark .glass-sidebar { background: rgba(5, 8, 16, 0.95); border-right: 1px solid var(--glass-border); }
        
        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(16px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 40; }
        .dark .glass-header { background: rgba(2, 6, 23, 0.7); border-bottom: 1px solid var(--glass-border); }
        
        .glass-card { 
            background: var(--glass-bg); border: 1px solid var(--glass-border); 
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05); backdrop-filter: blur(10px); transition: all 0.3s; 
            position: relative; overflow: visible !important; z-index: 10;
        }
        .dark .glass-card { box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 12px 40px -10px rgba(99,102,241,0.15); z-index: 30; }

        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.1); color: #1e293b; transition: 0.3s; }
        .dark .glass-input { background: rgba(255, 255, 255, 0.02); border: 1px solid rgba(255, 255, 255, 0.08); color: white; }
        .glass-input:focus { border-color: var(--accent); background: rgba(0, 0, 0, 0.05); outline: none; box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.15); }
        .dark .glass-input:focus { background: rgba(255, 255, 255, 0.05); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #64748b; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(0, 0, 0, 0.03); color: #0f172a; transform: translateX(4px); }
        .dark .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #6366f1; border-left: 3px solid #6366f1; border-radius: 4px 12px 12px 4px; }
        .dark .nav-link.active { color: #818cf8; border-left-color: #818cf8; }
        
        .tab-btn { position: relative; color: #64748b; transition: all 0.3s; }
        .tab-btn:hover { color: #1e293b; }
        .dark .tab-btn:hover { color: #cbd5e1; }
        .tab-btn.active { color: #6366f1; font-weight: 700; }
        .dark .tab-btn.active { color: #fff; font-weight: 600; text-shadow: 0 0 12px rgba(255,255,255,0.3); }
        .tab-btn.active::after { content: ''; position: absolute; bottom: -1px; left: 0; width: 100%; height: 2px; background: var(--accent); box-shadow: 0 -2px 10px var(--accent); border-radius: 2px 2px 0 0; }

        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(0,0,0,0.03); }
        .table-row:hover { background: rgba(0,0,0,0.02); }
        .dark .table-row { border-bottom: 1px solid rgba(255,255,255,0.03); }
        .dark .table-row:hover { background: rgba(255,255,255,0.02); }

        .reveal { opacity: 0; transform: translateY(15px); animation: revealAnim 0.5s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        [x-cloak] { display: none !important; }


        /* ==========================================================
           PENYEMPURNAAN DETAIL INTERAKSI — DISAMAKAN DENGAN DIREKTORI
           ========================================================== */
        html { scroll-behavior: smooth; }
        .smooth-student-scroll {
            scroll-behavior: auto;
            overscroll-behavior-y: contain;
            -webkit-overflow-scrolling: touch;
            scrollbar-gutter: stable both-edges;
            scroll-padding-top: 8rem;
            will-change: scroll-position;
        }
        .smooth-student-scroll:focus { outline: none; }
        .smooth-student-scroll.is-wheel-smoothing { cursor: default; }

        .glass-card {
            transition-property: transform, border-color, box-shadow, background-color, opacity;
            transition-duration: .28s;
            transition-timing-function: cubic-bezier(.22,.61,.36,1);
            will-change: transform;
        }
        .glass-card:hover {
            transform: translate3d(0,-2px,0);
            box-shadow: 0 14px 36px -18px rgba(15,23,42,.28);
        }
        .dark .glass-card:hover { box-shadow: 0 18px 46px -20px rgba(0,0,0,.72); }

        .soft-hover,
        .tab-btn,
        .table-row,
        .glass-input,
        button,
        a {
            transition-property: transform, background-color, border-color, color, box-shadow, opacity;
            transition-duration: .22s;
            transition-timing-function: cubic-bezier(.22,.61,.36,1);
        }
        .tab-btn:hover { transform: translateY(-1px); }
        .table-row:hover { background: rgba(99,102,241,.045); }
        .dark .table-row:hover { background: rgba(129,140,248,.055); }
        .nav-link:hover { transform: translateX(2px); }
        button:hover, a:hover { will-change: transform; }
        button:active, a:active { transform: scale(.985); }

        .metric-card-quiet:hover { transform: translate3d(0,-2px,0); }
        .metric-card-quiet:hover .metric-icon-quiet { transform: scale(1.06); }
        .metric-icon-quiet { transition: transform .28s cubic-bezier(.22,.61,.36,1); }

        .la-pulse-card {
            background: rgba(255,255,255,.72);
            border: 1px solid rgba(15,23,42,.08);
            box-shadow: 0 10px 32px -24px rgba(15,23,42,.30);
            transition: transform .24s cubic-bezier(.22,.61,.36,1), border-color .24s ease, background-color .24s ease, box-shadow .24s ease;
        }
        .dark .la-pulse-card { background: rgba(255,255,255,.035); border-color: rgba(255,255,255,.08); box-shadow: none; }
        .la-pulse-card:hover { transform: translate3d(0,-2px,0); border-color: rgba(99,102,241,.28); box-shadow: 0 16px 34px -26px rgba(79,70,229,.42); }
        .dark .la-pulse-card:hover { border-color: rgba(129,140,248,.22); box-shadow: 0 16px 42px -30px rgba(0,0,0,.9); }
        .la-mini-bar { overflow: hidden; border-radius: 999px; background: rgba(148,163,184,.20); }
        .dark .la-mini-bar { background: rgba(255,255,255,.075); }
        .la-mini-bar > span { display: block; height: 100%; border-radius: inherit; transition: width 1s cubic-bezier(.22,.61,.36,1); }


        /* ==========================================================
           PRESENTASI LEARNING ANALYTICS
           Kelas berikut hanya mengatur tampilan. Seluruh data tetap
           memakai variabel dan koleksi yang sama dari controller.
           ========================================================== */
        .analytics-panel,
        .analytics-metric-card {
            position: relative;
            overflow: hidden;
            border: 1px solid rgba(15,23,42,.08);
            background: rgba(255,255,255,.76);
            box-shadow: 0 12px 32px -26px rgba(15,23,42,.34);
            transition: transform .24s cubic-bezier(.22,.61,.36,1), border-color .24s ease, box-shadow .24s ease, background-color .24s ease;
        }
        .dark .analytics-panel,
        .dark .analytics-metric-card {
            border-color: rgba(255,255,255,.09);
            background: rgba(255,255,255,.035);
            box-shadow: none;
        }
        .analytics-panel:hover,
        .analytics-metric-card:hover {
            transform: translate3d(0,-1px,0);
            border-color: rgba(99,102,241,.28);
            box-shadow: 0 18px 38px -28px rgba(79,70,229,.42);
        }
        .dark .analytics-panel:hover,
        .dark .analytics-metric-card:hover {
            border-color: rgba(129,140,248,.24);
            box-shadow: 0 18px 46px -30px rgba(0,0,0,.9);
        }
        .analytics-ring {
            --progress: 0%;
            width: 96px;
            height: 96px;
            border-radius: 999px;
            display: grid;
            place-items: center;
            background: conic-gradient(#6366f1 var(--progress), rgba(148,163,184,.18) 0);
            flex: 0 0 auto;
            transition: background .45s ease;
        }
        .dark .analytics-ring { background: conic-gradient(#818cf8 var(--progress), rgba(255,255,255,.10) 0); }
        .analytics-ring__inner {
            width: 76px;
            height: 76px;
            border-radius: inherit;
            display: grid;
            place-items: center;
            background: rgba(255,255,255,.96);
            box-shadow: inset 0 0 0 1px rgba(15,23,42,.05);
        }
        .dark .analytics-ring__inner { background: #0f141e; box-shadow: inset 0 0 0 1px rgba(255,255,255,.07); }
        .analytics-track { height: 6px; overflow: hidden; border-radius: 999px; background: rgba(148,163,184,.20); }
        .dark .analytics-track { background: rgba(255,255,255,.075); }
        .analytics-track > span { display: block; height: 100%; border-radius: inherit; transition: width 1s cubic-bezier(.22,.61,.36,1); }
        .analytics-signal {
            border-left: 3px solid currentColor;
            background: rgba(99,102,241,.045);
        }
        .dark .analytics-signal { background: rgba(255,255,255,.035); }
        .analytics-metric-card {
            width: 100%;
            min-height: 178px;
            text-align: left;
            padding: 1.15rem;
            border-radius: 1rem;
        }
        .analytics-metric-card:focus-visible {
            outline: 3px solid rgba(99,102,241,.28);
            outline-offset: 3px;
        }
        .analytics-metric-card .analytics-metric-icon {
            transition: transform .24s cubic-bezier(.22,.61,.36,1), background-color .24s ease;
        }
        .analytics-metric-card:hover .analytics-metric-icon { transform: scale(1.05); }
        .analytics-performance-row {
            transition: background-color .2s ease, transform .2s cubic-bezier(.22,.61,.36,1), border-color .2s ease;
        }
        .analytics-performance-row:hover {
            transform: translateX(2px);
            background: rgba(99,102,241,.045);
            border-color: rgba(99,102,241,.18);
        }
        .dark .analytics-performance-row:hover { background: rgba(129,140,248,.055); border-color: rgba(129,140,248,.16); }

        .insight-trigger { transition-duration: .2s; }
        .insight-tooltip:hover .insight-trigger { transform: scale(1.06); }
        .insight-content { z-index: 2147483000 !important; transition-duration: .18s; }
        .sticky, .glass-header { transform: translateZ(0); }

        .chart-container canvas { transition: opacity .22s ease; }
        .chart-container:hover canvas { opacity: .96; }

        @media (prefers-reduced-motion: reduce) {
            html,
            .smooth-student-scroll { scroll-behavior: auto !important; }
            *, *::before, *::after { animation-duration: .01ms !important; animation-iteration-count: 1 !important; transition-duration: .01ms !important; }
        }

        /* --- TOOLTIP INSIGHT (PETUNJUK HALAMAN) --- */
        .insight-tooltip { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; margin-left: 6px; }
        .insight-tooltip:hover { z-index: 99999; }
        
        .insight-trigger { 
            display: flex; align-items: center; justify-content: center;
            width: 18px; height: 18px; border-radius: 50%;
            background: rgba(99, 102, 241, 0.1); border: 1px solid rgba(99, 102, 241, 0.3);
            color: #6366f1; font-size: 11px; font-weight: 800; cursor: help;
            transition: all 0.3s ease;
        }
        .dark .insight-trigger { background: rgba(129, 140, 248, 0.1); border-color: rgba(129, 140, 248, 0.3); color: #818cf8; }
        .insight-tooltip:hover .insight-trigger { background: #6366f1; color: white; border-color: #6366f1; transform: scale(1.1); box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        
        .insight-content {
            opacity: 0; visibility: hidden; position: absolute; bottom: calc(100% + 10px); left: 50%; transform: translateX(-50%) translateY(10px);
            width: max-content; max-width: 260px; background: rgba(15, 23, 42, 0.95); backdrop-filter: blur(10px);
            color: #f8fafc; font-size: 12px; padding: 12px 16px; border-radius: 12px; text-align: center;
            box-shadow: 0 10px 30px -5px rgba(0,0,0,0.5); border: 1px solid rgba(255,255,255,0.1); 
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); pointer-events: none; font-weight: 500; line-height: 1.5; z-index: 99999;
        }
        .dark .insight-content { background: rgba(255, 255, 255, 0.95); color: #0f172a; border: 1px solid rgba(0,0,0,0.1); }
        
        .insight-tooltip:hover .insight-content { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(0); }
        .insight-content::after {
            content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%);
            border-width: 6px; border-style: solid; border-color: rgba(15, 23, 42, 0.95) transparent transparent transparent;
        }
        .dark .insight-content::after { border-color: rgba(255, 255, 255, 0.95) transparent transparent transparent; }
        
        .insight-right .insight-content { bottom: auto; top: 50%; left: calc(100% + 12px); transform: translateY(-50%) translateX(-10px); text-align: left; }
        .insight-right:hover .insight-content { transform: translateY(-50%) translateX(0); }
        .insight-right .insight-content::after { top: 50%; left: -12px; transform: translateY(-50%); border-color: transparent rgba(15, 23, 42, 0.95) transparent transparent; border-width: 6px; }
        .dark .insight-right .insight-content::after { border-color: transparent rgba(255, 255, 255, 0.95) transparent transparent; }
    </style>
</head>
<body class="h-screen w-full flex overflow-hidden selection:bg-indigo-500/30 selection:text-indigo-900 dark:selection:text-white" 
      x-data="{ 
          sidebarOpen: false,
          activeTab: 'overview', 
          showEdit: false, 
          searchLab: '', 
          searchQuiz: '',
          searchLesson: '',
          showProgress: false,
          
          // State modal ringkasan analitik
          showLessonModal: false,
          showLabModal: false,
          showQuizModal: false,
          showAvgLabModal: false,
          showAvgQuizModal: false,
          showGlobalProgressModal: false,
          showQuizReviewModal: false,
          selectedQuizReview: null,

          confirmHapus() {
              Swal.fire({ title: 'Hapus Siswa?', text: 'Tindakan ini tidak dapat dibatalkan. Semua data riwayat akan terhapus.', icon: 'warning', showCancelButton: true, confirmButtonColor: '#ef4444', cancelButtonColor: '#334155', confirmButtonText: 'Ya, Hapus Permanen', cancelButtonText: 'Batal', reverseButtons: true })
              .then((result) => { if (result.isConfirmed) document.getElementById('delete-student-form').submit(); })
          }
      }"
      @keydown.escape.window="showLessonModal = false; showLabModal = false; showQuizModal = false; showAvgLabModal = false; showAvgQuizModal = false; showGlobalProgressModal = false; showQuizReviewModal = false; showEdit = false;"
      x-init="setTimeout(() => showProgress = true, 200); $watch('activeTab', value => { if(value === 'overview') { showProgress = false; setTimeout(() => showProgress = true, 50); } });">

    {{-- HELPER DATA BLADE (Real Database Collections & Logical Duration Formatter) --}}
    @php
        use Illuminate\Support\Str;

        function formatTime($seconds) {
            if ($seconds === null || $seconds === '') return '-';
            if ($seconds == 0) return '0s';
            if ($seconds > 43200) { return '> 12j'; }
            
            $h = floor($seconds / 3600);
            $m = floor(($seconds % 3600) / 60);
            $s = $seconds % 60;
            
            $res = [];
            if ($h > 0) $res[] = "{$h}j";
            if ($m > 0) $res[] = "{$m}m";
            if ($s > 0 || empty($res)) $res[] = "{$s}s";
            
            return implode(' ', $res);
        }
        
        $labHistories = isset($labHistories) ? collect($labHistories) : collect([]);
        $quizAttempts = isset($quizAttempts) ? collect($quizAttempts) : collect([]);
        $lessonHistories = isset($lessonHistories) ? collect($lessonHistories) : collect([]);
        $completedLessonIds = $completedLessonIds ?? [];
        
        // PETA BLUEPRINT TRACKER
        $curriculumMap = [
            [
                'id' => 1, 'number' => '01', 'title' => 'PENDAHULUAN', 'color' => 'cyan', 'lab_id' => 1, 'lab_name' => 'Setup Environment', 'quiz_key' => '1',
                'topics' => [['name' => '1.1 Konsep HTML & CSS', 'ids' => range(1, 6)], ['name' => '1.2 Konsep Dasar Tailwind', 'ids' => range(7, 11)], ['name' => '1.3 Latar Belakang & Struktur', 'ids' => range(12, 15)], ['name' => '1.4 Implementasi pada HTML', 'ids' => range(16, 19)], ['name' => '1.5 Keunggulan & Utilitas', 'ids' => range(20, 23)], ['name' => '1.6 Instalasi & Konfigurasi', 'ids' => range(24, 28)]]
            ],
            [
                'id' => 2, 'number' => '02', 'title' => 'LAYOUTING', 'color' => 'indigo', 'lab_id' => 2, 'lab_name' => 'Building Grid Layout', 'quiz_key' => '2',
                'topics' => [['name' => '2.1 Arsitektur Flexbox', 'ids' => range(29, 33)], ['name' => '2.2 Penguasaan Sistem Grid', 'ids' => range(34, 40)], ['name' => '2.3 Pengelolaan Layout', 'ids' => range(41, 45)]]
            ],
            [
                'id' => 3, 'number' => '03', 'title' => 'STYLING', 'color' => 'fuchsia', 'lab_id' => 3, 'lab_name' => 'Styling Components', 'quiz_key' => '3',
                'topics' => [['name' => '3.1 Tipografi & Font', 'ids' => range(46, 51)], ['name' => '3.2 Latar Belakang', 'ids' => range(52, 55)], ['name' => '3.3 Borders & Rings', 'ids' => range(56, 59)], ['name' => '3.4 Efek dan Filter', 'ids' => range(60, 64)]]
            ]
        ];

        // 🔹 LOGIKA GAMBAR AVATAR (MENGGUNAKAN UPLOADS & CACHE BUSTING)
        $pathPrefix = 'uploads/'; 

        // Avatar untuk ADMIN (User Login)
        $adminAvatarUrl = 'https://ui-avatars.com/api/?name='.urlencode(Auth::user()->name ?? 'A').'&background=6366f1&color=fff&size=256';
        if (!empty(Auth::user()->avatar)) {
            $adminAvatarUrl = Str::startsWith(Auth::user()->avatar, ['http://', 'https://']) 
                ? Auth::user()->avatar 
                : asset($pathPrefix . Auth::user()->avatar) . '?v=' . time(); 
        }

        // Avatar untuk SISWA (Profile yang sedang dilihat)
        $studentAvatarUrl = 'https://ui-avatars.com/api/?name='.urlencode($user->name ?? 'S').'&background=06b6d4&color=fff&size=256';
        if (!empty($user->avatar)) {
            $studentAvatarUrl = Str::startsWith($user->avatar, ['http://', 'https://']) 
                ? $user->avatar 
                : asset($pathPrefix . $user->avatar) . '?v=' . time();
        }

        $latestQuizForAdvice = $quizAttempts->sortByDesc('completed_at')->first() ?? $quizAttempts->sortByDesc('created_at')->first();
        $failedQuizCount = $quizAttempts->where('score', '<', 70)->count();
        $failedLabCount = $labHistories->filter(fn($lab) => ($lab->status ?? '') !== 'passed' && ($lab->final_score ?? 0) < 70)->count();
        $adminAdviceScore = 0;
        $adminRecommendations = collect();

        if ($latestQuizForAdvice && ($latestQuizForAdvice->score ?? 0) < 70) {
            $adminAdviceScore += 2;
            $adminRecommendations->push([
                'title' => 'Jadwalkan penguatan evaluasi terakhir',
                'body' => 'Nilai terakhir belum mencapai KKM. Arahkan siswa meninjau feedback evaluasi dan mengulang materi bab terkait.',
                'tone' => 'red',
            ]);
        }

        if ($latestQuizForAdvice && (($latestQuizForAdvice->flagged_count ?? 0) > 0)) {
            $adminAdviceScore += 1;
            $adminRecommendations->push([
                'title' => 'Bahas soal yang ditandai ragu-ragu',
                'body' => 'Siswa masih ragu pada beberapa soal. Gunakan sesi singkat untuk menguatkan konsep yang belum mantap.',
                'tone' => 'amber',
            ]);
        }

        if ($failedQuizCount >= 2) {
            $adminAdviceScore += 1;
            $adminRecommendations->push([
                'title' => 'Pantau pola remedial',
                'body' => 'Ada beberapa percobaan kuis di bawah KKM. Prioritaskan siswa ini dalam pendampingan kelas.',
                'tone' => 'red',
            ]);
        }

        if (($totalLessons ?? 0) > 0 && $lessonsCompleted < ceil(($totalLessons ?? 0) * 0.75)) {
            $adminRecommendations->push([
                'title' => 'Dorong penyelesaian materi',
                'body' => 'Progres bacaan belum tinggi. Minta siswa menyelesaikan materi prasyarat sebelum evaluasi berikutnya.',
                'tone' => 'cyan',
            ]);
        }

        if ($failedLabCount > 0 || (($totalLabs ?? 0) > 0 && $labsCompleted < ($totalLabs ?? 0))) {
            $adminRecommendations->push([
                'title' => 'Lengkapi praktik lab',
                'body' => 'Praktik lab masih perlu diperkuat agar pemahaman teori tersambung dengan implementasi.',
                'tone' => 'blue',
            ]);
        }

        if ($adminRecommendations->isEmpty()) {
            $adminRecommendations->push([
                'title' => 'Pertahankan ritme belajar',
                'body' => 'Siswa berada pada jalur yang stabil. Dorong untuk melanjutkan bab berikutnya dan tetap meninjau riwayat evaluasi.',
                'tone' => 'emerald',
            ]);
        }

        $adminRisk = $adminAdviceScore >= 3
            ? ['label' => 'Risiko Tinggi', 'class' => 'text-red-600 dark:text-red-400 bg-red-50 dark:bg-red-500/10 border-red-200 dark:border-red-500/20']
            : ($adminAdviceScore >= 1
                ? ['label' => 'Perlu Perhatian', 'class' => 'text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-500/10 border-amber-200 dark:border-amber-500/20']
                : ['label' => 'Stabil', 'class' => 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border-emerald-200 dark:border-emerald-500/20']);

        $adminRecommendations = $adminRecommendations->take(4)->values();


        $latestActivityCandidates = collect();
        if ($latestQuizForAdvice) {
            $latestActivityCandidates->push($latestQuizForAdvice->completed_at ?? $latestQuizForAdvice->created_at ?? null);
        }
        $latestLabForPulse = $labHistories->sortByDesc('created_at')->first();
        if ($latestLabForPulse) {
            $latestActivityCandidates->push($latestLabForPulse->completed_at ?? $latestLabForPulse->created_at ?? null);
        }
        $latestLessonForPulse = $lessonHistories->sortByDesc('created_at')->first();
        if ($latestLessonForPulse) {
            $latestActivityCandidates->push($latestLessonForPulse->created_at ?? null);
        }
        $latestActivityAt = $latestActivityCandidates->filter()->map(fn($date) => \Carbon\Carbon::parse($date))->sortDesc()->first();
        $quizPassedCountForPulse = count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70));
        $totalAcademicItemsForPulse = max(1, (int)($totalLessons ?? 65) + (int)($totalLabs ?? 4) + (int)($totalQuizzes ?? 4));
        $completedAcademicItemsForPulse = (int)($lessonsCompleted ?? count($completedLessonIds ?? [])) + (int)($labsCompleted ?? ($labStats['total'] ?? 0)) + (int)$quizPassedCountForPulse;
        $academicCoverageForPulse = min(100, round(($completedAcademicItemsForPulse / $totalAcademicItemsForPulse) * 100));


        // RINGKASAN PRESENTASI ANALITIK — dihitung dari data yang telah tersedia.
        $lessonsDoneForAnalytics = (int) ($lessonsCompleted ?? count($completedLessonIds ?? []));
        $lessonsTotalForAnalytics = max(1, (int) ($totalLessons ?? 65));
        $labsDoneForAnalytics = (int) ($labsCompleted ?? ($labStats['total'] ?? 0));
        $labsTotalForAnalytics = max(1, (int) ($totalLabs ?? 4));
        $quizzesPassedForAnalytics = (int) $quizPassedCountForPulse;
        $quizzesTotalForAnalytics = max(1, (int) ($totalQuizzes ?? 4));

        $lessonProgressForAnalytics = min(100, round(($lessonsDoneForAnalytics / $lessonsTotalForAnalytics) * 100));
        $labProgressForAnalytics = min(100, round(($labsDoneForAnalytics / $labsTotalForAnalytics) * 100));
        $quizProgressForAnalytics = min(100, round(($quizzesPassedForAnalytics / $quizzesTotalForAnalytics) * 100));
        $quizAverageForAnalytics = (float) ($quizAverage ?? ($quizStats['avg_score'] ?? 0));
        $labAverageForAnalytics = (float) ($labAverage ?? ($labStats['avg_score'] ?? 0));

        $quizInteractionSignalsForAnalytics = (int) data_get($studentAnalyticsSummary ?? [], 'quiz_focus_lost_total', 0)
            + (int) data_get($studentAnalyticsSummary ?? [], 'quiz_flagged_total', 0)
            + (int) data_get($studentAnalyticsSummary ?? [], 'quiz_unanswered_total', 0);
        $activityRecordCountForAnalytics = $lessonHistories->count() + $labHistories->count() + $quizAttempts->count();

        if ($activityRecordCountForAnalytics === 0) {
            $learningStateForAnalytics = [
                'label' => 'Belum mulai',
                'headline' => 'Belum ada aktivitas belajar yang tercatat',
                'description' => 'Siswa belum memiliki rekam materi, praktik lab, atau evaluasi. Langkah awalnya adalah memastikan siswa masuk kelas dan memulai materi pendahuluan.',
                'class' => 'border-slate-200 bg-slate-50 text-slate-600 dark:border-white/10 dark:bg-white/5 dark:text-white/60',
            ];
        } elseif ($adminAdviceScore >= 3) {
            $learningStateForAnalytics = [
                'label' => 'Prioritas tinggi',
                'headline' => 'Perlu pendampingan pada evaluasi dan penyelesaian belajar',
                'description' => 'Aktivitas sudah tercatat, tetapi terdapat beberapa sinyal yang perlu segera ditindaklanjuti agar siswa tidak tertinggal.',
                'class' => 'border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300',
            ];
        } elseif ($adminAdviceScore >= 1) {
            $learningStateForAnalytics = [
                'label' => 'Perlu perhatian',
                'headline' => 'Belajar berjalan, tetapi masih ada indikator yang perlu diperkuat',
                'description' => 'Fokuskan tindak lanjut pada rekomendasi prioritas agar progres materi, praktik, dan evaluasi berkembang secara seimbang.',
                'class' => 'border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300',
            ];
        } elseif ($academicCoverageForPulse >= 80 && $quizzesPassedForAnalytics >= $quizzesTotalForAnalytics && $labsDoneForAnalytics >= $labsTotalForAnalytics) {
            $learningStateForAnalytics = [
                'label' => 'Tuntas',
                'headline' => 'Capaian pembelajaran telah terpenuhi dengan baik',
                'description' => 'Siswa telah menunjukkan penyelesaian materi, praktik, dan evaluasi yang konsisten. Pertahankan ritme belajar dan lakukan pengayaan bila diperlukan.',
                'class' => 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300',
            ];
        } else {
            $learningStateForAnalytics = [
                'label' => 'Berkembang',
                'headline' => 'Proses belajar berjalan secara bertahap',
                'description' => 'Siswa sudah aktif belajar. Lanjutkan penyelesaian indikator yang tersisa agar capaian materi, praktik, dan evaluasi semakin seimbang.',
                'class' => 'border-indigo-200 bg-indigo-50 text-indigo-700 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300',
            ];
        }

        $latestActivityLabelForAnalytics = $latestActivityAt ? $latestActivityAt->diffForHumans() : 'Belum tercatat';
        $latestActivityDateForAnalytics = $latestActivityAt ? $latestActivityAt->translatedFormat('d M Y, H:i') : 'Belum ada riwayat aktivitas';
        $priorityRecommendationForAnalytics = $adminRecommendations->first() ?? [
            'title' => 'Pertahankan ritme belajar',
            'body' => 'Siswa berada pada jalur yang stabil.',
            'tone' => 'emerald',
        ];
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
                <img src="{{ $adminAvatarUrl }}" alt="Admin Avatar" class="w-8 h-8 rounded-full object-cover shadow-lg border border-slate-200 dark:border-white/10 bg-indigo-500">
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

    {{-- MAIN CONTENT --}}
    <main class="flex-1 flex flex-col relative h-full bg-slate-50 dark:bg-[#020617] overflow-hidden transition-colors">
        
        {{-- Background Aesthetics --}}
        <div class="fixed inset-0 pointer-events-none z-0">
            <div class="absolute top-[10%] left-[20%] w-[500px] h-[500px] bg-indigo-300/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
            <div class="absolute bottom-[10%] right-[10%] w-[400px] h-[400px] bg-cyan-300/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02] dark:opacity-[0.04] mix-blend-overlay transition-opacity"></div>
        </div>

        {{-- HEADER PROFILE --}}
        <header class="glass-header flex flex-col justify-end px-6 md:px-10 shrink-0 sticky top-0 z-40 pt-5 transition-colors">
            <div class="flex items-start justify-between w-full mb-3 md:mb-5">
                <div class="flex items-center gap-4">
                    <button @click="sidebarOpen = true" class="md:hidden p-2 bg-slate-200/50 dark:bg-white/5 rounded-lg text-slate-700 dark:text-white hover:bg-slate-200 dark:hover:bg-white/10 transition mt-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
                    <div class="flex items-center gap-4">
                        <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-indigo-500 to-cyan-400 p-[1px] shadow-lg hidden sm:block relative">
                            <img src="{{ $studentAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover rounded-[10px] bg-white dark:bg-[#0f141e]">
                            <div class="absolute -bottom-1 -right-1 w-4 h-4 bg-emerald-500 rounded-full border-2 border-white dark:border-[#020617] flex items-center justify-center text-white" title="Akun Siswa Terverifikasi Aktif">
                                <svg class="w-2.5 h-2.5" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                        </div>
                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition">Dasbor</a></li>
                                    <li><div class="flex items-center"><svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg><span class="text-slate-900 dark:text-white transition-colors">{{ $user->name ?? 'Detail Siswa' }}</span></div></li>
                                </ol>
                            </nav>
                            <h2 class="text-slate-900 dark:text-white font-bold text-lg md:text-xl tracking-tight flex items-center gap-2 leading-none transition-colors">{{ $user->name ?? 'Profil Siswa' }}</h2>
                            <div class="flex items-center gap-2 mt-1.5">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 font-mono transition-colors">{{ $user->email ?? 'No email recorded' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6 mt-1">
                    {{-- Progres keseluruhan --}}
                    <div class="hidden xl:flex flex-col items-end mr-2 cursor-pointer hover:opacity-80 transition-opacity" @click="showGlobalProgressModal = true">
                        <div class="flex items-center gap-1.5 mb-1.5">
                            <p class="text-[9px] uppercase font-extrabold text-slate-500 dark:text-slate-400 tracking-widest transition-colors">Progres Keseluruhan</p>
                            {{-- TOOLTIP INSIGHT MENGGUNAKAN BAWAAN ANDA --}}
                            
                        </div>
                        <div class="flex items-center gap-3">
                            <div class="w-24 h-1.5 bg-slate-200 dark:bg-[#0f141e] rounded-full overflow-hidden border border-slate-300 dark:border-white/5 shadow-inner transition-colors">
                                <div class="h-full bg-cyan-500 dark:bg-cyan-400 rounded-full transition-colors" style="width: {{ $globalProgress ?? 0 }}%"></div>
                            </div>
                            <span class="text-xs font-black text-cyan-600 dark:text-cyan-400 transition-colors">{{ $globalProgress ?? 0 }}%</span>
                        </div>
                    </div>

                    {{-- Menu Ekspor --}}
                    <div class="relative" x-data="{ exportOpen: false }">
                        <div class="flex items-center gap-1.5">
                            <button @click="exportOpen = !exportOpen" @click.away="exportOpen = false" class="p-2.5 sm:px-4 sm:py-2.5 rounded-full sm:rounded-xl bg-slate-200/50 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 border border-transparent dark:border-white/10 text-slate-700 dark:text-white text-xs font-bold transition flex items-center gap-2 shadow-sm dark:shadow-lg">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                <span class="hidden sm:inline">Ekspor</span>
                            </button>
                            
                        </div>

                        <div x-show="exportOpen" class="absolute right-0 mt-2 w-48 bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-xl shadow-lg dark:shadow-[0_15px_50px_rgba(0,0,0,0.9)] z-[9999] overflow-hidden transition-colors" style="display: none;" x-transition>
                            <div class="px-4 py-2 border-b border-slate-100 dark:border-white/5 text-[9px] font-bold text-slate-400 dark:text-white/30 uppercase tracking-widest bg-slate-50 dark:bg-[#0a0e17] transition-colors">Pilih Format</div>
                            <a href="{{ route('admin.student.export.csv', $user->id) }}" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition border-b border-slate-100 dark:border-white/5"><svg class="w-4 h-4 text-emerald-600 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Ekspor CSV</a>
                            <a href="{{ route('admin.student.export.pdf', $user->id) }}" target="_blank" class="flex items-center gap-3 px-4 py-3.5 text-[11px] font-bold text-slate-700 dark:text-white hover:bg-slate-50 dark:hover:bg-white/5 transition"><svg class="w-4 h-4 text-red-600 dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/></svg> Cetak PDF</a>
                        </div>
                    </div>

                    <div class="flex items-center gap-1.5">
                        <button @click="showEdit = true" class="p-2.5 rounded-full bg-indigo-50 dark:bg-indigo-500/10 hover:bg-indigo-100 dark:hover:bg-indigo-500 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white border border-indigo-200 dark:border-indigo-500/20 hover:border-indigo-300 dark:hover:border-indigo-500 transition-all shadow-sm dark:shadow-lg active:scale-95"><svg class="w-4 h-4 transition-transform hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg></button>
                        <div class="insight-tooltip insight-right">
                            
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabs --}}
            <div class="flex gap-6 md:gap-8 mt-2 overflow-x-auto custom-scrollbar w-full relative z-10 items-center pb-2">
                <button @click="activeTab = 'overview'" :class="activeTab === 'overview' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg> Ikhtisar
                </button>
                <button @click="activeTab = 'curriculum'" :class="activeTab === 'curriculum' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg> Pelacakan Kurikulum
                </button>
                <button @click="activeTab = 'history'" :class="activeTab === 'history' ? 'active text-slate-900 dark:text-white' : 'text-slate-500'" class="tab-btn pb-3 text-[11px] font-bold uppercase tracking-wider flex items-center gap-2 whitespace-nowrap">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> Riwayat Aktivitas
                </button>
            </div>
        </header>

        {{-- CONTENT BODY --}}
        <div data-smooth-student-scroll class="smooth-student-scroll flex-1 overflow-y-auto custom-scrollbar p-5 md:p-8 z-10" tabindex="-1">
            <div class="max-w-7xl mx-auto pb-20 relative">

                {{-- =========================================================
                     TAB 1: OVERVIEW 
                     ========================================================= --}}
                <div x-show="activeTab === 'overview'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-12" style="display: none;" x-cloak>
                    
                    {{-- TAB HEADINGS DENGAN TOOLTIP INSIGHT --}}
                    <div class="flex items-center gap-2.5 mb-6 ml-2">
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Ringkasan Profil</h2>
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content text-left">Menampilkan informasi dasar siswa, status kelas, dan statistik singkat dari capaian akademik secara keseluruhan.</div>
                        </div>
                    </div>

                    {{-- PROFIL & STATUS KELAS --}}
                    <div class="animate-fade-in-up mb-8">
                        <div class="glass-card rounded-2xl p-6 md:p-8 flex flex-col md:flex-row items-start md:items-center justify-between gap-6 shadow-sm dark:shadow-lg border border-slate-200 dark:border-white/5 transition-colors">
                            
                            {{-- Informasi Dasar Siswa --}}
                            <div class="flex items-center gap-5 w-full md:w-auto">
                                <div class="w-16 h-16 rounded-2xl bg-indigo-50 dark:bg-indigo-500/20 text-indigo-600 dark:text-indigo-400 flex items-center justify-center text-2xl font-black border border-indigo-200 dark:border-indigo-500/30 shrink-0 transition-colors shadow-inner overflow-hidden relative">
                                    <img src="{{ $studentAvatarUrl }}" alt="Avatar" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <h3 class="text-xl font-bold text-slate-900 dark:text-white tracking-tight transition-colors flex items-center gap-2">
                                        {{ $user->name }}
                                    </h3>
                                    <p class="text-sm text-slate-500 dark:text-slate-400 font-mono mt-0.5 transition-colors">{{ $user->email }}</p>
                                    <div class="flex flex-wrap items-center gap-2 mt-2">
                                        @if($user->institution)
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 uppercase tracking-widest">{{ $user->institution }}</span>
                                        @endif
                                        @if($user->study_program)
                                            <span class="px-2 py-0.5 rounded text-[9px] font-bold bg-slate-100 dark:bg-white/5 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 uppercase tracking-widest">{{ $user->study_program }}</span>
                                        @endif
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-1" title="Tanggal Mendaftar: {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('d F Y, H:i') }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg> 
                                            Bergabung {{ \Carbon\Carbon::parse($user->created_at)->translatedFormat('M Y') }}
                                        </span>
                                        <span class="text-[10px] text-slate-400 dark:text-slate-500 flex items-center gap-1" title="Terakhir diperbarui: {{ \Carbon\Carbon::parse($user->updated_at)->translatedFormat('d F Y, H:i') }}">
                                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Diperbarui {{ \Carbon\Carbon::parse($user->updated_at)->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                            </div>
                            
                            {{-- Class Status (Interactive Edit) --}}
                            <div class="w-full md:w-auto min-w-[280px]">
                                @empty($user->class_group)
                                    <button @click.stop="showEdit = true" class="w-full py-3 rounded-xl bg-indigo-50 dark:bg-indigo-600/20 hover:bg-indigo-100 dark:hover:bg-indigo-600 text-indigo-600 dark:text-indigo-400 hover:text-indigo-800 dark:hover:text-white text-xs font-bold transition-colors border border-indigo-200 dark:border-indigo-500/30">
                                        Set Kelas Manual
                                    </button>
                                @else
                                    <div class="flex flex-col gap-2 w-full relative z-10" @click.stop>
                                        <div @click="showEdit = true" class="flex items-center justify-between gap-4 text-xs text-emerald-700 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-4 py-3 rounded-xl border border-emerald-200 dark:border-emerald-500/20 w-full transition-colors cursor-pointer hover:bg-emerald-100 dark:hover:bg-emerald-500/20 group" title="Ubah data siswa & kelas">
                                            <span class="flex items-center gap-2 font-bold"><span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse shadow-[0_0_8px_#10b981]"></span> Kelas: {{ $user->class_group }}</span>
                                            <div class="flex items-center gap-3">
                                                @if(isset($classGroup))
                                                    <span class="{{ $classGroup->is_active ? 'text-emerald-600 dark:text-emerald-500' : 'text-red-600 dark:text-red-500' }} font-black">{{ $classGroup->is_active ? 'Aktif' : 'Ditutup' }}</span>
                                                @endif
                                                <div class="p-1 rounded-md bg-emerald-200/50 dark:bg-emerald-500/30 text-emerald-700 dark:text-emerald-300 opacity-0 group-hover:opacity-100 transition-opacity">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                                </div>
                                            </div>
                                        </div>
                                        @if(isset($classGroup))
                                            <div class="text-[10px] font-mono text-slate-500 dark:text-slate-400 bg-slate-100 dark:bg-[#020617] border border-slate-200 dark:border-white/5 px-4 py-2.5 rounded-lg shadow-inner flex justify-between items-center w-full transition-colors">
                                                <span>Token Pendaftaran: <span class="font-bold text-slate-900 dark:text-white tracking-widest transition-colors">{{ $classGroup->token }}</span></span>
                                            </div>
                                        @endif
                                    </div>
                                @endempty
                            </div>
                        </div>
                    </div>



                    {{-- RINGKASAN ANALITIK PEMBELAJARAN --}}
                    <section class="space-y-4" aria-labelledby="learning-analytics-heading">
                        <div class="flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-indigo-600 dark:text-indigo-300">Learning Analytics</p>
                                <h2 id="learning-analytics-heading" class="mt-1 text-lg font-black tracking-tight text-slate-900 dark:text-white">Gambaran belajar {{ $user->name }}</h2>
                                <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-white/45">Baca kondisi, kemajuan, hasil evaluasi, dan keterlibatan siswa dari data yang sudah tercatat.</p>
                            </div>
                            <span class="inline-flex w-fit items-center rounded-full border px-3 py-1.5 text-[10px] font-black uppercase tracking-widest {{ $learningStateForAnalytics['class'] }}">{{ $learningStateForAnalytics['label'] }}</span>
                        </div>

                        <div class="grid gap-4 xl:grid-cols-12">
                            <article class="analytics-panel xl:col-span-5 rounded-2xl p-5 md:p-6">
                                <div class="flex items-start justify-between gap-4">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Kondisi pembelajaran</p>
                                        <h3 class="mt-2 text-base font-black leading-6 text-slate-900 dark:text-white">{{ $learningStateForAnalytics['headline'] }}</h3>
                                    </div>
                                    <span class="inline-flex h-10 w-10 shrink-0 items-center justify-center rounded-xl border {{ $learningStateForAnalytics['class'] }}">
                                        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01M10.3 3.86l-8.1 14A2 2 0 003.92 21h16.16a2 2 0 001.72-3.14l-8.1-14a2 2 0 00-3.4 0z"/></svg>
                                    </span>
                                </div>
                                <p class="mt-3 text-xs font-semibold leading-5 text-slate-600 dark:text-white/55">{{ $learningStateForAnalytics['description'] }}</p>
                                <div class="analytics-signal mt-4 rounded-r-xl px-3 py-3 text-indigo-700 dark:text-indigo-300">
                                    <p class="text-[9px] font-black uppercase tracking-widest opacity-70">Prioritas tindak lanjut</p>
                                    <p class="mt-1 text-xs font-black text-slate-900 dark:text-white">{{ $priorityRecommendationForAnalytics['title'] }}</p>
                                    <p class="mt-1 text-[11px] font-semibold leading-5 text-slate-600 dark:text-white/50">{{ $priorityRecommendationForAnalytics['body'] }}</p>
                                </div>
                            </article>

                            <article class="analytics-panel xl:col-span-4 rounded-2xl p-5 md:p-6">
                                <div class="flex items-center gap-4">
                                    <div class="analytics-ring" style="--progress: {{ $globalProgress ?? 0 }}%;">
                                        <div class="analytics-ring__inner text-center">
                                            <strong class="text-lg font-black text-slate-900 dark:text-white">{{ $globalProgress ?? 0 }}%</strong>
                                            <span class="-mt-1 text-[8px] font-black uppercase tracking-widest text-slate-400">Progres</span>
                                        </div>
                                    </div>
                                    <div class="min-w-0">
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Cakupan pembelajaran</p>
                                        <h3 class="mt-2 text-base font-black text-slate-900 dark:text-white">{{ $completedAcademicItemsForPulse }} dari {{ $totalAcademicItemsForPulse }} indikator tercapai</h3>
                                        <p class="mt-1 text-[11px] font-semibold leading-5 text-slate-500 dark:text-white/45">Progres kumulatif dari penyelesaian materi, praktik lab, dan evaluasi yang lulus.</p>
                                    </div>
                                </div>
                                <div class="mt-5 space-y-3">
                                    <div>
                                        <div class="mb-1 flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-white/45"><span>Materi</span><span>{{ $lessonsDoneForAnalytics }}/{{ $lessonsTotalForAnalytics }}</span></div>
                                        <div class="analytics-track"><span class="bg-cyan-500" style="width: {{ $lessonProgressForAnalytics }}%"></span></div>
                                    </div>
                                    <div>
                                        <div class="mb-1 flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-white/45"><span>Praktik lab</span><span>{{ $labsDoneForAnalytics }}/{{ $labsTotalForAnalytics }}</span></div>
                                        <div class="analytics-track"><span class="bg-indigo-500" style="width: {{ $labProgressForAnalytics }}%"></span></div>
                                    </div>
                                    <div>
                                        <div class="mb-1 flex items-center justify-between text-[10px] font-bold text-slate-500 dark:text-white/45"><span>Evaluasi lulus</span><span>{{ $quizzesPassedForAnalytics }}/{{ $quizzesTotalForAnalytics }}</span></div>
                                        <div class="analytics-track"><span class="bg-fuchsia-500" style="width: {{ $quizProgressForAnalytics }}%"></span></div>
                                    </div>
                                </div>
                            </article>

                            <article class="analytics-panel xl:col-span-3 rounded-2xl p-5 md:p-6">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Hasil & keterlibatan</p>
                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl border border-amber-100 bg-amber-50/70 p-3 dark:border-amber-500/20 dark:bg-amber-500/[0.08]">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-amber-700 dark:text-amber-300">Rata-rata kuis</p>
                                        <p class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ number_format($quizAverageForAnalytics, 1) }}<span class="ml-1 text-[10px] text-slate-400">/100</span></p>
                                    </div>
                                    <div class="rounded-xl border border-emerald-100 bg-emerald-50/70 p-3 dark:border-emerald-500/20 dark:bg-emerald-500/[0.08]">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-300">Rata-rata lab</p>
                                        <p class="mt-2 text-xl font-black text-slate-900 dark:text-white">{{ number_format($labAverageForAnalytics, 1) }}<span class="ml-1 text-[10px] text-slate-400">/100</span></p>
                                    </div>
                                </div>
                                <div class="mt-4 space-y-3 border-t border-slate-100 pt-4 dark:border-white/5">
                                    <div class="flex items-start gap-2.5">
                                        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-slate-100 text-slate-500 dark:bg-white/5 dark:text-white/55"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                                        <div><p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Aktivitas terakhir</p><p class="mt-0.5 text-xs font-black text-slate-900 dark:text-white">{{ $latestActivityLabelForAnalytics }}</p><p class="mt-0.5 text-[10px] font-semibold text-slate-500 dark:text-white/45">{{ $latestActivityDateForAnalytics }}</p></div>
                                    </div>
                                    <div class="flex items-start gap-2.5">
                                        <span class="mt-0.5 inline-flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-rose-50 text-rose-600 dark:bg-rose-500/10 dark:text-rose-300"><svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v4m0 4h.01"/></svg></span>
                                        <div><p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Sinyal pengerjaan</p><p class="mt-0.5 text-xs font-black text-slate-900 dark:text-white">{{ $quizInteractionSignalsForAnalytics }} penanda perlu ditinjau</p><p class="mt-0.5 text-[10px] font-semibold text-slate-500 dark:text-white/45">Gabungan ragu-ragu, kehilangan fokus, dan jawaban kosong pada kuis.</p></div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    </section>

                    {{-- INDIKATOR AKADEMIK UTAMA --}}
                    <section class="space-y-4" aria-labelledby="academic-indicators-heading">
                        <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                            <div>
                                <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 dark:text-white/35">Indikator utama</p>
                                <h3 id="academic-indicators-heading" class="mt-1 text-base font-black text-slate-900 dark:text-white">Rincian capaian akademik</h3>
                            </div>
                            <p class="text-[11px] font-semibold text-slate-500 dark:text-white/45">Pilih indikator untuk membuka riwayat data yang terkait.</p>
                        </div>

                        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-5">
                            <button type="button" @click="showLessonModal = true" class="analytics-metric-card group" aria-label="Lihat detail materi yang diselesaikan">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Materi diselesaikan</p>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-cyan-100 bg-cyan-50 text-cyan-600 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ $lessonsDoneForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $lessonsTotalForAnalytics }}</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ max(0, $lessonsTotalForAnalytics - $lessonsDoneForAnalytics) }} materi masih dapat diselesaikan.</p>
                                <div class="analytics-track mt-4"><span class="bg-cyan-500" style="width: {{ $lessonProgressForAnalytics }}%"></span></div>
                            </button>

                            <button type="button" @click="showLabModal = true" class="analytics-metric-card group" aria-label="Lihat detail praktik lab tuntas">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Praktik lab tuntas</p>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-indigo-100 bg-indigo-50 text-indigo-600 dark:border-indigo-500/20 dark:bg-indigo-500/10 dark:text-indigo-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ $labsDoneForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $labsTotalForAnalytics }}</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ max(0, $labsTotalForAnalytics - $labsDoneForAnalytics) }} praktik belum dinyatakan lulus.</p>
                                <div class="analytics-track mt-4"><span class="bg-indigo-500" style="width: {{ $labProgressForAnalytics }}%"></span></div>
                            </button>

                            <button type="button" @click="showQuizModal = true" class="analytics-metric-card group" aria-label="Lihat detail evaluasi yang lulus">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Evaluasi memenuhi KKM</p>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-fuchsia-100 bg-fuchsia-50 text-fuchsia-600 dark:border-fuchsia-500/20 dark:bg-fuchsia-500/10 dark:text-fuchsia-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ $quizzesPassedForAnalytics }}<span class="ml-1 text-sm text-slate-400">/ {{ $quizzesTotalForAnalytics }}</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ max(0, $quizzesTotalForAnalytics - $quizzesPassedForAnalytics) }} evaluasi masih perlu penguatan.</p>
                                <div class="analytics-track mt-4"><span class="bg-fuchsia-500" style="width: {{ $quizProgressForAnalytics }}%"></span></div>
                            </button>

                            <button type="button" @click="showAvgLabModal = true" class="analytics-metric-card group" aria-label="Lihat rata-rata nilai lab">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Rata-rata nilai lab</p>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-emerald-100 bg-emerald-50 text-emerald-600 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ number_format($labAverageForAnalytics, 1) }}<span class="ml-1 text-sm text-slate-400">/100</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $labAverageForAnalytics >= 70 ? 'Rata-rata telah mencapai KKM.' : 'Rata-rata masih di bawah KKM 70.' }}</p>
                                <div class="analytics-track mt-4"><span class="bg-emerald-500" style="width: {{ min(100, max(0, $labAverageForAnalytics)) }}%"></span></div>
                            </button>

                            <button type="button" @click="showAvgQuizModal = true" class="analytics-metric-card group" aria-label="Lihat rata-rata nilai kuis">
                                <div class="flex items-start justify-between gap-3">
                                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Rata-rata nilai kuis</p>
                                    <span class="analytics-metric-icon inline-flex h-9 w-9 items-center justify-center rounded-xl border border-amber-100 bg-amber-50 text-amber-600 dark:border-amber-500/20 dark:bg-amber-500/10 dark:text-amber-300"><svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2m0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg></span>
                                </div>
                                <p class="mt-5 text-2xl font-black text-slate-900 dark:text-white">{{ number_format($quizAverageForAnalytics, 1) }}<span class="ml-1 text-sm text-slate-400">/100</span></p>
                                <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">{{ $quizAverageForAnalytics >= 70 ? 'Rata-rata telah mencapai KKM.' : 'Rata-rata masih di bawah KKM 70.' }}</p>
                                <div class="analytics-track mt-4"><span class="bg-amber-500" style="width: {{ min(100, max(0, $quizAverageForAnalytics)) }}%"></span></div>
                            </button>
                        </div>
                    </section>

                    @php
                        $analytics = $studentAnalyticsSummary ?? [];
                        $chapterRows = collect($chapterPerformance ?? [])->take(4);
                        $strongestTp = $analytics['strongest_outcome'] ?? ($outcomeSummary['strongest'] ?? null);
                        $weakestTp = $analytics['weakest_outcome'] ?? ($outcomeSummary['weakest'] ?? null);
                        $quizAverageDurationForAnalytics = $analytics['quiz_avg_duration_label'] ?? 'Belum ada data';
                        $labAverageDurationForAnalytics = $analytics['lab_avg_duration_label'] ?? 'Belum ada data';
                        $outcomesNeedReviewForAnalytics = (int) ($analytics['outcomes_need_review'] ?? 0);
                    @endphp

                    {{-- ANALISIS HASIL PEMBELAJARAN --}}
                    <section class="grid gap-4 xl:grid-cols-[1.35fr_.65fr]" aria-label="Analisis hasil pembelajaran">
                        <article class="analytics-panel rounded-2xl p-5 md:p-6">
                            <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                                <div>
                                    <p class="text-[10px] font-black uppercase tracking-[0.18em] text-slate-400 dark:text-white/35">Analisis hasil pembelajaran</p>
                                    <h3 class="mt-1 text-base font-black text-slate-900 dark:text-white">Capaian evaluasi per bab</h3>
                                    <p class="mt-1 text-xs font-semibold text-slate-500 dark:text-white/45">Skor terbaik menunjukkan capaian tertinggi pada setiap bab; status lulus menggunakan KKM 70.</p>
                                </div>
                                <span class="inline-flex w-fit rounded-lg border border-slate-200 bg-slate-50 px-3 py-1.5 text-[10px] font-black text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-white/45">{{ $chapterRows->count() }} bab terukur</span>
                            </div>

                            <div class="mt-5 space-y-2.5">
                                @forelse($chapterRows as $row)
                                    @php
                                        $rowScore = (float) ($row['best_score'] ?? 0);
                                        $rowPassed = (bool) ($row['passed'] ?? false);
                                        $rowAttempts = (int) ($row['attempts'] ?? 0);
                                    @endphp
                                    <div class="analytics-performance-row rounded-xl border border-slate-100 p-3 dark:border-white/5">
                                        <div class="flex items-center justify-between gap-3">
                                            <div class="min-w-0">
                                                <p class="truncate text-xs font-black text-slate-800 dark:text-white">{{ $row['label'] ?? 'Bab' }}</p>
                                                <p class="mt-0.5 text-[10px] font-semibold text-slate-500 dark:text-white/45">{{ $rowAttempts }} percobaan · {{ $rowPassed ? 'Memenuhi KKM' : 'Belum memenuhi KKM' }}</p>
                                            </div>
                                            <div class="shrink-0 text-right">
                                                <p class="font-mono text-sm font-black {{ $rowPassed ? 'text-emerald-600 dark:text-emerald-300' : 'text-amber-600 dark:text-amber-300' }}">{{ number_format($rowScore, 0) }}</p>
                                                <p class="text-[9px] font-bold text-slate-400">/100</p>
                                            </div>
                                        </div>
                                        <div class="analytics-track mt-3"><span class="{{ $rowPassed ? 'bg-emerald-500' : 'bg-amber-500' }}" style="width: {{ min(100, max(0, $rowScore)) }}%"></span></div>
                                    </div>
                                @empty
                                    <div class="rounded-xl border border-dashed border-slate-200 px-4 py-8 text-center dark:border-white/10">
                                        <p class="text-xs font-black text-slate-700 dark:text-white">Belum ada evaluasi yang selesai</p>
                                        <p class="mt-1 text-[11px] font-semibold text-slate-500 dark:text-white/45">Data per bab akan muncul setelah siswa mengumpulkan kuis.</p>
                                    </div>
                                @endforelse
                            </div>
                        </article>

                        <div class="space-y-4">
                            <article class="analytics-panel rounded-2xl p-5">
                                <div class="flex items-center justify-between gap-3">
                                    <div>
                                        <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Fokus pemahaman</p>
                                        <h3 class="mt-1 text-sm font-black text-slate-900 dark:text-white">Konsep terkuat dan perlu penguatan</h3>
                                    </div>
                                    <span class="rounded-lg bg-slate-100 px-2.5 py-1 text-[9px] font-black text-slate-500 dark:bg-white/5 dark:text-white/50">{{ $outcomesNeedReviewForAnalytics }} perlu review</span>
                                </div>
                                <div class="mt-4 space-y-3">
                                    <div class="rounded-xl border border-emerald-200 bg-emerald-50/70 p-3 dark:border-emerald-500/20 dark:bg-emerald-500/10">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-300">Terkuat</p>
                                        <p class="mt-1 text-sm font-black text-slate-900 dark:text-white">{{ data_get($strongestTp, 'display_code', 'Belum ada data') }}</p>
                                        <p class="mt-1 text-[11px] font-semibold leading-5 text-slate-600 dark:text-white/55">{{ Str::limit(data_get($strongestTp, 'title', 'Belum ada data tujuan pembelajaran.'), 90) }}</p>
                                    </div>
                                    <div class="rounded-xl border border-amber-200 bg-amber-50/70 p-3 dark:border-amber-500/20 dark:bg-amber-500/10">
                                        <p class="text-[9px] font-black uppercase tracking-widest text-amber-700 dark:text-amber-300">Perlu penguatan</p>
                                        <p class="mt-1 text-sm font-black text-slate-900 dark:text-white">{{ data_get($weakestTp, 'display_code', 'Belum ada data') }}</p>
                                        <p class="mt-1 text-[11px] font-semibold leading-5 text-slate-600 dark:text-white/55">{{ Str::limit(data_get($weakestTp, 'title', 'Belum ada data tujuan pembelajaran.'), 90) }}</p>
                                    </div>
                                </div>
                            </article>

                            <article class="analytics-panel rounded-2xl p-5">
                                <p class="text-[10px] font-black uppercase tracking-widest text-slate-400 dark:text-white/35">Pola pengerjaan</p>
                                <div class="mt-4 grid grid-cols-2 gap-3">
                                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-white/5"><p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Durasi kuis</p><p class="mt-1 text-sm font-black text-slate-900 dark:text-white">{{ $quizAverageDurationForAnalytics }}</p><p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-white/45">Rata-rata</p></div>
                                    <div class="rounded-xl bg-slate-50 p-3 dark:bg-white/5"><p class="text-[9px] font-black uppercase tracking-widest text-slate-400">Durasi lab</p><p class="mt-1 text-sm font-black text-slate-900 dark:text-white">{{ $labAverageDurationForAnalytics }}</p><p class="mt-1 text-[10px] font-semibold text-slate-500 dark:text-white/45">Rata-rata</p></div>
                                </div>
                                <div class="mt-3 rounded-xl border border-rose-100 bg-rose-50/60 px-3 py-3 dark:border-rose-500/20 dark:bg-rose-500/[0.07]">
                                    <p class="text-[10px] font-black text-rose-700 dark:text-rose-300">{{ $quizInteractionSignalsForAnalytics }} sinyal pengerjaan perlu ditinjau</p>
                                    <p class="mt-1 text-[10px] font-semibold leading-5 text-slate-600 dark:text-white/50">Sinyal diperoleh dari ragu-ragu, kehilangan fokus, dan jawaban kosong pada evaluasi.</p>
                                </div>
                            </article>
                        </div>
                    </section>

                    {{-- ARAHAN OTOMATIS ADMIN --}}
                    <div class="glass-card rounded-2xl p-5 md:p-6 transition-colors">
                        <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4 mb-5">
                            <div>
                                <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 dark:text-slate-500 mb-1">Tindak lanjut yang disarankan</p>
                                <h3 class="text-base font-black text-slate-900 dark:text-white transition-colors">Langkah berikutnya untuk {{ $user->name }}</h3>
                                <p class="text-xs text-slate-500 dark:text-slate-400 mt-1">Disusun dari progres materi, hasil kuis, praktik lab, dan sinyal pengerjaan yang tercatat.</p>
                            </div>
                            <span class="px-3 py-1.5 rounded-lg border text-[10px] font-black uppercase tracking-widest {{ $adminRisk['class'] }}">
                                {{ $adminRisk['label'] }}
                            </span>
                        </div>

                        <div class="grid md:grid-cols-2 gap-3">
                            @foreach($adminRecommendations as $idx => $rec)
                                @php
                                    $tone = $rec['tone'] ?? 'cyan';
                                    $toneClass = match($tone) {
                                        'red' => 'border-red-200 dark:border-red-500/20 bg-red-50/70 dark:bg-red-500/[0.06] text-red-600 dark:text-red-400',
                                        'amber' => 'border-amber-200 dark:border-amber-500/20 bg-amber-50/70 dark:bg-amber-500/[0.06] text-amber-600 dark:text-amber-400',
                                        'blue' => 'border-blue-200 dark:border-blue-500/20 bg-blue-50/70 dark:bg-blue-500/[0.06] text-blue-600 dark:text-blue-400',
                                        'emerald' => 'border-emerald-200 dark:border-emerald-500/20 bg-emerald-50/70 dark:bg-emerald-500/[0.06] text-emerald-600 dark:text-emerald-400',
                                        default => 'border-cyan-200 dark:border-cyan-500/20 bg-cyan-50/70 dark:bg-cyan-500/[0.06] text-cyan-600 dark:text-cyan-400',
                                    };
                                @endphp
                                <div class="rounded-2xl border {{ $toneClass }} p-4 transition-colors">
                                    <div class="flex items-start gap-3">
                                        <div class="w-7 h-7 rounded-lg bg-white/70 dark:bg-white/10 flex items-center justify-center text-[11px] font-black shrink-0">
                                            {{ $idx + 1 }}
                                        </div>
                                        <div>
                                            <h4 class="text-sm font-black text-slate-900 dark:text-white">{{ $rec['title'] }}</h4>
                                            <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-400 mt-1">{{ $rec['body'] }}</p>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- DETAIL CHART & SUMMARY --}}
                    <div class="grid lg:grid-cols-3 gap-6">
                        <div class="glass-card rounded-2xl p-6 flex flex-col transition-colors">
                            <h3 class="text-sm font-black text-slate-900 dark:text-white mb-5 tracking-wide transition-colors">Status Kelulusan</h3>
                            <div class="flex-1 flex flex-col justify-center space-y-4">
                                <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-indigo-300 dark:hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 dark:bg-indigo-500/20 border border-indigo-200 dark:border-indigo-500/20 flex items-center justify-center text-indigo-600 dark:text-indigo-400 text-lg shadow-sm dark:shadow-inner group-hover/status:scale-110 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Praktik (Labs)</p>
                                            @php $pLabs = ($totalLabs ?? 4) > 0 ? (($labsCompleted ?? ($labStats['total'] ?? 0)) / ($totalLabs ?? 4)) * 100 : 0; @endphp
                                            <div class="flex items-center justify-between w-full mt-2" title="Kemajuan Praktikum">
                                                <div class="w-full bg-slate-200 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="{{ $pLabs == 100 ? 'bg-emerald-500' : 'bg-indigo-500' }} h-1 rounded-full transition-all duration-1000" style="width: {{ $pLabs }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black transition-colors {{ ($labsCompleted ?? $labStats['total'] ?? 0) >= ($totalLabs ?? 4) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-300' }} shrink-0">{{ $labsCompleted ?? ($labStats['total'] ?? 0) }}/{{ $totalLabs ?? 4 }}</span>
                                </div>
                                <div class="bg-slate-50 dark:bg-white/[0.03] border border-slate-200 dark:border-white/5 rounded-xl p-4 flex items-center justify-between hover:border-fuchsia-300 dark:hover:border-white/10 transition group/status cursor-default">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-fuchsia-50 dark:bg-fuchsia-500/20 border border-fuchsia-200 dark:border-fuchsia-500/20 flex items-center justify-center text-fuchsia-600 dark:text-fuchsia-400 text-lg shadow-sm dark:shadow-inner group-hover/status:scale-110 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Teori (Quizzes)</p>
                                            @php 
                                                $qC = count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70));
                                                $pQuiz = ($totalQuizzes ?? 4) > 0 ? ($qC / ($totalQuizzes ?? 4)) * 100 : 0; 
                                            @endphp
                                            <div class="flex items-center justify-between w-full mt-2" title="Kemajuan Ujian Kuis">
                                                <div class="w-full bg-slate-200 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="{{ $pQuiz == 100 ? 'bg-emerald-500' : 'bg-fuchsia-500' }} h-1 rounded-full transition-all duration-1000" style="width: {{ $pQuiz }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-sm font-black transition-colors {{ $qC >= ($totalQuizzes ?? 4) ? 'text-emerald-600 dark:text-emerald-400' : 'text-slate-400 dark:text-slate-300' }} shrink-0">{{ $qC }}/{{ $totalQuizzes ?? 4 }}</span>
                                </div>
                                <div class="bg-cyan-50/50 dark:bg-cyan-500/5 border border-cyan-200 dark:border-cyan-500/20 rounded-xl p-4 flex items-center justify-between hover:border-cyan-400 dark:hover:border-cyan-500/30 transition group/status cursor-default cursor-pointer" @click="showGlobalProgressModal = true" title="Klik untuk rincian formula progres keseluruhan">
                                    <div class="flex items-center gap-4 w-full">
                                        <div class="w-10 h-10 rounded-xl bg-cyan-100 dark:bg-cyan-500/20 border border-cyan-300 dark:border-cyan-500/30 flex items-center justify-center text-cyan-600 dark:text-cyan-400 text-lg shadow-sm dark:shadow-[0_0_15px_rgba(34,211,238,0.2)] group-hover/status:rotate-12 transition-colors shrink-0">
                                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z" /></svg>
                                        </div>
                                        <div class="flex-1 pr-4">
                                            <p class="text-xs font-bold text-slate-900 dark:text-white transition-colors">Tingkat Akhir</p>
                                            <div class="flex items-center justify-between w-full mt-2">
                                                <div class="w-full bg-cyan-100 dark:bg-[#020617] h-1 rounded-full mr-3 shadow-inner">
                                                    <div class="bg-cyan-500 h-1 rounded-full shadow-[0_0_5px_currentColor] transition-all duration-1000" style="width: {{ $globalProgress ?? 0 }}%"></div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="text-lg font-black text-cyan-600 dark:text-cyan-400 drop-shadow-sm dark:drop-shadow-[0_0_5px_rgba(34,211,238,0.5)] transition-colors shrink-0">{{ $globalProgress ?? 0 }}%</span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-2 glass-card rounded-2xl p-6 relative flex flex-col transition-colors">
                            <div class="flex justify-between items-center mb-6">
                                <div>
                                    <h3 class="text-sm font-black text-slate-900 dark:text-white tracking-wide transition-colors">Tren Performa Lab</h3>
                                    <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">Visualisasi skor dari 10 modul praktik terakhir (Sumbu Y = Skor 0-100, Sumbu X = Nama Modul).</p>
                                </div>
                                <span class="px-3 py-1.5 rounded-lg bg-indigo-50 dark:bg-indigo-500/10 text-indigo-600 dark:text-indigo-400 text-[10px] font-bold border border-indigo-200 dark:border-indigo-500/20 flex items-center gap-1.5 shadow-sm dark:shadow-inner transition-colors cursor-default">
                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 12l3-3 3 3 4-4M8 21l4-4 4 4M3 4h18M4 4h16v12a1 1 0 01-1 1H5a1 1 0 01-1-1V4z"/></svg>
                                    Grafik Nilai
                                </span>
                            </div>
                            <div class="flex-1 min-h-[250px] w-full relative">
                                @if(isset($chartScores) && count($chartScores) > 0)
                                    <canvas id="scoreChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-slate-200 dark:border-white/5 rounded-xl bg-slate-50 dark:bg-white/[0.01] transition-colors">
                                        <svg class="w-8 h-8 text-slate-400 dark:text-slate-600 mb-3 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" /></svg>
                                        <p class="text-xs font-semibold text-slate-500 dark:text-slate-400 transition-colors">Belum ada data grafik</p>
                                        <p class="text-[10px] text-slate-400 dark:text-slate-500 mt-1 transition-colors">Siswa belum menyelesaikan praktik lab dengan status lulus.</p>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TAB 2: CURRICULUM TRACKER --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'curriculum'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;" x-cloak>
                    
                    {{-- HERO TAB 2 --}}
                    <div class="flex items-center gap-2.5 mb-6 ml-2">
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Capaian Kurikulum</h2>
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content text-left">Fitur ini melacak progres materi (slide), kelulusan lab praktikum, dan status evaluasi untuk setiap bab pembelajaran secara mendetail.</div>
                        </div>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-6 mb-8">
                        @foreach($curriculumMap as $index => $chapter)
                            @php
                                $labDone = in_array($chapter['lab_id'], $passedLabIds ?? []);
                                $quizScore = $quizScoresMap['quiz_' . $chapter['quiz_key']] ?? null;
                                $quizPass = ($quizScore !== null && $quizScore >= 70);
                                $totalLessonIds = 0; $completedLessonCount = 0;
                                foreach($chapter['topics'] as $t) {
                                    $totalLessonIds += count($t['ids']);
                                    $completedLessonCount += count(array_intersect($t['ids'], $completedLessonIds ?? []));
                                }
                                $totalWeight = $totalLessonIds + 20; 
                                $currentWeight = $completedLessonCount + ($labDone ? 10 : 0) + ($quizPass ? 10 : 0);
                                $chapterPercent = min(round(($currentWeight / $totalWeight) * 100), 100);
                            @endphp

                            <div class="glass-card rounded-2xl overflow-hidden flex flex-col relative group h-full hover:border-{{ $chapter['color'] }}-400 dark:hover:border-{{ $chapter['color'] }}-500/40 transition-colors" style="animation-delay: {{ $index * 100 }}ms">
                                <div class="absolute top-0 left-0 h-1.5 bg-{{ $chapter['color'] }}-500 transition-all duration-1000 shadow-[0_0_10px_currentColor]" :style="showProgress ? 'width: {{ $chapterPercent }}%' : 'width: 0%'"></div>
                                
                                <div class="px-6 py-5 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-white/[0.01] flex justify-between items-center group-hover:bg-{{ $chapter['color'] }}-50 dark:group-hover:bg-{{ $chapter['color'] }}-500/5 transition-colors">
                                    <div class="flex items-center gap-3">
                                        <span class="text-[10px] font-black px-2.5 py-1 rounded bg-{{ $chapter['color'] }}-100 dark:bg-{{ $chapter['color'] }}-500/10 text-{{ $chapter['color'] }}-700 dark:text-{{ $chapter['color'] }}-400 border border-{{ $chapter['color'] }}-200 dark:border-{{ $chapter['color'] }}-500/20 shadow-sm dark:shadow-inner transition-colors">BAB {{ $chapter['number'] }}</span>
                                        <h4 class="text-sm font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                            {{ $chapter['title'] }}
                                            @if($chapterPercent == 100) <svg class="w-4 h-4 text-emerald-500 dark:text-emerald-400 drop-shadow-sm dark:drop-shadow-[0_0_5px_#10b981] transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg> @endif
                                        </h4>
                                    </div>
                                    <span class="text-xs font-mono font-bold text-{{ $chapter['color'] }}-600 dark:text-{{ $chapter['color'] }}-400 transition-colors" x-data="{ p: 0 }" x-init="let i = setInterval(() => { if(p < {{ $chapterPercent }}) p++; else clearInterval(i); }, 20);" x-text="p + '%'"></span>
                                </div>

                                <div class="p-6 flex-1 flex flex-col gap-6">
                                    <div class="space-y-4 relative">
                                        <div class="absolute left-[7px] top-2 bottom-2 w-px border-l-2 border-dashed border-slate-200 dark:border-white/10 group-hover:border-{{ $chapter['color'] }}-300 dark:group-hover:border-{{ $chapter['color'] }}-500/20 transition-colors"></div>
                                        
                                        @foreach($chapter['topics'] as $topic)
                                            @php 
                                                $missingIds = array_diff($topic['ids'], $completedLessonIds ?? []);
                                                $isTopicDone = empty($missingIds);
                                                $partial = count($topic['ids']) - count($missingIds);
                                                $total = count($topic['ids']);
                                                $progressW = ($partial/$total)*100;
                                            @endphp
                                            <div class="relative pl-6 flex items-center justify-between group/item hover:bg-slate-50 dark:hover:bg-white/[0.02] p-1.5 -ml-1.5 rounded-lg transition-colors cursor-default" title="Tersisa {{ $total - $partial }} slide untuk diselesaikan">
                                                <div class="flex items-center gap-3">
                                                    <div class="absolute left-[3.5px] top-3 w-2.5 h-2.5 rounded-full border-[2px] border-white dark:border-[#0f141e] {{ $isTopicDone ? 'bg-emerald-500 shadow-sm dark:shadow-[0_0_8px_#10b981]' : 'bg-slate-300 dark:bg-slate-700' }} transition-colors duration-300"></div>
                                                    <div class="flex flex-col">
                                                        <span class="text-[13px] font-semibold transition-colors duration-300 {{ $isTopicDone ? 'text-slate-900 dark:text-white' : 'text-slate-500 dark:text-slate-400' }}">{{ $topic['name'] }}</span>
                                                        <div class="flex items-center gap-2 mt-0.5">
                                                            <div class="w-16 h-1 bg-slate-200 dark:bg-white/10 rounded-full overflow-hidden transition-colors">
                                                                <div class="h-full bg-slate-400 dark:bg-slate-500 rounded-full {{ $isTopicDone ? 'bg-emerald-500 dark:bg-emerald-400' : '' }} transition-all duration-1000" style="width: {{ $progressW }}%"></div>
                                                            </div>
                                                            <span class="text-[9px] font-mono {{ $isTopicDone ? 'text-emerald-600 dark:text-emerald-500/70' : 'text-slate-400 dark:text-slate-500' }} transition-colors">{{ $partial }}/{{ $total }} slide</span>
                                                        </div>
                                                    </div>
                                                </div>
                                                @if($isTopicDone) <span class="text-[9px] font-bold text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 px-1.5 py-0.5 rounded uppercase border border-emerald-200 dark:border-emerald-500/20 transition-colors">Done</span> @endif
                                            </div>
                                        @endforeach
                                    </div>

                                    <div class="mt-auto space-y-3 pt-5 border-t border-slate-200 dark:border-white/5 transition-colors">
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors" title="Bobot Praktik: Tambahan persentase jika Lulus">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium transition-colors">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-100 dark:bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-600 dark:text-{{ $chapter['color'] }}-400 text-xs shadow-sm dark:shadow-inner transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                                </div>
                                                {{ $chapter['lab_name'] }}
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded transition-colors {{ $labDone ? 'text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-500/10 border border-emerald-200 dark:border-emerald-500/20' : 'text-slate-500 bg-slate-200 dark:bg-slate-800/50' }}">
                                                {{ $labDone ? 'LULUS' : 'PENDING' }}
                                            </span>
                                        </div>
                                        <div class="flex items-center justify-between p-2 rounded-lg bg-slate-50 dark:bg-white/[0.02] hover:bg-slate-100 dark:hover:bg-white/[0.04] transition-colors" title="Bobot Evaluasi: Tambahan persentase jika Skor >= 70">
                                            <div class="flex items-center gap-2.5 text-xs text-slate-700 dark:text-slate-300 font-medium transition-colors">
                                                <div class="w-6 h-6 rounded bg-{{ $chapter['color'] }}-100 dark:bg-{{ $chapter['color'] }}-500/20 flex items-center justify-center text-{{ $chapter['color'] }}-600 dark:text-{{ $chapter['color'] }}-400 text-xs shadow-sm dark:shadow-inner transition-colors">
                                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4" /></svg>
                                                </div>
                                                Evaluasi Bab
                                            </div>
                                            <span class="text-[10px] font-black px-2 py-0.5 rounded transition-colors {{ $quizPass ? 'text-fuchsia-600 dark:text-fuchsia-400 bg-fuchsia-50 dark:bg-fuchsia-500/10 border border-fuchsia-200 dark:border-fuchsia-500/20' : 'text-slate-500 bg-slate-200 dark:bg-slate-800/50' }}">
                                                {{ $quizScore !== null ? 'SKOR: '.$quizScore : 'BELUM' }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                {{-- ============================== --}}
                {{-- TAB 3: HISTORY LOG --}}
                {{-- ============================== --}}
                <div x-show="activeTab === 'history'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" class="space-y-8" style="display: none;" x-cloak>
                    
                    {{-- HERO TAB 3 --}}
                    <div class="flex items-center gap-2.5 mb-6 ml-2">
                        <h2 class="text-lg font-black text-slate-800 dark:text-white tracking-tight">Log Aktivitas</h2>
                        <div class="insight-tooltip insight-right">
                            <span class="insight-trigger">?</span>
                            <div class="insight-content text-left">Tabel ini menampilkan riwayat lengkap dari setiap percobaan praktik lab dan evaluasi kuis yang telah dikumpulkan.</div>
                        </div>
                    </div>

                    {{-- 1. Lab History --}}
                    <div class="glass-card rounded-2xl relative z-10 flex flex-col transition-colors" style="overflow: visible !important;">
                        {{-- Header Table --}}
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-indigo-600 dark:text-indigo-400">
                                        <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                    </span> Riwayat Praktik Lab
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Log aktivitas penyelesaian modul praktikum yang dikerjakan siswa.</p>
                            </div>
                            
                            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                                <div class="relative w-full sm:w-64 group">
                                    <input type="text" x-model="searchLab" placeholder="Cari lab atau status..." 
                                        class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-indigo-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Data Table --}}
                        <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                            <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 w-10 text-center">#</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Judul Modul</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                Status
                                                <div class="insight-tooltip insight-right">
                                                    <span class="insight-trigger" style="width: 13px; height: 13px; font-size: 8px;">?</span>
                                                    <div class="insight-content font-normal text-left">Lulus, gagal, atau waktu habis (Sesi secara paksa).</div>
                                                </div>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5" title="Skor dalam rentang 0-100">Skor</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Waktu Pengumpulan</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Aksi</th>
                                    </tr>
                                </thead>
                                @forelse($labHistories as $idx => $h)
                                <tbody x-data="{ expanded: false }" class="divide-y divide-slate-200 dark:divide-white/5 transition-colors" x-show="searchLab === '' || '{{ addslashes(strtolower($h->lab->title ?? 'Lab #'.$h->lab_id)) }}'.includes(searchLab.toLowerCase()) || '{{ strtolower($h->status) }}'.includes(searchLab.toLowerCase())">
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/5 transition-colors group table-row">
                                        <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block text-slate-900 dark:text-white font-semibold transition group-hover:text-indigo-600 dark:group-hover:text-indigo-300">{{ $h->lab->title ?? 'Lab #'.$h->lab_id }}</span>
                                        </td>
                                        
                                        @php
                                            $labStatusStr = strtolower($h->status ?? '');
                                            // Deteksi batas waktu
                                            $limitLabSec = isset($h->lab->time_limit) ? $h->lab->time_limit * 60 : (isset($h->lab->duration) ? $h->lab->duration * 60 : 0);
                                            $isLabTimeout = $labStatusStr === 'timeout' || $labStatusStr === 'waktu habis' || (isset($h->is_timeout) && $h->is_timeout == 1) || ($limitLabSec > 0 && $h->duration_seconds >= $limitLabSec) || $h->duration_seconds > 43200;
                                            $isLabLulus = $labStatusStr === 'passed' || $labStatusStr === 'lulus';
                                            
                                            if ($isLabLulus) {
                                                $lStatClass = 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/20';
                                            } elseif ($isLabTimeout) {
                                                $lStatClass = 'bg-amber-50 dark:bg-amber-500/10 text-amber-600 dark:text-amber-400 border border-amber-200 dark:border-amber-500/20';
                                            } else {
                                                $lStatClass = 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border border-red-200 dark:border-red-500/20';
                                            }
                                        @endphp

                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider transition-colors {{ $lStatClass }}">
                                                {{ $isLabTimeout ? 'TIMEOUT' : $h->status }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-slate-900 dark:text-white font-black transition-colors" title="Skor Evaluasi Otomatis">{{ $h->final_score }}</span>
                                        </td>
                                        
                                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 text-xs font-mono transition-colors">
                                            <span class="block text-[11px] text-slate-700 dark:text-slate-300" title="{{ \Carbon\Carbon::parse($h->created_at)->diffForHumans() }}">
                                                {{ \Carbon\Carbon::parse($h->created_at)->format('d M Y, H:i') }} WIB
                                            </span>
                                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                <span class="text-[9px] opacity-70" title="Total durasi yang dihabiskan siswa">Durasi: {{ formatTime($h->duration_seconds) }}</span>
                                                @if($isLabTimeout)
                                                    <span class="px-1 py-[1px] rounded-[4px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[8px] font-bold tracking-wider uppercase border border-amber-200 dark:border-amber-500/30" title="Sesi ditinggalkan atau melebihi batas waktu">Sesi</span>
                                                @endif
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-right">
                                            @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                                <button @click="expanded = !expanded" title="Lihat Snapshot Kode" class="inline-flex items-center justify-center px-4 py-2.5 rounded-xl bg-white dark:bg-[#020617] hover:bg-indigo-600 border border-slate-200 dark:border-white/10 hover:border-indigo-500 text-slate-700 hover:text-white dark:text-white text-[10px] font-bold transition-all shadow-sm dark:shadow-inner hover:shadow-[0_0_15px_rgba(99,102,241,0.5)] group/btn relative z-30 gap-1.5">
                                                    <svg x-show="!expanded" class="w-3.5 h-3.5 text-indigo-500 dark:text-indigo-400 group-hover/btn:text-white transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                                    <svg x-show="expanded" style="display:none;" class="w-3.5 h-3.5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                                                    <span x-text="expanded ? 'Tutup Kode' : 'Lihat Kode'"></span>
                                                </button>
                                            @else
                                                <span class="text-[10px] text-slate-400 dark:text-slate-500 italic px-3 py-1.5 bg-slate-100 dark:bg-slate-800/30 rounded-lg transition-colors border border-slate-200 dark:border-transparent">No Snapshot</span>
                                            @endif
                                        </td>
                                    </tr>
                                    
                                    {{-- Expanded Code Snippet View --}}
                                    @if($h->last_code_snapshot != null && $h->last_code_snapshot != '')
                                    <tr x-show="expanded" x-cloak class="bg-slate-50 dark:bg-[#05080f] shadow-inner transition-colors">
                                        <td colspan="6" class="p-0 border-b border-slate-200 dark:border-white/5">
                                            <div x-show="expanded" x-collapse>
                                                <div class="p-6 md:p-8 bg-slate-100/50 dark:bg-gradient-to-b dark:from-[#0a0d14] dark:to-transparent transition-colors border-t border-slate-200 dark:border-white/5 relative">
                                                    <div class="flex justify-between items-center mb-3 ml-1">
                                                        <p class="text-[10px] font-black text-slate-500 dark:text-slate-400 uppercase tracking-widest transition-colors">Hasil Kode Terakhir</p>
                                                    </div>
                                                    <div class="rounded-xl overflow-hidden border border-slate-300/80 dark:border-slate-800 shadow-xl bg-[#0d1117] transition-colors relative" x-data="{ copied: false }">
                                                        {{-- Copy Button Overlay --}}
                                                        <button @click="navigator.clipboard.writeText(`{{ addslashes($h->last_code_snapshot) }}`); copied = true; setTimeout(() => copied = false, 2000)" 
                                                                class="absolute top-2 right-2 p-1.5 rounded-lg bg-white/10 hover:bg-white/20 text-white/50 hover:text-white transition-all z-10 flex items-center justify-center" title="Salin kode">
                                                            <svg x-show="!copied" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                                                            <svg x-show="copied" style="display:none;" class="w-4 h-4 text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                                                        </button>
                                                        
                                                        <div class="bg-[#1e2330] px-4 py-2.5 border-b border-white/5 flex gap-1.5 items-center transition-colors">
                                                            <div class="w-2.5 h-2.5 rounded-full bg-red-500/80"></div>
                                                            <div class="w-2.5 h-2.5 rounded-full bg-amber-500/80"></div>
                                                            <div class="w-2.5 h-2.5 rounded-full bg-emerald-500/80"></div>
                                                            <span class="ml-3 text-[10px] text-slate-400 font-mono transition-colors">index.html</span>
                                                        </div>
                                                        <div class="p-5 max-h-[300px] overflow-y-auto custom-scrollbar">
                                                            <pre class="text-cyan-50 text-xs font-mono leading-relaxed"><code>{{ $h->last_code_snapshot }}</code></pre>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                    @endif
                                </tbody>
                                @empty
                                <tbody>
                                    <tr>
                                        <td colspan="6" class="text-center py-16 text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl m-4 block border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                                            <svg class="w-8 h-8 mx-auto mb-3 text-slate-300 dark:text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Belum ada aktivitas penyelesaian lab.
                                        </td>
                                    </tr>
                                </tbody>
                                @endforelse
                            </table>
                            @if(count($labHistories) > 0)
                                <div class="p-3 border-t border-slate-200 dark:border-white/5 bg-slate-50/30 dark:bg-[#020617]/20 text-center transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Menampilkan {{ count($labHistories) }} Riwayat Terakhir</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    {{-- 2. Quiz History --}}
                    <div class="glass-card rounded-2xl relative z-10 flex flex-col transition-colors mt-8" style="overflow: visible !important;">
                        {{-- Header Table --}}
                        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50/50 dark:bg-[#020617]/40 flex flex-col md:flex-row justify-between items-start md:items-center gap-6 rounded-t-2xl transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    <span class="text-fuchsia-600 dark:text-fuchsia-400">
                                        <svg class="w-5 h-5 inline-block" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                    </span> Riwayat Evaluasi Kuis
                                </h3>
                                <p class="text-xs text-slate-500 dark:text-white/40 mt-1 transition-colors">Log aktivitas pengambilan kuis dan evaluasi teori.</p>
                            </div>
                            <div class="flex flex-wrap items-center gap-3 w-full md:w-auto relative z-40">
                                <div class="relative w-full sm:w-64 group">
                                    <input type="text" x-model="searchQuiz" placeholder="Cari evaluasi..." 
                                        class="w-full bg-white dark:bg-[#0a0e17] border border-slate-200 dark:border-white/10 rounded-xl pl-10 pr-4 py-2.5 text-xs text-slate-900 dark:text-white focus:border-fuchsia-500 outline-none transition-colors shadow-sm dark:shadow-inner placeholder-slate-400 dark:placeholder-white/20">
                                    <svg class="w-3.5 h-3.5 absolute left-3 top-3 text-slate-400 dark:text-white/30 group-focus-within:text-fuchsia-600 dark:group-focus-within:text-fuchsia-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Data Table --}}
                        <div class="overflow-x-auto relative p-0 sm:p-6 pt-0 border-t border-slate-200 dark:border-white/5 sm:border-none transition-colors">
                            <table class="w-full text-sm text-left whitespace-nowrap sm:whitespace-normal border border-slate-200 dark:border-white/5 rounded-xl shadow-inner bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors duration-500">
                                <thead class="bg-slate-100 dark:bg-[#0f141e] text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold border-b border-slate-200 dark:border-white/5 sticky top-0 z-20 transition-colors">
                                    <tr>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 w-10 text-center" title="Diurutkan dari yang terbaru">#</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5">Judul Evaluasi</th>
                                        <th class="px-6 py-4 border-b border-slate-200 dark:border-white/5 text-center">
                                            <div class="flex items-center justify-center gap-1.5">
                                                Status
                                                <div class="insight-tooltip insight-right ml-1">
                                                    <span class="insight-trigger" style="width: 13px; height: 13px; font-size: 8px;">?</span>
                                                    <div class="insight-content font-normal text-left">Kriteria kelulusan adalah skor >= 70. Jika kurang dari itu, maka dianggap Gagal/Remedial.</div>
                                                </div>
                                            </div>
                                        </th>
                                        <th class="px-6 py-4 text-center border-b border-slate-200 dark:border-white/5" title="Skor dalam rentang 0-100">Skor</th>
                                        <th class="px-6 py-4 text-right border-b border-slate-200 dark:border-white/5">Waktu Pengumpulan</th>
                                    </tr>
                                </thead>
                                <tbody class="divide-y divide-slate-200 dark:divide-white/5 transition-colors">
                                    @forelse($quizAttempts as $idx => $q)
                                    @php 
                                        $qName = $q->chapter_id == '99' ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$q->chapter_id; 
                                        $quizStatusStr = strtolower($q->status ?? '');
                                        
                                        $limitQuizSec = isset($q->time_limit) ? $q->time_limit * 60 : 0;
                                        $qDuration = $q->time_spent_seconds ?? 0;
                                        $isQuizTimeout = $quizStatusStr === 'timeout' || $quizStatusStr === 'waktu habis' || (isset($q->is_timeout) && $q->is_timeout == 1) || ($limitQuizSec > 0 && $qDuration >= $limitQuizSec) || $qDuration > 43200;
                                        $isQuizLulus = $q->score >= 70;
                                        $quizOutcomeAnalytics = $quizOutcomeAnalyticsByAttempt[$q->id] ?? [];

                                        if ($isQuizLulus) {
                                            $qStatClass = 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400';
                                        } elseif ($isQuizTimeout) {
                                            $qStatClass = 'bg-amber-100 text-amber-700 dark:bg-amber-500/20 dark:text-amber-400';
                                        } else {
                                            $qStatClass = 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400';
                                        }

                                        $quizReviewPayload = [
                                            'student' => $user->name,
                                            'title' => $qName,
                                            'score' => $q->score ?? 0,
                                            'status' => $isQuizLulus ? 'Lulus' : ($isQuizTimeout ? 'Waktu Habis' : 'Remedial'),
                                            'date' => \Carbon\Carbon::parse($q->completed_at ?? $q->created_at)->format('d M Y, H:i'),
                                            'duration' => formatTime($qDuration),
                                            'answered' => (int) ($q->answered_count ?? 0),
                                            'unanswered' => (int) ($q->unanswered_count ?? 0),
                                            'flagged' => (int) ($q->flagged_count ?? 0),
                                            'focusLost' => (int) ($q->focus_lost_count ?? 0),
                                            'feedbackLevel' => $q->feedback_level ?? ($isQuizLulus ? 'Lulus' : 'Perlu Penguatan'),
                                            'feedbackMessage' => $q->feedback_message ?? ($isQuizLulus ? 'Siswa sudah mencapai KKM. Tinjauan ulang tetap dapat membantu memperkuat bagian yang masih salah.' : 'Siswa belum mencapai KKM. Perlu penguatan materi dan percobaan ulang.'),
                                            'reflectionNote' => $q->reflection_note ?? '',
                                            'outcomeDecision' => $quizOutcomeAnalytics['decision'] ?? 'Belum ada data TP.',
                                            'outcomeSummary' => $quizOutcomeAnalytics['summary_text'] ?? 'Belum ada analitik tujuan pembelajaran.',
                                            'outcomes' => collect($quizOutcomeAnalytics['outcomes'] ?? [])->values()->all(),
                                        ];
                                    @endphp
                                    <tr class="hover:bg-slate-50 dark:hover:bg-white/[0.02] transition-colors group table-row" x-show="searchQuiz === '' || '{{ addslashes(strtolower($qName)) }}'.includes(searchQuiz.toLowerCase())">
                                        <td class="px-6 py-4 text-center text-slate-400 font-mono text-xs">{{ $idx + 1 }}</td>
                                        <td class="px-6 py-4">
                                            <span class="block text-slate-900 dark:text-white font-semibold transition group-hover:text-fuchsia-600 dark:group-hover:text-fuchsia-300">{{ $qName }}</span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="px-2.5 py-1 rounded text-[10px] font-black uppercase tracking-wider transition-colors {{ $qStatClass }}">
                                                {{ $isQuizTimeout ? 'TIMEOUT' : ($isQuizLulus ? 'LULUS' : 'GAGAL') }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <span class="text-slate-900 dark:text-white font-black transition-colors" title="Skor Tertinggi Evaluasi">{{ $q->score }}</span>
                                            <button type="button"
                                                    @click="selectedQuizReview = {{ \Illuminate\Support\Js::from($quizReviewPayload) }}; showQuizReviewModal = true"
                                                    class="block mx-auto mt-1 text-[9px] font-black uppercase tracking-widest text-fuchsia-600 dark:text-fuchsia-400 hover:text-fuchsia-700 dark:hover:text-fuchsia-300 transition-colors focus:outline-none">
                                                Tinjau
                                            </button>
                                        </td>
                                        <td class="px-6 py-4 text-right text-slate-500 dark:text-slate-400 text-xs font-mono transition-colors">
                                            <span class="block text-[11px] text-slate-700 dark:text-slate-300" title="{{ \Carbon\Carbon::parse($q->created_at)->diffForHumans() }}">
                                                {{ \Carbon\Carbon::parse($q->created_at)->format('d M Y, H:i') }} WIB
                                            </span>
                                            <div class="flex items-center justify-end gap-1.5 mt-0.5">
                                                <span class="text-[9px] opacity-70" title="Total durasi yang dihabiskan siswa">Durasi: {{ formatTime($qDuration) }}</span>
                                                @if($isQuizTimeout)
                                                    <span class="px-1 py-[1px] rounded-[4px] bg-amber-100 dark:bg-amber-500/20 text-amber-700 dark:text-amber-400 text-[8px] font-bold tracking-wider uppercase border border-amber-200 dark:border-amber-500/30" title="Sesi ditinggalkan atau melebihi batas waktu evaluasi">Sesi</span>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="5" class="text-center py-16 text-slate-500 dark:text-white/30 text-xs italic bg-slate-50 dark:bg-[#0a0e17]/50 rounded-xl m-4 block border border-dashed border-slate-300 dark:border-white/10 transition-colors">
                                            <svg class="w-8 h-8 mx-auto mb-3 text-slate-300 dark:text-white/10" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            Belum ada data pengambilan kuis.
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                            @if(count($quizAttempts) > 0)
                                <div class="p-3 border-t border-slate-200 dark:border-white/5 bg-slate-50/30 dark:bg-[#020617]/20 text-center transition-colors">
                                    <span class="text-[9px] font-bold text-slate-400 dark:text-slate-500 uppercase tracking-widest transition-colors">Menampilkan {{ count($quizAttempts) }} Riwayat Terakhir</span>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </main>

    {{-- ==================== MODALS (INSIGHTS) ==================== --}}

    {{-- MODAL INFO AKADEMIK 1: MATERI (LESSON) --}}
    <div x-show="showLessonModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showLessonModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Materi Dibaca</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa menyelesaikan <span class="font-bold text-slate-900 dark:text-white">{{ count($completedLessonIds ?? []) }} dari {{ $totalLessons ?? 65 }}</span> materi.</p>
                </div>
                <button @click="showLessonModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-4">
                @foreach($curriculumMap as $chapter)
                    <div class="bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl p-5 transition-colors">
                        <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Bab {{ $chapter['number'] }}: {{ $chapter['title'] }}</h4>
                        <div class="space-y-3">
                            @foreach($chapter['topics'] as $topic)
                                @php 
                                    $intersect = array_intersect($topic['ids'], $completedLessonIds ?? []);
                                    $doneCount = count($intersect);
                                    $totalCount = count($topic['ids']);
                                    $isDone = $doneCount === $totalCount;
                                @endphp
                                @if($doneCount > 0)
                                <div class="flex justify-between items-center text-sm">
                                    <span class="text-slate-600 dark:text-slate-300 font-medium transition-colors">
                                        {{ $topic['name'] }}
                                    </span>
                                    <span class="font-semibold text-slate-900 dark:text-white transition-colors">{{ $doneCount }}/{{ $totalCount }}</span>
                                </div>
                                @endif
                            @endforeach
                            @if(count(array_intersect(Arr::collapse(array_column($chapter['topics'], 'ids')), $completedLessonIds ?? [])) === 0)
                                <p class="text-sm text-slate-400 dark:text-slate-500 italic transition-colors">Belum ada materi yang dibaca.</p>
                            @endif
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
    
    {{-- MODAL INFO AKADEMIK 2: LAB LULUS --}}
    <div x-show="showLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Kelulusan Praktik</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa lulus <span class="font-bold text-slate-900 dark:text-white">{{ count($passedLabIds ?? []) }} dari {{ $totalLabs ?? 4 }}</span> modul.</p>
                </div>
                <button @click="showLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @php $passedLabsList = $labHistories->where('status', 'passed'); @endphp
                @forelse($passedLabsList as $lab)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $lab->lab->title ?? 'Modul Lab #'.$lab->lab_id }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">
                            {{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $lab->final_score }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-sm text-slate-500 dark:text-slate-400 italic transition-colors">Belum ada modul yang lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
    
    {{-- MODAL INFO AKADEMIK 3: KUIS LULUS --}}
    <div x-show="showQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Detail Evaluasi Lulus</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Siswa lulus <span class="font-bold text-slate-900 dark:text-white">{{ count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70)) }} dari {{ $totalQuizzes ?? 4 }}</span> evaluasi.</p>
                </div>
                <button @click="showQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="max-h-[50vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @php $passedQuizzesList = $quizAttempts->where('score', '>=', 70); @endphp
                @forelse($passedQuizzesList as $quiz)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <p class="text-sm font-semibold text-slate-900 dark:text-white transition-colors">{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$quiz->chapter_id }}</p>
                        <p class="text-xs text-slate-500 dark:text-slate-400 mt-1 transition-colors">
                            {{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y, H:i') }}
                        </p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg font-bold text-slate-900 dark:text-white transition-colors">{{ $quiz->score }}</span>
                    </div>
                </div>
                @empty
                <div class="text-center py-10">
                    <p class="text-sm text-slate-500 dark:text-slate-400 italic transition-colors">Belum ada evaluasi yang lulus.</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 4: AVG LAB --}}
    <div x-show="showAvgLabModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showAvgLabModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Analisis Praktik</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Total percobaan: <span class="font-bold text-slate-900 dark:text-white">{{ count($labHistories) }} kali</span></p>
                </div>
                <button @click="showAvgLabModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Tertinggi</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $labHistories->max('final_score') ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Terendah</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $labHistories->min('final_score') ?? 0 }}</p>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Riwayat Percobaan</h4>
            <div class="max-h-[35vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @forelse($labHistories as $lab)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white block">{{ $lab->lab->title ?? 'Lab #'.$lab->lab_id }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($lab->created_at)->format('d M Y') }}</span>
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white transition-colors">{{ $lab->final_score }}</span>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-slate-500 dark:text-slate-400 italic">Belum ada percobaan dilakukan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 5: AVG QUIZ --}}
    <div x-show="showAvgQuizModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showAvgQuizModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Analisis Evaluasi</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Total percobaan: <span class="font-bold text-slate-900 dark:text-white">{{ count($quizAttempts) }} kali</span></p>
                </div>
                <button @click="showAvgQuizModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            <div class="grid grid-cols-2 gap-4 mb-6">
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Tertinggi</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $quizAttempts->max('score') ?? 0 }}</p>
                </div>
                <div class="bg-slate-50 dark:bg-[#1d1d1f] p-5 rounded-2xl transition-colors">
                    <p class="text-xs text-slate-500 dark:text-slate-400 font-semibold mb-1 transition-colors">Skor Terendah</p>
                    <p class="text-2xl font-bold text-slate-900 dark:text-white transition-colors">{{ $quizAttempts->min('score') ?? 0 }}</p>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Riwayat Percobaan</h4>
            <div class="max-h-[35vh] overflow-y-auto custom-scrollbar pr-2 space-y-3">
                @forelse($quizAttempts as $quiz)
                <div class="flex justify-between items-center p-4 bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl transition-colors">
                    <div>
                        <span class="font-semibold text-sm text-slate-900 dark:text-white block">{{ $quiz->chapter_id == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab '.$quiz->chapter_id }}</span>
                        <span class="text-xs text-slate-500 dark:text-slate-400">{{ \Carbon\Carbon::parse($quiz->created_at)->format('d M Y') }}</span>
                    </div>
                    <span class="font-bold text-slate-900 dark:text-white transition-colors">{{ $quiz->score }}</span>
                </div>
                @empty
                <div class="text-center py-6 text-sm text-slate-500 dark:text-slate-400 italic">Belum ada percobaan dilakukan.</div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- MODAL HERO TINJAUAN KUIS SISWA --}}
    <div x-show="showQuizReviewModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/90 backdrop-blur-md transition-colors" @click="showQuizReviewModal = false"></div>
        <div class="relative w-full max-w-3xl overflow-hidden bg-white dark:bg-[#0f141e] border border-fuchsia-200 dark:border-fuchsia-500/20 rounded-3xl shadow-2xl transition-colors" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0">
            <div class="relative p-6 md:p-8 bg-gradient-to-br from-fuchsia-600 via-purple-600 to-cyan-600 text-white">
                <div class="absolute inset-0 bg-black/10"></div>
                <button @click="showQuizReviewModal = false" class="absolute top-5 right-5 z-10 p-2 rounded-full bg-white/10 hover:bg-white/20 transition focus:outline-none" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <div class="relative z-10 max-w-xl">
                    <p class="text-[10px] uppercase tracking-[0.26em] font-black text-white/65 mb-2">Tinjauan Hasil Siswa</p>
                    <h3 class="text-2xl md:text-3xl font-black leading-tight" x-text="selectedQuizReview?.title || 'Evaluasi'"></h3>
                    <p class="text-sm text-white/75 mt-2">Siswa: <span class="font-bold" x-text="selectedQuizReview?.student || '-'"></span></p>
                    <p class="text-sm text-white/75 mt-3" x-text="selectedQuizReview?.feedbackMessage || 'Ringkasan pengerjaan siswa tersedia untuk ditinjau.'"></p>
                </div>
                <div class="absolute right-8 bottom-[-22px] z-10 hidden md:block text-right">
                    <div class="text-7xl font-black leading-none" x-text="selectedQuizReview?.score ?? 0"></div>
                    <div class="text-[10px] uppercase tracking-widest font-bold text-white/70">Skor Akhir</div>
                </div>
            </div>

            <div class="p-6 md:p-8">
                <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mb-6">
                    <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4 transition-colors">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Status</p>
                        <p class="text-lg font-black mt-1" :class="(selectedQuizReview?.score ?? 0) >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="selectedQuizReview?.status || '-'"></p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4 transition-colors">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Durasi</p>
                        <p class="text-lg font-black mt-1 text-slate-900 dark:text-white font-mono" x-text="selectedQuizReview?.duration || '-'"></p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4 transition-colors">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Terjawab</p>
                        <p class="text-lg font-black mt-1 text-slate-900 dark:text-white"><span x-text="selectedQuizReview?.answered ?? 0"></span> Soal</p>
                    </div>
                    <div class="rounded-2xl bg-slate-50 dark:bg-white/[0.04] border border-slate-200 dark:border-white/10 p-4 transition-colors">
                        <p class="text-[10px] uppercase tracking-widest text-slate-400 font-bold">Ragu-ragu</p>
                        <p class="text-lg font-black mt-1 text-amber-600 dark:text-amber-400"><span x-text="selectedQuizReview?.flagged ?? 0"></span> Soal</p>
                    </div>
                </div>

                <div class="rounded-2xl bg-slate-50 dark:bg-[#020617]/60 border border-slate-200 dark:border-white/10 p-5 transition-colors">
                    <p class="text-sm font-bold text-slate-900 dark:text-white" x-text="selectedQuizReview?.feedbackLevel || 'Ringkasan'"></p>
                    <div class="grid sm:grid-cols-3 gap-3 mt-4 text-xs">
                        <div class="text-slate-500 dark:text-slate-400">Dikumpulkan<br><span class="font-bold text-slate-900 dark:text-white" x-text="selectedQuizReview?.date || '-'"></span></div>
                        <div class="text-slate-500 dark:text-slate-400">Soal Kosong<br><span class="font-bold text-slate-900 dark:text-white" x-text="selectedQuizReview?.unanswered ?? 0"></span></div>
                        <div class="text-slate-500 dark:text-slate-400">Fokus Terganggu<br><span class="font-bold text-slate-900 dark:text-white" x-text="selectedQuizReview?.focusLost ?? 0"></span></div>
                    </div>
                    <div class="mt-5 rounded-xl border border-fuchsia-200 dark:border-fuchsia-500/20 bg-fuchsia-50/70 dark:bg-fuchsia-500/10 p-4">
                        <p class="text-[10px] uppercase tracking-widest font-black text-fuchsia-700 dark:text-fuchsia-300">Keputusan TP</p>
                        <p class="text-sm font-black text-slate-900 dark:text-white mt-1" x-text="selectedQuizReview?.outcomeDecision || 'Belum ada data TP.'"></p>
                        <p class="text-xs leading-relaxed text-slate-600 dark:text-slate-300 mt-2" x-text="selectedQuizReview?.outcomeSummary || 'Belum ada analitik tujuan pembelajaran.'"></p>
                    </div>
                    <template x-if="selectedQuizReview?.outcomes?.length">
                        <div class="mt-5 rounded-2xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/[0.03] p-4">
                            <div class="flex items-center justify-between gap-3 mb-3">
                                <div>
                                    <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 dark:text-slate-500">Analitik TP Siswa</p>
                                    <h4 class="text-sm font-black text-slate-900 dark:text-white mt-1">Capaian per Tujuan Pembelajaran</h4>
                                </div>
                                <span class="text-[10px] font-black text-slate-400 dark:text-slate-500" x-text="selectedQuizReview.outcomes.length + ' TP'"></span>
                            </div>
                            <template x-for="tp in selectedQuizReview.outcomes" :key="tp.key">
                                <div class="rounded-xl border p-3 mb-3 last:mb-0 transition-colors"
                                     :class="{
                                        'border-emerald-200 bg-emerald-50 dark:border-emerald-500/20 dark:bg-emerald-500/10': tp.tone === 'emerald',
                                        'border-cyan-200 bg-cyan-50 dark:border-cyan-500/20 dark:bg-cyan-500/10': tp.tone === 'cyan',
                                        'border-amber-200 bg-amber-50 dark:border-amber-500/20 dark:bg-amber-500/10': tp.tone === 'amber',
                                        'border-red-200 bg-red-50 dark:border-red-500/20 dark:bg-red-500/10': tp.tone === 'red',
                                        'border-slate-200 bg-slate-50 dark:border-white/10 dark:bg-white/[0.03]': !['emerald','cyan','amber','red'].includes(tp.tone)
                                     }">
                                    <div class="flex items-start justify-between gap-3">
                                        <div class="min-w-0">
                                            <p class="text-[10px] uppercase tracking-widest font-black text-slate-500 dark:text-slate-400" x-text="tp.display_code || tp.code || 'TP'"></p>
                                            <h4 class="text-sm font-black text-slate-900 dark:text-white mt-1 leading-snug" x-text="tp.title || 'Tujuan Pembelajaran'"></h4>
                                        </div>
                                        <span class="text-2xl font-black text-slate-900 dark:text-white" x-text="(tp.mastery_percent ?? 0) + '%'"></span>
                                    </div>
                                    <div class="mt-3 h-1.5 rounded-full bg-white/70 dark:bg-black/20 overflow-hidden">
                                        <div class="h-full rounded-full"
                                             :class="{
                                                'bg-emerald-500': tp.tone === 'emerald',
                                                'bg-cyan-500': tp.tone === 'cyan',
                                                'bg-amber-500': tp.tone === 'amber',
                                                'bg-red-500': tp.tone === 'red',
                                                'bg-slate-400': !['emerald','cyan','amber','red'].includes(tp.tone)
                                             }"
                                             :style="'width: ' + Math.min(100, Math.max(0, tp.mastery_percent ?? 0)) + '%'"></div>
                                    </div>
                                    <div class="mt-3 grid gap-2 text-xs leading-relaxed text-slate-700 dark:text-slate-300">
                                        <p><span class="font-black">Data:</span> <span x-text="tp.activity_data || '-'"></span></p>
                                        <p><span class="font-black">Arahan Materi:</span> <span x-text="tp.material_direction || '-'"></span></p>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </template>
                    <template x-if="selectedQuizReview?.reflectionNote">
                        <div class="mt-5 rounded-xl border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/[0.03] p-4">
                            <p class="text-[10px] uppercase tracking-widest font-black text-slate-400 dark:text-slate-500 mb-2">Catatan Siswa</p>
                            <p class="text-sm leading-relaxed text-slate-700 dark:text-slate-300 italic">"<span x-text="selectedQuizReview.reflectionNote"></span>"</p>
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL INFO AKADEMIK 6: GLOBAL PROGRESS --}}
    <div x-show="showGlobalProgressModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showGlobalProgressModal = false"></div>
        <div class="relative w-full max-w-lg bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
            <div class="flex justify-between items-start mb-6">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Progres Keseluruhan</h3>
                    <p class="text-slate-500 dark:text-slate-400 text-sm mt-1 transition-colors">Kalkulasi tingkat penyelesaian akhir.</p>
                </div>
                <button @click="showGlobalProgressModal = false" class="text-slate-400 hover:text-slate-700 dark:text-slate-500 dark:hover:text-white transition-colors p-2" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            @php
                $calcLessonsDone = $lessonsCompleted ?? count($completedLessonIds ?? []);
                $calcLessonsTotal = $totalLessons ?? 65;
                
                $calcLabsDone = $labsCompleted ?? ($labStats['total'] ?? 0);
                $calcLabsTotal = $totalLabs ?? 4;
                
                $calcQuizzesDone = $chaptersPassed ?? count(array_filter($quizScoresMap ?? [], fn($s) => $s >= 70));
                $calcQuizzesTotal = $totalQuizzes ?? 4;
                
                $calcTotalDone = $calcLessonsDone + $calcLabsDone + $calcQuizzesDone;
                $calcTotalTarget = $calcLessonsTotal + $calcLabsTotal + $calcQuizzesTotal;
                
                $gProg = $globalProgress ?? 0;
            @endphp

            <div class="bg-slate-50 dark:bg-[#1d1d1f] rounded-2xl p-6 mb-8 transition-colors">
                <div class="flex justify-between items-center mb-4">
                    <span class="text-sm font-semibold text-slate-500 dark:text-slate-400 transition-colors">Penyelesaian</span>
                    <span class="text-3xl font-bold text-slate-900 dark:text-white transition-colors">{{ $gProg }}%</span>
                </div>
                <div class="w-full bg-slate-200 dark:bg-slate-800 h-2 rounded-full overflow-hidden transition-colors">
                    <div class="h-full bg-blue-500 rounded-full transition-all duration-1000" style="width: {{ $gProg }}%"></div>
                </div>
            </div>

            <h4 class="text-sm font-semibold text-slate-900 dark:text-white mb-4 transition-colors">Rincian Komponen</h4>
            <div class="space-y-4">
                <div class="flex justify-between items-center text-sm transition-colors">
                    <span class="font-medium text-slate-700 dark:text-slate-300">Materi Teori</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $calcLessonsDone }} / {{ $calcLessonsTotal }}</span>
                </div>
                <div class="w-full h-px bg-slate-200 dark:bg-white/5"></div>
                <div class="flex justify-between items-center text-sm transition-colors">
                    <span class="font-medium text-slate-700 dark:text-slate-300">Praktik Selesai</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $calcLabsDone }} / {{ $calcLabsTotal }}</span>
                </div>
                <div class="w-full h-px bg-slate-200 dark:bg-white/5"></div>
                <div class="flex justify-between items-center text-sm transition-colors">
                    <span class="font-medium text-slate-700 dark:text-slate-300">Evaluasi Lulus</span>
                    <span class="font-semibold text-slate-900 dark:text-white">{{ $calcQuizzesDone }} / {{ $calcQuizzesTotal }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- MODAL EDIT DATA SISWA (ADMIN) --}}
    <div x-show="showEdit" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" class="fixed inset-0 z-[100] flex items-center justify-center p-4" style="display: none;" x-cloak>
        <div class="absolute inset-0 bg-slate-900/40 dark:bg-[#020617]/80 backdrop-blur-sm transition-colors" @click="showEdit = false"></div>
        <div class="relative w-full max-w-xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl p-6 md:p-8 transition-colors shadow-2xl" @click.stop>
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h3 class="text-xl font-bold text-slate-900 dark:text-white transition-colors">Perbarui Data Siswa</h3>
                </div>
                <button @click="showEdit = false" class="text-slate-400 dark:text-slate-500 hover:text-slate-800 dark:hover:text-white transition bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:bg-white/10 p-2 rounded-full" title="Tutup">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            
            <form action="{{ route('admin.student.update', $user->id) }}" method="POST" enctype="multipart/form-data" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf 
                @method('PUT')
                
                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Profile Photo <span class="text-slate-400 dark:text-slate-500 font-normal">(Opsional)</span></label>
                    <input type="file" name="avatar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-slate-100 dark:file:bg-white/5 file:text-slate-700 dark:file:text-slate-300 hover:file:bg-slate-200 dark:hover:file:bg-white/10 cursor-pointer transition-colors">
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Nama Lengkap</label>
                        <input type="text" name="name" value="{{ $user->name }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors" required>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Alamat Email</label>
                        <input type="email" name="email" value="{{ $user->email }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors" required>
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Grup Kelas</label>
                        <div class="relative">
                            <select name="class_group" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none appearance-none transition-colors cursor-pointer">
                                <option value="" class="text-slate-400" {{ empty($user->class_group) ? 'selected' : '' }}>-- Pilih Kelas --</option>
                                @foreach($availableClasses ?? [] as $cls)
                                    <option value="{{ $cls->name }}" class="text-slate-900 dark:text-white" {{ trim($user->class_group) === trim($cls->name) ? 'selected' : '' }}>
                                        {{ $cls->name }} {{ $cls->major ? ' - '.$cls->major : '' }}
                                    </option>
                                @endforeach
                            </select>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-slate-400">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Phone Number</label>
                        <input type="text" name="phone" value="{{ $user->phone }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                    </div>
                </div>

                <div class="grid grid-cols-2 gap-5">
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Institution</label>
                        <input type="text" name="institution" value="{{ $user->institution }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                    </div>
                    <div>
                        <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Study Program</label>
                        <input type="text" name="study_program" value="{{ $user->study_program }}" class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                    </div>
                </div>

                <div>
                    <label class="text-xs font-semibold text-slate-700 dark:text-slate-300 mb-2 block transition-colors">Atur Ulang Kata Sandi <span class="text-slate-400 dark:text-slate-500 font-normal">(Kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" placeholder="Masukkan password baru..." class="w-full bg-slate-50 dark:bg-[#1d1d1f] border border-slate-200 dark:border-white/10 rounded-xl px-4 py-3 text-sm text-slate-900 dark:text-white focus:border-blue-500 outline-none transition-colors">
                </div>
                
                <div class="flex justify-between items-center mt-10 pt-6 border-t border-slate-200 dark:border-white/5 transition-colors">
                    <button type="button" @click="confirmHapus()" class="text-sm font-semibold text-red-500 hover:text-red-600 transition-colors px-3 py-2 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10">
                        Hapus Akun
                    </button>

                    <div class="flex gap-3">
                        <button type="button" @click="showEdit = false" class="px-6 py-3 rounded-xl text-sm font-semibold text-slate-600 dark:text-slate-300 hover:bg-slate-100 dark:hover:bg-white/5 transition-colors" :disabled="isSubmitting">Batal</button>
                        <button type="submit" class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 text-white text-sm font-semibold shadow-md transition-colors flex items-center gap-2" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''" :disabled="isSubmitting">
                            <span x-text="isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan'"></span>
                        </button>
                    </div>
                </div>
            </form>
            
            <form id="delete-student-form" action="{{ route('admin.users.delete', $user->id) }}" method="POST" class="hidden">
                @csrf @method('DELETE')
            </form>
        </div>
    </div>

    {{-- SCRIPTS KHUSUS ADMIN DETAIL --}}
    @if(session('success')) <script> document.addEventListener('DOMContentLoaded', () => { Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3000, background: document.documentElement.classList.contains('dark') ? '#0f141e' : '#fff', color: document.documentElement.classList.contains('dark') ? '#fff' : '#1e293b', iconColor: '#10b981' }); }); </script> @endif
    
    <script>
        // SCRIPT THEME SWITCHER
        document.addEventListener('DOMContentLoaded', () => {
            const themeToggleBtnSidebar = document.getElementById('theme-toggle-sidebar');
            const themeToggleDarkIconSidebar = document.getElementById('theme-toggle-dark-icon-sidebar');
            const themeToggleLightIconSidebar = document.getElementById('theme-toggle-light-icon-sidebar');
            const themeToggleTextSidebar = document.getElementById('theme-toggle-text-sidebar');

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

            const isDarkTheme = document.documentElement.classList.contains('dark');
            syncIcons(isDarkTheme);

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

        // SCRIPT CHART (Beradaptasi dengan Tema)
        let scoreChartInstance = null;
        
        function renderCharts() {
            const isDark = document.documentElement.classList.contains('dark');
            const gridColor = isDark ? 'rgba(255,255,255,0.05)' : 'rgba(0,0,0,0.05)';
            const textColor = isDark ? '#64748b' : '#64748b';
            const pointBg = isDark ? '#1d1d1f' : '#ffffff';

            const ctxScore = document.getElementById('scoreChart');
            if(ctxScore && {!! json_encode($chartScores ?? []) !!}.length > 0) {
                if(scoreChartInstance) scoreChartInstance.destroy();
                const gradient = ctxScore.getContext('2d').createLinearGradient(0, 0, 0, 300);
                gradient.addColorStop(0, 'rgba(59, 130, 246, 0.2)'); gradient.addColorStop(1, 'rgba(59, 130, 246, 0)');
                scoreChartInstance = new Chart(ctxScore, { 
                    type: 'line', 
                    data: { 
                        labels: {!! json_encode($chartLabels ?? []) !!}, 
                        datasets: [{ 
                            label: 'Nilai Praktik', 
                            data: {!! json_encode($chartScores ?? []) !!}, 
                            borderColor: '#3b82f6', backgroundColor: gradient, 
                            borderWidth: 2, tension: 0.3, fill: true, 
                            pointBackgroundColor: pointBg, pointBorderColor: '#3b82f6', pointBorderWidth: 2, pointRadius: 4, pointHoverRadius: 6 
                        }] 
                    }, 
                    options: { 
                        responsive: true, maintainAspectRatio: false, 
                        plugins: { 
                            legend: { display: false },
                            tooltip: {
                                backgroundColor: isDark ? 'rgba(30, 30, 30, 0.95)' : 'rgba(255, 255, 255, 0.95)',
                                titleColor: isDark ? '#ffffff' : '#0f172a',
                                bodyColor: isDark ? '#cbd5e1' : '#64748b',
                                titleFont: { size: 13, family: 'Inter', weight: 'bold' },
                                bodyFont: { size: 12, family: 'Inter' },
                                borderColor: isDark ? 'rgba(255,255,255,0.1)' : 'rgba(0,0,0,0.1)',
                                borderWidth: 1,
                                padding: 12,
                                displayColors: false
                            }
                        }, 
                        scales: { 
                            y: { 
                                beginAtZero: true, 
                                max: 100, 
                                grid: { color: gridColor }, 
                                ticks: { color: textColor, stepSize: 20 } 
                            }, 
                            x: { 
                                display: true,
                                grid: { display: false },
                                ticks: { color: textColor, font: { size: 11 } }
                            } 
                        } 
                    } 
                });
            }
        }

        document.addEventListener('DOMContentLoaded', renderCharts);
        window.addEventListener('theme-toggled', renderCharts);


        // SCROLL HALUS KHUSUS HALAMAN DETAIL SISWA
        document.addEventListener('DOMContentLoaded', () => {
            const scroller = document.querySelector('[data-smooth-student-scroll]');
            const finePointer = window.matchMedia('(pointer: fine)').matches;
            const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
            if (!scroller || !finePointer || reducedMotion) return;

            let target = scroller.scrollTop;
            let current = scroller.scrollTop;
            let rafId = null;
            let lastWheelAt = 0;

            const clamp = (value, min, max) => Math.max(min, Math.min(value, max));
            const maxScroll = () => scroller.scrollHeight - scroller.clientHeight;

            const render = () => {
                current += (target - current) * 0.18;
                if (Math.abs(target - current) < 0.45) {
                    current = target;
                    scroller.scrollTop = current;
                    rafId = null;
                    scroller.classList.remove('is-wheel-smoothing');
                    return;
                }
                scroller.scrollTop = current;
                rafId = requestAnimationFrame(render);
            };

            scroller.addEventListener('wheel', (event) => {
                const nativeZone = event.target.closest('[data-native-scroll], textarea, select, input, .overflow-auto, .overflow-x-auto');
                if (nativeZone && nativeZone !== scroller) return;
                if (Math.abs(event.deltaY) < Math.abs(event.deltaX)) return;

                event.preventDefault();
                const now = performance.now();
                if (now - lastWheelAt > 180) {
                    current = scroller.scrollTop;
                    target = current;
                }
                lastWheelAt = now;
                target = clamp(target + event.deltaY, 0, maxScroll());
                scroller.classList.add('is-wheel-smoothing');
                if (!rafId) rafId = requestAnimationFrame(render);
            }, { passive: false });

            scroller.addEventListener('scroll', () => {
                if (!rafId) {
                    current = scroller.scrollTop;
                    target = current;
                }
            }, { passive: true });
        });
    </script>
</body>
</html>
