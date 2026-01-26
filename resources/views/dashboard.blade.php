@extends('layouts.landing')

@section('title', 'Dashboard Siswa · TailwindLearn')

@section('content')
<div id="appRoot" class="relative h-screen bg-[#020617] text-white font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <div class="absolute top-[-10%] left-[-10%] w-[800px] h-[800px] bg-fuchsia-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex flex-1 overflow-hidden relative">

        <aside class="w-[280px] bg-[#050912]/80 backdrop-blur-xl border-r border-white/5 flex flex-col shrink-0 z-40 hidden lg:flex">
            <div class="p-6">
                

                <p class="text-xs font-bold text-white/30 uppercase tracking-widest mb-4 pl-2">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white font-bold shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all">
                        <span class="text-fuchsia-400 group-hover:scale-110 transition">📊</span>
                        Overview
                    </a>
                    <a href="{{ route('courses.htmldancss') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition">📚</span>
                        Materi Belajar
                    </a>
                </nav>
            </div>
            
            <div class="mt-auto p-6 border-t border-white/5">
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="flex items-center gap-3 w-full px-4 py-3 rounded-lg hover:bg-red-500/10 hover:text-red-400 text-gray-400 transition-all text-sm font-bold group">
                        <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <main class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10">
            
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                <div class="flex flex-col md:flex-row justify-between items-end gap-6">
                    <div>
                        <h1 class="text-4xl font-black text-white mb-2">
                            Progress <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Belajar</span>
                        </h1>
                        <p class="text-white/60 text-lg">Pantau pencapaian materi dan hasil evaluasi kuis Anda.</p>
                    </div>
                    <div class="hidden md:block text-right">
                        <p class="text-xs text-white/30 uppercase tracking-widest mb-1">Tanggal Hari Ini</p>
                        <p class="text-xl font-mono font-bold text-white">{{ date('d M Y') }}</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    
                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-8 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest mb-2">Materi Selesai</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-black text-white">{{ $stats['lessons_completed'] ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-xl">/ {{ $stats['total_lessons'] ?? 0 }}</span>
                            </div>
                            @php $pctLesson = ($stats['total_lessons'] > 0) ? ($stats['lessons_completed'] / $stats['total_lessons']) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden">
                                <div class="h-full bg-fuchsia-500 shadow-[0_0_10px_#d946ef]" style="width: {{ $pctLesson }}%"></div>
                            </div>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-8 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest mb-2">Rata-rata Nilai</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-black {{ ($stats['quiz_average'] ?? 0) >= 70 ? 'text-cyan-400' : 'text-yellow-400' }}">
                                    {{ $stats['quiz_average'] ?? 0 }}
                                </span>
                                <span class="text-white/40 font-bold text-xl">pts</span>
                            </div>
                            <p class="text-[10px] text-white/30 mt-4">Dari {{ $stats['quizzes_completed'] ?? 0 }} kuis yang diselesaikan.</p>
                        </div>
                    </div>

                    <div class="relative overflow-hidden rounded-3xl border border-white/10 bg-[#0f141e] p-8 group hover:-translate-y-1 transition duration-300">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/20 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                        <div class="relative z-10">
                            <p class="text-xs font-bold text-white/40 uppercase tracking-widest mb-2">Bab Lulus (KKM 70)</p>
                            <div class="flex items-baseline gap-1">
                                <span class="text-5xl font-black text-emerald-400">{{ $stats['chapters_passed'] ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-xl">Bab</span>
                            </div>
                            <p class="text-[10px] text-emerald-400/50 mt-4 font-bold">Terus tingkatkan!</p>
                        </div>
                    </div>

                </div>

                <div class="grid lg:grid-cols-3 gap-8">
                    
                    <div class="lg:col-span-2 space-y-8">
                        
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl shadow-lg relative overflow-hidden">
                            <div class="flex items-center justify-between mb-6 relative z-10">
                                <div>
                                    <h3 class="text-lg font-bold text-white">Grafik Perkembangan Nilai</h3>
                                    <p class="text-xs text-white/40">Riwayat nilai evaluasi per bab yang dikerjakan.</p>
                                </div>
                            </div>
                            <div class="relative h-[280px] w-full z-10">
                                <canvas id="quizChart"></canvas>
                            </div>
                            <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-fuchsia-900/10 to-transparent pointer-events-none"></div>
                        </div>

                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">📝</span> Riwayat Pengerjaan Kuis
                                </h3>
                            </div>

                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs text-white/30 uppercase tracking-widest border-b border-white/5">
                                            <th class="pb-3 pl-2">Topik Kuis</th>
                                            <th class="pb-3">Tanggal</th>
                                            <th class="pb-3 text-center">Durasi</th>
                                            <th class="pb-3 text-right pr-2">Nilai Akhir</th>
                                        </tr>
                                    </thead>
                                    <tbody class="text-sm text-white/70">
                                        @forelse($recentAttempts as $attempt)
                                            <tr class="group hover:bg-white/5 transition border-b border-white/5 last:border-0">
                                                <td class="py-4 pl-2 font-medium text-white flex items-center gap-3">
                                                    <div class="w-8 h-8 rounded bg-gradient-to-br from-fuchsia-600 to-purple-700 flex items-center justify-center text-xs font-bold text-white shadow-lg">
                                                        {{ $attempt->chapter_id }}
                                                    </div>
                                                    Evaluasi Bab {{ $attempt->chapter_id }}
                                                </td>
                                                <td class="py-4 text-xs font-mono text-white/50">
                                                    {{ $attempt->created_at->format('d M Y, H:i') }}
                                                </td>
                                                <td class="py-4 text-center text-xs text-white/40 font-mono">
                                                    {{ gmdate("i", $attempt->time_spent_seconds) }}m {{ gmdate("s", $attempt->time_spent_seconds) }}s
                                                </td>
                                                <td class="py-4 text-right pr-2">
                                                    <span class="px-3 py-1 rounded-full text-xs font-bold border 
                                                        {{ $attempt->score >= 70 ? 'text-emerald-400 bg-emerald-500/10 border-emerald-500/20' : 'text-red-400 bg-red-500/10 border-red-500/20' }}">
                                                        {{ $attempt->score }}
                                                    </span>
                                                </td>
                                            </tr>
                                        @empty
                                            <tr>
                                                <td colspan="4" class="py-10 text-center">
                                                    <div class="flex flex-col items-center justify-center opacity-40">
                                                        <span class="text-4xl mb-2">📭</span>
                                                        <p class="text-sm italic">Belum ada data kuis.</p>
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>

                    </div>

                    <div class="lg:col-span-1 space-y-8">
                        
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Konsistensi (90 Hari)</h3>
                            <div id="heatmap" class="flex flex-wrap gap-1.5 content-start min-h-[150px]"></div>
                            <div class="mt-4 flex gap-4 text-[10px] text-white/30 uppercase tracking-wider font-bold">
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-white/5"></div> 0</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-cyan-500/50"></div> 1-2</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-fuchsia-500"></div> 3+</span>
                            </div>
                        </div>

                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl h-[400px] flex flex-col">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Aktivitas Terbaru</h3>
                            <ul id="activityLogList" class="space-y-3 overflow-y-auto custom-scrollbar pr-2 flex-1">
                                <li class="text-center text-white/20 text-xs italic py-10">Memuat log...</li>
                            </ul>
                        </div>
                    </div>

                </div>

                <div class="border-t border-white/5 pt-8 text-center">
                    <p class="text-white/20 text-xs">&copy; {{ date('Y') }} TailwindLearn. Learning Management System.</p>
                </div>

            </div>
        </main>
    </div>
</div>

<style>
    .nav-link.active { color: #e879f9; position: relative; }
    .nav-link.active::after {
        content: ''; position: absolute; left: 0; bottom: -6px; width: 100%; height: 2px;
        background: linear-gradient(to right,#d946ef,#22d3ee); box-shadow: 0 0 12px rgba(217,70,239,.8); border-radius: 2px;
    }
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    #animated-bg { background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.25), transparent 40%), radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.25), transparent 40%), radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.25), transparent 40%); animation:bgMove 20s ease-in-out infinite alternate; }
    @keyframes bgMove{to{transform:scale(1.15)}}

    [data-title]:hover::after { content: attr(data-title); position: absolute; bottom: 120%; left: 50%; transform: translateX(-50%); background: #000; color: #fff; padding: 4px 8px; font-size: 10px; border-radius: 4px; white-space: nowrap; pointer-events: none; z-index: 50; border: 1px solid rgba(255,255,255,0.2); }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // 1. RENDER CHART NILAI (Data dari Controller Index)
        const ctx = document.getElementById('quizChart').getContext('2d');
        const gradient = ctx.createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(232, 121, 249, 0.5)'); // Fuchsia
        gradient.addColorStop(1, 'rgba(232, 121, 249, 0)');

        // Mengambil data JSON dari Controller (Tanpa AJAX)
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
                    pointBorderColor: '#e879f9',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)' } },
                    y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)' } }
                }
            }
        });

        // 2. FETCH AJAX DATA (Untuk Heatmap & Log agar halaman ringan)
        fetchDashboardData();
    });

    async function fetchDashboardData() {
        try {
            const response = await fetch("{{ route('api.dashboard.progress') }}", { headers: { 'Accept': 'application/json' } });
            if (!response.ok) throw new Error('API Error');
            const data = await response.json();
            
            renderHeatmap(data.activity_timeline || []);
            renderActivityLog(data.activity_log || []);
        } catch (error) { console.error(error); }
    }

    function renderHeatmap(timeline) {
        const el = document.getElementById('heatmap');
        el.innerHTML = '';
        const map = {}; timeline.forEach(t => map[t.date] = t.count);
        for(let i=89; i>=0; i--) {
            const d = new Date(); d.setDate(d.getDate()-i);
            const k = d.toISOString().split('T')[0];
            const v = map[k]||0;
            let c = 'bg-white/5';
            if(v>=1) c='bg-cyan-500/50 shadow-[0_0_5px_#22d3ee]';
            if(v>=3) c='bg-fuchsia-500 shadow-[0_0_8px_#d946ef]';
            const div = document.createElement('div');
            div.className = `w-3 h-3 rounded-sm ${c} relative cursor-pointer hover:scale-125 transition`;
            div.setAttribute('data-title', `${k}: ${v}`);
            el.appendChild(div);
        }
    }

    function renderActivityLog(logs) {
        const list = document.getElementById('activityLogList');
        list.innerHTML = '';
        if (logs.length === 0) { list.innerHTML = `<li class="text-white/30 text-center text-sm py-10">Belum ada aktivitas.</li>`; return; }
        
        logs.forEach(item => {
            const icon = item.type === 'Kuis' ? '📝' : '✓';
            const iconBg = item.type === 'Kuis' ? 'bg-fuchsia-500/20 text-fuchsia-400' : 'bg-cyan-500/10 text-cyan-400';
            list.insertAdjacentHTML('beforeend', `
                <li class="group flex items-center gap-4 p-3 rounded-xl bg-white/5 hover:bg-white/10 transition border border-white/5">
                    <div class="w-8 h-8 rounded-full ${iconBg} flex items-center justify-center shrink-0 font-bold text-sm">${icon}</div>
                    <div class="flex-1 min-w-0">
                        <div class="flex justify-between items-center mb-1">
                            <h4 class="text-sm font-bold text-white truncate">${item.activity}</h4>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-[10px] text-white/40">${item.time}</span>
                            <span class="text-[10px] font-bold text-emerald-400">${item.status}</span>
                        </div>
                    </div>
                </li>
            `);
        });
    }
</script>
@endsection