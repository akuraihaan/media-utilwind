<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Tinjauan Hasil Lab · {{ $studentName ?? ($student->name ?? 'Siswa') }}</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=DM+Mono:wght@400;500&family=Inter:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                        mono: ['DM Mono', 'monospace'],
                    },
                    boxShadow: {
                        soft: '0 18px 60px -28px rgba(15, 23, 42, 0.35)',
                    }
                }
            }
        }
    </script>
    <style>
        body { font-family: 'Inter', sans-serif; background: #f8fafc; color: #0f172a; }
        .font-mono { font-family: 'DM Mono', monospace; font-variant-numeric: tabular-nums; }
        .data-number { font-variant-numeric: tabular-nums lining-nums; font-feature-settings: 'tnum' 1, 'lnum' 1; }
        .code-scroll::-webkit-scrollbar { height: 8px; width: 8px; }
        .code-scroll::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 999px; }
        .code-scroll::-webkit-scrollbar-track { background: transparent; }
    </style>
</head>
<body class="min-h-screen antialiased">
@php
    $safeRoute = function ($name, $parameters = [], $fallback = '#') {
        return \Illuminate\Support\Facades\Route::has($name) ? route($name, $parameters) : $fallback;
    };

    $metrics = $analysis['metrics'] ?? [];
    $reviewItems = $analysis['review_items'] ?? [];
    $completedAt = $history->completed_at ?: $history->created_at;
    $studentName = $student->name ?? 'Siswa';
    $labTitle = $lab->title ?? 'Lab';
    $labAnalyticsUrl = $safeRoute('admin.lab.analytics');
    $studentProfileUrl = !empty($student?->id)
        ? $safeRoute('admin.student.detail', $student->id, $safeRoute('admin.students.index'))
        : $safeRoute('admin.students.index');
    $score = (int) round((float) ($analysis['score'] ?? 0));
    $isPassed = (bool) ($analysis['is_passed'] ?? false);
    $totalSteps = max(0, (int) ($metrics['total_steps'] ?? 0));
    $completedSteps = max(0, (int) ($metrics['completed_steps'] ?? 0));
    $unfinishedSteps = max(0, $totalSteps - $completedSteps);
    $earnedPoints = max(0, (int) ($metrics['earned_points'] ?? 0));
    $totalPoints = max(0, (int) ($metrics['total_points'] ?? 0));
    $taskCompletionPercent = $totalSteps > 0 ? round(($completedSteps / $totalSteps) * 100) : 0;
    $pointCompletionPercent = $totalPoints > 0 ? round(($earnedPoints / $totalPoints) * 100) : 0;
    $statusClass = $analysis['is_passed']
        ? 'bg-emerald-50 text-emerald-700 border-emerald-200'
        : 'bg-rose-50 text-rose-700 border-rose-200';
@endphp

