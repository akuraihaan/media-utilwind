@extends('layouts.auth')

@section('title', 'Login')

@section('content')
<div class="relative min-h-screen flex items-center justify-center bg-[#020617] text-slate-200 font-sans overflow-hidden selection:bg-fuchsia-500/30 selection:text-white p-6">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    <nav class="absolute top-6 left-6 z-50 animate-fade-in-up" style="animation-delay: 0.2s;">
        <a href="{{ url('/') }}" 
           class="group flex items-center gap-3 px-5 py-2.5 rounded-full bg-[#0f141e]/50 border border-white/10 hover:border-cyan-500/50 hover:bg-cyan-500/10 backdrop-blur-md transition-all duration-300 shadow-lg shadow-black/20">
            <div class="w-6 h-6 rounded-full bg-white/5 flex items-center justify-center group-hover:bg-cyan-400 group-hover:text-black text-white/50 transition-all duration-300">
                <svg class="w-3.5 h-3.5 transition-transform duration-300 group-hover:-translate-x-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
            </div>
            <span class="text-sm font-bold text-white/60 group-hover:text-white transition-colors">Kembali ke Beranda</span>
        </a>
    </nav>

    <div class="w-full max-w-[450px] relative z-20 animate-fade-in-up">
        
        <div class="absolute -top-20 -left-20 w-40 h-40 bg-fuchsia-500/20 rounded-full blur-[60px] animate-pulse"></div>
        <div class="absolute -bottom-20 -right-20 w-40 h-40 bg-cyan-500/20 rounded-full blur-[60px] animate-pulse" style="animation-delay: 1s;"></div>

        <div class="relative bg-[#0f141e]/70 backdrop-blur-xl border border-white/10 rounded-3xl p-8 md:p-10 shadow-2xl shadow-black/50 overflow-hidden group">
            
            <div class="absolute inset-0 bg-gradient-to-br from-white/5 to-transparent opacity-0 group-hover:opacity-100 transition duration-500 pointer-events-none"></div>

            <div class="text-center mb-10">
                    
                </a>
                
                <h1 class="text-2xl font-black text-white mb-2">Selamat Datang Kembali!</h1>
                <p class="text-white/50 text-sm">Lanjutkan progress belajar Tailwind CSS Anda.</p>
            </div>

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf

                <div class="group/input">
                    <label class="block text-xs font-bold text-white/60 uppercase tracking-wider mb-2 ml-1 group-focus-within/input:text-cyan-400 transition-colors">Email Address</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-white/30 group-focus-within/input:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207" />
                            </svg>
                        </div>
                        <input name="email" type="email" required autofocus
                            class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-[#020617]/50 border border-white/10 text-white placeholder-white/20 focus:outline-none focus:border-cyan-500/50 focus:ring-2 focus:ring-cyan-500/20 transition-all duration-300"
                            placeholder="nama@email.com">
                    </div>
                    @error('email')
                        <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                <div class="group/input">
                    <label class="block text-xs font-bold text-white/60 uppercase tracking-wider mb-2 ml-1 group-focus-within/input:text-fuchsia-400 transition-colors">Password</label>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-white/30 group-focus-within/input:text-fuchsia-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                            </svg>
                        </div>
                        <input name="password" type="password" required
                            class="w-full pl-11 pr-4 py-3.5 rounded-xl bg-[#020617]/50 border border-white/10 text-white placeholder-white/20 focus:outline-none focus:border-fuchsia-500/50 focus:ring-2 focus:ring-fuchsia-500/20 transition-all duration-300"
                            placeholder="••••••••">
                    </div>
                    @error('password')
                        <p class="text-red-400 text-xs mt-2 flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                            {{ $message }}
                        </p>
                    @enderror
                </div>

                

                <button type="submit"
                    class="relative w-full py-4 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 font-bold text-white shadow-lg shadow-fuchsia-900/30 hover:shadow-fuchsia-900/50 hover:scale-[1.02] active:scale-[0.98] transition-all duration-300 overflow-hidden group/btn">
                    <span class="relative z-10 flex items-center justify-center gap-2">
                        Masuk Sekarang
                        <svg class="w-4 h-4 group-hover/btn:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6" />
                        </svg>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-transparent via-white/20 to-transparent -translate-x-full group-hover/btn:translate-x-full transition-transform duration-1000"></div>
                </button>
            </form>

            <div class="mt-8 text-center border-t border-white/5 pt-6">
                <p class="text-sm text-white/40">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-cyan-400 font-bold hover:text-cyan-300 hover:underline decoration-2 underline-offset-4 transition-all ml-1">
                        Daftar Gratis
                    </a>
                </p>
            </div>
        </div>
        
        <p class="text-center text-white/20 text-xs mt-8">
            &copy; {{ date('Y') }} Utilwind CSS
        </p>
    </div>

</div>

<style>
    @keyframes bgMove { to { transform: scale(1.15); } }
    @keyframes waveMove { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }

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
    #noise-overlay {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        mix-blend-mode: overlay;
    }
    #cursor-glow {
        position: fixed; width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(217,70,239,.1), transparent 65%);
        filter: blur(80px); pointer-events: none; z-index: 999; transform: translate(-50%,-50%);
    }
</style>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
    $(document).ready(function() {
        const c = document.getElementById('stars');
        if(c) {
            const x = c.getContext('2d');
            let s = [];
            function initStars() { 
                c.width = window.innerWidth; 
                c.height = window.innerHeight; 
                s=[]; 
                for(let i=0;i<60;i++) s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.2+0.1}); 
            }
            function animateStars() { 
                x.clearRect(0,0,c.width,c.height); 
                x.fillStyle='rgba(255,255,255,0.3)'; 
                s.forEach(t=>{ 
                    x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill(); 
                    t.y-=t.v; if(t.y<0)t.y=c.height; 
                }); 
                requestAnimationFrame(animateStars); 
            }
            window.addEventListener('resize', initStars); 
            initStars(); 
            animateStars();
        }

        $(document).mousemove(function(e) { 
            $('#cursor-glow').css({ left: e.clientX, top: e.clientY }); 
        });
    });
</script>
@endsection