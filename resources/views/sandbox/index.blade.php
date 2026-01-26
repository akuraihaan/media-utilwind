@extends('layouts.landing')

@section('title', 'Live Sandbox · TailwindLearn')

@section('content')
<div class="relative min-h-screen bg-[#020617] text-slate-200 font-sans overflow-hidden selection:bg-cyan-500/30 selection:text-white">

    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div id="animated-bg" class="absolute inset-0 opacity-60"></div>
        <div id="gradient-wave" class="absolute inset-0"></div>
        <canvas id="stars" class="absolute inset-0 pointer-events-none"></canvas>
        <div id="noise-overlay" class="absolute inset-0 z-10 opacity-[0.035]"></div>
        <div id="cursor-glow"></div>
    </div>

    <nav id="navbar" class="fixed top-0 left-0 right-0 z-50 h-[74px] w-full bg-[#020617]/80 backdrop-blur-xl border-b border-white/5 flex items-center justify-between px-6 lg:px-8 transition-all duration-500">
        
        <div class="flex items-center gap-3 cursor-pointer" onclick="window.location.href='{{ route('landing') }}'">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-fuchsia-600 to-cyan-500 flex items-center justify-center font-extrabold text-black shadow-lg shadow-fuchsia-500/20">
                TW
            </div>
            <span class="font-bold tracking-wide text-lg text-white hover:text-cyan-100 transition">TailwindLearn</span>
        </div>

        <div class="hidden md:flex gap-10 text-sm font-medium">
            <a href="{{ route('landing') }}" class="nav-link text-white/70 hover:text-white transition">Beranda</a>
            <a href="{{ route('landing') }}#tentang" class="nav-link text-white/70 hover:text-white transition">Tentang</a>
            <a href="{{ route('landing') }}#fitur" class="nav-link text-white/70 hover:text-white transition">Fitur</a>
            <a href="{{ route('landing') }}#alur" class="nav-link text-white/70 hover:text-white transition">Alur</a>
            <a href="#" class="nav-link text-white font-bold relative after:content-[''] after:absolute after:-bottom-1 after:left-0 after:w-full after:h-0.5 after:bg-cyan-400">Sandbox</a>
        </div>

        <div class="flex gap-3 items-center">
            @auth
                <span class="text-white/70 text-sm hidden sm:block font-medium">{{ Auth::user()->name }}</span>
                
                <a href="{{ route('dashboard') }}" class="px-5 py-2 rounded-xl border border-white/20 text-sm hover:bg-white/10 transition hidden lg:block">Dashboard</a>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 text-sm font-semibold shadow-lg shadow-fuchsia-900/30 hover:scale-105 transition transform">
                        Keluar
                    </button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm font-bold text-white/70 hover:text-white transition px-2">Masuk</a>
                <a href="{{ route('register') }}" class="px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-600 to-purple-600 text-sm font-semibold shadow-lg shadow-fuchsia-900/30 hover:scale-105 transition transform">
                    Mulai Belajar
                </a>
            @endauth
        </div>
    </nav>

    <div class="pt-28 pb-10 px-4 lg:px-6 h-screen flex flex-col">
        
        <div class="flex flex-col md:flex-row justify-between items-end mb-6 max-w-[1600px] mx-auto w-full gap-4">
            <div>
                <div class="flex items-center gap-2 mb-1">
                    <span class="relative flex h-2 w-2">
                      <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                      <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    <span class="text-xs font-bold tracking-widest text-emerald-400 uppercase">Live Environment</span>
                </div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight">
                    Code <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-400">Playground</span>
                </h1>
            </div>
            
            <div class="flex gap-4">
                <div class="px-4 py-2 rounded-lg bg-[#0f141e] border border-white/10 flex items-center gap-3">
                    <span class="text-xs text-white/40 uppercase font-bold">Status</span>
                    <span id="sandboxStatus" class="text-xs font-bold text-emerald-400">Ready</span>
                </div>
                <div class="px-4 py-2 rounded-lg bg-[#0f141e] border border-white/10 flex items-center gap-3">
                    <span class="text-xs text-white/40 uppercase font-bold">Mode</span>
                    <span id="sandboxMode" class="text-xs font-bold text-cyan-400">Live Preview</span>
                </div>
            </div>
        </div>

        <div class="flex-1 w-full max-w-[1600px] mx-auto relative z-10 animate-fade-in-up">
            
            <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-fuchsia-500 rounded-2xl blur opacity-20"></div>

            <div class="relative h-full flex flex-col bg-[#0b0f19] border border-white/10 rounded-2xl shadow-2xl overflow-hidden">
                
                <div class="h-12 bg-[#0f141e] border-b border-white/5 flex items-center justify-between px-4 select-none">
                    <div class="flex gap-2 h-full items-end">
                        <div class="px-4 py-2 bg-[#0b0f19] border-t border-x border-white/10 rounded-t-lg text-xs font-bold text-cyan-400 flex items-center gap-2 relative top-[1px]">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            index.html
                        </div>
                        <div class="px-4 py-2 text-xs font-medium text-white/40 hover:text-white/80 transition cursor-not-allowed">
                            style.css <span class="text-[10px] bg-white/10 px-1 rounded ml-1">CDN</span>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <button onclick="toggleLiveMode()" class="p-1.5 rounded-lg hover:bg-white/10 text-white/60 hover:text-cyan-400 transition" title="Toggle Live/Manual Mode">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                        </button>
                        <div class="w-px h-4 bg-white/10"></div>
                        <button onclick="resetSandbox()" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-red-500/10 hover:bg-red-500/20 text-red-400 text-xs font-bold border border-red-500/20 transition">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                            Reset Code
                        </button>
                    </div>
                </div>

                <div class="flex-1 grid lg:grid-cols-2 overflow-hidden">
                    
                    <div class="flex flex-col border-r border-white/5 bg-[#1e1e1e] relative group">
                        <div id="editor" class="flex-1"></div>
                        
                        <div class="h-36 bg-[#0f141e] border-t border-white/10 flex flex-col">
                            <div class="px-4 py-1 bg-[#0b0f19] border-b border-white/5 flex justify-between items-center">
                                <span class="text-[10px] font-bold uppercase tracking-wider text-white/40">Terminal / Validation Output</span>
                                <span class="flex gap-1.5">
                                    <span class="w-2 h-2 rounded-full bg-red-500/50"></span>
                                    <span class="w-2 h-2 rounded-full bg-yellow-500/50"></span>
                                    <span class="w-2 h-2 rounded-full bg-green-500/50"></span>
                                </span>
                            </div>
                            <ul id="validationList" class="flex-1 p-3 overflow-y-auto font-mono text-xs space-y-2">
                                <li class="text-white/30 animate-pulse">_ System ready. Waiting for input...</li>
                            </ul>
                        </div>
                    </div>

                    <div class="bg-white flex flex-col relative">
                        <div class="h-8 bg-gray-100 border-b border-gray-200 flex items-center px-3 gap-2">
                            <div class="flex gap-1.5">
                                <div class="w-2.5 h-2.5 rounded-full bg-red-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-yellow-400"></div>
                                <div class="w-2.5 h-2.5 rounded-full bg-green-400"></div>
                            </div>
                            <div class="flex-1 text-center">
                                <div class="inline-block bg-white px-3 py-0.5 rounded text-[10px] text-gray-400 border border-gray-200 shadow-sm">
                                    localhost:8000
                                </div>
                            </div>
                        </div>
                        <iframe id="preview" class="flex-1 w-full h-full border-0 bg-white"></iframe>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Background Animations */
    @keyframes bgMove { to { transform: scale(1.15); } }
    @keyframes waveMove { 0%{background-position:0% 50%} 50%{background-position:100% 50%} 100%{background-position:0% 50%} }
    @keyframes fadeInUp { from { opacity: 0; transform: translateY(20px); } to { opacity: 1; transform: translateY(0); } }

    .animate-fade-in-up { animation: fadeInUp 0.8s cubic-bezier(0.16, 1, 0.3, 1) forwards; opacity: 0; }

    #animated-bg {
        background: radial-gradient(600px circle at 20% 20%, rgba(217,70,239,.15), transparent 40%),
                    radial-gradient(700px circle at 80% 30%, rgba(34,211,238,.15), transparent 40%),
                    radial-gradient(800px circle at 50% 80%, rgba(168,85,247,.15), transparent 40%);
        animation: bgMove 20s ease-in-out infinite alternate;
    }
    #gradient-wave {
        background: linear-gradient(120deg,rgba(217,70,239,.05),rgba(34,211,238,.05),rgba(168,85,247,.05));
        background-size: 400% 400%; animation: waveMove 26s ease infinite;
    }
    #noise-overlay {
        background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 200 200'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.8' numOctaves='4'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)'/%3E%3C/svg%3E");
        mix-blend-mode: overlay;
    }
    #cursor-glow {
        position: fixed; width: 400px; height: 400px; border-radius: 50%;
        background: radial-gradient(circle, rgba(217,70,239,.1), transparent 65%);
        filter: blur(80px); pointer-events: none; z-index: 999; transform: translate(-50%,-50%);
    }

    #validationList::-webkit-scrollbar { width: 6px; }
    #validationList::-webkit-scrollbar-track { background: #0f141e; }
    #validationList::-webkit-scrollbar-thumb { background: #334155; border-radius: 3px; }
    #validationList::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>

