@extends('layouts.landing')

@section('title', 'Detail Siswa · ' . $student->name)

@section('content')
<style>
    /* Premium Glass Effect */
    .glass-card {
        background: rgba(15, 23, 42, 0.6);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.08);
        box-shadow: 0 4px 30px rgba(0, 0, 0, 0.1);
        transition: all 0.3s ease;
    }
    .glass-card:hover {
        border-color: rgba(255, 255, 255, 0.15);
        box-shadow: 0 10px 40px -10px rgba(0,0,0,0.5);
    }
    .reveal { opacity: 0; transform: translateY(20px); animation: fadeInUp 0.8s forwards; }
    @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
</style>

<div class="relative min-h-screen pt-28 pb-32 bg-[#020617] text-white overflow-x-hidden font-sans selection:bg-cyan-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        <div class="absolute top-[-10%] right-[-10%] w-[800px] h-[800px] bg-cyan-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] left-[-10%] w-[600px] h-[600px] bg-indigo-900/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-6xl mx-auto px-6 lg:px-8 relative z-10">

        <div class="mb-10 reveal" style="animation-delay: 0.1s;">
            <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center gap-2 text-xs font-bold text-white/40 hover:text-white mb-6 transition uppercase tracking-widest group">
                <span class="group-hover:-translate-x-1 transition">←</span> Kembali ke Panel Admin
            </a>

            <div class="glass-card rounded-3xl p-8 flex flex-col md:flex-row items-center gap-8 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-full h-1 bg-gradient-to-r from-cyan-500 via-indigo-500 to-fuchsia-500 opacity-70"></div>

                <div class="relative shrink-0">
                    <div class="w-24 h-24 rounded-full bg-gradient-to-br from-gray-700 to-gray-900 flex items-center justify-center text-3xl font-black text-white border-4 border-[#0f141e] shadow-2xl">
                        {{ substr($student->name, 0, 1) }}
                    </div>
                    <div class="absolute bottom-1 right-1 w-6 h-6 bg-emerald-500 border-4 border-[#0f141e] rounded-full" title="Active"></div>
                </div>

                <div class="text-center md:text-left flex-1">
                    <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">{{ $student->name }}</h1>
                    <p class="text-white/50 font-mono text-sm mt-1 mb-4">{{ $student->email }}</p>
                    
                    <div class="flex flex-wrap justify-center md:justify-start gap-3">
                        <span class="px-3 py-1 rounded-full bg-white/5 border border-white/10 text-[10px] font-bold uppercase tracking-wider text-white/70">
                            {{ $student->role ?? 'Student' }}
                        </span>
                        <span class="px-3 py-1 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-[10px] font-bold uppercase tracking-wider text-cyan-400">
                            Terdaftar: {{ $student->created_at->format('d M Y') }}
                        </span>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-10 reveal" style="animation-delay: 0.2s;">
            <div class="glass-card p-5 rounded-2xl text-center hover:bg-white/5 transition">
                <p class="text-[10px] uppercase tracking-widest text-white/40 font-bold mb-1">Total Kuis</p>
                <p class="text-2xl font-black text-white">{{ $stats['total'] ?? 0 }}</p>
            </div>

            <div class="glass-card p-5 rounded-2xl text-center hover:bg-white/5 transition">
                <p class="text-[10px] uppercase tracking-widest text-white/40 font-bold mb-1">Rata-rata</p>
                <p class="text-2xl font-black text-cyan-400">{{ $stats['avg'] ?? 0 }}</p>
            </div>

            <div class="glass-card p-5 rounded-2xl text-center hover:bg-white/5 transition">
                <p class="text-[10px] uppercase tracking-widest text-white/40 font-bold mb-1">Nilai Tertinggi</p>
                <p class="text-2xl font-black text-fuchsia-400">{{ $stats['max'] ?? 0 }}</p>
            </div>

            <div class="glass-card p-5 rounded-2xl text-center hover:bg-white/5 transition">
                <p class="text-[10px] uppercase tracking-widest text-white/40 font-bold mb-1">Terakhir Aktif</p>
                <p class="text-lg font-bold text-emerald-400 mt-1 truncate">
                    {{ $stats['last_active'] ?? '-' }}
                </p>
            </div>
        </div>

        <div class="reveal" style="animation-delay: 0.3s;">
            <div class="flex items-center gap-3 mb-6">
                <div class="p-2 bg-indigo-500/10 rounded-lg text-indigo-400">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
                </div>
                <h2 class="text-2xl font-bold text-white">Riwayat Pengerjaan Kuis</h2>
            </div>

            <div class="glass-card rounded-3xl overflow-hidden shadow-2xl">
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left">
                        <thead class="bg-[#0a0e17] text-white/40 text-xs uppercase font-bold border-b border-white/5">
                            <tr>
                                <th class="px-6 py-5">Topik Kuis</th>
                                <th class="px-6 py-5 text-center">Tanggal</th>
                                <th class="px-6 py-5 text-center">Status</th>
                                <th class="px-6 py-5 text-right">Nilai Akhir</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-white/5">
                            @forelse($attempts ?? [] as $attempt)
                            <tr class="hover:bg-white/5 transition duration-200 group">
                                <td class="px-6 py-4">
                                    <div class="flex flex-col gap-1">
                                        <span class="text-white font-bold group-hover:text-cyan-400 transition">
                                            Evaluasi Bab {{ $attempt->chapter_id }}
                                        </span>
                                        <span class="text-[10px] text-white/30 font-mono">Attempt ID: #{{ $attempt->id }}</span>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-center text-white/60">
                                    {{ \Carbon\Carbon::parse($attempt->created_at)->format('d M Y, H:i') }}
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(($attempt->score ?? 0) >= 70)
                                        <span class="px-2 py-1 rounded bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-[10px] font-bold uppercase tracking-wider">
                                            Lulus
                                        </span>
                                    @else
                                        <span class="px-2 py-1 rounded bg-red-500/10 border border-red-500/20 text-red-400 text-[10px] font-bold uppercase tracking-wider">
                                            Remedial
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <span class="text-lg font-black {{ ($attempt->score ?? 0) >= 70 ? 'text-emerald-400' : 'text-red-400' }}">
                                        {{ $attempt->score ?? 0 }}
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="px-6 py-12 text-center">
                                    <div class="text-4xl mb-3 opacity-20">📂</div>
                                    <p class="text-white/30">Belum ada riwayat kuis yang dikerjakan.</p>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection