@extends('layouts.landing')

@section('title', 'Admin Control Center · Deep Insight')

@section('content')
<style>
    /* Custom Scrollbar */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: rgba(255,255,255,0.02); }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(34,211,238,0.4); }
    
    /* Glassmorphism Card */
    .glass-card { 
        background: rgba(15, 20, 30, 0.6); 
        backdrop-filter: blur(12px); 
        border: 1px solid rgba(255, 255, 255, 0.08); 
        transition: all 0.3s ease;
    }
    .glass-card:hover { 
        border-color: rgba(255, 255, 255, 0.15); 
        transform: translateY(-2px); 
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5); 
    }

    /* Animation */
    .reveal { opacity: 0; transform: translateY(20px); animation: revealAnim 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
</style>

<div class="relative min-h-screen pt-28 pb-32 bg-[#020617] text-white overflow-x-hidden font-sans selection:bg-cyan-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[800px] h-[800px] bg-cyan-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-indigo-900/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        <div class="flex flex-col md:flex-row justify-between items-end mb-12 gap-6 reveal" style="animation-delay: 0.1s;">
            <div>
                <div class="flex items-center gap-2 mb-2">
                    <!-- <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-cyan-500"></span>
                    </span> -->
                    <!-- <span class="text-cyan-400 text-[10px] font-bold uppercase tracking-widest pl-1">System Online</span> -->
                </div>
                <h1 class="text-4xl md:text-5xl font-black text-white tracking-tight leading-tight">
                    Admin <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-blue-500">Dashboard</span>
                </h1>
                <p class="text-white/50 mt-2 text-lg max-w-2xl">
                    Pusat analisis performa siswa dan manajemen sistem pembelajaran.
                </p>
            </div>
            
            <div class="flex gap-3">
                <a href="{{ route('landing') }}" class="group px-5 py-3 rounded-xl bg-white/5 border border-white/10 hover:bg-white/10 text-sm font-bold transition flex items-center gap-2 backdrop-blur-md">
                    <span class="group-hover:-translate-x-1 transition">Beranda</span> 
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12 reveal" style="animation-delay: 0.2s;">
            
            <div class="glass-card p-6 rounded-3xl relative overflow-hidden group">
                <div class="absolute right-0 top-0 p-4 opacity-5 group-hover:opacity-10 transition transform group-hover:scale-110">
                    <svg class="w-16 h-16 text-cyan-400" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Total Siswa</p>
                <h3 class="text-4xl font-black text-white mt-2">{{ $totalStudents ?? 0 }}</h3>
                <div class="mt-3 flex items-center gap-2 text-xs font-bold text-emerald-400">
                    <span class="px-2 py-0.5 rounded bg-emerald-500/10 border border-emerald-500/20">Active Users</span>
                </div>
            </div>

            <div class="glass-card p-6 rounded-3xl relative overflow-hidden group hover:border-fuchsia-500/30">
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Total Partisipasi Kuis</p>
                <h3 class="text-4xl font-black text-fuchsia-400 mt-2">{{ $totalAttempts ?? 0 }}</h3>
                <p class="text-xs text-white/30 mt-2">Data submission masuk</p>
            </div>

            <div class="glass-card p-6 rounded-3xl relative overflow-hidden group hover:border-emerald-500/30">
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Rata-rata Kelas</p>
                <div class="flex items-baseline gap-1 mt-2">
                    <h3 class="text-4xl font-black text-emerald-400">{{ $globalAverage ?? 0 }}</h3>
                    <span class="text-lg text-white/30 font-bold">/100</span>
                </div>
                <div class="w-full h-1 bg-white/10 rounded-full mt-3 overflow-hidden">
                    <div class="h-full bg-emerald-500" style="width: {{ $globalAverage ?? 0 }}%"></div>
                </div>
            </div>

            <div class="glass-card p-6 rounded-3xl relative overflow-hidden group hover:border-red-500/30">
                <p class="text-xs font-bold text-white/40 uppercase tracking-widest">Perlu Remedial</p>
                <h3 class="text-4xl font-black text-red-400 mt-2">{{ $remedialCount ?? 0 }}</h3>
                <p class="text-xs text-red-400/60 mt-2 font-bold">Nilai di bawah 70</p>
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8 mb-16 reveal" style="animation-delay: 0.3s;">
            <div class="lg:col-span-2 glass-card p-8 rounded-3xl flex flex-col relative overflow-hidden">
                <div class="flex justify-between items-center mb-6 relative z-10">
                    <div>
                        <h3 class="text-lg font-bold text-white">Tren Nilai</h3>
                        <p class="text-xs text-white/40">Performa 7 Hari Terakhir</p>
                    </div>
                    <span class="text-xs bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 px-3 py-1 rounded-full font-bold animate-pulse">Live Data</span>
                </div>
                <div class="flex-1 w-full relative z-10 min-h-[250px]">
                    <canvas id="adminChart"></canvas>
                </div>
                <div class="absolute bottom-0 left-0 right-0 h-40 bg-gradient-to-t from-cyan-900/10 to-transparent pointer-events-none"></div>
            </div>

            <div class="glass-card p-8 rounded-3xl flex flex-col">
                <h3 class="text-lg font-bold text-white mb-6 flex items-center gap-2">
                    <span class="text-yellow-400">🏆</span> Top 5 Siswa
                </h3>
                <div class="space-y-4 flex-1 overflow-y-auto custom-scrollbar pr-2">
                    @forelse($topStudents ?? [] as $index => $s)
                    <a href="{{ route('admin.student.detail', $s->id) }}" class="flex items-center gap-4 p-3 rounded-xl bg-white/5 border border-white/5 hover:bg-white/10 transition group cursor-pointer">
                        <div class="w-10 h-10 rounded-xl flex items-center justify-center font-black text-sm shadow-lg
                            {{ $index == 0 ? 'bg-gradient-to-br from-yellow-400 to-orange-500 text-black' : 
                               ($index == 1 ? 'bg-gradient-to-br from-gray-300 to-gray-400 text-black' : 
                               ($index == 2 ? 'bg-gradient-to-br from-orange-700 to-orange-800 text-white' : 'bg-white/10 text-white')) }}">
                            {{ $index + 1 }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-white truncate group-hover:text-cyan-400 transition">{{ $s->name }}</p>
                            <p class="text-[10px] text-white/40 truncate">{{ $s->email }}</p>
                        </div>
                        <div class="text-right">
                            <span class="block text-lg font-black text-emerald-400">{{ round($s->avg_score) }}</span>
                            <span class="text-[10px] text-white/30 uppercase">Avg</span>
                        </div>
                    </a>
                    @empty
                    <div class="text-center py-10 opacity-30">
                        <div class="text-4xl mb-2">∅</div>
                        <p class="text-xs">Belum ada data nilai.</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        <section class="mb-16 reveal" style="animation-delay: 0.4s;">
            <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
                <div class="flex items-center gap-3">
                    <div class="p-2 bg-fuchsia-500/10 rounded-lg text-fuchsia-400">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                    </div>
                    <div>
                        <h2 class="text-2xl font-bold text-white">Analisis Butir Soal (Top 10)</h2>
                        <p class="text-white/40 text-sm">Evaluasi tingkat kesulitan berdasarkan jawaban siswa.</p>
                    </div>
                </div>
                <a href="{{ route('admin.analytics.questions') }}" class="text-xs font-bold px-4 py-2 rounded-lg bg-white/5 border border-white/10 hover:bg-white/10 hover:text-white text-white/60 transition flex items-center gap-2">
                    Lihat Laporan Lengkap <span>→</span>
                </a>
            </div>

            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-black/20 text-white/40 text-xs uppercase font-bold border-b border-white/5">
                            <tr>
                                <th class="px-6 py-5 w-[40%]">Pertanyaan</th>
                                <th class="px-6 py-5 text-center">Total Responden</th>
                                <th class="px-6 py-5 text-center">Akurasi</th>
                                <th class="px-6 py-5 text-center">Tingkat Kesulitan</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($questionStats ?? [] as $q)
                            <tr class="hover:bg-white/5 transition duration-200 group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="w-fit text-[10px] font-bold text-white/30 bg-white/5 px-2 py-0.5 rounded border border-white/5 uppercase tracking-wide">
                                            Bab {{ $q->chapter_id }}
                                        </span>
                                        <p class="text-white font-medium line-clamp-1 group-hover:text-cyan-400 transition" title="{{ $q->question_text }}">
                                            {{ $q->question_text }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="text-white font-bold">{{ $q->total_answers }}</span> <span class="text-white/30 text-xs">Siswa</span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <span class="font-bold {{ ($q->accuracy ?? 0) >= 70 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $q->accuracy ?? 0 }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(($q->difficulty ?? '') == 'Sulit')
                                        <span class="px-2 py-1 rounded bg-red-500/10 text-red-400 border border-red-500/20 text-[10px] font-bold uppercase">Sulit</span>
                                    @elseif(($q->difficulty ?? '') == 'Sedang')
                                        <span class="px-2 py-1 rounded bg-yellow-500/10 text-yellow-400 border border-yellow-500/20 text-[10px] font-bold uppercase">Sedang</span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 text-[10px] font-bold uppercase">Mudah</span>
                                    @endif
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-3xl mb-2 opacity-30">📊</div>
                                    <span class="text-white/30 text-sm">Belum ada data pengerjaan soal.</span>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <section class="reveal" style="animation-delay: 0.5s;">
            <div class="flex flex-col md:flex-row justify-between items-end mb-6 gap-4">
                <div>
                    <h2 class="text-2xl font-bold text-white">Manajemen Pengguna</h2>
                    <p class="text-white/40 text-sm">Database siswa dan administrator.</p>
                </div>
                <div class="relative group w-full md:w-1/3">
                    <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-500 group-focus-within:text-cyan-400 transition" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input id="searchInput" type="text" placeholder="Cari user... (Ctrl + K)" 
                        class="w-full pl-12 pr-4 py-3 rounded-xl bg-white/5 border border-white/10 focus:border-cyan-500/50 focus:ring-1 focus:ring-cyan-500/50 focus:bg-[#0f141e] outline-none transition text-white placeholder-white/30 backdrop-blur-xl">
                </div>
            </div>

            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl relative">
                <div class="overflow-x-auto max-h-[500px] custom-scrollbar">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#0a0e17] text-white/40 text-xs uppercase font-bold border-b border-white/5 sticky top-0 z-10 backdrop-blur-md">
                            <tr>
                                <th class="px-6 py-4">User Identity</th>
                                <th class="px-6 py-4">Role Access</th>
                                <th class="px-6 py-4 text-right">Aksi</th>
                            </tr>
                        </thead>
                        <tbody id="studentTable" class="divide-y divide-white/5">
                            @foreach($users ?? [] as $user)
                            <tr class="user-row group hover:bg-white/5 transition duration-200"
                                data-id="{{ $user->id }}"
                                data-name="{{ $user->name }}"
                                data-email="{{ $user->email }}"
                                data-role="{{ $user->role ?? 'student' }}">
                                
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-4">
                                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center font-bold text-white shadow-inner border border-white/5">
                                            {{ substr($user->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <a href="{{ route('admin.student.detail', $user->id) }}" class="font-bold text-white group-hover:text-cyan-400 transition hover:underline decoration-cyan-400/50 underline-offset-4">
                                                {{ $user->name }}
                                            </a>
                                            <div class="text-xs text-white/40 font-mono">{{ $user->email }}</div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold border uppercase tracking-wider
                                        {{ ($user->role ?? '') === 'admin' ? 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20' : 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20' }}">
                                        {{ $user->role ?? 'STUDENT' }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <button class="edit-btn p-2 rounded-lg bg-white/5 hover:bg-cyan-500/20 text-white/50 hover:text-cyan-400 transition border border-transparent hover:border-cyan-500/30" title="Edit Data">
                                        ✏️ Edit
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

    </div>

    <div id="userModal" class="fixed inset-0 z-[100] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[#020617]/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>
        <div id="modalCard" class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-3xl p-8 shadow-2xl transform scale-95 opacity-0 transition-all duration-300 ring-1 ring-white/10">
            
            <div class="mb-6">
                <h3 class="text-xl font-bold text-white">Edit Informasi User</h3>
                <p class="text-white/40 text-xs mt-1">Perubahan akan langsung diterapkan pada sistem.</p>
            </div>

            <div class="space-y-4">
                <div>
                    <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-1.5 block">Nama Lengkap</label>
                    <input id="modalName" type="text" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition text-sm">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-1.5 block">Email Address</label>
                    <input id="modalEmail" type="email" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-cyan-500 focus:ring-1 focus:ring-cyan-500 outline-none transition text-sm">
                </div>
                <div>
                    <label class="text-[10px] font-bold text-white/50 uppercase tracking-wider mb-1.5 block">Role Access</label>
                    <select id="modalRole" class="w-full bg-black/20 border border-white/10 rounded-xl px-4 py-3 text-white focus:border-fuchsia-500 focus:ring-1 focus:ring-fuchsia-500 outline-none transition appearance-none cursor-pointer text-sm">
                        <option value="student" class="bg-[#0f141e]">Student</option>
                        <option value="admin" class="bg-[#0f141e]">Admin</option>
                    </select>
                </div>
            </div>

            <div class="mt-8 flex gap-3 pt-6 border-t border-white/5">
                <button id="deleteUser" class="px-4 py-2.5 rounded-xl bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500/20 transition font-bold text-xs flex items-center gap-2">
                    🗑 Hapus User
                </button>
                <div class="flex-1 flex justify-end gap-3">
                    <button onclick="closeModal()" class="px-4 py-2.5 rounded-xl text-white/60 hover:text-white hover:bg-white/5 transition font-bold text-xs">Batal</button>
                    <button id="saveUser" class="px-6 py-2.5 rounded-xl bg-cyan-600 hover:bg-cyan-500 text-white font-bold text-xs shadow-lg shadow-cyan-500/20 transition transform active:scale-95">
                        Simpan Data
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div id="deleteConfirm" class="fixed inset-0 z-[110] hidden flex items-center justify-center">
        <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-md"></div>
        <div id="deleteCard" class="relative w-full max-w-sm bg-[#0f141e] border border-red-500/30 rounded-3xl p-8 text-center shadow-[0_0_50px_rgba(239,68,68,0.1)] scale-95 opacity-0 transition-all duration-300">
            <div class="w-16 h-16 bg-red-500/10 rounded-full flex items-center justify-center mx-auto mb-4 border border-red-500/20 text-red-500">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Hapus Permanen?</h3>
            <p class="text-white/60 text-sm mb-6 leading-relaxed">Data pengguna dan riwayat kuis akan dihapus dari database. Tindakan ini tidak dapat dibatalkan.</p>
            <div class="flex gap-3 justify-center">
                <button id="cancelDelete" class="px-6 py-2.5 rounded-xl bg-white/5 hover:bg-white/10 text-white transition text-sm font-bold">Batal</button>
                <button id="confirmDelete" class="px-6 py-2.5 rounded-xl bg-red-600 hover:bg-red-500 text-white font-bold shadow-lg shadow-red-500/30 transition text-sm">Ya, Hapus User</button>
            </div>
        </div>
    </div>

</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // 1. REVEAL ANIMATION
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => { if (entry.isIntersecting) entry.target.classList.add('show'); });
    }, { threshold: 0.1 });
    document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

    // 2. CHART CONFIG (Fail-Safe)
    const ctx = document.getElementById('adminChart');
    if(ctx) {
        // Gradient Effect
        const gradient = ctx.getContext('2d').createLinearGradient(0, 0, 0, 300);
        gradient.addColorStop(0, 'rgba(34, 211, 238, 0.5)'); // Cyan Color
        gradient.addColorStop(1, 'rgba(34, 211, 238, 0)');

        new Chart(ctx.getContext('2d'), {
            type: 'line',
            data: {
                labels: {!! json_encode($chartLabels ?? []) !!},
                datasets: [{
                    label: 'Rata-rata Nilai',
                    data: {!! json_encode($chartScores ?? []) !!},
                    borderColor: '#22d3ee',
                    backgroundColor: gradient,
                    borderWidth: 3,
                    pointBackgroundColor: '#0f141e',
                    pointBorderColor: '#22d3ee',
                    pointBorderWidth: 2,
                    pointRadius: 6,
                    pointHoverRadius: 8,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: { legend: { display: false } },
                scales: {
                    x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.3)', font: {size: 10} } },
                    y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.3)', stepSize: 20, font: {size: 10} } }
                }
            }
        });
    }

    // 3. SEARCH & MODAL LOGIC
    $('#searchInput').on('keyup', function() {
        const val = $(this).val().toLowerCase();
        $('#studentTable tr').filter(function() { $(this).toggle($(this).text().toLowerCase().indexOf(val) > -1) });
    });

    $(document).keydown(e => {
        if (e.ctrlKey && e.key === 'k') { e.preventDefault(); $('#searchInput').focus(); }
        if (e.key === "Escape") { closeModal(); $('#searchInput').blur(); }
    });

    let selectedId, selectedRow;
    $(document).on('click', '.edit-btn, .user-row', function(e) {
        if($(e.target).closest('button').length && !$(e.target).hasClass('edit-btn')) return; // Prevent click on non-edit areas triggering modal if needed, or remove to allow row click
        
        const tr = $(this).closest('tr');
        selectedId = tr.data('id'); selectedRow = tr;
        $('#modalName').val(tr.data('name'));
        $('#modalEmail').val(tr.data('email'));
        $('#modalRole').val(tr.data('role'));
        
        $('#userModal').removeClass('hidden');
        // Simple entry animation
        setTimeout(() => $('#modalCard').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'), 10);
    });

    function closeModal() {
        $('#modalCard, #deleteCard').removeClass('scale-100 opacity-100').addClass('scale-95 opacity-0');
        setTimeout(() => { $('#userModal, #deleteConfirm').addClass('hidden') }, 300);
    }

    // 4. AJAX ACTIONS
    $('#saveUser').click(function() {
        const btn = $(this);
        const originalText = btn.text();
        btn.text('Menyimpan...').prop('disabled', true);

        $.ajax({
            url: `/admin/users/${selectedId}/update`,
            type: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                name: $('#modalName').val(),
                email: $('#modalEmail').val(),
                role: $('#modalRole').val()
            },
            success: function(res) {
                location.reload(); // Simple reload to refresh data
            },
            error: function(err) {
                alert('Gagal update. Pastikan Controller valid.');
                btn.text(originalText).prop('disabled', false);
            }
        });
    });

    $('#deleteUser').click(function() {
        $('#userModal').addClass('hidden');
        $('#deleteConfirm').removeClass('hidden');
        setTimeout(() => $('#deleteCard').removeClass('scale-95 opacity-0').addClass('scale-100 opacity-100'), 10);
    });

    $('#cancelDelete').click(closeModal);
    
    $('#confirmDelete').click(function() {
        $.ajax({
            url: `/admin/users/${selectedId}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            success: function() { location.reload(); }
        });
    });
</script>
@endsection