<script src="https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs/loader.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
    $(document).ready(function() {
        const c = document.getElementById('stars');
        if(c) {
            const x = c.getContext('2d');
            let s = [];
            function initStars() { c.width = window.innerWidth; c.height = window.innerHeight; s=[]; for(let i=0;i<60;i++)s.push({x:Math.random()*c.width,y:Math.random()*c.height,r:Math.random()*1.5,v:Math.random()*0.2+0.1}); }
            function animateStars() { x.clearRect(0,0,c.width,c.height); x.fillStyle='rgba(255,255,255,0.3)'; s.forEach(t=>{ x.beginPath(); x.arc(t.x,t.y,t.r,0,6.28); x.fill(); t.y-=t.v; if(t.y<0)t.y=c.height; }); requestAnimationFrame(animateStars); }
            window.addEventListener('resize', initStars); initStars(); animateStars();
        }
        $(document).mousemove(function(e) { $('#cursor-glow').css({ left: e.clientX, top: e.clientY }); });
    });

    const STORAGE_KEY = 'tailwind_sandbox_pro_v1';
    let editor = null;
    let liveMode = true;
    let debounceTimer = null;

    const DEFAULT_CODE = `<div class="min-h-screen flex items-center justify-center bg-slate-100">
  <div class="p-8 bg-white rounded-2xl shadow-xl text-center max-w-sm">
    <div class="w-16 h-16 bg-blue-500 rounded-full mx-auto flex items-center justify-center mb-4 shadow-lg shadow-blue-500/30">
      <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
    </div>
    <h1 class="text-2xl font-bold text-slate-800 mb-2">Hello World!</h1>
    <p class="text-slate-500">
      Coba ubah warna background di atas menjadi <code class="text-pink-500 font-bold">bg-pink-500</code>.
    </p>
    <button class="mt-6 px-6 py-2 bg-slate-900 text-white rounded-lg hover:scale-105 transition">
      Mulai Eksperimen
    </button>
  </div>
</div>`;

    function buildHTML(code) {
        return `
        <!doctype html>
        <html>
        <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width,initial-scale=1">
        <script src="https://cdn.tailwindcss.com"><\/script>
        </head>
        <body>
        ${code}
        </body>
        </html>`;
    }

    function runPreview() {
        if (!editor) return;
        document.getElementById('preview').srcdoc = buildHTML(editor.getValue());
    }

    function resetSandbox() {
        if (!editor) return;
        editor.setValue(DEFAULT_CODE);
        localStorage.removeItem(STORAGE_KEY);
        runPreview();
        setStatus('Reset Complete');
    }

    function setStatus(text) {
        $('#sandboxStatus').text(text).addClass('animate-pulse');
        setTimeout(() => $('#sandboxStatus').removeClass('animate-pulse'), 500);
    }

    function validateCode(code) {
        const rules = [
            { ok: /<h1[\s>]/i.test(code), text: 'Gunakan elemen <h1>' },
            { ok: (code.match(/<p[\s>]/gi) || []).length >= 1, text: 'Minimal 1 paragraf <p>' },
            { ok: /class=".*(bg-|text-|p-|m-|flex|grid)/.test(code), text: 'Gunakan utility class Tailwind' }
        ];

        let html = '';
        let allOk = true;

        rules.forEach(r => {
            if(!r.ok) allOk = false;
            const color = r.ok ? 'text-emerald-400' : 'text-red-400';
            const icon = r.ok ? '[OK]' : '[ERR]';
            html += `<li class="${color} flex gap-2">
                <span class="opacity-50">${icon}</span> 
                <span>${r.text}</span>
            </li>`;
        });

        if(allOk) {
             html += `<li class="text-cyan-400 mt-2 border-t border-white/10 pt-2">> Code looks great! Ready to build.</li>`;
        }

        $('#validationList').html(html);
        setStatus(allOk ? 'Valid' : 'Typing...');
    }

    require.config({ paths: { vs: 'https://cdn.jsdelivr.net/npm/monaco-editor@0.45.0/min/vs' }});

    require(['vs/editor/editor.main'], () => {
        const saved = localStorage.getItem(STORAGE_KEY) || DEFAULT_CODE;
        editor = monaco.editor.create(document.getElementById('editor'), {
            value: saved,
            language: 'html',
            theme: 'vs-dark', 
            fontSize: 14,
            fontFamily: 'Menlo, Monaco, "Courier New", monospace',
            minimap: { enabled: false },
            automaticLayout: true,
            wordWrap: 'on',
            padding: { top: 20, bottom: 20 },
            scrollBeyondLastLine: false,
            smoothScrolling: true,
            cursorSmoothCaretAnimation: true,
            renderLineHighlight: 'all'
        });

        editor.onDidChangeModelContent(() => {
            localStorage.setItem(STORAGE_KEY, editor.getValue());
            validateCode(editor.getValue());
            if (!liveMode) return;
            clearTimeout(debounceTimer);
            debounceTimer = setTimeout(runPreview, 300);
        });

        runPreview();
        validateCode(saved);
    });

    window.addEventListener('keydown', e => {
        if (e.ctrlKey && e.key === 'Enter') {
            e.preventDefault();
            runPreview();
            setStatus('Manual Run');
        }
    });

    function toggleLiveMode() {
        liveMode = !liveMode;
        $('#sandboxMode').text(liveMode ? 'Live Preview' : 'Manual Run (Ctrl+Enter)');
        setStatus(liveMode ? 'Mode: Live' : 'Mode: Manual');
    }
</script>
@endsection