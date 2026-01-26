<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Evaluasi Bab {{ $chapterId }} · TailwindLearn</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #020617; }
        ::-webkit-scrollbar-thumb { background: #4c1d95; border-radius: 10px; }
        
        .no-select { -webkit-user-select: none; user-select: none; }
        .glass { background: rgba(15, 23, 42, 0.6); backdrop-filter: blur(12px); border-bottom: 1px solid rgba(255, 255, 255, 0.05); }
        .glass-panel { background: rgba(30, 41, 59, 0.4); backdrop-filter: blur(12px); border: 1px solid rgba(255, 255, 255, 0.05); }
        
        .q-card { display: none; opacity: 0; transform: translateY(10px); transition: all 0.3s ease-out; }
        .q-card.active { display: block; opacity: 1; transform: translateY(0); }
        
        /* Radio Button Logic */
        input:checked + div { border-color: #d946ef; background: linear-gradient(to right, rgba(217, 70, 239, 0.1), transparent); }
        input:checked + div .indicator { transform: scale(1); }
        input:checked + div .opt-text { color: #fff; font-weight: 600; }
        
        .nav-btn.active { border-color: #d946ef; color: white; background: rgba(217, 70, 239, 0.1); }
        .nav-btn.answered { background: #d946ef; color: white; border-color: #d946ef; }
    </style>
</head>
<body class="bg-[#020617] text-white h-screen flex flex-col overflow-hidden no-select selection:bg-fuchsia-500/30">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute inset-0 bg-[radial-gradient(ellipse_at_top,_var(--tw-gradient-stops))] from-fuchsia-900/20 via-[#020617] to-[#020617]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-5"></div>
    </div>

    <header class="h-20 glass flex items-center justify-between px-6 lg:px-10 shrink-0 z-50">
        <div class="flex items-center gap-5">
            <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-fuchsia-600 to-purple-600 flex items-center justify-center font-black text-lg shadow-lg shadow-purple-500/20">Q</div>
            <div>
                <h1 class="font-bold text-lg tracking-wide text-white">Evaluasi Bab {{ $chapterId }}</h1>
                <div class="flex items-center gap-2 text-xs text-white/40">
                    <span class="flex items-center gap-1"><span class="w-2 h-2 rounded-full bg-green-500 animate-pulse"></span> Auto-Save Aktif</span>
                </div>
            </div>
        </div>

        <div class="flex items-center gap-4 bg-[#0f172a]/80 border border-white/10 px-5 py-2.5 rounded-full shadow-2xl relative overflow-hidden group">
            <div class="absolute inset-0 bg-red-500/10 w-0 transition-all duration-1000" id="timer-progress"></div>
            <div class="relative flex items-center gap-3 z-10">
                <span class="text-[10px] uppercase text-red-400 font-bold tracking-widest mb-0.5">Sisa Waktu</span>
                <span id="timer" class="text-xl font-mono font-bold text-white tracking-widest">--:--</span>
            </div>
        </div>
    </header>

    <div class="flex flex-1 overflow-hidden">
        
        <aside class="w-80 glass border-r-0 border-r border-white/5 p-6 hidden lg:flex flex-col z-40">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest">Peta Soal</h3>
                <span class="text-xs text-fuchsia-400 font-mono bg-fuchsia-500/10 px-2 py-1 rounded" id="answered-count">0/{{ $questions->count() }}</span>
            </div>
            
            <div class="grid grid-cols-5 gap-3 content-start">
                @foreach($questions as $index => $q)
                    <button onclick="jumpTo({{ $index }})" id="nav-btn-{{ $index }}" 
                        class="nav-btn h-12 w-full rounded-lg border border-white/10 hover:border-white/30 hover:bg-white/5 transition flex items-center justify-center text-sm font-bold text-white/40 relative group">
                        {{ $index + 1 }}
                    </button>
                @endforeach
            </div>
            
            <div class="mt-auto pt-6 border-t border-white/10">
                <button onclick="confirmSubmit()" class="w-full py-3.5 rounded-xl bg-white text-black font-bold text-sm shadow-lg hover:bg-gray-200 transition active:scale-95 flex items-center justify-center gap-2">
                    <span>Selesai & Kumpulkan</span>
                </button>
            </div>
        </aside>

        <main class="flex-1 relative overflow-y-auto custom-scrollbar p-6 lg:p-12 flex flex-col items-center">
            
            <div class="w-full max-w-4xl mb-8">
                <div class="h-1 w-full bg-white/5 rounded-full overflow-hidden">
                    <div id="progress-bar" class="h-full bg-gradient-to-r from-fuchsia-500 to-purple-500 w-0 transition-all duration-500 shadow-[0_0_10px_#d946ef]"></div>
                </div>
            </div>

            <div class="w-full max-w-4xl flex-1 flex flex-col justify-center pb-20">
                @foreach($questions as $index => $q)
                    <div id="q-card-{{ $index }}" class="q-card {{ $index === 0 ? 'active' : '' }}">
                        <div class="mb-10">
                            <span class="inline-flex items-center gap-2 px-3 py-1 rounded-full bg-fuchsia-500/10 text-fuchsia-300 text-xs font-bold mb-6 border border-fuchsia-500/20">
                                <span>No. {{ $index + 1 }}</span>
                            </span>
                            <h2 class="text-2xl lg:text-3xl font-bold leading-relaxed text-gray-100 selection:bg-fuchsia-500/40">
                                {{ $q->question_text }}
                            </h2>
                        </div>

                        <div class="space-y-4">
                            @foreach($q->options as $opt)
                                <label class="cursor-pointer block relative group">
                                    <input type="radio" name="q_{{ $q->id }}" value="{{ $opt->id }}" class="hidden" 
                                           onchange="saveAnswer({{ $index }}, {{ $q->id }}, {{ $opt->id }})">
                                    
                                    <div class="glass-panel flex items-center p-5 rounded-2xl hover:bg-white/5 transition-all duration-300 group-hover:border-white/20">
                                        <div class="w-6 h-6 rounded-full border-2 border-white/20 mr-5 flex items-center justify-center shrink-0 transition-colors group-hover:border-white/40">
                                            <div class="indicator w-3 h-3 rounded-full bg-fuchsia-500 scale-0 transition-transform duration-200"></div>
                                        </div>
                                        <span class="opt-text text-base text-gray-400 transition-colors leading-snug">
                                            {{ $opt->option_text }}
                                        </span>
                                    </div>
                                </label>
                            @endforeach
                        </div>
                    </div>
                @endforeach
            </div>

            <div class="fixed bottom-0 left-0 lg:left-80 right-0 p-6 bg-gradient-to-t from-[#020617] to-transparent z-40 flex justify-center pointer-events-none">
                <div class="w-full max-w-4xl flex justify-between items-center pointer-events-auto">
                    <button onclick="prevQuestion()" id="btn-prev" class="px-6 py-3 rounded-xl glass text-white/50 hover:text-white hover:bg-white/10 transition disabled:opacity-0 disabled:cursor-not-allowed flex items-center gap-2 backdrop-blur-xl">
                        <span>Sebelumnya</span>
                    </button>
                    <button onclick="nextQuestion()" id="btn-next" class="px-8 py-3 rounded-xl bg-fuchsia-600 text-white font-bold hover:bg-fuchsia-500 transition shadow-[0_0_30px_rgba(192,38,211,0.3)] flex items-center gap-2 backdrop-blur-xl">
                        <span>Selanjutnya</span>
                    </button>
                </div>
            </div>
        </main>
    </div>

    <div id="loadingModal" class="fixed inset-0 z-[110] bg-[#020617]/90 flex flex-col items-center justify-center hidden backdrop-blur-sm">
        <div class="w-16 h-16 border-4 border-fuchsia-600 border-t-transparent rounded-full animate-spin mb-4"></div>
        <p class="text-white font-bold animate-pulse" id="loading-text">Menyimpan Jawaban...</p>
        <p class="text-white/40 text-xs mt-2" id="error-details" style="display:none;"></p>
        <button onclick="closeLoading()" id="close-error-btn" class="mt-4 px-4 py-2 bg-white/10 rounded hidden">Tutup</button>
    </div>

</body>

<script>
    // --- 1. CONFIGURATION ---
    const TOTAL_Q = {{ $questions->count() }};
    const ATTEMPT_ID = {{ $attemptId }};
    const USER_ID = {{ Auth::id() }};
    const STORAGE_KEY = `quiz_draft_${ATTEMPT_ID}_${USER_ID}`;
    
    let remainingSeconds = parseInt("{{ $remainingSeconds }}", 10); 
    let currentIndex = 0;
    let answers = {}; 
    let timerInterval;
    let isSubmitting = false;

    $(document).ready(() => {
        // A. Load Draft from LocalStorage
        loadDraft();
        
        startTimer();
        updateUI();
        preventNavigation();
    });

    // --- 2. LOCAL STORAGE LOGIC (AUTO SAVE) ---
    function loadDraft() {
        const saved = localStorage.getItem(STORAGE_KEY);
        if (saved) {
            try {
                answers = JSON.parse(saved);
                
                // Restore UI based on loaded answers
                $.each(answers, function(qId, optId) {
                    // Find the radio button and check it
                    $(`input[name="q_${qId}"][value="${optId}"]`).prop('checked', true);
                    
                    // Find which index this question belongs to (for sidebar highlighting)
                    // Note: This assumes simple looping. If random, might need more robust logic.
                    // But visual update based on `answers` keys is enough for sidebar.
                });
                
                // Refresh Sidebar Visuals
                refreshSidebarStatus();
                
            } catch (e) {
                console.error("Gagal memuat draft", e);
            }
        }
    }

    function saveToDraft() {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(answers));
    }

    function clearDraft() {
        localStorage.removeItem(STORAGE_KEY);
    }

    function refreshSidebarStatus() {
        // Iterate all questions to see if they are in answers object
        for(let i=0; i < TOTAL_Q; i++) {
            // Check if input inside q-card-i is checked
            let isAnswered = $(`#q-card-${i} input:checked`).length > 0;
            if(isAnswered) {
                $(`#nav-btn-${i}`).addClass('answered').removeClass('text-white/40');
            }
        }
        
        let count = Object.keys(answers).length;
        $('#answered-count').text(`${count}/${TOTAL_Q}`);
        let pct = (count / TOTAL_Q) * 100;
        $('#progress-bar').css('width', `${pct}%`);
    }

    // --- 3. LOGIC UTAMA ---
    function startTimer() {
        updateTimerDisplay();
        timerInterval = setInterval(() => {
            remainingSeconds--;
            updateTimerDisplay();
            if (remainingSeconds <= 0) {
                clearInterval(timerInterval);
                submitQuiz(true);
            }
        }, 1000);
    }

    function updateTimerDisplay() {
        let secureSeconds = Math.max(0, remainingSeconds);
        let m = Math.floor(secureSeconds / 60).toString().padStart(2, '0');
        let s = (secureSeconds % 60).toString().padStart(2, '0');
        $('#timer').text(`${m}:${s}`);
        if (secureSeconds < 60) {
            $('#timer').addClass('text-red-500 animate-pulse');
            $('#timer-progress').addClass('bg-red-500/20 w-full');
        }
    }

    function showQuestion(idx) {
        $('.q-card').removeClass('active');
        setTimeout(() => { $(`#q-card-${idx}`).addClass('active'); }, 50);
        currentIndex = idx;
        updateUI();
    }

    function nextQuestion() {
        if (currentIndex < TOTAL_Q - 1) showQuestion(currentIndex + 1);
        else submitQuiz(false); // Confirm first
    }

    function prevQuestion() {
        if (currentIndex > 0) showQuestion(currentIndex - 1);
    }

    function jumpTo(idx) { showQuestion(idx); }

    function updateUI() {
        $('#btn-prev').prop('disabled', currentIndex === 0).toggleClass('opacity-0', currentIndex === 0);
        
        if (currentIndex === TOTAL_Q - 1) {
            $('#btn-next').html('<span>Selesai</span>').removeClass('bg-fuchsia-600').addClass('bg-white text-black hover:bg-gray-200');
        } else {
            $('#btn-next').html('<span>Selanjutnya</span>').addClass('bg-fuchsia-600').removeClass('bg-white text-black hover:bg-gray-200');
        }

        $('.nav-btn').removeClass('active');
        $(`#nav-btn-${currentIndex}`).addClass('active');
    }

    // --- 4. SAVE ANSWER ---
    window.saveAnswer = function(idx, qId, optId) {
        answers[qId] = optId; // Update Memory
        saveToDraft(); // Update LocalStorage
        
        // Update Visuals
        $(`#nav-btn-${idx}`).addClass('answered').removeClass('text-white/40');
        refreshSidebarStatus();
    }

    // --- 5. SUBMIT & ERROR HANDLING ---
    window.confirmSubmit = function() {
        if(confirm("Yakin ingin mengumpulkan jawaban?")) {
            submitQuiz();
        }
    }

    window.submitQuiz = function(force = false) {
        isSubmitting = true;
        clearInterval(timerInterval);
        
        $('#loadingModal').fadeIn(200).css('display', 'flex');
        $('#loading-text').text("Mengirim ke Server...");
        $('#error-details').hide();
        $('#close-error-btn').hide();

        let totalDuration = 20 * 60; 
        let timeSpent = totalDuration - remainingSeconds;

        $.ajax({
            url: "{{ route('quiz.submit') }}",
            method: "POST",
            data: {
                _token: $('meta[name="csrf-token"]').attr('content'),
                attempt_id: ATTEMPT_ID,
                answers: answers,
                time_spent: timeSpent
            },
            success: function(res) {
                clearDraft(); // Hapus draft jika sukses
                window.location.href = res.redirect;
            },
            error: function(xhr, status, error) {
                isSubmitting = false; // Allow exit to try again
                console.error("Submit Error:", xhr.responseText);
                
                // Tampilkan Error di Modal
                $('#loading-text').text("Gagal Mengirim!").addClass('text-red-500');
                $('#error-details').text("Error: " + xhr.status + " - " + error).show();
                $('#close-error-btn').show();
                
                startTimer(); // Lanjutkan timer agar user tidak panik
            }
        });
    }

    window.closeLoading = function() {
        $('#loadingModal').fadeOut();
    }

    function preventNavigation() {
        history.pushState(null, null, location.href);
        window.onpopstate = function () { history.go(1); };
        window.onbeforeunload = function() {
            if (!isSubmitting) return "Jawaban belum dikirim!";
        };
    }
</script>
</html>