<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ujian Bab {{ $chapterId }} | Utilwind CBT</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=JetBrains+Mono:wght@500&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        .font-mono { font-family: 'JetBrains Mono', monospace; }
        
        /* THEME: Glassmorphism Consistent with Intro */
        .glass-panel {
            background: rgba(15, 23, 42, 0.7);
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.08);
        }
        .glass-card {
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.05);
        }
        
        /* Custom Scrollbar */
        .custom-scrollbar::-webkit-scrollbar { width: 6px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        /* Radio Button Logic (Neon Glow Effect) */
        .option-input:checked + .option-card {
            border-color: #d946ef; /* Fuchsia-500 */
            background: linear-gradient(to right, rgba(217, 70, 239, 0.1), rgba(168, 85, 247, 0.05));
            box-shadow: 0 0 20px rgba(217, 70, 239, 0.1);
        }
        .option-input:checked + .option-card .option-circle {
            background: linear-gradient(135deg, #d946ef, #a855f7);
            border-color: transparent;
            color: white;
            box-shadow: 0 0 10px rgba(217, 70, 239, 0.3);
        }
        
        .no-select { user-select: none; -webkit-user-select: none; }
    </style>
</head>
<body class="bg-[#020617] text-white h-screen flex flex-col overflow-hidden selection:bg-fuchsia-500/30 no-select" 
      x-data="cbtApp()" x-init="initCBT()" oncontextmenu="return false;">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-purple-600/20 rounded-full blur-[100px] animate-pulse"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-fuchsia-600/20 rounded-full blur-[100px] animate-pulse" style="animation-delay: 2s;"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
    </div>

    <div x-show="isBlurred" 
         class="fixed inset-0 z-[100] bg-[#020617]/95 backdrop-blur-2xl flex flex-col items-center justify-center text-center p-8 transition-opacity duration-300"
         style="display: none;"
         x-transition.opacity>
        <div class="w-24 h-24 bg-red-500/10 rounded-full flex items-center justify-center mb-6 ring-1 ring-red-500/50 animate-pulse">
            <svg class="w-10 h-10 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
        </div>
        <h2 class="text-2xl font-bold text-white mb-2 tracking-widest uppercase">Fokus Terganggu</h2>
        <p class="text-white/50 max-w-md">
            Sistem mendeteksi Anda meninggalkan halaman ujian. <br>Kembali fokus mengerjakan soal sekarang.
        </p>
        <button @click="isBlurred = false" class="mt-8 px-8 py-3 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl transition-all shadow-lg shadow-red-900/40">
            KEMBALI KE UJIAN
        </button>
    </div>

    <header class="h-16 glass-panel fixed top-0 w-full z-50 flex items-center justify-between px-4 lg:px-8">
        
        <div class="flex items-center gap-6">
            <div class="flex items-center gap-2.5">
                
            </div>


            <div>
                <h2 class="text-xs font-bold text-white/80 uppercase tracking-wider">Bab {{ $chapterId }}</h2>
                <div class="flex items-center gap-1.5 mt-0.5">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[10px] text-emerald-400 font-bold tracking-wider">Evaluasi</span>
                </div>
            </div>
        </div>

        <div class="absolute left-1/2 -translate-x-1/2 hidden lg:flex flex-col items-center group cursor-default">
            <span class="text-[9px] uppercase tracking-[0.3em] text-white/30 font-bold mb-1 group-hover:text-fuchsia-400 transition-colors">Sisa Waktu</span>
            <div class="font-mono text-2xl font-bold tracking-tight transition-all duration-300 tabular-nums drop-shadow-[0_0_10px_rgba(255,255,255,0.1)]" 
                 :class="timeLeft < 300 ? 'text-red-400 drop-shadow-[0_0_15px_rgba(248,113,113,0.6)] animate-pulse scale-110' : 'text-white'"
                 x-text="formatTime(timeLeft)">
                --:--
            </div>
        </div>

        <div class="flex items-center gap-3">
            <div class="text-right hidden sm:block">
                <div class="text-xs font-bold text-white">{{ Auth::user()->name }}</div>
                <div class="text-[10px] text-white/40">Peserta #{{ Auth::id() }}</div>
            </div>
            <div class="w-9 h-9 rounded-full bg-white/5 border border-white/10 flex items-center justify-center text-xs font-bold text-fuchsia-400 ring-2 ring-white/5 shadow-inner">
                {{ substr(Auth::user()->name, 0, 1) }}
            </div>
        </div>
    </header>

    <div class="pt-16 h-full flex flex-col lg:flex-row">
        
        <main class="flex-1 h-full overflow-y-auto custom-scrollbar relative flex flex-col">
            
            <div class="h-0.5 w-full bg-white/5 lg:hidden">
                <div class="h-full bg-gradient-to-r from-purple-500 to-fuchsia-500 transition-all duration-300" :style="`width: ${(currentIndex + 1) / questions.length * 100}%`"></div>
            </div>

            <div class="flex-1 max-w-5xl mx-auto w-full p-6 lg:p-10 pb-32">
                
                <div x-show="!ready" class="h-64 flex flex-col items-center justify-center text-white/30 animate-pulse">
                    <div class="w-12 h-12 border-4 border-fuchsia-500 border-t-transparent rounded-full animate-spin mb-4 shadow-[0_0_20px_rgba(217,70,239,0.3)]"></div>
                    <span class="text-xs font-bold tracking-widest uppercase">Memuat Lembar Soal...</span>
                </div>

                <div x-show="ready" x-transition.opacity.duration.300ms>
                    
                    <div class="flex justify-between items-end mb-8 border-b border-white/5 pb-4">
                        <div class="flex items-center gap-3">
                            <span class="text-5xl font-black text-white/10 select-none">Q<span x-text="currentIndex + 1"></span></span>
                            <div class="h-8 w-px bg-white/10"></div>
                            <span class="text-xs font-bold text-white/40 uppercase tracking-wider">Single Choice</span>
                        </div>
                        
                        <div class="lg:hidden glass-card px-3 py-1.5 rounded-lg font-mono text-sm font-bold text-white/80">
                            <span x-text="formatTime(timeLeft)"></span>
                        </div>
                    </div>

                    <div class="text-lg md:text-xl leading-loose text-white/90 font-medium mb-10 tracking-wide">
                        <span x-html="currentQuestion.question_text"></span>
                    </div>

                    <div class="grid gap-3">
                        <template x-for="(option, idx) in currentQuestion.options" :key="option.id">
                            <label class="block relative cursor-pointer group">
                                <input type="radio" 
                                       :name="'q_' + currentQuestion.id" 
                                       class="option-input hidden"
                                       :value="option.id"
                                       :checked="answers[currentQuestion.id] == option.id"
                                       @change="selectAnswer(currentQuestion.id, option.id)">
                                
                                <div class="option-card p-5 rounded-2xl border border-white/5 bg-white/[0.02] hover:bg-white/[0.05] hover:border-white/10 transition-all duration-200 flex items-start gap-5 group-hover:shadow-lg">
                                    <div class="option-circle w-8 h-8 rounded-lg border border-white/20 bg-white/5 flex items-center justify-center text-sm font-bold text-white/40 shrink-0 mt-0.5 transition-all duration-300">
                                        <span x-text="String.fromCharCode(65 + idx)"></span>
                                    </div>
                                    <div class="text-base text-white/70 group-hover:text-white transition-colors pt-1 leading-relaxed">
                                        <span x-text="option.option_text"></span>
                                    </div>
                                </div>
                            </label>
                        </template>
                    </div>

                </div>
            </div>

            <div class="glass-panel p-4 lg:px-8 absolute bottom-0 w-full z-20">
                <div class="max-w-5xl mx-auto flex items-center justify-between">
                    
                    <button @click="prevQuestion()" 
                            :disabled="currentIndex === 0"
                            class="px-5 py-2.5 rounded-xl border border-white/10 text-white/50 hover:bg-white/5 hover:text-white disabled:opacity-30 disabled:cursor-not-allowed transition-all flex items-center gap-2 font-medium text-sm group">
                        <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
                        <span class="hidden sm:inline">Sebelumnya</span>
                    </button>

                    <button @click="toggleFlag(currentQuestion.id)"
                            class="group px-6 py-2.5 rounded-xl border transition-all flex items-center gap-2.5 font-bold text-sm"
                            :class="flags[currentQuestion.id] 
                                ? 'bg-amber-500/10 border-amber-500/50 text-amber-400 shadow-[0_0_15px_rgba(245,158,11,0.2)]' 
                                : 'bg-white/5 border-white/5 text-white/40 hover:bg-white/10 hover:text-white/60'">
                        <div class="w-4 h-4 rounded border flex items-center justify-center transition-colors"
                             :class="flags[currentQuestion.id] ? 'border-amber-400 bg-amber-400 text-black' : 'border-white/30'">
                             <svg x-show="flags[currentQuestion.id]" class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                        </div>
                        <span>Ragu-ragu</span>
                    </button>

                    <template x-if="currentIndex < questions.length - 1">
                        <button @click="nextQuestion()" 
                                class="px-6 py-2.5 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 hover:from-fuchsia-500 hover:to-purple-500 text-white shadow-lg shadow-purple-900/30 transition-all flex items-center gap-2 font-bold text-sm group">
                            <span class="hidden sm:inline">Selanjutnya</span>
                            <svg class="w-4 h-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                        </button>
                    </template>
                    
                    <template x-if="currentIndex === questions.length - 1">
                        <button @click="confirmSubmit()" 
                                class="px-8 py-2.5 rounded-xl bg-gradient-to-r from-emerald-500 to-teal-500 hover:from-emerald-400 hover:to-teal-400 text-white shadow-lg shadow-emerald-500/30 transition-all flex items-center gap-2 font-bold text-sm animate-pulse">
                            <span>Kumpulkan</span>
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg>
                        </button>
                    </template>

                </div>
            </div>
        </main>

        <aside class="w-full lg:w-[320px] glass-panel border-l-0 lg:border-l border-white/5 flex flex-col shrink-0 lg:h-full z-30 shadow-2xl">
            
            <div class="p-5 border-b border-white/5 bg-white/[0.02]">
                <h3 class="text-[10px] font-bold text-white/30 uppercase tracking-[0.2em] mb-4">Navigasi Soal</h3>
                <div class="grid grid-cols-2 gap-3">
                    <div class="flex items-center gap-2 px-3 py-2 bg-white/5 rounded border border-white/5">
                        <span class="w-2 h-2 rounded-full bg-emerald-500 shadow-[0_0_8px_#10b981]"></span> 
                        <span class="text-[10px] text-white/70 font-bold">DIJAWAB</span>
                    </div>
                    <div class="flex items-center gap-2 px-3 py-2 bg-white/5 rounded border border-white/5">
                        <span class="w-2 h-2 rounded-full bg-amber-500 shadow-[0_0_8px_#f59e0b]"></span> 
                        <span class="text-[10px] text-white/70 font-bold">RAGU</span>
                    </div>
                </div>
            </div>

            <div class="flex-1 overflow-y-auto p-5 custom-scrollbar">
                <div class="grid grid-cols-5 gap-2">
                    <template x-for="(q, index) in questions" :key="q.id">
                        <button @click="goToQuestion(index)"
                            class="relative w-full aspect-square rounded-lg text-sm font-bold transition-all duration-200 flex items-center justify-center shadow-sm"
                            :class="{
                                'bg-fuchsia-600 text-white ring-2 ring-fuchsia-400 ring-offset-2 ring-offset-[#020617] z-10 scale-105': currentIndex === index,
                                'bg-amber-500/20 text-amber-400 border border-amber-500/50 hover:bg-amber-500/30': flags[q.id] && currentIndex !== index,
                                'bg-emerald-500/20 text-emerald-400 border border-emerald-500/50 hover:bg-emerald-500/30': answers[q.id] && !flags[q.id] && currentIndex !== index,
                                'bg-white/5 text-white/30 border border-white/5 hover:bg-white/10 hover:text-white/60': !answers[q.id] && !flags[q.id] && currentIndex !== index
                            }">
                            <span x-text="index + 1"></span>
                            <div x-show="flags[q.id]" class="absolute top-1 right-1 w-1.5 h-1.5 rounded-full bg-amber-400 shadow-[0_0_4px_#fbbf24]"></div>
                        </button>
                    </template>
                </div>
            </div>
            
            <div class="p-4 border-t border-white/5 bg-white/[0.02] text-center">
                 <div class="flex items-center justify-center gap-2 text-[10px] text-red-400/80 font-bold tracking-widest uppercase bg-red-500/5 py-2 rounded border border-red-500/20">
                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 15v2m0 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                    Akses Keluar Dikunci
                </div>
            </div>
        </aside>

    </div>

    <script>
        function cbtApp() {
            return {
                questions: @json($questions),
                savedAnswers: @json($savedAnswers),
                timeLeft: Math.floor({{ $remainingSeconds }}), 
                attemptId: {{ $attemptId }},
                
                currentIndex: 0,
                answers: {},
                flags: {},
                ready: false,
                isBlurred: false,
                timerInterval: null,

                get currentQuestion() { return this.questions[this.currentIndex]; },

                initCBT() {
                    // 1. Restore Data
                    Object.values(this.savedAnswers).forEach(record => {
                        if (record.quiz_option_id) this.answers[record.quiz_question_id] = record.quiz_option_id;
                        if (record.is_flagged) this.flags[record.quiz_question_id] = true;
                    });

                    this.ready = true;
                    this.startTimer();
                    this.activateStrictMode();
                },

                formatTime(seconds) {
                    if (seconds < 0) seconds = 0;
                    const m = Math.floor(seconds / 60);
                    const s = seconds % 60;
                    return `${String(m).padStart(2, '0')}:${String(s).padStart(2, '0')}`;
                },

                startTimer() {
                    this.timerInterval = setInterval(() => {
                        if (this.timeLeft > 0) this.timeLeft--;
                        else this.timeOut();
                    }, 1000);
                },

                nextQuestion() { if (this.currentIndex < this.questions.length - 1) this.currentIndex++; },
                prevQuestion() { if (this.currentIndex > 0) this.currentIndex--; },
                goToQuestion(index) { this.currentIndex = index; },
                
                selectAnswer(qId, oId) {
                    this.answers[qId] = oId;
                    this.saveToDb(qId, oId, this.flags[qId] || false);
                },

                toggleFlag(qId) {
                    if (this.flags[qId]) {
                        delete this.flags[qId];
                        this.saveToDb(qId, this.answers[qId] || null, false);
                    } else {
                        this.flags[qId] = true;
                        this.saveToDb(qId, this.answers[qId] || null, true);
                    }
                },

                saveToDb(qId, oId, isFlagged) {
                    fetch("{{ route('quiz.save-progress') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ attempt_id: this.attemptId, question_id: qId, option_id: oId, is_flagged: isFlagged ? 1 : 0 })
                    }).catch(e => {});
                },

                confirmSubmit() {
                    if(confirm("Apakah Anda yakin ingin mengumpulkan jawaban dan mengakhiri ujian?")) {
                        this.submitQuiz();
                    }
                },

                submitQuiz() {
                    this.disableStrictMode();
                    document.body.innerHTML += `<div class="fixed inset-0 z-[200] bg-[#020617]/95 flex flex-col items-center justify-center text-white"><div class="w-16 h-16 border-4 border-fuchsia-500 border-t-transparent rounded-full animate-spin mb-4 shadow-[0_0_20px_#d946ef]"></div><h2 class="text-xl font-bold tracking-widest uppercase">Menyimpan Jawaban...</h2></div>`;
                    
                    fetch("{{ route('quiz.submit') }}", {
                        method: "POST",
                        headers: { "Content-Type": "application/json", "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').content },
                        body: JSON.stringify({ attempt_id: this.attemptId, time_spent: ({{ $remainingSeconds }} - this.timeLeft) })
                    }).then(res => res.json()).then(data => {
                        window.location.href = data.redirect;
                    });
                },

                timeOut() {
                    clearInterval(this.timerInterval);
                    alert("WAKTU HABIS! Sistem mengumpulkan jawaban otomatis.");
                    this.submitQuiz();
                },

                // === STRICT SECURITY ===
                activateStrictMode() {
                    // Anti-Back Button
                    history.pushState(null, null, location.href);
                    window.onpopstate = () => history.go(1);

                    // Anti-Refresh/Close
                    window.onbeforeunload = (e) => {
                        e.preventDefault();
                        return "Ujian sedang berlangsung!";
                    };

                    // Anti-Switch Tab
                    document.addEventListener("visibilitychange", () => {
                        if (document.hidden) {
                            this.isBlurred = true;
                            document.title = "⚠️ KEMBALI SEGERA!";
                        } else {
                            document.title = "Ujian Bab {{ $chapterId }}";
                        }
                    });

                    // Anti-Context Menu & Keys
                    document.addEventListener('keydown', (e) => {
                        if (e.key === 'F5' || (e.ctrlKey && e.key === 'r')) e.preventDefault();
                    });
                },

                disableStrictMode() {
                    window.onbeforeunload = null;
                }
            }
        }
    </script>
</body>
</html>