<div class="min-h-screen">
    <header class="sticky top-0 z-40 border-b border-slate-200/80 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-4 sm:px-6 lg:flex-row lg:items-center lg:justify-between lg:px-8">
            <div class="flex min-w-0 items-center gap-4">
                <a href="{{ $labAnalyticsUrl }}" class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-slate-50 text-slate-600 transition hover:border-cyan-200 hover:text-cyan-700" title="Kembali ke analitik lab">
                    <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                </a>
                <div class="min-w-0">
                    <nav class="mb-1 hidden text-[11px] font-bold uppercase tracking-[0.18em] text-slate-400 sm:block">
                        <a href="{{ $safeRoute('admin.dashboard') }}" class="hover:text-cyan-700">Dasbor</a>
                        <span class="mx-2">/</span>
                        <a href="{{ $labAnalyticsUrl }}" class="hover:text-cyan-700">Analitik Lab</a>
                        <span class="mx-2">/</span>
                        <span class="text-slate-600">Tinjauan Hasil Lab</span>
                    </nav>
                    <h1 class="truncate text-xl font-black tracking-tight text-slate-950 sm:text-2xl">Tinjauan Hasil Lab</h1>
                    <p class="mt-1 truncate text-sm text-slate-500">{{ $labTitle }} · {{ $studentName }}</p>
                </div>
            </div>
            <div class="flex flex-wrap items-center gap-2">
                <a href="{{ $labAnalyticsUrl }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-cyan-200 hover:text-cyan-700">Analitik Lab</a>
                <a href="{{ $studentProfileUrl }}" class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-xs font-bold text-slate-600 shadow-sm transition hover:border-cyan-200 hover:text-cyan-700">Profil Siswa</a>
            </div>
        </div>
    </header>

    <main id="admin-main-content" class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">
        <section class="overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-soft">
            <div class="grid gap-0 lg:grid-cols-[1.1fr_0.9fr]">
                <div class="p-6 sm:p-8">
                    <div class="flex flex-wrap items-center gap-2">
                        <span class="inline-flex items-center rounded-full border px-3 py-1 text-xs font-extrabold uppercase tracking-[0.16em] {{ $statusClass }}">{{ $analysis['status_label'] }}</span>
                        <span class="inline-flex items-center rounded-full border border-slate-200 bg-slate-50 px-3 py-1 text-xs font-bold text-slate-500">Dikumpulkan {{ $completedAt ? \Carbon\Carbon::parse($completedAt)->translatedFormat('d M Y, H:i') : '-' }}</span>
                    </div>

                    <div class="mt-8 grid gap-6 sm:grid-cols-[auto_1fr] sm:items-end">
                        <div class="relative h-40 w-40">
                            <svg class="h-full w-full -rotate-90" viewBox="0 0 120 120">
                                <circle cx="60" cy="60" r="52" stroke="#e2e8f0" stroke-width="12" fill="none"/>
                                <circle cx="60" cy="60" r="52" stroke="{{ $analysis['is_passed'] ? '#10b981' : '#f43f5e' }}" stroke-width="12" fill="none" stroke-linecap="round" stroke-dasharray="{{ min(100, max(0, $analysis['score'])) * 3.27 }} 327"/>
                            </svg>
                            <div class="absolute inset-0 flex flex-col items-center justify-center">
                                <span class="text-4xl font-black text-slate-950">{{ $analysis['score'] }}</span>
                                <span class="text-[10px] font-bold uppercase tracking-[0.18em] text-slate-400">Skor</span>
                            </div>
                        </div>
                        <div class="min-w-0">
                            <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-cyan-700">Data Pengerjaan</p>
                            <h2 class="mt-2 text-2xl font-black text-slate-950">Skor akhir {{ $score }} dari 100</h2>
                            <p class="mt-3 max-w-2xl text-sm leading-7 text-slate-600">
                                {{ $completedSteps }} dari {{ $totalSteps }} tugas selesai · {{ $metrics['earned_points'] ?? 0 }} dari {{ $metrics['total_points'] ?? 0 }} poin · durasi {{ $metrics['duration_text'] ?? '-' }}.
                            </p>
                        </div>
                    </div>
                </div>
                <div class="border-t border-slate-200 bg-slate-50 p-6 sm:p-8 lg:border-l lg:border-t-0">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.16em] text-slate-500">Ringkasan Praktik</p>
                    <p class="mt-3 text-xl font-black text-slate-950">{{ $labTitle }}</p>
                    @if(!empty($chapterSummary['number']) || !empty($chapterSummary['title']))
                        <span class="mt-3 inline-flex rounded-lg border border-cyan-200 bg-cyan-50 px-2.5 py-1 text-[10px] font-extrabold uppercase tracking-wider text-cyan-700">Bab {{ $chapterSummary['number'] ?? '-' }} · {{ $chapterSummary['title'] ?? 'Materi Praktik' }}</span>
                    @endif
                    <p class="mt-4 text-sm leading-7 text-slate-600">{{ $chapterSummary['summary'] ?? 'Data berikut berasal dari satu riwayat praktik yang telah diselesaikan oleh siswa.' }}</p>

                    <dl class="mt-6 divide-y divide-slate-200 overflow-hidden rounded-xl border border-slate-200 bg-white text-sm">
                        <div class="flex items-center justify-between gap-4 px-4 py-3"><dt class="text-slate-500">Siswa</dt><dd class="text-right font-bold text-slate-950">{{ $studentName }}</dd></div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3"><dt class="text-slate-500">Lab</dt><dd class="text-right font-bold text-slate-950">{{ $labTitle }}</dd></div>
                        <div class="flex items-center justify-between gap-4 px-4 py-3"><dt class="text-slate-500">Status</dt><dd class="text-right font-bold {{ $isPassed ? 'text-emerald-700' : 'text-rose-700' }}">{{ $analysis['status_label'] ?? ($isPassed ? 'Lulus' : 'Belum lulus') }}</dd></div>
                    </dl>
                </div>
            </div>
        </section>

        <section class="mt-6 grid gap-4 sm:grid-cols-2 lg:grid-cols-4" aria-label="Ringkasan angka praktik">
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Tugas Selesai</p>
                <p class="data-number mt-2 text-2xl font-black text-slate-950">{{ $completedSteps }}/{{ $totalSteps }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Task dengan status selesai.</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Poin Diperoleh</p>
                <p class="data-number mt-2 text-2xl font-black text-slate-950">{{ $metrics['earned_points'] ?? 0 }}/{{ $metrics['total_points'] ?? 0 }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Total poin yang tercatat.</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Durasi</p>
                <p class="data-number mt-2 font-mono text-2xl font-black text-cyan-700">{{ $metrics['duration_text'] ?? '-' }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Waktu pengerjaan praktik.</p>
            </article>
            <article class="rounded-xl border border-slate-200 bg-white p-5 shadow-sm">
                <p class="text-[10px] font-extrabold uppercase tracking-[0.18em] text-slate-400">Rata-rata Skor Lab</p>
                <p class="data-number mt-2 text-2xl font-black text-cyan-700">{{ $labStats['average_score'] ?? 0 }}</p>
                <p class="mt-2 text-xs font-semibold text-slate-500">Rata-rata skor pada lab ini.</p>
            </article>
        </section>
        <section class="mt-6 grid gap-6 lg:grid-cols-[1.05fr_0.95fr]">
            <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">
                <div class="border-b border-slate-200 p-6">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Data Tugas</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Status Pengerjaan Tugas</h2>
                </div>
                <div class="divide-y divide-slate-100">
                    @foreach($reviewItems as $item)
                        <div class="p-6">
                            <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-3">
                                        <span class="flex h-8 w-8 shrink-0 items-center justify-center rounded-lg {{ $item['is_completed'] ? 'bg-emerald-100 text-emerald-700' : 'bg-rose-100 text-rose-700' }} text-sm font-black">{{ $item['number'] }}</span>
                                        <h3 class="min-w-0 truncate font-black text-slate-950">{{ $item['step']->title ?? 'Tugas Lab' }}</h3>
                                    </div>
                                    <p class="mt-3 text-sm leading-6 text-slate-600">{{ $item['step']->instruction ?? 'Tidak ada instruksi.' }}</p>
                                </div>
                                <span class="shrink-0 rounded-full border px-3 py-1 text-xs font-black {{ $item['is_completed'] ? 'border-emerald-200 bg-emerald-50 text-emerald-700' : 'border-rose-200 bg-rose-50 text-rose-700' }}">{{ $item['status'] }}</span>
                            </div>
                            <div class="mt-4 flex flex-wrap gap-2">
                                @forelse($item['rules'] as $rule)
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 font-mono text-[11px] font-bold text-slate-600">{{ $rule }}</span>
                                @empty
                                    <span class="rounded-lg border border-slate-200 bg-slate-50 px-2.5 py-1 text-[11px] font-bold text-slate-500">Tidak ada aturan khusus</span>
                                @endforelse
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="space-y-6">
                <aside class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm" aria-label="Catatan praktik">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Data Pengerjaan</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Catatan Praktik</h2>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Ringkasan angka yang tercatat pada riwayat praktik ini.</p>

                    <div class="mt-5 space-y-3 text-sm">
                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">Tugas selesai</span>
                                <span class="data-number font-black text-slate-950">{{ $completedSteps }}/{{ $totalSteps }}</span>
                            </div>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-200" aria-label="Penyelesaian tugas {{ $taskCompletionPercent }} persen">
                                <span class="block h-full rounded-full bg-cyan-500" style="width: {{ $taskCompletionPercent }}%"></span>
                            </div>
                            <p class="mt-1.5 text-[11px] font-semibold text-slate-500">{{ $taskCompletionPercent }}% dari seluruh tugas.</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-slate-500">Tugas belum selesai</span>
                            <span class="data-number font-black text-slate-950">{{ $unfinishedSteps }}</span>
                        </div>

                        <div class="rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <div class="flex items-center justify-between gap-4">
                                <span class="text-slate-500">Poin diperoleh</span>
                                <span class="data-number font-black text-slate-950">{{ $earnedPoints }}/{{ $totalPoints }}</span>
                            </div>
                            <div class="mt-2 h-1.5 overflow-hidden rounded-full bg-slate-200" aria-label="Perolehan poin {{ $pointCompletionPercent }} persen">
                                <span class="block h-full rounded-full bg-indigo-500" style="width: {{ $pointCompletionPercent }}%"></span>
                            </div>
                            <p class="mt-1.5 text-[11px] font-semibold text-slate-500">{{ $pointCompletionPercent }}% dari total poin.</p>
                        </div>

                        <div class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 px-4 py-3">
                            <span class="text-slate-500">Durasi praktik</span>
                            <span class="data-number font-mono font-black text-cyan-700">{{ $metrics['duration_text'] ?? '-' }}</span>
                        </div>
                    </div>
                </aside>

                <div class="rounded-2xl border border-slate-200 bg-white p-6 shadow-sm">
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Riwayat Praktik</p>
                    <h2 class="mt-2 text-xl font-black text-slate-950">Percobaan pada Lab Ini</h2>
                    <div class="mt-5 space-y-3">
                        @foreach($previousAttempts as $attempt)
                            <a href="{{ route('admin.labs.results.show', $attempt->id) }}" class="flex items-center justify-between gap-4 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-cyan-200 hover:bg-cyan-50/60">
                                <div class="min-w-0">
                                    <p class="truncate text-sm font-black text-slate-950">{{ \Carbon\Carbon::parse($attempt->created_at)->translatedFormat('d M Y, H:i') }}</p>
                                    <p class="mt-1 text-xs text-slate-500">{{ gmdate(($attempt->duration_seconds ?? 0) >= 3600 ? 'H:i:s' : 'i:s', max(0, (int) ($attempt->duration_seconds ?? 0))) }}</p>
                                </div>
                                <div class="shrink-0 text-right">
                                    <p class="text-lg font-black {{ ($attempt->final_score ?? 0) >= ($lab->passing_grade ?? 70) ? 'text-emerald-700' : 'text-rose-700' }}">{{ $attempt->final_score ?? 0 }}</p>
                                    <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400">Skor</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>

            </div>
        </section>

        <section class="mt-6 rounded-2xl border border-slate-200 bg-slate-950 shadow-soft">
            <div class="flex flex-col gap-3 border-b border-white/10 p-6 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-[11px] font-extrabold uppercase tracking-[0.2em] text-slate-400">Kode Akhir</p>
                    <h2 class="mt-2 text-xl font-black text-white">Cuplikan Kode Akhir</h2>
                </div>
                <span class="rounded-full border border-white/10 bg-white/5 px-3 py-1 text-xs font-bold text-slate-300">kode-akhir.html</span>
            </div>
            <pre class="code-scroll max-h-[520px] overflow-auto whitespace-pre-wrap break-words p-6 font-mono text-[12px] leading-6 text-slate-100"><code>{{ $analysis['source_code'] ?: 'Tidak ada cuplikan kode yang tersimpan.' }}</code></pre>
        </section>
    </main>
</div>
</body>
</html>
