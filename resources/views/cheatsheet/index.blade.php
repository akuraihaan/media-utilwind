@extends('layouts.landing')
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
@section('title', 'Kamus Utilitas')

@php
    // =======================================================================
    // ULTIMATE UTILITY DATABASE (50 ITEMS)
    // =======================================================================
    $gallery = [
        'layout' => [
            'title' => 'Layout & Display',
            'icon' => '📐',
            'desc' => 'Mengatur struktur dasar, posisi, dan visibilitas elemen di halaman.',
            'items' => [
                [
                    'id' => 'util-block',
                    'name' => 'block',
                    'code' => '<span class="block bg-indigo-500 p-4 text-white rounded-lg text-center font-bold shadow-lg">Block Element (Takes full width)</span>'
                ],
                [
                    'id' => 'util-inline-block',
                    'name' => 'inline-block',
                    'code' => '<div class="space-x-4 text-center">
  <span class="inline-block bg-fuchsia-500 p-3 text-white rounded-lg font-bold shadow-lg">Item 1</span>
  <span class="inline-block bg-fuchsia-500 p-3 text-white rounded-lg font-bold shadow-lg">Item 2</span>
</div>'
                ],
                [
                    'id' => 'util-absolute',
                    'name' => 'absolute',
                    'code' => '<div class="relative w-48 h-32 bg-slate-800 rounded-2xl border border-slate-600 flex items-center justify-center text-slate-400">
  Parent (relative)
  <div class="absolute -bottom-4 -right-4 w-16 h-16 bg-cyan-500 rounded-xl shadow-[0_0_20px_#06b6d4] flex items-center justify-center text-white text-[10px] font-bold">absolute</div>
</div>'
                ],
                [
                    'id' => 'util-overflow-hidden',
                    'name' => 'overflow-hidden',
                    'code' => '<div class="w-40 h-40 bg-slate-800 rounded-3xl overflow-hidden border border-slate-600 shadow-xl">
  <div class="w-full h-16 bg-emerald-500 translate-x-8 translate-y-8 flex items-center justify-center text-white font-bold text-xs">Clipped</div>
</div>'
                ],
                [
                    'id' => 'util-z-50',
                    'name' => 'z-50',
                    'code' => '<div class="relative h-32 w-48">
  <div class="absolute inset-0 bg-slate-700 border border-slate-600 rounded-xl z-10 translate-x-4 translate-y-4 flex items-end justify-end p-2 text-xs text-white/50">z-10</div>
  <div class="absolute inset-0 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-xl z-50 flex items-center justify-center text-white font-black shadow-2xl shadow-indigo-500/50">z-50</div>
</div>'
                ],
                [
                    'id' => 'util-aspect-video',
                    'name' => 'aspect-video',
                    'code' => '<div class="w-full max-w-xs aspect-video bg-slate-800 rounded-xl border border-slate-600 flex flex-col items-center justify-center text-slate-400 shadow-lg">
  <svg class="w-10 h-10 mb-2 text-slate-500" fill="currentColor" viewBox="0 0 20 20"><path d="M2 6a2 2 0 012-2h6a2 2 0 012 2v8a2 2 0 01-2 2H4a2 2 0 01-2-2V6zM14.553 7.106A1 1 0 0014 8v4a1 1 0 00.553.894l2 1A1 1 0 0018 13V7a1 1 0 00-1.447-.894l-2 1z"></path></svg>
  <span class="font-mono text-sm font-bold text-white">16 : 9</span>
</div>'
                ],
                [
                    'id' => 'util-aspect-square',
                    'name' => 'aspect-square',
                    'code' => '<div class="w-32 aspect-square bg-slate-800 rounded-xl border border-slate-600 flex flex-col items-center justify-center text-slate-400 shadow-lg">
  <span class="font-mono text-lg font-bold text-white">1 : 1</span>
</div>'
                ],
                [
                    'id' => 'util-hidden',
                    'name' => 'hidden / md:block',
                    'code' => '<div class="flex gap-4">
  <div class="p-4 bg-emerald-500 rounded-lg text-white font-bold">Always Visible</div>
  <div class="p-4 bg-rose-500 rounded-lg text-white font-bold hidden md:block border-2 border-dashed border-rose-300">Hidden on Mobile</div>
</div>'
                ],
            ]
        ],
        'flexgrid' => [
            'title' => 'Flexbox & Grid',
            'icon' => '🍱',
            'desc' => 'Membangun tata letak grid dan baris yang responsif dengan sangat cepat.',
            'items' => [
                [
                    'id' => 'util-flex-row',
                    'name' => 'flex-row & gap',
                    'code' => '<div class="flex flex-row gap-4 bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-xl">
  <div class="w-12 h-12 bg-blue-500 rounded-xl shadow-lg"></div>
  <div class="w-12 h-12 bg-indigo-500 rounded-xl shadow-lg"></div>
  <div class="w-12 h-12 bg-purple-500 rounded-xl shadow-lg"></div>
</div>'
                ],
                [
                    'id' => 'util-flex-col',
                    'name' => 'flex-col',
                    'code' => '<div class="flex flex-col gap-3 bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-xs shadow-xl">
  <div class="w-full h-8 bg-fuchsia-500 rounded-lg shadow"></div>
  <div class="w-full h-8 bg-pink-500 rounded-lg shadow"></div>
  <div class="w-full h-8 bg-rose-500 rounded-lg shadow"></div>
</div>'
                ],
                [
                    'id' => 'util-justify-between',
                    'name' => 'justify-between',
                    'code' => '<div class="flex justify-between items-center bg-slate-800 p-4 rounded-xl border border-slate-700 w-full max-w-sm shadow-lg">
  <div class="font-bold text-white">Start</div>
  <div class="w-8 h-8 bg-cyan-500 rounded-full animate-pulse"></div>
  <div class="font-bold text-white">End</div>
</div>'
                ],
                [
                    'id' => 'util-items-center',
                    'name' => 'items-center',
                    'code' => '<div class="flex items-center gap-4 bg-slate-800 h-32 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-xl">
  <div class="w-12 h-20 bg-amber-500 rounded-xl shadow-lg"></div>
  <div class="w-12 h-10 bg-amber-400 rounded-xl shadow-lg flex items-center justify-center text-white text-xs font-bold">Center</div>
  <div class="w-12 h-16 bg-amber-300 rounded-xl shadow-lg"></div>
</div>'
                ],
                [
                    'id' => 'util-grid-3',
                    'name' => 'grid-cols-3',
                    'code' => '<div class="grid grid-cols-3 gap-3 bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-xl">
  <div class="h-12 bg-emerald-500 rounded-lg shadow"></div>
  <div class="h-12 bg-emerald-400 rounded-lg shadow"></div>
  <div class="h-12 bg-emerald-300 rounded-lg shadow"></div>
  <div class="h-12 bg-emerald-400 rounded-lg shadow"></div>
  <div class="h-12 bg-emerald-300 rounded-lg shadow"></div>
  <div class="h-12 bg-emerald-500 rounded-lg shadow"></div>
</div>'
                ],
                [
                    'id' => 'util-col-span',
                    'name' => 'col-span-2',
                    'code' => '<div class="grid grid-cols-3 gap-3 bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-sm shadow-xl">
  <div class="h-12 bg-indigo-500 rounded-lg shadow col-span-2 flex items-center justify-center text-white font-bold text-xs">col-span-2</div>
  <div class="h-12 bg-indigo-400 rounded-lg shadow"></div>
  <div class="h-12 bg-indigo-400 rounded-lg shadow"></div>
  <div class="h-12 bg-indigo-500 rounded-lg shadow col-span-2 flex items-center justify-center text-white font-bold text-xs">col-span-2</div>
</div>'
                ],
                [
                    'id' => 'util-flex-wrap',
                    'name' => 'flex-wrap',
                    'code' => '<div class="flex flex-wrap gap-2 bg-slate-800 p-4 rounded-xl border border-slate-700 w-full max-w-[250px] shadow-lg">
  <span class="px-3 py-1 bg-slate-700 text-white rounded text-xs">Tag 1</span>
  <span class="px-3 py-1 bg-slate-700 text-white rounded text-xs">Tag 2</span>
  <span class="px-3 py-1 bg-slate-700 text-white rounded text-xs">Long Tag Name 3</span>
  <span class="px-3 py-1 bg-slate-700 text-white rounded text-xs">Tag 4</span>
  <span class="px-3 py-1 bg-slate-700 text-white rounded text-xs">Tag 5</span>
</div>'
                ],
            ]
        ],
        'spacing' => [
            'title' => 'Spacing & Sizing',
            'icon' => '↔',
            'desc' => 'Mengelola margin, padding, lebar, dan tinggi elemen secara presisi.',
            'items' => [
                [
                    'id' => 'util-p-6',
                    'name' => 'p-6 (Padding)',
                    'code' => '<div class="bg-slate-800 rounded-2xl border border-dashed border-slate-500 inline-block shadow-lg">
  <div class="bg-blue-500 m-6 w-24 h-12 rounded-lg flex items-center justify-center text-white font-bold shadow-md">p-6</div>
</div>'
                ],
                [
                    'id' => 'util-mx-auto',
                    'name' => 'mx-auto',
                    'code' => '<div class="w-full max-w-sm bg-slate-800 rounded-xl border border-slate-700 p-4 shadow-lg">
  <div class="w-24 h-12 bg-fuchsia-500 mx-auto rounded-lg flex items-center justify-center text-white font-bold text-xs shadow-md">mx-auto</div>
</div>'
                ],
                [
                    'id' => 'util-space-y',
                    'name' => 'space-y-4',
                    'code' => '<div class="space-y-4 bg-slate-800 p-6 rounded-2xl border border-slate-700 w-full max-w-xs shadow-xl">
  <div class="w-full h-8 bg-cyan-500 rounded-lg shadow"></div>
  <div class="w-full h-8 bg-cyan-500 rounded-lg shadow flex items-center justify-center text-white text-[10px] font-bold">Margin Y Otomatis</div>
  <div class="w-full h-8 bg-cyan-500 rounded-lg shadow"></div>
</div>'
                ],
                [
                    'id' => 'util-space-x',
                    'name' => '-space-x-4',
                    'code' => '<div class="flex -space-x-4">
  <div class="w-16 h-16 rounded-full bg-rose-500 border-4 border-[#020617] shadow-lg flex items-center justify-center text-white font-bold">1</div>
  <div class="w-16 h-16 rounded-full bg-indigo-500 border-4 border-[#020617] shadow-lg flex items-center justify-center text-white font-bold">2</div>
  <div class="w-16 h-16 rounded-full bg-emerald-500 border-4 border-[#020617] shadow-lg flex items-center justify-center text-white font-bold">3</div>
</div>'
                ],
                [
                    'id' => 'util-w-full',
                    'name' => 'w-full / w-1/2',
                    'code' => '<div class="w-full max-w-sm space-y-3 bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl">
  <div class="w-full h-8 bg-blue-500 rounded flex items-center justify-center text-white text-xs font-bold">w-full (100%)</div>
  <div class="w-1/2 h-8 bg-blue-400 rounded flex items-center justify-center text-white text-xs font-bold">w-1/2 (50%)</div>
</div>'
                ],
                [
                    'id' => 'util-h-screen',
                    'name' => 'h-screen',
                    'code' => '<div class="w-48 h-40 bg-slate-800 border border-slate-700 rounded-2xl flex flex-col items-center justify-center shadow-xl">
  <svg class="w-8 h-8 text-slate-500 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7v8a2 2 0 002 2h6M8 7V5a2 2 0 012-2h4.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V15a2 2 0 01-2 2h-2M8 7H6a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2v-2"></path></svg>
  <span class="text-white font-bold text-sm">100vh Height</span>
  <span class="text-slate-500 text-[10px]">Viewport Height</span>
</div>'
                ],
            ]
        ],
        'typography' => [
            'title' => 'Typography',
            'icon' => 'Aa',
            'desc' => 'Styling teks, ukuran font, ketebalan, dan efek gradasi modern.',
            'items' => [
                [
                    'id' => 'util-text-size',
                    'name' => 'text-3xl / text-sm',
                    'code' => '<div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl text-center space-y-2 max-w-sm w-full">
  <p class="text-xs text-slate-400">text-xs (12px)</p>
  <p class="text-sm text-slate-300">text-sm (14px)</p>
  <p class="text-base text-white">text-base (16px)</p>
  <p class="text-2xl text-white font-bold">text-2xl (24px)</p>
  <p class="text-4xl text-white font-black">text-4xl</p>
</div>'
                ],
                [
                    'id' => 'util-tracking',
                    'name' => 'tracking-widest',
                    'code' => '<div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl text-center space-y-4 max-w-sm w-full">
  <div>
    <p class="text-[10px] text-slate-500 mb-1">tracking-tight</p>
    <h3 class="text-3xl font-black text-white tracking-tight">HEADLINE</h3>
  </div>
  <div>
    <p class="text-[10px] text-slate-500 mb-1">tracking-widest</p>
    <p class="text-sm font-bold text-cyan-400 tracking-widest uppercase">Sub Label</p>
  </div>
</div>'
                ],
                [
                    'id' => 'util-leading',
                    'name' => 'leading-relaxed',
                    'code' => '<div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl max-w-sm w-full text-left">
  <p class="text-sm text-slate-300 leading-relaxed">
    <span class="text-fuchsia-400 font-bold">leading-relaxed</span> memberikan ruang napas (line-height) yang lebih lega antar baris teks. Sangat cocok digunakan untuk paragraf artikel atau deskripsi panjang agar mata tidak cepat lelah saat membaca.
  </p>
</div>'
                ],
                [
                    'id' => 'util-truncate',
                    'name' => 'truncate / line-clamp',
                    'code' => '<div class="bg-slate-800 p-6 rounded-2xl border border-slate-700 shadow-xl max-w-[200px] w-full text-left">
  <p class="text-xs text-slate-500 mb-1">truncate</p>
  <p class="text-white font-bold truncate mb-4">Judul Artikel Ini Sangat Panjang Sekali Lho</p>
  
  <p class="text-xs text-slate-500 mb-1">line-clamp-2</p>
  <p class="text-sm text-slate-300 line-clamp-2">Paragraf ini menggunakan line clamp dua baris. Jika teks melebihi dua baris, otomatis akan dipotong dengan titik-titik di akhir baris kedua.</p>
</div>'
                ],
                [
                    'id' => 'util-text-gradient',
                    'name' => 'Gradient Text',
                    'code' => '<div class="bg-slate-900 p-8 rounded-3xl border border-slate-800 shadow-2xl">
  <h1 class="text-5xl font-black text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-600">
    Future Web
  </h1>
</div>'
                ],
            ]
        ],
        'colors_borders' => [
            'title' => 'Colors, Borders & Rings',
            'icon' => '✨',
            'desc' => 'Warna latar, opacity, gradient, radius sudut, outline modern, dan shadow.',
            'items' => [
                [
                    'id' => 'util-gradient',
                    'name' => 'bg-gradient-to-r',
                    'code' => '<div class="w-full max-w-sm h-32 rounded-3xl bg-gradient-to-r from-rose-500 via-fuchsia-500 to-indigo-500 p-1 shadow-xl">
  <div class="w-full h-full bg-slate-900 rounded-[22px] flex items-center justify-center">
    <span class="text-white font-bold tracking-widest uppercase">Awesome</span>
  </div>
</div>'
                ],
                [
                    'id' => 'util-opacity',
                    'name' => 'opacity & /50 (Alpha)',
                    'code' => '<div class="flex gap-4">
  <div class="w-20 h-20 bg-blue-500 rounded-2xl flex items-center justify-center text-white font-bold shadow-lg">100%</div>
  <div class="w-20 h-20 bg-blue-500/50 rounded-2xl flex items-center justify-center text-white font-bold border border-blue-400 shadow-lg">50%</div>
  <div class="w-20 h-20 bg-blue-500/20 rounded-2xl flex items-center justify-center text-blue-400 font-bold border border-blue-500/30">20%</div>
</div>'
                ],
                [
                    'id' => 'util-rounded',
                    'name' => 'rounded-full / rounded-xl',
                    'code' => '<div class="flex gap-4 items-end">
  <div class="w-16 h-16 bg-emerald-500 rounded-md flex items-center justify-center text-white text-[10px] font-bold shadow-lg">-md</div>
  <div class="w-16 h-16 bg-emerald-500 rounded-2xl flex items-center justify-center text-white text-[10px] font-bold shadow-lg">-2xl</div>
  <div class="w-16 h-16 bg-emerald-500 rounded-full flex items-center justify-center text-white text-[10px] font-bold shadow-lg">-full</div>
</div>'
                ],
                [
                    'id' => 'util-divide',
                    'name' => 'divide-y',
                    'code' => '<div class="w-full max-w-xs bg-slate-800 rounded-2xl border border-slate-700 divide-y divide-slate-700/50 flex flex-col text-white shadow-xl">
  <div class="p-4 flex justify-between"><span>Dashboard</span> <span>→</span></div>
  <div class="p-4 flex justify-between"><span>Settings</span> <span>→</span></div>
  <div class="p-4 flex justify-between text-rose-400"><span>Logout</span> <span>→</span></div>
</div>'
                ],
                [
                    'id' => 'util-ring',
                    'name' => 'ring & ring-offset',
                    'code' => '<div class="flex flex-col gap-6 items-center">
  <button class="px-8 py-3 bg-indigo-600 text-white font-bold rounded-xl ring-4 ring-indigo-500/50 outline-none">Normal Ring</button>
  <button class="px-8 py-3 bg-fuchsia-600 text-white font-bold rounded-xl ring-4 ring-fuchsia-500 ring-offset-4 ring-offset-[#020617] outline-none">Ring with Offset</button>
</div>'
                ],
                [
                    'id' => 'util-backdrop',
                    'name' => 'backdrop-blur (Glass)',
                    'code' => '<div class="relative w-full max-w-sm h-40 bg-[url(\'https://images.unsplash.com/photo-1550684848-fac1c5b4e853\')] bg-cover bg-center rounded-3xl overflow-hidden flex items-center justify-center shadow-2xl">
  <div class="absolute inset-0 bg-black/20"></div>
  <div class="px-8 py-4 bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl shadow-xl z-10 text-white font-black tracking-widest">
    GLASSMORPHISM
  </div>
</div>'
                ],
                [
                    'id' => 'util-shadow',
                    'name' => 'shadow-xl & shadow-inner',
                    'code' => '<div class="flex gap-6">
  <div class="w-24 h-24 bg-slate-800 rounded-2xl shadow-2xl shadow-cyan-500/40 border border-slate-700 flex items-center justify-center text-white text-xs font-bold text-center p-2">Drop Shadow</div>
  <div class="w-24 h-24 bg-slate-900 rounded-2xl shadow-inner border border-slate-800 flex items-center justify-center text-slate-500 text-xs font-bold text-center p-2">Inner Shadow</div>
</div>'
                ],
            ]
        ],
        'interactive' => [
            'title' => 'Interactive & Animations',
            'icon' => '✨',
            'desc' => 'State modifier (hover, focus, group) dan keyframe animasi.',
            'items' => [
                [
                    'id' => 'util-hover-active',
                    'name' => 'hover: & active:',
                    'code' => '<button class="px-8 py-4 bg-indigo-600 text-white font-bold rounded-xl hover:bg-indigo-500 hover:shadow-[0_0_20px_rgba(99,102,241,0.5)] active:scale-90 transition-all duration-300">
  Press & Hold Me
</button>'
                ],
                [
                    'id' => 'util-group-hover',
                    'name' => 'group-hover:',
                    'code' => '<div class="group w-64 bg-slate-800 p-5 rounded-2xl border border-slate-700 cursor-pointer hover:border-cyan-500 transition-colors shadow-lg">
  <h3 class="text-white font-bold group-hover:text-cyan-400 transition-colors">Hover Parent</h3>
  <p class="text-slate-400 text-xs mt-2 transition-opacity opacity-50 group-hover:opacity-100">Teks ini akan menjadi terang jika kotak parent di-hover.</p>
  <div class="w-8 h-8 bg-cyan-500/20 rounded-full mt-4 flex items-center justify-center text-cyan-400 group-hover:translate-x-4 transition-transform duration-300">→</div>
</div>'
                ],
                [
                    'id' => 'util-peer',
                    'name' => 'peer-checked:',
                    'code' => '<label class="flex items-center gap-4 cursor-pointer">
  <input type="checkbox" class="peer sr-only">
  <div class="w-14 h-8 bg-slate-700 rounded-full peer-checked:bg-emerald-500 transition-colors relative after:absolute after:top-1 after:left-1 after:bg-white after:w-6 after:h-6 after:rounded-full after:transition-all peer-checked:after:translate-x-6 shadow-inner"></div>
  <span class="text-slate-400 peer-checked:text-emerald-400 font-bold transition-colors">Toggle Me</span>
</label>'
                ],
                [
                    'id' => 'util-focus-within',
                    'name' => 'focus-within:',
                    'code' => '<div class="flex items-center gap-3 w-full max-w-xs bg-slate-800 border border-slate-600 rounded-xl px-4 py-3 focus-within:border-fuchsia-500 focus-within:ring-1 focus-within:ring-fuchsia-500 transition-all shadow-lg text-slate-400 focus-within:text-fuchsia-400">
  <svg class="w-5 h-5 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
  <input type="text" placeholder="Click to focus..." class="bg-transparent border-none outline-none text-white w-full placeholder-slate-500 text-sm">
</div>'
                ],
                [
                    'id' => 'util-animate-spin',
                    'name' => 'animate-spin & pulse',
                    'code' => '<div class="flex gap-8 items-center">
  <div class="w-12 h-12 border-4 border-slate-700 border-t-cyan-500 rounded-full animate-spin shadow-[0_0_15px_#06b6d4]"></div>
  <div class="w-12 h-12 bg-fuchsia-500 rounded-2xl animate-pulse shadow-[0_0_20px_#d946ef]"></div>
  <div class="w-12 h-12 bg-amber-500 rounded-full animate-bounce shadow-lg flex items-center justify-center text-white font-black text-xl">↓</div>
</div>'
                ],
                [
                    'id' => 'util-cursor',
                    'name' => 'cursor-not-allowed',
                    'code' => '<div class="flex gap-4">
  <button class="px-6 py-3 bg-indigo-600 text-white font-bold rounded-lg cursor-pointer">Pointer</button>
  <button class="px-6 py-3 bg-slate-800 text-slate-500 border border-slate-700 font-bold rounded-lg cursor-not-allowed" disabled>Not Allowed</button>
</div>'
                ],
                [
                    'id' => 'util-transition',
                    'name' => 'transition-all duration-500',
                    'code' => '<div class="w-20 h-20 bg-rose-500 rounded-2xl hover:bg-indigo-500 hover:rotate-45 hover:scale-110 hover:rounded-full transition-all duration-500 cursor-pointer shadow-lg flex items-center justify-center text-white text-xs font-bold text-center">Hover Slowly</div>'
                ],
            ]
        ]
    ];
