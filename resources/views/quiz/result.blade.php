<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasil Evaluasi Bab {{ $chapterId }} | Utilwind</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800;900&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">

    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        .glass-card {
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(20px);
        }
        .soft-panel {
            background: rgba(15, 23, 42, 0.72);
            border: 1px solid rgba(255, 255, 255, 0.08);
            backdrop-filter: blur(18px);
        }
        .custom-scrollbar::-webkit-scrollbar { width: 7px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.12); border-radius: 999px; }
    </style>
</head>
<body class="bg-[#020617] text-white min-h-screen selection:bg-fuchsia-500/30">
    @php
        $score = (int) $attempt->score;
        $isPassed = $score >= 70;
        $duration = (int) ($attempt->time_spent_seconds ?? 0);
        $durationText = gmdate($duration >= 3600 ? 'H:i:s' : 'i:s', max(0, $duration));
        $focusLost = (int) ($attempt->focus_lost_count ?? 0);
        $levelColor = $score >= 85 ? 'emerald' : ($isPassed ? 'cyan' : 'red');
    @endphp

    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div class="absolute top-[-12%] left-[-12%] w-[540px] h-[540px] bg-purple-600/20 rounded-full blur-[110px]"></div>
        <div class="absolute bottom-[-12%] right-[-12%] w-[540px] h-[540px] bg-fuchsia-600/20 rounded-full blur-[110px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
    </div>

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 lg:py-12">
        <div class="flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5 mb-8">
            <div>
                <p class="text-[11px] font-bold tracking-[0.28em] uppercase text-fuchsia-300/70 mb-2">Feedback Akhir Evaluasi</p>
                <h1 class="text-3xl md:text-5xl font-black tracking-tight">Evaluasi Bab {{ $chapterId }}</h1>
                <p class="text-sm text-white/50 mt-3 max-w-2xl leading-relaxed">
                    Ringkasan ini membantu Anda memahami hasil pengerjaan, bagian yang sudah kuat, dan bagian yang masih perlu ditinjau kembali.
                </p>
            </div>

            <div class="flex flex-wrap gap-3">
                @if(!$isPassed)
                    <a href="{{ route('quiz.intro', ['chapterId' => $chapterId]) }}" class="px-5 py-3 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 hover:from-fuchsia-500 hover:to-purple-500 text-white text-sm font-bold shadow-lg shadow-purple-900/30 transition">
                        Ulangi Evaluasi
                    </a>
                @endif
                <a href="{{ route('dashboard') }}" class="px-5 py-3 rounded-xl border border-white/10 bg-white/5 hover:bg-white/10 text-white/80 hover:text-white text-sm font-bold transition">
                    Kembali ke Dashboard
                </a>
            </div>
        </div>

        <section class="grid lg:grid-cols-[360px,1fr] gap-6 mb-6">
            <div class="glass-card rounded-3xl p-6 relative overflow-hidden">
                <div class="absolute -right-16 -top-16 w-44 h-44 rounded-full bg-{{ $levelColor }}-500/15 blur-2xl"></div>
                <div class="relative">
                    <p class="text-[10px] font-bold uppercase tracking-widest text-white/35 mb-3">Skor Akhir</p>
                    <div class="flex items-end gap-2 mb-4">
                        <span class="text-7xl font-black leading-none text-{{ $levelColor }}-400">{{ $score }}</span>
                        <span class="text-lg font-bold text-white/35 mb-2">/100</span>
                    </div>
                    <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-lg border text-xs font-bold {{ $isPassed ? 'border-emerald-500/30 bg-emerald-500/10 text-emerald-300' : 'border-red-500/30 bg-red-500/10 text-red-300' }}">
                        <span class="w-2 h-2 rounded-full {{ $isPassed ? 'bg-emerald-400' : 'bg-red-400' }}"></span>
                        {{ $feedback['level'] }}
                    </div>
                    <p class="text-sm text-white/60 leading-relaxed mt-5">{{ $feedback['message'] }}</p>
                </div>
            </div>

            <div class="grid sm:grid-cols-2 xl:grid-cols-4 gap-4">
                <div class="soft-panel rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-widest text-white/35 font-bold">Kelengkapan</p>
                    <p class="text-3xl font-black mt-2">{{ $metrics['completion_percent'] }}%</p>
                    <p class="text-xs text-white/45 mt-1">{{ $metrics['answered_count'] }} dari {{ $metrics['total_questions'] }} soal terjawab</p>
                </div>
                <div class="soft-panel rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-widest text-white/35 font-bold">Ketepatan</p>
                    <p class="text-3xl font-black mt-2 text-emerald-300">{{ $metrics['correct_count'] }}</p>
                    <p class="text-xs text-white/45 mt-1">{{ $metrics['wrong_count'] }} perlu ditinjau ulang</p>
                </div>
                <div class="soft-panel rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-widest text-white/35 font-bold">Durasi</p>
                    <p class="text-3xl font-black mt-2 font-mono">{{ $durationText }}</p>
                    <p class="text-xs text-white/45 mt-1">Waktu pengerjaan tersimpan</p>
                </div>
                <div class="soft-panel rounded-2xl p-5">
                    <p class="text-[10px] uppercase tracking-widest text-white/35 font-bold">Fokus</p>
                    <p class="text-3xl font-black mt-2 text-amber-300">{{ $focusLost }}</p>
                    <p class="text-xs text-white/45 mt-1">Perpindahan tab terdeteksi</p>
                </div>
            </div>
        </section>

        <section class="grid lg:grid-cols-[1fr,380px] gap-6">
            <div class="glass-card rounded-3xl overflow-hidden">
                <div class="p-5 border-b border-white/10 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3">
                    <div>
                        <h2 class="font-black text-xl">Tinjauan Jawaban</h2>
                        <p class="text-xs text-white/45 mt-1">Gunakan bagian ini untuk melihat soal yang perlu diperkuat.</p>
                    </div>
                    <div class="text-xs text-white/45 font-mono">
                        {{ $metrics['flagged_count'] }} ragu-ragu / {{ $metrics['unanswered_count'] }} kosong
                    </div>
                </div>

                <div class="divide-y divide-white/10 max-h-[720px] overflow-y-auto custom-scrollbar">
                    @foreach($reviewItems as $item)
                        <article class="p-5 hover:bg-white/[0.025] transition">
                            <div class="flex items-start justify-between gap-4 mb-3">
                                <div class="flex items-center gap-3">
                                    <span class="w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black {{ $item['is_correct'] ? 'bg-emerald-500/15 text-emerald-300 border border-emerald-500/25' : 'bg-red-500/15 text-red-300 border border-red-500/25' }}">
                                        {{ $item['number'] }}
                                    </span>
                                    <span class="text-xs font-bold uppercase tracking-widest {{ $item['is_correct'] ? 'text-emerald-300' : 'text-red-300' }}">
                                        {{ $item['status'] }}
                                    </span>
                                </div>
                                @if($item['is_flagged'])
                                    <span class="px-2.5 py-1 rounded-lg bg-amber-500/10 text-amber-300 border border-amber-500/20 text-[10px] font-bold uppercase tracking-widest">Ragu-ragu</span>
                                @endif
                            </div>

                            <div class="text-sm md:text-base text-white/80 leading-relaxed mb-4">
                                {!! $item['question']->question_text !!}
                            </div>

                            <div class="grid md:grid-cols-2 gap-3">
                                <div class="rounded-2xl border border-white/10 bg-white/[0.03] p-4">
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-white/35 mb-2">Jawaban Anda</p>
                                    <p class="text-sm text-white/72 leading-relaxed">
                                        {{ $item['selected_option']->option_text ?? 'Belum dijawab' }}
                                    </p>
                                </div>
                                <div class="rounded-2xl border border-emerald-500/18 bg-emerald-500/[0.055] p-4">
                                    <p class="text-[10px] uppercase tracking-widest font-bold text-emerald-300/80 mb-2">Jawaban Benar</p>
                                    <p class="text-sm text-white/80 leading-relaxed">
                                        {{ $item['correct_option']->option_text ?? 'Kunci belum tersedia' }}
                                    </p>
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>
            </div>

            <aside class="space-y-4">
                <div class="glass-card rounded-3xl p-6">
                    <h2 class="font-black text-lg mb-4">Ringkasan Pengerjaan</h2>
                    <div class="space-y-3">
                        <div class="flex items-center justify-between rounded-xl bg-white/[0.035] border border-white/10 px-4 py-3">
                            <span class="text-sm text-white/60">Soal kosong</span>
                            <span class="font-black">{{ $metrics['unanswered_count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/[0.035] border border-white/10 px-4 py-3">
                            <span class="text-sm text-white/60">Ragu-ragu</span>
                            <span class="font-black">{{ $metrics['flagged_count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/[0.035] border border-white/10 px-4 py-3">
                            <span class="text-sm text-white/60">Perubahan jawaban</span>
                            <span class="font-black">{{ $metrics['answer_change_count'] }}</span>
                        </div>
                        <div class="flex items-center justify-between rounded-xl bg-white/[0.035] border border-white/10 px-4 py-3">
                            <span class="text-sm text-white/60">Status KKM</span>
                            <span class="font-black {{ $isPassed ? 'text-emerald-300' : 'text-red-300' }}">{{ $isPassed ? 'Lulus' : 'Remedial' }}</span>
                        </div>
                    </div>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <h2 class="font-black text-lg mb-3">Catatan Refleksi</h2>
                    <p class="text-xs text-white/45 leading-relaxed mb-4">
                        Tuliskan bagian yang perlu dipahami ulang atau strategi belajar berikutnya.
                    </p>

                    @if(session('success'))
                        <div class="mb-4 rounded-xl border border-emerald-500/20 bg-emerald-500/10 px-4 py-3 text-xs font-bold text-emerald-300">
                            {{ session('success') }}
                        </div>
                    @endif

                    <form action="{{ route('quiz.reflection', $attempt->id) }}" method="POST" class="space-y-3">
                        @csrf
                        <textarea name="reflection_note" rows="5" maxlength="1000" class="w-full resize-none rounded-2xl border border-white/10 bg-[#020617]/70 p-4 text-sm text-white/80 outline-none transition focus:border-fuchsia-400/60 focus:ring-2 focus:ring-fuchsia-500/20" placeholder="Contoh: Saya masih perlu mengulang bagian grid/flexbox dan meninjau soal yang salah.">{{ old('reflection_note', $attempt->reflection_note) }}</textarea>
                        @error('reflection_note')
                            <p class="text-xs font-bold text-red-300">{{ $message }}</p>
                        @enderror
                        <button type="submit" class="w-full rounded-xl bg-white text-slate-950 py-3 text-xs font-black uppercase tracking-widest transition hover:bg-slate-200">
                            Simpan Refleksi
                        </button>
                    </form>
                </div>

                <div class="glass-card rounded-3xl p-6">
                    <h2 class="font-black text-lg mb-3">Arah Belajar</h2>
                    <p class="text-sm text-white/60 leading-relaxed">
                        @if($isPassed)
                            Lanjutkan materi berikutnya, tetapi tetap tinjau soal yang salah agar pemahaman tidak hanya mengejar skor.
                        @else
                            Ulangi materi Bab {{ $chapterId }}, kerjakan latihan kecil, lalu coba evaluasi ulang saat bagian yang salah sudah dipahami.
                        @endif
                    </p>
                </div>
            </aside>
        </section>
    </main>
</body>
</html>
