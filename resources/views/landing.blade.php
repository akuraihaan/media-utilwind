@extends('layouts.landing')

@section('title', 'TailwindLearn · Media Pembelajaran Interaktif')

@section('content')
<div id="landingRoot" class="relative min-h-screen bg-[#020617] text-slate-200 font-sans overflow-x-hidden selection:bg-fuchsia-500/30 selection:text-white">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 h-[74px] w-full bg-[#020617]/10 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-6 lg:px-8 transition-all duration-500">
        
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-fuchsia-500 to-cyan-400 flex items-center justify-center font-extrabold text-black shadow-xl">TW</div>
            <span class="font-semibold tracking-wide text-lg">TailwindLearn</span>
        </div>

        <div class="hidden md:flex gap-10 text-sm font-medium">
            <a href="#hero" class="nav-link text-white font-bold relative">Beranda</a>
            <a href="#tentang" class="nav-link text-white/70 hover:text-white transition">Tentang</a>
            <a href="#fitur" class="nav-link text-white/70 hover:text-white transition">Fitur</a>
            <a href="#alur" class="nav-link text-white/70 hover:text-white transition">Alur</a>
            <a href="{{ route('sandbox') }}" class="nav-link text-white/70 hover:text-white transition">Sandbox</a>
        </div>

        <div class="flex gap-3 items-center">
            @auth
                <span class="text-white/70 text-sm hidden sm:block font-medium">{{ Auth::user()->name }}</span>
                
                @if(auth()->user()->role === 'student')
                    <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-xl border border-white/20 text-sm hover:bg-white/10 transition hidden lg:block">Dashboard</a>
                @endif

                @if(auth()->user()->role === 'admin')
                    <a href="{{ route('admin.dashboard') }}" class="px-5 py-2 rounded-xl border border-fuchsia-500/30 text-fuchsia-400 text-sm hover:bg-fuchsia-500/10 transition hidden lg:block">Admin</a>
                @endif

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="cta-main px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 text-sm font-semibold shadow-lg shadow-fuchsia-900/30 hover:scale-105 transition transform">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-bold text-white/70 hover:text-white transition px-2">Masuk</a>
                <a href="{{ route('register') }}" class="cta-main px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 text-sm font-semibold shadow-lg shadow-fuchsia-900/30 hover:scale-105 transition transform">
                    Mulai Belajar
                </a>
            @endauth
        </div>
    </nav>

    <div id="scrollProgress" class="fixed top-[73px] left-0 h-[2px] bg-gradient-to-r from-fuchsia-500 via-purple-500 to-cyan-400 z-[60] shadow-[0_0_10px_#d946ef]" style="width:0%"></div>

    <section id="hero" class="relative pt-48 pb-32 px-6 lg:px-8 overflow-hidden min-h-[90vh] flex items-center justify-center">
        
        <div class="absolute top-1/4 left-[10%] w-32 h-32 bg-cyan-500/10 rounded-full blur-[60px] parallax" data-speed="3"></div>
        <div class="absolute bottom-1/3 right-[10%] w-48 h-48 bg-fuchsia-500/10 rounded-full blur-[80px] parallax" data-speed="-2"></div>

        <div class="max-w-5xl mx-auto text-center relative z-10">
            
            <!-- <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full bg-white/5 border border-white/10 text-cyan-300 text-[10px] font-bold uppercase tracking-widest mb-8 backdrop-blur-md animate-fade-in-up shadow-lg shadow-cyan-900/20">
                <span class="relative flex h-2 w-2">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-cyan-400 opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 bg-cyan-500"></span>
                </span>
                Media Pembelajaran Interaktif
            </div> -->

            <h1 class="text-5xl md:text-7xl lg:text-8xl font-black text-white leading-[1.1] mb-8 tracking-tight animate-fade-in-up" style="animation-delay: 0.1s;">
                Belajar <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 via-purple-400 to-cyan-400 animate-gradient-x">Web Design</span> <br>
                Dengan Tutorial Terstruktur
            </h1>

            @auth
                <p class="mt-4 text-emerald-400 font-bold animate-fade-in-up" style="animation-delay: 0.2s;">
                    👋 Selamat datang kembali, {{ Auth::user()->name }}
                </p>
            @endauth

            <p class="mt-8 max-w-3xl mx-auto text-lg md:text-xl text-white/60 leading-relaxed animate-fade-in-up" style="animation-delay: 0.2s;">
                Platform pembelajaran berbasis web yang dirancang untuk mendukung proses belajar secara bertahap, interaktif, dan terukur melalui sistem monitoring progress.
            </p>

            <div class="mt-12 flex flex-col sm:flex-row justify-center gap-5 animate-fade-in-up" style="animation-delay: 0.3s;">
                @guest
                    <a href="{{ route('register') }}" class="group relative px-8 py-4 rounded-2xl bg-white text-black font-bold text-lg hover:scale-105 transition-transform shadow-[0_0_30px_rgba(255,255,255,0.2)] overflow-hidden">
                        <span class="relative z-10">Mulai Belajar Gratis</span>
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-200 to-fuchsia-200 opacity-0 group-hover:opacity-100 transition duration-300"></div>
                    </a>
                @else
                    <a href="{{ route('courses.htmldancss') }}" class="px-8 py-4 rounded-2xl bg-gradient-to-r from-emerald-400 to-cyan-500 text-black font-bold text-lg hover:scale-105 transition-transform shadow-[0_0_20px_rgba(52,211,153,0.4)]">
                        Lanjutkan Belajar
                    </a>
                @endauth

                <a href="#tentang" class="px-8 py-4 rounded-2xl bg-white/5 border border-white/10 text-white font-bold text-lg hover:bg-white/10 transition-all backdrop-blur-sm flex items-center gap-2 group">
                    <span>Pelajari Konsep</span>
                    <svg class="w-5 h-5 group-hover:translate-y-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"/></svg>
                </a>
            </div>

            <div id="scrollHint" class="absolute bottom-[-100px] left-1/2 -translate-x-1/2 text-white/30 text-xs font-bold uppercase tracking-widest animate-bounce">
                Scroll Down
            </div>
        </div>
    </section>

    <section id="tentang" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="grid lg:grid-cols-2 gap-16 items-center">
                <div class="reveal">
                    <h2 class="text-3xl md:text-5xl font-black text-white leading-tight mb-6">
                        Metode Belajar <br>
                        <span class="text-fuchsia-400">Step-by-Step</span>
                    </h2>
                    <p class="text-white/60 text-lg leading-relaxed mb-6">
                        TailwindLearn dikembangkan untuk mendukung proses pembelajaran mandiri. Evaluasi quiz dan pencatatan attempt digunakan untuk memonitor perkembangan secara objektif.
                    </p>
                    <div class="flex flex-wrap gap-3">
                        @foreach(['Materi Terstruktur', 'Evaluasi Quiz', 'Tracking Progress', 'Gamifikasi'] as $tag)
                            <span class="px-3 py-1 rounded-lg bg-white/5 border border-white/10 text-xs font-bold text-white/70">{{ $tag }}</span>
                        @endforeach
                    </div>
                </div>

                <div class="tilt-card group relative p-1 rounded-3xl bg-gradient-to-b from-white/10 to-white/5 hover:from-cyan-500/50 hover:to-fuchsia-500/50 transition-all duration-500 reveal">
                    <div class="relative h-full bg-[#0b0f19] rounded-[22px] p-10 overflow-hidden border border-white/5">
                        <div class="absolute top-0 right-0 w-40 h-40 bg-fuchsia-500/10 rounded-full blur-[50px]"></div>
                        <ul class="space-y-6">
                            @foreach(['Materi dari dasar hingga mahir', 'Studi kasus nyata', 'Quiz interaktif', 'Sandbox coding live'] as $item)
                            <li class="flex items-center gap-4 text-white/80">
                                <div class="w-8 h-8 rounded-full bg-emerald-500/20 flex items-center justify-center text-emerald-400 font-bold text-xs">✓</div>
                                {{ $item }}
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="fitur" class="py-32 border-t border-white/5 bg-[#0b0f19]/30">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-20 reveal">
                <h2 class="text-4xl font-black text-white mb-4">Fitur Unggulan</h2>
                <p class="text-white/60">Platform lengkap untuk menunjang karir Anda.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                @foreach([
                    ['Tutorial Terstruktur', 'Disusun sistematis untuk pemahaman maksimal.', '📚', 'from-orange-500/20'],
                    ['Monitoring Progress', 'Analisis aktivitas belajar secara real-time.', '📊', 'from-blue-500/20'],
                    ['Quiz & Attempt', 'Evaluasi pemahaman dengan sistem skor.', '🏆', 'from-purple-500/20'],
                ] as [$title, $desc, $icon, $grad])
                <div class="group relative p-8 rounded-3xl bg-[#0f141e] border border-white/5 hover:border-white/10 transition-all duration-500 hover:-translate-y-2 reveal">
                    <div class="absolute inset-0 bg-gradient-to-br {{ $grad }} to-transparent opacity-0 group-hover:opacity-100 transition duration-500 rounded-3xl"></div>
                    <div class="relative z-10">
                        <div class="w-12 h-12 rounded-xl bg-white/5 flex items-center justify-center text-2xl mb-6 border border-white/5">{{ $icon }}</div>
                        <h3 class="text-xl font-bold text-white mb-3">{{ $title }}</h3>
                        <p class="text-white/50 leading-relaxed">{{ $desc }}</p>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </section>

    <section id="alur" class="py-32 relative">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <h2 class="text-4xl font-black text-center text-white mb-20 reveal">Roadmap Belajar</h2>
            
            <div class="relative">
                <div class="absolute top-12 left-0 w-full h-0.5 bg-white/5 hidden md:block"></div>

                <div class="grid md:grid-cols-4 gap-8 relative z-10">
                    @foreach(['Daftar Akun', 'Pilih Materi', 'Kerjakan Quiz', 'Lihat Progress'] as $i => $step)
                    <div class="group text-center reveal" style="transition-delay: {{ $i * 100 }}ms">
                        <div class="w-24 h-24 mx-auto rounded-full bg-[#0f141e] border border-white/10 flex items-center justify-center text-4xl font-black text-white mb-6 shadow-[0_0_20px_rgba(0,0,0,0.5)] group-hover:border-cyan-500/50 group-hover:scale-110 transition duration-500 relative overflow-hidden">
                            <div class="absolute inset-0 bg-cyan-500/10 opacity-0 group-hover:opacity-100 transition duration-500 rounded-full"></div>
                            <span class="relative z-10 group-hover:text-cyan-400 transition">{{ $i+1 }}</span>
                        </div>
                        <h3 class="text-lg font-bold text-white mb-2 group-hover:text-cyan-400 transition">{{ $step }}</h3>
                        <p class="text-xs text-white/40">Langkah {{ $i+1 }} menuju pro.</p>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <section class="py-32 border-t border-white/5 relative bg-[#050912]">
        <div class="max-w-7xl mx-auto px-6 lg:px-8">
            <div class="text-center mb-16 reveal">
                <h2 class="text-4xl font-black text-white mb-4">Intip Dashboard Anda</h2>
                <p class="text-white/60">Pantau performa belajar dengan visualisasi data yang menarik.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-10 reveal">
                
                <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-fuchsia-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <p class="text-sm font-bold text-white/60 mb-2">Progress Belajar</p>
                    <p id="previewProgress" class="text-5xl font-black text-fuchsia-400 counter" data-target="72">0<span class="text-2xl">%</span></p>
                    <div class="mt-6 h-2 w-full bg-white/5 rounded-full overflow-hidden">
                        <div id="previewBar" class="h-full bg-gradient-to-r from-fuchsia-500 to-cyan-400 w-0 transition-all duration-1000 ease-out"></div>
                    </div>
                </div>

                <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <p class="text-sm font-bold text-white/60 mb-2">Modul Diselesaikan</p>
                    <p class="text-5xl font-black text-cyan-400 counter" data-target="8">0</p>
                </div>

                <div class="rounded-3xl bg-[#0f141e] border border-white/10 p-8 relative overflow-hidden group">
                    <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/10 to-transparent opacity-0 group-hover:opacity-100 transition duration-500"></div>
                    <p class="text-sm font-bold text-white/60 mb-2">Quiz Attempt</p>
                    <p class="text-5xl font-black text-emerald-400 counter" data-target="21">0</p>
                </div>

            </div>
        </div>
    </section>

    <footer class="border-t border-white/10 bg-[#020617] relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-t from-cyan-900/10 to-transparent pointer-events-none"></div>
        
        <div class="max-w-7xl mx-auto px-6 lg:px-8 py-24 relative z-10 text-center">
            <h2 class="text-4xl font-black text-white mb-8">Siap Menjadi Frontend Expert?</h2>
            <p class="text-white/60 mb-12 text-lg">Akses semua materi dasar secara gratis. Upgrade skill Anda hari ini.</p>
            
            <div class="flex justify-center gap-6">
                @auth
                    <a href="{{ route('dashboard') }}" class="px-10 py-4 rounded-2xl bg-cyan-500 hover:bg-cyan-400 text-black font-bold text-lg transition shadow-[0_0_30px_rgba(34,211,238,0.4)]">
                        Buka Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="px-10 py-4 rounded-2xl bg-white text-black font-bold text-lg hover:bg-cyan-50 transition shadow-[0_0_30px_rgba(255,255,255,0.2)]">
                        Buat Akun Gratis
                    </a>
                @endauth
            </div>

            <div class="mt-24 pt-8 border-t border-white/5 flex flex-col md:flex-row justify-between items-center gap-4 text-xs text-white/30">
                <p>&copy; {{ date('Y') }} Flowwind Learn. All rights reserved.</p>
                <div class="flex gap-6">
                    <a href="#" class="hover:text-white transition">Privacy Policy</a>
                    <a href="#" class="hover:text-white transition">Terms of Service</a>
                </div>
            </div>
        </div>
    </footer>

</div>

<style>
    /* NAVBAR */
    .nav-link { position: relative; transition: color .3s ease; }
    .nav-link:hover { color: #fff; }
    /* Manual Active State for Sections (Handled by JS) */
    .nav-link.active { color: #fff; font-weight: 700; text-shadow: 0 0 10px rgba(255,255,255,0.3); }

    /* ANIMATIONS */
    @keyframes bgMove { to { transform: scale(1.15); } }
    @keyframes waveMove { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    @keyframes gradient-x { 0%, 100% { background-position: 0% 50%; } 50% { background-position: 100% 50%; } }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .animate-gradient-x { background-size: 200% 200%; animation: gradient-x 5s ease infinite; }
    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }
    
    .reveal { opacity: 0; transform: translateY(30px); transition: all 0.8s cubic-bezier(0.16, 1, 0.3, 1); }
    .reveal.show { opacity: 1; transform: translateY(0); }

    /* BACKGROUNDS */
    #animated-bg {
        background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%),
                    radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%),
                    radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    #gradient-wave {
        background: linear-gradient(120deg,rgba(217,70,239,.05),rgba(34,211,238,.05),rgba(168,85,247,.05));
        background-size: 400% 400%; animation: waveMove 26s ease infinite;
    }
    #cursor-glow {
        position: fixed; width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(217,70,239,.12), transparent 65%);
        filter: blur(80px); pointer-events: none; z-index: 999; transform: translate(-50%,-50%); transition: width 0.3s, height 0.3s;
    }
    #noise-overlay {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        mix-blend-mode: overlay;
    }

    /* 3D TILT */
    .tilt-card { transform-style: preserve-3d; perspective: 1000px; }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(document).ready(function() {
    
    // 1. NAVBAR SCROLL EFFECT
    $(window).scroll(function() {
        if ($(this).scrollTop() > 50) {
            $('#navbar').removeClass('h-[74px] bg-[#020617]/10').addClass('h-[64px] bg-[#020617]/80 backdrop-blur-xl');
        } else {
            $('#navbar').addClass('h-[74px] bg-[#020617]/10').removeClass('h-[64px] bg-[#020617]/80 backdrop-blur-xl');
        }
        
        // Progress Bar
        const p = ($(window).scrollTop() / ($(document).height() - $(window).height())) * 100;
        $('#scrollProgress').css('width', p+'%');
    });

    // 2. NAV ACTIVE STATE
    const sections = ['#hero', '#tentang', '#fitur', '#alur'];
    $(window).scroll(function() {
        let current = '';
        sections.forEach(id => {
            const section = $(id);
            if (section.length && $(window).scrollTop() >= section.offset().top - 150) {
                current = id;
            }
        });
        $('.nav-link').removeClass('font-bold text-white');
        $(`.nav-link[href="${current}"]`).addClass('font-bold text-white');
    });

    // 3. TILT EFFECT
    $('.tilt-card').mousemove(function(e) {
        const el = $(this); const offset = el.offset();
        const w = el.width(); const h = el.height();
        const mouseX = e.pageX - offset.left - w/2;
        const mouseY = e.pageY - offset.top - h/2;
        const rotateX = (mouseY / h) * -10;
        const rotateY = (mouseX / w) * 10;
        el.children().css('transform', `perspective(1000px) rotateX(${rotateX}deg) rotateY(${rotateY}deg) scale(1.02)`);
    });
    $('.tilt-card').mouseleave(function() { $(this).children().css('transform', 'perspective(1000px) rotateX(0) rotateY(0) scale(1)'); });

    // 4. CURSOR GLOW
    $(document).mousemove(function(e) { $('#cursor-glow').css({ left: e.clientX, top: e.clientY }); });

    // 5. PARALLAX HERO
    $(window).scroll(function() {
        const scrolled = $(window).scrollTop();
        $('.parallax').each(function() {
            const speed = $(this).data('speed');
            $(this).css('transform', `translateY(${scrolled * speed * 0.1}px)`);
        });
    });

    // 6. SCROLL REVEAL & COUNTER & DASHBOARD PREVIEW
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                entry.target.classList.add('show');
                
                // Animate Progress Bar Preview
                if(entry.target.id === 'previewBar' || $(entry.target).find('#previewBar').length) {
                    $('#previewBar').css('width', '72%');
                }

                // Trigger Counters
                $(entry.target).find('.counter').each(function() {
                    const $this = $(this);
                    if ($this.data('animated')) return;
                    $this.data('animated', true);
                    const target = $this.data('target');
                    $({ val: 0 }).animate({ val: target }, {
                        duration: 2500, easing: 'swing',
                        step: function() { 
                            let txt = Math.ceil(this.val);
                            if($this.text().includes('%')) txt += '%';
                            $this.text(txt); 
                        }
                    });
                });
            }
        });
    });
    $('.reveal').each(function() { observer.observe(this); });

    // 7. STARS CANVAS
    const c = document.getElementById('stars'); const x = c.getContext('2d');
    let s = []; function initStars() { c.width = window.innerWidth; c.height = window.innerHeight; s=[]; for(let i=0;i<100;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.2+0.1}); }
    function animateStars() { x.clearRect(0,0,c.width,c.height); x.fillStyle='rgba(255,255,255,0.5)'; s.forEach(t=>{ x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill(); t.y-=t.v; if(t.y<0)t.y=c.height; }); requestAnimationFrame(animateStars); }
    window.addEventListener('resize', initStars); initStars(); animateStars();
});
</script>
@endsection