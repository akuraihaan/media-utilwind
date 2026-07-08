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
    <title>{{ $lab->title }} | Ruang Kerja Lab</title>
    
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
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #424242; border-radius: 4px; }
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
    <header class="h-12 bg-vscode-activity flex items-center justify-between px-2 sm:px-4 shrink-0 select-none border-b border-vscode-bg z-30">
        <div class="flex items-center gap-2 sm:gap-4">
            <button @click="goBack()" class="hover:text-white transition text-gray-400 p-1 sm:p-0" title="Kembali ke dasbor">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
            </button>
            <div class="flex items-center gap-1 sm:gap-2">
                <svg class="w-4 h-4 text-vscode-accent hidden sm:block" viewBox="0 0 24 24" fill="currentColor"><path d="M14.6 2.6c-.6-.4-1.4-.4-2 0L2.1 8.8c-.6.4-.6 1.2 0 1.6l10.5 6.3c.6.4 1.4.4 2 0l10.5-6.3c.6-.4.6-1.2 0-1.6L14.6 2.6zM13.6 18l-8-4.8v3.2l8 4.8 8-4.8v-3.2l-8 4.8z"/></svg>
                <span class="font-bold text-white tracking-wide text-xs sm:text-xs uppercase truncate max-w-[120px] sm:max-w-xs">{{ $lab->title }}</span>
            </div>
        </div>

        {{-- Center Actions (Only Desktop) --}}
        <div class="hidden lg:flex items-center gap-2">
            <button @click="showGuideModal = true" class="flex items-center gap-2 px-3 py-1 bg-[#252526] hover:bg-[#3e3e42] rounded text-xs text-gray-200 transition border border-white/10 shadow-sm">
                <svg class="w-3.5 h-3.5 text-vscode-accent" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                Panduan
            </button>
            <button @click="manualSave()" class="flex items-center gap-2 px-3 py-1 bg-[#3e3e42] hover:bg-[#4e4e52] rounded text-xs text-white transition border border-white/5 shadow-sm">
                <svg class="w-3 h-3 text-green-400" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Simpan & Jalankan (Ctrl+S)
            </button>
        </div>

        {{-- Right Stats --}}
        <div class="flex items-center gap-2 sm:gap-4">
            <button @click="showGuideModal = true" class="lg:hidden w-8 h-8 rounded bg-vscode-bg border border-vscode-border text-vscode-accent font-black text-xs flex items-center justify-center" title="Panduan Lab">
                ?
            </button>
            <div class="hidden sm:flex items-center gap-2 px-3 py-1 bg-vscode-bg rounded border border-vscode-border">
                <span class="text-[10px] sm:text-xs text-gray-400">Skor:</span>
                <span class="font-mono font-bold text-vscode-accent text-[10px] sm:text-xs" x-text="Math.round(score) + '%'">0%</span>
            </div>
            
            <div class="font-mono font-bold text-[10px] sm:text-xs bg-vscode-bg sm:bg-transparent px-2 sm:px-0 py-1 sm:py-0 rounded border border-vscode-border sm:border-transparent" :class="timeCritical ? 'text-red-400 animate-pulse' : 'text-gray-300'">
                <span x-text="timer">--:--</span>
            </div>

            <button @click="confirmFinish()" 
                class="px-2 sm:px-4 py-1.5 rounded text-[10px] sm:text-xs font-bold text-white transition border border-transparent shadow-lg"
                :class="readOnly ? 'bg-gray-600 hover:bg-gray-500' : 'bg-vscode-accent hover:bg-[#0062a3]'">
                <span x-text="readOnly ? 'Keluar' : 'Selesai'" class="sm:hidden"></span>
                <span x-text="readOnly ? 'Keluar' : 'Selesai & Kumpulkan'" class="hidden sm:inline"></span>
            </button>
        </div>
    </header>

    {{-- MAIN WORKSPACE --}}
    <div class="flex-1 flex flex-col lg:flex-row overflow-hidden relative">
        
        {{-- 2. SIDEBAR (TASK LIST) --}}
        <aside class="w-full lg:w-[320px] bg-vscode-sidebar border-r border-vscode-border flex-col z-20 shrink-0"
               :class="mobileTab === 'tasks' ? 'flex h-full' : 'hidden lg:flex'">
            <div class="h-9 px-4 flex items-center justify-between text-[10px] font-bold text-gray-400 tracking-wider uppercase bg-[#252526] shrink-0 border-b border-vscode-border lg:border-none">
                <span>DAFTAR TUGAS</span>
                <span class="text-[9px] bg-[#3e3e42] px-1.5 rounded text-white" x-text="completed.length + '/' + stepsData.length"></span>
            </div>
            
            <div class="flex-1 overflow-y-auto custom-scrollbar pb-16 lg:pb-0">
                <template x-for="step in stepsData" :key="step.id">
                    <div class="border-b border-[#3e3e42]/50 group" :class="isLocked(step.id) ? 'step-locked' : ''">
                        
                        {{-- Task Header Clickable --}}
                        <div @click="toggleTask(step.id)" 
                             class="px-4 py-3 cursor-pointer flex items-center gap-2 hover:bg-[#2a2d2e] border-l-2 transition-all"
                             :class="expandedTask === step.id ? 'active-task' : 'border-transparent'">
                            
                            {{-- Icon Status --}}
                            <div class="shrink-0">
                                <template x-if="isCompleted(step.id)">
                                    <svg class="w-4 h-4 text-vscode-success" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="3" d="M5 13l4 4L19 7"/></svg>
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
                        <div x-show="expandedTask === step.id" x-collapse class="bg-[#1e1e1e] p-4 border-l border-vscode-accent/30">
                            {{-- Instruksi --}}
                            <div class="p-3 rounded bg-[#252526] border border-[#3e3e42] mb-3">
                                <p class="text-xs text-gray-300 leading-relaxed font-mono whitespace-pre-wrap" x-html="step.instruction"></p>
                            </div>
                            
                            <div class="flex justify-between items-center mt-3">
                                {{-- Tombol Reset Kode --}}
                                <button @click="forceLoadCode(step.id)" x-show="!isCompleted(step.id) && !readOnly" 
                                        class="text-[10px] text-gray-500 hover:text-vscode-warning underline decoration-dashed transition">
                                    Reset Kode
                                </button>

                                {{-- Tombol Validasi --}}
                                <template x-if="!readOnly">
                                    <button @click="checkTask(step.id)" 
                                            :disabled="loadingId === step.id || isCompleted(step.id)"
                                            class="px-3 py-1.5 text-[10px] font-bold rounded border transition flex items-center gap-2 ml-auto shadow-lg"
                                            :class="isCompleted(step.id) 
                                                ? 'bg-vscode-success/10 text-vscode-success border-vscode-success/30 cursor-default' 
                                                : 'bg-vscode-accent text-white border-transparent hover:bg-[#0062a3]'">
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
        <main class="flex-1 flex-col relative bg-vscode-bg w-full lg:w-auto"
              :class="mobileTab === 'editor' ? 'flex h-full' : 'hidden lg:flex'">
            {{-- Tabs & Mobile Action --}}
            <div class="h-9 flex items-center justify-between bg-[#252526] border-b border-[#252526] pr-2 shrink-0">
                <div class="h-full px-3 py-2 text-xs text-white bg-vscode-bg border-t-2 border-vscode-accent flex items-center gap-2 pr-6 border-r border-[#252526]">
                    <svg class="w-3.5 h-3.5 text-[#e37933]" viewBox="0 0 24 24" fill="currentColor"><path d="M1.5 0h21l-1.91 21.563L11.977 24l-8.564-2.438L1.5 0zm7.031 9.75l-.232-2.718 10.059.003.23-2.622L5.412 4.41l.698 8.01h9.126l-.325 3.426-2.91.804-2.955-.81-.188-2.11H6.248l.33 4.171L12 19.351l5.379-1.443.744-8.157H8.531z"/></svg>
                    <span>index.html</span>
                    <span x-show="unsaved" class="ml-2 w-2 h-2 rounded-full bg-white animate-pulse"></span>
                </div>
                
                {{-- Tombol jalankan untuk mobile --}}
                <button @click="manualSave(); mobileTab = 'preview'" class="lg:hidden flex items-center gap-1 px-3 py-1 bg-vscode-accent rounded text-[10px] text-white font-bold shadow">
                    <svg class="w-3 h-3 text-green-300" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg> Jalankan
                </button>
            </div>

            {{-- Editor Instance --}}
            <div class="flex-1 relative group pb-14 lg:pb-0">
                <div id="editor-container" class="absolute inset-0"></div>
                
                {{-- Read Only Overlay --}}
                <div x-show="readOnly" class="absolute inset-0 z-10 flex items-center justify-center bg-black/50 backdrop-blur-[1px] pointer-events-none">
                    <span class="bg-black px-4 py-2 rounded border border-vscode-success text-vscode-success font-mono text-xs shadow-xl">MODE TINJAU</span>
                </div>
            </div>
        </main>

        {{-- 4. RIGHT PANEL (PREVIEW & TERMINAL) --}}
        <div class="w-full lg:w-[45%] flex-col border-l border-vscode-border bg-vscode-bg shrink-0"
             :class="mobileTab === 'preview' ? 'flex h-full pb-14 lg:pb-0' : 'hidden lg:flex'">
            
            {{-- Live Preview --}}
            <div class="flex-1 flex flex-col relative bg-white transition-all">
                <div class="h-9 px-3 flex items-center justify-between bg-[#f3f3f3] border-b border-[#e1e1e1] shrink-0">
                    <span class="text-[10px] font-bold text-[#555] flex items-center gap-1">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9m-9 9a9 9 0 019-9"/></svg> LOCALHOST:8000
                    </span>
                    <button @click="runCode()" class="text-[#555] hover:text-black" title="Segarkan pratinjau"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg></button>
                </div>
                <iframe id="preview-frame" class="flex-1 w-full h-full border-0 bg-white"></iframe>
            </div>

            {{-- Terminal --}}
            <div class="bg-vscode-bg border-t border-vscode-border flex flex-col transition-all duration-300 shrink-0" 
                 :class="terminalOpen ? 'h-48' : 'h-8'">
                <div class="h-8 px-4 flex items-center justify-between bg-vscode-bg border-b border-vscode-border cursor-pointer hover:bg-[#2a2d2e] shrink-0" @click="terminalOpen = !terminalOpen">
                    <div class="flex gap-6 text-[10px] uppercase font-bold text-gray-400">
                        <span class="text-white border-b border-white pb-1">Terminal</span>
                        <span>Keluaran</span>
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
                    <div class="flex gap-2 mt-2 pb-2">
                        <span class="text-vscode-accent">➜</span>
                        <span class="text-white">~</span>
                        <span class="text-gray-400 animate-pulse">_</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 5. MOBILE BOTTOM NAVIGATION BAR --}}
    <div class="lg:hidden flex bg-vscode-sidebar border-t border-vscode-border shrink-0 h-14 w-full fixed bottom-0 z-40">
        <button @click="mobileTab = 'tasks'" 
                class="flex-1 flex flex-col justify-center items-center gap-1 font-bold text-[10px] uppercase transition-colors"
                :class="mobileTab === 'tasks' ? 'text-vscode-accent border-t-2 border-vscode-accent bg-[#2a2d2e]' : 'text-gray-400 border-t-2 border-transparent'">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/></svg>
            Tugas
        </button>
        <button @click="mobileTab = 'editor'" 
                class="flex-1 flex flex-col justify-center items-center gap-1 font-bold text-[10px] uppercase transition-colors"
                :class="mobileTab === 'editor' ? 'text-vscode-accent border-t-2 border-vscode-accent bg-[#2a2d2e]' : 'text-gray-400 border-t-2 border-transparent'">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
            Editor
        </button>
        <button @click="mobileTab = 'preview'" 
                class="flex-1 flex flex-col justify-center items-center gap-1 font-bold text-[10px] uppercase transition-colors"
                :class="mobileTab === 'preview' ? 'text-vscode-accent border-t-2 border-vscode-accent bg-[#2a2d2e]' : 'text-gray-400 border-t-2 border-transparent'">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
            Pratinjau
        </button>
    </div>

    {{-- FOOTER (Only visible on Desktop) --}}
    <footer class="hidden lg:flex h-6 bg-vscode-accent text-white items-center justify-between px-3 text-[10px] select-none shrink-0 font-medium z-30">
        <div class="flex gap-4">
            <span class="flex items-center gap-1"><svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg> master*</span>
        </div>
        <div class="flex gap-4">
            <span>UTF-8</span>
            <span>HTML</span>
        </div>
    </footer>

    {{-- PANDUAN LAB --}}
    <div x-show="showGuideModal" class="fixed inset-0 z-[100] flex items-center justify-center bg-black/60 backdrop-blur-sm p-4" style="display: none;" x-cloak>
        <div class="absolute inset-0" @click="showGuideModal = false"></div>
        <div class="relative bg-[#252526] border border-[#454545] w-full max-w-xl rounded-xl shadow-2xl overflow-hidden">
            <div class="bg-[#333333] px-5 py-3 border-b border-[#454545] flex justify-between items-center">
                <div>
                    <p class="text-[10px] uppercase tracking-widest text-vscode-accent font-bold">Panduan Pengerjaan Lab</p>
                    <h3 class="text-white text-base font-bold mt-0.5">{{ $lab->title }}</h3>
                </div>
                <button @click="showGuideModal = false" class="text-gray-400 hover:text-white">✕</button>
            </div>
            <div class="p-5 space-y-4 max-h-[72vh] overflow-y-auto custom-scrollbar">
                <div class="rounded-xl border border-vscode-accent/30 bg-vscode-accent/10 p-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-vscode-accent font-bold">Nilai Lulus</p>
                            <p class="text-xs leading-relaxed text-gray-300 mt-1">Kumpulkan lab setelah skor mencapai minimal <b x-text="passingGrade + '%'"></b> atau semua tugas penting tervalidasi.</p>
                        </div>
                        <span class="shrink-0 rounded-lg bg-[#1e1e1e] border border-vscode-accent/30 px-3 py-2 text-vscode-accent font-mono font-black text-sm" x-text="passingGrade + '%'"></span>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Bahan yang diperlukan</p>
                    <div class="grid sm:grid-cols-2 gap-2">
                        <div class="rounded-lg border border-[#3e3e42] bg-[#1e1e1e] px-3 py-2 text-xs text-gray-300">Materi bab terkait</div>
                        <div class="rounded-lg border border-[#3e3e42] bg-[#1e1e1e] px-3 py-2 text-xs text-gray-300">Instruksi tugas aktif</div>
                        <div class="rounded-lg border border-[#3e3e42] bg-[#1e1e1e] px-3 py-2 text-xs text-gray-300">Class Tailwind yang dipelajari</div>
                        <div class="rounded-lg border border-[#3e3e42] bg-[#1e1e1e] px-3 py-2 text-xs text-gray-300">Koneksi stabil dan fokus</div>
                    </div>
                </div>

                <div>
                    <p class="text-[10px] uppercase tracking-widest text-gray-400 font-bold mb-2">Langkah penggunaan</p>
                    <div class="space-y-2">
                        <div class="flex gap-3 rounded-lg border border-[#3e3e42] bg-[#1e1e1e] p-3">
                            <span class="w-6 h-6 rounded bg-vscode-accent/20 text-vscode-accent flex items-center justify-center text-[10px] font-black shrink-0">1</span>
                            <p class="text-xs leading-relaxed text-gray-300">Pilih tugas di sidebar, baca instruksi dan aturan validasinya.</p>
                        </div>
                        <div class="flex gap-3 rounded-lg border border-[#3e3e42] bg-[#1e1e1e] p-3">
                            <span class="w-6 h-6 rounded bg-vscode-accent/20 text-vscode-accent flex items-center justify-center text-[10px] font-black shrink-0">2</span>
                            <p class="text-xs leading-relaxed text-gray-300">Tulis kode di editor, lalu tekan <b>Simpan & Jalankan</b> atau <b>Ctrl+S</b>.</p>
                        </div>
                        <div class="flex gap-3 rounded-lg border border-[#3e3e42] bg-[#1e1e1e] p-3">
                            <span class="w-6 h-6 rounded bg-vscode-accent/20 text-vscode-accent flex items-center justify-center text-[10px] font-black shrink-0">3</span>
                            <p class="text-xs leading-relaxed text-gray-300">Cek pratinjau, klik <b>Verifikasi</b>, lalu perbaiki pesan error bila belum sesuai.</p>
                        </div>
                        <div class="flex gap-3 rounded-lg border border-[#3e3e42] bg-[#1e1e1e] p-3">
                            <span class="w-6 h-6 rounded bg-vscode-success/20 text-vscode-success flex items-center justify-center text-[10px] font-black shrink-0">4</span>
                            <p class="text-xs leading-relaxed text-gray-300">Kumpulkan setelah skor memenuhi nilai lulus. Jika di bawah standar, ulangi perbaikan tugas terlebih dahulu.</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-4 bg-[#2d2d2d] border-t border-[#454545] flex justify-end">
                <button @click="showGuideModal = false" class="px-4 py-2 rounded bg-vscode-accent hover:bg-[#0062a3] text-white text-xs font-bold">Mulai Mengerjakan</button>
            </div>
        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div x-show="showToast" x-transition:enter="toast-enter" class="fixed bottom-20 lg:bottom-10 right-5 z-50 bg-[#252526] border border-vscode-border shadow-2xl p-4 w-72 rounded flex gap-3 items-start">
        <div :class="toastType === 'success' ? 'text-vscode-success' : (toastType === 'info' ? 'text-vscode-accent' : 'text-vscode-error')">
            <svg x-show="toastType === 'success'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <svg x-show="toastType === 'error'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
            <svg x-show="toastType === 'info'" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
        </div>
        <div class="flex-1">
            <h4 class="text-white text-xs font-bold mb-1" x-text="toastTitle">Notifikasi</h4>
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
                <button @click="modal.open = false" class="px-4 py-1.5 rounded text-white text-xs border border-gray-500 hover:bg-gray-700">Batal</button>
                <button x-show="modal.action" @click="handleModalAction()" class="px-4 py-1.5 rounded text-white text-xs bg-vscode-accent hover:bg-[#0062a3]">Lanjutkan</button>
            </div>
        </div>
    </div>

    <script>
        function labApp() {
            return {
                // --- STATE DATA ---
                readOnly: {{ $session->status === 'completed' ? 'true' : 'false' }},
                score: {{ $session->current_score ?? 0 }},
                passingGrade: {{ (int) ($lab->passing_grade ?? 70) }},
                completed: @json(array_map('intval', $completedStepIds)),
                
                // Data Steps dari PHP
                stepsData: @json($stepsData),
                
                code: @json($session->current_code ?? ''),
                expiry: {{ $session->expires_at ? \Carbon\Carbon::parse($session->expires_at)->timestamp : 0 }},
                saveCount: {{ (int) ($session->save_count ?? 0) }},
                validationCount: {{ (int) ($session->validation_attempt_count ?? 0) }},
                changeCount: {{ (int) ($session->code_change_count ?? 0) }},
                hydratingEditor: false,

                // --- UI CONTROL ---
                mobileTab: 'editor', // Mobile View Switcher ('tasks', 'editor', 'preview')
                terminalOpen: true, previewOpen: true, expandedTask: null, loadingId: null,
                unsaved: false, showToast: false, showGuideModal: false, toastTitle: '', toastMsg: '', toastType: 'info',
                logs: [{time: 'INIT', msg: 'Lingkungan kerja berhasil dimuat.', color: 'text-vscode-success'}],
                timer: 'MEMUAT', timeCritical: false,
                modal: { open: false, title: '', msg: '', icon: '', action: null },

                // --- INIT SEQUENCE ---
                init() {
                    this.log('Sistem', 'Menyiapkan ruang kerja...', 'text-gray-500');
                    
                    // 1. Tentukan Task Aktif (Task pertama yang belum selesai)
                    const firstUnfinished = this.stepsData.find(s => !this.completed.includes(s.id));
                    this.expandedTask = firstUnfinished ? firstUnfinished.id : (this.stepsData.length > 0 ? this.stepsData[0].id : null);

                    // 2. Setup Editor
                    this.initEditor();

                    // 3. Fallback Initial Code jika user baru masuk
                    if(!this.code || this.code.trim() === '') {
                        const currentStep = this.stepsData.find(s => s.id === this.expandedTask);
                        if(currentStep && currentStep.initial_code) {
                            this.code = currentStep.initial_code;
                            this.hydratingEditor = true;
                            this.editor.setValue(this.code, -1);
                            this.hydratingEditor = false;
                            this.log('Sistem', 'Template kode awal dimuat.', 'text-[#cca700]');
                        }
                    }

                    // 4. Start Timer
                    if(!this.readOnly) {
                        this.startTimer();
                    } else {
                        this.timer = "SELESAI";
                        this.log('Sistem', 'Mode tinjau aktif.', 'text-[#007acc]');
                    }
                    
                    // 5. Initial Run
                    this.$nextTick(() => this.runCode());

                    if (!this.readOnly && this.completed.length === 0) {
                        this.$nextTick(() => { this.showGuideModal = true; });
                    }

                    // 6. Handle Editor Resize when switching tabs on mobile
                    this.$watch('mobileTab', val => {
                        if(val === 'editor') {
                            setTimeout(() => { if(this.editor) this.editor.resize(); }, 50);
                        }
                    });
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
                    
                    this.hydratingEditor = true;
                    this.editor.setValue(this.code, -1);
                    this.hydratingEditor = false;
                    this.editor.session.on('change', () => { 
                        this.code = this.editor.getValue();
                        if(!this.readOnly && !this.hydratingEditor) {
                            this.unsaved = true;
                            this.changeCount++;
                        }
                    });
                },

                syncInteractionCounters(data) {
                    if(!data) return;
                    if(Number.isFinite(Number(data.save_count))) this.saveCount = Number(data.save_count);
                    if(Number.isFinite(Number(data.validation_attempt_count))) this.validationCount = Number(data.validation_attempt_count);
                    if(Number.isFinite(Number(data.code_change_count))) this.changeCount = Number(data.code_change_count);
                },

                // --- CORE LOGIC: CHECK & AUTO NEXT ---
                async checkTask(stepId) {
                    this.loadingId = stepId;
                    this.log('Pemeriksa', `Memverifikasi tugas #${stepId}...`, 'text-[#007acc]');
                    
                    try {
                        const res = await fetch('{{ route('lab.check', $session->id ?? 0) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ step_id: stepId, source_code: this.code, event_type: 'validation', code_change_count: this.changeCount })
                        });
                        const data = await res.json();
                        this.syncInteractionCounters(data);

                        if (data.status === 'success') {
                            // 1. Update State Lokal
                            if (!this.completed.includes(stepId)) {
                                this.completed.push(stepId);
                                this.score = data.new_score;
                            }
                            
                            this.log('Pemeriksa', `[BERHASIL] ${data.output}`, 'text-[#4ec9b0]');
                            this.triggerToast('Berhasil', 'Jawaban Anda benar. Lanjut ke tugas berikutnya.', 'success');

                            // 2. AUTO NEXT TASK TRIGGER
                            this.handleNextTask(stepId);

                        } else {
                            const failMsg = `${data.message || 'Tugas belum memenuhi aturan validasi.'} Baca ulang instruksi tugas aktif, cek class wajib atau struktur HTML, gunakan Reset Kode bila perlu, lalu klik Verifikasi lagi.`;
                            this.log('Pemeriksa', `[GAGAL] ${failMsg}`, 'text-[#f14c4c]');
                            this.triggerToast('Belum Memenuhi Syarat', failMsg, 'error');
                        }
                    } catch (e) {
                        this.log('Sistem', 'Gangguan jaringan: ' + e.message, 'text-[#f14c4c]');
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
                        if (nextStep.initial_code && nextStep.initial_code.trim() !== "") {
                            this.log('Sistem', `Memuat lingkungan untuk tugas: ${nextStep.title}...`, 'text-gray-400');
                            
                            setTimeout(() => {
                                this.code = nextStep.initial_code;
                                this.hydratingEditor = true;
                                this.editor.setValue(this.code, -1);
                                this.hydratingEditor = false;
                                this.runCode(); 
                                
                                this.log('Sistem', 'Kode awal baru sudah dimasukkan.', 'text-[#cca700]');
                                this.triggerToast('Lingkungan Diperbarui', 'Kode baru untuk tugas ini telah dimuat.', 'info');
                            }, 1000); 
                        }
                        
                        // Opsional: Switch to Tasks tab on Mobile to show progress
                        if (window.innerWidth < 1024) {
                            this.mobileTab = 'tasks';
                        }
                    } else {
                        // Jika task habis
                        this.log('Sistem', 'Semua tugas selesai. Lab siap dikumpulkan.', 'text-[#4ec9b0]');
                        this.confirmFinish();
                    }
                },

                // --- UTILS ---
                checkCurrentTask() { if (this.expandedTask && !this.isCompleted(this.expandedTask)) this.checkTask(this.expandedTask); },
                
                forceLoadCode(stepId) {
                    if(!confirm("Reset kode editor ke template awal tugas ini? Kode Anda akan hilang.")) return;
                    const step = this.stepsData.find(s => s.id === stepId);
                    if(step && step.initial_code) {
                        this.code = step.initial_code;
                        this.hydratingEditor = true;
                        this.editor.setValue(this.code, -1);
                        this.hydratingEditor = false;
                        if(!this.readOnly) {
                            this.changeCount++;
                            this.unsaved = true;
                        }
                        this.runCode();
                    this.log('Sistem', 'Kode dikembalikan ke template awal.', 'text-[#cca700]');
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
                    const passing = this.passingGrade || 70;
                    const msg = this.score < passing
                        ? `Skor Anda baru ${Math.round(this.score)}%, belum mencapai nilai lulus ${passing}%. Pilih Batal untuk memperbaiki tugas yang belum tervalidasi, baca ulang instruksi, lalu verifikasi lagi. Tetap kumpulkan sekarang?`
                        : `Skor Anda ${Math.round(this.score)}% dan sudah memenuhi nilai lulus ${passing}%. Siap dikumpulkan?`;
                    this.modal = { open: true, icon: '✓', title: 'Kumpulkan Lab?', msg: msg, action: 'submit' };
                },

                async handleModalAction() {
                    if (this.modal.action === 'submit') {
                        this.modal.open = false;
                        this.log('Sistem', 'Mengirim hasil akhir...', 'text-[#007acc]');
                        try {
                            const res = await fetch('{{ route('lab.end', $session->id ?? 0) }}', {
                                method: 'POST',
                                headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                                body: JSON.stringify({ source_code: this.code, event_type: 'submit', code_change_count: this.changeCount })
                            });
                            const data = await res.json();
                            if(data.status === 'success') window.location.href = data.redirect_url;
                        } catch(e) { this.modal = {open: true, icon: '!', title: 'Galat', msg: 'Gagal mengumpulkan lab.', action: null}; }
                    } else {
                        this.modal.open = false;
                    }
                },

                async manualSave() {
                    if(this.readOnly) return;
                    this.runCode();
                    try {
                        const res = await fetch('{{ route('lab.check', $session->id ?? 0) }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'Accept': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
                            body: JSON.stringify({ source_code: this.code, event_type: 'manual_save', code_change_count: this.changeCount })
                        });
                        const data = await res.json();
                        this.syncInteractionCounters(data);
                        if(data.status === 'success') {
                            this.unsaved = false;
                            this.triggerToast('Tersimpan', `Berkas tersimpan (${this.saveCount}x).`, 'success');
                        } else {
                            this.triggerToast('Gagal Menyimpan', data.message || 'Server belum menerima perubahan kode.', 'error');
                        }
                    } catch(e) {
                        this.triggerToast('Gagal Menyimpan', 'Gangguan jaringan: ' + e.message, 'error');
                    }
                },
                toggleTask(id) { if(!this.isLocked(id)) this.expandedTask = (this.expandedTask === id) ? null : id; },
                isCompleted(id) { return this.completed.includes(parseInt(id)); },
                isLocked(id) { if (this.readOnly) return false; const idx = this.stepsData.findIndex(s => s.id === id); return idx > 0 && !this.completed.includes(this.stepsData[idx - 1].id); },
                goBack() { window.location.href = "{{ $session->review_result_url ?? route('courses.htmldancss') }}"; },
                
                log(source, msg, color) { 
                    const t = new Date().toLocaleTimeString('id-ID', {hour12: false}); 
                    this.logs.push({time: t, msg: `<span class="font-bold text-gray-500">[${source}]</span> ${msg}`, color}); 
                    this.$nextTick(() => { const el = document.getElementById('terminal-logs'); if(el) el.scrollTop = el.scrollHeight; }); 
                },
                
                triggerToast(title, msg, type) {
                    this.toastTitle = title; this.toastMsg = msg; this.toastType = type; this.showToast = true;
                    setTimeout(() => this.showToast = false, type === 'error' ? 6500 : 3000);
                }
            }
        }
    </script>
</body>
</html>