@endphp

{{-- MAIN WRAPPER --}}
<div class="min-h-screen bg-[#020617] text-white font-sans selection:bg-fuchsia-500/30 pt-20">

    {{-- Background Decoration --}}
    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div class="absolute top-0 left-1/4 w-[600px] h-[600px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-[500px] h-[500px] bg-fuchsia-900/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03] mix-blend-overlay"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="flex max-w-[1600px] mx-auto min-h-[calc(100vh-80px)]">

        {{-- SIDEBAR TOC (Desktop Only) --}}
        <aside class="w-72 hidden lg:block shrink-0 h-[calc(100vh-80px)] sticky top-20 overflow-y-auto custom-scrollbar border-r border-white/5 py-8 pl-6 pr-4 bg-[#020617]/50 backdrop-blur-sm">
            <div class="mb-6 px-2">
                <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-1">Explore Library</h3>
                <p class="text-[10px] text-white/30">Total 50 Kelas Utilitas Inti</p>
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
                
                {{-- BREADCRUMB START --}}
                <nav class="flex items-center gap-2 mb-6 text-[10px] md:text-xs font-bold uppercase tracking-widest text-white/40 justify-start" aria-label="Breadcrumb">
                    <a href="/" class="hover:text-white transition-colors flex items-center gap-1.5">
                        <svg class="w-3 h-3 md:w-3.5 md:h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                        Home
                    </a>
                    <span class="text-white/20">/</span>
                    <a href="{{ route('dashboard') }}" class="hover:text-white transition-colors">Dashboard</a>
                    <span class="text-white/20">/</span>
                    <span class="text-cyan-400 drop-shadow-[0_0_8px_rgba(34,211,238,0.5)]">Kamus Utilitas</span>
                </nav>
                {{-- BREADCRUMB END --}}

                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end gap-6 mb-8">
                    <div>
                        
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 tracking-tight">
                            Kamus <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-500">Utilitas</span>
                        </h1>
                        <p class="text-white/50 text-base md:text-lg max-w-2xl leading-relaxed">
                            Panduan visual 50+ kelas utilitas Tailwind paling krusial. Pahami cara kerjanya secara instan atau bedah kodenya langsung di Sandbox.
                        </p>
                    </div>
                    
                    {{-- Search Box --}}
                    <div class="relative group w-full xl:w-80 z-20 shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-fuchsia-600 rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                        <div class="relative bg-[#0f141e] border border-white/10 rounded-xl flex items-center px-4 h-14 shadow-xl">
                            <svg class="w-5 h-5 text-white/30 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" id="compSearch" placeholder="Cari utilitas (flex, shadow...)" class="w-full bg-transparent border-none text-white placeholder-white/30 text-sm focus:ring-0 focus:outline-none font-medium">
                            <span class="hidden md:block text-[10px] text-white/20 border border-white/10 px-2 py-1 rounded bg-white/5 font-mono">CTRL+K</span>
                        </div>
                    </div>
                </div>

                {{-- LOOP SECTIONS --}}
                <div class="space-y-24 pb-32">
                    @foreach($gallery as $key => $cat)
                        <section id="{{ $key }}" class="section-group scroll-mt-32">
                            
                            <div class="mb-8 border-b border-white/10 pb-6 flex items-start gap-5">
                                <div class="w-12 h-12 md:w-14 md:h-14 rounded-2xl bg-gradient-to-br from-white/5 to-white/10 border border-white/10 flex items-center justify-center text-2xl md:text-3xl shadow-lg shrink-0">
                                    {{ $cat['icon'] }}
                                </div>
                                <div>
                                    <h2 class="text-2xl md:text-3xl font-bold text-white tracking-tight">{{ $cat['title'] }}</h2>
                                    <p class="text-white/40 text-xs md:text-sm mt-1 max-w-xl">{{ $cat['desc'] }}</p>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 xl:grid-cols-2 gap-8">
                                @foreach($cat['items'] as $item)
                                    <div class="comp-card group bg-[#0f141e] border border-white/10 rounded-3xl overflow-hidden hover:border-fuchsia-500/30 transition-all duration-300 hover:shadow-2xl hover:shadow-fuchsia-900/10 flex flex-col h-full ring-1 ring-white/0 hover:ring-white/10" data-class="{{ $item['name'] }}">
                                        
                                        {{-- Header: Actions --}}
                                        <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                                            <code class="text-sm font-bold text-fuchsia-400 font-mono cursor-pointer hover:underline hover:text-fuchsia-300 transition flex items-center gap-2 truncate pr-4" onclick="copyToClipboard('{{ $item['name'] }}')" title="{{ $item['name'] }}">
                                                .{{ $item['name'] }}
                                            </code>
                                            
                                            <div class="flex gap-2 shrink-0">
                                                {{-- Toggle Code --}}
                                                <button onclick="toggleCode('{{ $item['id'] }}')" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition" title="Lihat HTML">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                                                </button>
                                                {{-- Copy --}}
                                                <button onclick="copyCode('{{ $item['id'] }}')" class="p-2 rounded-lg hover:bg-white/10 text-white/40 hover:text-white transition" title="Salin Kode">
                                                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                                </button>
                                                {{-- Try in Sandbox --}}
                                                <button onclick="sendToSandbox('{{ $item['id'] }}')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-fuchsia-500/10 text-fuchsia-400 border border-fuchsia-500/20 hover:bg-fuchsia-500 hover:text-white transition text-[10px] md:text-xs font-bold shadow-lg shadow-fuchsia-500/10" title="Edit di Sandbox">
                                                    <span>Try</span>
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Visual Preview --}}
                                        <div class="p-6 md:p-10 bg-[#020617] flex items-center justify-center min-h-[200px] md:min-h-[250px] relative overflow-hidden flex-1 group-hover:bg-[#050912] transition">
                                            {{-- Checkerboard Pattern (Transparency Grid) --}}
                                            <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 16px 16px;"></div>
                                            
                                            {{-- Render Component (HTML Utuh) --}}
                                            <div class="relative z-10 w-full flex justify-center items-center">
                                                {!! $item['code'] !!}
                                            </div>
                                        </div>

                                        {{-- Source Code Area (Default Hidden) --}}
                                        <div id="code-{{ $item['id'] }}" class="hidden bg-[#0b0f19] border-t border-white/10 p-0 relative group/code transition-all animate-fade-in-down">
                                            <textarea id="raw-{{ $item['id'] }}" class="hidden">{{ $item['code'] }}</textarea>
                                            <div class="relative">
                                                <pre class="text-[10px] md:text-[11px] leading-relaxed text-gray-400 font-mono p-4 md:p-5 overflow-x-auto custom-scrollbar select-all max-h-64">{{ $item['code'] }}</pre>
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
                    <p class="text-white/20 text-xs">Utilwind Utility Library &copy; {{ date('Y') }}</p>
                    <p class="text-white/10 text-[10px] mt-1">Total 50 Core Utility Visualizations.</p>
                </div>

            </div>
        </main>
    </div>

    {{-- TOAST NOTIFICATION (Visual Feedback) --}}
    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-[#0f141e] border border-emerald-500/50 text-white px-6 py-3 rounded-full shadow-2xl shadow-emerald-900/50 transform translate-y-24 opacity-0 transition-all duration-300 z-50 flex items-center gap-3 backdrop-blur-xl">
        <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-[#020617]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
        </div>
        <span class="font-bold text-sm tracking-wide">Copied!</span>
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

    // 3. SEND TO SANDBOX
    function sendToSandbox(id) {
        const rawHtml = document.getElementById('raw-' + id).value;
        
        // Membungkus code dengan template background slate gelap persis seperti di preview Kamus
        const template = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandbox Preview</title>
    <script src="https://cdn.tailwindcss.com"><\/script>
</head>
<body class="bg-[#020617] min-h-screen flex items-center justify-center p-8 antialiased">
    
    ${rawHtml}

</body>
</html>`;

        const encodedCode = btoa(encodeURIComponent(template)); // Encoding aman untuk URL
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
            const className = card.dataset.class.toLowerCase();
            card.style.display = (text.includes(term) || className.includes(term)) ? 'flex' : 'none';
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
    }, { rootMargin: '-10% 0px -80% 0px' });

    sections.forEach(section => observer.observe(section));

</script>

<style>
    /* UTILITIES */
    .custom-scrollbar::-webkit-scrollbar { width: 6px; height: 6px; }
    .custom-scrollbar::-webkit-scrollbar-track { background: transparent; }
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