@php
    // -------------------------------------------------------------------------
    // SERVER-SIDE DATA PREPARATION
    // -------------------------------------------------------------------------
    // Kita format data dari Eloquent ke Array bersih agar mudah dibaca AlpineJS
    // Ini menghindari error parsing JSON jika ada karakter aneh di database
    $stepsData = $lab->steps->map(function($s) {
        return [
            'id' => $s->id,
            'index' => $s->order_index,
            'title' => $s->title,
            'instruction' => $s->instruction,
            'points' => $s->points,
            // Pastikan initial_code tidak null (fallback string kosong)
            'initial_code' => $s->initial_code ?? '' 
        ];
    })->values(); // Reset keys agar jadi array JSON murni [0, 1, 2...]
@endphp

<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lab->title }} | Pro Workspace</title>
    
    {{-- LIBRARIES --}}
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    {{-- ACE EDITOR --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/mode-html.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/theme-one_dark.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ext-language_tools.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ext-emmet.js"></script>

    {{-- FONTS --}}
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'] },
                    colors: {
                        vscode: {
                            bg: '#1e1e1e',       // Editor Main BG
                            sidebar: '#252526',  // Sidebar BG
                            activity: '#333333', // Top Header
                            panel: '#1e1e1e',    // Bottom Panel
                            border: '#3e3e42',   // Borders
                            accent: '#007acc',   // Blue Highlight
                            text: '#cccccc',     // Default Text
                            active: '#37373d',   // Active List Item
                            success: '#4ec9b0',  // Green Success
                            warning: '#cca700',  // Yellow Warning
                            error: '#f14c4c'     // Red Error
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #1e1e1e; color: #cccccc; overflow: hidden; }
        .ace_editor { font-family: 'JetBrains Mono', monospace !important; line-height: 1.5; }
        .ace_gutter { background: #1e1e1e !important; color: #858585 !important; }
        
        /* Scrollbar ala VS Code */
        ::-webkit-scrollbar { width: 10px; height: 10px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #424242; }
        ::-webkit-scrollbar-thumb:hover { background: #4f4f4f; }
        
        .step-locked { opacity: 0.5; pointer-events: none; filter: grayscale(1); }
        .active-task { background-color: #37373d; border-left: 2px solid #007acc; }
        
        /* Toast Animation */
        .toast-enter { animation: slideIn 0.3s ease-out forwards; }
        @keyframes slideIn { from { transform: translateX(100%); opacity: 0; } to { transform: translateX(0); opacity: 1; } }
        
        [x-cloak] { display: none !important; }
    </style>
</head>

{{-- ALPINE DATA INIT --}}
<body x-data="labApp()" x-init="init()" 
      @keydown.window.ctrl.s.prevent="!readOnly && manualSave()" 
      class="flex flex-col h-screen w-full antialiased text-sm">

    {{-- 1. HEADER (ACTIVITY BAR) --}}
    <header class="h-12 bg-[#333333] flex items-center justify-between px-4 shrink-0 select-none border-b border-[#1e1e1e]">
        <div class="flex items-center gap-4">
            <button @click="goBack()" class="hover:text-white transition text-gray-400" title="Back to Dashboard">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex items-center gap-2">
                <svg class="w-4 h-4 text-[#007acc]" viewBox="0 0 24 24" fill="currentColor"><path d="M14.6 2.6c-.6-.4-1.4-.4-2 0L2.1 8.8c-.6.4-.6 1.2 0 1.6l10.5 6.3c.6.4 1.4.4 2 0l10.5-6.3c.6-.4.6-1.2 0-1.6L14.6 2.6zM13.6 18l-8-4.8v3.2l8 4.8 8-4.8v-3.2l-8 4.8z"/></svg>
                <span class="font-bold text-white tracking-wide text-xs uppercase">{{ $lab->title }}</span>
            </div>
        </div>

        {{-- Center Actions --}}
        <div class="hidden md:flex items-center gap-2">
            <button @click="manualSave()" class="flex items-center gap-2 px-3 py-1 bg-[#3e3e42] hover:bg-[#4e4e52] rounded text-xs text-white transition border border-white/5">
                <svg class="w-3 h-3 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Simpan & Run (Ctrl+S)
            </button>
        </div>

        {{-- Right Stats --}}
        <div class="flex items-center gap-4">
            <div class="flex items-center gap-2 px-3 py-1 bg-[#1e1e1e] rounded border border-[#3e3e42]">
                <span class="text-xs text-gray-400">Score:</span>
                <span class="font-mono font-bold text-[#007acc]" x-text="Math.round(score) + '%'">0%</span>
            </div>
            
            <div class="font-mono font-bold text-xs" :class="timeCritical ? 'text-red-400 animate-pulse' : 'text-gray-300'">
                <span x-text="timer">--:--</span>
            </div>

            <button @click="confirmFinish()" 
                class="px-4 py-1.5 rounded text-xs font-bold text-white transition border border-transparent shadow-lg"
                :class="readOnly ? 'bg-gray-600 hover:bg-gray-500' : 'bg-[#007acc] hover:bg-[#0062a3]'">
                <span x-text="readOnly ? 'Keluar' : 'Selesai & Kumpulkan'"></span>
            </button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden">
        
        {{-- 2. SIDEBAR (TASK LIST) --}}
        <aside class="w-80 bg-vscode-sidebar border-r border-vscode-border flex flex-col z-20">
            <div class="h-9 px-4 flex items-center justify-between text-[10px] font-bold text-gray-400 tracking-wider uppercase bg-[#252526]">
                <span>DAFTAR TUGAS</span>
                <span class="text-[9px] bg-[#3e3e42] px-1.5 rounded text-white" x-text="completed.length + '/' + stepsData.length"></span>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar">
                <template x-for="step in stepsData" :key="step.id">
                    <div class="border-b border-[#3e3e42]/50 group" :class="isLocked(step.id) ? 'step-locked' : ''">
                        
                        {{-- Task Header Clickable --}}
                        <div @click="toggleTask(step.id)" 
                             class="px-4 py-3 cursor-pointer flex items-center gap-2 hover:bg-[#2a2d2e] border-l-2 transition-all"
                             :class="expandedTask === step.id ? 'active-task' : 'border-transparent'">
                            
                            {{-- Icon Status --}}
                            <div class="shrink-0">
                                <template x-if="isCompleted(step.id)">
                                    <svg class="w-4 h-4 text-[#4ec9b0]" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                                </template>
                                <template x-if="!isCompleted(step.id)">
                                    <div class="w-4 h-4 rounded-sm border border-gray-500 flex items-center justify-center text-[9px] font-mono text-gray-400" x-text="step.index"></div>
                                </template>
                            </div>

                            <div class="flex-1 min-w-0">
                                <div class="text-xs truncate font-medium group-hover:text-white" 
                                     :class="expandedTask === step.id ? 'text-white' : 'text-gray-400'" 
                                     x-text="step.title"></div>
                            </div>
                        </div>

                        {{-- Task Detail (Accordion) --}}
                        <div x-show="expandedTask === step.id" x-collapse class="bg-[#1e1e1e] p-4 border-l border-[#007acc]/30">
                            {{-- Instruksi --}}
                            <div class="p-3 rounded bg-[#252526] border border-[#3e3e42] mb-3">
                                <p class="text-xs text-gray-300 leading-relaxed font-mono whitespace-pre-wrap" x-html="step.instruction"></p>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                {{-- Tombol Reset Code --}}
                                <button @click="forceLoadCode(step.id)" x-show="!isCompleted(step.id) && !readOnly" 
                                        class="text-[10px] text-gray-500 hover:text-[#cca700] underline decoration-dashed transition">
                                    Reset Code
                                </button>

                                {{-- Tombol Validasi --}}
                                <template x-if="!readOnly">
                                    <button @click="checkTask(step.id)" 
                                            :disabled="loadingId === step.id || isCompleted(step.id)"
                                            class="px-3 py-1.5 text-[10px] font-bold rounded border transition flex items-center gap-2 ml-auto shadow-lg"
                                            :class="isCompleted(step.id) 
                                                ? 'bg-[#4ec9b0]/10 text-[#4ec9b0] border-[#4ec9b0]/30 cursor-default' 
                                                : 'bg-[#007acc] text-white border-transparent hover:bg-[#0062a3]'">
                                        <span x-show="loadingId === step.id" class="animate-spin w-3 h-3 border-2 border-white border-t-transparent rounded-full"></span>
                                        <span x-text="isCompleted(step.id) ? 'Selesai' : 'Verifikasi'"></span>
                                    </button>
                                </template>
                            </div>
                        </div>
                    </div>
                </template>
            </div>
        </aside>

        {{-- 3. EDITOR AREA --}}
        <main class="flex-1 flex flex-col relative bg-[#1e1e1e]">
            {{-- Tabs --}}
            <div class="h-9 flex bg-[#252526] border-b border-[#252526]">
                <div class="px-3 py-2 text-xs text-white bg-[#1e1e1e] border-t-2 border-[#007acc] flex items-center gap-2 pr-6 border-r border-[#252526]">
                    <svg class="w-3.5 h-3.5 text-[#e37933]" viewBox="0 0 24 24" fill="currentColor"><path d="M1.5 0h21l-1.91 21.563L11.977 24l-8.564-2.438L1.5 0zm7.031 9.75l-.232-2.718 10.059.003.23-2.622L5.412 4.41l.698 8.01h9.126l-.325 3.426-2.91.804-2.955-.81-.188-2.11H6.248l.33 4.171L12 19.351l5.379-1.443.744-8.157H8.531z"/></svg>
                    <span>index.html</span>
                    <span x-show="unsaved" class="ml-2 w-2 h-2 rounded-full bg-white animate-pulse"></span>
                </div>
            </div>

            {{-- Editor Instance --}}
            <div class="flex-1 relative group">
                <div id="editor-container" class="absolute inset-0"></div>
                
                {{-- Read Only Overlay --}}
                <div x-show="readOnly" class="absolute inset-0 z-10 flex items-center justify-center bg-black/50 backdrop-blur-[1px] pointer-events-none">
                    <span class="bg-black px-4 py-2 rounded border border-[#4ec9b0] text-[#4ec9b0] font-mono text-xs shadow-xl">READ ONLY MODE</span>
                </div>
            </div>
        </main>

        {{-- 4. RIGHT PANEL --}}
        <div class="w-[45%] flex flex-col border-l border-vscode-border bg-[#1e1e1e]">
            
            {{-- Live Preview --}}
            <div class="flex-1 flex flex-col relative bg-white transition-all">
                <div class="h-9 px-3 flex items-center justify-between bg-[#f3f3f3] border-b border-[#e1e1e1]">
                    <span class="text-[10px] font-bold text-[#555] flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> LOCALHOST:8000
                    </span>
                    <button @click="runCode()" class="text-[#555] hover:text-black" title="Refresh Preview"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                </div>
                <iframe id="preview-frame" class="flex-1 w-full h-full border-0 bg-white"></iframe>
            </div>

            {{-- Terminal --}}
            <div class="bg-[#1e1e1e] border-t border-vscode-border flex flex-col transition-all duration-300" :style="terminalOpen ? 'height: 180px' : 'height: 30px'">
                <div class="h-8 px-4 flex items-center justify-between bg-[#1e1e1e] border-b border-vscode-border cursor-pointer hover:bg-[#2a2d2e]" @click="terminalOpen = !terminalOpen">
                    <div class="flex gap-6 text-[10px] uppercase font-bold text-gray-400">
                        <span class="text-white border-b border-white pb-1">Terminal</span>
                        <span>Output</span>
                    </div>
                    <svg class="w-3.5 h-3.5 text-white transition-transform" :class="!terminalOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                
                <div x-show="terminalOpen" class="flex-1 p-3 font-mono text-xs overflow-y-auto" id="terminal-logs">
                    <template x-for="log in logs">
                        <div class="mb-1 flex gap-3">
                            <span class="text-gray-500 select-none w-14 shrink-0" x-text="log.time"></span>
                            <span :class="log.color" x-html="log.msg"></span>
                        </div>
                    </template>
                    <div class="flex gap-2 mt-2">
                        <span class="text-[#007acc]">➜</span>
                        <span class="text-white">~</span>
                        <span class="text-gray-400 animate-pulse">_</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- FOOTER --}}
    <footer class="h-6 bg-[#007acc] text-white flex items-center justify-between px-3 text-[10px] select-none shrink-0 font-medium">
        <div class="flex gap-4">
            <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg> master*</span>
        </div>
        <div class="flex gap-4">
            <span>Ln 1, Col 1</span>
            <span>UTF-8</span>
            <span>HTML</span>
        </div>
    </footer>

    {{-- TOAST NOTIFICATION --}}
    <div x-show="showToast" x-transition:enter="toast-enter" class="fixed bottom-10 right-5 z-50 bg-[#252526] border border-[#3e3e42] shadow-2xl p-4 w-72 rounded flex gap-3 items-start">
        <div :class="toastType === 'success' ? 'text-[#4ec9b0]' : (toastType === 'info' ? 'text-[#007acc]' : 'text-[#f14c4c]')">
            <svg x-show="toastType === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <svg x-show="toastType === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <svg x-show="toastType === 'info'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1">
            <h4 class="text-white text-xs font-bold mb-1" x-text="toastTitle">Notification</h4>
            <p class="text-gray-400 text-xs" x-text="toastMsg"></p>
        </div>
        <button @click="showToast = false" class="text-gray-500 hover:text-white">✕</button>
    </div>

    {{-- MODAL ALERT --}}
    <div x-show="modal.open" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/50 backdrop-blur-sm p-4 pop-in" style="display: none;">
        <div class="bg-[#252526] border border-[#454545] w-full max-w-sm rounded shadow-2xl p-0 overflow-hidden">
            <div class="bg-[#333333] px-4 py-2 border-b border-[#454545] flex justify-between items-center">
                <span class="text-xs font-bold text-white uppercase" x-text="modal.title">INFO</span>
                <button @click="modal.open = false" class="text-gray-400 hover:text-white">✕</button>
            </div>
            <div class="p-6 text-center">
                <div class="text-4xl mb-3" x-text="modal.icon"></div>
                <p class="text-sm text-gray-300" x-text="modal.msg"></p>
            </div>
            <div class="p-4 flex gap-2 justify-center bg-[#2d2d2d] border-t border-[#454545]">
                <button @click="modal.open = false" class="px-4 py-1.5 rounded text-white text-xs border border-gray-500 hover:bg-gray-700">Cancel</button>
                <button x-show="modal.action" @click="handleModalAction()" class="px-4 py-1.5 rounded text-white text-xs bg-[#007acc] hover:bg-[#0062a3]">OK</button>
            </div>
        </div>
    </div>

    <script>
        function labApp() {
            return {
                // --- STATE DATA ---
                readOnly: {{ $session->status === 'completed' ? 'true' : 'false' }},
                score: {{ $session->current_score ?? 0 }},
                completed: @json(array_map('intval', $completedStepIds)),
                
                // Data Steps dari PHP (Server Side Transformed)
                stepsData: @json($stepsData),
                
                code: @json($session->current_code ?? ''),
                expiry: {{ $session->expires_at ? \Carbon\Carbon::parse($session->expires_at)->timestamp : 0 }},
                
                // --- UI CONTROL ---
                terminalOpen: true, previewOpen: true, expandedTask: null, loadingId: null,
                unsaved: false, showToast: false, toastTitle: '', toastMsg: '', toastType: 'info',
                logs: [{time: 'INIT', msg: 'Environment loaded successfully.', color: 'text-[#4ec9b0]'}],
                timer: 'LOADING', timeCritical: false,
                modal: { open: false, title: '', msg: '', icon: '', action: null },

                // --- INIT SEQUENCE ---
                init() {
                    this.log('System', 'Initializing VS Code Workspace...', 'text-gray-500');
                    
                    // 1. Tentukan Task Aktif (Task pertama yang belum selesai)
                    const firstUnfinished = this.stepsData.find(s => !this.completed.includes(s.id));
                    this.expandedTask = firstUnfinished ? firstUnfinished.id : this.stepsData[0].id;

                    // 2. Setup Editor
                    this.initEditor();

                    // 3. Fallback Initial Code jika user baru masuk
                    // Jika kode kosong, ambil initial_code dari task yang aktif
                    if(!this.code || this.code.trim() === '') {
                        const currentStep = this.stepsData.find(s => s.id === this.expandedTask);
                        if(currentStep && currentStep.initial_code) {
                            this.code = currentStep.initial_code;
                            this.editor.setValue(this.code, -1);
                            this.log('System', 'Initial code template loaded.', 'text-[#cca700]');
                        }
                    }

                    // 4. Start Timer
                    if(!this.readOnly) {
                        this.startTimer();
                    } else {
                        this.timer = "FINISHED";
                        this.log('System', 'Review Mode Enabled.', 'text-[#007acc]');
                    }
                    
                    // 5. Initial Run
                    this.$nextTick(() => this.runCode());
                },

                initEditor() {
                    ace.require("ace/ext/language_tools");
                    this.editor = ace.edit("editor-container");
                    this.editor.setTheme("ace/theme/one_dark");
                    this.editor.session.setMode("ace/mode/html");
                    this.editor.setOptions({ 
                        fontSize: "14px", fontFamily: "JetBrains Mono", 
                        showPrintMargin: false, wrap: true, readOnly: this.readOnly,
                        enableBasicAutocompletion: true, enableLiveAutocompletion: true, enableEmmet: true 
                    });
                    
                    this.editor.setValue(this.code, -1);
                    this.editor.session.on('change', () => { 
                        this.code = this.editor.getValue();
                        if(!this.readOnly) this.unsaved = true;
                    });
                },

                // --- CORE LOGIC: CHECK & AUTO NEXT ---
                async checkTask(stepId) {
                    this.loadingId = stepId;
                    this.log('Runner', `Verifying Task #${stepId}...`, 'text-[#007acc]');
                    
                    try {
                        const res = await fetch('{{ route('lab.check', $session->id ?? 0) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ step_id: stepId, source_code: this.code })
                        });
                        const data = await res.json();

                        if (data.status === 'success') {
                            // 1. Update State Lokal
                            if (!this.completed.includes(stepId)) {
                                this.completed.push(stepId);
                                this.score = data.new_score;
                            }
                            
                            this.log('Runner', `[SUCCESS] ${data.output}`, 'text-[#4ec9b0]');
                            this.triggerToast('Success', 'Jawaban Anda benar! Lanjut ke task berikutnya.', 'success');

                            // 2. AUTO NEXT TASK TRIGGER
                            this.handleNextTask(stepId);

                        } else {
                            this.log('Runner', `[FAIL] ${data.message}`, 'text-[#f14c4c]');
                            this.triggerToast('Failed', data.message, 'error');
                        }
                    } catch (e) {
                        this.log('System', 'Network Error: ' + e.message, 'text-[#f14c4c]');
                    }
                    this.loadingId = null;
                },

                // --- LOGIC PINDAH TASK OTOMATIS & INJECT KODE ---
                handleNextTask(currentStepId) {
                    const currentIndex = this.stepsData.findIndex(s => s.id === currentStepId);
                    
                    // Cek jika ada task selanjutnya
                    if (currentIndex < this.stepsData.length - 1) {
                        const nextStep = this.stepsData[currentIndex + 1];
                        
                        // 1. Pindah Fokus Sidebar
                        this.expandedTask = nextStep.id;

                        // 2. Cek & Inject Initial Code (OTOMATIS)
                        // Jika task selanjutnya punya initial code spesifik, timpa editor
                        if (nextStep.initial_code && nextStep.initial_code.trim() !== "") {
                            this.log('System', `Loading environment for Task: ${nextStep.title}...`, 'text-gray-400');
                            
                            setTimeout(() => {
                                this.code = nextStep.initial_code;
                                this.editor.setValue(this.code, -1);
                                this.runCode(); 
                                
                                this.log('System', 'New boilerplate code injected.', 'text-[#cca700]');
                                this.triggerToast('Environment Updated', 'Kode baru untuk task ini telah dimuat.', 'info');
                            }, 1000); 
                        }
                    } else {
                        // Jika task habis
                        this.log('System', 'All tasks completed. Ready to submit.', 'text-[#4ec9b0]');
                        this.confirmFinish();
                    }
                },

                // --- UTILS ---
                checkCurrentTask() { if (this.expandedTask && !this.isCompleted(this.expandedTask)) this.checkTask(this.expandedTask); },
                
                forceLoadCode(stepId) {
                    if(!confirm("Reset kode editor ke template awal task ini? Kode Anda akan hilang.")) return;
                    const step = this.stepsData.find(s => s.id === stepId);
                    if(step && step.initial_code) {
                        this.code = step.initial_code;
                        this.editor.setValue(this.code, -1);
                        this.runCode();
                        this.log('System', 'Code reset to template.', 'text-[#cca700]');
                    }
                },

                runCode() {
                    const frame = document.getElementById('preview-frame').contentDocument;
                    const tailwind = '<script src="https://cdn.tailwindcss.com"><\/script>';
                    let content = this.code;
                    
                    // Smart Wrap: Jika user nulis div doang, bungkus body
                    if(!content.includes('<html')) {
                        content = `<!DOCTYPE html><html><head>${tailwind}</head><body class="bg-gray-100 p-4">${content}</body></html>`;
                    } else if(!content.includes('cdn.tailwindcss.com')) {
                        content = content.replace('<head>', '<head>' + tailwind);
                    }

                    frame.open(); frame.write(content); frame.close();
                },

                startTimer() {
                    const interval = setInterval(() => {
                        const now = Math.floor(Date.now() / 1000);
                        const diff = this.expiry - now;
                        
                        if (diff <= 0) { 
                            this.timer = "00:00"; clearInterval(interval); 
                            window.location.href = "{{ route('dashboard') }}"; 
                            return; 
                        }
                        
                        if (diff < 60) this.timeCritical = true; // Blink red last minute

                        const m = Math.floor(diff / 60).toString().padStart(2, '0');
                        const s = (diff % 60).toString().padStart(2, '0');
                        this.timer = `${m}:${s}`;
                    }, 1000);
                },

                confirmFinish() {
                    if (this.readOnly) return this.goBack();
                    const msg = this.score < 50 ? `Skor Anda baru ${this.score}%. Yakin ingin mengumpulkan?` : `Skor Anda ${this.score}%. Siap dikumpulkan?`;
                    this.modal = { open: true, icon: '🚀', title: 'Submit Lab?', msg: msg, action: 'submit' };
                },

                async handleModalAction() {
                    if (this.modal.action === 'submit') {
                        this.modal.open = false;
                        this.log('System', 'Submitting final result...', 'text-[#007acc]');
                        try {
                            const res = await fetch('{{ route('lab.end', $session->id ?? 0) }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                                body: JSON.stringify({ source_code: this.code })
                            });
                            const data = await res.json();
                            if(data.status === 'success') window.location.href = data.redirect_url;
                        } catch(e) { this.modal = {open: true, icon: '⚠️', title: 'Error', msg: 'Gagal submit.', action: null}; }
                    } else {
                        this.modal.open = false;
                    }
                },

                manualSave() { this.runCode(); this.unsaved = false; this.triggerToast('Saved', 'File saved successfully.', 'success'); },
                toggleTask(id) { if(!this.isLocked(id)) this.expandedTask = (this.expandedTask === id) ? null : id; },
                isCompleted(id) { return this.completed.includes(parseInt(id)); },
                isLocked(id) { if (this.readOnly) return false; const idx = this.stepsData.findIndex(s => s.id === id); return idx > 0 && !this.completed.includes(this.stepsData[idx - 1].id); },
                goBack() { window.location.href = "{{ route('courses.htmldancss') }}"; },
                
                log(source, msg, color) { 
                    const t = new Date().toLocaleTimeString('en-GB', {hour12: false}); 
                    this.logs.push({time: t, msg: `<span class="font-bold text-gray-500">[${source}]</span> ${msg}`, color}); 
                    this.$nextTick(() => { const el = document.getElementById('terminal-logs'); el.scrollTop = el.scrollHeight; }); 
                },
                
                triggerToast(title, msg, type) {
                    this.toastTitle = title; this.toastMsg = msg; this.toastType = type; this.showToast = true;
                    setTimeout(() => this.showToast = false, 3000);
                }
            }
        }
    </script>
</body>
</html>