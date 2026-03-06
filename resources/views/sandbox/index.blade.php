@extends('layouts.landing')

@section('title', 'Sandbox')

@section('content')
<div class="relative min-h-screen bg-slate-50 dark:bg-[#020617] text-slate-800 dark:text-slate-200 font-sans overflow-hidden selection:bg-cyan-500/30 selection:text-cyan-900 dark:selection:text-white transition-colors duration-500">

    {{-- ======================================================================
         1. BACKGROUND ATMOSPHERE
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40 transition-colors duration-500"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none opacity-0 dark:opacity-100 transition-opacity duration-500"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.02] dark:opacity-[0.035] transition-opacity duration-500"></div>
        <div id="cursor-glow" class="transition-colors duration-500"></div>
    </div>

    {{-- ======================================================================
         2. MAIN NAVBAR
         ====================================================================== --}}
    @include('layouts.partials.navbar')

    {{-- ======================================================================
         3. SANDBOX WORKSPACE
         ====================================================================== --}}
    <div class="pt-20 h-screen flex flex-col">
        
        {{-- TOOLBAR AREA --}}
        <div class="h-14 bg-white/90 dark:bg-[#0b0f19]/90 border-b border-slate-200 dark:border-white/5 flex items-center justify-between px-3 md:px-4 lg:px-6 backdrop-blur-sm shrink-0 z-40 overflow-x-auto no-scrollbar transition-colors duration-500">
            
            {{-- Left: Breadcrumb & Status --}}
            <div class="flex items-center gap-3 md:gap-4 shrink-0">
                <nav class="flex items-center gap-1.5 md:gap-2 text-[9px] md:text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-white/40 transition-colors" aria-label="Breadcrumb">
                    <a href="/" class="hover:text-slate-900 dark:hover:text-white transition-colors flex items-center gap-1 hidden sm:flex">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                    </a>
                    <span class="text-slate-300 dark:text-white/20 hidden sm:inline transition-colors">/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-slate-900 dark:hover:text-white transition-colors hidden md:inline">Dashboard</a>
                    <span class="text-slate-300 dark:text-white/20 hidden md:inline transition-colors">/</span>
                    <span class="text-emerald-600 dark:text-emerald-400 drop-shadow-none dark:drop-shadow-[0_0_8px_rgba(16,185,129,0.5)] transition-colors">Sandbox</span>
                </nav>

                <div class="h-4 w-px bg-slate-300 dark:bg-white/10 mx-1 md:mx-2 hidden sm:block transition-colors"></div>

                <div class="flex items-center gap-2 border border-emerald-200 dark:border-white/10 bg-emerald-50 dark:bg-white/5 px-2.5 py-1 rounded-md transition-colors">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-[9px] md:text-[10px] font-bold text-emerald-600 dark:text-emerald-400 tracking-wider uppercase whitespace-nowrap transition-colors">Live Env<span class="hidden md:inline">ironment</span></span>
                </div>
                
                <span id="saveStatus" class="text-[9px] text-slate-400 dark:text-white/40 hidden lg:block font-mono transition-colors">Changes saved locally</span>
            </div>

            {{-- Right: Actions --}}
            <div class="flex items-center gap-1.5 md:gap-3 shrink-0 ml-4">
                <button onclick="copyEditorCode()" class="flex items-center gap-1.5 md:gap-2 px-2 md:px-3 py-1.5 rounded-lg hover:bg-slate-100 dark:hover:bg-white/10 text-slate-500 dark:text-white/60 hover:text-slate-900 dark:hover:text-white transition text-[10px] md:text-xs font-medium border border-transparent hover:border-slate-200 dark:hover:border-white/10 group">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400 dark:text-white/40 group-hover:text-cyan-600 dark:group-hover:text-cyan-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span class="hidden sm:inline whitespace-nowrap">Copy Code</span>
                </button>

                <button onclick="resetCode()" class="flex items-center gap-1.5 md:gap-2 px-2 md:px-3 py-1.5 rounded-lg hover:bg-red-50 dark:hover:bg-red-500/10 text-slate-500 dark:text-white/60 hover:text-red-600 dark:hover:text-red-400 transition text-[10px] md:text-xs font-medium border border-transparent hover:border-red-200 dark:hover:border-red-500/20 group">
                    <svg class="w-3.5 h-3.5 md:w-4 md:h-4 text-slate-400 dark:text-white/40 group-hover:text-red-600 dark:group-hover:text-red-400 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span class="hidden sm:inline">Reset</span>
                </button>

                <div class="h-4 w-px bg-slate-300 dark:bg-white/10 mx-0.5 md:mx-1 transition-colors"></div>

                <button onclick="runPreview()" class="flex items-center gap-1.5 md:gap-2 px-3 md:px-5 py-1.5 md:py-2 rounded-lg bg-gradient-to-r from-indigo-500 to-blue-500 dark:from-indigo-600 dark:to-blue-600 hover:from-indigo-600 hover:to-blue-600 dark:hover:from-indigo-500 dark:hover:to-blue-500 text-white text-[10px] md:text-xs font-bold shadow-md dark:shadow-lg dark:shadow-indigo-500/20 transition-all hover:scale-105 active:scale-95 whitespace-nowrap">
                    <svg class="w-2.5 h-2.5 md:w-3 md:h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                    RUN <span class="hidden lg:inline opacity-70 dark:opacity-50 ml-1 font-normal">(Ctrl+Enter)</span>
                </button>
            </div>
        </div>

        {{-- SPLIT VIEW (Editor & Preview) --}}
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden">
            
            {{-- ================= LEFT: EDITOR PANE ================= --}}
            <div class="w-full md:w-1/2 flex flex-col bg-white dark:bg-[#1e1e1e] border-r border-slate-200 dark:border-white/10 relative group/editor h-1/2 md:h-full transition-colors duration-500">
                
                {{-- Editor Tabs (Tab style.css dihapus sesuai instruksi) --}}
                <div class="h-9 bg-slate-100 dark:bg-[#0f141e] flex items-end px-2 border-b border-slate-200 dark:border-white/5 select-none shrink-0 transition-colors duration-500">
                    <div class="px-4 py-2 bg-white dark:bg-[#1e1e1e] text-cyan-600 dark:text-cyan-400 text-[11px] font-bold border-t-2 border-cyan-500 rounded-t flex items-center gap-2 transition-colors">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        index.html
                    </div>
                </div>

                {{-- Monaco Container --}}
                <div id="monaco-container" class="flex-1 relative min-h-[150px]"></div>

                {{-- Console / Validation Panel --}}
                <div class="h-auto min-h-[40px] max-h-24 md:max-h-32 bg-slate-50 dark:bg-[#0f141e] border-t border-slate-200 dark:border-white/10 flex flex-col transition-colors duration-500 shrink-0">
                    <div class="px-3 py-1.5 bg-slate-100 dark:bg-[#0b0f19] border-b border-slate-200 dark:border-white/5 flex items-center justify-between transition-colors">
                        <div class="flex items-center gap-2">
                            <svg class="w-3 h-3 text-slate-400 dark:text-white/40 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-bold text-slate-500 dark:text-white/50 uppercase tracking-wider transition-colors">Console</span>
                        </div>
                        <span id="validator-badge" class="text-[9px] bg-slate-200 dark:bg-white/5 px-1.5 py-0.5 rounded text-slate-500 dark:text-white/30 transition-colors">Idle</span>
                    </div>
                    <div id="console-output" class="p-2 font-mono text-[10px] text-slate-600 dark:text-white/60 overflow-y-auto custom-scrollbar flex-1 transition-colors">
                        <span class="text-emerald-600 dark:text-emerald-500/80">root@utilwind:~$</span> System initialized. Waiting for input...
                    </div>
                </div>
            </div>

            {{-- ================= RIGHT: PREVIEW PANE ================= --}}
            <div class="w-full md:w-1/2 flex flex-col bg-white dark:bg-[#0b0f19] relative h-1/2 md:h-full border-t-4 border-slate-200 dark:border-[#0b0f19] md:border-t-0 transition-colors duration-500">
                
                {{-- Mockup Browser Toolbar --}}
                <div class="h-9 bg-slate-100 dark:bg-[#1e293b] border-b border-slate-200 dark:border-white/5 flex items-center justify-between px-3 select-none shrink-0 transition-colors">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-400/80 border border-red-500/20"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-400/80 border border-yellow-500/20"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-green-400/80 border border-green-500/20"></div>
                    </div>
                    
                    {{-- Address Bar --}}
                    <div class="flex-1 mx-4">
                        <div class="bg-white dark:bg-[#0f172a] border border-slate-200 dark:border-white/5 rounded-md px-3 py-0.5 flex items-center justify-center gap-2 shadow-sm transition-colors">
                            <svg class="w-2.5 h-2.5 text-slate-400 dark:text-slate-500 transition-colors" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span class="text-[10px] font-mono text-slate-500 dark:text-slate-400 truncate transition-colors">localhost:3000/preview</span>
                        </div>
                    </div>

                    <button onclick="runPreview()" class="text-slate-400 hover:text-slate-600 dark:hover:text-white/80 transition-colors" title="Refresh Preview">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                </div>

                {{-- Iframe Preview --}}
                <iframe id="preview-frame" class="flex-1 w-full h-full border-0 bg-white dark:bg-[#020617] transition-colors duration-500" sandbox="allow-scripts"></iframe>
                
                {{-- Resizer Visual Hint --}}
                <div class="absolute inset-y-0 left-0 w-[2px] bg-indigo-500/0 hover:bg-indigo-400 dark:hover:bg-indigo-500/50 cursor-col-resize hidden md:block transition-colors z-20"></div>
            </div>

        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div id="toast" class="fixed bottom-10 right-10 max-w-[80vw] bg-white dark:bg-[#0f141e] border border-cyan-200 dark:border-cyan-500/30 text-slate-800 dark:text-white px-4 md:px-5 py-3 rounded-xl shadow-xl dark:shadow-2xl transform translate-y-24 opacity-0 transition-all duration-300 z-[100] flex items-center gap-3 backdrop-blur-md">
        <div class="w-6 h-6 md:w-8 md:h-8 rounded-lg bg-cyan-100 dark:bg-cyan-500/20 text-cyan-600 dark:text-cyan-400 flex items-center justify-center shrink-0 transition-colors">
            <svg class="w-3 h-3 md:w-4 md:h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <h4 class="text-[11px] md:text-xs font-bold text-slate-900 dark:text-white transition-colors">Notification</h4>
            <p id="toast-msg" class="text-[9px] md:text-[10px] text-slate-500 dark:text-white/60 truncate transition-colors">Action completed successfully.</p>
        </div>
    </div>

</div>

{{-- ======================================================================
     STYLES
     ====================================================================== --}}
<style>
    /* Custom Scrollbar Dinamis */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: transparent; }
    ::-webkit-scrollbar-thumb { background: rgba(150,150,150,0.3); border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: rgba(150,150,150,0.5); }
    
    .dark ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); }
    .dark ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    .no-scrollbar::-webkit-scrollbar { display: none; }
    .no-scrollbar { -ms-overflow-style: none; scrollbar-width: none; }

    /* Animated Background Dinamis */
    #animated-bg {
        background: radial-gradient(circle at 50% 50%, rgba(76, 29, 149, 0.05), transparent 50%),
                    radial-gradient(circle at 0% 0%, rgba(217, 70, 239, 0.05), transparent 40%);
        animation: bgPulse 10s ease-in-out infinite alternate;
    }
    .dark #animated-bg {
        background: radial-gradient(circle at 50% 50%, rgba(76, 29, 149, 0.15), transparent 50%),
                    radial-gradient(circle at 0% 0%, rgba(217, 70, 239, 0.1), transparent 40%);
    }
    @keyframes bgPulse { 0% { opacity: 0.3; } 100% { opacity: 0.6; } }

    /* Cursor Glow Dinamis */
    #cursor-glow {
        position: fixed; width: 300px; height: 300px; border-radius: 50%;
        background: radial-gradient(circle, rgba(34,211,238,0.05), transparent 70%);
        filter: blur(60px); pointer-events: none; z-index: 9999; transform: translate(-50%, -50%);
    }
    .dark #cursor-glow {
        background: radial-gradient(circle, rgba(34,211,238,0.08), transparent 70%);
    }
