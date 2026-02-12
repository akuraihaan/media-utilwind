@extends('layouts.landing')

@section('title', 'UI Component Gallery · Utilwind')

@php
    // =======================================================================
    // ULTIMATE COMPONENT DATABASE
    // =======================================================================
    $gallery = [
        'buttons' => [
            'title' => 'Buttons',
            'icon' => '🖱️',
            'desc' => 'Koleksi tombol interaktif dengan berbagai style dan state.',
            'items' => [
                [
                    'id' => 'btn-primary',
                    'name' => 'Primary Brand',
                    'code' => '<button class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-[#020617] transition-all duration-300">
  Primary Action
</button>'
                ],
                [
                    'id' => 'btn-glow',
                    'name' => 'Neon Glow',
                    'code' => '<button class="relative px-8 py-3 rounded-full bg-fuchsia-600 text-white font-bold transition-all duration-300 hover:shadow-[0_0_20px_rgba(192,38,211,0.6)] hover:scale-105 active:scale-95 group">
  <span class="relative z-10 flex items-center gap-2">
    Launch
    <svg class="w-4 h-4 group-hover:translate-x-1 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
  </span>
  <div class="absolute inset-0 rounded-full bg-fuchsia-600 blur-sm opacity-50 group-hover:opacity-100 transition duration-300"></div>
</button>'
                ],
                [
                    'id' => 'btn-outline',
                    'name' => 'Glass Outline',
                    'code' => '<button class="px-6 py-2.5 bg-white/5 border border-white/10 text-white font-medium rounded-lg hover:bg-white/10 hover:border-white/30 transition-all backdrop-blur-sm shadow-sm">
  Documentation
</button>'
                ],
                [
                    'id' => 'btn-gradient',
                    'name' => 'Gradient Border',
                    'code' => '<button class="relative p-[2px] rounded-lg bg-gradient-to-r from-cyan-500 via-blue-500 to-indigo-500 hover:scale-[1.02] transition-transform shadow-lg shadow-cyan-500/20">
  <div class="px-6 py-2 bg-[#020617] rounded-[6px] text-white font-bold relative z-10 flex items-center gap-2">
    <span>✨</span> Special Offer
  </div>
</button>'
                ],
                [
                    'id' => 'btn-icon',
                    'name' => 'Icon Only',
                    'code' => '<div class="flex gap-4">
  <button class="p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
  <button class="p-3 bg-white/10 text-white rounded-full hover:bg-white/20 transition border border-white/10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
</div>'
                ],
                [
                    'id' => 'btn-loading',
                    'name' => 'Loading State',
                    'code' => '<button class="px-6 py-2.5 bg-indigo-600/50 text-indigo-200 font-semibold rounded-lg cursor-not-allowed flex items-center gap-3" disabled>
  <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
  Processing...
</button>'
                ]
            ]
        ],
        'alerts' => [
            'title' => 'Alerts & Badges',
            'icon' => '🔔',
            'desc' => 'Notifikasi dan label status untuk feedback pengguna.',
            'items' => [
                [
                    'id' => 'alert-success',
                    'name' => 'Success Alert',
                    'code' => '<div class="flex items-center gap-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 shadow-lg shadow-emerald-900/10 max-w-md w-full">
  <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0 border border-emerald-500/30">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
  </div>
  <div>
    <h4 class="font-bold text-sm">Pembayaran Berhasil!</h4>
    <p class="text-xs opacity-80 mt-1">Transaksi Anda telah dikonfirmasi oleh sistem.</p>
  </div>
</div>'
                ],
                [
                    'id' => 'alert-warning',
                    'name' => 'Warning Glass',
                    'code' => '<div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 backdrop-blur-md flex gap-3 max-w-md w-full">
  <svg class="w-5 h-5 text-yellow-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
  <div>
    <h3 class="text-sm font-bold text-yellow-400">Penyimpanan Hampir Penuh</h3>
    <p class="text-xs text-yellow-200/70 mt-1">Harap hapus beberapa file untuk melanjutkan upload.</p>
  </div>
</div>'
                ],
                [
                    'id' => 'badge-group',
                    'name' => 'Status Badges',
                    'code' => '<div class="flex flex-wrap gap-2">
  <span class="px-2.5 py-0.5 rounded-full bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">Beta</span>
  <span class="px-2.5 py-0.5 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold border border-emerald-500/20 flex items-center gap-1"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Online</span>
  <span class="px-2.5 py-0.5 rounded-full bg-red-500/10 text-red-400 text-xs font-bold border border-red-500/20">High Priority</span>
  <span class="px-2.5 py-0.5 rounded text-xs font-mono bg-white/10 text-white/70 border border-white/10">v2.0.1</span>
</div>'
                ]
            ]
        ],
        'cards' => [
            'title' => 'Cards',
            'icon' => '🗂️',
            'desc' => 'Container fleksibel untuk menampilkan konten.',
            'items' => [
                [
                    'id' => 'card-glass',
                    'name' => 'Glass Effect Card',
                    'code' => '<div class="relative w-80 p-6 bg-white/5 border border-white/10 rounded-2xl shadow-2xl backdrop-blur-md overflow-hidden group">
  <div class="absolute -top-10 -right-10 w-32 h-32 bg-fuchsia-600/20 rounded-full blur-2xl group-hover:bg-fuchsia-600/30 transition duration-500"></div>
  <div class="relative z-10">
    <div class="w-12 h-12 bg-white/10 rounded-xl flex items-center justify-center text-2xl mb-4 border border-white/10">✨</div>
    <h3 class="text-lg font-bold text-white mb-2">Modern UI</h3>
    <p class="text-white/60 text-sm leading-relaxed mb-6">Designed with glassmorphism principles for a sleek, modern aesthetic that fits dark mode perfectly.</p>
    <button class="text-sm font-bold text-fuchsia-400 hover:text-fuchsia-300 transition flex items-center gap-1">Learn more →</button>
  </div>
</div>'
                ],
                [
                    'id' => 'card-image',
                    'name' => 'Image Card',
                    'code' => '<div class="w-72 bg-[#0f141e] border border-white/10 rounded-2xl overflow-hidden shadow-lg group hover:-translate-y-1 transition duration-300">
  <div class="h-40 overflow-hidden relative">
    <img src="https://images.unsplash.com/photo-1635776062127-d379bfcba9f8?q=80&w=1000&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
    <div class="absolute inset-0 bg-gradient-to-t from-[#0f141e] to-transparent opacity-80"></div>
    <span class="absolute top-3 right-3 bg-black/50 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-white border border-white/10">Technology</span>
  </div>
  <div class="p-5">
    <h3 class="text-white font-bold text-lg mb-2 leading-tight group-hover:text-cyan-400 transition">Future of AI Development</h3>
    <p class="text-white/40 text-xs mb-4">Explore how artificial intelligence changes coding.</p>
    <div class="flex items-center gap-3 border-t border-white/5 pt-4">
        <div class="w-6 h-6 rounded-full bg-indigo-500"></div>
        <span class="text-xs text-white/70">By Admin</span>
        <span class="text-xs text-white/30 ml-auto">5 min read</span>
    </div>
  </div>
</div>'
                ],
                [
                    'id' => 'card-stats',
                    'name' => 'Statistic Widget',
                    'code' => '<div class="flex gap-4">
  <div class="p-5 rounded-2xl bg-[#020617] border border-white/10 w-40 hover:border-cyan-500/50 transition cursor-pointer group">
    <div class="text-white/40 text-xs font-bold uppercase mb-2">Total Revenue</div>
    <div class="text-2xl font-black text-white group-hover:text-cyan-400 transition">$42,500</div>
    <div class="text-[10px] text-emerald-400 mt-1 flex items-center gap-1">
        <span>↑ 12%</span> <span class="text-white/20">vs last month</span>
    </div>
  </div>
</div>'
                ]
            ]
        ],
        'forms' => [
            'title' => 'Forms',
            'icon' => '📝',
            'desc' => 'Input, Select, dan elemen form lainnya.',
            'items' => [
                [
                    'id' => 'inp-icon',
                    'name' => 'Input with Icon',
                    'code' => '<div class="w-full max-w-sm">
  <label class="block text-xs font-bold text-white/60 uppercase mb-2">Email Address</label>
  <div class="relative">
    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
      <svg class="w-5 h-5 text-white/30" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
    </div>
    <input type="email" class="block w-full pl-10 pr-3 py-2.5 border border-white/10 rounded-lg leading-5 bg-white/5 text-white placeholder-white/30 focus:outline-none focus:bg-white/10 focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 sm:text-sm transition-all" placeholder="you@example.com">
  </div>
</div>'
                ],
                [
                    'id' => 'inp-file',
                    'name' => 'File Upload Zone',
                    'code' => '<div class="flex items-center justify-center w-full max-w-sm">
    <label for="dropzone-file" class="flex flex-col items-center justify-center w-full h-32 border-2 border-white/10 border-dashed rounded-xl cursor-pointer bg-white/[0.02] hover:bg-white/[0.05] hover:border-indigo-500/50 transition-all group">
        <div class="flex flex-col items-center justify-center pt-5 pb-6">
            <svg class="w-8 h-8 mb-2 text-white/30 group-hover:text-indigo-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
            <p class="text-xs text-white/50"><span class="font-bold text-white">Click to upload</span> or drag and drop</p>
        </div>
        <input id="dropzone-file" type="file" class="hidden" />
    </label>
</div>'
                ],
                [
                    'id' => 'inp-toggle',
                    'name' => 'Toggle Switch',
                    'code' => '<label class="inline-flex items-center cursor-pointer group">
  <input type="checkbox" value="" class="sr-only peer" checked>
  <div class="w-11 h-6 bg-white/10 peer-focus:outline-none rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-600 relative"></div>
  <span class="ml-3 text-sm font-medium text-white/70 group-hover:text-white transition">Enable Notifications</span>
</label>'
                ]
            ]
        ],
        'navigation' => [
            'title' => 'Navigation',
            'icon' => '🧭',
            'desc' => 'Tabs, Breadcrumbs, dan Navigasi.',
            'items' => [
                [
                    'id' => 'nav-tabs',
                    'name' => 'Pill Tabs',
                    'code' => '<div class="inline-flex p-1 bg-white/5 rounded-xl border border-white/5">
  <button class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg shadow-lg">Account</button>
  <button class="px-4 py-2 text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition">Security</button>
  <button class="px-4 py-2 text-sm font-medium text-white/60 hover:text-white hover:bg-white/5 rounded-lg transition">Billing</button>
</div>'
                ],
                [
                    'id' => 'nav-breadcrumbs',
                    'name' => 'Breadcrumbs',
                    'code' => '<nav class="flex" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    <li class="inline-flex items-center text-sm font-medium text-white/50 hover:text-white">Home</li>
    <li><span class="mx-2 text-white/30">/</span></li>
    <li class="inline-flex items-center text-sm font-medium text-white/50 hover:text-white">Projects</li>
    <li><span class="mx-2 text-white/30">/</span></li>
    <li aria-current="page" class="text-sm font-medium text-indigo-400">Settings</li>
  </ol>
</nav>'
                ]
            ]
        ],
        'avatars' => [
            'title' => 'Avatars',
            'icon' => '👤',
            'desc' => 'Representasi user dan grup.',
            'items' => [
                [
                    'id' => 'av-status',
                    'name' => 'Avatar with Status',
                    'code' => '<div class="flex items-center gap-4">
    <div class="relative">
        <img class="w-10 h-10 rounded-full border-2 border-[#020617]" src="https://ui-avatars.com/api/?name=A+D&background=random" alt="">
        <span class="bottom-0 left-7 absolute  w-3.5 h-3.5 bg-green-400 border-2 border-[#020617] rounded-full"></span>
    </div>
    <div class="relative">
        <img class="w-10 h-10 rounded-full border-2 border-[#020617]" src="https://ui-avatars.com/api/?name=B+C&background=random" alt="">
        <span class="top-0 left-7 absolute  w-3.5 h-3.5 bg-red-500 border-2 border-[#020617] rounded-full"></span>
    </div>
</div>'
                ],
                [
                    'id' => 'av-group',
                    'name' => 'Avatar Group',
                    'code' => '<div class="flex -space-x-3">
    <img class="w-10 h-10 border-2 border-[#020617] rounded-full" src="https://ui-avatars.com/api/?name=Alice&background=ef4444&color=fff" alt="">
    <img class="w-10 h-10 border-2 border-[#020617] rounded-full" src="https://ui-avatars.com/api/?name=Bob&background=3b82f6&color=fff" alt="">
    <img class="w-10 h-10 border-2 border-[#020617] rounded-full" src="https://ui-avatars.com/api/?name=Charlie&background=10b981&color=fff" alt="">
    <a class="flex items-center justify-center w-10 h-10 text-xs font-medium text-white bg-gray-700 border-2 border-[#020617] rounded-full hover:bg-gray-600" href="#">+99</a>
</div>'
                ]
            ]
        ]
    ];
