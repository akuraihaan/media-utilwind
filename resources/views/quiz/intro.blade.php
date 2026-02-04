<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persiapan Evaluasi - TailwindLearn</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
        .glass { background: rgba(255, 255, 255, 0.05); backdrop-filter: blur(10px); border: 1px solid rgba(255, 255, 255, 0.1); }
        .animate-float { animation: float 6s ease-in-out infinite; }
        @keyframes float { 0%, 100% { transform: translateY(0); } 50% { transform: translateY(-10px); } }
    </style>
</head>
<body class="bg-[#020617] text-white h-screen flex items-center justify-center overflow-hidden selection:bg-fuchsia-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-fuchsia-600/20 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
    </div>

    <div class="max-w-2xl w-full mx-4">
        <div class="glass rounded-3xl p-8 md:p-12 shadow-2xl relative overflow-hidden">
            
            <div class="absolute top-0 left-0 w-full h-1.5 bg-gradient-to-r from-purple-500 via-fuchsia-500 to-cyan-500"></div>

            <div class="text-center mb-10">
                <div class="w-20 h-20 bg-gradient-to-br from-purple-600 to-fuchsia-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-lg animate-float">
                    <span class="text-4xl">📝</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black mb-2">Evaluasi Bab {{ $chapterId }}</h1>
                <p class="text-white/50">Harap baca ketentuan berikut sebelum memulai.</p>
            </div>

            <div class="grid md:grid-cols-2 gap-4 mb-8">
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex items-start gap-3">
                    <!-- <div class="text-xl">⏱️</div> -->
                    <div>
                        <h4 class="font-bold text-white text-sm">Durasi Waktu</h4>
                        <p class="text-xs text-white/50 mt-1">20 Menit pengerjaan. Waktu berjalan server-side (tetap berjalan meski browser ditutup).</p>
                    </div>
                </div>
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex items-start gap-3">
                    <!-- <div class="text-xl">🎯</div> -->
                    <div>
                        <h4 class="font-bold text-white text-sm">Passing Grade</h4>
                        <p class="text-xs text-white/50 mt-1">Nilai minimal 70 untuk dianggap lulus pada bab ini.</p>
                    </div>
                </div>
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex items-start gap-3">
                    <!-- <div class="text-xl">⚡</div> -->
                    <div>
                        <h4 class="font-bold text-white text-sm">Auto Submit</h4>
                        <p class="text-xs text-white/50 mt-1">Jawaban tersimpan otomatis saat waktu habis.</p>
                    </div>
                </div>
                <div class="bg-white/5 p-4 rounded-xl border border-white/5 flex items-start gap-3">
                    <!-- <div class="text-xl">🚫</div> -->
                    <div>
                        <h4 class="font-bold text-white text-sm">Integritas</h4>
                        <p class="text-xs text-white/50 mt-1">Dilarang membuka tab lain atau melakukan kecurangan.</p>
                    </div>
                </div>
            </div>

            <form action="{{ route('quiz.startSession') }}" method="POST">
                @csrf
                <input type="hidden" name="chapter_id" value="{{ $chapterId }}">

                <label class="flex items-start gap-3 p-4 rounded-xl border border-white/10 bg-[#0f172a]/50 cursor-pointer hover:bg-[#0f172a] transition mb-8 group">
                    <div class="relative flex items-center">
                        <input type="checkbox" id="agreement" class="peer h-5 w-5 cursor-pointer appearance-none rounded border border-white/20 bg-white/5 checked:border-fuchsia-500 checked:bg-fuchsia-500 transition-all">
                        <svg class="pointer-events-none absolute left-1/2 top-1/2 -translate-x-1/2 -translate-y-1/2 opacity-0 peer-checked:opacity-100 w-3.5 h-3.5 text-white" viewBox="0 0 14 14" fill="none">
                            <path d="M3 8L6 11L11 3.5" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                    </div>
                    <span class="text-sm text-gray-400 group-hover:text-gray-300 select-none">
                        Saya menyatakan telah memahami materi Bab {{ $chapterId }} dan siap mengerjakan evaluasi ini dengan jujur.
                    </span>
                </label>

                <div class="flex gap-4">
                    <a href="{{ route('dashboard') }}" class="flex-1 py-3.5 rounded-xl border border-white/10 text-center text-sm font-bold text-white/60 hover:bg-white/5 hover:text-white transition">
                        Batal
                    </a>
                    <button type="submit" id="startBtn" disabled class="flex-1 py-3.5 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 text-white font-bold text-sm shadow-lg shadow-purple-900/40 opacity-50 cursor-not-allowed transition-all">
                        Mulai Mengerjakan →
                    </button>
                </div>
            </form>

        </div>
        
        <p class="text-center text-white/20 text-xs mt-6">TailwindLearn Quiz System v2.0</p>
    </div>

    <script>
        const checkbox = document.getElementById('agreement');
        const btn = document.getElementById('startBtn');

        checkbox.addEventListener('change', function() {
            if(this.checked) {
                btn.disabled = false;
                btn.classList.remove('opacity-50', 'cursor-not-allowed');
                btn.classList.add('hover:scale-[1.02]', 'active:scale-95');
            } else {
                btn.disabled = true;
                btn.classList.add('opacity-50', 'cursor-not-allowed');
                btn.classList.remove('hover:scale-[1.02]', 'active:scale-95');
            }
        });
    </script>
</body>
</html>