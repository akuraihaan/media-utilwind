@php
    $guideTitle = $guideTitle ?? 'Panduan Analitik';
    $guideSubtitle = $guideSubtitle ?? 'Bagian penting yang perlu dibaca';
    $guideImage = $guideImage ?? null;
    $guideIntro = $guideIntro ?? 'Gunakan nomor pada gambar untuk mengenali bagian penting halaman, lalu baca panduan di sebelah kanan.';
    $guidePoints = collect($guidePoints ?? []);
@endphp

<div class="space-y-5 text-left">
    <div class="grid gap-5 lg:grid-cols-[1.25fr_.75fr]">
        <div class="rounded-2xl border border-slate-200 bg-slate-50/80 p-3 shadow-inner dark:border-white/10 dark:bg-[#020617]/70">
            <div class="relative overflow-hidden rounded-xl border border-slate-200 bg-white dark:border-white/10 dark:bg-[#0a0e17]">
                @if($guideImage)
                    <img src="{{ asset($guideImage) }}" alt="{{ $guideTitle }}" class="block h-auto w-full bg-white object-contain">
                @else
                    <div class="aspect-[16/10] bg-slate-100 dark:bg-white/5"></div>
                @endif

                <div class="absolute inset-0 bg-slate-950/[0.02] dark:bg-slate-950/10"></div>

                @foreach($guidePoints as $pointIndex => $point)
                    <div
                        class="absolute z-30 grid h-7 w-7 place-items-center rounded-full border-2 border-white bg-indigo-600 text-[11px] font-black text-white shadow-lg ring-4 ring-indigo-500/20"
                        style="left: {{ $point['x'] ?? 50 }}%; top: {{ $point['y'] ?? 50 }}%; transform: translate(-50%, -50%);"
                        title="{{ $point['title'] ?? 'Bagian penting' }}">
                        {{ $pointIndex + 1 }}
                    </div>
                @endforeach
            </div>
        </div>

        <div class="space-y-3">
            <div>
                <p class="text-[10px] font-black uppercase tracking-[0.2em] text-indigo-600 dark:text-indigo-300">{{ $guideSubtitle }}</p>
                <h4 class="mt-1 text-xl font-black text-slate-900 dark:text-white">{{ $guideTitle }}</h4>
                <p class="mt-2 text-xs font-semibold leading-relaxed text-slate-500 dark:text-white/45">{{ $guideIntro }}</p>
            </div>

            @foreach($guidePoints as $pointIndex => $point)
                <div class="rounded-xl border border-slate-200 bg-white/85 p-3 shadow-sm dark:border-white/5 dark:bg-white/[0.03]">
                    <div class="flex items-start gap-3">
                        <span class="grid h-6 w-6 shrink-0 place-items-center rounded-lg bg-indigo-600 text-[10px] font-black text-white">{{ $pointIndex + 1 }}</span>
                        <div>
                            <p class="text-xs font-black text-slate-900 dark:text-white">{{ $point['title'] ?? 'Bagian penting' }}</p>
                            <p class="mt-1 text-[11px] font-semibold leading-relaxed text-slate-500 dark:text-white/45">{{ $point['description'] ?? '-' }}</p>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</div>
