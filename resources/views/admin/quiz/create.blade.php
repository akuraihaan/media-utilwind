@extends('layouts.landing')

@section('title', 'Quiz Creator Studio · Utilwind')

@section('content')
{{-- SETUP RESOURCES --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<style>
    /* --- CUSTOM INPUT STYLING --- */
    .glass-input {
        background: rgba(15, 23, 42, 0.6);
        border: 1px solid rgba(255, 255, 255, 0.1);
        color: white;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }
    .glass-input:focus {
        border-color: #8b5cf6;
        background: rgba(15, 23, 42, 0.9);
        box-shadow: 0 0 0 4px rgba(139, 92, 246, 0.15);
    }
    
    /* --- RADIO BUTTON CUSTOM --- */
    input[type="radio"].custom-radio { appearance: none; -webkit-appearance: none; }
    .radio-label { cursor: pointer; transition: all 0.3s; opacity: 0.4; filter: grayscale(100%); }
    
    input[type="radio"]:checked + .radio-label { opacity: 1; filter: grayscale(0%); transform: scale(1.1); }
    input[type="radio"]:checked + .radio-label .check-icon {
        background: #10b981; box-shadow: 0 0 15px #10b981; border-color: #10b981; color: white;
    }

    /* --- ANIMATION --- */
    .preview-bounce { animation: bounceIn 0.5s cubic-bezier(0.175, 0.885, 0.32, 1.275); }
    @keyframes bounceIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    
    /* Custom Scrollbar for Preview */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
</style>

<div class="relative min-h-screen bg-[#020617] text-white font-sans selection:bg-indigo-500/30 pt-24 pb-20 overflow-hidden">

    {{-- Background Atmosphere --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.04] mix-blend-overlay"></div>
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-fuchsia-900/10 rounded-full blur-[100px]"></div>
    </div>

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10 h-full">
        
        {{-- HEADER --}}
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
            <div>
                <a href="{{ route('admin.analytics.questions') }}" class="text-xs font-bold text-white/40 hover:text-white transition uppercase tracking-widest flex items-center gap-2 mb-2">
                    <span>←</span> Kembali ke Analytics
                </a>
                <h1 class="text-3xl md:text-4xl font-black text-white">Quiz <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-400 to-fuchsia-400">Creator Studio</span></h1>
            </div>
            
            <button onclick="submitForm()" class="px-8 py-3 rounded-xl bg-gradient-to-r from-indigo-600 to-fuchsia-600 text-white font-bold shadow-lg shadow-indigo-500/30 hover:scale-105 transition transform flex items-center gap-2 w-full md:w-auto justify-center">
                <span id="btnText">Simpan Kuis</span>
                <svg id="btnLoader" class="hidden w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
            </button>
        </div>

        <div class="grid lg:grid-cols-2 gap-8 h-full">
            
            {{-- KOLOM KIRI: EDITOR FORM --}}
            <div class="bg-[#0f141e]/80 backdrop-blur-xl border border-white/10 rounded-3xl p-8 shadow-2xl h-fit">
                <form id="quizForm" action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    
                    {{-- 1. Metadata --}}
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-white/50 uppercase tracking-wider mb-2">Target Bab</label>
                        <select name="chapter_id" class="w-full glass-input rounded-xl p-3.5 outline-none appearance-none cursor-pointer bg-[#0f141e]" onchange="updatePreview()">
                            <option value="1">Bab 1 - Konsep Dasar Web</option>
                            <option value="2">Bab 2 - Styling Layout</option>
                            <option value="3">Bab 3 - Komponen Interaktif</option>
                            <option value="4">Bab 4 - Advanced Utility</option>
                        </select>
                    </div>

                    {{-- 2. Pertanyaan --}}
                    <div class="mb-8">
                        <label class="block text-xs font-bold text-white/50 uppercase tracking-wider mb-2">Pertanyaan Utama</label>
                        <textarea id="inputQuestion" name="question_text" rows="4" 
                            class="w-full glass-input rounded-xl p-4 outline-none resize-none text-lg font-medium placeholder-white/20" 
                            placeholder="Ketik pertanyaan Anda di sini..." 
                            oninput="updatePreview()"></textarea>
                    </div>

                    {{-- 3. Opsi Jawaban --}}
                    <div class="space-y-4">
                        <div class="flex justify-between items-center mb-2">
                            <label class="block text-xs font-bold text-white/50 uppercase tracking-wider">Opsi Jawaban & Kunci Benar</label>
                            <span class="text-[10px] text-emerald-400 bg-emerald-500/10 px-2 py-1 rounded border border-emerald-500/20">Klik ikon centang untuk set kunci</span>
                        </div>

                        @foreach(['a', 'b', 'c', 'd'] as $opt)
                        <div class="option-row relative flex items-center gap-4 group">
                            {{-- Radio Button Custom (Kunci Jawaban) --}}
                            <div class="relative">
                                <input type="radio" name="correct_answer" value="option_{{ $opt }}" id="radio_{{ $opt }}" class="custom-radio hidden" {{ $opt == 'a' ? 'checked' : '' }} onchange="updatePreview()">
                                <label for="radio_{{ $opt }}" class="radio-label flex flex-col items-center gap-1 w-10">
                                    <div class="check-icon w-8 h-8 rounded-full border-2 border-white/20 flex items-center justify-center transition-all bg-[#020617]">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path d="M5 13l4 4L19 7"/></svg>
                                    </div>
                                    <span class="text-[10px] font-bold uppercase text-white/50">{{ strtoupper($opt) }}</span>
                                </label>
                            </div>

                            {{-- Input Text --}}
                            <div class="flex-1 relative">
                                <input type="text" name="option_{{ $opt }}" id="input_{{ $opt }}"
                                    class="w-full glass-input rounded-xl pl-4 pr-4 py-3 outline-none focus:pl-6 transition-all" 
                                    placeholder="Tulis opsi jawaban {{ strtoupper($opt) }}..." 
                                    oninput="updatePreview()">
                            </div>
                        </div>
                        @endforeach
                    </div>
                </form>
            </div>

            {{-- KOLOM KANAN: LIVE PREVIEW (HP MOCKUP) --}}
            <div class="relative hidden lg:flex justify-center items-start pt-10 sticky top-24">
                
                {{-- Label Preview --}}
                <div class="absolute top-0 right-10 flex items-center gap-2 animate-pulse">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-xs font-bold text-emerald-400 uppercase tracking-widest">Live Preview Student App</span>
                </div>

                {{-- Phone Frame --}}
                <div class="relative w-[380px] h-[750px] bg-[#020617] border-8 border-[#1e293b] rounded-[3rem] shadow-2xl overflow-hidden ring-1 ring-white/10">
                    
                    {{-- Notch --}}
                    <div class="absolute top-0 inset-x-0 h-7 bg-[#1e293b] z-20 flex justify-center">
                        <div class="w-32 h-5 bg-[#020617] rounded-b-xl"></div>
                    </div>
                    
                    {{-- Screen Content --}}
                    <div class="h-full w-full bg-gradient-to-b from-[#0f141e] to-[#020617] p-6 pt-12 flex flex-col overflow-y-auto custom-scrollbar">
                        
                        {{-- Header App --}}
                        <div class="flex justify-between items-center mb-8">
                            <div class="flex items-center gap-2 text-white/50">
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center">←</div>
                            </div>
                            <div class="text-center">
                                <span class="text-[10px] font-bold text-indigo-400 uppercase tracking-widest block" id="previewChapter">BAB 1</span>
                                <span class="text-xs font-bold text-white">Quiz Session</span>
                            </div>
                            <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs">1/10</div>
                        </div>

                        {{-- Question Card --}}
                        <div class="bg-white/5 border border-white/10 rounded-2xl p-6 mb-6 shadow-lg preview-bounce">
                            <p class="text-white font-bold text-lg leading-relaxed" id="previewQuestion">
                                Pratinjau pertanyaan akan muncul di sini secara real-time...
                            </p>
                        </div>

                        {{-- Options List --}}
                        <div class="space-y-3 flex-1">
                            @foreach(['a', 'b', 'c', 'd'] as $opt)
                            <div id="preview_{{ $opt }}" class="p-4 rounded-xl border border-white/10 bg-white/[0.02] flex items-center gap-3 transition-all duration-300">
                                <div class="w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-white/50 shrink-0 uppercase">{{ $opt }}</div>
                                <span class="text-sm text-white/80 preview-text">Opsi {{ strtoupper($opt) }}</span>
                            </div>
                            @endforeach
                        </div>

                        {{-- Button App --}}
                        <div class="mt-6 pt-4 border-t border-white/5">
                            <div class="w-full py-3 bg-indigo-600 rounded-xl text-center text-sm font-bold text-white opacity-50">Lanjut</div>
                        </div>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    // --- LIVE PREVIEW LOGIC ---
    function updatePreview() {
        // 1. Update Chapter
        const chapter = document.querySelector('select[name="chapter_id"]').value;
        document.getElementById('previewChapter').innerText = "BAB " + chapter;

        // 2. Update Question Text
        const qText = document.getElementById('inputQuestion').value;
        const previewQ = document.getElementById('previewQuestion');
        previewQ.innerText = qText || "Pratinjau pertanyaan akan muncul di sini...";
        
        // Animasi ketik
        previewQ.classList.remove('preview-bounce');
        void previewQ.offsetWidth; // trigger reflow
        previewQ.classList.add('preview-bounce');

        // 3. Update Options & Highlight Correct
        const options = ['a', 'b', 'c', 'd'];
        const selectedRadio = document.querySelector('input[name="correct_answer"]:checked');
        const correctVal = selectedRadio ? selectedRadio.value : ''; // "option_a"

        options.forEach(opt => {
            // Get Text
            const inputVal = document.getElementById('input_' + opt).value;
            const previewEl = document.getElementById('preview_' + opt);
            const textEl = previewEl.querySelector('.preview-text');
            
            textEl.innerText = inputVal || "Opsi " + opt.toUpperCase();

            // Handle Highlight Correct Answer in Preview
            if (correctVal === 'option_' + opt) {
                // Style for Correct (Greenish Glow)
                previewEl.className = "p-4 rounded-xl border border-emerald-500/50 bg-emerald-500/10 flex items-center gap-3 transition-all duration-300 shadow-[0_0_15px_rgba(16,185,129,0.1)] scale-[1.02]";
                previewEl.querySelector('div').className = "w-8 h-8 rounded-full bg-emerald-500 flex items-center justify-center text-xs font-bold text-white shrink-0 shadow-lg";
                previewEl.querySelector('div').innerHTML = "✓";
            } else {
                // Style for Normal
                previewEl.className = "p-4 rounded-xl border border-white/10 bg-white/[0.02] flex items-center gap-3 transition-all duration-300 opacity-60";
                previewEl.querySelector('div').className = "w-8 h-8 rounded-full bg-white/10 flex items-center justify-center text-xs font-bold text-white/50 shrink-0 uppercase";
                previewEl.querySelector('div').innerHTML = opt.toUpperCase();
            }
        });
    }

    // --- AJAX SUBMISSION ---
    function submitForm() {
        const form = $('#quizForm');
        // Simple Validation
        if(!form[0].checkValidity()) {
            form[0].reportValidity();
            return;
        }

        // Loading State
        $('#btnText').text('Menyimpan...');
        $('#btnLoader').removeClass('hidden');
        $('button').prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            method: 'POST',
            data: form.serialize(),
            success: function(res) {
                // Success Alert
                Swal.fire({
                    title: 'Berhasil!',
                    text: 'Kuis baru telah ditambahkan ke database.',
                    icon: 'success',
                    background: '#0f141e',
                    color: '#fff',
                    confirmButtonColor: '#4f46e5'
                }).then(() => {
                    // Reset Form & Preview
                    form[0].reset();
                    updatePreview();
                    $('#btnText').text('Simpan Kuis');
                    $('#btnLoader').addClass('hidden');
                    $('button').prop('disabled', false);
                });
            },
            error: function(err) {
                console.error(err);
                Swal.fire({
                    title: 'Gagal!',
                    text: 'Terjadi kesalahan saat menyimpan. Cek koneksi atau input.',
                    icon: 'error',
                    background: '#0f141e',
                    color: '#fff',
                    confirmButtonColor: '#ef4444'
                });
                $('#btnText').text('Simpan Kuis');
                $('#btnLoader').addClass('hidden');
                $('button').prop('disabled', false);
            }
        });
    }

    // Initialize Preview on Load
    document.addEventListener('DOMContentLoaded', updatePreview);
</script>
@endsection