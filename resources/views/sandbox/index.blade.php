@extends('layouts.landing')

@section('title', 'Sandbox')

@section('content')
<div class="relative min-h-screen bg-[#020617] text-slate-200 font-sans overflow-hidden selection:bg-cyan-500/30 selection:text-white">

    {{-- ======================================================================
         1. BACKGROUND ATMOSPHERE (Konsisten dengan halaman lain)
         ====================================================================== --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-40"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    {{-- ======================================================================
         2. MAIN NAVBAR (Partial)
         ====================================================================== --}}
    @include('layouts.partials.navbar')

    {{-- ======================================================================
         3. SANDBOX WORKSPACE
         Padding-top disesuaikan (pt-20) agar pas di bawah navbar fixed.
         ====================================================================== --}}
    <div class="pt-20 h-screen flex flex-col">
        
        {{-- TOOLBAR AREA (Secondary Nav specific for Sandbox) --}}
        <div class="h-14 bg-[#0b0f19]/90 border-b border-white/5 flex items-center justify-between px-4 lg:px-6 backdrop-blur-sm shrink-0 z-40">
            
            {{-- Left: Status --}}
            <div class="flex items-center gap-4">
                <div class="flex items-center gap-2">
                    <span class="relative flex h-2.5 w-2.5">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold text-emerald-400 tracking-wider uppercase">Live Environment</span>
                </div>
                <div class="h-4 w-px bg-white/10 hidden sm:block"></div>
                <span id="saveStatus" class="text-[10px] text-white/40 hidden sm:block font-mono">Changes saved locally</span>
            </div>

            {{-- Right: Actions --}}
            <div class="flex items-center gap-3">
                <button onclick="copyEditorCode()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-white/10 text-white/60 hover:text-white transition text-xs font-medium border border-transparent hover:border-white/10 group">
                    <svg class="w-4 h-4 text-white/40 group-hover:text-cyan-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"/></svg>
                    <span class="hidden sm:inline">Copy Code</span>
                </button>

                <button onclick="resetCode()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg hover:bg-red-500/10 text-white/60 hover:text-red-400 transition text-xs font-medium border border-transparent hover:border-red-500/20 group">
                    <svg class="w-4 h-4 text-white/40 group-hover:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                    <span class="hidden sm:inline">Reset</span>
                </button>

                <div class="h-4 w-px bg-white/10 mx-1"></div>

                <button onclick="runPreview()" class="flex items-center gap-2 px-5 py-2 rounded-lg bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white text-xs font-bold shadow-lg shadow-indigo-500/20 transition hover:scale-105 active:scale-95">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM9.555 7.168A1 1 0 008 8v4a1 1 0 001.555.832l3-2a1 1 0 000-1.664l-3-2z" clip-rule="evenodd"/></svg>
                    RUN <span class="hidden md:inline opacity-50 ml-1 font-normal">(Ctrl+Enter)</span>
                </button>
            </div>
        </div>

        {{-- SPLIT VIEW (Editor & Preview) --}}
        <div class="flex-1 flex flex-col md:flex-row overflow-hidden">
            
            {{-- ================= LEFT: EDITOR PANE ================= --}}
            <div class="w-full md:w-1/2 flex flex-col bg-[#1e1e1e] border-r border-white/10 relative group/editor">
                
                {{-- Editor Tabs --}}
                <div class="h-9 bg-[#0f141e] flex items-end px-2 border-b border-white/5 select-none">
                    <div class="px-4 py-2 bg-[#1e1e1e] text-cyan-400 text-[11px] font-bold border-t-2 border-cyan-500 rounded-t flex items-center gap-2">
                        <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                        index.html
                    </div>
                    <div class="px-4 py-2 text-white/30 text-[11px] font-medium hover:text-white/60 transition cursor-not-allowed flex items-center gap-2">
                        <span class="w-2 h-2 rounded-full bg-blue-500"></span> style.css
                    </div>
                </div>

                {{-- Monaco Container --}}
                <div id="monaco-container" class="flex-1 relative"></div>

                {{-- Console / Validation Panel --}}
                <div class="h-auto min-h-[40px] max-h-32 bg-[#0f141e] border-t border-white/10 flex flex-col transition-all">
                    <div class="px-3 py-1.5 bg-[#0b0f19] border-b border-white/5 flex items-center justify-between">
                        <div class="flex items-center gap-2">
                            <svg class="w-3 h-3 text-white/40" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 9l3 3-3 3m5 0h3M5 20h14a2 2 0 002-2V6a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                            <span class="text-[10px] font-bold text-white/50 uppercase tracking-wider">Console</span>
                        </div>
                        <span id="validator-badge" class="text-[9px] bg-white/5 px-1.5 py-0.5 rounded text-white/30">Idle</span>
                    </div>
                    <div id="console-output" class="p-2 font-mono text-[10px] text-white/60 overflow-y-auto custom-scrollbar flex-1">
                        <span class="text-emerald-500/80">root@utilwind:~$</span> System initialized. Waiting for input...
                    </div>
                </div>
            </div>

            {{-- ================= RIGHT: PREVIEW PANE ================= --}}
            <div class="w-full md:w-1/2 flex flex-col bg-white relative">
                
                {{-- Mockup Browser Toolbar --}}
                <div class="h-9 bg-gray-100 border-b border-gray-200 flex items-center justify-between px-3 select-none">
                    <div class="flex gap-1.5">
                        <div class="w-2.5 h-2.5 rounded-full bg-red-400/80 border border-red-500/20"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-yellow-400/80 border border-yellow-500/20"></div>
                        <div class="w-2.5 h-2.5 rounded-full bg-green-400/80 border border-green-500/20"></div>
                    </div>
                    
                    {{-- Address Bar --}}
                    <div class="flex-1 mx-4">
                        <div class="bg-white border border-gray-300 rounded-md px-3 py-0.5 flex items-center justify-center gap-2 shadow-sm">
                            <svg class="w-2.5 h-2.5 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/></svg>
                            <span class="text-[10px] font-mono text-gray-500">localhost:3000/preview</span>
                        </div>
                    </div>

                    <button onclick="runPreview()" class="text-gray-400 hover:text-gray-600 transition" title="Refresh Preview">
                        <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </button>
                </div>

                {{-- Iframe Preview --}}
                <iframe id="preview-frame" class="flex-1 w-full h-full border-0 bg-white" sandbox="allow-scripts"></iframe>
                
                {{-- Resizer Visual Hint (Optional) --}}
                <div class="absolute inset-y-0 left-0 w-[2px] bg-indigo-500/0 hover:bg-indigo-500/50 cursor-col-resize hidden md:block transition-colors z-20"></div>
            </div>

        </div>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div id="toast" class="fixed bottom-10 right-10 bg-[#0f141e] border border-cyan-500/30 text-white px-5 py-3 rounded-xl shadow-2xl transform translate-y-24 opacity-0 transition-all duration-300 z-[100] flex items-center gap-3 backdrop-blur-md">
        <div class="w-8 h-8 rounded-lg bg-cyan-500/20 text-cyan-400 flex items-center justify-center">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg>
        </div>
        <div>
            <h4 class="text-xs font-bold text-white">Notification</h4>
            <p id="toast-msg" class="text-[10px] text-white/60">Action completed successfully.</p>
        </div>
    </div>

