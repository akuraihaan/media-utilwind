<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persiapan Evaluasi - Utilwind</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        /* TEMA: COSMIC PURPLE */
        body { font-family: 'Outfit', sans-serif; }
        
        /* Glassmorphism Card */
        .glass-card {
            background: rgba(30, 20, 60, 0.7);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(139, 92, 246, 0.15); /* Violet Border */
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }
        
        /* Info Boxes */
        .info-box {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
            transition: all 0.3s ease;
        }
        .info-box:hover {
            background: rgba(139, 92, 246, 0.1);
            border-color: rgba(139, 92, 246, 0.3);
            transform: translateY(-2px);
        }

        /* Checkbox Custom styling */
        .checkbox-wrapper input:checked + div {
            background-color: #d946ef; /* Fuchsia */
            border-color: #d946ef;
            box-shadow: 0 0 10px rgba(217, 70, 239, 0.5);
        }
        .checkbox-wrapper input:checked + div svg {
            display: block;
        }
        
        /* Glow Text */
        .text-glow { text-shadow: 0 0 20px rgba(139, 92, 246, 0.5); }
    </style>
</head>
<body class="bg-[#0f0720] text-white h-full min-h-screen flex items-center justify-center p-4 overflow-x-hidden selection:bg-fuchsia-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[600px] h-[600px] bg-violet-800/20 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[600px] h-[600px] bg-fuchsia-800/20 rounded-full blur-[120px] animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
    </div>

    <div class="max-w-3xl w-full relative z-10">
        
        <div class="glass-card rounded-3xl overflow-hidden relative">
            
            <div class="h-1 w-full bg-gradient-to-r from-violet-600 via-fuchsia-600 to-indigo-600 shadow-[0_0_15px_rgba(217,70,239,0.5)]"></div>

            <div class="p-8 md:p-12">
                
                <div class="text-center mb-10">
                    <div class="flex items-center justify-center gap-4 mb-6">
                        <div class="w-16 h-16 rounded-2xl bg-gradient-to-br from-violet-600 to-fuchsia-600 p-[2px] shadow-[0_0_30px_rgba(139,92,246,0.4)]">
                            <div class="w-full h-full bg-[#1a103c] rounded-2xl flex items-center justify-center p-3">
                                <img src="{{ asset('images/logo.png') }}" alt="Utilwind Logo" class="w-full h-full object-contain drop-shadow-lg">
                            </div>
                        </div>
                        <div class="text-left">
                            <h1 class="text-3xl font-bold text-white tracking-tight leading-none text-glow">
                                Util<span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-violet-400">wind</span>
                            </h1>
                            <p class="text-xs text-violet-300 font-bold tracking-[0.3em] uppercase mt-1 opacity-70">LMS Evaluation</p>
                        </div>
                    </div>

                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-3">Evaluasi Bab {{ $chapterId }}</h2>
                    <p class="text-violet-200/60 text-sm max-w-lg mx-auto leading-relaxed font-light">
                        Ujian ini menggunakan sistem CBT (Computer Based Test). Harap baca instruksi di bawah ini dengan seksama sebelum memulai.
                    </p>
                </div>

                <div class="grid md:grid-cols-2 gap-4 mb-10">
                    <div class="info-box p-5 rounded-2xl flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-xl bg-violet-500/10 flex items-center justify-center text-violet-400 group-hover:bg-violet-500 group-hover:text-white transition-colors shadow-inner border border-violet-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-violet-100 text-sm">Durasi 20 Menit</h4>
                            <p class="text-xs text-violet-300/50 mt-1 leading-relaxed">Waktu berjalan server-side.</p>
                        </div>
                    </div>

                    <div class="info-box p-5 rounded-2xl flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-xl bg-fuchsia-500/10 flex items-center justify-center text-fuchsia-400 group-hover:bg-fuchsia-500 group-hover:text-white transition-colors shadow-inner border border-fuchsia-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-violet-100 text-sm">KKM: 70 Poin</h4>
                            <p class="text-xs text-violet-300/50 mt-1 leading-relaxed">Anda harus mencapai skor minimal 70 untuk lulus.</p>
                        </div>
                    </div>

                    <div class="info-box p-5 rounded-2xl flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center text-red-400 group-hover:bg-red-500 group-hover:text-white transition-colors shadow-inner border border-red-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-violet-100 text-sm">Proteksi Ketat</h4>
                            <p class="text-xs text-violet-300/50 mt-1 leading-relaxed">Dilarang pindah tab, refresh, atau kembali. Sistem mendeteksi kecurangan.</p>
                        </div>
                    </div>

                    <div class="info-box p-5 rounded-2xl flex items-start gap-4 group">
                        <div class="w-10 h-10 rounded-xl bg-amber-500/10 flex items-center justify-center text-amber-400 group-hover:bg-amber-500 group-hover:text-white transition-colors shadow-inner border border-amber-500/20">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" /></svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-violet-100 text-sm">Real-time Save</h4>
                            <p class="text-xs text-violet-300/50 mt-1 leading-relaxed">Jawaban tersimpan otomatis ke database setiap kali diklik.</p>
                        </div>
                    </div>
                </div>

                <form action="{{ route('quiz.startSession') }}" method="POST">
                    @csrf
                    <input type="hidden" name="chapter_id" value="{{ $chapterId }}">

                    <label class="checkbox-wrapper flex items-start gap-4 p-4 rounded-2xl border border-violet-500/10 bg-[#1a103c]/50 cursor-pointer hover:bg-[#1a103c] hover:border-violet-500/30 transition mb-8 group select-none">
                        <div class="relative flex items-center mt-0.5">
                            <input type="checkbox" id="agreement" class="peer hidden">
                            <div class="w-5 h-5 rounded-md border border-violet-500/50 bg-violet-900/20 flex items-center justify-center transition-all group-hover:border-fuchsia-500">
                                <svg class="w-3.5 h-3.5 text-white hidden pointer-events-none" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </div>
                        </div>
                        <span class="text-sm text-violet-300 group-hover:text-white transition-colors font-light">
                            Saya menyatakan siap mengerjakan ujian dengan jujur, tidak melakukan kecurangan, dan menerima konsekuensi jika melanggar aturan.
                        </span>
                    </label>

                    <div class="flex gap-4">
                        <a href="{{ route('dashboard') }}" class="flex-1 py-4 rounded-xl border border-violet-500/20 text-center text-sm font-bold text-violet-300 hover:bg-white/5 hover:text-white transition hover:border-violet-500/50">
                            Kembali
                        </a>
                        
                        <button type="submit" id="startBtn" disabled class="flex-[2] py-4 rounded-xl bg-gradient-to-r from-violet-600 to-fuchsia-600 text-white font-bold text-sm shadow-[0_0_20px_rgba(139,92,246,0.3)] opacity-50 cursor-not-allowed transition-all transform flex items-center justify-center gap-2 border border-white/10">
                            <span>Mulai Mengerjakan</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                        </button>
                    </div>
                </form>

            </div>
        </div>
        
    </div>

    <script>
        const checkbox = document.getElementById('agreement');
        const btn = document.getElementById('startBtn');

        checkbox.addEventListener('change', function() {
            if(this.checked) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('hover:scale-[1.02]', 'hover:shadow-[0_0_30px_rgba(217,70,239,0.5)]');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.classList.remove('hover:scale-[1.02]', 'hover:shadow-[0_0_30px_rgba(217,70,239,0.5)]');
            }
        });
    </script>
</body>
</html>