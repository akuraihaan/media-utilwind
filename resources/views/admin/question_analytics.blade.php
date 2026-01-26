@extends('layouts.landing')

@section('title', 'Deep Analytics · Butir Soal')

@section('content')
<style>
    /* Premium Glassmorphism */
    .glass-panel {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
    
    /* Card Hover Glow Effects */
    .card-hover-effect { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .card-hover-effect:hover { transform: translateY(-4px); }
    .card-hover-effect.glow-red:hover { box-shadow: 0 0 30px -5px rgba(239, 68, 68, 0.3); border-color: rgba(239, 68, 68, 0.4); }
    .card-hover-effect.glow-yellow:hover { box-shadow: 0 0 30px -5px rgba(234, 179, 8, 0.3); border-color: rgba(234, 179, 8, 0.4); }
    .card-hover-effect.glow-green:hover { box-shadow: 0 0 30px -5px rgba(16, 185, 129, 0.3); border-color: rgba(16, 185, 129, 0.4); }

    /* Animation */
    .reveal { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.8s forwards; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
</style>

<div class="relative min-h-screen pt-28 pb-32 bg-[#020617] text-white overflow-x-hidden font-sans selection:bg-indigo-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        <div class="absolute -top-[20%] left-[20%] w-[1000px] h-[1000px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        <div class="mb-12 reveal" style="animation-delay: 0.1s;">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-white/40 hover:text-white mb-6 transition uppercase tracking-widest group">
                <span class="group-hover:-translate-x-1 transition">←</span> Kembali ke Panel Admin
            </a>

            <div class="flex flex-col lg:flex-row justify-between items-end gap-8">
                <div class="max-w-2xl">
                    <div class="flex items-center gap-3 mb-2">
                        <span class="flex h-2 w-2 relative">
                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-fuchsia-400 opacity-75"></span>
                            <span class="relative inline-flex rounded-full h-2 w-2 bg-fuchsia-500"></span>
                        </span>
                    </div>
                    <h1 class="text-4xl md:text-6xl font-black text-white leading-tight tracking-tight">
                        Analisis <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 via-indigo-400 to-cyan-400">Butir Soal</span>
                    </h1>
                    <p class="text-white/40 mt-4 text-lg leading-relaxed">
                        Insight mendalam mengenai kualitas pertanyaan kuis. Identifikasi soal yang 
                        <span class="text-red-400 font-bold">Terlalu Sulit</span> atau 
                        <span class="text-emerald-400 font-bold">Terlalu Mudah</span>.
                    </p>
                </div>

                <div class="w-full lg:w-[400px] relative group">
                    <div class="absolute -inset-0.5 bg-gradient-to-r from-fuchsia-500 to-cyan-500 rounded-2xl opacity-20 group-hover:opacity-40 transition duration-500 blur"></div>
                    <div class="relative flex items-center bg-[#0f141e] rounded-2xl border border-white/10">
                        <div class="pl-4 text-white/30 group-focus-within:text-white transition">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </div>
                        <input id="searchQuestion" type="text" placeholder="Cari pertanyaan..." 
                            class="w-full bg-transparent border-none text-white placeholder-white/20 focus:ring-0 py-4 px-4 outline-none">
                        <div class="pr-4 text-[10px] font-mono text-white/20 border-white/5 border px-2 py-1 rounded">CTRL+K</div>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-6 reveal" style="animation-delay: 0.3s;" id="questionGrid">
            
            @forelse($questions ?? [] as $index => $q)
                @php
                    // Logic Warna Dinamis
                    $acc = $q->accuracy ?? 0;
                    $statusColor = $acc >= 80 ? 'green' : ($acc >= 50 ? 'yellow' : 'red');
                    $bgGradient = $acc >= 80 
                        ? 'from-emerald-500/20 to-transparent' 
                        : ($acc >= 50 ? 'from-amber-500/20 to-transparent' : 'from-red-500/20 to-transparent');
                    $barColor = $acc >= 80 ? 'bg-emerald-500' : ($acc >= 50 ? 'bg-amber-500' : 'bg-red-500');
                @endphp

            <div class="question-card glass-panel rounded-3xl p-1 relative overflow-hidden card-hover-effect glow-{{ $statusColor }}">
                
                <div class="bg-[#0f141e] rounded-[22px] p-6 md:p-8 h-full relative z-10">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-[10px] font-bold text-white/50 uppercase tracking-widest font-mono">
                                BAB {{ $q->chapter_id }}
                            </span>
                            @if($statusColor == 'red')
                                <span class="px-3 py-1 rounded-lg bg-red-500/10 border border-red-500/20 text-[10px] font-bold text-red-400 uppercase tracking-widest flex items-center gap-2">
                                    <span class="relative flex h-1.5 w-1.5">
                                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                                      <span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span>
                                    </span>
                                    Sulit / Evaluasi
                                </span>
                            @elseif($statusColor == 'yellow')
                                <span class="px-3 py-1 rounded-lg bg-amber-500/10 border border-amber-500/20 text-[10px] font-bold text-amber-400 uppercase tracking-widest">
                                    Normal
                                </span>
                            @else
                                <span class="px-3 py-1 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-[10px] font-bold text-emerald-400 uppercase tracking-widest">
                                    Mudah
                                </span>
                            @endif
                        </div>
                        
                        <span class="text-white/10 font-black text-4xl absolute top-4 right-6 select-none">#{{ $index + 1 }}</span>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-bold text-white leading-relaxed mb-4 group-hover:text-indigo-300 transition">
                                {{ $q->question_text }}
                            </h3>
                            <div class="flex items-center gap-4 text-sm text-white/40">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                    <span>Total Responden: <strong class="text-white">{{ $q->total_attempts ?? 0 }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 bg-white/5 rounded-2xl p-5 border border-white/5 flex flex-col justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br {{ $bgGradient }} opacity-20"></div>

                            <div class="relative z-10">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-white/50 uppercase tracking-wider">Akurasi</span>
                                    <span class="text-3xl font-black text-white">{{ $acc }}<span class="text-sm text-white/40">%</span></span>
                                </div>

                                <div class="h-3 w-full bg-black/40 rounded-full overflow-hidden flex">
                                    <div class="h-full {{ $barColor }}" style="width: {{ $acc }}%"></div>
                                </div>

                                <div class="flex justify-between mt-3 text-[10px] font-bold uppercase tracking-wide">
                                    <div class="flex items-center gap-1.5 text-emerald-400">
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                        {{ $q->correct_count ?? 0 }} Benar
                                    </div>
                                    <div class="flex items-center gap-1.5 text-red-400">
                                        {{ $q->wrong_count ?? 0 }} Salah
                                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
            @empty
            <div class="py-20 text-center border border-dashed border-white/10 rounded-3xl">
                <div class="text-6xl mb-4 grayscale opacity-20">📊</div>
                <h3 class="text-2xl font-bold text-white">Belum Ada Data</h3>
                <p class="text-white/40 mt-2">Statistik akan muncul setelah siswa mulai mengerjakan kuis.</p>
            </div>
            @endforelse

        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    // Live Search Functionality
    $('#searchQuestion').on('keyup', function() {
        const val = $(this).val().toLowerCase();
        
        $('.question-card').each(function() {
            const text = $(this).find('h3').text().toLowerCase();
            if (text.indexOf(val) > -1) {
                $(this).fadeIn(200);
            } else {
                $(this).fadeOut(200);
            }
        });
    });

    // Shortcut Ctrl+K
    $(document).keydown(function(e) {
        if (e.ctrlKey && e.key === 'k') {
            e.preventDefault();
            $('#searchQuestion').focus();
        }
    });
</script>
@endsection