</div>

{{-- ======================================================================
     STYLES
     ====================================================================== --}}
<style>
    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 6px; height: 6px; }
    ::-webkit-scrollbar-track { background: #0f141e; }
    ::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    ::-webkit-scrollbar-thumb:hover { background: #475569; }

    /* Animated Background */
    #animated-bg {
        background: radial-gradient(circle at 50% 50%, rgba(76, 29, 149, 0.15), transparent 50%),
                    radial-gradient(circle at 0% 0%, rgba(217, 70, 239, 0.1), transparent 40%);
        animation: bgPulse 10s ease-in-out infinite alternate;
    }
    @keyframes bgPulse { 0% { opacity: 0.3; } 100% { opacity: 0.6; } }

    /* Cursor Glow */
    #cursor-glow {
        position: fixed; width: 300px; height: 300px; border-radius: 50%;
        background: radial-gradient(circle, rgba(34,211,238,0.08), transparent 70%);
        filter: blur(60px); pointer-events: none; z-index: 9999; transform: translate(-50%, -50%);
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
    let isLive = true;

    // DEFAULT TEMPLATE (Jika kosong)
    const DEFAULT_CODE = `<div class="min-h-screen flex items-center justify-center bg-slate-50">
  <div class="max-w-md w-full bg-white rounded-2xl shadow-xl overflow-hidden transform hover:scale-105 transition-all duration-300 group">
    <div class="h-32 bg-gradient-to-r from-indigo-500 to-purple-600 relative">
        <div class="absolute -bottom-8 left-1/2 -translate-x-1/2 p-1 bg-white rounded-full">
            <img src="https://ui-avatars.com/api/?name=Util+Wind&background=0D9488&color=fff" class="w-16 h-16 rounded-full border-2 border-white">
        </div>
    </div>
    <div class="pt-12 pb-8 px-8 text-center">
        <h2 class="text-2xl font-bold text-slate-800 group-hover:text-indigo-600 transition">Hello, Utilwind!</h2>
        <p class="text-slate-500 mt-2 text-sm">
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
        
        // Logic Import: Cek URL Param 'import' dulu, jika tidak ada baru load LocalStorage
        const urlParams = new URLSearchParams(window.location.search);
        const importData = urlParams.get('import');
        let initialCode = DEFAULT_CODE;

        if (importData) {
            try {
                // Decode Base64
                initialCode = decodeURIComponent(atob(importData));
                console.log("Import success from Gallery!");
                showToast("Komponen berhasil di-import!");
                
                // Clean URL (Optional, biar rapi)
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
            theme: 'vs-dark', // Dark theme matching UI
            fontSize: 14,
            fontFamily: 'Menlo, Monaco, "Courier New", monospace',
            minimap: { enabled: false }, // Hemat tempat
            automaticLayout: true,
            padding: { top: 20, bottom: 20 },
            scrollBeyondLastLine: false,
            renderLineHighlight: 'all',
            smoothScrolling: true,
            tabSize: 2
        });

        // First Run
        runPreview();

        // Event Listener: Change
        editor.onDidChangeModelContent(() => {
            const code = editor.getValue();
            localStorage.setItem(STORAGE_KEY, code);
            $('#saveStatus').text('Saving...').addClass('text-yellow-400');
            
            // Debounce Preview
            if(window.timer) clearTimeout(window.timer);
            window.timer = setTimeout(() => {
                runPreview();
                $('#saveStatus').text('Changes saved locally').removeClass('text-yellow-400');
            }, 800); // Delay 800ms
        });
    });

    // 2. PREVIEW LOGIC (Inject Tailwind CDN)
    function runPreview() {
        if (!editor) return;
        
        const rawCode = editor.getValue();
        validateCode(rawCode); // Run Validator

        const fullHTML = `
            <!doctype html>
            <html>
            <head>
                <meta charset="UTF-8">
                <meta name="viewport" content="width=device-width, initial-scale=1.0">
                <script src="https://cdn.tailwindcss.com"><\/script>
                <style>
                    html { scroll-behavior: smooth; }
                    body { font-family: 'Inter', sans-serif; }
                    /* Scrollbar custom di dalam iframe */
                    ::-webkit-scrollbar { width: 8px; }
                    ::-webkit-scrollbar-track { background: #f1f1f1; }
                    ::-webkit-scrollbar-thumb { background: #c1c1c1; border-radius: 4px; }
                    ::-webkit-scrollbar-thumb:hover { background: #a8a8a8; }
                </style>
            </head>
            <body>
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
        let logs = '';
        let errors = 0;
        
        // Cek 1: Class Tailwind
        if (!code.includes('class=')) {
            logs += `<div class="text-yellow-400 mb-1">⚠ Warning: Tidak ada utility class Tailwind ditemukan.</div>`;
        }
        
        // Cek 2: Struktur Div
        const openDivs = (code.match(/<div/g) || []).length;
        const closeDivs = (code.match(/<\/div>/g) || []).length;
        if (openDivs !== closeDivs) {
            logs += `<div class="text-red-400 mb-1">❌ Error: Jumlah tag &lt;div&gt; tidak seimbang (Open: ${openDivs}, Close: ${closeDivs}).</div>`;
            errors++;
        }

        // Output Logic
        if (logs === '') {
            logs = `<div class="text-emerald-400">✓ Compiling... Success. Syntax looks good.</div>`;
            badge.innerText = "Valid";
            badge.className = "text-[9px] bg-emerald-500/10 px-1.5 py-0.5 rounded text-emerald-400 border border-emerald-500/20";
        } else {
            badge.innerText = errors > 0 ? "Error" : "Warning";
            badge.className = errors > 0 
                ? "text-[9px] bg-red-500/10 px-1.5 py-0.5 rounded text-red-400 border border-red-500/20"
                : "text-[9px] bg-yellow-500/10 px-1.5 py-0.5 rounded text-yellow-400 border border-yellow-500/20";
        }
        
        consoleEl.innerHTML = `<span class="text-emerald-500/80">root@utilwind:~$</span> ` + logs;
    }

    // 5. KEYBOARD SHORTCUTS
    document.addEventListener('keydown', function(e) {
        // Ctrl + Enter to Run
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            e.preventDefault();
            runPreview();
            showToast("Running code...");
        }
    });

    // 6. MOUSE GLOW & STARS (Visuals)
    $(document).mousemove(function(e) { $('#cursor-glow').css({ left: e.clientX, top: e.clientY }); });

    const c = document.getElementById('stars');
    if (c) {
        const x = c.getContext('2d');
        let s = [];
        function initStars() { c.width = window.innerWidth; c.height = window.innerHeight; s=[]; for(let i=0;i<60;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.2+0.1}); }
        function animateStars() { x.clearRect(0,0,c.width,c.height); x.fillStyle='rgba(255,255,255,0.3)'; s.forEach(t=>{ x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill(); t.y-=t.v; if(t.y<0)t.y=c.height; }); requestAnimationFrame(animateStars); }
        window.addEventListener('resize', initStars); initStars(); animateStars();
    }

</script>
@endsection