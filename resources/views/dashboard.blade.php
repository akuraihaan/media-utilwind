@extends('layouts.landing')

@section('title', 'Dashboard Siswa · TailwindLearn')

@section('content')
{{-- 
    PERBAIKAN:
    - pt-20: Memberi ruang untuk Navbar Fixed (h-20).
    - h-screen: Memastikan footer tidak naik jika konten sedikit.
--}}
<div id="appRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30 pt-20">

    {{-- Background Effects --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')
    
    <div class="flex flex-1 overflow-hidden relative">

        {{-- SIDEBAR MENU DASHBOARD --}}
        {{-- Hapus 'fixed' jika ada, gunakan flex item biasa --}}
        <aside class="w-[280px] bg-[#050912]/80 backdrop-blur-xl border-r border-white/5 flex flex-col shrink-0 z-40 hidden lg:flex">
            <div class="p-6">
                <p class="text-xs font-bold text-white/30 uppercase tracking-widest mb-4 pl-2">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white font-bold shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-[1.02]">
                        <span class="text-fuchsia-400 group-hover:scale-110 transition">📊</span>
                        Overview
                    </a>
                    <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition">📚</span>
                        Materi Belajar
                    </a>
                    {{-- Tambahan menu profile agar konsisten --}}
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition">⚙️</span>
                        Pengaturan
                    </a>
                </nav>
            </div>
            
            {{-- Quote Harian di bawah sidebar --}}
            <div class="mt-auto p-6">
                <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-900/20 to-purple-900/20 border border-white/5 text-center">
                    <p class="text-[10px] text-white/40 italic">"Code is poetry."</p>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10">
            
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                {{-- HEADER PAGE --}}
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl font-black text-white mb-2">
                            Progress <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Belajar</span>
                        </h1>
                        <p class="text-white/60 text-lg">Pantau pencapaian materi, lab hands-on, dan hasil evaluasi.</p>
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-xs text-white/30 uppercase tracking-widest mb-1">Tanggal Hari Ini</p>
                        <p class="text-xl font-mono font-bold text-white">{{ date('d M Y') }}</p>
                    </div>
                </div>

                {{-- ALERT LAB AKTIF (Resume) --}}
                @if(isset($activeSession) && $activeSession)
                <div class="rounded-2xl bg-indigo-900/40 border border-indigo-500/30 p-4 flex items-center justify-between shadow-lg shadow-indigo-900/20 mb-2 animate-pulse-slow">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center animate-pulse shadow-[0_0_15px_#6366f1]">⚡</div>
                        <div>
                            <h3 class="font-bold text-white">Lab Sedang Berjalan: {{ $activeSession->lab->title }}</h3>
                            <p class="text-indigo-200 text-xs">Sesi akan berakhir dalam {{ \Carbon\Carbon::parse($activeSession->expires_at)->diffForHumans() }}.</p>
                        </div>
                    </div>
                    <a href="{{ route('lab.workspace', $activeSession->id) }}" class="px-5 py-2 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-lg text-sm transition shadow-lg hover:shadow-indigo-500/50">
                        Lanjut Coding →
                    </a>
                </div>
                @endif

                {{-- GRID STATISTIK UTAMA --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    {{-- CARD 1: MATERI --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Materi Bacaan</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-lg">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                            @php $pctLesson = ($totalLessons > 0) ? ($lessonsCompleted / $totalLessons) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden">
                                <div class="h-full bg-fuchsia-500 shadow-[0_0_10px_#d946ef] transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Hands-on Labs</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black {{ ($labsCompleted == $totalLabs && $totalLabs > 0) ? 'text-emerald-400' : 'text-blue-400' }}">
                                    {{ $labsCompleted ?? 0 }}
                                </span>
                                <span class="text-white/40 font-bold text-lg">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                            @php $pctLab = ($totalLabs > 0) ? ($labsCompleted / $totalLabs) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden">
                                <div class="h-full bg-blue-500 shadow-[0_0_10px_#3b82f6] transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Rata-rata Kuis</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black {{ ($quizAverage ?? 0) >= 70 ? 'text-cyan-400' : 'text-yellow-400' }}">
                                    {{ round($quizAverage ?? 0) }}
                                </span>
                                <span class="text-white/40 font-bold text-lg">pts</span>
                            </div>
                            <p class="text-[10px] text-white/30 mt-4">Dari {{ $quizzesCompleted ?? 0 }} evaluasi yang selesai.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest mb-2">Bab Lulus</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-emerald-400">{{ $chaptersPassed ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-lg">Bab</span>
                            </div>
                            <p class="text-[10px] text-emerald-400/50 mt-4 font-bold uppercase tracking-wider">Keep Going!</p>
                        </div>
                    </div>

                </div>

                {{-- BAGIAN BAWAH: CHART & LOG --}}
                <div class="grid lg:grid-cols-3 gap-8">
                    
                    {{-- GRAFIK --}}
                    <div class="lg:col-span-2 space-y-8">
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl shadow-lg relative overflow-hidden">
                            <div class="flex items-center justify-between mb-6 relative z-10">
                                <div>
                                    <h3 class="text-lg font-bold text-white">Grafik Perkembangan Nilai</h3>
                                    <p class="text-xs text-white/40">Visualisasi hasil evaluasi kuis Anda.</p>
                                </div>
                            </div>
                            <div class="relative h-[250px] w-full z-10">
                                <canvas id="quizChart"></canvas>
                            </div>
                            {{-- Fade effect bawah chart --}}
                            <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#0f141e] to-transparent pointer-events-none"></div>
                        </div>

                        {{-- TABEL HISTORY (Dipindahkan ke dalam blade) --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">🕒</span> Riwayat Pengerjaan
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs text-white/30 uppercase tracking-widest border-b border-white/5">
                                            <th class="pb-3 pl-2">Aktivitas</th>
                                            <th class="pb-3">Waktu</th>
                                            <th class="pb-3 text-right pr-2">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-white/70">
                                        @forelse($historyCombined as $item)
                                            <tr class="group hover:bg-white/5 transition border-b border-white/5 last:border-0">
                                                <td class="py-4 pl-2 font-medium text-white flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded flex items-center justify-center text-xs font-bold text-white shadow-lg
                                                        {{ $item['type'] == 'lab' ? 'bg-blue-600 shadow-blue-900/20' : 
                                                          ($item['type'] == 'quiz' ? 'bg-fuchsia-600 shadow-fuchsia-900/20' : 'bg-gray-600') }}">
                                                        {{ $item['icon'] }}
                                                    </div>
                                                    <div class="flex flex-col">
                                                        <span>{{ $item['name'] }}</span>
                                                        <span class="text-[10px] text-white/30 uppercase">{{ ucfirst($item['type']) }}</span>
                                                    </div>
                                                </td>
                                                <td class="py-4 text-xs font-mono text-white/50">
                                                    {{ \Carbon\Carbon::parse($item['date'])->diffForHumans() }}
                                                </td>
                                                <td class="py-4 text-right pr-2">
                                                    @if(isset($item['score']))
                                                        <span class="px-3 py-1 rounded-full text-xs font-bold border 
                                                            {{ $item['score'] >= 70 ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-red-400 bg-red-500/10 border-red-500/20' }}">
                                                            {{ $item['score'] }} pts
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="3" class="py-8 text-center text-white/30 italic">Belum ada data aktivitas.</td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    {{-- SIDEBAR KANAN --}}
                    <div class="lg:col-span-1 space-y-8">
                        {{-- Heatmap --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Konsistensi</h3>
                            <div id="heatmap" class="flex flex-wrap gap-1.5 content-start min-h-[150px]"></div>
                            <div class="mt-4 flex gap-4 text-[10px] text-white/30 uppercase tracking-wider font-bold">
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-white/5"></div> 0</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-cyan-500/50"></div> 1</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-fuchsia-500"></div> 3+</span>
                            </div>
                        </div>

                        {{-- Log Real-time --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl h-[400px] flex flex-col">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Live Log</h3>
                            <ul id="activityLogList" class="space-y-3 overflow-y-auto custom-scrollbar pr-2 flex-1">
                                <li class="text-center text-white/20 text-xs italic py-10">Memuat log...</li>
                            </ul>
                        </div>
                    </div>

                </div>

                {{-- Footer Dashboard --}}
                <div class="border-t border-white/5 pt-8 text-center">
                    <p class="text-white/20 text-xs">&copy; {{ date('Y') }} Utilwind CSS</p>
                </div>

            </div>
        </main>
    </div>
</div>

{{-- Styles --}}
<style>
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* Animated BG */
    #animated-bg { background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}

    /* Tooltip Heatmap */
    [data-title]:hover::after { content: attr(data-title); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 4px 8px; font-size: 10px; border-radius: 4px; white-space: nowrap; pointer-events: none; z-index: 50; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 4px 6px rgba(0,0,0,0.3); }

    /* Animation Fade Up */
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
    .animate-fade-in-up { animation: fadeInUp 0.5s ease-out forwards; opacity: 0; }
</style>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. CHART.JS CONFIGURATION
        const ctx = document.getElementById('quizChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(232, 121, 249, 0.5)'); 
        gradient.addColorStop(1, 'rgba(232, 121, 249, 0)');

        const chartLabels = {!! json_encode($chartData['labels'] ?? []) !!};
        const chartScores = {!! json_encode($chartData['scores'] ?? []) !!};

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: chartLabels, 
                datasets: [{
                    label: 'Nilai Evaluasi',
                    data: chartScores,
                    borderColor: '#e879f9', 
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#020617',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)', font: { family: 'monospace' } } },
                    y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)' } }
                }
            }
        });

        // 2. FETCH REAL-TIME DATA
        fetchDashboardData();
    });

    async function fetchDashboardData() {
        try {
            const response = await fetch("{{ route('api.dashboard.progress') }}", { headers: { 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('API Error');
            const data = await response.json();
            
            renderHeatmap(data.activity_timeline || []);
            renderActivityLog(data.activity_log || []);
        } catch (error) { 
            console.error("Sync Error:", error);
            document.getElementById('activityLogList').innerHTML = `<li class="text-center text-red-400/50 text-xs italic py-4">Gagal sinkronisasi data.</li>`;
        }
    }

    function renderHeatmap(timeline) {
        const el = document.getElementById('heatmap');
        el.innerHTML = '';
        const map = {}; timeline.forEach(t => map[t.date] = t.count);
        
        for(let i=83; i>=0; i--) {
            const d = new Date(); d.setDate(d.getDate()-i);
            const k = d.toISOString().split('T')[0];
            const v = map[k]||0;
            
            let c = 'bg-white/5'; 
            if(v>=1) c='bg-cyan-500/40 shadow-[0_0_5px_#22d3ee]'; 
            if(v>=3) c='bg-fuchsia-500 shadow-[0_0_8px_#d946ef]'; 
            
            const div = document.createElement('div');
            div.className = `w-2.5 h-2.5 rounded-[2px] ${c} relative cursor-pointer hover:scale-150 transition hover:z-20 hover:border hover:border-white`;
            div.setAttribute('data-title', `${k}: ${v} Aktivitas`);
            el.appendChild(div);
        }
    }

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList');
        list.innerHTML = '';
        
        if (logs.length === 0) { 
            list.innerHTML = `<li class="text-white/30 text-center text-xs italic py-10">Belum ada aktivitas hari ini.</li>`; 
            return; 
        }
        
        logs.forEach((item, index) => {
            let icon = '✓';
            let iconBg = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            
            if (item.type === 'Kuis') { icon = '📝'; iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20'; }
            if (item.type === 'Lab')  { icon = '💻'; iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20'; }

            const delay = index * 100; // Stagger animation delay

            list.insertAdjacentHTML('beforeend', `
                <li class="group flex items-center gap-3 p-3 rounded-xl bg-white/[0.02] hover:bg-white/[0.05] transition border border-white/5 animate-fade-in-up" style="animation-delay: ${delay}ms">
                    <div class="w-8 h-8 rounded-lg ${iconBg} border flex items-center justify-center shrink-0 font-bold text-xs shadow-inner">
                        ${icon}
                    </div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-0.5">
                            <h4 class="text-xs font-bold text-white truncate w-24" title="${item.activity}">${item.activity}</h4>
                            <span class="text-[9px] font-bold px-1.5 py-0.5 rounded ${item.status === 'Lulus' ? 'bg-emerald-500/10 text-emerald-400' : 'bg-red-500/10 text-red-400'}">
                                ${item.status}
                            </span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-white/30 font-mono">${item.time}</span>
                        </div>
                    </div>
                </li>
            `);
        });
    }
</script>
@endsection