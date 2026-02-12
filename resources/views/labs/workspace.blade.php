<!DOCTYPE html>
<html lang="id" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ $lab->title }} | Pro Workspace</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ace.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/mode-html.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/theme-one_dark.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.32.6/ext-language_tools.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: { sans: ['Inter', 'sans-serif'], mono: ['JetBrains Mono', 'monospace'] },
                    colors: {
                        bg: { main: '#09090b', panel: '#18181b', active: '#27272a', border: '#3f3f46' },
                        accent: { primary: '#6366f1', success: '#10b981', warning: '#f59e0b', error: '#ef4444' }
                    }
                }
            }
        }
    </script>
    <style>
        body { background: #09090b; color: #e4e4e7; overflow: hidden; }
        .scroll-smooth::-webkit-scrollbar { width: 6px; height: 6px; }
        .scroll-smooth::-webkit-scrollbar-thumb { background: #3f3f46; border-radius: 3px; }
        .step-locked { opacity: 0.5; pointer-events: none; filter: grayscale(1); background-color: #0f0f10; }
        .read-only-overlay { background: repeating-linear-gradient(45deg, rgba(255,255,255,0.03), rgba(255,255,255,0.03) 10px, transparent 10px, transparent 20px); }
        .ace_editor.ace_autocomplete { background: #18181b; border: 1px solid #3f3f46; color: #e4e4e7; }
        .pop-in { animation: popIn 0.3s cubic-bezier(0.16, 1, 0.3, 1); }
        @keyframes popIn { from { transform: scale(0.95); opacity: 0; } to { transform: scale(1); opacity: 1; } }
    </style>
</head>

<body x-data="labApp()" x-init="init()" 
      @keydown.window.ctrl.s.prevent="!readOnly && manualSave()" 
      class="flex flex-col h-screen w-full antialiased selection:bg-accent-primary/30">

    <header class="h-14 bg-bg-panel/90 backdrop-blur-md border-b border-bg-border flex items-center justify-between px-4 z-50 shrink-0">
        <div class="flex items-center gap-4">
            <button @click="goBack()" class="p-2 rounded hover:bg-bg-active text-gray-400 hover:text-white transition group" title="Kembali">
                <svg class="w-5 h-5 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div>
                <h1 class="text-sm font-bold text-white tracking-wide truncate max-w-[250px]">{{ $lab->title }}</h1>
                <div class="text-[10px] text-gray-500 flex items-center gap-2 mt-0.5">
                    <template x-if="readOnly">
                        <span class="text-accent-success font-bold flex items-center gap-1 bg-accent-success/10 px-2 py-0.5 rounded border border-accent-success/20">
                            <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg> REVIEW MODE (LULUS)
                        </span>
                    </template>
                    <template x-if="!readOnly">
                        <span class="flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-accent-success animate-pulse"></span> Live Session</span>
                    </template>
                </div>
            </div>
        </div>

        <div class="absolute left-1/2 -translate-x-1/2" x-show="!readOnly">
            <button @click="manualSave()" class="flex items-center gap-2 px-4 py-1.5 rounded bg-bg-active border border-bg-border hover:border-accent-primary/50 text-gray-300 hover:text-white transition shadow-lg group">
                <svg class="w-3.5 h-3.5 text-accent-success group-hover:scale-110 transition" fill="currentColor" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z"/></svg>
                <span class="text-xs font-bold">Simpan & Run (Ctrl+S)</span>
            </button>
        </div>

        <div class="flex items-center gap-4">
            <div x-show="!readOnly" class="flex items-center gap-2 px-3 py-1.5 bg-bg-active rounded border border-bg-border">
                <svg class="w-3.5 h-3.5 text-accent-warning" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                <span class="text-xs font-mono font-bold text-white tabular-nums" x-text="timer">--:--</span>
            </div>
            
            <button @click="confirmFinish()" class="px-5 py-2 rounded text-xs font-bold text-white shadow-lg transition border border-white/5"
                :class="readOnly ? 'bg-gray-700 hover:bg-gray-600' : 'bg-accent-primary hover:bg-indigo-600'">
                <span x-text="readOnly ? 'Kembali ke Materi' : 'Selesai & Kumpulkan'"></span>
            </button>
        </div>
    </header>

    <div class="flex-1 flex overflow-hidden relative">
        
        <aside class="w-[340px] bg-bg-panel border-r border-bg-border flex flex-col z-20">
            <div class="flex justify-between items-center p-3 border-b border-bg-border bg-bg-main/50">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Daftar Tugas</span>
                <span class="text-[10px] font-mono px-2 py-0.5 rounded bg-accent-primary/10 text-accent-primary border border-accent-primary/20">
                    Skor: <span x-text="score">0</span>/100
                </span>
            </div>

            <div class="flex-1 overflow-y-auto scroll-smooth p-0">
                <div class="p-4 bg-bg-active/30 border-b border-bg-border">
                    <p class="text-xs text-gray-400 leading-relaxed">{{ $lab->description ?? 'Selesaikan langkah-langkah berikut secara berurutan.' }}</p>
                </div>

                @foreach($lab->steps as $step)
                <div class="border-b border-bg-border transition group bg-bg-panel"
                     :class="isLocked({{ $step->id }}) ? 'step-locked' : 'hover:bg-bg-main'">
                    
                    <div @click="toggleTask({{ $step->id }})" class="p-4 cursor-pointer flex gap-3 items-start">
                        <div class="mt-0.5 w-5 h-5 rounded-full border flex items-center justify-center shrink-0 transition-all duration-300"
                             :class="isCompleted({{ $step->id }}) ? 'bg-accent-success border-accent-success text-white scale-110' : 'border-gray-600 text-gray-500'">
                            
                            <template x-if="isCompleted({{ $step->id }})">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
                            </template>
                            <template x-if="!isCompleted({{ $step->id }})">
                                <span class="text-[10px] font-bold">{{ $loop->iteration }}</span>
                            </template>
                        </div>
                        
                        <div class="flex-1">
                            <div class="flex justify-between items-center">
                                <h3 class="text-xs font-bold text-gray-200 group-hover:text-white transition" :class="isCompleted({{ $step->id }}) ? 'text-accent-success' : ''">
                                    {{ $step->title }}
                                </h3>
                                <span class="text-[9px] text-gray-500 border border-bg-border px-1.5 rounded">+{{ $step->points }}</span>
                            </div>
                            
                            <div x-show="expandedTask === {{ $step->id }}" x-collapse class="mt-2 text-xs text-gray-400 border-l-2 border-bg-border pl-3 leading-relaxed">
                                {!! nl2br(e($step->instruction)) !!}
                            </div>
                        </div>
                    </div>

                    <div x-show="expandedTask === {{ $step->id }}" class="px-4 pb-4 flex justify-end">
                        
                        <template x-if="readOnly">
                            <div class="flex items-center gap-2">
                                <template x-if="isCompleted({{ $step->id }})">
                                    <span class="text-[10px] font-bold text-accent-success flex items-center gap-1 border border-accent-success/30 px-2 py-1 rounded bg-accent-success/10">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M5 13l4 4L19 7"/></svg> TUGAS SELESAI
                                    </span>
                                </template>
                                <template x-if="!isCompleted({{ $step->id }})">
                                    <span class="text-[10px] font-bold text-accent-warning border border-accent-warning/30 px-2 py-1 rounded bg-accent-warning/10">
                                        BELUM SEMPURNA
                                    </span>
                                </template>
                            </div>
                        </template>

                        <template x-if="!readOnly">
                            <button @click="checkTask({{ $step->id }})" 
                                    :disabled="loadingId === {{ $step->id }} || isCompleted({{ $step->id }})"
                                    class="px-3 py-1.5 text-[10px] font-bold rounded border transition flex items-center gap-2"
                                    :class="isCompleted({{ $step->id }}) 
                                        ? 'bg-accent-success/10 text-accent-success border-accent-success/30 cursor-default' 
                                        : 'bg-accent-primary/10 text-accent-primary border-accent-primary/30 hover:bg-accent-primary hover:text-white'">
                                <span x-show="loadingId === {{ $step->id }}" class="animate-spin w-3 h-3 border-2 border-current border-t-transparent rounded-full"></span>
                                <span x-text="isCompleted({{ $step->id }}) ? 'Tugas Selesai' : 'Cek Kode'"></span>
                            </button>
                        </template>
                    </div>
                </div>
                @endforeach
            </div>
        </aside>

        <main class="flex-1 flex flex-col relative min-w-0 bg-[#1e1e1e]">
            <div class="h-9 bg-bg-main border-b border-bg-border flex items-end px-2">
                <div class="px-4 py-1.5 text-xs text-white bg-bg-panel border-t border-x border-bg-border rounded-t flex items-center gap-2 relative">
                    <span class="text-accent-warning"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg></span>
                    <span class="font-mono text-gray-300">index.html</span>
                    <span x-show="unsaved" class="absolute right-2 top-2 w-1.5 h-1.5 bg-accent-primary rounded-full animate-pulse"></span>
                </div>
                <div class="ml-auto text-[10px] text-gray-500 flex items-center gap-2 px-2">
                    <span>Hint: <code class="bg-bg-active px-1 rounded border border-bg-border">Ctrl</code> + <code class="bg-bg-active px-1 rounded border border-bg-border">Space</code></span>
                </div>
            </div>
            
            <div class="flex-1 relative">
                <div id="editor-container" class="absolute inset-0"></div>
                <div x-show="readOnly" class="absolute inset-0 z-10 flex items-center justify-center read-only-overlay pointer-events-none">
                    <span class="bg-black/80 px-4 py-2 rounded-full border border-accent-success/30 backdrop-blur text-xs font-bold text-accent-success shadow-xl">READ ONLY MODE</span>
                </div>
            </div>
        </main>

        <div class="w-[450px] flex flex-col border-l border-bg-border bg-bg-panel transition-all">
            <div class="flex-1 bg-white relative flex flex-col">
                <div class="h-9 px-3 flex items-center justify-between border-b border-bg-border bg-bg-panel shrink-0">
                    <span class="text-[10px] font-bold text-gray-400">LIVE PREVIEW</span>
                    <button @click="runCode()" class="text-gray-500 hover:text-white" title="Refresh"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                </div>
                <iframe id="preview-frame" class="flex-1 w-full h-full border-0 bg-white"></iframe>
            </div>

            <div class="flex flex-col bg-bg-main transition-all border-t border-bg-border" :style="terminalOpen ? 'height: 35%' : 'height: 36px'">
                <div class="h-9 flex items-center justify-between px-3 bg-bg-active cursor-pointer hover:bg-bg-border" @click="terminalOpen = !terminalOpen">
                    <span class="text-[10px] font-bold text-gray-300 flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M8 9l3 3-3 3m5 0h3"/></svg> TERMINAL
                    </span>
                    <svg class="w-3 h-3 text-gray-500 transition-transform" :class="!terminalOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                </div>
                <div x-show="terminalOpen" class="flex-1 p-3 font-mono text-[11px] overflow-y-auto text-gray-400 bg-bg-main" id="terminal-logs">
                    <template x-for="log in logs">
                        <div class="mb-1 flex gap-2">
                            <span class="opacity-30 select-none shrink-0" x-text="log.time"></span>
                            <span :class="log.color" x-text="log.msg"></span>
                        </div>
                    </template>
                    <div class="mt-2 text-accent-primary animate-pulse">_</div>
                </div>
            </div>
        </div>
    </div>

    <div x-show="modal.open" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/80 backdrop-blur-sm p-4 pop-in" style="display: none;">
        <div class="bg-bg-panel border border-bg-border w-full max-w-sm rounded-xl p-6 text-center shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 left-0 w-full h-1 bg-gradient-to-r from-accent-primary to-accent-success"></div>
            <div class="mb-4 text-3xl" x-text="modal.icon"></div>
            <h3 class="text-lg font-bold text-white mb-2" x-text="modal.title"></h3>
            <p class="text-sm text-gray-400 mb-6 leading-relaxed" x-text="modal.msg"></p>
            <div class="flex gap-3 justify-center">
                <button x-show="modal.type === 'confirm'" @click="modal.open = false" class="flex-1 py-2 rounded text-gray-400 text-xs font-bold border border-bg-border hover:bg-bg-active">Batal</button>
                <button @click="handleModalAction()" class="flex-1 py-2 rounded text-white text-xs font-bold bg-accent-primary hover:bg-indigo-600 shadow-lg">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        function labApp() {
            return {
                // DATA UTAMA
                readOnly: {{ $session->status === 'completed' ? 'true' : 'false' }},
                score: {{ $session->current_score ?? 0 }},
                
                // Array ID yang selesai (Dipaksa jadi int agar aman)
                completed: @json(array_map('intval', $completedStepIds)), 
                
                allSteps: @json($lab->steps->pluck('id')),
                code: @json($session->current_code ?? ''),
                expiry: {{ $session->expires_at ? \Carbon\Carbon::parse($session->expires_at)->timestamp : 0 }},
                
                // UI STATE
                sidebarOpen: true,
                terminalOpen: true,
                expandedTask: {{ $lab->steps->first()->id ?? 'null' }},
                loadingId: null,
                unsaved: false,
                logs: [{time: '00:00', msg: 'System Ready...', color: 'text-gray-500'}],
                timer: 'Loading...',
                saveTimeout: null,
                modal: { open: false, title: '', msg: '', icon: 'ℹ️', type: 'info', action: null },

                init() {
                    // Safety: Code null -> string
                    if(this.code === null) this.code = "";
                    
                    this.initEditor();
                    
                    if(this.readOnly) {
                        this.timer = 'SELESAI';
                        this.log('Mode Review: Jawaban Anda dimuat.', 'text-accent-success');
                        this.log(`Skor Akhir: ${this.score}`, 'text-accent-primary');
                    } else {
                        this.startTimer();
                        this.log('Lab dimulai. Good luck!', 'text-accent-success');
                        if(this.completed.length > 0) {
                            this.log(`Sync: ${this.completed.length} tugas berhasil dipulihkan.`, 'text-accent-primary');
                        }
                    }
                    this.$nextTick(() => this.runCode());
                },

                initEditor() {
                    ace.require("ace/ext/language_tools");
                    this.editor = ace.edit("editor-container");
                    this.editor.setTheme("ace/theme/one_dark");
                    this.editor.session.setMode("ace/mode/html");
                    this.editor.setOptions({ 
                        fontSize: "14px", 
                        fontFamily: "JetBrains Mono", 
                        showPrintMargin: false, 
                        wrap: true,
                        readOnly: this.readOnly,
                        enableBasicAutocompletion: true,
                        enableLiveAutocompletion: true
                    });

                    // Tambahkan Custom Completer untuk Tailwind
                    const tailwindCompleter = {
                        getCompletions: function(editor, session, pos, prefix, callback) {
                            if (prefix.length === 0) { callback(null, []); return; }
                            const classes = ["flex", "grid", "hidden", "block", "bg-red-500", "bg-blue-500", "text-white", "p-4", "m-4", "rounded", "w-full", "h-screen", "items-center", "justify-center"];
                            callback(null, classes.map(cls => ({caption: cls, value: cls, meta: "Tailwind"})));
                        }
                    };
                    this.editor.completers = [tailwindCompleter];

                    this.editor.setValue(this.code, -1);
                    this.editor.session.on('change', () => { 
                        this.code = this.editor.getValue();
                        if(!this.readOnly) {
                            this.unsaved = true;
                            clearTimeout(this.saveTimeout);
                            this.saveTimeout = setTimeout(() => { this.manualSave(true); }, 2000);
                        }
                    });
                },

                runCode() {
                    const frame = document.getElementById('preview-frame').contentDocument;
                    const tailwind = '<script src="https://cdn.tailwindcss.com"><\/script>';
                    let content = this.code;
                    if(!content.includes('<html')) content = `<!DOCTYPE html><html><head>${tailwind}</head><body class="p-4 bg-white">${content}</body></html>`;
                    else if(content.includes('<head>')) content = content.replace('<head>', '<head>'+tailwind);
                    else content = tailwind + content;
                    frame.open(); frame.write(content); frame.close();
                },

                isLocked(stepId) {
                    if (this.readOnly) return false;
                    const idx = this.allSteps.indexOf(stepId);
                    if (idx === 0) return false; 
                    const prevId = this.allSteps[idx - 1];
                    return !this.completed.includes(prevId);
                },

                async sendRequest(url, body) {
                    try {
                        const res = await fetch(url, {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify(body)
                        });
                        const data = await res.json();
                        if(!res.ok) throw new Error(data.message || 'Server Error');
                        return data;
                    } catch (e) {
                        this.log('Error: ' + e.message, 'text-accent-error');
                        throw e;
                    }
                },

                async manualSave(silent = false) {
                    this.runCode();
                    if(!silent) this.log('Menyimpan...', 'text-gray-500');
                    try {
                        await this.sendRequest('{{ route('lab.check', $session->id ?? 0) }}', { source_code: this.code });
                        this.unsaved = false;
                        if(!silent) this.log('Tersimpan.', 'text-gray-500');
                    } catch(e) {}
                },

                async checkTask(stepId) {
                    this.loadingId = stepId;
                    this.log(`Memeriksa Task...`, 'text-gray-400');
                    try {
                        const data = await this.sendRequest('{{ route('lab.check', $session->id ?? 0) }}', { step_id: stepId, source_code: this.code });
                        if (data.status === 'success') {
                            // Update Lokal agar UI berubah hijau
                            if (!this.completed.includes(stepId)) {
                                this.completed.push(stepId);
                                this.score = data.new_score;
                                const idx = this.allSteps.indexOf(stepId);
                                if(idx < this.allSteps.length - 1) this.expandedTask = this.allSteps[idx + 1];
                            }
                            this.log('PASS: ' + data.output, 'text-accent-success');
                            this.modal = {open: true, icon: '🎉', title: 'Benar!', msg: 'Lanjut ke langkah berikutnya.', type: 'info'};
                        } else {
                            this.log('FAIL: ' + data.message, 'text-accent-error');
                            this.modal = {open: true, icon: '🚫', title: 'Masih Salah', msg: data.message, type: 'error'};
                        }
                    } catch (e) {}
                    this.loadingId = null;
                },

                toggleTask(id) { 
                    if(this.isLocked(id)) return;
                    this.expandedTask = (this.expandedTask === id) ? null : id; 
                },
                
                // Helper pengecekan array yang aman tipe data
                isCompleted(id) { return this.completed.includes(parseInt(id)); },
                
                confirmFinish() {
                    if (this.readOnly) return this.goBack();
                    const isGood = this.score >= 50;
                    this.modal = {
                        open: true, 
                        icon: isGood ? '🏆' : '⚠️', 
                        title: isGood ? 'Kumpulkan?' : 'Skor Rendah', 
                        msg: `Skor Anda: ${this.score}. Yakin?`, 
                        type: 'confirm', 
                        action: 'submit'
                    };
                },

               async handleModalAction() {
                    if (this.modal.action === 'submit') {
                        // Cek jika mode review
                        if (this.readOnly) { 
                            window.location.href = "{{ route('courses.htmldancss') }}"; 
                            return; 
                        }

                        // === PERBAIKAN DISINI ===
                        // 1. Paksa ambil nilai terbaru dari Editor ACE
                        this.code = this.editor.getValue();
                        
                        // 2. Validasi Client Side
                        if(!this.code || this.code.trim() === "") {
                             this.modal = {open: true, icon: '⚠️', title: 'Kode Kosong', msg: 'Tulis kode sebelum mengumpulkan!', type: 'error'};
                             return;
                        }

                        this.modal.open = false;
                        this.log('Mengumpulkan...', 'text-accent-primary');
                        
                        try {
                            // 3. Kirim ke Backend
                            const data = await this.sendRequest('{{ route('lab.end', $session->id ?? 0) }}', {
                                source_code: this.code, // Kirim kode terbaru
                                duration: 0 // Backend akan menghitung durasi
                            });
                            
                            if(data.status === 'success') {
                                window.location.href = data.redirect_url;
                            }
                        } catch(e) {
                            this.modal = {open: true, icon: '⚠️', title: 'Gagal', msg: e.message, type: 'error'};
                        }
                    } else {
                        this.modal.open = false;
                    }
                },
                goBack() { window.location.href = "{{ route('courses.htmldancss') }}"; },

                startTimer() {
                    setInterval(() => {
                        const now = Math.floor(Date.now() / 1000);
                        const diff = this.expiry - now;
                        if (diff <= 0) { this.timer = "00:00:00"; return; }
                        const h = Math.floor(diff / 3600).toString().padStart(2, '0');
                        const m = Math.floor((diff % 3600) / 60).toString().padStart(2, '0');
                        const s = (diff % 60).toString().padStart(2, '0');
                        this.timer = `${h}:${m}:${s}`;
                    }, 1000);
                },

                log(msg, color) {
                    const time = new Date().toLocaleTimeString('en-GB', {hour: '2-digit', minute:'2-digit', second:'2-digit'});
                    this.logs.push({time, msg, color});
                    this.$nextTick(() => { const el = document.getElementById('terminal-logs'); el.scrollTop = el.scrollHeight; });
                }
            }
        }
    </script>
</body>
</html>