@endphp

<div class="min-h-screen bg-[#020617] text-white font-sans selection:bg-fuchsia-500/30 pt-20">

    {{-- Background Atmosphere --}}
    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div class="absolute top-0 right-0 w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-0 left-0 w-[600px] h-[600px] bg-fuchsia-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex max-w-[1600px] mx-auto min-h-[calc(100vh-80px)]">

        {{-- SIDEBAR KATEGORI (Sticky Desktop) --}}
        <aside class="w-72 hidden lg:block shrink-0 h-[calc(100vh-80px)] sticky top-20 overflow-y-auto custom-scrollbar border-r border-white/5 py-8 pl-6 pr-4 bg-[#020617]/50 backdrop-blur-sm">
            <div class="mb-6 px-2">
                <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-1">Explore Library</h3>
                <p class="text-[10px] text-white/30">Total {{ collect($gallery)->sum(fn($c) => count($c['items'])) }} Komponen</p>
            </div>
            
            <ul class="space-y-3" id="toc-list">
                @foreach($gallery as $key => $cat)
                    <li>
                        <a href="#{{ $key }}" class="nav-item group flex items-center gap-3 px-4 py-3 rounded-xl transition-all text-sm text-white/60 hover:text-white hover:bg-white/5 border border-transparent hover:border-white/5">
                            <span class="text-xl opacity-70 group-hover:scale-110 transition duration-300">{{ $cat['icon'] }}</span>
                            <div class="flex flex-col">
                                <span class="font-bold">{{ $cat['title'] }}</span>
                                <span class="text-[10px] text-white/30 group-hover:text-white/50">{{ count($cat['items']) }} variants</span>
                            </div>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-6 lg:p-12 w-full overflow-hidden">
            
            {{-- Header Gallery --}}
            <div class="max-w-6xl mx-auto mb-16">
                <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-8">
                    <div>
                        <span class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-cyan-500/10 border border-cyan-500/20 text-cyan-400 text-[10px] font-bold uppercase tracking-widest mb-4">
                            <span class="w-1.5 h-1.5 rounded-full bg-cyan-500 animate-pulse"></span> Ready to Copy
                        </span>
                        <h1 class="text-4xl md:text-6xl font-black text-white mb-4">
                            Component <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-500">Gallery</span>
                        </h1>
                        <p class="text-white/50 text-lg max-w-2xl">Kumpulan komponen UI modern siap pakai. Salin kodenya atau modifikasi langsung di Sandbox.</p>
                    </div>
                    
                    {{-- Search Box --}}
                    <div class="relative group w-full md:w-80 z-20">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-fuchsia-600 rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                        <div class="relative bg-[#0f141e] border border-white/10 rounded-xl flex items-center px-4 h-14 shadow-xl">
                            <svg class="w-5 h-5 text-white/30 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" id="compSearch" placeholder="Cari tombol, card, form..." class="w-full bg-transparent border-none text-white placeholder-white/30 text-base focus:ring-0 focus:outline-none font-medium">
                            <span class="hidden md:block text-[10px] text-white/20 border border-white/10 px-2 py-1 rounded bg-white/5">CTRL+K</span>
                        </div>
                    </div>
                </div>

                {{-- LOOP SECTIONS --}}
                <div class="space-y-24 pb-32">
                    @foreach($gallery as $key => $cat)
                        <section id="{{ $key }}" class="section-group scroll-mt-32">
                            
                            <div class="mb-8 border-b border-white/10 pb-6 flex items-start gap-5">
                                <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-white/5 to-white/10 border border-white/10 flex items-center justify-center text-3xl shadow-lg">
                                    {{ $cat['icon'] }}
                                </div>
                                <div>
                                    <h2 class="text-3xl font-bold text-white">{{ $cat['title'] }}</h2>
                                    <p class="text-white/40 text-sm mt-1 max-w-xl">{{ $cat['desc'] }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                                @foreach($cat['items'] as $item)
                                    <div class="comp-card group bg-[#0f141e] border border-white/10 rounded-3xl overflow-hidden hover:border-cyan-500/30 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-900/10 flex flex-col h-full ring-1 ring-white/0 hover:ring-white/10">
                                        
                                        {{-- Header: Actions --}}
                                        <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                                            <h3 class="text-sm font-bold text-white/80 group-hover:text-white transition flex items-center gap-2">
                                                {{ $item['name'] }}
                                            </h3>
                                            
                                            <div class="flex gap-2">
                                                {{-- Toggle Code --}}
                                                <button onclick="toggleCode('{{ $item['id'] }}')" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition" title="Lihat HTML">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                                </button>
                                                {{-- Copy --}}
                                                <button onclick="copyCode('{{ $item['id'] }}')" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition" title="Salin Kode">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                                </button>
                                                {{-- Try in Sandbox (Highlight) --}}
                                                <button onclick="sendToSandbox('{{ $item['id'] }}')" class="flex items-center gap-2 px-3 py-1.5 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 hover:bg-cyan-500 hover:text-white transition text-xs font-bold shadow-lg shadow-cyan-500/10" title="Edit di Sandbox">
                                                    <span>Try</span>
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Visual Preview --}}
                                        <div class="p-10 bg-[#020617] flex items-center justify-center min-h-[220px] relative overflow-hidden flex-1 group-hover:bg-[#050912] transition">
                                            {{-- Checkerboard Pattern (Transparency Grid) --}}
                                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 16px 16px;"></div>
                                            
                                            {{-- Render Component --}}
                                            <div class="relative z-10 w-full flex justify-center items-center">
                                                {!! $item['code'] !!}
                                            </div>
                                        </div>

                                        {{-- Source Code Area (Default Hidden) --}}
                                        <div id="code-{{ $item['id'] }}" class="hidden bg-[#0b0f19] border-t border-white/10 p-0 relative group/code transition-all animate-fade-in-down">
                                            <textarea id="raw-{{ $item['id'] }}" class="hidden">{{ $item['code'] }}</textarea>
                                            <div class="relative">
                                                <pre class="text-[11px] leading-relaxed text-gray-400 font-mono p-5 overflow-x-auto custom-scrollbar select-all h-40">{{ $item['code'] }}</pre>
                                                <div class="absolute top-3 right-3 opacity-0 group-hover/code:opacity-100 transition">
                                                    <span class="text-[9px] bg-white/10 px-2 py-1 rounded text-white/50 uppercase tracking-widest font-bold border border-white/5">HTML</span>
                                                </div>
                                            </div>
                                        </div>

                                    </div>
                                @endforeach
                            </div>
                        </section>
                    @endforeach
                </div>

                {{-- Footer Text --}}
                <div class="border-t border-white/5 pt-10 text-center pb-20">
                    <p class="text-white/20 text-xs">Utilwind Component Library v1.0 &copy; {{ date('Y') }}</p>
                </div>

            </div>
        </main>
    </div>

    {{-- TOAST NOTIFICATION --}}
    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-[#0f141e] border border-cyan-500/50 text-white px-6 py-3 rounded-full shadow-2xl shadow-cyan-900/50 transform translate-y-24 opacity-0 transition-all duration-300 z-50 flex items-center gap-3 backdrop-blur-xl">
        <div class="w-6 h-6 rounded-full bg-cyan-500 flex items-center justify-center text-[#020617]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
        </div>
        <span class="font-bold text-sm tracking-wide">Code Copied!</span>
    </div>

</div>

{{-- SCRIPT --}}
<script>
    // 1. TOGGLE CODE VIEW
    function toggleCode(id) {
        const el = document.getElementById('code-' + id);
        el.classList.toggle('hidden');
    }

    // 2. COPY CODE
    function copyCode(id) {
        const code = document.getElementById('raw-' + id).value;
        navigator.clipboard.writeText(code).then(() => showToast('Code Copied!'));
    }

    // 3. SEND TO SANDBOX (Base64 Encoded for URL Safety)
    function sendToSandbox(id) {
        const code = document.getElementById('raw-' + id).value;
        const encodedCode = btoa(encodeURIComponent(code)); // Encoding agar URL aman
        window.open("{{ route('sandbox') }}?import=" + encodedCode, '_blank');
    }

    function showToast(msg) {
        const toast = document.getElementById('toast');
        toast.querySelector('span').innerText = msg;
        toast.classList.remove('translate-y-24', 'opacity-0');
        setTimeout(() => {
            toast.classList.add('translate-y-24', 'opacity-0');
        }, 2000);
    }

    // 4. SEARCH FUNCTION
    document.getElementById('compSearch').addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        document.querySelectorAll('.comp-card').forEach(card => {
            const text = card.innerText.toLowerCase();
            card.style.display = text.includes(term) ? 'flex' : 'none';
        });
        
        // Hide empty sections
        document.querySelectorAll('.section-group').forEach(sec => {
            const visible = Array.from(sec.querySelectorAll('.comp-card')).some(c => c.style.display !== 'none');
            sec.style.display = visible ? 'block' : 'none';
        });
    });

    // 5. KEYBOARD SHORTCUT (CTRL+K)
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            document.getElementById('compSearch').focus();
        }
    });

    // 6. SCROLLSPY (SIDEBAR ACTIVE STATE)
    const navLinks = document.querySelectorAll('.nav-item');
    const sections = document.querySelectorAll('.section-group');

    // Menggunakan Intersection Observer untuk performa lebih baik
    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const id = entry.target.getAttribute('id');
                navLinks.forEach(link => {
                    link.classList.remove('bg-white/5', 'text-white', 'border-white/10');
                    link.classList.add('text-white/60', 'border-transparent');
                    if (link.getAttribute('href') === '#' + id) {
                        link.classList.add('bg-white/5', 'text-white', 'border-white/10');
                        link.classList.remove('text-white/60', 'border-transparent');
                    }
                });
            }
        });
    }, { rootMargin: '-10% 0px -80% 0px' }); // Trigger di area atas viewport

    sections.forEach(section => observer.observe(section));

</script>

<style>
    /* UTILITIES */
    .custom-scrollbar::-webkit-scrollbar { width: 4px; height: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

    .animate-fade-in-down { animation: fadeInDown 0.3s ease-out forwards; }
    @keyframes fadeInDown { from { opacity: 0; transform: translateY(-10px); } to { opacity: 1; transform: translateY(0); } }

    /* BACKGROUNDS */
    #animated-bg {
        background: radial-gradient(600px circle at 80% 20%, rgba(34,211,238,.1), transparent 40%),
                    radial-gradient(600px circle at 20% 80%, rgba(217,70,239,.1), transparent 40%);
        animation: bgPulse 10s ease-in-out infinite alternate;
    }
    @keyframes bgPulse { 0% { opacity: 0.3; } 100% { opacity: 0.6; } }
</style>