</style>

{{-- ======================================================================
     SCRIPTS (MONACO, LOGIC, IMPORT HANDLER)
     ====================================================================== --}}
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>

<script>
    const STORAGE_KEY = 'utilwind_sandbox_v3';
    let editor = null;
    
    // Fungsi untuk mengecek secara real-time apakah tema saat ini adalah Dark Mode
    function isCurrentThemeDark() {
        return document.documentElement.classList.contains('dark');
    }

    const DEFAULT_CODE = `<div class="min-h-screen flex items-center justify-center p-4">
  <div class="max-w-md w-full bg-white dark:bg-slate-800 rounded-2xl shadow-xl overflow-hidden transform hover:scale-[1.02] transition-all duration-300 group">
    <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
        <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 p-1 bg-white dark:bg-slate-800 rounded-full transition-colors">
            <img src="https://ui-avatars.com/api/?name=Util+Wind&background=0D9488&color=fff" class="w-16 h-16 rounded-full border-2 border-white dark:border-slate-800 transition-colors">
        </div>
    </div>
    <div class="pt-12 pb-8 px-8 text-center">
        <h2 class="text-2xl font-bold text-slate-800 dark:text-white group-hover:text-indigo-600 dark:group-hover:text-indigo-400 transition-colors">Hello, Utilwind!</h2>
        <p class="text-slate-500 dark:text-slate-400 mt-2 text-sm leading-relaxed transition-colors">
            Selamat datang di Sandbox. Coba edit kode ini atau import komponen dari Gallery.
        </p>
        <button class="mt-6 px-6 py-2.5 bg-indigo-600 text-white font-medium rounded-full shadow-lg shadow-indigo-500/30 hover:bg-indigo-700 transition active:scale-95">
            Mulai Coding 🚀
        </button>
    </div>
  </div>
</div>`;

    // 1. INITIALIZE MONACO EDITOR
    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' }});

    require(['vs/editor/editor.main'], function () {
        
        const urlParams = new URLSearchParams(window.location.search);
        const importData = urlParams.get('import');
        let initialCode = DEFAULT_CODE;

        if (importData) {
            try {
                const decodedStr = decodeURIComponent(atob(importData));
                const bodyMatch = decodedStr.match(/<body[^>]*>([\s\S]*)<\/body>/i);
                
                if(bodyMatch && bodyMatch[1]) {
                     initialCode = bodyMatch[1].trim(); 
                } else {
                     initialCode = decodedStr; 
                }
                
                showToast("Komponen berhasil di-import!");
                window.history.replaceState({}, document.title, window.location.pathname);
            } catch (e) {
                console.error("Import failed:", e);
                showToast("Gagal mengimport kode.");
            }
        } else {
            initialCode = localStorage.getItem(STORAGE_KEY) || DEFAULT_CODE;
        }

        editor = monaco.editor.create(document.getElementById('monaco-container'), {
            value: initialCode,
            language: 'html',
            theme: isCurrentThemeDark() ? 'vs-dark' : 'vs', 
            fontSize: window.innerWidth < 768 ? 12 : 14, 
            fontFamily: 'Menlo, Monaco, "Courier New", monospace',
            minimap: { enabled: false }, 
            automaticLayout: true,
            padding: { top: 16, bottom: 16 },
            scrollBeyondLastLine: false,
            renderLineHighlight: 'all',
            smoothScrolling: true,
            tabSize: 2,
            wordWrap: 'on' 
        });

        runPreview();

        // Deteksi perubahan kode (Auto-save)
        editor.onDidChangeModelContent(() => {
            const code = editor.getValue();
            localStorage.setItem(STORAGE_KEY, code);
            
            const isDarkNow = isCurrentThemeDark();
            $('#saveStatus')
                .text('Saving...')
                .addClass(isDarkNow ? 'text-yellow-400' : 'text-yellow-600')
                .removeClass('text-slate-400 dark:text-white/40');
            
            // Debounce Preview
            if(window.timer) clearTimeout(window.timer);
            window.timer = setTimeout(() => {
                runPreview();
                $('#saveStatus')
                    .text('Changes saved locally')
                    .removeClass('text-yellow-400 text-yellow-600')
                    .addClass('text-slate-400 dark:text-white/40');
            }, 800); 
        });

        // Sinkronisasi Tema Monaco secara dinamis saat tombol Switcher di-klik
        const observer = new MutationObserver(function(mutations) {
            mutations.forEach(function(mutation) {
                if (mutation.attributeName === "class") {
                    const darkNow = isCurrentThemeDark();
                    monaco.editor.setTheme(darkNow ? 'vs-dark' : 'vs');
                    runPreview(); // Refresh iFrame agar temanya ikut berubah
                }
            });
        });
        observer.observe(document.documentElement, { attributes: true });
    });

    // 2. PREVIEW LOGIC
    function runPreview() {
        if (!editor) return;
        
        const rawCode = editor.getValue();
        validateCode(rawCode); 

        // Ambil tema SAAT INI (dijamin akurat mencegah bug localStorage sinkronisasi)
        const isDark = isCurrentThemeDark();
        const bgClass = isDark ? 'bg-[#020617] text-white' : 'bg-slate-50 text-slate-900';

        const fullHTML = `
            <!doctype html>
            <html lang="en" class="${isDark ? 'dark' : ''}">
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <script src="https://cdn.tailwindcss.com"><\/script>
                <script>
                  tailwind.config = { darkMode: 'class' }
                <\/script>
                <style>
                    html { scroll-behavior: smooth; }
                    body { font-family: 'Inter', sans-serif; }
                    ::-webkit-scrollbar { width: 6px; height: 6px; }
                    ::-webkit-scrollbar-track { background: ${isDark ? '#020617' : '#f1f5f9'}; }
                    ::-webkit-scrollbar-thumb { background: ${isDark ? '#334155' : '#cbd5e1'}; border-radius: 3px; }
                    ::-webkit-scrollbar-thumb:hover { background: ${isDark ? '#475569' : '#94a3b8'}; }
                </style>
            </head>
            <body class="${bgClass} transition-colors duration-500 antialiased">
                ${rawCode}
            </body>
            </html>
        `;
        
        document.getElementById('preview-frame').srcdoc = fullHTML;
    }

    // 3. UTILITY FUNCTIONS
    function resetCode() {
        if (confirm("Reset seluruh kode ke template default?")) {
            editor.setValue(DEFAULT_CODE);
            runPreview();
            showToast("Sandbox berhasil di-reset.");
        }
    }

    function copyEditorCode() {
        const code = editor.getValue();
        navigator.clipboard.writeText(code).then(() => showToast("Kode berhasil disalin!"));
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        document.getElementById('toast-msg').innerText = msg;
        toast.classList.remove('translate-y-24', 'opacity-0');
        setTimeout(() => toast.classList.add('translate-y-24', 'opacity-0'), 2500);
    }

    // 4. SIMPLE VALIDATOR (Console Feedback)
    function validateCode(code) {
        const consoleEl = document.getElementById('console-output');
        const badge = document.getElementById('validator-badge');
        const isDark = isCurrentThemeDark();
        let logs = '';
        let errors = 0;
        
        const warnClass = isDark ? 'text-yellow-400' : 'text-yellow-600 font-medium';
        const errClass = isDark ? 'text-red-400' : 'text-red-600 font-bold';
        const succClass = isDark ? 'text-emerald-400' : 'text-emerald-600 font-medium';
        const rootClass = isDark ? 'text-emerald-500/80' : 'text-emerald-600/80 font-bold';

        if (!code.includes('class=')) {
            logs += `<div class="${warnClass} mb-1">⚠ Warning: Tidak ada utility class Tailwind ditemukan.</div>`;
        }
        
        const openDivs = (code.match(/<div/g) || []).length;
        const closeDivs = (code.match(/<\/div>/g) || []).length;
        if (openDivs !== closeDivs) {
            logs += `<div class="${errClass} mb-1">❌ Error: Jumlah tag &lt;div&gt; tidak seimbang (Open: ${openDivs}, Close: ${closeDivs}).</div>`;
            errors++;
        }

        if (logs === '') {
            logs = `<div class="${succClass}">✓ Compiling... Success. Syntax looks good.</div>`;
            badge.innerText = "Valid";
            badge.className = isDark ? "text-[9px] bg-emerald-500/10 px-1.5 py-0.5 rounded text-emerald-400 border border-emerald-500/20" : "text-[9px] bg-emerald-50 px-1.5 py-0.5 rounded text-emerald-600 border border-emerald-200";
        } else {
            badge.innerText = errors > 0 ? "Error" : "Warning";
            if (errors > 0) {
                 badge.className = isDark ? "text-[9px] bg-red-500/10 px-1.5 py-0.5 rounded text-red-400 border border-red-500/20" : "text-[9px] bg-red-50 px-1.5 py-0.5 rounded text-red-600 border border-red-200";
            } else {
                 badge.className = isDark ? "text-[9px] bg-yellow-500/10 px-1.5 py-0.5 rounded text-yellow-400 border border-yellow-500/20" : "text-[9px] bg-yellow-50 px-1.5 py-0.5 rounded text-yellow-600 border border-yellow-200";
            }
        }
        
        consoleEl.innerHTML = `<span class="${rootClass}">root@utilwind:~$</span> ` + logs;
    }

    // 5. KEYBOARD SHORTCUTS
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            runPreview();
            showToast("Running code...");
        }
    });

    // 6. MOUSE GLOW & STARS (Visuals)
    if (window.innerWidth > 768) {
        $(document).mousemove(function(e) { $('#cursor-glow').css({ left: e.clientX, top: e.clientY }); });

        const c = document.getElementById('stars');
        if (c) {
            const x = c.getContext('2d');
            let s = [];
            function initStars() { c.width = window.innerWidth; c.height = window.innerHeight; s=[]; for(let i=0;i<60;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.2+0.1}); }
            function animateStars() { x.clearRect(0,0,c.width,c.height); x.fillStyle='rgba(255,255,255,0.3)'; s.forEach(t=>{ x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill(); t.y-=t.v; if(t.y<0)t.y=c.height; }); requestAnimationFrame(animateStars); }
            window.addEventListener('resize', initStars); initStars(); animateStars();
        }
    }
</script>
@endsection