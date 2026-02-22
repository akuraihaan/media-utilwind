@extends('layouts.landing')

@section('title', 'Dashboard Siswa · TailwindLearn')

@section('content')
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
                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition">⚙️</span>
                        Pengaturan
                    </a>
                </nav>
            </div>
            
            {{-- Quote Harian di bawah sidebar --}}
            <div class="mt-auto p-6">
                <div class="p-4 rounded-xl bg-gradient-to-br from-indigo-900/20 to-purple-900/20 border border-white/5 text-center shadow-inner">
                    <p class="text-[10px] text-white/50 italic">"Code is like humor. When you have to explain it, it’s bad."</p>
                </div>
            </div>
        </aside>

        {{-- MAIN CONTENT DENGAN X-DATA MODALS --}}
        <main x-data="{ 
                showJoinModal: false,
                showLessonModal: false,
                showLabModal: false,
                showQuizModal: false,
                showChapterModal: false
              }" 
              @keydown.escape.window="showJoinModal = false; showLessonModal = false; showLabModal = false; showQuizModal = false; showChapterModal = false;" 
              class="flex-1 h-full overflow-y-auto scroll-smooth relative custom-scrollbar p-6 lg:p-10">
            
            <div class="max-w-7xl mx-auto space-y-8 pb-20">
                
                {{-- HEADER PAGE DENGAN FITUR GABUNG KELAS BERSYARAT --}}
                <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
                    <div>
                        <h1 class="text-4xl font-black text-white mb-2 tracking-tight">
                            Progress <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Belajar</span>
                        </h1>
                        <p class="text-white/60 text-lg">Pantau pencapaian materi, lab hands-on, dan hasil evaluasi.</p>
                        
                        {{-- INFO STATUS KELAS SISWA --}}
                        <div class="mt-6 inline-flex items-center gap-4 px-4 py-2.5 rounded-2xl bg-white/[0.02] border border-white/10 shadow-inner">
                            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center font-bold text-white shadow-lg text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div>
                                <p class="text-[10px] text-white/40 uppercase tracking-widest font-bold mb-0.5">Status Kelas</p>
                                <div class="flex items-center gap-2">
                                    <span class="w-2 h-2 rounded-full {{ Auth::user()->class_group ? 'bg-emerald-500 shadow-[0_0_8px_#10b981]' : 'bg-yellow-500 shadow-[0_0_8px_#eab308] animate-pulse' }}"></span>
                                    <span class="text-sm font-bold text-white">{{ Auth::user()->class_group ?? 'Belum Terhubung ke Kelas' }}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex flex-col items-end gap-4 w-full md:w-auto">
                        <div class="hidden md:block text-right">
                            <p class="text-xs text-white/30 uppercase tracking-widest mb-1">Tanggal Hari Ini</p>
                            <p class="text-xl font-mono font-bold text-white">{{ date('d M Y') }}</p>
                        </div>
                        
                        {{-- LOGIKA TOMBOL GABUNG KELAS --}}
                        @empty(Auth::user()->class_group)
                            {{-- Jika Belum Ada Kelas: Munculkan Tombol --}}
                            <button @click="showJoinModal = true" class="w-full md:w-auto flex items-center justify-center gap-2 px-6 py-3 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white text-sm font-bold shadow-[0_0_20px_rgba(99,102,241,0.4)] transition transform hover:-translate-y-0.5 border border-indigo-400 group">
                                <svg class="w-4 h-4 transition-transform group-hover:rotate-12" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                Gabung Kelas
                            </button>
                        @else
                            {{-- Jika Sudah Ada Kelas: Tombol Hilang, Ganti Status Badge --}}
                            <div class="px-5 py-3 rounded-xl bg-emerald-500/10 border border-emerald-500/20 shadow-inner flex items-center gap-3">
                                <span class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></span>
                                <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Tergabung di Kelas</span>
                            </div>
                        @endempty
                    </div>
                </div>

                {{-- ALERT LAB AKTIF (Resume) --}}
                @if(isset($activeSession) && $activeSession)
                <div class="rounded-2xl bg-indigo-900/40 border border-indigo-500/30 p-4 flex items-center justify-between shadow-lg shadow-indigo-900/20 mb-2 animate-pulse-slow">
                    <div class="flex items-center gap-4">
                        <div class="w-10 h-10 rounded-full bg-indigo-500 flex items-center justify-center animate-pulse shadow-[0_0_15px_#6366f1]">⚡</div>
                        <div>
                            <h3 class="font-bold text-white">Lab Sedang Berjalan: {{ $activeSession->lab->title ?? 'Praktikum' }}</h3>
                            <p class="text-indigo-200 text-xs">Aktivitas koding Anda belum diselesaikan.</p>
                        </div>
                    </div>
                    <a href="{{ route('lab.workspace', $activeSession->lab_id) }}" class="px-5 py-2 bg-indigo-500 hover:bg-indigo-400 text-white font-bold rounded-lg text-sm transition shadow-lg hover:shadow-indigo-500/50">
                        Lanjut Coding →
                    </a>
                </div>
                @endif

                {{-- =========================================================
                     GRID STATISTIK UTAMA (DENGAN TOOLTIP & HERO MODALS)
                     ========================================================= --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
                    
                    {{-- CARD 1: MATERI --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-fuchsia-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showLessonModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-fuchsia-400 transition">Materi Bacaan</p>
                                <div class="tooltip-container tooltip-fuchsia tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-fuchsia-400">?</div>
                                    <div class="tooltip-content">
                                        <span class="block font-bold text-fuchsia-400 mb-1 border-b border-white/10 pb-1">Materi Diselesaikan</span>
                                        Jumlah materi teori yang telah Anda baca dari total keseluruhan materi. Klik untuk detail.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-fuchsia-400 transition drop-shadow-[0_0_8px_rgba(217,70,239,0.3)]">{{ $lessonsCompleted ?? 0 }}</span>
                                <span class="text-white/40 font-bold text-lg">/ {{ $totalLessons ?? 0 }}</span>
                            </div>
                            @php $pctLesson = ($totalLessons > 0) ? ($lessonsCompleted / $totalLessons) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-fuchsia-500 shadow-[0_0_10px_#d946ef] transition-all duration-1000" style="width: {{ $pctLesson }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 2: HANDS-ON LABS --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-blue-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showLabModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-blue-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-blue-400 transition">Hands-on Labs</p>
                                <div class="tooltip-container tooltip-blue tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-blue-400">?</div>
                                    <div class="tooltip-content border-blue-glow" style="border-color: rgba(59, 130, 246, 0.5);">
                                        <span class="block font-bold text-blue-400 mb-1 border-b border-white/10 pb-1">Lab Lulus</span>
                                        Jumlah modul coding/praktikum yang berhasil Anda selesaikan dengan nilai minimal 70. Klik untuk insight.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-blue-400 transition drop-shadow-[0_0_8px_rgba(59,130,246,0.3)]">
                                    {{ $labsCompleted ?? 0 }}
                                </span>
                                <span class="text-white/40 font-bold text-lg">/ {{ $totalLabs ?? 0 }}</span>
                            </div>
                            @php $pctLab = ($totalLabs > 0) ? ($labsCompleted / $totalLabs) * 100 : 0; @endphp
                            <div class="w-full h-1.5 bg-white/5 rounded-full mt-4 overflow-hidden border border-white/5">
                                <div class="h-full bg-blue-500 shadow-[0_0_10px_#3b82f6] transition-all duration-1000" style="width: {{ $pctLab }}%"></div>
                            </div>
                        </div>
                    </div>

                    {{-- CARD 3: RATA-RATA KUIS --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-cyan-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showQuizModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-cyan-400 transition">Rata-rata Kuis</p>
                                <div class="tooltip-container tooltip-cyan tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-cyan-400">?</div>
                                    <div class="tooltip-content border-cyan-glow">
                                        <span class="block font-bold text-cyan-400 mb-1 border-b border-white/10 pb-1">Skor Keseluruhan</span>
                                        Nilai rata-rata yang Anda kumpulkan dari seluruh evaluasi teori yang pernah dicoba. Klik untuk breakdown.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-cyan-400 transition drop-shadow-[0_0_8px_rgba(34,211,238,0.3)]">
                                    {{ round($quizAverage ?? 0, 1) }}
                                </span>
                                <span class="text-white/40 font-bold text-lg">pts</span>
                            </div>
                            <p class="text-[10px] text-white/30 mt-4 font-mono">Dari {{ $quizzesCompleted ?? 0 }} evaluasi selesai.</p>
                        </div>
                    </div>

                    {{-- CARD 4: BAB LULUS --}}
                    <div class="relative overflow-visible rounded-3xl border border-white/10 bg-[#0f141e] p-6 group hover:-translate-y-1 hover:border-emerald-500/40 transition duration-300 cursor-pointer shadow-lg" @click="showChapterModal = true">
                        <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl pointer-events-none"></div>
                        <div class="relative z-10">
                            <div class="flex justify-between items-start mb-2">
                                <p class="text-[10px] font-bold text-white/40 uppercase tracking-widest group-hover:text-emerald-400 transition">Bab Lulus</p>
                                <div class="tooltip-container tooltip-emerald tooltip-down tooltip-left">
                                    <div class="tooltip-trigger bg-transparent border-transparent shadow-none text-white/30 group-hover:text-emerald-400">?</div>
                                    <div class="tooltip-content border-emerald-glow">
                                        <span class="block font-bold text-emerald-400 mb-1 border-b border-white/10 pb-1">Kelulusan Bab</span>
                                        Jumlah Bab Teori yang berhasil Anda selesaikan dengan nilai kuis memuaskan. Klik untuk info.
                                    </div>
                                </div>
                            </div>
                            <div class="flex items-baseline gap-1">
                                <span class="text-4xl font-black text-white group-hover:text-emerald-400 transition drop-shadow-[0_0_8px_rgba(16,185,129,0.3)]">{{ $chaptersPassed ?? 0 }}</span>
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
                                    <p class="text-xs text-white/40">Visualisasi hasil evaluasi kuis terbaik Anda per bab.</p>
                                </div>
                            </div>
                            <div class="relative h-[250px] w-full z-10">
                                @if(isset($chartData['scores']) && count($chartData['scores']) > 0)
                                    <canvas id="quizChart"></canvas>
                                @else
                                    <div class="absolute inset-0 flex flex-col items-center justify-center border-2 border-dashed border-white/5 rounded-xl bg-white/[0.01]">
                                        <p class="text-xs font-semibold text-slate-400">Belum Ada Data Kuis</p>
                                        <p class="text-[10px] text-slate-500 mt-1">Selesaikan kuis untuk melihat perkembangan nilai Anda.</p>
                                    </div>
                                @endif
                            </div>
                            {{-- Fade effect bawah chart --}}
                            <div class="absolute bottom-0 left-0 right-0 h-20 bg-gradient-to-t from-[#0f141e] to-transparent pointer-events-none"></div>
                        </div>

                        {{-- TABEL HISTORY --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl">
                            <div class="flex items-center justify-between mb-6">
                                <h3 class="text-lg font-bold text-white flex items-center gap-2">
                                    <span class="text-xl">🕒</span> Riwayat Pengerjaan Terakhir
                                </h3>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="w-full text-left border-collapse">
                                    <thead>
                                        <tr class="text-xs text-white/30 uppercase tracking-widest border-b border-white/5">
                                            <th class="pb-3 pl-2">Aktivitas</th>
                                            <th class="pb-3">Waktu</th>
                                            <th class="pb-3 text-right pr-2">Skor/Status</th>
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
                                                <td colspan="3" class="py-8 text-center text-white/30 italic">Belum ada data aktivitas. Mulai belajar sekarang!</td>
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
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4">Konsistensi Belajar</h3>
                            <div id="heatmap" class="flex flex-wrap gap-1.5 content-start min-h-[150px]"></div>
                            <div class="mt-4 flex gap-4 text-[10px] text-white/30 uppercase tracking-wider font-bold">
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-white/5"></div> 0</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-cyan-500/50"></div> 1-2</span>
                                <span class="flex items-center gap-1.5"><div class="w-2.5 h-2.5 rounded-sm bg-fuchsia-500"></div> 3+</span>
                            </div>
                        </div>

                        {{-- Log Real-time --}}
                        <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 backdrop-blur-xl h-[400px] flex flex-col relative overflow-hidden">
                            <h3 class="text-sm font-bold text-white/70 uppercase tracking-wider mb-4 relative z-10">Live Log</h3>
                            <ul id="activityLogList" class="space-y-3 overflow-y-auto custom-scrollbar pr-2 flex-1 relative z-10">
                                <li class="text-center text-white/20 text-xs italic py-10">Memuat log aktivitas...</li>
                            </ul>
                            <div class="absolute bottom-0 left-0 right-0 h-10 bg-gradient-to-t from-[#0f141e] to-transparent pointer-events-none z-20"></div>
                        </div>
                    </div>

                </div>

                {{-- Footer Dashboard --}}
                <div class="border-t border-white/5 pt-8 mt-10 text-center">
                    <p class="text-white/20 text-xs">&copy; {{ date('Y') }} Utilwind CSS E-Learning</p>
                </div>

            </div>

            {{-- =========================================================================
                 HERO MODALS (INSIGHT DATA UNTUK 4 KARTU STATISTIK)
                 ========================================================================= --}}
            
            {{-- 1. Modal Materi --}}
            <div x-show="showLessonModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showLessonModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-fuchsia-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(217,70,239,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-3 rounded-2xl bg-fuchsia-500/20 text-fuchsia-400 border border-fuchsia-500/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        </div>
                        <button @click="showLessonModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Progres Teori</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Statistik ini menghitung jumlah slide/halaman materi teori yang telah Anda baca secara utuh.</p>
                    
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-5xl font-black text-fuchsia-400">{{ $lessonsCompleted ?? 0 }}</span>
                        <span class="text-xl text-white/30 font-bold">/ {{ $totalLessons ?? 0 }}</span>
                        <p class="text-[10px] text-fuchsia-400/50 uppercase tracking-widest font-bold mt-2">Materi Diselesaikan ({{ $pctLesson ?? 0 }}%)</p>
                    </div>
                </div>
            </div>

            {{-- 2. Modal Lab --}}
            <div x-show="showLabModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showLabModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-blue-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(59,130,246,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-3 rounded-2xl bg-blue-500/20 text-blue-400 border border-blue-500/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        </div>
                        <button @click="showLabModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Hands-on Labs</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Sebuah lab dinyatakan <span class="text-emerald-400 font-bold">Lulus (Passed)</span> jika Anda mendapatkan skor <b>70 ke atas</b> saat validasi kode.</p>
                    
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-5xl font-black text-blue-400">{{ $labsCompleted ?? 0 }}</span>
                        <span class="text-xl text-white/30 font-bold">/ {{ $totalLabs ?? 0 }}</span>
                        <p class="text-[10px] text-blue-400/50 uppercase tracking-widest font-bold mt-2">Modul Praktikum Lulus</p>
                    </div>
                </div>
            </div>

            {{-- 3. Modal Rata-Rata Kuis --}}
            <div x-show="showQuizModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showQuizModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-cyan-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(34,211,238,0.15)]" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-3 rounded-2xl bg-cyan-500/20 text-cyan-400 border border-cyan-500/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
                        </div>
                        <button @click="showQuizModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Rata-rata Kuis</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Kalkulasi nilai ini diambil dari nilai rata-rata seluruh pengerjaan evaluasi teori (kuis) Anda.</p>
                    
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-6xl font-black text-cyan-400">{{ round($quizAverage ?? 0, 1) }}</span>
                        <p class="text-[10px] text-cyan-400/50 uppercase tracking-widest font-bold mt-3">Rata-rata Poin (Pts)</p>
                    </div>
                </div>
            </div>

            {{-- 4. Modal Bab Lulus --}}
            <div x-show="showChapterModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showChapterModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-emerald-500/40 rounded-3xl p-8 shadow-[0_20px_70px_rgba(16,185,129,0.15)]" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    <div class="flex justify-between items-start mb-6">
                        <div class="p-3 rounded-2xl bg-emerald-500/20 text-emerald-400 border border-emerald-500/30">
                            <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                        </div>
                        <button @click="showChapterModal = false" class="text-slate-500 hover:text-white transition p-2 rounded-lg bg-white/5 hover:bg-red-500/20"><svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    <h3 class="text-2xl font-black text-white mb-2">Bab Lulus</h3>
                    <p class="text-slate-400 text-sm leading-relaxed mb-6">Satu bab teori dinyatakan terlewati apabila nilai akhir evaluasi Anda mencapai ambang batas <b>>= 70</b>.</p>
                    
                    <div class="bg-[#0a0e17] rounded-2xl p-6 border border-white/5 shadow-inner text-center">
                        <span class="text-6xl font-black text-emerald-400">{{ $chaptersPassed ?? 0 }}</span>
                        <p class="text-[10px] text-emerald-400/50 uppercase tracking-widest font-bold mt-3">Total Bab Terselesaikan</p>
                    </div>
                </div>
            </div>

            {{-- =========================================================================
                 MODAL GABUNG KELAS (GOOGLE CLASSROOM STYLE)
                 Hanya dimunculkan jika tombol dipicu (yang mana tombolnya hilang kalau sudah join)
                 ========================================================================= --}}
            @empty(Auth::user()->class_group)
            <div x-show="showJoinModal" class="fixed inset-0 z-[99999] flex items-center justify-center p-4" x-cloak>
                <div class="absolute inset-0 bg-[#020617]/90 backdrop-blur-sm" @click="showJoinModal = false"></div>
                <div class="relative w-full max-w-md bg-[#0f141e] border border-white/10 rounded-3xl p-6 md:p-8 shadow-2xl" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 scale-95" x-transition:enter-end="opacity-100 scale-100">
                    
                    <div class="flex justify-between items-center mb-2">
                        <h3 class="text-xl font-bold text-white flex items-center gap-3">
                            <div class="p-2.5 rounded-xl bg-indigo-500/20 text-indigo-400 border border-indigo-500/30">
                                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                            </div>
                            Gabung ke Kelas
                        </h3>
                        <button @click="showJoinModal = false" class="text-slate-500 hover:text-white transition p-1.5 rounded-lg bg-white/5 hover:bg-white/10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg></button>
                    </div>
                    
                    <p class="text-xs text-slate-400 mb-6 mt-2">Mintalah kode token akses (6 karakter) kepada instruktur Anda, lalu masukkan di bawah ini.</p>

                    <form action="{{ route('student.join_class') }}" method="POST" class="space-y-6" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                        @csrf
                        <div>
                            <label class="text-[10px] font-black text-slate-400 uppercase tracking-widest block mb-2">Token Kelas <span class="text-red-500">*</span></label>
                            <div class="relative group">
                                <input type="text" name="token" required maxlength="6" style="text-transform: uppercase;" placeholder="Contoh: A7X9YM" class="w-full bg-[#0a0e17] border border-white/10 rounded-xl px-4 py-4 text-xl font-mono tracking-[0.3em] font-bold text-white focus:ring-2 ring-indigo-500/40 focus:border-indigo-500 outline-none transition-all placeholder:text-slate-700 placeholder:tracking-normal placeholder:font-sans placeholder:font-normal shadow-inner text-center">
                            </div>
                        </div>

                        {{-- Kartu Info Akun --}}
                        <div class="bg-white/[0.02] border border-white/5 rounded-xl p-4 flex items-center gap-4 shadow-inner">
                            <div class="w-10 h-10 rounded-full bg-gradient-to-br from-indigo-500 to-fuchsia-500 flex items-center justify-center text-white font-bold shadow-lg text-lg">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-[9px] text-slate-500 font-bold uppercase tracking-widest mb-0.5">Masuk Sebagai:</p>
                                <p class="text-sm font-bold text-white truncate">{{ Auth::user()->name }}</p>
                                <p class="text-[10px] text-slate-400 font-mono truncate">{{ Auth::user()->email }}</p>
                            </div>
                        </div>

                        <div class="flex justify-end gap-3 pt-4 border-t border-white/5 mt-6">
                            <button type="button" @click="showJoinModal = false" class="px-5 py-2.5 rounded-xl text-slate-400 hover:text-white font-bold text-xs transition border border-transparent hover:border-white/10">Batal</button>
                            <button type="submit" class="px-6 py-2.5 rounded-xl bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs shadow-[0_0_15px_rgba(99,102,241,0.4)] transition flex items-center gap-2 border border-indigo-400" :disabled="isSubmitting" :class="isSubmitting ? 'opacity-70 cursor-wait' : ''">
                                <svg x-show="isSubmitting" class="animate-spin h-3.5 w-3.5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" x-cloak><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <span x-text="isSubmitting ? 'Memverifikasi...' : 'Gabung Kelas'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endempty

        </main>
    </div>
</div>

{{-- Script SweetAlert untuk Menangkap Response dari Controller --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@if(session('success'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'success', title: "{{ session('success') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#10b981' });
    });
</script>
@endif
@if(session('error'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'error', title: "{{ session('error') }}", showConfirmButton: false, timer: 4000, background: '#0f141e', color: '#fff', iconColor: '#ef4444' });
    });
</script>
@endif
@if(session('info'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        Swal.fire({ toast: true, position: 'top-end', icon: 'info', title: "{{ session('info') }}", showConfirmButton: false, timer: 3500, background: '#0f141e', color: '#fff', iconColor: '#3b82f6' });
    });
</script>
@endif

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
    
    [x-cloak] { display: none !important; }

    /* SISTEM TOOLTIP SUPER SOLID */
    .tooltip-container { position: relative; display: inline-flex; align-items: center; justify-content: center; z-index: 50; }
    .tooltip-container:hover { z-index: 99999; }
    .tooltip-trigger { 
        width: 18px; height: 18px; border-radius: 50%; color: white; 
        font-size: 11px; font-weight: 900; display: flex; align-items: center; justify-content: center; 
        cursor: help; transition: all 0.2s; border: 1px solid rgba(255,255,255,0.2);
    }
    .tooltip-trigger:hover { transform: scale(1.15); }
    .tooltip-content { 
        opacity: 0; visibility: hidden; position: absolute; pointer-events: none; 
        width: max-content; min-width: 200px; max-width: 280px; white-space: normal; text-align: left; 
        background-color: #020617; 
        color: #e2e8f0; font-size: 11px; padding: 14px 16px; line-height: 1.5;
        border-radius: 12px; box-shadow: 0 20px 60px rgba(0,0,0,1); z-index: 99999;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); 
    }

    .tooltip-down .tooltip-content { top: calc(100% + 12px); left: 50%; transform: translateX(-50%) translateY(-10px); }
    .tooltip-down:hover .tooltip-content { transform: translateX(-50%) translateY(0); opacity: 1; visibility: visible; }
    .tooltip-down .tooltip-content::after { content: ''; position: absolute; bottom: 100%; left: 50%; margin-left: -6px; border-width: 6px; border-style: solid; border-color: transparent transparent #020617 transparent; }
    
    .tooltip-left .tooltip-content { left: auto; right: -12px; transform: translateX(0) translateY(-10px); }
    .tooltip-down.tooltip-left:hover .tooltip-content { transform: translateX(0) translateY(0); }
    .tooltip-left .tooltip-content::after { left: auto; right: 15px; margin-left: 0; }

    .tooltip-blue .tooltip-trigger { background-color: #3b82f6; box-shadow: 0 0 10px rgba(59,130,246,0.5); }
    .tooltip-blue .tooltip-trigger:hover { background-color: #60a5fa; box-shadow: 0 0 15px rgba(59,130,246,0.8); }
    
    .tooltip-fuchsia .tooltip-trigger { background-color: #d946ef; box-shadow: 0 0 10px rgba(217,70,239,0.5); }
    .tooltip-fuchsia .tooltip-trigger:hover { background-color: #e879f9; box-shadow: 0 0 15px rgba(217,70,239,0.8); }
    .tooltip-fuchsia .tooltip-content { border: 1px solid rgba(217,70,239,0.5); }

    .tooltip-cyan .tooltip-trigger { background-color: #06b6d4; box-shadow: 0 0 10px rgba(6,182,212,0.5); }
    .tooltip-cyan .tooltip-trigger:hover { background-color: #22d3ee; box-shadow: 0 0 15px rgba(6,182,212,0.8); }
    .tooltip-cyan .tooltip-content { border: 1px solid rgba(6,182,212,0.5); }

    .tooltip-emerald .tooltip-trigger { background-color: #10b981; box-shadow: 0 0 10px rgba(16,185,129,0.5); }
    .tooltip-emerald .tooltip-trigger:hover { background-color: #34d399; box-shadow: 0 0 15px rgba(16,185,129,0.8); }
    .tooltip-emerald .tooltip-content { border: 1px solid rgba(16,185,129,0.5); }
</style>

{{-- Scripts --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        
        // 1. CHART.JS CONFIGURATION
        const ctx = document.getElementById('quizChart').getContext('2d');
        if(ctx && {!! json_encode($chartData['scores'] ?? []) !!}.length > 0) {
            const gradient = ctx.createLinearGradient(0, 0, 0, 300);
            gradient.addColorStop(0, 'rgba(232, 121, 249, 0.5)'); 
            gradient.addColorStop(1, 'rgba(232, 121, 249, 0)');

            new Chart(ctx, {
                type: 'line',
                data: {
                    labels: {!! json_encode($chartData['labels'] ?? []) !!}, 
                    datasets: [{
                        label: 'Nilai Evaluasi Terakhir',
                        data: {!! json_encode($chartData['scores'] ?? []) !!},
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
                    plugins: { 
                        legend: { display: false },
                        tooltip: {
                            backgroundColor: 'rgba(15, 20, 30, 0.9)',
                            titleFont: { family: 'Inter', size: 13, weight: 'bold' },
                            bodyFont: { family: 'Inter', size: 12 },
                            padding: 12,
                            borderColor: 'rgba(255,255,255,0.1)',
                            borderWidth: 1,
                            displayColors: false,
                        }
                    },
                    scales: {
                        x: { grid: { display: false }, ticks: { color: 'rgba(255,255,255,0.4)', font: { family: 'monospace' } } },
                        y: { beginAtZero: true, max: 100, grid: { color: 'rgba(255,255,255,0.05)' }, ticks: { color: 'rgba(255,255,255,0.4)' } }
                    }
                }
            });
        }

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
            document.getElementById('activityLogList').innerHTML = `<li class="text-center text-red-400/50 text-xs italic py-4">Gagal sinkronisasi data log harian.</li>`;
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
            list.innerHTML = `<li class="text-white/30 text-center text-xs italic py-10">Belum ada aktivitas hari ini. Mulai belajar sekarang!</li>`; 
            return; 
        }
        
        logs.forEach((item, index) => {
            let icon = '✓';
            let iconBg = 'bg-cyan-500/10 text-cyan-400 border-cyan-500/20';
            
            if (item.type === 'Kuis') { icon = '📝'; iconBg = 'bg-fuchsia-500/10 text-fuchsia-400 border-fuchsia-500/20'; }
            if (item.type === 'Lab')  { icon = '💻'; iconBg = 'bg-blue-500/10 text-blue-400 border-blue-500/20'; }

            const delay = index * 100;

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