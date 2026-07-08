<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Analitik Kuis · Panel Admin Utilwind</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    
    {{-- RESOURCES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>

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
        body { font-family: 'Inter', sans-serif; transition: background-color 0.3s, color 0.3s; overflow-x: hidden; }
        body { background-color: #f8fafc; color: #0f172a; }
        body.dark { background-color: #020617; color: #e2e8f0; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }

        /* --- SCROLLBAR --- */
        .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #6366f1; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb { background: #334155; }
        .dark .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: #818cf8; }

        /* --- GLASS COMPONENTS (ADAPTIF TERANG/GELAP) --- */
        .glass-sidebar { background: rgba(255, 255, 255, 0.95); backdrop-filter: blur(20px); border-right: 1px solid rgba(0,0,0,0.05); z-index: 50; transition: 0.3s; }
        .dark .glass-sidebar { background: rgba(5, 8, 16, 0.95); border-right: 1px solid rgba(255, 255, 255, 0.08); }

        .glass-header { background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(0,0,0,0.05); z-index: 40; transition: 0.3s; }
        .dark .glass-header { background: rgba(2, 6, 23, 0.8); border-bottom: 1px solid rgba(255, 255, 255, 0.08); }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.85); border: 1px solid rgba(0,0,0,0.05);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.03); backdrop-filter: blur(10px);
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            position: relative; overflow: visible !important; z-index: 10;
        }
        .dark .glass-card {
            background: rgba(10, 14, 23, 0.85); border: 1px solid rgba(255, 255, 255, 0.08);
            box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2);
        }
        .glass-card:hover { border-color: rgba(99, 102, 241, 0.4); transform: translateY(-4px); box-shadow: 0 10px 40px -10px rgba(0,0,0,0.1); z-index: 30; }
        .dark .glass-card:hover { box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); }
        
        /* --- INPUTS & NAV --- */
        .glass-input { background: rgba(0, 0, 0, 0.03); border: 1px solid rgba(0, 0, 0, 0.1); color: #0f172a; transition: 0.3s; }
        .dark .glass-input { background: rgba(255, 255, 255, 0.03); border: 1px solid rgba(255, 255, 255, 0.1); color: white; }
        .glass-input:focus { border-color: #6366f1; background: rgba(0, 0, 0, 0.05); outline: none; box-shadow: 0 0 0 2px rgba(99, 102, 241, 0.2); }
        .dark .glass-input:focus { background: rgba(255, 255, 255, 0.05); border-color: #818cf8; box-shadow: 0 0 0 2px rgba(129, 140, 248, 0.2); }
        
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 12px 16px; border-radius: 12px; color: #64748b; font-weight: 600; font-size: 0.9rem; transition: all 0.2s; border: 1px solid transparent; }
        .dark .nav-link { color: #94a3b8; font-weight: 500; }
        .nav-link:hover { background: rgba(0, 0, 0, 0.03); color: #0f172a; }
        .dark .nav-link:hover { background: rgba(255, 255, 255, 0.03); color: white; }
        .nav-link.active { background: linear-gradient(90deg, rgba(99, 102, 241, 0.1) 0%, rgba(99, 102, 241, 0) 100%); color: #6366f1; border-left: 3px solid #6366f1; border-radius: 4px 12px 12px 4px; }
        .dark .nav-link.active { color: #818cf8; border-left-color: #818cf8; }

        .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.6s forwards ease-out; }
        @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
        
        .table-row { transition: background 0.2s; border-bottom: 1px solid rgba(0,0,0,0.03); }
        .table-row:hover { background: rgba(0,0,0,0.02); }
        .dark .table-row { border-bottom: 1px solid rgba(255,255,255,0.03); }
        .dark .table-row:hover { background: rgba(255,255,255,0.02); }

        .question-form-grid {
            display: grid;
            grid-template-columns: minmax(0, 1fr);
            gap: 1.25rem;
        }
        @media (min-width: 1024px) {
            .question-form-grid {
                grid-template-columns: minmax(0, 1fr) minmax(300px, 340px);
                align-items: start;
            }
        }
        .question-panel,
        .question-preview-panel {
            border: 1px solid #e2e8f0;
            background: rgba(255, 255, 255, 0.92);
            border-radius: 1.25rem;
            box-shadow: 0 12px 34px rgba(15, 23, 42, 0.05);
        }
        .dark .question-panel,
        .dark .question-preview-panel {
            border-color: rgba(255, 255, 255, 0.09);
            background: rgba(2, 6, 23, 0.42);
            box-shadow: 0 16px 40px rgba(0, 0, 0, 0.22);
        }
        .question-preview-panel { padding: 1rem; }
        @media (min-width: 640px) { .question-preview-panel { padding: 1.25rem; } }
        .question-kicker {
            margin-bottom: 0.25rem;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.18em;
            text-transform: uppercase;
            color: #0891b2;
        }
        .dark .question-kicker { color: #67e8f9; }
        .question-label {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 10px;
            font-weight: 900;
            letter-spacing: 0.14em;
            text-transform: uppercase;
            color: #64748b;
        }
        .dark .question-label { color: rgba(255, 255, 255, 0.48); }
        .question-input {
            width: 100%;
            border-radius: 0.9rem;
            border: 1px solid #e2e8f0;
            background: #ffffff;
            padding: 0.8rem 0.95rem;
            color: #0f172a;
            font-size: 0.875rem;
            outline: none;
            transition: border-color .2s ease, box-shadow .2s ease, background .2s ease;
        }
        .question-input:focus {
            border-color: #0891b2;
            box-shadow: 0 0 0 4px rgba(14, 165, 233, 0.12);
        }
        .dark .question-input {
            border-color: rgba(255, 255, 255, 0.10);
            background: rgba(2, 6, 23, 0.72);
            color: #f8fafc;
        }
        .dark .question-input:focus {
            border-color: #22d3ee;
            box-shadow: 0 0 0 4px rgba(34, 211, 238, 0.12);
        }
        .question-type-card {
            display: flex;
            align-items: flex-start;
            gap: 0.85rem;
            min-height: 92px;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            background: #fff;
            padding: 0.85rem;
            text-align: left;
            transition: border-color .2s ease, background .2s ease, box-shadow .2s ease, transform .2s ease;
        }
        .question-type-card:hover {
            border-color: #67e8f9;
            transform: translateY(-1px);
        }
        .question-type-card.is-active {
            border-color: #06b6d4;
            box-shadow: 0 0 0 2px rgba(6, 182, 212, 0.18), 0 14px 34px rgba(15, 23, 42, 0.08);
            background: rgba(236, 254, 255, 0.92);
        }
        .dark .question-type-card {
            border-color: rgba(255, 255, 255, 0.10);
            background: rgba(2, 6, 23, 0.58);
        }
        .dark .question-type-card:hover { border-color: rgba(103, 232, 249, 0.55); }
        .dark .question-type-card.is-active {
            border-color: rgba(34, 211, 238, 0.58);
            background: rgba(8, 145, 178, 0.12);
            box-shadow: 0 0 0 2px rgba(34, 211, 238, 0.14), 0 18px 40px rgba(0, 0, 0, 0.28);
        }
        .answer-input-row { display: flex; align-items: center; gap: 0.75rem; }
        .answer-letter,
        .preview-letter {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            border-radius: 0.8rem;
            font-size: 0.75rem;
            font-weight: 900;
        }
        .answer-letter {
            width: 2.5rem;
            height: 2.5rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            color: #64748b;
        }
        .dark .answer-letter {
            border-color: rgba(255, 255, 255, 0.10);
            background: rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.62);
        }
        .preview-option {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            border-radius: 0.9rem;
            border: 1px solid #e2e8f0;
            background: #f8fafc;
            padding: 0.7rem;
            color: #475569;
            font-size: 0.82rem;
            font-weight: 700;
        }
        .preview-letter {
            width: 2rem;
            height: 2rem;
            background: #e2e8f0;
            color: #475569;
        }
        .preview-option.is-correct {
            border-color: #86efac;
            background: #f0fdf4;
            color: #166534;
        }
        .preview-option.is-correct .preview-letter {
            background: #16a34a;
            color: white;
        }
        .dark .preview-option {
            border-color: rgba(255, 255, 255, 0.09);
            background: rgba(255, 255, 255, 0.04);
            color: rgba(255, 255, 255, 0.68);
        }
        .dark .preview-option.is-correct {
            border-color: rgba(74, 222, 128, 0.45);
            background: rgba(34, 197, 94, 0.12);
            color: #bbf7d0;
        }
        .question-media-card {
            background: linear-gradient(180deg, rgba(248, 250, 252, 0.96), rgba(241, 245, 249, 0.9));
        }
        .dark .question-media-card {
            background: linear-gradient(180deg, rgba(15, 23, 42, 0.92), rgba(2, 6, 23, 0.78));
        }
        .question-media-card img {
            aspect-ratio: 16 / 9;
        }
        .question-rich-text code {
            display: inline-flex;
            max-width: 100%;
            align-items: center;
            border: 1px solid #bae6fd;
            border-radius: 0.6rem;
            background: linear-gradient(135deg, #eff6ff, #ecfeff);
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.9);
            color: #0369a1;
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.92em;
            font-weight: 900;
            line-height: 1.6;
            overflow-wrap: anywhere;
            padding: 0.1rem 0.45rem;
            white-space: break-spaces;
        }
        .dark .question-rich-text code {
            border-color: rgba(103, 232, 249, 0.3);
            background: linear-gradient(135deg, rgba(8, 47, 73, 0.72), rgba(15, 23, 42, 0.94));
            box-shadow: inset 0 1px 0 rgba(255,255,255,0.08);
            color: #67e8f9;
        }
        .question-rich-text pre {
            margin: 0.75rem 0;
            max-width: 100%;
            overflow-x: auto;
            border: 1px solid rgba(148, 163, 184, 0.28);
            border-radius: 1rem;
            background: linear-gradient(135deg, #0f172a, #111827);
            box-shadow: 0 16px 32px rgba(15, 23, 42, 0.14);
            color: #dbeafe;
            font-family: 'JetBrains Mono', ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, monospace;
            font-size: 0.9rem;
            font-weight: 800;
            line-height: 1.75;
            padding: 0.95rem 1rem;
        }
        .question-rich-text pre code {
            display: block;
            border: 0;
            border-radius: 0;
            background: transparent;
            box-shadow: none;
            color: inherit;
            padding: 0;
            white-space: pre;
        }

        /* =========================================================================
           SISTEM TOOLTIP SUPER SOLID
           ========================================================================= */
        .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
        .tooltip-container:hover { z-index: 99999; }
        .tooltip-trigger { width: 18px; height: 18px; border-radius: 50%; color: #64748b; font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; cursor: help; transition: all 0.2s; border: 1px solid rgba(0,0,0,0.2); }
        .dark .tooltip-trigger { color: white; border-color: rgba(255,255,255,0.2); }
        .tooltip-trigger:hover { transform: scale(1.15); }
        
        .tooltip-content { opacity: 0; visibility: hidden; position: absolute; pointer-events: none; width: max-content; min-width: 220px; max-width: 280px; white-space: normal; text-align: left; background-color: #ffffff; color: #1e293b; font-size: 11px; padding: 14px 16px; line-height: 1.5; border-radius: 12px; box-shadow: 0 20px 40px rgba(0,0,0,0.1); z-index: 99999; border: 1px solid #e2e8f0; transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); }
        .dark .tooltip-content { background-color: #020617; color: #e2e8f0; box-shadow: 0 20px 60px rgba(0,0,0,1); border: 1px solid rgba(255,255,255,0.05); }

        .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
        .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #ffffff transparent; }
        .dark .tooltip-down .tooltip-content::after { border-color: transparent transparent #020617 transparent; }

        .tooltip-up .tooltip-content { bottom: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(10px); }
        .tooltip-up:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
        .tooltip-up .tooltip-content::after { content: ''; position: absolute; top: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: #ffffff transparent transparent transparent; }
        .dark .tooltip-up .tooltip-content::after { border-color: #0f141e transparent transparent transparent; }

        .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
        .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
        .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

        .tooltip-indigo .tooltip-trigger { background-color: #e0e7ff; color: #4f46e5; border-color: #c7d2fe; }
        .dark .tooltip-indigo .tooltip-trigger { background-color: #6366f1; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(99,102,241,0.5); }
        .tooltip-cyan .tooltip-trigger { background-color: #cffafe; color: #0891b2; border-color: #a5f3fc; }
        .dark .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
        .tooltip-emerald .tooltip-trigger { background-color: #d1fae5; color: #059669; border-color: #a7f3d0; }
        .dark .tooltip-emerald .tooltip-trigger { background-color: #10b981; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
        .tooltip-red .tooltip-trigger { background-color: #fecaca; color: #dc2626; border-color: #fca5a5; }
        .dark .tooltip-red .tooltip-trigger { background-color: #ef4444; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(239,68,68,0.5); }
        .tooltip-violet .tooltip-trigger { background-color: #ede9fe; color: #7c3aed; border-color: #ddd6fe; }
        .dark .tooltip-violet .tooltip-trigger { background-color: #8b5cf6; color: white; border-color: transparent; box-shadow: 0 0 10px rgba(139,92,246,0.5); }

        .modal-open { overflow: hidden; padding-right: 5px; }
        [x-cloak] { display: none !important; }
        .swal2-container.quiz-alert-layer { z-index: 2147483647 !important; }
        .quiz-alert-popup { border-radius: 1.25rem !important; }
        .text-adaptive { color: #1e293b; }
        .dark .text-adaptive { color: #f8fafc; }
        .text-adaptive-muted { color: #64748b; }
        .dark .text-adaptive-muted { color: rgba(255,255,255,0.4); }
    </style>

    {{-- =========================================================================
         DESAIN ANALITIK KUIS — RUANG BACA DAN VISUAL SOAL
         ========================================================================= --}}
    <style>
        :root {
            --quiz-surface: rgba(255,255,255,.92);
            --quiz-surface-soft: #f8fafc;
            --quiz-line: rgba(148,163,184,.24);
            --quiz-line-strong: rgba(99,102,241,.30);
            --quiz-muted: #64748b;
            --quiz-ink: #0f172a;
            --quiz-shadow: 0 16px 36px rgba(15,23,42,.06);
        }
        .dark {
            --quiz-surface: rgba(10,14,23,.90);
            --quiz-surface-soft: rgba(15,23,42,.78);
            --quiz-line: rgba(148,163,184,.16);
            --quiz-line-strong: rgba(129,140,248,.34);
            --quiz-muted: #94a3b8;
            --quiz-ink: #f8fafc;
            --quiz-shadow: 0 18px 42px rgba(2,6,23,.28);
        }

        #admin-main-content {
            scroll-behavior: smooth;
            scroll-padding-top: 7.5rem;
            overscroll-behavior-y: contain;
            scrollbar-gutter: stable both-edges;
        }

        .quiz-analytics-shell {
            display: flex;
            max-width: 90rem;
            flex-direction: column;
            gap: clamp(2rem, 3vw, 3.25rem);
            padding-bottom: 5.5rem;
        }
        .quiz-analytics-shell > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }
        .quiz-dashboard-stack,
        .quiz-question-detail-stack {
            display: flex;
            flex-direction: column;
            gap: clamp(1.5rem, 2.3vw, 2.5rem);
        }
        .quiz-dashboard-stack > *,
        .quiz-question-detail-stack > * {
            margin-top: 0 !important;
            margin-bottom: 0 !important;
        }

        .quiz-dashboard-stack .glass-card,
        .quiz-question-detail-stack .glass-card,
        .quiz-question-toolbar,
        .quiz-question-card {
            border-color: var(--quiz-line);
            box-shadow: var(--quiz-shadow);
        }

        .question-insight-overview {
            margin: 0 !important;
            padding: clamp(1.25rem, 2vw, 1.75rem) !important;
            border-color: var(--quiz-line) !important;
            border-radius: 1.5rem !important;
            background:
                radial-gradient(circle at 100% 0%, rgba(99,102,241,.09), transparent 18rem),
                var(--quiz-surface) !important;
            box-shadow: var(--quiz-shadow) !important;
        }
        .dark .question-insight-overview {
            background:
                radial-gradient(circle at 100% 0%, rgba(129,140,248,.13), transparent 19rem),
                var(--quiz-surface) !important;
        }
        .question-insight-overview > div:first-child {
            margin-bottom: 1.35rem !important;
            padding-bottom: 1.1rem !important;
            border-color: var(--quiz-line) !important;
        }
        .question-insight-overview .rounded-2xl {
            border-color: var(--quiz-line) !important;
            background: var(--quiz-surface-soft) !important;
        }
        .question-insight-overview .space-y-3 {
            display: grid;
            gap: .75rem;
        }
        .question-insight-overview .space-y-3 > * + * { margin-top: 0 !important; }
        .question-insight-overview article {
            padding: 1rem !important;
            border-color: var(--quiz-line) !important;
            background: var(--quiz-surface) !important;
            transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease;
        }
        .question-insight-overview article:hover {
            transform: translateY(-1px);
            border-color: var(--quiz-line-strong) !important;
            box-shadow: 0 12px 24px rgba(15,23,42,.07);
        }

        .quiz-chapter-layout {
            gap: clamp(1.5rem, 2.4vw, 2.5rem) !important;
            margin: 0 !important;
        }
        .quiz-chapter-layout > div { gap: 1.15rem !important; }
        .quiz-chapter-card-grid { gap: 1rem !important; }
        .quiz-chapter-card {
            min-height: 13.25rem !important;
            padding: 1.35rem !important;
            border-radius: 1.35rem !important;
            border-color: var(--quiz-line) !important;
            box-shadow: var(--quiz-shadow);
        }
        .quiz-chapter-card:hover { transform: translateY(-2px) !important; }
        .quiz-distribution-card {
            min-height: 100%;
            padding: 1.35rem !important;
            border-radius: 1.35rem !important;
            border-color: var(--quiz-line) !important;
            box-shadow: var(--quiz-shadow);
        }
        .quiz-final-card {
            padding: clamp(1.35rem, 2.4vw, 2rem) !important;
            border-radius: 1.45rem !important;
            border-color: var(--quiz-line) !important;
            box-shadow: var(--quiz-shadow);
        }
        .quiz-student-panel,
        .quiz-recent-panel {
            padding: clamp(1.25rem, 2vw, 1.5rem) !important;
            border-color: var(--quiz-line) !important;
            border-radius: 1.35rem !important;
            box-shadow: var(--quiz-shadow);
        }
        .quiz-student-analytics.analytics-panel {
            border-color: var(--quiz-line) !important;
            box-shadow: var(--quiz-shadow);
        }
        .quiz-student-analytics.analytics-panel > div:first-child {
            background: linear-gradient(115deg, var(--quiz-surface-soft), transparent) !important;
            border-color: var(--quiz-line) !important;
        }
        .quiz-student-analytics .analytics-student-grid {
            gap: 1.25rem !important;
        }
        .quiz-student-analytics .student-performance-card {
            border-color: var(--quiz-line) !important;
            background: var(--quiz-surface-soft) !important;
            padding: 1.2rem !important;
            transition: transform .18s ease, border-color .18s ease, background-color .18s ease, box-shadow .18s ease;
        }
        .quiz-student-analytics .student-performance-card:hover {
            border-color: var(--quiz-line-strong) !important;
            background: var(--quiz-surface) !important;
            box-shadow: 0 16px 32px rgba(15,23,42,.08);
        }
        .dark .quiz-student-analytics .student-performance-card:hover {
            box-shadow: 0 18px 38px rgba(0,0,0,.26);
        }
        .quiz-student-analytics .analytics-help {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 1.05rem;
            height: 1.05rem;
            border: 1px solid rgba(100,116,139,.38);
            border-radius: 999px;
            color: var(--quiz-muted);
            font-size: .62rem;
            font-weight: 900;
            line-height: 1;
            cursor: help;
            user-select: none;
            transition: border-color .16s ease, color .16s ease, background .16s ease;
        }
        .quiz-student-analytics .analytics-help:hover {
            border-color: var(--quiz-line-strong);
            background: rgba(99,102,241,.10);
            color: #4f46e5;
        }
        .dark .quiz-student-analytics .analytics-help:hover { color: #c7d2fe; }
        .quiz-student-analytics .analytics-help-inline {
            width: .95rem;
            height: .95rem;
            margin-left: .25rem;
            vertical-align: text-bottom;
            font-size: .57rem;
        }

        .quiz-question-toolbar {
            display: flex;
            flex-wrap: wrap;
            align-items: end;
            justify-content: space-between;
            gap: 1rem 1.5rem;
            padding: clamp(1.1rem, 2vw, 1.45rem);
            border: 1px solid var(--quiz-line);
            border-radius: 1.35rem;
            background:
                linear-gradient(135deg, rgba(99,102,241,.08), transparent 46%),
                var(--quiz-surface);
            box-shadow: var(--quiz-shadow);
        }
        .dark .quiz-question-toolbar {
            background:
                linear-gradient(135deg, rgba(129,140,248,.13), transparent 46%),
                var(--quiz-surface);
        }
        .quiz-question-toolbar-copy { min-width: min(100%, 16rem); }
        .quiz-question-toolbar-copy p {
            color: #4f46e5;
            font-size: .63rem;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
        }
        .dark .quiz-question-toolbar-copy p { color: #a5b4fc; }
        .quiz-question-toolbar-copy h3 {
            margin-top: .3rem;
            color: var(--quiz-ink);
            font-size: clamp(1rem, 1.6vw, 1.25rem);
            font-weight: 900;
            letter-spacing: -.025em;
        }
        .quiz-question-toolbar-copy span {
            display: block;
            margin-top: .3rem;
            color: var(--quiz-muted);
            font-size: .72rem;
            font-weight: 650;
        }
        .quiz-question-controls {
            display: grid;
            width: min(100%, 43rem);
            grid-template-columns: minmax(0, 1fr);
            gap: .65rem;
        }
        .quiz-question-controls input,
        .quiz-question-controls select {
            min-height: 2.9rem;
            border-color: var(--quiz-line) !important;
            background: var(--quiz-surface-soft) !important;
            box-shadow: none !important;
        }
        @media (min-width: 640px) {
            .quiz-question-controls { grid-template-columns: minmax(0,1fr) minmax(11.5rem,.38fr); }
        }

        .quiz-question-card-list {
            display: grid;
            gap: 1.15rem;
        }
        .quiz-question-card {
            overflow: hidden;
            border: 1px solid var(--quiz-line);
            border-radius: 1.45rem;
            background: var(--quiz-surface);
            transition: border-color .2s ease, box-shadow .2s ease, transform .2s ease;
        }
        .quiz-question-card:hover {
            transform: translateY(-2px);
            border-color: var(--quiz-line-strong);
            box-shadow: 0 20px 42px rgba(15,23,42,.10);
        }
        .dark .quiz-question-card:hover { box-shadow: 0 24px 48px rgba(0,0,0,.28); }
        .quiz-question-card-header {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 1rem;
            padding: 1rem 1.15rem;
            border-bottom: 1px solid var(--quiz-line);
            background: linear-gradient(110deg, var(--quiz-surface-soft), transparent);
        }
        .quiz-question-index {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-width: 2.15rem;
            height: 2.15rem;
            border: 1px solid var(--quiz-line);
            border-radius: .75rem;
            background: var(--quiz-surface);
            color: #4f46e5;
            font-size: .76rem;
            font-weight: 900;
            font-variant-numeric: tabular-nums;
        }
        .dark .quiz-question-index { color: #c7d2fe; }
        .quiz-question-meta {
            display: flex;
            min-width: 0;
            flex-wrap: wrap;
            align-items: center;
            gap: .45rem;
        }
        .quiz-question-meta > span {
            border: 1px solid var(--quiz-line);
            border-radius: 999px;
            background: var(--quiz-surface);
            padding: .3rem .55rem;
            color: var(--quiz-muted);
            font-size: .62rem;
            font-weight: 850;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .quiz-question-actions { display: flex; flex: 0 0 auto; gap: .45rem; }
        .quiz-question-actions button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 2.15rem;
            height: 2.15rem;
            border: 1px solid var(--quiz-line);
            border-radius: .72rem;
            background: var(--quiz-surface);
            color: var(--quiz-muted);
            transition: background-color .18s ease, color .18s ease, border-color .18s ease, transform .18s ease;
        }
        .quiz-question-actions button:hover { transform: translateY(-1px); }
        .quiz-question-actions .edit-question:hover { border-color: rgba(245,158,11,.48); background: #f59e0b; color: white; }
        .quiz-question-actions .delete-question:hover { border-color: rgba(239,68,68,.48); background: #ef4444; color: white; }

        .quiz-question-card-body {
            display: grid;
            gap: 1.15rem;
            padding: 1.15rem;
        }
        .quiz-question-main { min-width: 0; }
        .quiz-question-text {
            display: block;
            width: 100%;
            padding: .15rem 0;
            color: var(--quiz-ink);
            font-size: .88rem;
            font-weight: 750;
            line-height: 1.75;
            text-align: left;
        }
        .quiz-question-text:hover { color: #4f46e5; }
        .dark .quiz-question-text:hover { color: #c7d2fe; }
        .quiz-question-pills {
            display: flex;
            flex-wrap: wrap;
            gap: .45rem;
            margin-top: .9rem;
        }
        .quiz-question-pill {
            max-width: 100%;
            overflow: hidden;
            border: 1px solid var(--quiz-line);
            border-radius: .65rem;
            background: var(--quiz-surface-soft);
            padding: .32rem .52rem;
            color: var(--quiz-muted);
            font-size: .62rem;
            font-weight: 800;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .quiz-question-pill.tp { border-color: rgba(6,182,212,.25); background: rgba(236,254,255,.78); color: #0e7490; }
        .quiz-question-pill.type { border-color: rgba(217,70,239,.22); background: rgba(253,244,255,.72); color: #a21caf; }
        .dark .quiz-question-pill.tp { border-color: rgba(34,211,238,.22); background: rgba(6,182,212,.12); color: #a5f3fc; }
        .dark .quiz-question-pill.type { border-color: rgba(232,121,249,.20); background: rgba(217,70,239,.12); color: #f5d0fe; }
        .quiz-question-options {
            display: grid;
            gap: .55rem;
            margin-top: 1rem;
        }
        .quiz-question-option {
            display: flex;
            align-items: flex-start;
            gap: .62rem;
            min-width: 0;
            padding: .66rem .72rem;
            border: 1px solid var(--quiz-line);
            border-radius: .82rem;
            background: var(--quiz-surface-soft);
            color: var(--quiz-muted);
            font-size: .73rem;
            font-weight: 650;
            line-height: 1.45;
        }
        .quiz-question-option.is-correct {
            border-color: rgba(16,185,129,.30);
            background: rgba(236,253,245,.70);
            color: #047857;
        }
        .dark .quiz-question-option.is-correct { background: rgba(16,185,129,.10); color: #a7f3d0; }
        .quiz-option-letter {
            display: inline-flex;
            width: 1.45rem;
            height: 1.45rem;
            flex: 0 0 auto;
            align-items: center;
            justify-content: center;
            border-radius: .48rem;
            background: rgba(148,163,184,.16);
            color: var(--quiz-muted);
            font-size: .62rem;
            font-weight: 900;
        }
        .is-correct .quiz-option-letter { background: #10b981; color: white; }
        .quiz-question-option span:last-child { overflow-wrap: anywhere; }

        .question-analytics-rail {
            display: flex;
            flex-direction: column;
            gap: .9rem;
            min-width: 0;
            padding: 1rem;
            border: 1px solid var(--quiz-line);
            border-radius: 1.05rem;
            background: var(--quiz-surface-soft);
        }
        .question-analytics-rail .status-badge {
            display: inline-flex;
            width: fit-content;
            align-items: center;
            gap: .38rem;
            border: 1px solid var(--quiz-line);
            border-radius: 999px;
            padding: .34rem .55rem;
            font-size: .62rem;
            font-weight: 900;
            letter-spacing: .06em;
            text-transform: uppercase;
        }
        .status-badge.status-easy { border-color: rgba(16,185,129,.28); background: rgba(236,253,245,.75); color: #047857; }
        .status-badge.status-mid { border-color: rgba(245,158,11,.30); background: rgba(255,251,235,.78); color: #b45309; }
        .status-badge.status-hard { border-color: rgba(244,63,94,.30); background: rgba(255,241,242,.78); color: #be123c; }
        .status-badge.status-empty { color: var(--quiz-muted); }
        .dark .status-badge.status-easy { background: rgba(16,185,129,.10); color: #a7f3d0; }
        .dark .status-badge.status-mid { background: rgba(245,158,11,.10); color: #fde68a; }
        .dark .status-badge.status-hard { background: rgba(244,63,94,.10); color: #fecdd3; }
        .question-accuracy-readout {
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            gap: .75rem;
        }
        .question-accuracy-readout p { color: var(--quiz-muted); font-size: .62rem; font-weight: 900; letter-spacing: .12em; text-transform: uppercase; }
        .question-accuracy-readout strong { display:block; margin-top:.22rem; color:var(--quiz-ink); font-size:2.35rem; font-weight:900; letter-spacing:-.07em; line-height:1; font-variant-numeric: tabular-nums; }
        .question-accuracy-readout span { color:var(--quiz-muted); font-size:.68rem; font-weight:750; text-align:right; }
        .question-answer-meter {
            display: flex;
            overflow: hidden;
            height: .72rem;
            border-radius: 999px;
            background: rgba(148,163,184,.18);
        }
        .question-answer-meter > span { display: block; height:100%; min-width: 0; transition: width .35s cubic-bezier(.22,1,.36,1); }
        .question-answer-meter .answer-correct { background: #10b981; }
        .question-answer-meter .answer-wrong { background: #fb7185; }
        .question-answer-meter .answer-empty { width: 100%; background: #94a3b8; }
        .question-answer-legend {
            display: grid;
            grid-template-columns: repeat(2,minmax(0,1fr));
            gap: .55rem;
        }
        .question-answer-legend > div {
            padding: .6rem;
            border: 1px solid var(--quiz-line);
            border-radius: .72rem;
            background: var(--quiz-surface);
        }
        .question-answer-legend span { display:block; color: var(--quiz-muted); font-size:.57rem; font-weight:900; letter-spacing:.08em; text-transform:uppercase; }
        .question-answer-legend strong { display:block; margin-top:.18rem; color:var(--quiz-ink); font-size:1rem; font-weight:900; font-variant-numeric:tabular-nums; }
        .question-answer-legend .correct strong { color:#059669; }
        .question-answer-legend .wrong strong { color:#e11d48; }
        .quiz-question-card-footer {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: space-between;
            gap: .8rem;
            padding: .9rem 1.15rem 1.05rem;
            border-top: 1px solid var(--quiz-line);
            background: rgba(248,250,252,.55);
        }
        .dark .quiz-question-card-footer { background: rgba(2,6,23,.22); }
        .quiz-question-outcome {
            min-width: 0;
            color: var(--quiz-muted);
            font-size: .7rem;
            font-weight: 650;
            line-height: 1.5;
        }
        .quiz-question-outcome b { color:var(--quiz-ink); font-weight:900; }
        .quiz-question-insight-button {
            display: inline-flex;
            align-items: center;
            gap: .45rem;
            flex: 0 0 auto;
            padding: .6rem .75rem;
            border: 1px solid rgba(99,102,241,.20);
            border-radius: .75rem;
            background: rgba(238,242,255,.78);
            color: #4338ca;
            font-size: .68rem;
            font-weight: 900;
            transition: background-color .18s ease, border-color .18s ease, transform .18s ease;
        }
        .quiz-question-insight-button:hover { transform: translateY(-1px); border-color: rgba(99,102,241,.34); background:#eef2ff; }
        .dark .quiz-question-insight-button { border-color: rgba(129,140,248,.26); background: rgba(99,102,241,.12); color:#c7d2fe; }
        .dark .quiz-question-insight-button:hover { background: rgba(99,102,241,.20); }

        @media (min-width: 1024px) {
            .quiz-question-card-body { grid-template-columns: minmax(0,1fr) minmax(15rem,.32fr); padding: 1.35rem; }
            .quiz-question-options { grid-template-columns: repeat(2,minmax(0,1fr)); }
            .question-analytics-rail { align-self: stretch; }
        }
        @media (max-width: 767px) {
            .quiz-analytics-shell { gap: 1.5rem; padding-bottom: 3.5rem; }
            .quiz-dashboard-stack,
            .quiz-question-detail-stack { gap: 1.25rem; }
            .question-insight-overview { padding: 1rem !important; }
            .quiz-chapter-layout { gap: 1.25rem !important; }
            .quiz-question-toolbar { align-items: stretch; }
            .quiz-question-card-header,
            .quiz-question-card-body,
            .quiz-question-card-footer { padding-left: 1rem; padding-right: 1rem; }
            .question-accuracy-readout strong { font-size:2rem; }
        }
        @media (hover:none),(pointer:coarse) {
            .quiz-question-card:hover,
            .question-insight-overview article:hover,
            .quiz-chapter-card:hover { transform:none !important; }
        }
        @media (prefers-reduced-motion: reduce) {
            .quiz-question-card,
            .question-insight-overview article,
            .quiz-chapter-card,
            .question-answer-meter > span,
            .quiz-question-actions button,
            .quiz-question-insight-button { transition-duration:.01ms !important; animation-duration:.01ms !important; }
        }
    </style>


    {{-- =========================================================================
         PENYELARASAN ANALITIK KUIS
         Fokus pada pembacaan data per kelas dan proporsi panel admin awal.
         ========================================================================= --}}
    <style id="quiz-class-focus-refinement">
        /* Proporsi mengikuti panel awal: jarak lebih rapat, card tidak terlalu besar. */
        .quiz-analytics-shell {
            max-width: 84rem !important;
            gap: clamp(1.5rem, 2.2vw, 2.25rem) !important;
            padding-bottom: 4.25rem !important;
        }
        .quiz-dashboard-stack,
        .quiz-question-detail-stack {
            gap: clamp(1.25rem, 1.9vw, 1.75rem) !important;
        }
        .quiz-chapter-layout { gap: 1.25rem !important; }
        .quiz-chapter-layout > div { gap: .9rem !important; }
        .quiz-chapter-card {
            min-height: 11.65rem !important;
            padding: 1.1rem !important;
            border-radius: 1.15rem !important;
        }
        .quiz-distribution-card,
        .quiz-final-card,
        .quiz-student-panel,
        .quiz-recent-panel {
            border-radius: 1.15rem !important;
        }
        .quiz-distribution-card { padding: 1.1rem !important; }
        .quiz-final-card { padding: 1.2rem !important; }
        .quiz-student-analytics.analytics-panel > div:first-child {
            padding: 1.1rem 1.2rem !important;
        }
        .quiz-student-analytics .analytics-help,
        .quiz-student-analytics .analytics-help-inline { display: none !important; }

        /* Angka analitik sejajar dan mudah dipindai tanpa mengganti gaya Inter utama. */
        .quiz-analytics-shell .tabular-nums,
        .quiz-analytics-shell .font-mono,
        .quiz-analytics-shell .analytics-data-number,
        .quiz-analytics-shell .quiz-class-metric strong {
            font-variant-numeric: tabular-nums;
            letter-spacing: -.025em;
        }
        .quiz-analytics-shell .analytics-data-number {
            color: var(--quiz-ink);
            font-size: .92rem;
            font-weight: 850;
            line-height: 1.15;
        }
        .quiz-analytics-shell .analytics-data-label {
            color: var(--quiz-muted);
            font-size: .56rem;
            font-weight: 850;
            letter-spacing: .09em;
            line-height: 1.2;
            text-transform: uppercase;
        }

        /* Ringkasan performa kelas: data dibaca per baris, tanpa saran tindakan. */
        .quiz-class-performance-panel {
            overflow: hidden;
            border: 1px solid var(--quiz-line);
            border-radius: 1.15rem;
            background: var(--quiz-surface);
            box-shadow: var(--quiz-shadow);
        }
        .quiz-class-performance-head {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) repeat(5, minmax(0, .72fr));
            gap: .75rem;
            padding: .72rem 1.1rem;
            border-bottom: 1px solid var(--quiz-line);
            background: var(--quiz-surface-soft);
            color: var(--quiz-muted);
            font-size: .56rem;
            font-weight: 900;
            letter-spacing: .09em;
            text-transform: uppercase;
        }
        .quiz-class-row {
            display: grid;
            grid-template-columns: minmax(0, 1.25fr) repeat(5, minmax(0, .72fr));
            gap: .75rem;
            align-items: center;
            padding: .92rem 1.1rem;
            border-bottom: 1px solid var(--quiz-line);
            transition: background-color .18s ease;
        }
        .quiz-class-row:last-child { border-bottom: 0; }
        .quiz-class-row:hover { background: rgba(99, 102, 241, .035); }
        .dark .quiz-class-row:hover { background: rgba(129, 140, 248, .055); }
        .quiz-class-name {
            min-width: 0;
        }
        .quiz-class-name strong {
            display: block;
            overflow: hidden;
            color: var(--quiz-ink);
            font-size: .82rem;
            font-weight: 850;
            line-height: 1.3;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        .quiz-class-name span {
            display: block;
            margin-top: .2rem;
            color: var(--quiz-muted);
            font-size: .63rem;
            font-weight: 650;
            line-height: 1.2;
        }
        .quiz-class-metric { min-width: 0; text-align: center; }
        .quiz-class-metric strong {
            display: block;
            color: var(--quiz-ink);
            font-size: .91rem;
            font-weight: 850;
            line-height: 1.15;
        }
        .quiz-class-metric small {
            display: block;
            margin-top: .18rem;
            color: var(--quiz-muted);
            font-size: .60rem;
            font-weight: 700;
            line-height: 1.2;
        }
        .quiz-class-rate { color: #059669 !important; }
        .dark .quiz-class-rate { color: #6ee7b7 !important; }

        @media (max-width: 900px) {
            .quiz-class-performance-head { display: none; }
            .quiz-class-row {
                grid-template-columns: minmax(0, 1fr) repeat(2, minmax(0, 1fr));
                gap: .65rem;
                padding: 1rem;
            }
            .quiz-class-name { grid-column: 1 / -1; padding-bottom: .25rem; }
            .quiz-class-metric {
                padding: .62rem .68rem;
                border: 1px solid var(--quiz-line);
                border-radius: .7rem;
                background: var(--quiz-surface-soft);
            }
            .quiz-class-metric::before {
                content: attr(data-label);
                display: block;
                overflow: hidden;
                color: var(--quiz-muted);
                font-size: .53rem;
                font-weight: 850;
                letter-spacing: .08em;
                line-height: 1.15;
                text-overflow: ellipsis;
                text-transform: uppercase;
                white-space: nowrap;
            }
            .quiz-class-metric small { margin-top: .26rem; }
        }
        @media (max-width: 640px) {
            .quiz-analytics-shell { gap: 1.25rem !important; padding-bottom: 3rem !important; }
            .quiz-chapter-card { min-height: 10.5rem !important; padding: 1rem !important; }
            .quiz-class-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
            .quiz-class-metric { min-height: 4rem; }
            .quiz-student-analytics .max-h-\[460px\] { max-height: 390px; }
        }
    </style>

    {{-- =====================================================================
         INSIGHT HERO KELAS KUIS & KARTU EVALUASI AKHIR
         Menjaga proporsi panel awal sambil memperjelas pembacaan data kelas.
         ===================================================================== --}}
    <style id="quiz-class-hero-and-final-card">
        .quiz-final-chapter-card {
            overflow: hidden !important;
            background:
                linear-gradient(135deg, rgba(245, 158, 11, .10), transparent 58%),
                var(--quiz-surface) !important;
        }
        .dark .quiz-final-chapter-card {
            background:
                linear-gradient(135deg, rgba(245, 158, 11, .14), transparent 58%),
                var(--quiz-surface) !important;
        }
        .quiz-final-chapter-card::after {
            content: '';
            position: absolute;
            right: -1.75rem;
            top: -1.75rem;
            width: 6.25rem;
            height: 6.25rem;
            border: 14px solid rgba(245, 158, 11, .10);
            border-radius: 999px;
            pointer-events: none;
        }
        .dark .quiz-final-chapter-card::after { border-color: rgba(251, 191, 36, .13); }

        .quiz-class-row { position: relative; }

        .quiz-class-hero-modal {
            overflow: hidden;
            border: 1px solid rgba(99, 102, 241, .18);
            border-radius: 1.5rem;
            background: rgba(255, 255, 255, .98);
            box-shadow: 0 28px 78px rgba(15, 23, 42, .28);
        }
        .dark .quiz-class-hero-modal {
            border-color: rgba(129, 140, 248, .20);
            background: #0f141e;
            box-shadow: 0 30px 82px rgba(0, 0, 0, .74);
        }
        .quiz-class-hero-head {
            position: relative;
            overflow: hidden;
            border-bottom: 1px solid var(--quiz-line);
            background:
                linear-gradient(135deg, rgba(99, 102, 241, .15), rgba(255, 255, 255, .78) 58%, rgba(6, 182, 212, .10));
        }
        .quiz-class-hero-head::after {
            content: '';
            position: absolute;
            right: -3.8rem;
            top: -3.8rem;
            width: 10rem;
            height: 10rem;
            border: 18px solid rgba(99, 102, 241, .11);
            border-radius: 999px;
            pointer-events: none;
        }
        .dark .quiz-class-hero-head {
            background: linear-gradient(135deg, rgba(99, 102, 241, .20), rgba(15, 23, 42, .92) 58%, rgba(6, 182, 212, .12));
        }
        .dark .quiz-class-hero-head::after { border-color: rgba(129, 140, 248, .16); }
        .quiz-class-hero-kicker {
            color: #4f46e5;
            font-size: .60rem;
            font-weight: 900;
            letter-spacing: .16em;
            text-transform: uppercase;
        }
        .dark .quiz-class-hero-kicker { color: #a5b4fc; }
        .quiz-class-hero-metric {
            min-width: 0;
            border: 1px solid var(--quiz-line);
            border-radius: .95rem;
            background: var(--quiz-surface-soft);
            padding: .92rem;
        }
        .quiz-class-hero-metric p,
        .quiz-class-hero-section-label {
            color: var(--quiz-muted);
            font-size: .58rem;
            font-weight: 900;
            letter-spacing: .11em;
            text-transform: uppercase;
        }
        .quiz-class-hero-metric strong {
            display: block;
            margin-top: .35rem;
            color: var(--quiz-ink);
            font-size: 1.3rem;
            font-weight: 900;
            letter-spacing: -.05em;
            line-height: 1;
            font-variant-numeric: tabular-nums;
        }
        .quiz-class-hero-metric span {
            display: block;
            margin-top: .30rem;
            color: var(--quiz-muted);
            font-size: .64rem;
            font-weight: 700;
            line-height: 1.28;
        }
        .quiz-class-hero-meter {
            display: flex;
            overflow: hidden;
            height: .65rem;
            border-radius: 999px;
            background: rgba(148, 163, 184, .18);
        }
        .dark .quiz-class-hero-meter { background: rgba(255, 255, 255, .075); }
        .quiz-class-hero-meter > span { display: block; height: 100%; transition: width .35s cubic-bezier(.22,1,.36,1); }

        @media (max-width: 640px) {
            .quiz-class-hero-modal { border-radius: 1.25rem; }
        }
    </style>

</head>

{{-- ==============================================================================
     LOGIKA BLADE BULLETPROOF (Aman dari null reference & Syntax Error)
     ============================================================================== --}}
@php
    $selectedQuestionClass = trim((string) request('class_group', ''));
    $selectedQuestionPeriod = (string) request('period', 'all');
    $questionPeriodOptions = collect([
        'all' => 'Semua waktu',
        '7d' => '7 hari terakhir',
        '30d' => '30 hari terakhir',
        '6m' => '6 bulan terakhir',
    ]);

    if (!$questionPeriodOptions->has($selectedQuestionPeriod)) {
        $selectedQuestionPeriod = 'all';
    }

    $questionPeriodStart = match ($selectedQuestionPeriod) {
        '7d' => \Illuminate\Support\Carbon::now()->subDays(6)->startOfDay(),
        '30d' => \Illuminate\Support\Carbon::now()->subDays(29)->startOfDay(),
        '6m' => \Illuminate\Support\Carbon::now()->subMonths(6)->startOfDay(),
        default => null,
    };
    $questionPeriodLabel = $questionPeriodOptions[$selectedQuestionPeriod] ?? 'Semua waktu';
    $questionClassGroups = \Illuminate\Support\Facades\DB::table('class_groups')
        ->whereNotNull('token')
        ->where('token', '<>', '')
        ->orderBy('name')
        ->pluck('name')
        ->merge(
            \Illuminate\Support\Facades\DB::table('quiz_attempts')
                ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
                ->whereNotNull('quiz_attempts.completed_at')
                ->whereNotNull('users.class_group')
                ->where('users.class_group', '<>', '')
                ->when($questionPeriodStart, fn ($query) => $query->where('quiz_attempts.completed_at', '>=', $questionPeriodStart))
                ->distinct()
                ->pluck('users.class_group')
        )
        ->filter()
        ->unique()
        ->sort()
        ->values();

    if ($selectedQuestionClass !== '' && !$questionClassGroups->contains($selectedQuestionClass)) {
        $selectedQuestionClass = '';
    }

    $quizAttemptScope = \App\Models\QuizAttempt::query()
        ->whereNotNull('completed_at')
        ->when($questionPeriodStart, fn ($query) => $query->where('completed_at', '>=', $questionPeriodStart))
        ->when($selectedQuestionClass !== '', fn ($query) => $query->whereHas('user', fn ($userQuery) => $userQuery->where('class_group', $selectedQuestionClass)));

    $totalAttempts = $totalAttempts ?? (clone $quizAttemptScope)->count();
    $avgScore = $avgScore ?? ((clone $quizAttemptScope)->avg('score') ?? 0);
    $passRate = $passRate ?? ($totalAttempts > 0 ? ((clone $quizAttemptScope)->where('score', '>=', 70)->count() / $totalAttempts) * 100 : 0);
    $recentAttempts = isset($recentAttempts) ? collect($recentAttempts) : (clone $quizAttemptScope)->with('user')->latest()->take(5)->get();

    // Data Master Soal & Akurasi
    $questionsRaw = \App\Models\QuizQuestion::with('options')->get();
    $questions = $questionsRaw->map(function($q) use ($questionPeriodStart, $selectedQuestionClass) {
        $correctOptionText = $q->options->firstWhere('is_correct', 1)?->option_text ?? 'Tidak ada kunci';
        $latestStudentAnswers = \Illuminate\Support\Facades\DB::table('quiz_attempt_answers')
            ->join('quiz_attempts', 'quiz_attempt_answers.quiz_attempt_id', '=', 'quiz_attempts.id')
            ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
            ->leftJoin('quiz_options as chosen', 'quiz_attempt_answers.quiz_option_id', '=', 'chosen.id')
            ->where('quiz_attempt_answers.quiz_question_id', $q->id)
            ->whereNotNull('quiz_attempts.completed_at')
            ->select(
                'quiz_attempts.user_id',
                'users.name',
                'users.email',
                'users.class_group',
                'quiz_attempts.score',
                'quiz_attempts.completed_at',
                'quiz_attempt_answers.is_correct',
                'chosen.option_text as chosen_text'
            )
            ->orderByDesc('quiz_attempts.completed_at')
            ->when($questionPeriodStart, fn ($query) => $query->where('quiz_attempts.completed_at', '>=', $questionPeriodStart))
            ->when($selectedQuestionClass !== '', fn ($query) => $query->where('users.class_group', $selectedQuestionClass))
            ->get()
            ->unique('user_id')
            ->values();

        $q->total_attempts = $latestStudentAnswers->count();
        $q->correct_count = $latestStudentAnswers->where('is_correct', 1)->count();
        $q->wrong_count = $latestStudentAnswers->where('is_correct', 0)->count();
        $q->accuracy = $q->total_attempts > 0 ? round(($q->correct_count / $q->total_attempts) * 100) : 0;
        if ($q->total_attempts === 0) $q->status = 'Belum Ada Data';
        elseif ($q->accuracy >= 80) $q->status = 'Mudah';
        elseif ($q->accuracy >= 50) $q->status = 'Sedang';
        else $q->status = 'Sulit';
        $q->outcome_meta = \App\Support\LearningOutcomeAnalytics::quizOutcomeMetadata($q);
        $q->answer_summary = [
            'correct_count' => $q->correct_count,
            'wrong_count' => $q->wrong_count,
            'total_count' => $q->total_attempts,
            'correct_percent' => $q->accuracy,
            'wrong_percent' => $q->total_attempts > 0 ? round(($q->wrong_count / $q->total_attempts) * 100) : 0,
        ];
        $answerDetails = $latestStudentAnswers->map(function ($answer) use ($correctOptionText) {
            $isCorrect = (int) $answer->is_correct === 1;

            return [
                'name' => $answer->name ?: 'Tanpa nama',
                'email' => $answer->email ?: '-',
                'class_group' => $answer->class_group ?: 'Kelas belum diatur',
                'is_correct' => $isCorrect ? 1 : 0,
                'chosen' => $answer->chosen_text ?: 'Tidak dijawab',
                'correct' => $correctOptionText,
                'score' => is_null($answer->score) ? '-' : round((float) $answer->score, 1),
                'answered_at' => $answer->completed_at ? \Illuminate\Support\Carbon::parse($answer->completed_at)->timezone(config('app.timezone'))->format('d M Y H:i') : '-',
                'context' => $isCorrect
                    ? 'Siswa sudah memilih kunci yang sesuai pada percobaan terbaru.'
                    : 'Siswa memilih opsi yang belum sesuai pada percobaan terbaru.',
            ];
        });

        $q->list_correct = $answerDetails->where('is_correct', 1)->values()->all();
        $q->list_wrong = $answerDetails->where('is_correct', 0)->values()->all();

        return $q;
    });

    $totalQuestions = $questions->count();
    $totalAnswersGlobal = $questions->sum('total_attempts');
    $globalAcc = $totalAnswersGlobal > 0 ? round(($questions->sum('correct_count') / $totalAnswersGlobal) * 100, 1) : 0;
    $hardQuestionsCount = $questions->where('status', 'Sulit')->count();

    $chapterGroups = $questions->where('chapter_id', '!=', 99)->groupBy('chapter_id');

    $finalExam = $questions->where('chapter_id', 99);
    $hardestQuestions = $questions->where('status', 'Sulit')->sortBy('accuracy')->take(5)->map(function($q) {
        $q->failure_rate = 100 - $q->accuracy;
        return $q;
    });
    // Daftar Semua Siswa (Digunakan di UI Tabel Pencarian)
    $studentStats = \Illuminate\Support\Facades\DB::table('quiz_attempts')
        ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
        ->select(
            'users.id',
            'users.name',
            'users.email',
            'users.class_group',
            \Illuminate\Support\Facades\DB::raw('AVG(quiz_attempts.score) as avg_score'),
            \Illuminate\Support\Facades\DB::raw('COUNT(quiz_attempts.id) as total_attempts'),
            \Illuminate\Support\Facades\DB::raw('MAX(quiz_attempts.score) as highest_score'),
            \Illuminate\Support\Facades\DB::raw('MIN(quiz_attempts.score) as lowest_score'),
            \Illuminate\Support\Facades\DB::raw('SUM(CASE WHEN quiz_attempts.score >= 70 THEN 1 ELSE 0 END) as passed_attempts'),
            \Illuminate\Support\Facades\DB::raw('SUM(COALESCE(quiz_attempts.focus_lost_count, 0)) as focus_lost_count'),
            \Illuminate\Support\Facades\DB::raw('ROUND(AVG(COALESCE(quiz_attempts.time_spent_seconds, 0)) / 60, 0) as avg_time_minutes'),
            \Illuminate\Support\Facades\DB::raw('MAX(quiz_attempts.completed_at) as last_completed_at')
        )
        ->whereNotNull('completed_at')
        ->when($questionPeriodStart, fn ($query) => $query->where('quiz_attempts.completed_at', '>=', $questionPeriodStart))
        ->when($selectedQuestionClass !== '', fn ($query) => $query->where('users.class_group', $selectedQuestionClass))
        ->groupBy('users.id', 'users.name', 'users.email', 'users.class_group')
        ->orderByDesc('avg_score')
        ->get()
        ->map(function($stat) {
            $stat->avg_score = round($stat->avg_score, 1);
            $stat->highest_score = round((float) ($stat->highest_score ?? 0), 1);
            $stat->lowest_score = round((float) ($stat->lowest_score ?? 0), 1);
            $stat->passed_attempts = (int) ($stat->passed_attempts ?? 0);
            $stat->focus_lost_count = (int) ($stat->focus_lost_count ?? 0);
            $stat->avg_time_minutes = (int) ($stat->avg_time_minutes ?? 0);
            $stat->status_label = $stat->avg_score >= 70 ? 'Tuntas' : 'Belum tuntas';
            return $stat;
        });

    // Performa kuis per kelas dihitung dari rekap pengguna pada filter aktif.
    // Nilai rata-rata dan durasi dibobotkan oleh jumlah percobaan agar setiap sesi memiliki bobot yang sama.
    $quizClassPerformance = $studentStats
        ->groupBy(function ($stat) {
            $className = trim((string) ($stat->class_group ?? ''));
            return $className !== '' ? $className : 'Kelas belum diatur';
        })
        ->map(function ($classStudents, $className) {
            $classStudents = collect($classStudents);
            $studentsCount = (int) $classStudents->count();
            $attemptsCount = (int) $classStudents->sum(fn ($student) => (int) ($student->total_attempts ?? 0));
            $passedCount = (int) $classStudents->sum(fn ($student) => (int) ($student->passed_attempts ?? 0));
            $weightedScoreTotal = (float) $classStudents->sum(fn ($student) => (float) ($student->avg_score ?? 0) * (int) ($student->total_attempts ?? 0));
            $weightedDurationTotal = (float) $classStudents->sum(fn ($student) => (float) ($student->avg_time_minutes ?? 0) * (int) ($student->total_attempts ?? 0));

            return (object) [
                'class_group' => (string) $className,
                'students_count' => $studentsCount,
                'total_attempts' => $attemptsCount,
                'passed_attempts' => $passedCount,
                'pass_rate' => $attemptsCount > 0 ? round(($passedCount / $attemptsCount) * 100, 1) : 0,
                'avg_score' => $attemptsCount > 0 ? round($weightedScoreTotal / $attemptsCount, 1) : 0,
                'avg_duration_minutes' => $attemptsCount > 0 ? round($weightedDurationTotal / $attemptsCount) : 0,
            ];
        })
        ->sortByDesc('avg_score')
        ->values();
    $totalParticipants = \Illuminate\Support\Facades\DB::table('quiz_attempts')
        ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
        ->whereNotNull('quiz_attempts.completed_at')
        ->when($questionPeriodStart, fn ($query) => $query->where('quiz_attempts.completed_at', '>=', $questionPeriodStart))
        ->when($selectedQuestionClass !== '', fn ($query) => $query->where('users.class_group', $selectedQuestionClass))
        ->distinct('quiz_attempts.user_id')
        ->count('quiz_attempts.user_id');

    // ==========================================================================
    // EXTRAKSI DETAIL JAWABAN SISWA (TETAP DIAMAN-KAN UNTUK ALPINEJS)
    // ==========================================================================
    $studentDetailsMap = [];
    $allAnswers = \Illuminate\Support\Facades\DB::table('quiz_attempt_answers')
        ->join('quiz_attempts', 'quiz_attempt_answers.quiz_attempt_id', '=', 'quiz_attempts.id')
        ->join('users', 'quiz_attempts.user_id', '=', 'users.id')
        ->join('quiz_questions', 'quiz_attempt_answers.quiz_question_id', '=', 'quiz_questions.id')
        ->leftJoin('quiz_options as chosen', 'quiz_attempt_answers.quiz_option_id', '=', 'chosen.id')
        ->select(
            'users.email', 'users.name', 'quiz_attempts.chapter_id', 'quiz_attempts.score',
            'quiz_questions.question_text', 'quiz_attempt_answers.is_correct',
            'chosen.option_text as chosen_text', 'quiz_questions.id as question_id'
        )
        ->whereNotNull('quiz_attempts.completed_at')
        ->when($questionPeriodStart, fn ($query) => $query->where('quiz_attempts.completed_at', '>=', $questionPeriodStart))
        ->when($selectedQuestionClass !== '', fn ($query) => $query->where('users.class_group', $selectedQuestionClass))
        ->get();

    $correctOptions = \Illuminate\Support\Facades\DB::table('quiz_options')->where('is_correct', 1)->pluck('option_text', 'quiz_question_id');

    foreach($allAnswers as $ans) {
        $email = $ans->email;
        if(!isset($studentDetailsMap[$email])) {
            $studentDetailsMap[$email] = [
                'name' => $ans->name, 
                'email' => $email, 
                'summary_score' => $studentStats->where('email', $email)->first()->avg_score ?? 0,
                'summary_total' => $studentStats->where('email', $email)->first()->total_attempts ?? 0,
                'chapters' => []
            ];
        }
        $chId = $ans->chapter_id;
        if(!isset($studentDetailsMap[$email]['chapters'][$chId])) {
            $studentDetailsMap[$email]['chapters'][$chId] = [
                'title' => $chId == 99 ? 'Evaluasi Akhir' : 'Bab ' . $chId,
                'score' => $ans->score,
                'answers' => []
            ];
        }
        $studentDetailsMap[$email]['chapters'][$chId]['answers'][] = [
            'question' => $ans->question_text,
            'is_correct' => $ans->is_correct,
            'chosen' => $ans->chosen_text ?? 'Tidak dijawab',
            'correct' => $correctOptions[$ans->question_id] ?? 'Tidak ada kunci'
        ];
    }

    $questionFocusRows = $hardestQuestions->take(3);
    $unansweredQuestionCount = $questions->where('status', 'Belum Ada Data')->count();
    $questionAnalyticsUrl = function (array $overrides = []) use ($selectedQuestionClass, $selectedQuestionPeriod) {
        $query = [
            'class_group' => $selectedQuestionClass ?: null,
            'period' => $selectedQuestionPeriod !== 'all' ? $selectedQuestionPeriod : null,
            'chapter' => request('chapter'),
        ];

        foreach ($overrides as $key => $value) {
            $query[$key] = $value;
        }

        $query = array_filter($query, fn ($value) => filled($value));

        return route('admin.analytics.questions')
            . ($query ? ('?' . http_build_query($query)) : '');
    };
@endphp

{{-- Inject JSON data for AlpineJS --}}
<script id="student-data-json" type="application/json">
    {!! json_encode($studentDetailsMap) !!}
</script>

<body class="flex h-screen w-full bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 transition-colors duration-500" 
      x-data="{ 
          sidebarOpen: false,
          isFullscreen: false,
          currentView: 'dashboard', 
          activeChapter: null,
          activeChapterName: '',
          search: '',
          difficulty: 'all',
          searchStudent: '',
          
          showQuestionsModal: false,
          showParticipantsModal: false,
          showAccuracyModal: false,
          showHardModal: false,
          
          // State Detail Siswa
          studentDetails: {},
          showStudentDetailModal: false,
          selectedStudent: null,

          // Insight hero untuk pembacaan data kelas kuis.
          showClassInsightModal: false,
          selectedClassInsight: {},
          openClassInsight(data) {
              this.selectedClassInsight = data || {};
              this.showClassInsightModal = true;
          },

          showDashboardInfoModal: false,

          init() {
              const dataElement = document.getElementById('student-data-json');
              if(dataElement) {
                  this.studentDetails = JSON.parse(dataElement.textContent);
              }
              const chapterParam = new URLSearchParams(window.location.search).get('chapter');
              if (chapterParam) {
                  const chapterId = chapterParam === 'all' ? 'all' : Number(chapterParam);
                  const chapterName = chapterParam === '99' ? 'Evaluasi Akhir' : 'Bab ' + chapterParam;
                  this.selectChapter(chapterId, chapterName);
              }
          },

          selectChapter(id, name) {
              this.activeChapter = id;
              this.activeChapterName = name;
              this.currentView = 'table';
              this.search = '';
              window.currentQuestionBankChapter = id;
              window.currentQuestionBankChapterName = name;
          },
          resetView() {
              this.currentView = 'dashboard';
              this.activeChapter = null;
              window.currentQuestionBankChapter = null;
              window.currentQuestionBankChapterName = '';
          },
          openStudentDetail(email) {
              this.selectedStudent = this.studentDetails[email] || null;
              this.showStudentDetailModal = true;
          },
          matchesQuestionRow(el, chapterId) {
              const searchText = this.search.toLowerCase().trim();
              const haystack = (el.dataset.search || '').toLowerCase();
              const status = el.dataset.status || '';

              return (this.activeChapter === 'all' || this.activeChapter == chapterId) &&
                  (searchText === '' || haystack.includes(searchText)) &&
                  (this.difficulty === 'all' || this.difficulty === status);
          }
      }" 
      @keydown.escape.window="isFullscreen = false; document.exitFullscreen(); showQuestionsModal = false; showParticipantsModal = false; showAccuracyModal = false; showHardModal = false; showStudentDetailModal = false; showClassInsightModal = false; showDashboardInfoModal = false; closeModal(); closeInsightModal();" 
      :class="{'modal-open': sidebarOpen || showQuestionsModal || showParticipantsModal || showAccuracyModal || showHardModal || showStudentDetailModal || showClassInsightModal || showDashboardInfoModal}">

    <div x-show="sidebarOpen" class="fixed inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-sm z-[90] md:hidden transition-opacity" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>

     {{-- ==================== 1. SIDEBAR ==================== --}}
    <aside class="glass-sidebar w-72 h-full flex flex-col fixed md:relative z-[100] transition-transform duration-300 transform md:translate-x-0" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
        <div class="h-24 flex items-center justify-between px-8 border-b border-slate-200 dark:border-white/5 relative overflow-hidden group transition-colors">
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
    <main id="admin-main-content" class="flex-1 flex flex-col relative z-10 h-full overflow-y-auto overflow-x-hidden">
        
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
                        <button x-show="currentView === 'table'" @click="resetView()" x-cloak x-transition class="p-2 rounded-full bg-slate-100 dark:bg-white/5 hover:bg-slate-200 dark:hover:bg-white/10 text-slate-700 dark:text-white transition-colors group border border-transparent dark:border-white/10 shadow-sm" title="Kembali ke ringkasan bank soal">
                            <svg class="w-4 h-4 text-slate-500 dark:text-white/70 group-hover:text-slate-900 dark:group-hover:text-white transition-transform group-hover:-translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                        </button>

                        <div>
                            <nav class="flex text-[10px] text-slate-500 dark:text-white/50 mb-1.5 font-bold hidden sm:flex transition-colors" aria-label="Breadcrumb">
                                <ol class="inline-flex items-center space-x-1">
                                    <li class="inline-flex items-center"><a href="{{ route('admin.dashboard') ?? '#' }}" class="hover:text-indigo-600 dark:hover:text-indigo-400 transition-colors">Dasbor</a></li>
                                    <li>
                                        <div class="flex items-center transition-colors">
                                            <svg class="w-3 h-3 text-slate-400 dark:text-white/30 mx-1" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                            <span class="text-slate-700 dark:text-white transition-colors">Analitik Kuis</span>
                                        </div>
                                    </li>
                                </ol>
                            </nav>
                            <div class="flex items-center gap-2">
                                <h2 class="text-adaptive font-bold text-lg md:text-xl tracking-tight transition-colors" x-text="currentView === 'dashboard' ? 'Analitik Kuis' : 'Soal: ' + activeChapterName"></h2>
                                
                                {{-- TOMBOL TRIGGER HERO MODAL PANDUAN --}}
                                <button x-show="currentView === 'dashboard'" @click="showDashboardInfoModal = true" class="w-6 h-6 md:w-7 md:h-7 rounded-full border border-slate-200 dark:border-white/10 flex items-center justify-center text-[10px] md:text-xs font-black text-slate-400 dark:text-slate-500 hover:text-indigo-600 dark:hover:text-indigo-400 bg-white/50 dark:bg-white/5 backdrop-blur-sm hover:bg-white dark:hover:bg-white/10 hover:border-indigo-200 dark:hover:border-indigo-500/30 transition-all duration-300 shadow-sm hover:shadow-md focus:outline-none mt-0.5" title="Panduan Modul Soal">
                                    ?
                                </button>
                            </div>
                            <p class="text-[9px] md:text-xs text-adaptive-muted flex items-center gap-1.5 mt-0.5 transition-colors">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                <span x-text="currentView === 'dashboard' ? 'Akurasi, soal, dan evaluasi' : 'Bab terpilih'"></span>
                            </p>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-3 sm:gap-6">
                    <button onclick="window.location.reload()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden sm:block border border-transparent dark:hover:border-white/10" title="Perbarui Data">
                        <svg class="w-4 h-4 hover:rotate-180 transition-transform duration-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>

                    <button @click="isFullscreen = !isFullscreen; isFullscreen ? document.documentElement.requestFullscreen() : document.exitFullscreen()" class="p-2.5 text-slate-500 dark:text-white/40 hover:text-slate-900 dark:hover:text-white transition-colors rounded-full hover:bg-slate-200 dark:hover:bg-white/5 hidden md:block border border-transparent dark:hover:border-white/10" title="Mode Layar Penuh">
                        <svg x-show="!isFullscreen" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4"/></svg>
                        <svg x-show="isFullscreen" style="display: none;" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                    </button>

                    {{-- Action Button (Hanya Muncul di Mode Detail Bab) --}}
                    <div class="border-l border-slate-300 dark:border-white/10 pl-5 ml-1 hidden lg:block transition-colors" x-show="currentView === 'table'">
                        <button onclick="openModal('create')" class="flex items-center gap-2 px-4 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-xs font-bold shadow-md dark:shadow-[0_0_15px_rgba(99,102,241,0.3)] transition border border-indigo-500 dark:border-indigo-400">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg> Tambah Soal
                        </button>
                    </div>

                    <div class="text-right hidden lg:block border-l border-slate-300 dark:border-white/10 pl-5 ml-1 transition-colors">
                        <p class="text-sm font-bold text-adaptive">{{ \Carbon\Carbon::now()->translatedFormat('d M Y') }}</p>
                        <p class="text-[10px] text-adaptive-muted font-mono mt-0.5">{{ \Carbon\Carbon::now()->format('H:i') }} WIB</p>
                    </div>

                    {{-- Mobile Add Button --}}
                    <button onclick="openModal('create')" x-show="currentView === 'table'" class="lg:hidden p-2 rounded-lg bg-indigo-600 text-white shadow-lg">
                         <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    </button>
                </div>
            </div>
        </header>

        {{-- Scroll Area Data --}}
        <div class="flex-1 p-4 md:p-8 lg:p-10 relative z-10">
            <div class="quiz-analytics-shell max-w-[90rem] mx-auto">

                {{-- =======================================================
                     VIEW 1: DASHBOARD GRID (OVERVIEW)
                     ======================================================= --}}
                <div x-show="currentView === 'dashboard'" class="quiz-dashboard-stack" x-transition:enter="transition ease-out duration-300 transform" x-transition:enter-start="opacity-0 translate-y-3" x-transition:enter-end="opacity-100 translate-y-0">
                    @php
                        $analyticsTitle = 'Ringkasan Kuis';
                        $analyticsSubtitle = 'Data pada periode dan kelas yang dipilih.';
                        $analyticsItems = [
                            ['label' => 'Soal', 'value' => number_format($totalQuestions), 'hint' => 'soal tersimpan', 'tone' => 'cyan'],
                            ['label' => 'Peserta', 'value' => number_format($totalParticipants), 'hint' => 'pengguna dengan kuis selesai', 'tone' => 'indigo'],
                            ['label' => 'Akurasi', 'value' => $globalAcc . '%', 'hint' => number_format($totalAnswersGlobal) . ' respons jawaban', 'tone' => 'emerald'],
                            ['label' => 'Percobaan Kuis', 'value' => number_format($totalAttempts), 'hint' => 'riwayat kuis selesai', 'tone' => 'amber'],
                        ];
                        $analyticsActions = [];
                    @endphp
                    @include('admin.partials.compact_analytics_strip')

                    @php
                        $filterId = 'question-analytics-filter';
                        $filterTitle = 'Filter Data';
                        $filterSummary = $questionPeriodLabel . ($selectedQuestionClass ? ' · ' . $selectedQuestionClass : ' · Semua kelas');
                        $filterAction = route('admin.analytics.questions');
                        $filterHidden = ['chapter' => request('chapter')];
                        $filterControls = [
                            [
                                'name' => 'period',
                                'label' => 'Periode',
                                'selected' => $selectedQuestionPeriod,
                                'options' => $questionPeriodOptions,
                                'minWidth' => 'min-w-[180px]',
                            ],
                            [
                                'name' => 'class_group',
                                'label' => 'Kelas',
                                'selected' => $selectedQuestionClass,
                                'emptyLabel' => 'Semua kelas',
                                'options' => $questionClassGroups->mapWithKeys(fn ($className) => [$className => $className]),
                                'minWidth' => 'min-w-[220px]',
                            ],
                        ];
                        $filterResetHref = route('admin.analytics.questions');
                        $filterResetVisible = $selectedQuestionClass || $selectedQuestionPeriod !== 'all';
                    @endphp
                    @include('admin.partials.analytics_filter_bar')

                    {{-- 2. PERFORMA KUIS PER KELAS --}}
                    <section id="quiz-classes" class="analytics-section reveal scroll-mt-28" style="animation-delay: .08s;" aria-label="Performa kuis per kelas">
                        <div class="mb-3 flex flex-col gap-2 sm:flex-row sm:items-end sm:justify-between">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-cyan-600 dark:text-cyan-400">Perbandingan Kelas</p>
                                <h3 class="mt-1 text-lg font-bold text-slate-900 dark:text-white">Kinerja Kuis per Kelas</h3>
                            </div>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-white/45">{{ number_format($quizClassPerformance->count()) }} kelas · kuis selesai · pilih baris untuk insight</span>
                        </div>

                        <div class="quiz-class-performance-panel">
                            <div class="quiz-class-performance-head">
                                <span>Kelas</span>
                                <span class="text-center">pengguna</span>
                                <span class="text-center">Percobaan</span>
                                <span class="text-center">Kelulusan</span>
                                <span class="text-center">Skor rata-rata</span>
                                <span class="text-center">Durasi rata-rata</span>
                            </div>
                            @forelse($quizClassPerformance as $classRow)
                                @php
                                    $classStudentCount = max(0, (int) ($classRow->students_count ?? 0));
                                    $classAttemptCount = max(0, (int) ($classRow->total_attempts ?? 0));
                                    $classPassedCount = max(0, (int) ($classRow->passed_attempts ?? 0));
                                    $classNotPassedCount = max(0, $classAttemptCount - $classPassedCount);
                                    $classPassRate = round((float) ($classRow->pass_rate ?? 0), 1);
                                    $classAverageScore = round((float) ($classRow->avg_score ?? 0), 1);
                                    $classAverageDuration = max(0, (int) ($classRow->avg_duration_minutes ?? 0));
                                    $classAttemptsPerStudent = $classStudentCount > 0
                                        ? round($classAttemptCount / $classStudentCount, 1)
                                        : 0;
                                    $classInsightPayload = [
                                        'name' => $classRow->class_group ?: 'Kelas belum diatur',
                                        'students_count' => $classStudentCount,
                                        'total_attempts' => $classAttemptCount,
                                        'passed_attempts' => $classPassedCount,
                                        'not_passed_attempts' => $classNotPassedCount,
                                        'pass_rate' => $classPassRate,
                                        'avg_score' => $classAverageScore,
                                        'avg_duration_minutes' => $classAverageDuration,
                                        'attempts_per_student' => $classAttemptsPerStudent,
                                    ];
                                @endphp
                                <article
                                    role="button"
                                    tabindex="0"
                                    aria-label="Buka insight kinerja kuis kelas {{ $classRow->class_group }}"
                                    data-class-insight='@json($classInsightPayload, JSON_HEX_APOS | JSON_HEX_QUOT | JSON_HEX_AMP | JSON_HEX_TAG)'
                                    @click="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    @keydown.enter="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    @keydown.space.prevent="openClassInsight(JSON.parse($event.currentTarget.dataset.classInsight))"
                                    class="quiz-class-row cursor-pointer focus:outline-none focus-visible:ring-2 focus-visible:ring-inset focus-visible:ring-indigo-500/50"
                                >
                                    <div class="quiz-class-name">
                                        <strong>{{ $classRow->class_group }}</strong>
                                        <span>{{ number_format($classStudentCount) }} pengguna dengan riwayat kuis</span>
                                    </div>
                                    <div class="quiz-class-metric" data-label="pengguna">
                                        <strong>{{ number_format($classStudentCount) }}</strong>
                                        <small>pengguna</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Percobaan">
                                        <strong>{{ number_format($classAttemptCount) }}</strong>
                                        <small>{{ $classAttemptsPerStudent }} / pengguna</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Kelulusan">
                                        <strong class="quiz-class-rate">{{ number_format($classPassedCount) }}/{{ number_format($classAttemptCount) }}</strong>
                                        <small>{{ $classPassRate }}%</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Skor rata-rata">
                                        <strong>{{ $classAverageScore }}</strong>
                                        <small>dari 100</small>
                                    </div>
                                    <div class="quiz-class-metric" data-label="Durasi rata-rata">
                                        <strong>{{ number_format($classAverageDuration) }}</strong>
                                        <small>menit / sesi</small>
                                    </div>
                                </article>
                            @empty
                                <div class="px-5 py-10 text-center text-xs font-semibold text-slate-500 dark:text-white/40">
                                    Belum ada riwayat kuis yang dapat dikelompokkan berdasarkan kelas.
                                </div>
                            @endforelse
                        </div>
                    </section>

                    {{-- 3. KUIS PER BAB DAN EVALUASI AKHIR --}}
                    @php
                        // Empat kartu disajikan bersama agar Bab 1–3 dan evaluasi akhir dapat dibandingkan dalam satu grid 2 × 2.
                        $chapterMeta = [
                            1 => ['title' => 'BAB 1: Pendahuluan', 'desc' => 'Dasar HTML, CSS & Tailwind', 'color' => 'cyan', 'label' => 'Kuis Bab'],
                            2 => ['title' => 'BAB 2: Layouting', 'desc' => 'Sistem Flexbox & Grid', 'color' => 'indigo', 'label' => 'Kuis Bab'],
                            3 => ['title' => 'BAB 3: Styling', 'desc' => 'Efek, Dekorasi & Tipografi', 'color' => 'fuchsia', 'label' => 'Kuis Bab'],
                        ];

                        $chapterCards = collect($chapterMeta)->map(function ($meta, $id) use ($chapterGroups) {
                            $chapterQuestions = collect($chapterGroups->get($id, collect()));
                            $questionCount = $chapterQuestions->count();

                            return array_merge($meta, [
                                'id' => (int) $id,
                                'question_count' => $questionCount,
                                'accuracy' => $questionCount > 0 ? round($chapterQuestions->avg('accuracy')) : 0,
                                'is_final' => false,
                            ]);
                        })->values();

                        $finalQuestionCount = $finalExam->count();
                        $finalAccuracy = $finalQuestionCount > 0 ? round($finalExam->avg('accuracy')) : 0;
                        $chapterCards->push([
                            'id' => 99,
                            'title' => 'Evaluasi Akhir',
                            'desc' => 'Cakupan materi Bab 1–3',
                            'color' => 'amber',
                            'label' => 'Evaluasi',
                            'question_count' => $finalQuestionCount,
                            'accuracy' => $finalAccuracy,
                            'is_final' => true,
                        ]);
                    @endphp

                    <div class="quiz-chapter-layout grid grid-cols-1 lg:grid-cols-3">
                        <section class="lg:col-span-2 space-y-4" aria-label="Kuis per bab dan evaluasi akhir">
                            <div class="flex flex-col gap-1 sm:flex-row sm:items-end sm:justify-between">
                                <div>
                                    <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-indigo-600 dark:text-indigo-400">Struktur Evaluasi</p>
                                    <h3 class="mt-1 flex items-center gap-2 text-lg font-bold text-slate-900 transition-colors dark:text-white">
                                        <svg class="h-5 w-5 text-indigo-500 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/></svg>
                                        Kuis Bab dan Evaluasi Akhir
                                    </h3>
                                </div>
                                <span class="text-[10px] font-bold text-slate-500 dark:text-white/45">4 kelompok soal · pilih kartu untuk melihat detail</span>
                            </div>

                            <div class="quiz-chapter-card-grid grid grid-cols-1 gap-4 sm:grid-cols-2 reveal" style="animation-delay: 0.1s;">
                                @foreach($chapterCards as $card)
                                    <article
                                        role="button"
                                        tabindex="0"
                                        aria-label="Buka {{ $card['title'] }}"
                                        @click="selectChapter({{ $card['id'] }}, '{{ addslashes($card['title']) }}')"
                                        @keydown.enter="selectChapter({{ $card['id'] }}, '{{ addslashes($card['title']) }}')"
                                        @keydown.space.prevent="selectChapter({{ $card['id'] }}, '{{ addslashes($card['title']) }}')"
                                        class="quiz-chapter-card {{ $card['is_final'] ? 'quiz-final-chapter-card' : '' }} glass-card group flex cursor-pointer flex-col justify-between rounded-2xl border-t-2 border-{{ $card['color'] }}-400 transition-colors hover:border-{{ $card['color'] }}-400/60 focus:outline-none focus-visible:ring-2 focus-visible:ring-{{ $card['color'] }}-500/50"
                                    >
                                        <div class="relative z-10">
                                            <div class="mb-4 flex items-start justify-between gap-3">
                                                <div class="min-w-0">
                                                    <span class="inline-flex items-center rounded-lg border border-{{ $card['color'] }}-200 bg-{{ $card['color'] }}-50 px-3 py-1 font-mono text-xl font-black text-{{ $card['color'] }}-600 transition-colors dark:border-{{ $card['color'] }}-500/20 dark:bg-{{ $card['color'] }}-500/10 dark:text-{{ $card['color'] }}-400 md:text-2xl">
                                                        {{ $card['is_final'] ? 'EA' : sprintf('%02d', $card['id']) }}
                                                    </span>
                                                    <p class="mt-2 text-[9px] font-black uppercase tracking-[.14em] text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-300">{{ $card['label'] }}</p>
                                                </div>
                                                <div class="text-right">
                                                    <p class="text-xl font-black tabular-nums text-slate-900 transition-colors dark:text-white md:text-2xl">{{ $card['question_count'] }}</p>
                                                    <p class="text-[9px] font-bold uppercase tracking-widest text-slate-500 transition-colors dark:text-white/40">Soal</p>
                                                </div>
                                            </div>
                                            <h4 class="text-base font-bold text-slate-900 transition-colors group-hover:text-{{ $card['color'] }}-600 dark:text-white dark:group-hover:text-{{ $card['color'] }}-400 md:text-lg">{{ $card['title'] }}</h4>
                                            <p class="mt-1 text-[10px] text-slate-600 transition-colors dark:text-white/50 md:text-xs">{{ $card['desc'] }}</p>
                                        </div>

                                        <div class="relative z-10 mt-4">
                                            <div class="flex items-center justify-between gap-3 text-[9px] font-black uppercase tracking-[.11em] text-slate-500 dark:text-white/40">
                                                <span>Akurasi jawaban</span>
                                                <span class="tabular-nums text-{{ $card['color'] }}-700 dark:text-{{ $card['color'] }}-300">{{ $card['accuracy'] }}%</span>
                                            </div>
                                            <div class="mt-2 h-1.5 w-full overflow-hidden rounded-full bg-slate-200 shadow-inner transition-colors dark:bg-white/10">
                                                <div class="h-full bg-{{ $card['color'] }}-500 transition-all duration-1000" style="width: {{ $card['accuracy'] }}%"></div>
                                            </div>
                                        </div>
                                    </article>
                                @endforeach
                            </div>
                        </section>

                        <section class="space-y-4 reveal" style="animation-delay: 0.2s;" aria-label="Komposisi tingkat kesulitan soal">
                            <div>
                                <p class="text-[10px] font-extrabold uppercase tracking-[.18em] text-emerald-600 dark:text-emerald-400">Komposisi Soal</p>
                                <h3 class="mt-1 flex items-center gap-2 text-lg font-bold text-slate-900 transition-colors dark:text-white">
                                    <svg class="h-5 w-5 text-emerald-500 dark:text-emerald-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/><path stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"/></svg>
                                    Tingkat Kesulitan
                                </h3>
                            </div>
                            <div class="quiz-distribution-card glass-card flex flex-col items-center justify-center rounded-3xl transition-colors">
                                <div class="relative h-40 w-40 md:h-48 md:w-48">
                                    <canvas id="difficultyChart"></canvas>
                                    <div class="pointer-events-none absolute inset-0 flex flex-col items-center justify-center">
                                        <span class="text-2xl font-black text-slate-900 transition-colors dark:text-white md:text-3xl">{{ $totalQuestions }}</span>
                                        <span class="text-[9px] font-bold uppercase tracking-widest text-slate-500 transition-colors dark:text-white/40 md:text-[10px]">Total</span>
                                    </div>
                                </div>
                                <div class="mt-6 grid w-full grid-cols-3 gap-2 text-center">
                                    <div class="rounded-xl border border-emerald-200 bg-emerald-50 p-2 transition-colors dark:border-emerald-500/20 dark:bg-emerald-500/10">
                                        <span class="block text-xs font-bold text-emerald-600 dark:text-emerald-400 md:text-sm">{{ $questions->where('status', 'Mudah')->count() }}</span>
                                        <span class="text-[8px] uppercase text-slate-500 transition-colors dark:text-white/40 md:text-[9px]">Mudah</span>
                                    </div>
                                    <div class="rounded-xl border border-yellow-200 bg-yellow-50 p-2 transition-colors dark:border-yellow-500/20 dark:bg-yellow-500/10">
                                        <span class="block text-xs font-bold text-yellow-600 dark:text-yellow-400 md:text-sm">{{ $questions->where('status', 'Sedang')->count() }}</span>
                                        <span class="text-[8px] uppercase text-slate-500 transition-colors dark:text-white/40 md:text-[9px]">Sedang</span>
                                    </div>
                                    <div class="rounded-xl border border-red-200 bg-red-50 p-2 transition-colors dark:border-red-500/20 dark:bg-red-500/10">
                                        <span class="block text-xs font-bold text-red-600 dark:text-red-400 md:text-sm">{{ $hardQuestionsCount }}</span>
                                        <span class="text-[8px] uppercase text-slate-500 transition-colors dark:text-white/40 md:text-[9px]">Sulit</span>
                                    </div>
                                </div>
                            </div>
                        </section>
                    </div>

                    {{-- 5. PERFORMA pengguna --}}
                    <div id="quiz-students" class="analytics-section glass-card analytics-panel quiz-student-analytics rounded-2xl overflow-hidden reveal border-t-2 border-amber-500/50 scroll-mt-28" style="animation-delay: 0.4s;">
                        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-4 bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors">
                            <div>
                                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                                    Performa pengguna
                                </h3>
                                <p class="text-[10px] text-slate-500 dark:text-white/40 mt-1 transition-colors">Rata-rata skor, ketuntasan, durasi, dan catatan fokus setiap pengguna.</p>
                            </div>
                            <span class="text-[10px] bg-white dark:bg-[#020617] px-3 py-1.5 rounded-lg text-slate-500 dark:text-white/50 border border-slate-200 dark:border-white/10 shadow-sm dark:shadow-inner transition-colors">{{ $studentStats->count() }} pengguna</span>
                        </div>

                        <div class="p-5 md:p-6">
                            <div class="max-h-[460px] overflow-x-auto overflow-y-auto custom-scrollbar rounded-2xl border border-slate-200 bg-white/70 dark:border-white/10 dark:bg-[#020617]/60">
                                <table class="w-full min-w-[1120px] text-left text-xs">
                                    <thead class="sticky top-0 z-10 bg-slate-50/95 text-[9px] font-black uppercase tracking-widest text-slate-400 backdrop-blur dark:bg-[#0a0e17]/95 dark:text-white/30">
                                        <tr>
                                            <th class="px-4 py-3">
                                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap">Nama Pengguna</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Percobaan Kuis</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Skor Rata-rata</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Rentang Skor</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Ketuntasan Kuis</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Gangguan Fokus</span>
                                            </th>
                                            <th class="px-3 py-3 text-center">
                                                <span class="inline-flex items-center justify-center gap-1.5 whitespace-nowrap">Durasi Rata-rata</span>
                                            </th>
                                            <th class="px-3 py-3">
                                                <span class="inline-flex items-center gap-1.5 whitespace-nowrap">Pengerjaan Terakhir</span>
                                            </th>
                                            <th class="px-4 py-3 text-right">
                                                <span class="inline-flex items-center justify-end gap-1.5 whitespace-nowrap">Detail</span>
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="divide-y divide-slate-100 dark:divide-white/5">
                                        @forelse($studentStats as $stat)
                                            @php
                                                $score = round((float) ($stat->avg_score ?? 0), 1);
                                                $passed = (int) ($stat->passed_attempts ?? 0);
                                                $totalTries = (int) ($stat->total_attempts ?? 0);
                                                $failed = max(0, $totalTries - $passed);
                                                $passPercent = $totalTries > 0 ? round(($passed / $totalTries) * 100) : 0;
                                                $focusLost = (int) ($stat->focus_lost_count ?? 0);
                                                $studentQuizAnalyticsUrl = route('admin.quiz.student.analytics', $stat->id ?? 0);
                                            @endphp
                                            <tr class="table-row">
                                                <td class="px-4 py-3">
                                                    <p class="max-w-[220px] truncate text-sm font-black text-slate-900 dark:text-white">{{ $stat->name ?? 'Siswa' }}</p>
                                                    <p class="mt-0.5 truncate text-[10px] font-semibold text-slate-500 dark:text-white/40">{{ $stat->class_group ?: 'Kelas belum diatur' }}</p>
                                                </td>
                                                <td class="px-3 py-3 text-center font-mono font-black text-adaptive">{{ number_format($totalTries) }}</td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="font-black {{ $score >= 70 ? 'text-emerald-600 dark:text-emerald-300' : 'text-rose-600 dark:text-rose-300' }}">{{ $score }}</span>
                                                </td>
                                                <td class="px-3 py-3 text-center font-mono text-adaptive-muted">{{ $stat->lowest_score ?? 0 }}-{{ $stat->highest_score ?? 0 }}</td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="font-mono font-black text-cyan-700 dark:text-cyan-300">{{ $passed }}/{{ $totalTries }}</span>
                                                    <span class="ml-1 text-[10px] font-bold text-adaptive-muted">({{ $passPercent }}%)</span>
                                                    @if($failed > 0)
                                                        <span class="ml-1 rounded-md bg-rose-50 px-1.5 py-0.5 text-[9px] font-black text-rose-700 dark:bg-rose-500/10 dark:text-rose-300">{{ $failed }} belum</span>
                                                    @endif
                                                </td>
                                                <td class="px-3 py-3 text-center">
                                                    <span class="rounded-lg px-2 py-1 text-[10px] font-black {{ $focusLost > 0 ? 'bg-amber-50 text-amber-700 dark:bg-amber-500/10 dark:text-amber-300' : 'bg-slate-50 text-slate-500 dark:bg-white/5 dark:text-white/45' }}">{{ $focusLost }}</span>
                                                </td>
                                                <td class="px-3 py-3 text-center font-mono text-adaptive-muted">{{ $stat->avg_time_minutes ?? 0 }} mnt</td>
                                                <td class="px-3 py-3 text-[10px] font-semibold text-adaptive-muted">{{ $stat->last_completed_at ? \Carbon\Carbon::parse($stat->last_completed_at)->diffForHumans() : '-' }}</td>
                                                <td class="px-4 py-3 text-right">
                                                    <a href="{{ $studentQuizAnalyticsUrl }}" class="inline-flex items-center justify-center rounded-lg bg-indigo-50 px-3 py-2 text-[10px] font-black text-indigo-700 transition hover:bg-indigo-600 hover:text-white dark:bg-indigo-500/10 dark:text-indigo-300 dark:hover:bg-indigo-600 dark:hover:text-white">
                                                        Detail
                                                    </a>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="9" class="px-4 py-10 text-center text-xs font-semibold text-slate-500 dark:text-white/40">
                                                    Belum ada data riwayat pengerjaan kuis siswa.
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- =======================================================
                     VIEW 2: DETAIL BANK SOAL PER BAB
                     Visual kartu memberi ruang lebih lega untuk membaca soal,
                     opsi jawaban, dan data respons siswa.
                     ======================================================= --}}
                <div x-show="currentView === 'table'" x-cloak class="quiz-question-detail-stack"
                     x-transition:enter="transition ease-out duration-300 transform"
                     x-transition:enter-start="opacity-0 translate-y-3"
                     x-transition:enter-end="opacity-100 translate-y-0">

                    {{-- Kontrol pencarian dan filter --}}
                    <section class="quiz-question-toolbar" aria-label="Filter bank soal">
                        <div class="quiz-question-toolbar-copy">
                            <p>Bank Soal</p>
                            <h3 x-text="activeChapterName || 'Semua Bab'"></h3>
                            <span>Telusuri soal, lihat respons siswa, lalu buka detail untuk meninjau jawaban.</span>
                        </div>

                        <div class="quiz-question-controls">
                            <div class="relative group">
                                <input x-model="search" type="text" placeholder="Cari teks soal atau opsi jawaban..."
                                       class="w-full rounded-xl pl-10 pr-4 py-3 text-xs md:text-sm text-slate-900 dark:text-white outline-none transition placeholder-slate-400 dark:placeholder-white/30">
                                <div class="absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 dark:text-white/30 group-focus-within:text-indigo-600 dark:group-focus-within:text-indigo-300 transition">
                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                </div>
                            </div>
                            <select x-model="difficulty" class="w-full rounded-xl px-4 py-3 text-xs text-slate-700 dark:text-white outline-none cursor-pointer transition">
                                <option value="all">Semua status</option>
                                <option value="Sulit">Sulit · di bawah 50%</option>
                                <option value="Sedang">Sedang · 50–79%</option>
                                <option value="Mudah">Mudah · 80% ke atas</option>
                                <option value="Belum Ada Data">Belum ada respons</option>
                            </select>
                        </div>
                    </section>

                    {{-- Kartu per soal --}}
                    @if($questions->count() > 0)
                        <section class="quiz-question-card-list" aria-label="Daftar soal dan analitik respons">
                            @foreach($questions as $q)
                                @php
                                    $questionSearchText = strtolower(trim(strip_tags(
                                        (string) ($q->question_text ?? '') . ' ' . (($q->options ?? collect())->pluck('option_text')->join(' '))
                                    )));
                                    $questionAccuracy = min(100, max(0, (int) ($q->accuracy ?? 0)));
                                    $questionTotal = max(0, (int) ($q->total_attempts ?? 0));
                                    $questionCorrect = max(0, (int) ($q->correct_count ?? 0));
                                    $questionWrong = max(0, (int) ($q->wrong_count ?? 0));
                                    $questionWrongPercent = $questionTotal > 0 ? min(100, max(0, 100 - $questionAccuracy)) : 0;
                                    $questionStatus = $q->status ?? 'Belum Ada Data';
                                    $statusBadgeClass = match ($questionStatus) {
                                        'Mudah' => 'status-easy',
                                        'Sedang' => 'status-mid',
                                        'Sulit' => 'status-hard',
                                        default => 'status-empty',
                                    };
                                    $questionInteractionType = !empty($q->media_url)
                                        ? 'image_context'
                                        : ($q->interaction_type ?? 'multiple_choice');
                                    $questionTypeLabel = [
                                        'multiple_choice' => 'Pilihan ganda',
                                        'image_context' => 'Soal gambar',
                                    ][$questionInteractionType] ?? 'Pilihan ganda';
                                    $questionChapterLabel = (int) ($q->chapter_id ?? 0) === 99
                                        ? 'Evaluasi akhir'
                                        : 'Bab ' . ($q->chapter_id ?? '-');
                                    $questionOutcomeCode = $q->outcome_meta['display_code']
                                        ?? ($q->learning_objective_code ?: 'TP Umum');
                                    $questionOutcomeTitle = $q->outcome_meta['title']
                                        ?? ($q->learning_objective_title ?: 'Tujuan pembelajaran umum');
                                @endphp

                                <article class="quiz-question-card question-row"
                                         data-search="{{ e($questionSearchText) }}"
                                         data-status="{{ e($questionStatus) }}"
                                         x-show="matchesQuestionRow($el, {{ (int) ($q->chapter_id ?? 0) }})"
                                         x-transition.opacity.duration.200ms>

                                    <header class="quiz-question-card-header">
                                        <div class="flex min-w-0 items-center gap-3">
                                            <span class="quiz-question-index">{{ str_pad((string) $loop->iteration, 2, '0', STR_PAD_LEFT) }}</span>
                                            <div class="quiz-question-meta">
                                                <span>{{ $questionChapterLabel }}</span>
                                                <span>{{ $questionTypeLabel }}</span>
                                                <span>{{ $questionOutcomeCode }}</span>
                                            </div>
                                        </div>

                                        <div class="quiz-question-actions" aria-label="Aksi soal">
                                            <button type="button" class="edit-question"
                                                    onclick='openModal("edit", @json($q))'
                                                    title="Perbarui soal">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>
                                            <button type="button" class="delete-question"
                                                    onclick="confirmHapus('{{ $q->id }}')"
                                                    title="Hapus soal">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </header>

                                    <div class="quiz-question-card-body">
                                        <div class="quiz-question-main">
                                            @if(!empty($q->media_url))
                                                <div data-media-card class="question-media-card mb-4 overflow-hidden rounded-2xl border border-slate-200 dark:border-white/10 shadow-sm">
                                                    <div class="flex items-center justify-between border-b border-slate-200 dark:border-white/10 px-3 py-2">
                                                        <span class="text-[9px] font-black uppercase tracking-[0.20em] text-cyan-700 dark:text-cyan-300">Media soal</span>
                                                        <span class="rounded-md border border-slate-200 dark:border-white/10 bg-white/70 dark:bg-white/5 px-2 py-0.5 text-[9px] font-bold text-slate-500 dark:text-white/45">Gambar</span>
                                                    </div>
                                                    <img src="{{ $q->media_url }}" alt="{{ $q->media_caption ?: 'Media soal' }}" loading="lazy"
                                                         onerror="this.closest('[data-media-card]').classList.add('hidden')"
                                                         class="h-40 w-full object-contain bg-slate-100 dark:bg-[#020617] md:h-48">
                                                    @if(!empty($q->media_caption))
                                                        <p class="px-3 py-2 text-[10px] text-slate-500 dark:text-white/50">{{ $q->media_caption }}</p>
                                                    @endif
                                                </div>
                                            @endif

                                            <div onclick="openInsightModalById({{ (int) $q->id }})"
                                                 onkeydown="if(event.key === 'Enter' || event.key === ' ') { event.preventDefault(); openInsightModalById({{ (int) $q->id }}); }"
                                                 role="button" tabindex="0" title="Buka ringkasan respons siswa"
                                                 class="quiz-question-text question-rich-text cursor-pointer focus:outline-none focus:ring-2 focus:ring-indigo-400/40">
                                                {!! $q->question_text !!}
                                            </div>

                                            <div class="quiz-question-pills">
                                                <span class="quiz-question-pill tp">{{ $questionOutcomeCode }}</span>
                                                <span class="quiz-question-pill" title="{{ $questionOutcomeTitle }}">{{ \Illuminate\Support\Str::limit($questionOutcomeTitle, 86) }}</span>
                                                <span class="quiz-question-pill type">{{ $questionTypeLabel }}</span>
                                            </div>

                                            <div class="quiz-question-options">
                                                @if(isset($q->options))
                                                    @foreach($q->options as $idx => $opt)
                                                        <div class="quiz-question-option {{ $opt->is_correct ? 'is-correct' : '' }}">
                                                            <span class="quiz-option-letter">{{ ['A','B','C','D','E'][$idx] ?? ($idx + 1) }}</span>
                                                            <span>{{ \Illuminate\Support\Str::limit($opt->option_text, 120) }}</span>
                                                            @if($opt->is_correct)
                                                                <svg class="ml-auto mt-0.5 h-4 w-4 shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                            @endif
                                                        </div>
                                                    @endforeach
                                                @endif
                                            </div>
                                        </div>

                                        <aside class="question-analytics-rail" aria-label="Analitik soal">
                                            <span class="status-badge {{ $statusBadgeClass }}">{{ $questionStatus }}</span>

                                            <div class="question-accuracy-readout">
                                                <div>
                                                    <p>Akurasi</p>
                                                    <strong>{{ $questionAccuracy }}%</strong>
                                                </div>
                                                <span>{{ number_format($questionTotal) }}<br>respons siswa</span>
                                            </div>

                                            <div class="question-answer-meter" aria-label="Perbandingan jawaban benar dan salah">
                                                @if($questionTotal > 0)
                                                    <span class="answer-correct" style="width: {{ $questionAccuracy }}%"></span>
                                                    <span class="answer-wrong" style="width: {{ $questionWrongPercent }}%"></span>
                                                @else
                                                    <span class="answer-empty"></span>
                                                @endif
                                            </div>

                                            <div class="question-answer-legend">
                                                <div class="correct">
                                                    <span>Benar</span>
                                                    <strong>{{ number_format($questionCorrect) }}</strong>
                                                </div>
                                                <div class="wrong">
                                                    <span>Salah</span>
                                                    <strong>{{ number_format($questionWrong) }}</strong>
                                                </div>
                                            </div>
                                        </aside>
                                    </div>

                                    <footer class="quiz-question-card-footer">
                                        <p class="quiz-question-outcome">
                                            <b>TP terkait:</b> {{ \Illuminate\Support\Str::limit($questionOutcomeTitle, 150) }}
                                        </p>
                                        <button type="button" onclick="openInsightModalById({{ (int) $q->id }})"
                                                class="quiz-question-insight-button">
                                            Lihat respons siswa
                                            <svg class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round" d="m9 5 7 7-7 7"/></svg>
                                        </button>
                                    </footer>
                                </article>
                            @endforeach
                        </section>
                    @else
                        <section class="glass-card rounded-2xl p-10 text-center flex flex-col items-center justify-center min-h-[300px] opacity-75">
                            <div class="mb-4 text-4xl grayscale">📂</div>
                            <h3 class="font-bold text-slate-900 dark:text-white">Bank soal belum tersedia</h3>
                            <p class="mt-2 text-xs text-slate-500 dark:text-white/50">Tidak ada soal pada bab ini.</p>
                        </section>
                    @endif
                </div>

            </div>
        </div>
    </main>
</div>


{{-- ==================== MODALS HERO INSIGHTS ==================== --}}

{{-- Detail jawaban per siswa --}}
<div x-show="showStudentDetailModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 md:p-10" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showStudentDetailModal = false"></div>
    <div class="relative w-full max-w-4xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-2xl shadow-2xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-0 transition-colors duration-500 overflow-hidden flex flex-col h-full max-h-[85vh] md:max-h-full" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        
        {{-- Header Modal Detail --}}
        <div class="p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0a0e17] flex justify-between items-center transition-colors shrink-0">
            <div class="flex items-center gap-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-full bg-indigo-100 dark:bg-indigo-500/20 flex items-center justify-center text-xl font-bold text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 transition-colors" x-text="selectedStudent ? selectedStudent.name.charAt(0) : 'U'"></div>
                    <div>
                        <h3 class="text-lg font-bold text-slate-900 dark:text-white transition-colors" x-text="selectedStudent ? selectedStudent.name : 'Memuat data...'"></h3>
                        <p class="text-xs text-slate-500 dark:text-white/50 font-mono transition-colors" x-text="selectedStudent ? selectedStudent.email : '...'"></p>
                    </div>
                </div>
                {{-- Global Score Info inside Header --}}
                <div class="border-l border-slate-200 dark:border-white/10 pl-6 flex gap-4 hidden sm:flex">
                    <div>
                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/40">Sesi</p>
                        <p class="text-base font-black text-slate-700 dark:text-white" x-text="selectedStudent?.summary_total"></p>
                    </div>
                    <div>
                        <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/40">Rata-rata</p>
                        <p class="text-base font-black text-emerald-600 dark:text-emerald-400" x-text="selectedStudent?.summary_score"></p>
                    </div>
                </div>
            </div>
            <button @click="showStudentDetailModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2.5 rounded-full border border-transparent dark:hover:border-red-500/30">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>

        {{-- Body Modal Scrollable (Looping Data Jawaban Asli AlpineJS) --}}
        <div class="flex-1 overflow-y-auto custom-scrollbar p-6 space-y-6">
            <template x-if="selectedStudent && Object.keys(selectedStudent.chapters).length > 0">
                <div>
                    <template x-for="(chapter, chId) in selectedStudent.chapters" :key="chId">
                        <div class="border border-slate-200 dark:border-white/10 rounded-xl overflow-hidden transition-colors mb-6" x-data="{ open: false }">
                            {{-- Accordion Header --}}
                            <div class="bg-slate-50 dark:bg-[#0a0e17]/50 p-4 flex justify-between items-center cursor-pointer hover:bg-slate-100 dark:hover:bg-white/5 transition-colors" @click="open = !open">
                                <div class="flex items-center gap-3">
                                    <span class="px-2 py-1 bg-slate-200 dark:bg-white/10 text-slate-500 dark:text-white/50 text-[10px] font-bold rounded uppercase tracking-widest transition-colors" x-text="chId == 99 ? 'Evaluasi' : 'BAB ' + chId"></span>
                                    <h4 class="font-bold text-slate-900 dark:text-white text-sm transition-colors" x-text="chapter.title"></h4>
                                </div>
                                <div class="flex items-center gap-4">
                                    <span class="text-xs font-bold" :class="chapter.score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400'" x-text="'Skor: ' + chapter.score"></span>
                                    <span class="text-[10px] text-slate-500 dark:text-white/40 transition-colors border-l border-slate-300 dark:border-white/10 pl-4"><span x-text="chapter.answers.length"></span> Soal</span>
                                    <svg class="w-4 h-4 text-slate-400 dark:text-white/40 transition-transform" :class="{'rotate-180': !open}" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7-7-7-7"/></svg>
                                </div>
                            </div>
                            
                            {{-- Accordion Body (Cards Jawaban Per Soal) --}}
                            <div x-show="open" x-transition class="bg-white dark:bg-transparent border-t border-slate-200 dark:border-white/5 transition-colors p-4 md:p-6">
                                <template x-for="(ans, index) in chapter.answers" :key="index">
                                    <div class="p-4 border border-slate-100 dark:border-white/5 rounded-xl mb-4 bg-slate-50/50 dark:bg-white/[0.02] transition-colors relative overflow-hidden">
                                        <div class="flex justify-between items-start gap-4 mb-3">
                                            <p class="font-medium text-xs md:text-sm text-slate-800 dark:text-white leading-relaxed">
                                                <span class="text-slate-400 dark:text-white/40 font-bold mr-1" x-text="(index + 1) + '.'"></span>
                                                <span x-text="ans.question"></span>
                                            </p>
                                            <span class="px-2 py-1 rounded text-[9px] font-bold shrink-0 shadow-sm transition-colors"
                                                  :class="ans.is_correct == 1 ? 'bg-emerald-100 text-emerald-700 dark:bg-emerald-500/20 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30' : 'bg-red-100 text-red-700 dark:bg-red-500/20 dark:text-red-400 border border-red-200 dark:border-red-500/30'"
                                                  x-text="ans.is_correct == 1 ? 'BENAR' : 'SALAH'"></span>
                                        </div>
                                        
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 mt-3">
                                            {{-- Box Jawaban Siswa --}}
                                            <div class="p-3 rounded-lg border border-slate-200 dark:border-white/5 bg-white dark:bg-[#0a0e17] transition-colors">
                                                <p class="text-[9px] uppercase tracking-widest font-bold text-slate-400 dark:text-white/40 mb-1.5 flex items-center gap-1.5">
                                                    Jawaban Siswa
                                                </p>
                                                <p class="text-xs font-mono font-medium transition-colors" 
                                                   :class="ans.is_correct == 1 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400 line-through opacity-80'" 
                                                   x-text="ans.chosen"></p>
                                            </div>
                                            
                                            {{-- Box Kunci Jawaban (Hanya muncul jika salah) --}}
                                            <div class="p-3 rounded-lg border border-emerald-200 dark:border-emerald-500/20 bg-emerald-50 dark:bg-emerald-500/10 transition-colors" x-show="ans.is_correct == 0">
                                                <p class="text-[9px] uppercase tracking-widest font-bold text-emerald-600/70 dark:text-emerald-400/70 mb-1.5 flex items-center gap-1.5">
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                                    Kunci Jawaban
                                                </p>
                                                <p class="text-xs font-mono text-emerald-700 dark:text-emerald-400 font-bold transition-colors" x-text="ans.correct"></p>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </template>
                </div>
            </template>
            
            {{-- Fallback Kosong --}}
            <template x-if="!selectedStudent || Object.keys(selectedStudent.chapters).length === 0">
                <div class="flex flex-col items-center justify-center py-20 opacity-60">
                    <div class="text-4xl mb-4 grayscale">📂</div>
                    <h3 class="text-slate-900 dark:text-white font-bold transition-colors">Belum ada riwayat jawaban</h3>
                    <p class="text-xs text-slate-500 dark:text-white/50 mt-2 transition-colors">Siswa ini belum menyelesaikan evaluasi apapun.</p>
                </div>
            </template>
        </div>
    </div>
</div>

{{-- 2. MODAL: TOTAL QUESTIONS --}}
<div x-show="showQuestionsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showQuestionsModal = false"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(6,182,212,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-cyan-600 dark:text-cyan-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                    Soal
                </h3>
                <p class="text-[10px] text-cyan-600 dark:text-cyan-400 mt-1 font-mono transition-colors">{{ number_format($totalQuestions) }} soal</p>
            </div>
            <button @click="showQuestionsModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
            @forelse($questions as $q)
            @php
                $modalQuestionStatusClass = match ($q->status ?? 'Belum Ada Data') {
                    'Sulit' => 'bg-red-50 dark:bg-red-500/10 text-red-600 dark:text-red-400 border-red-200 dark:border-red-500/20',
                    'Sedang' => 'bg-yellow-50 dark:bg-yellow-500/10 text-yellow-600 dark:text-yellow-400 border-yellow-200 dark:border-yellow-500/20',
                    'Mudah' => 'bg-emerald-50 dark:bg-emerald-500/10 text-emerald-600 dark:text-emerald-400 border-emerald-200 dark:border-emerald-500/20',
                    default => 'bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/45 border-slate-200 dark:border-white/10',
                };
            @endphp
            <div class="flex items-center justify-between gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-cyan-300 dark:hover:border-cyan-500/30 transition-colors group">
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors" title="{{ $q->question_text }}">{{ $q->question_text }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-0.5 transition-colors">{{ (int) $q->chapter_id === 99 ? 'Evaluasi' : 'Bab ' . $q->chapter_id }}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="text-[9px] font-bold uppercase tracking-widest border px-2 py-1 rounded transition-colors {{ $modalQuestionStatusClass }}">{{ $q->status }}</span>
                </div>
            </div>
            @empty
            <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data soal yang dimasukkan.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- 3. MODAL: PARTICIPANTS --}}
<div x-show="showParticipantsModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showParticipantsModal = false"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-indigo-200 dark:border-indigo-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(99,102,241,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-indigo-600 dark:text-indigo-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    Peserta
                </h3>
                <p class="text-[10px] text-indigo-600 dark:text-indigo-400 mt-1 font-mono transition-colors">{{ number_format($totalParticipants) }} siswa</p>
            </div>
            <button @click="showParticipantsModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-2 pr-2">
            @forelse($studentStats as $stat)
            <div class="flex items-center gap-4 p-3.5 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-indigo-300 dark:hover:border-indigo-500/30 transition-colors group">
                <div class="w-10 h-10 rounded-full bg-indigo-50 dark:bg-indigo-500/10 flex items-center justify-center text-sm font-bold text-indigo-600 dark:text-indigo-400 border border-indigo-200 dark:border-indigo-500/30 shrink-0 transition-colors">{{ substr($stat->name, 0, 2) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-bold text-slate-900 dark:text-white truncate transition-colors">{{ $stat->name }}</p>
                    <p class="text-[10px] text-slate-500 dark:text-white/50 font-mono mt-0.5 transition-colors">{{ $stat->email }}</p>
                </div>
                <div class="text-right shrink-0">
                    <span class="block text-sm font-black transition-colors {{ $stat->avg_score >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">Rata-rata : {{ $stat->avg_score }} </span>
                    <span class="text-[9px] text-slate-500 dark:text-white/40 transition-colors">{{ $stat->total_attempts }} Evaluasi Selesai</span>
                </div>
            </div>
            @empty
            <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada siswa yang berpartisipasi.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- 4. MODAL: GLOBAL ACCURACY (PER CHAPTER) --}}
<div x-show="showAccuracyModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showAccuracyModal = false"></div>
    <div class="relative w-full max-w-2xl bg-white dark:bg-[#0f141e] border border-emerald-200 dark:border-emerald-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(16,185,129,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-emerald-600 dark:text-emerald-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    Akurasi Bab
                </h3>
                <p class="text-[10px] text-emerald-600 dark:text-emerald-400 mt-1 font-mono transition-colors">Rata-rata per bab.</p>
            </div>
            <button @click="showAccuracyModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar space-y-3 pr-2">
            @forelse($chapterGroups as $id => $chQs)
            @php 
                $avgCh = $chQs->count() > 0 ? round($chQs->avg('accuracy'), 1) : 0; 
            @endphp
            <div class="flex items-center justify-between p-4 rounded-xl bg-slate-50 dark:bg-[#0a0e17]/80 border border-slate-200 dark:border-white/5 hover:border-emerald-300 dark:hover:border-emerald-500/30 transition-colors group">
                <div class="flex items-center gap-4">
                    <div class="w-10 h-10 rounded-lg bg-emerald-50 dark:bg-emerald-500/10 flex items-center justify-center font-bold text-emerald-600 dark:text-emerald-400 border border-emerald-200 dark:border-emerald-500/30 transition-colors">
                        0{{ $id }}
                    </div>
                    <div>
                        <p class="text-sm font-bold text-slate-900 dark:text-white transition-colors">Materi Bab {{ $id }}</p>
                        <p class="text-[10px] text-slate-500 dark:text-white/50 mt-0.5 transition-colors">{{ $chQs->count() }} Soal Dievaluasi</p>
                    </div>
                </div>
                <div class="text-right">
                    <span class="text-2xl font-black transition-colors {{ $avgCh >= 70 ? 'text-emerald-600 dark:text-emerald-400' : 'text-red-600 dark:text-red-400' }}">{{ $avgCh }}%</span>
                </div>
            </div>
            @empty
            <p class="text-[11px] text-slate-500 dark:text-white/40 text-center py-10 transition-colors">Belum ada data akurasi yang dihitung.</p>
            @endforelse
        </div>
    </div>
</div>

{{-- 5. MODAL: HARD QUESTIONS (SULIT SAJA) --}}
<div x-show="showHardModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4" style="display: none;" x-transition.opacity>
    <div class="absolute inset-0 bg-slate-900/80 dark:bg-[#020617]/95 backdrop-blur-md transition-colors" @click="showHardModal = false"></div>
    <div class="relative w-full max-w-3xl bg-white dark:bg-[#0f141e] border border-red-200 dark:border-red-500/40 rounded-2xl shadow-xl dark:shadow-[0_20px_70px_rgba(239,68,68,0.15)] p-6 transition-colors duration-500 overflow-hidden" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-8 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100">
        <div class="flex justify-between items-center mb-6 border-b border-slate-200 dark:border-white/5 pb-4 transition-colors">
            <div>
                <h3 class="text-lg font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors">
                    <svg class="w-5 h-5 text-red-600 dark:text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                    Soal dengan Akurasi di Bawah 50%
                </h3>
                <p class="text-[10px] text-red-600 dark:text-red-400 mt-1 font-mono transition-colors">Menampilkan soal dengan proporsi jawaban benar kurang dari 50%.</p>
            </div>
            <button @click="showHardModal = false" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-100 hover:bg-slate-200 dark:bg-white/5 dark:hover:bg-red-500/20 rounded-full p-1.5"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="max-h-[60vh] overflow-y-auto custom-scrollbar pr-2">
            <table class="w-full text-sm text-left">
                <thead class="bg-slate-100 dark:bg-white/5 text-slate-500 dark:text-white/40 text-[10px] uppercase font-bold sticky top-0 z-10 transition-colors">
                    <tr>
                        <th class="px-4 py-3 rounded-tl-lg border-b border-slate-200 dark:border-white/5">Kutipan Soal</th>
                        <th class="px-4 py-3 text-center border-b border-slate-200 dark:border-white/5">Bab</th>
                        <th class="px-4 py-3 text-center border-b border-slate-200 dark:border-white/5">Salah / Total Jawaban</th>
                        <th class="px-4 py-3 text-right rounded-tr-lg border-b border-slate-200 dark:border-white/5">Rasio Kegagalan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 dark:divide-white/5 bg-slate-50/50 dark:bg-[#0a0e17]/30 transition-colors">
                    @forelse($questions->where('status', 'Sulit') as $q)
                    <tr class="hover:bg-red-50 dark:hover:bg-red-500/5 transition-colors group">
                        <td class="px-4 py-4 text-slate-700 dark:text-white/80 text-[11px] transition-colors" title="{{ $q->question_text }}">
                            {{ \Illuminate\Support\Str::limit($q->question_text, 60) }}
                        </td>
                        <td class="px-4 py-4 text-center text-slate-500 dark:text-white/50 text-[10px] font-bold transition-colors">{{ (int) $q->chapter_id === 99 ? 'Evaluasi' : 'Bab ' . $q->chapter_id }}</td>
                        <td class="px-4 py-4 text-center transition-colors">
                            <span class="text-red-600 dark:text-red-400 font-bold">{{ $q->wrong_count }}</span> <span class="text-slate-400 dark:text-white/30">/ {{ $q->total_attempts }}</span>
                        </td>
                        <td class="px-4 py-4 text-right transition-colors">
                            <span class="px-2 py-1 rounded bg-red-100 dark:bg-red-500/20 text-red-700 dark:text-red-400 font-black text-[10px] transition-colors">{{ 100 - $q->accuracy }}%</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center py-10 text-emerald-600 dark:text-emerald-400 text-xs font-bold uppercase tracking-widest transition-colors">Tidak ada soal dengan akurasi di bawah 50%.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ==================== MODALS PENGOLAHAN SOAL (FORM/INSIGHT) ==================== --}}

{{-- MODAL CREATE/EDIT QUESTION --}}
<div id="quizModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-3 sm:p-4">
    <div class="absolute inset-0 bg-slate-900/90 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeModal()"></div>
    <div id="modalContent" class="relative w-full max-w-5xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[94vh]">
        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 flex justify-between items-center bg-slate-50 dark:bg-[#0a0e17] rounded-t-3xl transition-colors">
            <h3 class="text-lg md:text-xl font-bold text-slate-900 dark:text-white flex items-center gap-2 transition-colors" id="modalTitle"><span class="p-1.5 bg-indigo-100 dark:bg-indigo-500/20 rounded-lg text-indigo-700 dark:text-indigo-400 text-[10px] tracking-widest border border-indigo-200 dark:border-indigo-500/30 shadow-inner transition-colors">BARU</span> Tambah Soal</h3>
            <button onclick="closeModal()" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent dark:hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="p-4 sm:p-5 md:p-6 overflow-y-auto custom-scrollbar flex-1 bg-slate-50 dark:bg-[#0a0e17]/70 relative">
            <form id="quizForm" class="relative z-10" enctype="multipart/form-data">
                @csrf
                @include('admin.quiz._question_form_fields')
            </form>
        </div>
        <div class="p-4 sm:p-5 md:p-6 border-t border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0a0e17] flex flex-col-reverse sm:flex-row sm:justify-end gap-3 rounded-b-3xl transition-colors">
            <button onclick="closeModal()" class="px-5 md:px-6 py-2.5 rounded-xl text-slate-600 dark:text-white/60 hover:text-slate-900 dark:hover:text-white hover:bg-slate-200 dark:hover:bg-white/5 font-bold text-xs transition border border-transparent dark:hover:border-white/10">Batal</button>
            <button onclick="submitForm()" class="px-6 md:px-8 py-2.5 rounded-xl bg-slate-950 hover:bg-cyan-700 text-white dark:bg-cyan-500 dark:hover:bg-cyan-300 dark:text-slate-950 font-bold text-xs shadow-md transition transform hover:-translate-y-0.5 border border-slate-900 dark:border-cyan-300">Simpan Soal</button>
        </div>
    </div>
</div>

{{-- MODAL INSIGHT (DETAIL PENJAWAB BENAR/SALAH - BAGIAN TABEL BANK SOAL) --}}
<div id="insightModal" class="fixed inset-0 z-[999999] hidden flex items-center justify-center p-4">
    <div class="absolute inset-0 bg-slate-900/90 dark:bg-[#020617]/90 backdrop-blur-md transition-opacity" onclick="closeInsightModal()"></div>
    <div id="insightContent" class="relative w-full max-w-4xl bg-white dark:bg-[#0f141e] border border-slate-200 dark:border-white/10 rounded-3xl shadow-2xl dark:shadow-[0_20px_70px_rgba(0,0,0,0.9)] transform scale-95 opacity-0 transition-all duration-300 flex flex-col max-h-[90vh]">
        <div class="p-5 md:p-6 border-b border-slate-200 dark:border-white/5 bg-slate-50 dark:bg-[#0a0e17] flex justify-between items-center rounded-t-3xl transition-colors">
            <h3 class="font-bold text-slate-900 dark:text-white text-lg flex items-center gap-2 transition-colors"><svg class="w-5 h-5 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg> Tinjauan Siswa per Soal</h3>
            <button onclick="closeInsightModal()" class="text-slate-400 hover:text-slate-900 dark:text-white/40 dark:hover:text-white transition bg-slate-200 dark:bg-white/5 hover:bg-red-100 dark:hover:bg-red-500/20 p-2 rounded-full border border-transparent dark:hover:border-red-500/30"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
        </div>
        <div class="p-5 md:p-6 overflow-y-auto custom-scrollbar flex-1 space-y-5">
            <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-[#0a0e17]/80">
                <div class="flex flex-col gap-3 md:flex-row md:items-start md:justify-between">
                    <div class="min-w-0">
                        <div class="flex flex-wrap gap-2">
                            <span id="insightOutcomeCode" class="rounded-lg border border-cyan-200 bg-cyan-50 px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-cyan-700 dark:border-cyan-500/20 dark:bg-cyan-500/10 dark:text-cyan-200">TP</span>
                            <span id="insightAccuracy" class="rounded-lg border border-slate-200 bg-white px-2.5 py-1 text-[10px] font-black uppercase tracking-widest text-slate-500 dark:border-white/10 dark:bg-white/5 dark:text-slate-300">0%</span>
                        </div>
                        <p id="insightQuestion" class="mt-3 text-sm font-bold leading-relaxed text-slate-900 dark:text-white"></p>
                        <p id="insightOutcomeTitle" class="mt-1 text-xs leading-5 text-slate-500 dark:text-slate-400"></p>
                    </div>
                    <div class="grid grid-cols-3 gap-2 text-center text-xs md:min-w-64">
                        <div class="rounded-xl border border-slate-200 bg-white px-3 py-2 text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                            <b id="countTotalNumber" class="block text-lg">0</b>
                            <span class="text-[10px] font-black uppercase tracking-widest">Total</span>
                        </div>
                        <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-3 py-2 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200">
                            <b id="countCorrectNumber" class="block text-lg">0</b>
                            <span class="text-[10px] font-black uppercase tracking-widest">Benar</span>
                        </div>
                        <div class="rounded-xl border border-red-200 bg-red-50 px-3 py-2 text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200">
                            <b id="countWrongNumber" class="block text-lg">0</b>
                            <span class="text-[10px] font-black uppercase tracking-widest">Salah</span>
                        </div>
                    </div>
                </div>
            </div>

            <section class="rounded-2xl border border-slate-200 bg-white/80 p-4 dark:border-white/10 dark:bg-[#0a0e17]/80">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-2">
                    <p class="text-[10px] font-black uppercase tracking-widest text-slate-500 dark:text-slate-400">Analitik total respons</p>
                    <div class="flex gap-2">
                        <span id="countCorrect" class="rounded-lg border border-emerald-200 bg-emerald-50 px-3 py-1 text-[10px] font-black text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300">0 siswa benar</span>
                        <span id="countWrong" class="rounded-lg border border-red-200 bg-red-50 px-3 py-1 text-[10px] font-black text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-300">0 siswa salah</span>
                    </div>
                </div>
                <div class="h-3 overflow-hidden rounded-full bg-slate-200 dark:bg-black/20">
                    <div class="flex h-full">
                        <div id="correctBar" class="h-full bg-emerald-500 transition-all" style="width: 0%"></div>
                        <div id="wrongBar" class="h-full bg-red-500 transition-all" style="width: 0%"></div>
                    </div>
                </div>
                <div class="mt-3 grid gap-3 sm:grid-cols-2">
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[10px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-300">Siswa Benar</p>
                        <p id="correctQuantityNote" class="mt-1 text-xs font-bold text-slate-700 dark:text-white/70">0 siswa atau 0% dari total.</p>
                    </div>
                    <div class="rounded-xl border border-slate-200 bg-slate-50/80 p-3 dark:border-white/5 dark:bg-[#020617]/70">
                        <p class="text-[10px] font-black uppercase tracking-widest text-red-700 dark:text-red-300">Siswa Salah</p>
                        <p id="wrongQuantityNote" class="mt-1 text-xs font-bold text-slate-700 dark:text-white/70">0 siswa atau 0% dari total.</p>
                    </div>
                </div>
            </section>
            <div class="grid gap-4 lg:grid-cols-2">
                <section class="rounded-2xl border border-emerald-200 bg-emerald-50/50 p-4 dark:border-emerald-500/20 dark:bg-emerald-500/[0.06]">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <p class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-emerald-700 dark:text-emerald-300"><span class="h-1.5 w-1.5 rounded-full bg-emerald-500"></span> Siswa Benar</p>
                        <span id="listCorrectCount" class="rounded-lg border border-emerald-200 bg-white px-3 py-1 text-[10px] font-black text-emerald-700 dark:border-emerald-500/20 dark:bg-[#0a0e17] dark:text-emerald-300">0 siswa</span>
                    </div>
                    <div id="listCorrect" class="space-y-2"></div>
                </section>
                <section class="rounded-2xl border border-red-200 bg-red-50/50 p-4 dark:border-red-500/20 dark:bg-red-500/[0.06]">
                    <div class="mb-3 flex items-center justify-between gap-3">
                        <p class="flex items-center gap-2 text-[10px] font-black uppercase tracking-widest text-red-700 dark:text-red-300"><span class="h-1.5 w-1.5 rounded-full bg-red-500"></span> Siswa Salah</p>
                        <span id="listWrongCount" class="rounded-lg border border-red-200 bg-white px-3 py-1 text-[10px] font-black text-red-700 dark:border-red-500/20 dark:bg-[#0a0e17] dark:text-red-300">0 siswa</span>
                    </div>
                    <div id="listWrong" class="space-y-2"></div>
                </section>
            </div>
        </div>
    </div>
</div>

{{-- MODAL INSIGHT HERO KELAS KUIS --}}
<div x-show="showClassInsightModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;" role="dialog" aria-modal="true" aria-label="Insight kinerja kuis per kelas">
    <div class="absolute inset-0 bg-slate-900/80 backdrop-blur-md transition-opacity dark:bg-[#020617]/90" @click="showClassInsightModal = false" x-transition.opacity></div>

    <div class="quiz-class-hero-modal relative max-h-[92vh] w-full max-w-4xl overflow-y-auto custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-5 scale-95" x-transition:enter-end="opacity-100 translate-y-0 scale-100" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 scale-100" x-transition:leave-end="opacity-0 translate-y-5 scale-95">
        <button @click="showClassInsightModal = false" aria-label="Tutup insight kelas" class="absolute right-5 top-5 z-10 rounded-full p-2 text-slate-400 transition hover:bg-white/70 hover:text-slate-700 dark:hover:bg-white/5 dark:hover:text-white">
            <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        <div class="quiz-class-hero-head px-5 py-6 sm:px-7 sm:py-7">
            <p class="quiz-class-hero-kicker">Insight Kinerja Kelas</p>
            <h3 class="mt-2 truncate pr-10 text-2xl font-black tracking-tight text-slate-900 dark:text-white" x-text="selectedClassInsight.name || 'Kelas'"></h3>
            <p class="mt-2 max-w-2xl text-xs font-semibold leading-5 text-slate-600 dark:text-slate-300">Ringkasan dihitung dari seluruh kuis yang selesai pada periode dan kelas yang sedang difilter.</p>
        </div>

        <div class="space-y-5 p-5 sm:p-7">
            <div class="grid gap-3 sm:grid-cols-2 lg:grid-cols-4">
                <div class="quiz-class-hero-metric">
                    <p>pengguna</p>
                    <strong class="text-cyan-700 dark:text-cyan-300" x-text="selectedClassInsight.students_count || 0"></strong>
                    <span>pengguna dengan kuis selesai</span>
                </div>
                <div class="quiz-class-hero-metric">
                    <p>Percobaan</p>
                    <strong x-text="selectedClassInsight.total_attempts || 0"></strong>
                    <span x-text="(selectedClassInsight.attempts_per_student || 0) + ' sesi per pengguna'"></span>
                </div>
                <div class="quiz-class-hero-metric">
                    <p>Skor rata-rata</p>
                    <strong class="text-indigo-700 dark:text-indigo-300" x-text="selectedClassInsight.avg_score || 0"></strong>
                    <span>nilai akhir seluruh sesi</span>
                </div>
                <div class="quiz-class-hero-metric">
                    <p>Durasi rata-rata</p>
                    <strong class="text-amber-700 dark:text-amber-300" x-text="(selectedClassInsight.avg_duration_minutes || 0) + ' m'"></strong>
                    <span>waktu per sesi kuis</span>
                </div>
            </div>

            <section class="rounded-2xl border border-slate-200 bg-slate-50/80 p-4 dark:border-white/10 dark:bg-white/[0.025]">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <p class="quiz-class-hero-section-label">Kelulusan Kuis</p>
                        <div class="mt-2 flex items-end gap-3">
                            <strong class="text-3xl font-black tracking-tight text-emerald-600 dark:text-emerald-300" x-text="(selectedClassInsight.pass_rate || 0) + '%'"></strong>
                            <span class="pb-1 text-xs font-semibold text-slate-500 dark:text-slate-400" x-text="(selectedClassInsight.passed_attempts || 0) + ' lulus dari ' + (selectedClassInsight.total_attempts || 0) + ' percobaan'"></span>
                        </div>
                    </div>
                    <div class="flex flex-wrap gap-2 text-[10px] font-black uppercase tracking-widest">
                        <span class="rounded-lg border border-emerald-200 bg-emerald-50 px-2.5 py-1.5 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-300" x-text="(selectedClassInsight.passed_attempts || 0) + ' lulus'"></span>
                        <span class="rounded-lg border border-rose-200 bg-rose-50 px-2.5 py-1.5 text-rose-700 dark:border-rose-500/20 dark:bg-rose-500/10 dark:text-rose-300" x-text="(selectedClassInsight.not_passed_attempts || 0) + ' belum tuntas'"></span>
                    </div>
                </div>
                <div class="quiz-class-hero-meter mt-4">
                    <span class="bg-emerald-500" :style="'width: ' + Math.min(selectedClassInsight.pass_rate || 0, 100) + '%'" aria-label="Proporsi lulus"></span>
                    <span class="bg-rose-400" :style="'width: ' + Math.max(0, 100 - Math.min(selectedClassInsight.pass_rate || 0, 100)) + '%'" aria-label="Proporsi belum tuntas"></span>
                </div>
            </section>

            <div class="rounded-xl border border-dashed border-slate-300 bg-white/60 px-4 py-3 text-[11px] font-semibold leading-5 text-slate-500 dark:border-white/10 dark:bg-black/10 dark:text-slate-400">
                Basis data: jumlah pengguna dihitung dari pengguna dengan minimal satu kuis selesai; kelulusan, skor, dan durasi dihitung dari seluruh percobaan kuis pada kelas ini.
            </div>
        </div>

        <div class="flex justify-end border-t border-slate-200 px-5 py-4 dark:border-white/10 sm:px-7">
            <button type="button" @click="showClassInsightModal = false" class="text-sm font-bold text-slate-500 transition hover:text-slate-900 dark:text-white/50 dark:hover:text-white">Tutup</button>
        </div>
    </div>
</div>

{{-- MODAL PANDUAN DASBOR ADMIN (HERO MODAL POPUP) --}}
<div x-show="showDashboardInfoModal" class="fixed inset-0 z-[999999] flex items-center justify-center p-4 sm:p-6" x-cloak style="display: none;">
    <div class="absolute inset-0 bg-slate-900/60 dark:bg-[#020617]/80 backdrop-blur-md cursor-pointer transition-opacity" @click="showDashboardInfoModal = false" x-transition.opacity></div>
    
    <div class="relative max-h-[92vh] w-full max-w-6xl overflow-y-auto bg-white/95 dark:bg-[#0f141e]/95 backdrop-blur-xl border border-slate-200 dark:border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl transition-all custom-scrollbar" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95 translate-y-4" x-transition:enter-end="opacity-100 scale-100 translate-y-0" x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100 translate-y-0" x-transition:leave-end="opacity-0 scale-95 translate-y-4">
        
        <button @click="showDashboardInfoModal = false" class="absolute top-5 right-5 p-2 rounded-full hover:bg-slate-100 dark:hover:bg-white/5 text-slate-400 hover:text-slate-600 dark:hover:text-white transition-all focus:outline-none z-10">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
        </button>

        @php
            $guideTitle = 'Panduan Analitik Kuis';
            $guideSubtitle = 'Membaca data kuis, respons, dan capaian';
            $guideImage = 'images/guides/current-admin-question-analytics.png';
            $guideIntro = 'Gunakan nomor pada gambar untuk membaca ringkasan kuis, bank soal, serta distribusi tingkat kesulitan.';
            $guidePoints = [
                ['x' => 51, 'y' => 28, 'title' => 'Ringkasan evaluasi', 'description' => 'Baca total soal, peserta, akurasi, dan jumlah soal sulit untuk melihat kualitas kuis secara umum.'],
                ['x' => 47, 'y' => 63, 'title' => 'Bank soal', 'description' => 'Gunakan daftar soal untuk meninjau pertanyaan, opsi jawaban, kunci, media, dan tujuan pembelajaran.'],
                ['x' => 84, 'y' => 62, 'title' => 'Komposisi tingkat kesulitan', 'description' => 'Menampilkan jumlah soal mudah, sedang, sulit, dan soal yang belum memiliki respons.'],
            ];
        @endphp
        @include('admin.partials.analytics_guide_mockup')

        <div class="mt-8 pt-6 border-t border-slate-200 dark:border-white/5">
            <button @click="showDashboardInfoModal = false" class="w-full py-3 bg-slate-900 hover:bg-slate-800 dark:bg-white dark:hover:bg-slate-200 text-white dark:text-slate-900 font-bold text-sm rounded-xl transition-colors shadow-md focus:outline-none">
                Mengerti, Tutup Panduan
            </button>
        </div>
    </div>
</div>

{{-- SCRIPT: TEMA, AJAX & SWEETALERT --}}
@php
    $questionInsightData = $questions->mapWithKeys(function ($q) {
        return [
            (int) $q->id => [
                'id' => (int) $q->id,
                'question' => \Illuminate\Support\Str::limit(strip_tags((string) $q->question_text), 180),
                'outcome_code' => $q->outcome_meta['display_code'] ?? ($q->learning_objective_code ?: 'TP Umum'),
                'outcome_title' => $q->outcome_meta['title'] ?? ($q->learning_objective_title ?: 'Tujuan pembelajaran terkait'),
                'accuracy' => (int) ($q->accuracy ?? 0),
                'total_attempts' => (int) ($q->total_attempts ?? 0),
                'correct_count' => (int) ($q->correct_count ?? 0),
                'wrong_count' => (int) ($q->wrong_count ?? 0),
                'correct_percent' => (int) ($q->accuracy ?? 0),
                'wrong_percent' => (int) (($q->total_attempts ?? 0) > 0 ? round((($q->wrong_count ?? 0) / max(1, $q->total_attempts)) * 100) : 0),
                'correct' => $q->list_correct ?? [],
                'wrong' => $q->list_wrong ?? [],
            ],
        ];
    })->all();
@endphp
<script>
    const questionInsightData = @json($questionInsightData);

    // --- SINKRONISASI TEMA GELAP/TERANG ---
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

    // --- SETUP AJAX ---
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    // --- CHART DONUT (TEMA ADAPTIF) ---
    let myChart = null;

    function initChart() {
        const ctx = document.getElementById('difficultyChart');
        if(ctx) {
            if(myChart) myChart.destroy();
            
            const isDark = document.documentElement.classList.contains('dark');
            const borderColor = isDark ? '#020617' : '#ffffff';

            myChart = new Chart(ctx.getContext('2d'), {
                type: 'doughnut',
                data: {
                    labels: ['Mudah', 'Sedang', 'Sulit', 'Belum Ada Data'],
                    datasets: [{
                        data: [
                            {{ $questions->where('status', 'Mudah')->count() }},
                            {{ $questions->where('status', 'Sedang')->count() }},
                            {{ $questions->where('status', 'Sulit')->count() }},
                            {{ $questions->where('status', 'Belum Ada Data')->count() }}
                        ],
                        backgroundColor: ['#10b981', '#eab308', '#ef4444', '#94a3b8'],
                        borderColor: borderColor,
                        borderWidth: 2, 
                        hoverOffset: 4
                    }]
                },
                options: { responsive: true, maintainAspectRatio: false, plugins: { legend: {display: false} }, cutout: '75%' }
            });
        }
    }

    document.addEventListener("DOMContentLoaded", initChart);
    window.addEventListener('theme-toggled', initChart);

    const interactionMeta = {
        multiple_choice: { label: 'Pilihan Ganda', status: 'Standar' },
        image_context: { label: 'Soal Gambar', status: 'Media' },
    };
    let previewObjectUrl = null;
    const quizAlertDefaults = {
        customClass: {
            container: 'quiz-alert-layer',
            popup: 'quiz-alert-popup',
        },
    };

    function showQuizAlert(options = {}) {
        return Swal.fire({
            ...quizAlertDefaults,
            ...options,
            customClass: {
                ...quizAlertDefaults.customClass,
                ...(options.customClass || {}),
            },
        });
    }

    function renderQuestionStudentInsight(meta = null) {
        const total = Number(meta?.total_attempts || 0);
        const correct = Number(meta?.correct_count || 0);
        const wrong = Number(meta?.wrong_count || 0);
        const accuracy = total > 0 ? Math.round((correct / total) * 100) : 0;

        $('#studentInsightTotal').text(total);
        $('#studentInsightCorrect').text(correct);
        $('#studentInsightWrong').text(wrong);
        $('#studentInsightBar').css('width', accuracy + '%');
        $('#studentInsightStatus').text(total > 0 ? accuracy + '% akurat' : 'Baru');
        $('#studentInsightNote').text(total > 0
            ? `${correct} siswa menjawab benar dan ${wrong} siswa menjawab salah pada percobaan terbaru.`
            : (meta?.id ? 'Belum ada siswa yang menjawab soal ini.' : 'Data siswa akan tampil setelah soal tersimpan dan dijawab.'));
    }

    function getQuestionOutcomeOptions() {
        const element = document.getElementById('question-outcome-options-json');
        if (!element) return {};

        try {
            return JSON.parse(element.textContent || '{}') || {};
        } catch (error) {
            return {};
        }
    }

    const questionOutcomeOptions = getQuestionOutcomeOptions();

    function setLearningOutcomeFields(row = null) {
        $('#inputLearningObjectiveCode').val(row?.code || '');
        $('#inputLearningObjectiveTitle').val(row?.title || '');
        $('#inputRemediationHint').val(row?.material || row?.title || '');
        $('#selectedLearningObjectiveHint').text(row
            ? `${row.code} - ${row.title}`
            : 'Pilih TP sesuai bab. Kode dan arahan materi tersimpan otomatis.');
    }

    function populateLearningOutcomeSelect(selectedCode = '') {
        const chapter = String($('#inputChapter').val() || '1');
        const rows = questionOutcomeOptions[chapter] || [];
        const currentCode = selectedCode || ($('#inputLearningObjectiveCode').val() || '').trim();
        const select = $('#inputLearningObjectiveSelect');

        if (!select.length) return;

        select.empty().append($('<option>', {
            value: '',
            text: rows.length ? 'Pilih TP resmi' : 'TP belum tersedia',
        }));

        rows.forEach((row) => {
            $('<option>', {
                value: row.code,
                text: `${row.code} - ${row.title}`,
            })
                .attr('data-title', row.title || '')
                .attr('data-material', row.material || row.title || '')
                .appendTo(select);
        });

        const selectedRow = rows.find((row) => row.code === currentCode) || null;
        select.val(selectedRow ? selectedRow.code : '');

        if (selectedRow) {
            setLearningOutcomeFields(selectedRow);
        } else if (!currentCode) {
            setLearningOutcomeFields(null);
        }
    }

    function applySelectedLearningOutcome() {
        const selected = $('#inputLearningObjectiveSelect option:selected');
        const code = selected.val() || '';
        if (!code) {
            setLearningOutcomeFields(null);
            return;
        }

        setLearningOutcomeFields({
            code,
            title: selected.attr('data-title') || '',
            material: selected.attr('data-material') || selected.attr('data-title') || '',
        });
    }

    function syncLearningOutcomeSelectFromFields() {
        const code = ($('#inputLearningObjectiveCode').val() || '').trim();
        const title = ($('#inputLearningObjectiveTitle').val() || '').trim().toLowerCase();
        const select = $('#inputLearningObjectiveSelect');
        if (!select.length) return;

        const matched = select.find('option').filter(function () {
            const option = $(this);
            return option.val() === code || (title && (option.attr('data-title') || '').trim().toLowerCase() === title);
        }).first();

        select.val(matched.length ? matched.val() : '');
        if (matched.length) {
            applySelectedLearningOutcome();
        }
    }

    function renderExistingMediaPath(mediaPath = '') {
        const currentValue = (mediaPath || '').trim();
        const existingPath = ($('#inputMediaFile').attr('data-existing-path') || '').trim();
        const existingUrl = ($('#inputMediaFile').attr('data-existing-url') || '').trim();
        const value = existingPath && (!currentValue || currentValue === existingUrl)
            ? existingPath
            : currentValue;
        const mediaFile = $('#inputMediaFile')[0]?.files?.[0] || null;
        const removingMedia = $('#inputRemoveMedia').is(':checked');
        let label = 'Pilih gambar';
        let badge = 'Pilih File';

        if (removingMedia) {
            label = 'Media akan dihapus';
            badge = 'Hapus';
        } else if (mediaFile) {
            label = mediaFile.name;
            badge = 'File Baru';
        } else if (value) {
            label = value;
            badge = 'Media Aktif';
        }

        $('#mediaFileDisplay').text(label).attr('title', label);
        $('#mediaFileBadge').text(badge);
    }

    function redirectToActiveQuestionChapter(chapterId = null) {
        const target = new URL(window.location.href);
        const value = chapterId || $('#inputChapter').val() || window.currentQuestionBankChapter;

        if (value && value !== 'all') {
            target.searchParams.set('chapter', value);
        } else {
            target.searchParams.delete('chapter');
        }

        window.location.href = target.toString();
    }

    function checkImageUrlAvailable(url) {
        return new Promise((resolve) => {
            if (!url) {
                resolve(false);
                return;
            }

            const image = new Image();
            const timer = window.setTimeout(() => {
                image.onload = null;
                image.onerror = null;
                resolve(false);
            }, 7000);

            image.onload = () => {
                window.clearTimeout(timer);
                resolve(true);
            };
            image.onerror = () => {
                window.clearTimeout(timer);
                resolve(false);
            };
            image.src = url;
        });
    }

    function setQuestionType(type) {
        $('#inputInteractionType').val(type);
        updateQuestionTypeUI();
        renderQuestionPreview();
    }

    function updateQuestionTypeUI() {
        const type = $('#inputInteractionType').val() || 'multiple_choice';
        const needsMedia = type === 'image_context';
        const hasFile = Boolean($('#inputMediaFile')[0]?.files?.length);
        const isEditing = Boolean($('#questionId').val());

        $('.question-type-card').removeClass('is-active');
        $(`.question-type-card[data-type="${type}"]`).addClass('is-active');

        $('#optionFields, #correctAnswerField').removeClass('hidden');
        $('#optionFields input').prop('required', true).prop('disabled', false);
        $('#inputCorrect').prop('required', true).prop('disabled', false);
        $('#imageUploadField, #imageMetaFields').toggleClass('hidden', !needsMedia);
        $('#removeMediaField').toggleClass('hidden', !needsMedia || !isEditing);
        $('#inputMediaFile, #inputMediaUrl, #inputMediaCaption, #inputRemoveMedia').prop('disabled', !needsMedia);
        $('#inputInteractionPrompt').prop('required', false);
        $('#inputMediaUrl').prop('required', needsMedia && !hasFile);
        $('#previewTypeLabel').text(interactionMeta[type]?.label || 'Pilihan Ganda');
        $('#previewStatus').text(interactionMeta[type]?.status || 'Draft');
    }

    function renderQuestionPreview() {
        const type = $('#inputInteractionType').val() || 'multiple_choice';
        const question = ($('#inputQuestion').val() || '').trim();
        const caption = ($('#inputMediaCaption').val() || '').trim();
        const mediaUrl = ($('#inputMediaUrl').val() || '').trim();
        const mediaFile = $('#inputMediaFile')[0]?.files?.[0] || null;
        const removingMedia = $('#inputRemoveMedia').is(':checked');
        const isEditing = Boolean($('#questionId').val());

        if (previewObjectUrl) {
            URL.revokeObjectURL(previewObjectUrl);
            previewObjectUrl = null;
        }

        const previewSrc = type === 'image_context' ? (mediaFile ? URL.createObjectURL(mediaFile) : (removingMedia ? '' : mediaUrl)) : '';
        if (mediaFile) previewObjectUrl = previewSrc;

        $('#previewQuestion').text(question || 'Teks pertanyaan akan tampil di sini.');
        renderExistingMediaPath(type === 'image_context' && mediaUrl && !removingMedia ? mediaUrl : '');

        if (previewSrc) {
            $('#previewMedia').attr('src', previewSrc);
            $('#previewMediaWrap').removeClass('hidden');
        } else {
            $('#previewMedia').attr('src', '');
            $('#previewMediaWrap').addClass('hidden');
        }

        if (caption) {
            $('#previewCaption').text(caption).removeClass('hidden');
        } else {
            $('#previewCaption').text('').addClass('hidden');
        }

        const options = ['a', 'b', 'c', 'd'];
        const correctVal = $('#inputCorrect').val() || 'option_a';
        options.forEach((opt) => {
            const value = ($('#inputOption_' + opt).val() || '').trim();
            const previewEl = $('#preview_' + opt);
            previewEl.find('.preview-text').text(value || 'Pilihan ' + opt.toUpperCase());
            previewEl.toggleClass('is-correct', correctVal === 'option_' + opt);
        });
    }

    $(document).on('change', '#inputInteractionType', () => {
        updateQuestionTypeUI();
        renderQuestionPreview();
    });
    $(document).on('change', '#inputChapter', () => {
        setLearningOutcomeFields(null);
        populateLearningOutcomeSelect();
    });
    $(document).on('change', '#inputLearningObjectiveSelect', () => {
        applySelectedLearningOutcome();
    });
    $(document).on('input', '#inputLearningObjectiveCode, #inputLearningObjectiveTitle', () => {
        syncLearningOutcomeSelectFromFields();
    });
    $(document).on('input change', '#inputQuestion, #inputCorrect, #inputOption_a, #inputOption_b, #inputOption_c, #inputOption_d, #inputInteractionPrompt, #inputMediaUrl, #inputMediaCaption, #inputMediaFile, #inputRemoveMedia', () => {
        updateQuestionTypeUI();
        renderQuestionPreview();
    });

    // --- MODAL FUNCTIONS UNTUK BANK SOAL ---
    function openModal(mode, data = null) {
        $('#quizModal').removeClass('hidden');
        setTimeout(() => { $('#modalContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
        
        const isDark = document.documentElement.classList.contains('dark');
        
        if(mode === 'create') {
            const badge = isDark ? 'bg-indigo-500/20 text-indigo-400 border-indigo-500/30' : 'bg-indigo-100 text-indigo-700 border-indigo-200';
            $('#modalTitle').html(`<span class="p-1.5 ${badge} rounded-lg text-[10px] tracking-widest border shadow-inner transition-colors">BARU</span> Tambah Soal`);
            const defaultChapter = window.currentQuestionBankChapter && window.currentQuestionBankChapter !== 'all'
                ? window.currentQuestionBankChapter
                : 1;
            $('#quizForm')[0].reset(); $('#questionId').val(''); $('#inputChapter').val(defaultChapter);
            $('#inputInteractionType').val('multiple_choice');
            $('#inputOption_a, #inputOption_b, #inputOption_c, #inputOption_d').val('');
            $('#inputMediaFile').removeAttr('data-existing-path data-existing-url');
            $('#inputCorrect').val('option_a');
            populateLearningOutcomeSelect();
            renderExistingMediaPath('');
            renderQuestionStudentInsight(null);
            updateQuestionTypeUI();
            renderQuestionPreview();
        } else {
            const badge = isDark ? 'bg-amber-500/20 text-amber-400 border-amber-500/30' : 'bg-amber-100 text-amber-700 border-amber-200';
            $('#modalTitle').html(`<span class="p-1.5 ${badge} rounded-lg text-[10px] tracking-widest border shadow-inner transition-colors">EDIT</span> Perbarui Soal`);
            const objectiveCode = data.learning_objective_code || data.outcome_meta?.code || '';
            const objectiveTitle = data.learning_objective_title || data.outcome_meta?.title || '';
            const remediationHint = data.remediation_hint || data.outcome_meta?.material || objectiveTitle;
            $('#questionId').val(data.id); $('#inputQuestion').val(data.question_text); $('#inputChapter').val(data.chapter_id);
            $('#inputLearningObjectiveCode').val(objectiveCode);
            $('#inputLearningObjectiveTitle').val(objectiveTitle);
            $('#inputRemediationHint').val(remediationHint);
            let normalizedType = ['multiple_choice', 'image_context'].includes(data.interaction_type) ? data.interaction_type : 'multiple_choice';
            if (normalizedType === 'multiple_choice' && data.media_url) normalizedType = 'image_context';
            $('#inputInteractionType').val(normalizedType);
            $('#inputInteractionPrompt').val('');
            $('#inputMediaUrl').val(data.media_url || '');
            $('#inputMediaCaption').val(data.media_caption || '');
            $('#inputRemoveMedia').prop('checked', false);
            $('#inputMediaFile').val('');
            $('#inputMediaFile')
                .attr('data-existing-path', data.media_path || data.media_url || '')
                .attr('data-existing-url', data.media_url || '');
            populateLearningOutcomeSelect(objectiveCode);
            syncLearningOutcomeSelectFromFields();
            renderExistingMediaPath(data.media_path || data.media_url || '');
            if(data.options && data.options.length >= 4) {
                $('#inputOption_a').val(data.options[0].option_text); $('#inputOption_b').val(data.options[1].option_text);
                $('#inputOption_c').val(data.options[2].option_text); $('#inputOption_d').val(data.options[3].option_text);
                if(data.options[0].is_correct) $('#inputCorrect').val('option_a'); else if(data.options[1].is_correct) $('#inputCorrect').val('option_b');
                else if(data.options[2].is_correct) $('#inputCorrect').val('option_c'); else if(data.options[3].is_correct) $('#inputCorrect').val('option_d');
            }
            renderQuestionStudentInsight(questionInsightData[data.id] || { id: data.id });
            updateQuestionTypeUI();
            renderQuestionPreview();
        }
    }
    function closeModal() { $('#modalContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); setTimeout(() => { $('#quizModal').addClass('hidden'); }, 300); }
    
    async function submitForm() {
        const form = $('#quizForm'); const id = $('#questionId').val(); const url = id ? `/admin/questions/update/${id}` : `{{ route('admin.questions.store') }}`;
        const returnChapter = $('#inputChapter').val() || window.currentQuestionBankChapter;
        const interactionType = $('#inputInteractionType').val() || 'multiple_choice';
        const mediaUrl = ($('#inputMediaUrl').val() || '').trim();
        const mediaFile = $('#inputMediaFile')[0]?.files?.length || 0;
        const removingMedia = $('#inputRemoveMedia').is(':checked');

        if (!($('#inputLearningObjectiveSelect').val() || '').trim()) {
            showQuizAlert({
                title: 'Pemetaan TP belum dipilih',
                text: 'Pilih tujuan pembelajaran resmi sesuai bab sebelum menyimpan soal.',
                icon: 'warning',
                confirmButtonColor: '#06b6d4',
            });
            return;
        }
        applySelectedLearningOutcome();

        if (interactionType === 'image_context' && (!mediaUrl || removingMedia) && !mediaFile) {
            showQuizAlert({
                title: 'Media belum tersedia',
                text: 'Jenis soal Gambar wajib memiliki upload gambar atau URL gambar. Pilih file gambar atau isi URL gambar terlebih dahulu.',
                icon: 'warning',
                confirmButtonColor: '#06b6d4',
            });
            return;
        }
        if (interactionType === 'image_context' && mediaUrl && !mediaFile) {
            const imageAvailable = await checkImageUrlAvailable(mediaUrl);
            if (!imageAvailable) {
                showQuizAlert({
                    title: 'Gambar tidak dapat dimuat',
                    text: 'URL gambar tidak dapat dibuka. Periksa kembali URL gambar atau gunakan upload file.',
                    icon: 'error',
                    confirmButtonColor: '#ef4444',
                });
                return;
            }
        }
        if(!form[0].checkValidity()) { form[0].reportValidity(); return; }

        const formData = new FormData(form[0]);
        
        const isDark = document.documentElement.classList.contains('dark');
        const bg = isDark ? '#0f141e' : '#ffffff';
        const color = isDark ? '#fff' : '#1e293b';

        showQuizAlert({ title: 'Menyimpan...', didOpen: () => Swal.showLoading(), background: bg, color: color });
        $.ajax({
            url,
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
        }).done((res) => {
            showQuizAlert({ title: 'Berhasil!', text: res.message, icon: 'success', background: bg, color: color, confirmButtonColor: '#6366f1' }).then(() => redirectToActiveQuestionChapter(returnChapter));
        }).fail((err) => {
            showQuizAlert({ title: 'Gagal', text: err.responseJSON?.message || 'Terjadi kesalahan sistem', icon: 'error', background: bg, color: color, confirmButtonColor: '#ef4444' });
        });
    }

    function confirmHapus(id) {
        const isDark = document.documentElement.classList.contains('dark');
        const bg = isDark ? '#0f141e' : '#ffffff';
        const color = isDark ? '#fff' : '#1e293b';
        const cancelBg = isDark ? '#334155' : '#e2e8f0';

        Swal.fire({ 
            title: 'Hapus Pertanyaan?', 
            text: "Tindakan ini akan menghapus soal secara permanen!", 
            icon: 'warning', 
            showCancelButton: true, 
            confirmButtonColor: '#ef4444', 
            cancelButtonColor: cancelBg, 
            confirmButtonText: 'Ya, Hapus!', 
            cancelButtonText: 'Batal', 
            background: bg, 
            color: color 
        }).then((result) => { 
            if (result.isConfirmed) { 
                $.ajax({ url: `/admin/questions/delete/${id}`, type: 'DELETE', success: function(res) { 
                    Swal.fire({ title: 'Terhapus!', text: res.message, icon: 'success', background: bg, color: color, confirmButtonColor: '#6366f1' }).then(() => location.reload()); 
                }}); 
            } 
        });
    }

    function openInsightModalById(questionId) {
        const data = questionInsightData[questionId] || {};
        openInsightModal(data);
    }

    function escapeHtml(value = '') {
        return String(value ?? '').replace(/[&<>"']/g, (char) => ({
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        }[char]));
    }

    function renderStudentAnswerList(list = [], type = 'correct') {
        const isCorrect = type === 'correct';
        const palette = isCorrect
            ? {
                card: 'border-emerald-200 bg-white hover:border-emerald-300 dark:border-emerald-500/20 dark:bg-[#0a0e17] dark:hover:border-emerald-500/40',
                avatar: 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200',
                pill: 'border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-500/20 dark:bg-emerald-500/10 dark:text-emerald-200',
                label: 'text-emerald-700 dark:text-emerald-300',
                empty: 'Belum ada siswa yang menjawab benar pada soal ini.',
            }
            : {
                card: 'border-red-200 bg-white hover:border-red-300 dark:border-red-500/20 dark:bg-[#0a0e17] dark:hover:border-red-500/40',
                avatar: 'border-red-200 bg-red-50 text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200',
                pill: 'border-red-200 bg-red-50 text-red-700 dark:border-red-500/20 dark:bg-red-500/10 dark:text-red-200',
                label: 'text-red-700 dark:text-red-300',
                empty: 'Belum ada siswa yang menjawab salah pada soal ini.',
            };

        if (!list.length) {
            return `<p class="rounded-xl border border-dashed border-slate-300 bg-white/70 p-4 text-center text-[11px] italic text-slate-500 dark:border-white/10 dark:bg-[#0a0e17]/50 dark:text-white/40">${palette.empty}</p>`;
        }

        return list.map((student) => {
            const name = student.name || 'Tanpa nama';
            const initial = name.trim().charAt(0) || '?';
            const status = isCorrect ? 'Benar' : 'Salah';
            const context = student.context || (isCorrect
                ? 'Siswa sudah menjawab sesuai kunci pada percobaan terbaru.'
                : 'Siswa memilih opsi yang belum sesuai pada percobaan terbaru.');

            return `
                <article class="rounded-xl border ${palette.card} p-3 text-xs shadow-sm transition">
                    <div class="flex items-start gap-3">
                        <div class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg border ${palette.avatar} text-[11px] font-black">${escapeHtml(initial)}</div>
                        <div class="min-w-0 flex-1">
                            <div class="flex items-start justify-between gap-2">
                                <div class="min-w-0">
                                    <p class="truncate font-black text-slate-900 dark:text-white">${escapeHtml(name)}</p>
                                    <p class="truncate text-[10px] text-slate-500 dark:text-slate-400">${escapeHtml(student.email || '-')}</p>
                                </div>
                                <span class="shrink-0 rounded-lg border px-2 py-0.5 text-[10px] font-black ${palette.pill}">${status}</span>
                            </div>
                            <div class="mt-2 flex flex-wrap gap-1.5 text-[10px] text-slate-500 dark:text-slate-400">
                                <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 dark:border-white/10 dark:bg-white/5">${escapeHtml(student.class_group || 'Kelas belum diatur')}</span>
                                <span class="rounded-md border border-slate-200 bg-slate-50 px-2 py-1 dark:border-white/10 dark:bg-white/5">${escapeHtml(student.answered_at || '-')}</span>
                            </div>
                            <div class="mt-3 rounded-lg border border-slate-200 bg-slate-50 px-3 py-2 text-[11px] leading-5 text-slate-700 dark:border-white/10 dark:bg-white/5 dark:text-slate-200">
                                <p><span class="font-black ${palette.label}">Jawaban siswa:</span> ${escapeHtml(student.chosen || 'Tidak dijawab')}</p>
                                <p class="mt-1"><span class="font-black text-emerald-700 dark:text-emerald-300">Kunci:</span> ${escapeHtml(student.correct || 'Tidak ada kunci')}</p>
                            </div>
                            <p class="mt-2 rounded-lg border border-slate-200 bg-white/80 px-3 py-2 text-[11px] leading-5 text-slate-600 dark:border-white/10 dark:bg-black/10 dark:text-slate-300">${escapeHtml(context)}</p>
                        </div>
                    </div>
                </article>
            `;
        }).join('');
    }

    function openInsightModal(meta = {}) {
        const total = Number(meta.total_attempts || 0);
        const correct = Number(meta.correct_count || 0);
        const wrong = Number(meta.wrong_count || 0);
        const correctPercent = total > 0 ? Math.round((correct / total) * 100) : 0;
        const wrongPercent = total > 0 ? Math.round((wrong / total) * 100) : 0;

        $('#countTotalNumber').text(total);
        $('#countCorrect').text(correct + ' siswa benar');
        $('#countWrong').text(wrong + ' siswa salah');
        $('#countCorrectNumber').text(correct);
        $('#countWrongNumber').text(wrong);
        $('#insightQuestion').text(meta.question || 'Soal belum tersedia');
        $('#insightOutcomeCode').text(meta.outcome_code || 'TP');
        $('#insightOutcomeTitle').text(meta.outcome_title || 'Tujuan pembelajaran terkait');
        $('#insightAccuracy').text(correctPercent + '% akurasi siswa menjawab benar');
        $('#correctBar').css('width', correctPercent + '%');
        $('#wrongBar').css('width', wrongPercent + '%');
        $('#correctQuantityNote').text(correct + ' siswa atau ' + correctPercent + '% dari total siswa menjawab.');
        $('#wrongQuantityNote').text(wrong + ' siswa atau ' + wrongPercent + '% dari total siswa menjawab.');
        $('#listCorrectCount').text(correct + ' siswa');
        $('#listWrongCount').text(wrong + ' siswa');
        $('#listCorrect').html(renderStudentAnswerList(meta.correct || [], 'correct'));
        $('#listWrong').html(renderStudentAnswerList(meta.wrong || [], 'wrong'));
        $('#insightModal').removeClass('hidden'); setTimeout(() => { $('#insightContent').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'); }, 10);
    }
    function closeInsightModal() { $('#insightContent').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0'); setTimeout(() => { $('#insightModal').addClass('hidden'); }, 300); }
</script>
</body>
</html>
