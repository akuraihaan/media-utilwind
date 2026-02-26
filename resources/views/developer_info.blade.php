@extends('layouts.landing')

@section('title', 'Informasi')

@section('content')
<div id="appRoot" class="relative h-screen bg-[#020617] text-slate-300 font-sans overflow-hidden flex flex-col selection:bg-fuchsia-500/30 selection:text-white pt-20">

    {{-- ==================== BACKGROUND FX (ULTIMATE) ==================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iNDAiIGhlaWdodD0iNDAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGcgc3Ryb2tlPSJyZ2JhKDI1NSwgMjU1LCAyNTUsIDAuMDMpIiBmaWxsPSJub25lIiBmaWxsLXJ1bGU9ImV2ZW5vZGQiPjxwYXRoIGQ9Ik0wIDQwaDQwVjBIMHoiLz48L2c+PC9zdmc+')] opacity-50"></div>
        
        <div class="absolute top-[5%] left-[10%] w-[600px] h-[600px] bg-cyan-600/10 rounded-full blur-[120px] mix-blend-screen floating"></div>
        <div class="absolute bottom-[5%] right-[10%] w-[500px] h-[500px] bg-indigo-600/10 rounded-full blur-[120px] mix-blend-screen floating" style="animation-delay: -3s;"></div>
        
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')
    
    <div class="flex flex-1 overflow-hidden relative" x-data="{ sidebarOpen: false }">

        {{-- ==================== SIDEBAR (KONSISTEN DENGAN DASHBOARD) ==================== --}}
        
        {{-- Overlay Mobile --}}
        <div x-show="sidebarOpen" class="fixed inset-0 bg-[#020617]/80 backdrop-blur-sm z-[90] lg:hidden" @click="sidebarOpen = false" x-transition.opacity style="display: none;" x-cloak></div>
        
        <aside class="w-[280px] bg-[#050912]/80 backdrop-blur-xl border-r border-white/5 flex-col shrink-0 z-[100] fixed lg:relative h-full transition-transform duration-300 transform lg:translate-x-0 flex" :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">
            <div class="p-6 overflow-y-auto custom-scrollbar flex-1">
                <p class="text-xs font-bold text-white/30 uppercase tracking-widest mb-4 pl-2">Menu Utama</p>
                <nav class="space-y-2">
                    <a href="{{ route('dashboard') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">📊</span>
                        Overview
                    </a>
                    
                    {{-- Logic Kelas: Materi Belajar --}}
                    @php
                        $isUnlocked = Auth::user() && (Auth::user()->role === 'admin' || !empty(Auth::user()->class_group));
                    @endphp
                    @if($isUnlocked)
                        <a href="{{ route('courses.curriculum') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                            <span class="grayscale group-hover:grayscale-0 transition text-lg">📚</span>
                            Materi Belajar
                        </a>
                    @else
                        <button class="w-full text-left group flex items-center justify-between px-4 py-3 rounded-xl bg-red-500/5 text-red-400/80 cursor-not-allowed border border-transparent">
                            <div class="flex items-center gap-3"><span class="grayscale opacity-50 text-lg">📚</span> <span class="font-medium">Materi Belajar</span></div>
                            <svg class="w-4 h-4 text-red-500/60" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                        </button>
                    @endif

                    <a href="{{ route('profile.edit') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-white/5 text-white/60 hover:text-white transition border border-transparent hover:border-white/5">
                        <span class="grayscale group-hover:grayscale-0 transition text-lg">⚙️</span>
                        Pengaturan
                    </a>
                    
                    {{-- ACTIVE MENU: Info Pengembang --}}
                    <a href="{{ route('developer.info') }}" class="group flex items-center gap-3 px-4 py-3 rounded-xl bg-white/10 border border-white/10 text-white font-bold shadow-[0_0_15px_rgba(255,255,255,0.05)] transition-all hover:scale-[1.02]">
                        <span class="text-fuchsia-400 group-hover:scale-110 transition text-lg drop-shadow-[0_0_8px_rgba(217,70,239,0.5)]">👨‍💻</span>
                        Informasi
                    </a>
                </nav>
            </div>
            
           
        </aside>

        {{-- ==================== MAIN CONTENT ==================== --}}
        <main class="flex-1 flex flex-col relative z-10 h-full overflow-y-auto overflow-x-hidden perspective-container custom-scrollbar pb-20">
            
            {{-- HEADER CONTENT --}}
            <header class="flex flex-col justify-center px-6 md:px-10 py-6 shrink-0 sticky top-0 z-40 bg-[#020617]/80 backdrop-blur-xl border-b border-white/5">
                <div class="flex items-center justify-between w-full max-w-7xl mx-auto">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="lg:hidden p-2 bg-white/5 rounded-lg text-white hover:bg-white/10 transition">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                        </button>
                        <div>
                            <h2 class="text-white font-bold text-2xl md:text-3xl tracking-tight">Project & Developer Hub</h2>
                            <p class="text-sm text-white/60 mt-1">Informasi penelitian dan penyusun media.</p>
                        </div>
                    </div>
                </div>
            </header>

            {{-- BENTO GRID SHOWCASE (PROFIL) --}}
            <div class="flex-1 p-5 md:p-8 lg:p-10 flex items-center justify-center">
                <div class="w-full max-w-6xl grid grid-cols-1 xl:grid-cols-3 gap-6 md:gap-8 reveal">
                    
                    {{-- KIRI: DIGITAL ID CARD (3D HOVER EFFECT) --}}
                    <div class="xl:col-span-1 tilt-element glass-card rounded-[2rem] p-6 md:p-8 relative overflow-hidden flex flex-col items-center text-center group border-t-2 border-t-cyan-500/50 shadow-2xl shadow-cyan-900/20">
                        {{-- BG Card --}}
                        <div class="absolute inset-0 bg-gradient-to-b from-cyan-500/5 to-transparent pointer-events-none"></div>
                        <div class="absolute -top-20 -right-20 w-48 h-48 bg-cyan-500/20 rounded-full blur-[60px] group-hover:bg-cyan-500/30 transition duration-700"></div>
                        
                        {{-- Avatar --}}
                        <div class="relative w-32 h-32 md:w-48 md:h-48 mb-8 inner-3d mt-4">
                            <div class="absolute inset-0 rounded-full bg-gradient-to-tr from-cyan-400 to-indigo-500 animate-spin-slow blur-md opacity-50 group-hover:opacity-100 transition duration-500"></div>
                            <div class="absolute inset-1 bg-[#020617] rounded-full z-10"></div>
                            
                            {{-- Path Gambar Sesuai Request --}}
                            <img src="{{ asset('images/Raihan.jpg') }}" alt="Taufik Raihandani" onerror="this.src='https://ui-avatars.com/api/?name=Taufik+Raihandani&background=06b6d4&color=fff&size=200'" 
                                 class="absolute inset-2 w-[calc(100%-16px)] h-[calc(100%-16px)] object-cover rounded-full z-20 border-2 border-[#020617]">
                            
                            {{-- Verified Badge --}}
                            <div class="absolute bottom-3 right-3 w-10 h-10 bg-[#020617] rounded-full z-30 flex items-center justify-center">
                                <div class="w-8 h-8 bg-cyan-500 rounded-full flex items-center justify-center text-white shadow-[0_0_15px_#06b6d4]">
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </div>
                            </div>
                        </div>

                        {{-- Data Diri --}}
                        <div class="inner-3d w-full mb-8">
                            <h3 class="text-2xl md:text-3xl font-black text-white tracking-tight mb-2">Taufik Raihandani</h3>
                            <div class="inline-block px-4 py-1.5 rounded-lg bg-[#020617] border border-white/10 text-cyan-400 font-mono text-sm font-bold shadow-inner mb-6">
                                2210131210018
                            </div>
                            
                            <div class="space-y-3 w-full text-left bg-white/[0.02] p-5 rounded-2xl border border-white/5">
                                <div class="flex items-start gap-3">
                                    <span class="text-xl mt-0.5">🎓</span>
                                    <div>
                                        <p class="text-[10px] text-white/40 uppercase font-bold tracking-widest">Program Studi</p>
                                        <p class="text-sm font-medium text-white/90 mt-0.5">Pendidikan Komputer</p>
                                    </div>
                                </div>
                                <div class="flex items-start gap-3">
                                    <span class="text-xl mt-0.5">📍</span>
                                    <div>
                                        <p class="text-[10px] text-white/40 uppercase font-bold tracking-widest">Domisili</p>
                                        <p class="text-sm font-medium text-white/90 mt-0.5">HKSN Permai, Banjarmasin</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Contact Link --}}
                        <a href="mailto:2210131210018@mhs.ulm.ac.id" class="mt-auto w-full inner-3d py-3.5 rounded-xl bg-[#020617] border border-white/10 text-white/60 hover:text-white hover:border-cyan-500/50 hover:shadow-[0_0_20px_rgba(6,182,212,0.2)] flex items-center justify-center gap-2 transition-all group/mail">
                            <svg class="w-4 h-4 text-cyan-500 group-hover/mail:-translate-y-0.5 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span class="text-xs font-mono font-bold">Kirim Email</span>
                        </a>
                    </div>

                    {{-- KANAN: PROJECT SHOWCASE (BENTO BOXES) --}}
                    <div class="xl:col-span-2 flex flex-col gap-6 md:gap-8 h-full">
                        
                        {{-- Judul Penelitian Box --}}
                        <div class="glass-card rounded-[2rem] p-8 md:p-12 relative overflow-hidden flex-1 flex flex-col justify-center border-l-4 border-l-indigo-500 shadow-2xl shadow-indigo-900/10">
                            <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.02]"></div>
                            
                            <div class="relative z-10">
                                <div class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-indigo-500/10 border border-indigo-500/20 text-indigo-400 text-[10px] font-bold uppercase tracking-widest mb-6 shadow-inner">
                                    <span class="w-1.5 h-1.5 rounded-full bg-indigo-500 animate-pulse"></span>
                                    Topik Skripsi / Penelitian
                                </div>
                                
                                <h2 class="text-2xl md:text-3xl lg:text-4xl font-black text-white leading-snug md:leading-tight mb-6">
                                    Penerapan Pemantauan Kinerja Siswa Pada Media Pembelajaran Interaktif Materi <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-indigo-400">Tailwind CSS</span>
                                </h2>

                                <p class="text-white/60 text-sm md:text-base leading-relaxed max-w-2xl border-l-2 border-white/10 pl-5 py-1">
                                    Proyek media pembelajaran mandiri ini dirancang untuk memfasilitasi siswa dalam memahami framework <span class="text-white font-bold">utility-first</span> secara interaktif, dilengkapi sistem analitik untuk memetakan progres belajar.
                                </p>
                            </div>

                            {{-- Tech Stack Badges --}}
                            <div class="relative z-10 flex flex-wrap items-center gap-3 mt-10">
                                <span class="px-4 py-2 rounded-xl bg-[#020617] border border-white/10 text-xs font-bold text-slate-300 flex items-center gap-2 shadow-inner"><div class="w-3 h-3 bg-red-500 rounded-sm"></div> Laravel</span>
                                <span class="px-4 py-2 rounded-xl bg-[#020617] border border-white/10 text-xs font-bold text-slate-300 flex items-center gap-2 shadow-inner"><div class="w-3 h-3 bg-cyan-400 rounded-full"></div> Tailwind CSS</span>
                                <span class="px-4 py-2 rounded-xl bg-[#020617] border border-white/10 text-xs font-bold text-slate-300 flex items-center gap-2 shadow-inner"><div class="w-3 h-3 bg-blue-400 rounded-sm rotate-45"></div> Alpine.js</span>
                            </div>
                        </div>

                        {{-- Dosen & CTA Row --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 md:gap-8">
                            
                            {{-- Card Dosen --}}
                            <div class="glass-card rounded-[2rem] p-8 flex flex-col justify-center relative overflow-hidden">
                                <div class="absolute right-0 bottom-0 opacity-[0.03]">
                                    <svg class="w-48 h-48 -mb-10 -mr-10 text-white" fill="currentColor" viewBox="0 0 24 24"><path d="M12 14l9-5-9-5-9 5 9 5z"/><path d="M12 14l6.16-3.422a12.083 12.083 0 01.665 6.479A11.952 11.952 0 0012 20.055a11.952 11.952 0 00-6.824-2.998 12.078 12.078 0 01.665-6.479L12 14z"/></svg>
                                </div>
                                
                                <h4 class="text-[10px] text-white/40 uppercase font-black tracking-widest mb-6">Tim Pembimbing</h4>
                                
                                <div class="space-y-5 relative z-10">
                                    <div class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold shrink-0 shadow-inner">1</div>
                                        <p class="text-sm font-bold text-white/90 leading-tight pt-1.5">Novan Alkaf Bahrain Saputra, S.Kom., M.T.</p>
                                    </div>
                                    <div class="flex items-start gap-4">
                                        <div class="w-8 h-8 rounded-lg bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 flex items-center justify-center text-sm font-bold shrink-0 shadow-inner">2</div>
                                        <p class="text-sm font-bold text-white/90 leading-tight pt-1.5">Muhammad Hifdzi Adini, S.Kom., M.T.</p>
                                    </div>
                                </div>
                            </div>

                            {{-- Card Action --}}
                            <div class="glass-card rounded-[2rem] p-8 flex flex-col items-center justify-center text-center relative overflow-hidden group">
                                <div class="absolute inset-0 bg-gradient-to-br from-cyan-600/10 to-indigo-600/10 opacity-0 group-hover:opacity-100 transition duration-500"></div>
                                
                                <div class="w-16 h-16 rounded-full bg-[#020617] border border-white/10 flex items-center justify-center mb-6 text-cyan-400 shadow-lg relative z-10 group-hover:scale-110 transition duration-300">
                                    <svg class="w-7 h-7" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                </div>
                                
                                <h4 class="text-xl font-bold text-white mb-3 relative z-10">Eksplorasi Media</h4>
                                <p class="text-sm text-white/50 mb-8 relative z-10 leading-relaxed max-w-xs">Lihat hasil implementasi langsung dari media pembelajaran Tailwind CSS ini.</p>
                                
                                <a href="https://utilwind.research-media.web.id/" target="_blank" class="w-full relative z-10 inline-flex items-center justify-center gap-3 px-6 py-4 rounded-xl bg-cyan-500 hover:bg-cyan-400 text-[#020617] font-black text-sm uppercase tracking-wider transition-all shadow-[0_0_30px_rgba(6,182,212,0.4)] hover:scale-105 active:scale-95">
                                    Kunjungi Situs Utama
                                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/></svg>
                                </a>
                            </div>

                        </div>
                    </div>

                </div>
            </div>
        </main>
    </div>
</div>

{{-- SCRIPT & STYLE TAMBAHAN --}}
<style>
    /* Global Override Custom Scrollbar untuk match dengan wrapper */
    .custom-scrollbar::-webkit-scrollbar { width: 5px; height: 5px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    /* CSS Animasi Murni */
    .glass-card { background: rgba(10, 14, 23, 0.7); border: 1px solid rgba(255, 255, 255, 0.08); box-shadow: 0 4px 30px rgba(0, 0, 0, 0.2); backdrop-filter: blur(16px); }
    
    .animate-spin-slow { animation: spin 8s linear infinite; }
    @keyframes spin { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }

    .reveal { opacity: 0; transform: translateY(30px); animation: revealAnim 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; }
    @keyframes revealAnim { to { opacity: 1; transform: translateY(0); } }
    
    @keyframes float { 0% { transform: translateY(0px); } 50% { transform: translateY(-10px); } 100% { transform: translateY(0px); } }
    .floating { animation: float 6s ease-in-out infinite; }

    /* 3D Perspective Card Setup */
    .perspective-container { perspective: 1000px; transform-style: preserve-3d; }
    .tilt-element { transition: transform 0.1s ease-out; transform-style: preserve-3d; }
    .tilt-element::before {
        content: ''; position: absolute; inset: 0; border-radius: inherit;
        background: linear-gradient(135deg, rgba(255,255,255,0.1) 0%, rgba(255,255,255,0) 100%);
        opacity: 0; transition: opacity 0.3s; pointer-events: none; z-index: 10;
    }
    .tilt-element:hover::before { opacity: 1; }
    .inner-3d { transform: translateZ(40px); } /* Efek popping up dari dalam card */
</style>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        // Efek 3D Card Hover (Hanya jalan di Desktop agar mobile tidak berat)
        if(window.innerWidth > 1024) {
            const tiltElements = document.querySelectorAll('.tilt-element');
            
            tiltElements.forEach(el => {
                el.addEventListener('mousemove', (e) => {
                    const rect = el.getBoundingClientRect();
                    // Menghitung posisi relative kursor dalam kotak
                    const x = e.clientX - rect.left;
                    const y = e.clientY - rect.top;
                    
                    const centerX = rect.width / 2;
                    const centerY = rect.height / 2;
                    
                    // Rotasi berlawanan dengan arah mouse untuk efek menekan (tilt)
                    const rotateX = ((y - centerY) / centerY) * -10; // Max rotasi 10 derajat
                    const rotateY = ((x - centerX) / centerX) * 10;
                    
                    el.style.transform = `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale3d(1.02, 1.02, 1.02)`;
                });
                
                // Reset posisi ketika mouse keluar
                el.addEventListener('mouseleave', () => {
                    el.style.transform = `perspective(1000px) rotateX(0deg) rotateY(0deg) scale3d(1, 1, 1)`;
                });
            });
        }
    });
</script>
@endsection