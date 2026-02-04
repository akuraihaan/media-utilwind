@extends('layouts.landing')

@section('title', 'Deep Analytics · Insight & Filter')

@section('content')
<style>
    /* --- GLASSMORPHISM & UTILS --- */
    .glass-panel {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.05);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
    }
    
    /* Input & Select Custom Styles */
    .glass-input {
        background: rgba(15, 20, 30, 0.8);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s;
    }
    .glass-input:focus {
        border-color: #6366f1;
        box-shadow: 0 0 15px rgba(99, 102, 241, 0.2);
        background: rgba(15, 20, 30, 1);
    }
    
    /* Card Hover */
    .card-hover-effect { transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1); }
    .card-hover-effect:hover { transform: translateY(-4px); }
    
    /* Glow Colors */
    .glow-red:hover { box-shadow: 0 0 30px -5px rgba(239, 68, 68, 0.3); border-color: rgba(239, 68, 68, 0.4); }
    .glow-yellow:hover { box-shadow: 0 0 30px -5px rgba(234, 179, 8, 0.3); border-color: rgba(234, 179, 8, 0.4); }
    .glow-green:hover { box-shadow: 0 0 30px -5px rgba(16, 185, 129, 0.3); border-color: rgba(16, 185, 129, 0.4); }

    /* Animations */
    .reveal { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.8s forwards; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    @keyframes fadeInItem { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    .animate-item { animation: fadeInItem 0.3s forwards; opacity: 0; }

    /* Custom Scrollbar */
    .custom-scroll::-webkit-scrollbar { width: 5px; }
    .custom-scroll::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    .custom-scroll::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<div class="relative min-h-screen pt-28 pb-32 bg-[#020617] text-white overflow-x-hidden font-sans selection:bg-indigo-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        <div class="absolute -top-[20%] left-[20%] w-[1000px] h-[1000px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        <div class="mb-10 reveal" style="animation-delay: 0.1s;">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-white/40 hover:text-white mb-6 transition uppercase tracking-widest group">
                <span class="group-hover:-translate-x-1 transition">←</span> Kembali ke Dashboard
            </a>

            <div class="flex flex-col lg:flex-row justify-between items-end gap-6">
                <div>
                    <h1 class="text-4xl md:text-6xl font-black text-white leading-tight tracking-tight">
                        Analisis <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 via-indigo-400 to-cyan-400">Insight</span>
                    </h1>
                    <p class="text-white/40 mt-2 text-lg">
                        Filter berdasarkan Bab atau tingkat kesulitan untuk analisis yang lebih tajam.
                    </p>
                </div>
            </div>
        </div>

        <div class="sticky top-24 z-40 mb-8 reveal" style="animation-delay: 0.2s;">
            <div class="glass-panel p-4 rounded-2xl flex flex-col md:flex-row gap-4 justify-between items-center">
                
                <div class="relative w-full md:w-1/3">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-white/30">
                        <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input id="searchInput" type="text" placeholder="Cari teks soal..." 
                        class="w-full pl-10 pr-4 py-2.5 rounded-xl glass-input outline-none text-sm placeholder-white/30">
                </div>

                <div class="flex gap-3 w-full md:w-auto overflow-x-auto pb-1 md:pb-0">
                    
                    <div class="relative min-w-[140px]">
                        <select id="filterChapter" class="w-full pl-3 pr-10 py-2.5 rounded-xl glass-input outline-none text-sm appearance-none cursor-pointer">
                            <option value="all" class="bg-[#0f141e]">📂 Semua Bab</option>
                            @php
                                $chapters = $questions->pluck('chapter_id')->unique()->sort();
                            @endphp
                            @foreach($chapters as $chap)
                                <option value="{{ $chap }}" class="bg-[#0f141e]">Bab {{ $chap }}</option>
                            @endforeach
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-white/50">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="relative min-w-[160px]">
                        <select id="sortOption" class="w-full pl-3 pr-10 py-2.5 rounded-xl glass-input outline-none text-sm appearance-none cursor-pointer">
                            <option value="default" class="bg-[#0f141e]"> Urutkan Default</option>
                            <option value="hardest" class="bg-[#0f141e]"> Paling Sulit (0% ➝ 100%)</option>
                            <option value="easiest" class="bg-[#0f141e]"> Paling Mudah (100% ➝ 0%)</option>
                            <option value="most_attempt" class="bg-[#0f141e]"> Paling Banyak Dijawab</option>
                        </select>
                        <div class="absolute inset-y-0 right-3 flex items-center pointer-events-none text-white/50">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M3 4h13M3 8h9m-9 4h6m4 0l4-4m0 0l4 4m-4-4v12"/></svg>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div id="questionsContainer" class="grid grid-cols-1 gap-6 reveal" style="animation-delay: 0.3s;">
            
            @forelse($questions ?? [] as $index => $q)
                @php
                    $acc = $q->accuracy ?? 0;
                    
                    // Styling Logic
                    if($acc >= 80) {
                        $statusColor = 'green';
                        $bgGradient = 'from-emerald-500/20';
                        $barColor = 'bg-emerald-500';
                        $badgeText = 'Mudah';
                        $badgeClass = 'bg-emerald-500/10 border-emerald-500/20 text-emerald-400';
                    } elseif($acc >= 50) {
                        $statusColor = 'yellow';
                        $bgGradient = 'from-amber-500/20';
                        $barColor = 'bg-amber-500';
                        $badgeText = 'Sedang';
                        $badgeClass = 'bg-amber-500/10 border-amber-500/20 text-amber-400';
                    } else {
                        $statusColor = 'red';
                        $bgGradient = 'from-red-500/20';
                        $barColor = 'bg-red-500';
                        $badgeText = 'Sulit';
                        $badgeClass = 'bg-red-500/10 border-red-500/20 text-red-400';
                    }
                @endphp

            <div class="question-item question-card glass-panel rounded-3xl p-1 relative overflow-hidden card-hover-effect glow-{{ $statusColor }}"
                 data-chapter="{{ $q->chapter_id }}"
                 data-accuracy="{{ $acc }}"
                 data-attempts="{{ $q->total_attempts }}"
                 data-search="{{ strtolower($q->question_text) }}">
                
                <div class="bg-[#0f141e] rounded-[22px] p-6 md:p-8 h-full relative z-10">
                    
                    <div class="flex justify-between items-start mb-6">
                        <div class="flex items-center gap-3">
                            <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-[10px] font-bold text-white/50 uppercase tracking-widest font-mono">
                                BAB {{ $q->chapter_id }}
                            </span>
                            <span class="px-3 py-1 rounded-lg border text-[10px] font-bold uppercase tracking-widest flex items-center gap-2 {{ $badgeClass }}">
                                @if($statusColor == 'red')
                                    <span class="relative flex h-1.5 w-1.5"><span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span><span class="relative inline-flex rounded-full h-1.5 w-1.5 bg-red-500"></span></span>
                                @endif
                                {{ $badgeText }}
                            </span>
                        </div>
                        <span class="text-white/10 font-black text-4xl absolute top-4 right-6 select-none">#{{ $q->id }}</span>
                    </div>

                    <div class="grid lg:grid-cols-3 gap-8">
                        <div class="lg:col-span-2">
                            <h3 class="text-xl font-bold text-white leading-relaxed mb-4 group-hover:text-indigo-300 transition">
                                {{ $q->question_text }}
                            </h3>
                            <div class="flex items-center gap-4 text-sm text-white/40">
                                <div class="flex items-center gap-2">
                                    <div class="w-2 h-2 rounded-full bg-white/20"></div>
                                    <span>Total Responden: <strong class="text-white">{{ $q->total_attempts }}</strong></span>
                                </div>
                            </div>
                        </div>

                        <div class="lg:col-span-1 bg-white/5 rounded-2xl p-5 border border-white/5 flex flex-col justify-center relative overflow-hidden">
                            <div class="absolute inset-0 bg-gradient-to-br {{ $bgGradient }} to-transparent opacity-20"></div>

                            <div class="relative z-10">
                                <div class="flex justify-between items-end mb-2">
                                    <span class="text-xs font-bold text-white/50 uppercase tracking-wider">Akurasi</span>
                                    <span class="text-3xl font-black text-white">{{ $acc }}<span class="text-sm text-white/40">%</span></span>
                                </div>
                                <div class="h-3 w-full bg-black/40 rounded-full overflow-hidden flex mb-4">
                                    <div class="h-full {{ $barColor }}" style="width: {{ $acc }}%"></div>
                                </div>

                                <div class="flex gap-2">
                                    <button onclick='openInsightModal("Benar", @json($q->list_correct))' 
                                        class="flex-1 py-2 px-3 rounded-lg bg-emerald-500/10 border border-emerald-500/20 hover:bg-emerald-500/20 hover:scale-105 transition group/btn text-left relative overflow-hidden">
                                        <div class="text-[10px] uppercase text-emerald-400 font-bold tracking-wider mb-0.5">Benar</div>
                                        <div class="flex items-center justify-between text-white font-bold text-sm">
                                            {{ $q->correct_count }} Siswa
                                            <svg class="w-3 h-3 text-emerald-400 group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </button>

                                    <button onclick='openInsightModal("Salah", @json($q->list_wrong))' 
                                        class="flex-1 py-2 px-3 rounded-lg bg-red-500/10 border border-red-500/20 hover:bg-red-500/20 hover:scale-105 transition group/btn text-left relative overflow-hidden">
                                        <div class="text-[10px] uppercase text-red-400 font-bold tracking-wider mb-0.5">Salah</div>
                                        <div class="flex items-center justify-between text-white font-bold text-sm">
                                            {{ $q->wrong_count }} Siswa
                                            <svg class="w-3 h-3 text-red-400 group-hover/btn:translate-x-1 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M9 5l7 7-7 7"/></svg>
                                        </div>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div id="noDataState" class="py-20 text-center border border-dashed border-white/10 rounded-3xl">
                <div class="text-6xl mb-4 grayscale opacity-20">📊</div>
                <h3 class="text-2xl font-bold text-white">Belum Ada Data</h3>
            </div>
            @endforelse
            
            <div id="emptyFilterState" class="hidden py-20 text-center border border-dashed border-white/10 rounded-3xl col-span-1">
                <div class="text-5xl mb-4 opacity-30">🔍</div>
                <h3 class="text-xl font-bold text-white">Tidak ditemukan</h3>
                <p class="text-white/40 mt-1">Coba ubah filter atau kata kunci pencarian Anda.</p>
            </div>

        </div>
    </div>
</div>

<div id="insightModal" class="fixed inset-0 z-50 hidden" style="backdrop-filter: blur(4px);">
    <div class="absolute inset-0 bg-black/80 transition-opacity opacity-0" id="modalBackdrop"></div>
    <div class="absolute inset-0 flex items-center justify-center p-4">
        <div class="bg-[#0f141e] border border-white/10 w-full max-w-sm rounded-3xl shadow-2xl transform scale-95 opacity-0 transition-all duration-300 relative overflow-hidden flex flex-col max-h-[80vh]" id="modalContent">
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-white/5">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Siswa</h3>
                    <div id="modalSubtitle" class="text-xs mt-1 flex items-center gap-2"></div>
                </div>
                <button onclick="closeInsightModal()" class="w-8 h-8 rounded-full bg-white/5 flex items-center justify-center text-white/50 hover:bg-white/10 hover:text-white transition">✕</button>
            </div>
            <div class="p-4 overflow-y-auto custom-scroll bg-[#020617]/50 flex-1" id="studentListContainer"></div>
            <div class="p-4 border-t border-white/5 bg-[#0f141e] text-center">
                <button onclick="closeInsightModal()" class="text-xs font-bold text-white/30 hover:text-white transition uppercase tracking-widest">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        
        // --- LOGIC FILTER & SEARCH & SORT ---
        const $container = $('#questionsContainer');
        const $items = $('.question-item'); // Semua kartu soal
        const $emptyState = $('#emptyFilterState');

        function applyFilters() {
            const searchVal = $('#searchInput').val().toLowerCase();
            const chapterVal = $('#filterChapter').val();
            const sortVal = $('#sortOption').val();

            // 1. Filter (Show/Hide)
            let visibleItems = $items.filter(function() {
                const $el = $(this);
                const textMatch = $el.data('search').includes(searchVal);
                const chapterMatch = (chapterVal === 'all') || ($el.data('chapter') == chapterVal);
                
                return textMatch && chapterMatch;
            });

            // 2. Sort (Reorder DOM)
            visibleItems.sort(function(a, b) {
                const accA = $(a).data('accuracy');
                const accB = $(b).data('accuracy');
                const attA = $(a).data('attempts');
                const attB = $(b).data('attempts');

                if (sortVal === 'hardest') return accA - accB; // Rendah ke Tinggi
                if (sortVal === 'easiest') return accB - accA; // Tinggi ke Rendah
                if (sortVal === 'most_attempt') return attB - attA; // Banyak ke Sedikit
                return 0; // Default (urutan asli/ID)
            });

            // 3. Render Ulang
            $items.detach(); // Lepas semua dari DOM sementara
            $container.append(visibleItems); // Masukkan yang visible & sorted
            
            // Show/Hide Items visually
            $items.hide();
            visibleItems.fadeIn(200);

            // Empty State Check
            if (visibleItems.length === 0) $emptyState.removeClass('hidden');
            else $emptyState.addClass('hidden');
        }

        // Event Listeners
        $('#searchInput').on('keyup', applyFilters);
        $('#filterChapter').on('change', applyFilters);
        $('#sortOption').on('change', applyFilters);

        // Shortcut Search
        $(document).keydown(e => { 
            if (e.ctrlKey && e.key === 'k') { 
                e.preventDefault(); 
                $('#searchInput').focus(); 
            } 
        });
    });

    // --- LOGIC MODAL (INSIGHT SISWA) ---
    function openInsightModal(type, students) {
        const modal = $('#insightModal');
        const backdrop = $('#modalBackdrop');
        const content = $('#modalContent');
        const listContainer = $('#studentListContainer');
        const subtitle = $('#modalSubtitle');

        listContainer.empty();

        const isCorrect = type === 'Benar';
        const colorClass = isCorrect ? 'text-emerald-400' : 'text-red-400';
        const bgClass = isCorrect ? 'bg-emerald-500/10 border-emerald-500/20' : 'bg-red-500/10 border-red-500/20';
        const badgeText = isCorrect ? 'Menjawab Benar' : 'Menjawab Salah';

        subtitle.html(`
            <span class="w-2 h-2 rounded-full ${isCorrect ? 'bg-emerald-500' : 'bg-red-500'}"></span>
            <span class="${colorClass} font-bold">${badgeText}</span>
        `);

        if (!students || students.length === 0) {
            listContainer.append(`
                <div class="flex flex-col items-center justify-center py-10 text-center opacity-50">
                    <div class="text-2xl mb-2 grayscale">∅</div>
                    <p class="text-sm">Tidak ada data siswa.</p>
                </div>
            `);
        } else {
            students.forEach((name, i) => {
                listContainer.append(`
                    <div class="flex items-center gap-3 p-3 mb-2 rounded-xl bg-white/5 border border-white/5 hover:border-white/20 transition animate-item" style="animation-delay: ${i * 0.05}s">
                        <div class="w-8 h-8 rounded-full ${bgClass} border flex items-center justify-center text-xs font-bold ${colorClass}">
                            ${name.charAt(0).toUpperCase()}
                        </div>
                        <span class="text-sm text-white/90 font-medium">${name}</span>
                    </div>
                `);
            });
        }

        modal.removeClass('hidden');
        setTimeout(() => {
            backdrop.removeClass('opacity-0');
            content.removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100');
        }, 10);
    }

    function closeInsightModal() {
        const modal = $('#insightModal');
        const backdrop = $('#modalBackdrop');
        const content = $('#modalContent');

        backdrop.addClass('opacity-0');
        content.removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');

        setTimeout(() => { modal.addClass('hidden'); }, 300);
    }

    $('#modalBackdrop').click(closeInsightModal);
    $(document).keydown(e => { if(e.key === "Escape") closeInsightModal(); });
</script>
@endsection