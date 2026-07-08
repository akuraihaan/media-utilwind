<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panduan Panel Admin</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&family=JetBrains+Mono:wght@400;500;700;800&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

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

    <script>
        if (localStorage.getItem('color-theme') === 'dark' || (!('color-theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark');
        } else {
            document.documentElement.classList.remove('dark');
        }
    </script>

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        .glass-header { background: rgba(255, 255, 255, 0.82); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 40; }
        .dark .glass-header { background: rgba(2, 6, 23, 0.82); border-bottom: 1px solid rgba(255,255,255,0.05); }
        .glass-card {
            background: rgba(255, 255, 255, 0.86); border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); position: relative;
        }
        .dark .glass-card {
            background: rgba(10, 14, 23, 0.86); border: 1px solid rgba(255,255,255,0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        .guide-quick-nav {
            display: flex; gap: 0.55rem; overflow-x: auto; padding-bottom: 0.15rem;
        }
        .guide-chip {
            display: inline-flex; align-items: center; gap: 0.55rem; white-space: nowrap;
            padding: 0.62rem 0.82rem; border-radius: 999px; border: 1px solid #e2e8f0;
            background: rgba(255,255,255,0.68); color: #64748b; font-size: 0.72rem; font-weight: 900;
            transition: transform 0.2s ease, border-color 0.2s ease, background 0.2s ease, color 0.2s ease, box-shadow 0.2s ease;
        }
        .dark .guide-chip { background: rgba(255,255,255,0.035); border-color: rgba(255,255,255,0.08); color: #94a3b8; }
        .guide-chip:hover { transform: translateY(-1px); color: #0f172a; border-color: #c7d2fe; box-shadow: 0 10px 24px -20px rgba(79,70,229,0.42); }
        .dark .guide-chip:hover { color: #fff; border-color: rgba(129,140,248,0.3); }
        .guide-chip.active {
            color: #4f46e5; background: #eef2ff; border-color: #c7d2fe;
            box-shadow: 0 12px 28px -22px rgba(79,70,229,0.58);
        }
        .dark .guide-chip.active { color: #c7d2fe; background: rgba(99,102,241,0.15); border-color: rgba(129,140,248,0.28); }
        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }

        .admin-guide-item {
            overflow: hidden; border-radius: 1.35rem; border: 1px solid rgba(226, 232, 240, 0.9);
            background: rgba(248, 250, 252, 0.72);
            transition: border-color 0.25s ease, transform 0.25s ease, box-shadow 0.25s ease, background 0.25s ease;
        }
        .dark .admin-guide-item { border-color: rgba(255,255,255,0.06); background: rgba(255,255,255,0.025); }
        .admin-guide-item:hover {
            border-color: rgba(99, 102, 241, 0.34); transform: translateY(-2px);
            box-shadow: 0 18px 42px -28px rgba(15, 23, 42, 0.4);
        }
        .dark .admin-guide-item:hover { box-shadow: 0 18px 42px -28px rgba(0, 0, 0, 0.9); }
        .admin-guide-trigger {
            width: 100%; display: flex; align-items: center; gap: 1rem; padding: 1rem 1.1rem; text-align: left;
            transition: background 0.25s ease;
        }
        .admin-guide-trigger:hover { background: rgba(255,255,255,0.75); }
        .dark .admin-guide-trigger:hover { background: rgba(255,255,255,0.035); }
        .admin-guide-no {
            width: 2.5rem; height: 2.5rem; border-radius: 0.95rem; display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0; font-size: 0.72rem; font-weight: 900; transition: transform 0.25s ease;
        }
        .admin-guide-item:hover .admin-guide-no { transform: translateY(-1px) scale(1.04); }
        .admin-guide-title { display: block; color: #0f172a; font-size: 0.95rem; font-weight: 900; line-height: 1.25; }
        .dark .admin-guide-title { color: #fff; }
        .admin-guide-subtitle { display: block; margin-top: 0.2rem; color: #64748b; font-size: 0.76rem; line-height: 1.45; }
        .dark .admin-guide-subtitle { color: #94a3b8; }
        .admin-guide-content { padding: 0 1rem 1rem; }
        .guide-grid { display: grid; grid-template-columns: minmax(0, 1.7fr) minmax(260px, 0.82fr); gap: 1.25rem; align-items: stretch; }
        .guide-story-intro {
            border-radius: 1.25rem; border: 1px solid #e2e8f0; background: #f8fafc;
            padding: 1rem 1.1rem; color: #475569; font-size: 0.9rem; line-height: 1.75;
        }
        .dark .guide-story-intro { border-color: rgba(255,255,255,0.06); background: rgba(255,255,255,0.03); color: #cbd5e1; }

        .admin-shot {
            position: relative; min-height: 390px; aspect-ratio: 16 / 9; overflow: hidden; border-radius: 1.15rem;
            border: 1px solid #e2e8f0; background: #f8fafc; cursor: crosshair;
            box-shadow: inset 0 0 0 1px rgba(255,255,255,0.7);
            transition: transform 0.3s ease, box-shadow 0.3s ease, border-color 0.3s ease;
        }
        .dark .admin-shot { border-color: rgba(255,255,255,0.08); background: #080d16; box-shadow: inset 0 0 0 1px rgba(255,255,255,0.03); }
        .admin-shot:hover {
            transform: translateY(-4px) scale(1.006); border-color: rgba(99, 102, 241, 0.45);
            box-shadow: 0 28px 60px -36px rgba(15, 23, 42, 0.58), inset 0 0 0 1px rgba(255,255,255,0.75);
        }
        .dark .admin-shot:hover { box-shadow: 0 28px 60px -36px rgba(0, 0, 0, 0.95), inset 0 0 0 1px rgba(255,255,255,0.05); }
        .admin-shot::after {
            content: ''; position: absolute; inset: 0; pointer-events: none;
            background: radial-gradient(circle at 20% 10%, rgba(99,102,241,0.08), transparent 26%),
                        radial-gradient(circle at 86% 15%, rgba(6,182,212,0.08), transparent 30%);
        }
        .real-admin-shot {
            background: #fff;
            cursor: zoom-in;
            min-height: 0;
        }
        .real-admin-shot::after { display: none; }
        .admin-guide-screenshot {
            position: absolute; inset: 0; z-index: 1; display: block;
            width: 100%; height: 100%; object-fit: cover; object-position: top center;
            user-select: none; pointer-events: none;
        }
        .guide-hotspot {
            position: absolute; left: var(--x); top: var(--y); width: var(--w); height: var(--h);
            box-sizing: border-box; border: 2px solid rgba(99, 102, 241, 0.86); border-radius: 1rem; z-index: 20;
            background: rgba(99, 102, 241, 0.025); box-shadow: inset 0 0 0 1px rgba(255,255,255,0.35);
            pointer-events: none;
            transition: border-color 0.25s ease, box-shadow 0.25s ease, transform 0.25s ease, background-color 0.25s ease;
        }
        .guide-hotspot::after {
            content: ''; position: absolute; inset: 0; background: rgba(99, 102, 241, 0.05);
            opacity: 0; transition: opacity 0.25s ease;
        }
        .admin-shot:hover .guide-hotspot {
            border-color: #818cf8; background-color: rgba(99, 102, 241, 0.035);
            box-shadow: 0 0 0 4px rgba(99,102,241,0.08), 0 14px 28px rgba(99,102,241,0.12), inset 0 0 0 1px rgba(255,255,255,0.42);
            transform: none;
        }
        .admin-shot:hover .guide-hotspot::after { opacity: 1; }
        .guide-hotspot span {
            position: absolute; left: 0.45rem; top: 0.45rem; transform: none;
            width: 1.62rem; height: 1.62rem; border-radius: 999px; background: #6366f1; color: white;
            display: inline-flex; align-items: center; justify-content: center; font-weight: 900; font-size: 0.78rem;
            box-shadow: 0 10px 22px rgba(99,102,241,0.22); transition: transform 0.25s ease, box-shadow 0.25s ease;
        }
        .guide-hotspot.marker-right span { left: 0.45rem; right: auto; }
        .admin-shot:hover .guide-hotspot span { transform: scale(1.08); box-shadow: 0 12px 28px rgba(99,102,241,0.35); }

        .guide-note-list {
            display: flex; flex-direction: column; justify-content: center; gap: 0.85rem;
            border-radius: 1.15rem; background: #fff; border: 1px solid #e2e8f0; padding: 1rem;
        }
        .dark .guide-note-list { background: rgba(255,255,255,0.03); border-color: rgba(255,255,255,0.06); }
        .guide-note-list > div { display: flex; gap: 0.8rem; align-items: flex-start; padding: 0.65rem; border-radius: 0.95rem; transition: transform 0.25s ease, background 0.25s ease; }
        .guide-note-list > div:hover { transform: translateX(4px); background: #eef2ff; }
        .dark .guide-note-list > div:hover { background: rgba(99,102,241,0.09); }
        .guide-note-list span {
            width: 1.65rem; height: 1.65rem; border-radius: 999px; display: inline-flex; align-items: center; justify-content: center;
            flex-shrink: 0; background: #6366f1; color: #fff; font-size: 0.72rem; font-weight: 900;
        }
        .guide-note-list p { margin: 0; color: #475569; font-size: 0.82rem; line-height: 1.65; }
        .dark .guide-note-list p { color: #cbd5e1; }
        .guide-note-list strong { color: #0f172a; }
        .dark .guide-note-list strong { color: #fff; }
        .guide-page-link {
            display: inline-flex; align-items: center; justify-content: center; gap: 0.45rem;
            margin-top: 0.25rem; border-radius: 0.9rem; border: 1px solid #c7d2fe;
            background: #eef2ff; color: #4f46e5; padding: 0.75rem 0.9rem;
            font-size: 0.75rem; font-weight: 900; transition: background 0.2s ease, transform 0.2s ease;
        }
        .guide-page-link:hover { transform: translateY(-1px); background: #e0e7ff; }
        .dark .guide-page-link { border-color: rgba(129,140,248,0.25); background: rgba(99,102,241,0.12); color: #c7d2fe; }

        .mock-bar, .mock-line, .mock-pill, .mock-input, .mock-row, .mock-token, .mock-mini-card { border-radius: 999px; background: #e2e8f0; }
        .dark .mock-bar, .dark .mock-line, .dark .mock-pill, .dark .mock-input, .dark .mock-row, .dark .mock-token, .dark .mock-mini-card { background: rgba(255,255,255,0.12); }
        .mock-line { height: 0.62rem; width: 72%; }
        .mock-line.long { width: 92%; }
        .mock-line.short { width: 48%; }
        .mock-pill { height: 1.8rem; }
        .mock-input { height: 2.35rem; border: 1px solid #e2e8f0; background: #fff; }
        .dark .mock-input { border-color: rgba(255,255,255,0.08); background: rgba(2,6,23,0.62); }
        .mock-row { height: 2rem; border-radius: 0.75rem; }

        .admin-dashboard-shot { display: grid; grid-template-columns: 23% 1fr; gap: 4%; padding: 5%; background: linear-gradient(135deg, #f8fafc 0%, #eef2ff 55%, #f0f9ff 100%); }
        .dark .admin-dashboard-shot { background: linear-gradient(135deg, #020617 0%, #0f172a 55%, #111827 100%); }
        .mini-sidebar {
            z-index: 1; border-radius: 1rem; padding: 0.85rem; background: rgba(255,255,255,0.82); border: 1px solid #e2e8f0;
            display: flex; flex-direction: column; gap: 0.7rem; transition: transform 0.28s ease, box-shadow 0.28s ease;
        }
        .dark .mini-sidebar { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
        .mini-logo { width: 2rem; height: 2rem; border-radius: 0.65rem; background: linear-gradient(135deg, #6366f1, #d946ef); }
        .mini-nav-item { height: 1.65rem; border-radius: 0.65rem; background: #eef2ff; }
        .mini-nav-item.active { background: linear-gradient(135deg, #6366f1, #818cf8); }
        .dark .mini-nav-item { background: rgba(255,255,255,0.08); }
        .dashboard-area { z-index: 1; display: grid; grid-template-rows: 17% 24% 1fr; gap: 0.9rem; min-width: 0; }
        .mini-header, .kpi-grid, .admin-chart-panel, .admin-activity-panel {
            border-radius: 1rem; background: rgba(255,255,255,0.86); border: 1px solid #e2e8f0; transition: transform 0.28s ease, box-shadow 0.28s ease;
        }
        .dark .mini-header, .dark .kpi-card, .dark .admin-chart-panel, .dark .admin-activity-panel { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
        .mini-header { padding: 0.85rem; display: flex; align-items: center; justify-content: space-between; }
        .mini-header h5, .admin-shot h5 { margin: 0; color: #0f172a; font-weight: 950; font-size: 1rem; }
        .dark .mini-header h5, .dark .admin-shot h5 { color: #fff; }
        .kpi-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.65rem; padding: 0.65rem; background: transparent; border: 0; }
        .kpi-card { border-radius: 0.85rem; padding: 0.65rem; background: #fff; border: 1px solid #e2e8f0; }
        .kpi-card strong { display: block; color: #0f172a; font-size: 1.2rem; font-weight: 950; line-height: 1; }
        .dark .kpi-card strong { color: #fff; }
        .kpi-card span { color: #64748b; font-size: 0.55rem; font-weight: 900; text-transform: uppercase; }
        .chart-activity-grid { display: grid; grid-template-columns: 1.3fr 0.85fr; gap: 0.85rem; min-height: 0; }
        .admin-chart-panel { padding: 0.9rem; display: flex; flex-direction: column; justify-content: end; gap: 0.65rem; }
        .chart-bars { height: 7rem; display: flex; align-items: end; gap: 0.55rem; }
        .chart-bars span { flex: 1; border-radius: 0.45rem 0.45rem 0 0; background: #6366f1; }
        .chart-bars span:nth-child(1) { height: 42%; } .chart-bars span:nth-child(2) { height: 72%; background: #06b6d4; } .chart-bars span:nth-child(3) { height: 56%; } .chart-bars span:nth-child(4) { height: 88%; background: #10b981; }
        .admin-activity-panel { padding: 0.9rem; display: flex; flex-direction: column; justify-content: center; gap: 0.55rem; }

        .student-shot, .class-shot, .quiz-admin-shot, .lab-admin-shot, .review-shot { padding: 5%; }
        .student-shot { display: grid; grid-template-rows: 18% 1fr; gap: 1rem; background: linear-gradient(135deg, #f8fafc 0%, #eef6ff 100%); }
        .dark .student-shot, .dark .class-shot, .dark .quiz-admin-shot, .dark .review-shot { background: linear-gradient(135deg, #020617 0%, #0f172a 58%, #111827 100%); }
        .student-toolbar, .student-table, .student-detail-card, .class-form-card, .class-token-card, .class-status-card, .question-table, .question-editor, .quiz-analytics-card, .lab-list-card, .lab-task-card, .lab-score-card, .review-filter, .review-chart, .review-history {
            z-index: 1; border-radius: 1rem; background: rgba(255,255,255,0.9); border: 1px solid #e2e8f0; transition: transform 0.28s ease, box-shadow 0.28s ease;
        }
        .dark .student-toolbar, .dark .student-table, .dark .student-detail-card, .dark .class-form-card, .dark .class-token-card, .dark .class-status-card, .dark .question-table, .dark .question-editor, .dark .quiz-analytics-card, .dark .lab-list-card, .dark .lab-task-card, .dark .lab-score-card, .dark .review-filter, .dark .review-chart, .dark .review-history {
            background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08);
        }
        .student-toolbar { padding: 0.85rem; display: flex; align-items: center; gap: 0.7rem; }
        .student-toolbar .mock-input { width: 38%; }
        .toolbar-btn { height: 2.25rem; min-width: 5.2rem; border-radius: 0.75rem; background: #6366f1; color: white; display: flex; align-items: center; justify-content: center; font-size: 0.62rem; font-weight: 950; }
        .toolbar-btn.soft { background: #e0f2fe; color: #0369a1; }
        .student-content-grid { display: grid; grid-template-columns: 1.35fr 0.75fr; gap: 0.9rem; min-height: 0; }
        .student-table { padding: 0.9rem; display: flex; flex-direction: column; gap: 0.6rem; }
        .student-row { display: grid; grid-template-columns: 2fr 1fr 1fr 0.8fr; gap: 0.55rem; align-items: center; }
        .student-row span { height: 1.85rem; border-radius: 0.65rem; background: #f1f5f9; }
        .dark .student-row span { background: rgba(255,255,255,0.08); }
        .student-row.active span:first-child { background: rgba(99,102,241,0.12); border: 1px solid rgba(99,102,241,0.2); }
        .student-detail-card { padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem; }
        .detail-avatar { width: 3rem; height: 3rem; border-radius: 999px; background: linear-gradient(135deg, #6366f1, #d946ef); }

        .class-shot { display: grid; grid-template-columns: 0.92fr 1.1fr 0.85fr; gap: 0.9rem; background: linear-gradient(135deg, #f8fafc 0%, #f0fdf4 55%, #eef2ff 100%); }
        .class-form-card, .class-token-card, .class-status-card { padding: 1rem; display: flex; flex-direction: column; justify-content: center; gap: 0.75rem; }
        .token-box { border-radius: 0.9rem; padding: 0.9rem; background: #eef2ff; border: 1px dashed #818cf8; font-family: 'JetBrains Mono', monospace; font-weight: 900; color: #4f46e5; }
        .dark .token-box { background: rgba(99,102,241,0.12); border-color: rgba(129,140,248,0.42); color: #c7d2fe; }
        .class-status-dot { width: 0.65rem; height: 0.65rem; border-radius: 999px; background: #10b981; box-shadow: 0 0 10px #10b981; }

        .quiz-admin-shot { display: grid; grid-template-columns: 1.18fr 0.92fr; grid-template-rows: 1fr 32%; gap: 0.9rem; background: linear-gradient(135deg, #f8fafc 0%, #fdf4ff 55%, #eef2ff 100%); }
        .question-table, .question-editor, .quiz-analytics-card { padding: 0.95rem; }
        .question-table { display: flex; flex-direction: column; gap: 0.55rem; }
        .question-editor { display: flex; flex-direction: column; gap: 0.68rem; }
        .quiz-analytics-card { grid-column: 1 / -1; display: grid; grid-template-columns: repeat(4, 1fr); gap: 0.7rem; }
        .analytics-tile { border-radius: 0.8rem; padding: 0.75rem; background: #fff; border: 1px solid #e2e8f0; }
        .dark .analytics-tile { background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
        .analytics-tile strong { color: #0f172a; font-size: 1.1rem; font-weight: 950; }
        .dark .analytics-tile strong { color: #fff; }

        .lab-admin-shot { display: grid; grid-template-columns: 0.95fr 1.05fr 0.8fr; gap: 0.9rem; background: #1e1e1e; }
        .lab-list-card, .lab-task-card, .lab-score-card { background: #252526; border-color: #3e3e42; padding: 1rem; color: #e5e7eb; }
        .lab-row { height: 2.15rem; border-radius: 0.7rem; background: #34383b; margin-top: 0.65rem; }
        .lab-row.active { background: rgba(0,122,204,0.22); border: 1px solid rgba(0,122,204,0.42); }
        .lab-code-panel { border-radius: 0.85rem; background: #1e1e1e; border: 1px solid #3e3e42; padding: 0.75rem; display: flex; flex-direction: column; gap: 0.58rem; }
        .code-line { height: 0.55rem; border-radius: 999px; background: rgba(103,232,249,0.72); }
        .code-line:nth-child(2), .code-line:nth-child(4) { background: rgba(167,139,250,0.72); }
        .score-ring { width: 5rem; height: 5rem; border-radius: 999px; border: 0.75rem solid #10b981; display: flex; align-items: center; justify-content: center; font-weight: 950; color: white; margin: 0 auto; }

        .review-shot { display: grid; grid-template-columns: 1.25fr 0.85fr; grid-template-rows: 18% 1fr; gap: 0.9rem; background: linear-gradient(135deg, #f8fafc 0%, #eef6ff 55%, #f0fdf4 100%); }
        .review-filter { grid-column: 1 / -1; padding: 0.85rem; display: flex; align-items: center; gap: 0.7rem; }
        .review-filter .mock-input { width: 26%; }
        .review-chart { padding: 1rem; display: flex; flex-direction: column; gap: 0.75rem; }
        .line-chart { height: 8rem; border-radius: 1rem; background: linear-gradient(135deg, rgba(99,102,241,0.12), rgba(6,182,212,0.12)); border: 1px dashed rgba(99,102,241,0.42); }
        .review-history { padding: 1rem; display: flex; flex-direction: column; gap: 0.65rem; }

        .admin-shot:hover .mini-sidebar,
        .admin-shot:hover .mini-header,
        .admin-shot:hover .admin-chart-panel,
        .admin-shot:hover .student-toolbar,
        .admin-shot:hover .student-table,
        .admin-shot:hover .class-token-card,
        .admin-shot:hover .question-editor,
        .admin-shot:hover .lab-task-card,
        .admin-shot:hover .review-chart {
            transform: none; box-shadow: 0 18px 38px -30px rgba(15, 23, 42, 0.52);
        }
        .admin-shot:hover .admin-activity-panel,
        .admin-shot:hover .student-detail-card,
        .admin-shot:hover .class-form-card,
        .admin-shot:hover .class-status-card,
        .admin-shot:hover .question-table,
        .admin-shot:hover .quiz-analytics-card,
        .admin-shot:hover .lab-list-card,
        .admin-shot:hover .lab-score-card,
        .admin-shot:hover .review-history {
            transform: none;
        }

        @media (max-width: 1024px) {
            .guide-grid { grid-template-columns: 1fr; }
            .admin-shot { min-height: 340px; }
        }
        @media (max-width: 640px) {
            .admin-guide-trigger { align-items: flex-start; padding: 0.9rem; }
            .admin-guide-no { width: 2.2rem; height: 2.2rem; }
            .admin-shot { aspect-ratio: auto; min-height: 380px; }
            .admin-dashboard-shot, .student-content-grid, .class-shot, .quiz-admin-shot, .lab-admin-shot, .review-shot { grid-template-columns: 1fr; }
            .admin-dashboard-shot { padding: 7%; }
            .mini-sidebar, .student-detail-card, .class-status-card, .quiz-analytics-card, .lab-score-card, .review-history { display: none; }
            .dashboard-area, .student-shot, .review-shot { grid-template-rows: auto 1fr; }
            .kpi-grid { grid-template-columns: repeat(2, 1fr); }
            .chart-activity-grid { grid-template-columns: 1fr; }
            .guide-hotspot span { left: 0.35rem; top: 0.35rem; transform: none; }
            .guide-hotspot.marker-right span { left: 0.35rem; right: auto; }
        }
        [x-cloak] { display: none !important; }
    </style>
</head>
@php
    $adminName = Auth::user()->name ?? 'Administrator';
    $adminInitial = strtoupper(substr($adminName, 0, 1));
@endphp
<body class="min-h-screen w-full bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 overflow-x-hidden transition-colors duration-500"
      x-data="{ activeAdminGuide: 'dashboard', isFullscreen: false }"
      @keydown.escape.window="isFullscreen = false; if (document.fullscreenElement) document.exitFullscreen();">

    <div class="fixed inset-0 pointer-events-none z-0">
        <div class="absolute top-[8%] left-[20%] w-[520px] h-[520px] bg-indigo-300/20 dark:bg-indigo-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
        <div class="absolute bottom-[8%] right-[8%] w-[420px] h-[420px] bg-cyan-300/20 dark:bg-cyan-600/10 rounded-full blur-[120px] transition-colors duration-500"></div>
    </div>

    <header class="sticky top-0 z-50 glass-header px-4 py-4 md:px-6 transition-colors duration-500">
        <div class="mx-auto max-w-7xl">
            <div class="flex flex-col gap-4 lg:flex-row lg:items-center lg:justify-between">
                <div class="min-w-0">
                    <nav class="flex items-center gap-2 mb-3 text-[11px] font-bold uppercase tracking-widest text-slate-500 dark:text-slate-500 transition-colors" aria-label="Breadcrumb">
                        <a href="{{ route('admin.dashboard') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1.5">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                            Panel Admin
                        </a>
                        <span class="text-slate-300 dark:text-slate-700 transition-colors">/</span>
                        <span class="text-indigo-600 dark:text-indigo-400 transition-colors">Panduan admin</span>
                    </nav>
                    <div class="mt-3 flex flex-wrap items-center gap-3">
                        <h2 class="text-2xl md:text-3xl font-black tracking-tight text-slate-900 dark:text-white transition-colors">Panduan Admin</h2>
                    </div>
                    <p class="mt-2 max-w-2xl text-xs font-semibold leading-relaxed text-slate-500 dark:text-slate-400">Pilih halaman yang ingin dipahami, buka gambarnya, lalu ikuti catatan bernomor sesuai area yang ditandai.</p>
                </div>

                <div class="flex items-center gap-2 shrink-0">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 px-3.5 py-2.5 rounded-xl bg-white/80 dark:bg-white/[0.04] hover:bg-slate-100 dark:hover:bg-white/10 text-slate-700 dark:text-slate-300 transition-colors border border-slate-200 dark:border-white/10 text-xs font-bold shadow-sm">
                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.4"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                        Dasbor
                    </a>
                    <button data-theme-toggle type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/80 dark:bg-white/[0.04] hover:bg-slate-100 dark:hover:bg-white/10 text-slate-600 dark:text-slate-300 border border-slate-200 dark:border-white/10 transition-colors shadow-sm" title="Ubah Tema">
                        <svg data-theme-dark-icon class="hidden w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M17.293 13.293A8 8 0 016.707 2.707a8.001 8.001 0 1010.586 10.586z"/></svg>
                        <svg data-theme-light-icon class="hidden w-4 h-4 text-amber-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 2a1 1 0 011 1v1a1 1 0 11-2 0V3a1 1 0 011-1zm4 8a4 4 0 11-8 0 4 4 0 018 0zm-.464 4.95l.707.707a1 1 0 001.414-1.414l-.707-.707a1 1 0 00-1.414 1.414zM17 11a1 1 0 100-2h-1a1 1 0 100 2h1zM10 15a1 1 0 011 1v1a1 1 0 11-2 0v-1a1 1 0 011-1zM4 11a1 1 0 100-2H3a1 1 0 000 2h1z" clip-rule="evenodd"/></svg>
                        <span data-theme-toggle-text class="sr-only">Ubah Tema</span>
                    </button>
                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : (document.fullscreenElement && document.exitFullscreen())" class="hidden sm:inline-flex h-10 w-10 items-center justify-center rounded-xl bg-white/80 dark:bg-white/[0.04] text-slate-500 dark:text-slate-300 hover:text-slate-900 dark:hover:text-white border border-slate-200 dark:border-white/10 transition-colors shadow-sm" title="Mode Layar Penuh">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>
                    <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white text-xs shadow-lg">{{ $adminInitial }}</div>
                </div>
            </div>

            <nav class="guide-quick-nav custom-scrollbar mt-4" aria-label="Navigasi Panduan">
                <button type="button" @click="activeAdminGuide = 'dashboard'; document.getElementById('guide-dashboard')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'dashboard' ? 'active' : ''">Dasbor</button>
                <button type="button" @click="activeAdminGuide = 'students'; document.getElementById('guide-students')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'students' ? 'active' : ''">Siswa</button>
                <button type="button" @click="activeAdminGuide = 'classes'; document.getElementById('guide-classes')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'classes' ? 'active' : ''">Kelas</button>
                <button type="button" @click="activeAdminGuide = 'quiz'; document.getElementById('guide-quiz')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'quiz' ? 'active' : ''">Kuis</button>
                <button type="button" @click="activeAdminGuide = 'quiz-review'; document.getElementById('guide-quiz-review')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'quiz-review' ? 'active' : ''">Tinjau Kuis</button>
                <button type="button" @click="activeAdminGuide = 'question-create'; document.getElementById('guide-question-create')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'question-create' ? 'active' : ''">Buat Soal</button>
                <button type="button" @click="activeAdminGuide = 'lab'; document.getElementById('guide-lab')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'lab' ? 'active' : ''">Lab</button>
                <button type="button" @click="activeAdminGuide = 'analytics'; document.getElementById('guide-analytics')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'analytics' ? 'active' : ''">Analitik</button>
                <button type="button" @click="activeAdminGuide = 'student-detail'; document.getElementById('guide-student-detail')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'student-detail' ? 'active' : ''">Detail Siswa</button>
                <button type="button" @click="activeAdminGuide = 'lab-review'; document.getElementById('guide-lab-review')?.scrollIntoView({ behavior: 'smooth', block: 'start' })" class="guide-chip" :class="activeAdminGuide === 'lab-review' ? 'active' : ''">Review Lab</button>
            </nav>
        </div>
    </header>

    <main id="admin-main-content" class="relative z-10 mx-auto w-full max-w-7xl px-4 pb-10 pt-3 md:px-6 md:pb-12">

            <section class="glass-card rounded-[2rem] p-5 md:p-8 reveal">
                <div class="mb-6">
                    <div class="guide-story-intro">
                        Panduan ini disusun mengikuti alur kerja admin sehari-hari: mulai dari melihat kondisi kelas, mencari siswa, membagikan token, meninjau kuis, mengatur lab, sampai membaca analitik. Setiap bagian memakai gambar panduan terkini agar posisi menu, tabel, tombol, dan grafik sesuai dengan tampilan panel sekarang.
                    </div>
                </div>

                <div class="space-y-4">
                    <div id="guide-dashboard" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'dashboard' ? null : 'dashboard'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-indigo-100 text-indigo-700 dark:bg-indigo-500/10 dark:text-indigo-300">01</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Dasbor Admin</span>
                                <span class="admin-guide-subtitle">Tempat pertama untuk melihat kondisi belajar hari ini sebelum mengambil tindakan.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'dashboard' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'dashboard'" x-transition.opacity class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-dashboard.png') }}" alt="Tampilan asli dasbor admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot" style="--x: 0%; --y: 0%; --w: 22.5%; --h: 100%;"><span>1</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 18.5%; --w: 71.5%; --h: 58.5%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 81.5%; --w: 71.5%; --h: 17%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Mulai dari menu kiri</strong> untuk berpindah ke kuis, lab, kelas, siswa, atau kembali ke panduan saat membutuhkan bantuan.</p></div>
                                    <div><span>2</span><p><strong>Baca kartu utama</strong> untuk melihat siswa yang sudah tuntas, yang perlu pendampingan, dan aktivitas lab terbaru.</p></div>
                                    <div><span>3</span><p><strong>Lanjutkan ke grafik dan log</strong> saat ingin melihat pola nilai, aktivitas terbaru, atau sinyal yang perlu segera ditindaklanjuti.</p></div>
                                    <a href="{{ route('admin.dashboard') }}" class="guide-page-link">Buka Dasbor Admin</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-students" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'students' ? null : 'students'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">02</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Manajemen Siswa</span>
                                <span class="admin-guide-subtitle">Mencari, menyaring, dan membuka ringkasan belajar siswa tanpa bolak-balik halaman.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'students' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'students'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-students.png') }}" alt="Tampilan asli direktori siswa admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 51.5%; --y: 15.5%; --w: 43.5%; --h: 10.5%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 27.5%; --y: 28.5%; --w: 67.5%; --h: 69%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 76.5%; --y: 37%; --w: 16.5%; --h: 55%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Cari siswa lebih dulu</strong> menggunakan nama, email, kelas, atau status agar daftar yang terlihat langsung sesuai kebutuhan.</p></div>
                                    <div><span>2</span><p><strong>Periksa tabel siswa</strong> untuk melihat akun, kelas, status belajar, dan informasi dasar sebelum membuka detail.</p></div>
                                    <div><span>3</span><p><strong>Gunakan tombol aksi</strong> untuk melihat insight, membuka profil, atau membersihkan akun yang memang tidak lagi dipakai.</p></div>
                                    <a href="{{ route('admin.students.index') }}" class="guide-page-link">Buka Direktori Siswa</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-classes" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'classes' ? null : 'classes'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-emerald-100 text-emerald-700 dark:bg-emerald-500/10 dark:text-emerald-300">03</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Kelas dan Token</span>
                                <span class="admin-guide-subtitle">Mengatur ruang belajar dan membagikan kode masuk yang mudah dipakai siswa.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'classes' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'classes'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-classes.png') }}" alt="Tampilan asli manajemen kelas dan token admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 18.5%; --w: 71.5%; --h: 16%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 41%; --w: 71.5%; --h: 43%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 81.5%; --y: 64.5%; --w: 12.5%; --h: 17%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Lihat kondisi kelas</strong> melalui ringkasan total kelas, token aktif, dan jumlah siswa yang sudah tersambung.</p></div>
                                    <div><span>2</span><p><strong>Bagikan token kelas</strong> kepada siswa yang tepat. Token ini dipakai siswa untuk masuk ke kelasnya masing-masing.</p></div>
                                    <div><span>3</span><p><strong>Kelola akses seperlunya</strong> dengan memperbarui token, mengedit data kelas, menutup pendaftaran, atau membuka analitik kelas.</p></div>
                                    <a href="{{ route('admin.classes.index') }}" class="guide-page-link">Buka Kelas & Token</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-quiz" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'quiz' ? null : 'quiz'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-fuchsia-100 text-fuchsia-700 dark:bg-fuchsia-500/10 dark:text-fuchsia-300">04</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Manajemen Kuis</span>
                                <span class="admin-guide-subtitle">Meninjau kualitas soal dan menentukan butir mana yang perlu diperbaiki.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'quiz' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'quiz'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-question-analytics.png') }}" alt="Tampilan asli bank soal dan analisis evaluasi admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 18.5%; --w: 71.5%; --h: 18%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 40%; --w: 47%; --h: 58.5%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 74.5%; --y: 40%; --w: 22.5%; --h: 58.5%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Mulai dari ringkasan</strong> untuk melihat jumlah soal, partisipan, akurasi jawaban, dan soal yang mulai terlihat sulit.</p></div>
                                    <div><span>2</span><p><strong>Pilih kelompok soal</strong> untuk memperbaiki pertanyaan, opsi, kunci, media, atau TP.</p></div>
                                    <div><span>3</span><p><strong>Cek soal lemah</strong> untuk menentukan materi yang perlu dijelaskan ulang.</p></div>
                                    <a href="{{ route('admin.analytics.questions') }}" class="guide-page-link">Buka Manajemen Kuis</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-quiz-review" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'quiz-review' ? null : 'quiz-review'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-violet-100 text-violet-700 dark:bg-violet-500/10 dark:text-violet-300">05</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Tinjauan Kuis Siswa</span>
                                <span class="admin-guide-subtitle">Membuka hasil kuis siswa dari tabel analitik yang lebih ringkas.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'quiz-review' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'quiz-review'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-question-analytics.png') }}" alt="Tampilan asli analitik kuis dan tinjauan siswa admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 18.5%; --w: 71.5%; --h: 19%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 41%; --w: 71.5%; --h: 16%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 62%; --w: 71.5%; --h: 36%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Baca ringkasan kuis</strong> untuk melihat partisipan, akurasi, dan soal yang perlu ditinjau.</p></div>
                                    <div><span>2</span><p><strong>Gunakan filter kuis</strong> agar tabel siswa sesuai periode atau kelas yang ingin dianalisis.</p></div>
                                    <div><span>3</span><p><strong>Tinjau siswa</strong> untuk membuka halaman hasil kuis berdasarkan nama siswa tersebut.</p></div>
                                    <a href="{{ route('admin.analytics.questions') }}#quiz-students" class="guide-page-link">Buka Tinjauan Kuis</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-question-create" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'question-create' ? null : 'question-create'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-cyan-100 text-cyan-700 dark:bg-cyan-500/10 dark:text-cyan-300">06</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Buat Soal</span>
                                <span class="admin-guide-subtitle">Menyusun soal, opsi jawaban, tujuan pembelajaran, dan media pendukung.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'question-create' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'question-create'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-question-create.png') }}" alt="Tampilan asli halaman buat soal admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 18.5%; --w: 71.5%; --h: 20%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 42%; --w: 46%; --h: 56%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 73.5%; --y: 42%; --w: 23.5%; --h: 56%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Isi identitas soal</strong> seperti bab, tipe interaksi, teks pertanyaan, dan TP yang diukur.</p></div>
                                    <div><span>2</span><p><strong>Lengkapi opsi jawaban</strong> dan pastikan kunci jawaban sesuai sebelum menyimpan.</p></div>
                                    <div><span>3</span><p><strong>Cek pratinjau</strong> untuk memastikan soal dan media mudah dibaca siswa.</p></div>
                                    <a href="{{ route('admin.questions.create') }}" class="guide-page-link">Buka Buat Soal</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-lab" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'lab' ? null : 'lab'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-blue-100 text-blue-700 dark:bg-blue-500/10 dark:text-blue-300">07</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Konfigurasi Lab</span>
                                <span class="admin-guide-subtitle">Menyusun praktik yang jelas, bertahap, dan bisa diperiksa otomatis.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'lab' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'lab'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-lab.png') }}" alt="Tampilan asli konfigurasi lab admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 19%; --w: 71.5%; --h: 34%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 58%; --w: 71.5%; --h: 40%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 76%; --y: 61%; --w: 19%; --h: 37%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Pastikan modulnya jelas</strong> dengan judul, slug, deskripsi, durasi, dan nilai minimum yang mudah dipahami siswa.</p></div>
                                    <div><span>2</span><p><strong>Susun langkah praktik</strong> dari tugas paling awal sampai validasi akhir agar siswa tahu urutan pengerjaannya.</p></div>
                                    <div><span>3</span><p><strong>Kelola modul dengan hati-hati</strong> melalui tombol langkah, pengaturan, atau hapus jika modul sudah tidak dipakai.</p></div>
                                    <a href="{{ route('admin.labs.index') }}" class="guide-page-link">Buka Konfigurasi Lab</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-analytics" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'analytics' ? null : 'analytics'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-amber-100 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300">08</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Analitik dan Tinjauan</span>
                                <span class="admin-guide-subtitle">Membaca pola praktik sebelum memberi penguatan atau laporan.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'analytics' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'analytics'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-lab-analytics.png') }}" alt="Tampilan asli pusat analitik lab admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 19%; --w: 71.5%; --h: 19%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 44%; --w: 71.5%; --h: 54.5%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 84%; --y: 48.5%; --w: 11%; --h: 7%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Baca ringkasan dulu</strong> untuk mengetahui total percobaan, rasio lulus, rata-rata nilai, dan durasi pengerjaan.</p></div>
                                    <div><span>2</span><p><strong>Perhatikan grafik</strong> untuk melihat modul mana yang stabil, menurun, atau membutuhkan pendampingan tambahan.</p></div>
                                    <div><span>3</span><p><strong>Ubah tampilan grafik</strong> saat pola lebih mudah dibaca dalam bentuk garis atau batang.</p></div>
                                    <a href="{{ route('admin.lab.analytics') }}" class="guide-page-link">Buka Analitik Lab</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-student-detail" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'student-detail' ? null : 'student-detail'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-sky-100 text-sky-700 dark:bg-sky-500/10 dark:text-sky-300">09</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Detail Siswa</span>
                                <span class="admin-guide-subtitle">Membaca profil, capaian, dan riwayat aktivitas sebelum memberi tindak lanjut.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'student-detail' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'student-detail'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-student-detail.png') }}" alt="Tampilan asli detail siswa admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot marker-right" style="--x: 25.5%; --y: 18%; --w: 71.5%; --h: 20%;"><span>1</span></div>
                                    <div class="guide-hotspot" style="--x: 25.5%; --y: 43%; --w: 71.5%; --h: 17%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 62.5%; --y: 62%; --w: 34.5%; --h: 32%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Pastikan identitas siswa</strong> seperti nama, email, kelas, dan progres keseluruhan sudah sesuai.</p></div>
                                    <div><span>2</span><p><strong>Baca capaian utama</strong> untuk melihat materi, praktik lab, kuis, dan jumlah indikator yang perlu dicek.</p></div>
                                    <div><span>3</span><p><strong>Gunakan riwayat aktivitas</strong> untuk meninjau percobaan terbaru sebelum memberi arahan ke siswa.</p></div>
                                    <a href="{{ route('admin.students.index') }}" class="guide-page-link">Buka Direktori Siswa</a>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="guide-lab-review" class="admin-guide-item scroll-mt-44">
                        <button @click="activeAdminGuide = activeAdminGuide === 'lab-review' ? null : 'lab-review'" class="admin-guide-trigger" type="button">
                            <span class="admin-guide-no bg-rose-100 text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">10</span>
                            <span class="flex-1">
                                <span class="admin-guide-title">Review Hasil Lab</span>
                                <span class="admin-guide-subtitle">Memvalidasi skor, TP praktik, catatan risiko, dan bukti kode akhir siswa.</span>
                            </span>
                            <svg :class="activeAdminGuide === 'lab-review' ? 'rotate-180 text-indigo-500' : 'text-slate-400'" class="w-5 h-5 transition-transform shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7"/></svg>
                        </button>
                        <div x-show="activeAdminGuide === 'lab-review'" x-transition.opacity style="display: none;" class="admin-guide-content">
                            <div class="guide-grid">
                                <div class="admin-shot real-admin-shot">
                                    <img class="admin-guide-screenshot" src="{{ asset('images/guides/current-admin-lab-result-review.png') }}" alt="Tampilan asli review hasil lab admin Utilwind" loading="eager" decoding="async">
                                    <div class="guide-hotspot" style="--x: 5%; --y: 17%; --w: 52%; --h: 25%;"><span>1</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 5%; --y: 59%; --w: 91%; --h: 20%;"><span>2</span></div>
                                    <div class="guide-hotspot marker-right" style="--x: 5%; --y: 81%; --w: 91%; --h: 16%;"><span>3</span></div>
                                </div>
                                <div class="guide-note-list">
                                    <div><span>1</span><p><strong>Mulai dari status dan skor</strong> untuk memahami apakah siswa sudah mencapai target lab.</p></div>
                                    <div><span>2</span><p><strong>Cocokkan validasi TP</strong> dengan catatan risiko dan rekomendasi agar tindak lanjut lebih tepat.</p></div>
                                    <div><span>3</span><p><strong>Baca bukti pengerjaan</strong> melalui tugas, riwayat percobaan, dan cuplikan kode akhir.</p></div>
                                    <a href="{{ route('admin.lab.analytics') }}" class="guide-page-link">Buka Analitik Lab</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const htmlEl = document.documentElement;
            const themeButtons = document.querySelectorAll('[data-theme-toggle]');
            const darkIcons = document.querySelectorAll('[data-theme-dark-icon]');
            const lightIcons = document.querySelectorAll('[data-theme-light-icon]');
            const themeTexts = document.querySelectorAll('[data-theme-toggle-text]');

            function syncGuideHotspots() {
                document.querySelectorAll('.admin-shot').forEach(shot => {
                    const shotRect = shot.getBoundingClientRect();
                    if (!shotRect.width || !shotRect.height) return;

                    shot.querySelectorAll('.guide-hotspot[data-hotspot-target]').forEach(hotspot => {
                        const targets = Array.from(shot.querySelectorAll(hotspot.dataset.hotspotTarget))
                            .filter(target => {
                                const rect = target.getBoundingClientRect();
                                return rect.width > 0 && rect.height > 0;
                            });

                        if (!targets.length) {
                            hotspot.style.display = 'none';
                            return;
                        }

                        const pad = Number.parseFloat(hotspot.dataset.hotspotPad || '4');
                        const bounds = targets.reduce((box, target) => {
                            const rect = target.getBoundingClientRect();
                            return {
                                left: Math.min(box.left, rect.left),
                                top: Math.min(box.top, rect.top),
                                right: Math.max(box.right, rect.right),
                                bottom: Math.max(box.bottom, rect.bottom),
                            };
                        }, { left: Infinity, top: Infinity, right: -Infinity, bottom: -Infinity });

                        const edgeInset = 1;
                        const left = Math.max(edgeInset, bounds.left - shotRect.left - pad);
                        const top = Math.max(edgeInset, bounds.top - shotRect.top - pad);
                        const right = Math.min(shotRect.width - edgeInset, bounds.right - shotRect.left + pad);
                        const bottom = Math.min(shotRect.height - edgeInset, bounds.bottom - shotRect.top + pad);

                        hotspot.style.display = '';
                        hotspot.style.left = `${left}px`;
                        hotspot.style.top = `${top}px`;
                        hotspot.style.width = `${Math.max(0, right - left)}px`;
                        hotspot.style.height = `${Math.max(0, bottom - top)}px`;
                    });
                });
            }

            function syncThemeButton() {
                if (htmlEl.classList.contains('dark')) {
                    lightIcons.forEach(icon => icon.classList.remove('hidden'));
                    darkIcons.forEach(icon => icon.classList.add('hidden'));
                    themeTexts.forEach(text => text.textContent = 'Tema Terang');
                } else {
                    lightIcons.forEach(icon => icon.classList.add('hidden'));
                    darkIcons.forEach(icon => icon.classList.remove('hidden'));
                    themeTexts.forEach(text => text.textContent = 'Tema Gelap');
                }
            }

            syncThemeButton();

            themeButtons.forEach(button => button.addEventListener('click', function() {
                htmlEl.classList.toggle('dark');
                localStorage.setItem('color-theme', htmlEl.classList.contains('dark') ? 'dark' : 'light');
                syncThemeButton();
            }));

            requestAnimationFrame(syncGuideHotspots);
            setTimeout(syncGuideHotspots, 300);
            window.addEventListener('resize', syncGuideHotspots);
            document.querySelectorAll('.guide-chip, .admin-guide-trigger').forEach(control => {
                control.addEventListener('click', () => {
                    requestAnimationFrame(syncGuideHotspots);
                    setTimeout(syncGuideHotspots, 180);
                    setTimeout(syncGuideHotspots, 420);
                });
            });
        });
    </script>
</body>
</html>
