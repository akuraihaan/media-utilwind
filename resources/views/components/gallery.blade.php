@extends('layouts.landing')
<link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
@section('title', 'Kamus Komponen')

@php
    // =======================================================================
    // ULTIMATE COMPONENT DATABASE (50 KOMPONEN)
    // =======================================================================
    $gallery = [
        'buttons' => [
            'title' => 'Buttons & Actions',
            'icon' => '🖱️',
            'desc' => '10 Varian tombol interaktif dengan berbagai state dan efek.',
            'items' => [
                [
                    'id' => 'btn-primary',
                    'name' => 'Primary Brand',
                    'code' => '<button class="px-6 py-2.5 bg-indigo-600 text-white font-semibold rounded-lg shadow-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 focus:ring-offset-slate-900 transition-all duration-300">
  Primary Action
</button>'
                ],
                [
                    'id' => 'btn-glow',
                    'name' => 'Neon Glow',
                    'code' => '<button class="relative px-8 py-3 rounded-full bg-fuchsia-600 text-white font-bold transition-all duration-300 hover:shadow-[0_0_20px_rgba(192,38,211,0.6)] hover:scale-105 active:scale-95 group">
  <span class="relative z-10 flex items-center gap-2">
    Launch App
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
  <div class="px-6 py-2 bg-slate-900 rounded-[6px] text-white font-bold relative z-10 flex items-center gap-2">
    <span>✨</span> Special Offer
  </div>
</button>'
                ],
                [
                    'id' => 'btn-icon',
                    'name' => 'Icon Controls',
                    'code' => '<div class="flex gap-4">
  <button class="p-3 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition shadow-lg"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg></button>
  <button class="p-3 bg-white/10 text-white rounded-full hover:bg-white/20 transition border border-white/10"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
</div>'
                ],
                [
                    'id' => 'btn-loading',
                    'name' => 'Loading State',
                    'code' => '<button class="px-6 py-2.5 bg-indigo-600/50 text-indigo-200 font-semibold rounded-lg cursor-not-allowed flex items-center gap-3 border border-indigo-500/30" disabled>
  <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
  Processing...
</button>'
                ],
                [
                    'id' => 'btn-3d',
                    'name' => '3D Push Button',
                    'code' => '<button class="px-6 py-2.5 bg-emerald-500 text-white font-bold rounded-lg border-b-4 border-emerald-700 hover:bg-emerald-400 hover:border-emerald-600 active:border-b-0 active:translate-y-1 transition-all">
  Save Changes
</button>'
                ],
                [
                    'id' => 'btn-social',
                    'name' => 'Social Auth',
                    'code' => '<button class="flex items-center gap-3 px-5 py-2.5 bg-white text-slate-800 font-bold rounded-lg shadow-md hover:bg-gray-100 transition">
  <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.545,10.239v3.821h5.445c-0.712,2.315-2.647,3.972-5.445,3.972c-3.332,0-6.033-2.701-6.033-6.032s2.701-6.032,6.033-6.032c1.498,0,2.866,0.549,3.921,1.453l2.814-2.814C17.503,2.988,15.139,2,12.545,2C7.021,2,2.543,6.477,2.543,12s4.478,10,10.002,10c8.396,0,10.249-7.85,9.426-11.748L12.545,10.239z"/></svg>
  Continue with Google
</button>'
                ],
                [
                    'id' => 'btn-soft',
                    'name' => 'Soft Tonal',
                    'code' => '<button class="px-5 py-2 bg-blue-500/10 text-blue-400 font-semibold rounded-lg hover:bg-blue-500/20 transition-colors">
  Secondary Action
</button>'
                ],
                [
                    'id' => 'btn-fab',
                    'name' => 'Floating Action (FAB)',
                    'code' => '<button class="p-4 bg-gradient-to-r from-rose-500 to-pink-500 text-white rounded-full shadow-[0_10px_20px_rgba(244,63,94,0.4)] hover:shadow-[0_10px_25px_rgba(244,63,94,0.6)] hover:-translate-y-1 active:translate-y-0 transition-all">
  <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
</button>'
                ]
            ]
        ],
        'cards' => [
            'title' => 'Cards & Panels',
            'icon' => '🗂️',
            'desc' => '10 Layout kartu modular untuk berbagai jenis konten.',
            'items' => [
                [
                    'id' => 'card-profile',
                    'name' => 'User Profile',
                    'code' => '<div class="w-72 bg-slate-800 rounded-2xl border border-slate-700 shadow-xl overflow-hidden relative group">
  <div class="h-24 bg-gradient-to-r from-cyan-500 to-blue-500"></div>
  <div class="px-6 pb-6 text-center">
    <div class="relative -mt-12 flex justify-center mb-4">
      <img src="https://ui-avatars.com/api/?name=Alex+Dev&background=random" class="w-20 h-20 rounded-full border-4 border-slate-800 object-cover group-hover:scale-110 transition duration-300">
    </div>
    <h3 class="text-white font-bold text-lg">Alex Developer</h3>
    <p class="text-slate-400 text-xs font-mono mb-4">@alex_codes</p>
    <button class="w-full py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-bold rounded-xl transition border border-white/5">Follow</button>
  </div>
</div>'
                ],
                [
                    'id' => 'card-pricing',
                    'name' => 'Pricing Plan',
                    'code' => '<div class="w-72 p-6 bg-slate-900 border border-indigo-500/50 rounded-3xl shadow-[0_0_30px_rgba(99,102,241,0.15)] relative">
  <span class="absolute -top-3 left-1/2 -translate-x-1/2 bg-indigo-500 text-white text-[10px] font-black uppercase tracking-widest px-3 py-1 rounded-full shadow-lg">Pro Plan</span>
  <h3 class="text-white font-medium text-center mt-2">Unlimited Access</h3>
  <div class="text-center mt-4 mb-6">
    <span class="text-4xl font-black text-white">$29</span><span class="text-slate-500 text-sm">/mo</span>
  </div>
  <ul class="space-y-3 mb-8 text-sm text-slate-300">
    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> All Components</li>
    <li class="flex items-center gap-2"><svg class="w-4 h-4 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Lifetime Updates</li>
  </ul>
  <button class="w-full py-3 bg-indigo-600 hover:bg-indigo-500 text-white font-bold rounded-xl transition">Subscribe</button>
</div>'
                ],
                [
                    'id' => 'card-image',
                    'name' => 'Article Cover',
                    'code' => '<div class="w-72 bg-slate-800 border border-slate-700 rounded-2xl overflow-hidden shadow-lg group hover:-translate-y-1 transition duration-300">
  <div class="h-40 overflow-hidden relative">
    <img src="https://images.unsplash.com/photo-1550684848-fac1c5b4e853?q=80&w=600&auto=format&fit=crop" class="w-full h-full object-cover group-hover:scale-110 transition duration-700">
    <div class="absolute inset-0 bg-gradient-to-t from-slate-800 to-transparent"></div>
    <span class="absolute top-3 right-3 bg-black/50 backdrop-blur px-2 py-1 rounded text-[10px] font-bold text-white border border-white/10">Tech</span>
  </div>
  <div class="p-5">
    <h3 class="text-white font-bold text-lg mb-2 leading-tight group-hover:text-cyan-400 transition">Future of AI Coding</h3>
    <p class="text-slate-400 text-xs mb-4 line-clamp-2">Explore how artificial intelligence is shaping the landscape of modern web development.</p>
    <div class="flex items-center gap-2 border-t border-slate-700 pt-4">
        <div class="w-5 h-5 rounded-full bg-indigo-500 text-[8px] flex items-center justify-center font-bold text-white">A</div>
        <span class="text-xs text-slate-300">Admin</span>
    </div>
  </div>
</div>'
                ],
                [
                    'id' => 'card-ecommerce',
                    'name' => 'E-Commerce Item',
                    'code' => '<div class="w-64 bg-slate-800 rounded-2xl border border-white/5 overflow-hidden group">
  <div class="h-48 bg-slate-700 relative overflow-hidden">
    <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30?w=400&q=80" class="object-cover w-full h-full group-hover:scale-110 transition duration-500">
    <span class="absolute top-2 left-2 bg-rose-500 text-white text-[10px] font-bold px-2 py-1 rounded">-20%</span>
  </div>
  <div class="p-4">
    <h3 class="text-white font-bold mb-1">Smart Watch Series 7</h3>
    <div class="flex justify-between items-center mt-3">
      <span class="text-xl font-black text-emerald-400">$199</span>
      <button class="w-8 h-8 rounded-full bg-white/10 hover:bg-indigo-500 flex items-center justify-center text-white transition">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
      </button>
    </div>
  </div>
</div>'
                ],
                [
                    'id' => 'card-stats',
                    'name' => 'Statistic Widget',
                    'code' => '<div class="p-5 rounded-2xl bg-slate-800 border border-slate-700 w-48 hover:border-cyan-500/50 transition cursor-pointer group shadow-lg">
  <div class="flex justify-between items-start mb-2">
    <div class="text-slate-400 text-xs font-bold uppercase tracking-wider">Revenue</div>
    <div class="p-1.5 bg-cyan-500/10 text-cyan-400 rounded-lg"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
  </div>
  <div class="text-3xl font-black text-white group-hover:text-cyan-400 transition">$42.5k</div>
  <div class="text-[10px] text-emerald-400 mt-2 flex items-center gap-1 font-medium">
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
    <span>+12.5%</span> <span class="text-slate-500">from last month</span>
  </div>
</div>'
                ],
                [
                    'id' => 'card-testimonial',
                    'name' => 'Testimonial Review',
                    'code' => '<div class="w-80 p-6 bg-white/5 border border-white/10 rounded-3xl relative">
  <svg class="w-8 h-8 text-indigo-500/30 absolute top-6 right-6" fill="currentColor" viewBox="0 0 24 24"><path d="M14.017 21v-7.391c0-5.704 3.731-9.57 8.983-10.609l.995 2.151c-2.432.917-3.995 3.638-3.995 5.849h4v10h-9.983zm-14.017 0v-7.391c0-5.704 3.748-9.57 9-10.609l.996 2.151c-2.433.917-3.996 3.638-3.996 5.849h3.983v10h-9.983z"/></svg>
  <div class="flex gap-1 mb-4 text-amber-400 text-sm">
    ★★★★★
  </div>
  <p class="text-white/80 text-sm italic leading-relaxed mb-6">"Tailwind CSS completely changed how I build interfaces. It is fast, reliable, and highly customizable."</p>
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-full bg-gradient-to-tr from-fuchsia-500 to-indigo-500 p-0.5">
      <img src="https://ui-avatars.com/api/?name=Sarah+J&background=1e293b&color=fff" class="w-full h-full rounded-full border-2 border-slate-900">
    </div>
    <div>
      <h4 class="text-white font-bold text-sm">Sarah Jenkins</h4>
      <p class="text-[10px] text-slate-400">Frontend Lead</p>
    </div>
  </div>
</div>'
                ],
                [
                    'id' => 'card-event',
                    'name' => 'Event Ticket',
                    'code' => '<div class="flex w-80 bg-slate-800 rounded-2xl overflow-hidden border border-white/10">
  <div class="w-24 bg-gradient-to-b from-indigo-500 to-purple-600 flex flex-col items-center justify-center text-white border-r border-dashed border-white/30 relative">
    <div class="absolute -left-2 top-1/2 w-4 h-4 bg-[#020617] rounded-full transform -translate-y-1/2"></div>
    <div class="absolute -right-2 top-1/2 w-4 h-4 bg-[#020617] rounded-full transform -translate-y-1/2"></div>
    <span class="text-sm font-bold uppercase tracking-widest">DEC</span>
    <span class="text-4xl font-black">24</span>
  </div>
  <div class="p-5 flex-1">
    <h3 class="text-white font-bold text-lg leading-tight mb-1">Tech Conference 2026</h3>
    <p class="text-xs text-slate-400 mb-3 flex items-center gap-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> Grand Hotel, NY</p>
    <button class="w-full py-1.5 bg-white/10 text-white text-xs font-bold rounded hover:bg-white/20 transition">Get Ticket</button>
  </div>
</div>'
                ],
                [
                    'id' => 'card-job',
                    'name' => 'Job Posting',
                    'code' => '<div class="w-full max-w-md p-5 bg-slate-800 rounded-2xl border border-slate-700 hover:border-indigo-500 transition-colors group">
  <div class="flex justify-between items-start mb-4">
    <div class="flex gap-3 items-center">
      <div class="w-10 h-10 bg-white rounded-lg flex items-center justify-center font-black text-blue-600 text-xl">G</div>
      <div>
        <h3 class="text-white font-bold group-hover:text-indigo-400 transition">Senior Frontend Dev</h3>
        <p class="text-xs text-slate-400">Google Inc.</p>
      </div>
    </div>
    <span class="text-[10px] text-slate-500 font-mono">2d ago</span>
  </div>
  <div class="flex gap-2 mb-5">
    <span class="px-2 py-1 bg-white/5 rounded text-[10px] text-slate-300 border border-white/5">Full-time</span>
    <span class="px-2 py-1 bg-white/5 rounded text-[10px] text-slate-300 border border-white/5">Remote</span>
    <span class="px-2 py-1 bg-white/5 rounded text-[10px] text-slate-300 border border-white/5">$120k - $150k</span>
  </div>
  <button class="w-full py-2 bg-indigo-600/20 text-indigo-400 font-bold text-xs rounded-lg hover:bg-indigo-600 hover:text-white transition">Apply Now</button>
</div>'
                ],
                [
                    'id' => 'card-video',
                    'name' => 'Video Thumbnail',
                    'code' => '<div class="w-72 relative group rounded-2xl overflow-hidden border border-white/10 cursor-pointer">
  <img src="https://images.unsplash.com/photo-1517694712202-14dd9538aa97?w=600&q=80" class="w-full h-40 object-cover group-hover:scale-105 transition duration-500">
  <div class="absolute inset-0 bg-black/40 group-hover:bg-black/20 transition flex items-center justify-center">
    <div class="w-12 h-12 bg-white/20 backdrop-blur-md rounded-full flex items-center justify-center border border-white/30 group-hover:scale-110 transition">
      <svg class="w-5 h-5 text-white ml-1" fill="currentColor" viewBox="0 0 24 24"><path d="M8 5v14l11-7z"/></svg>
    </div>
  </div>
  <div class="absolute bottom-2 right-2 px-1.5 py-0.5 bg-black/80 text-white text-[10px] font-mono rounded">12:45</div>
</div>'
                ],
                [
                    'id' => 'card-minimal',
                    'name' => 'Minimalist Item',
                    'code' => '<div class="w-64 p-4 border-l-4 border-l-cyan-500 bg-gradient-to-r from-cyan-500/10 to-transparent rounded-r-xl">
  <h4 class="text-white font-bold text-sm">Meeting with Design Team</h4>
  <p class="text-xs text-slate-400 mt-1 flex items-center gap-2">
    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    10:00 AM - 11:30 AM
  </p>
</div>'
                ]
            ]
        ],
        'forms' => [
            'title' => 'Form & Inputs',
            'icon' => '📝',
            'desc' => '10 Elemen input data dengan state visual yang dioptimalkan.',
            'items' => [
                [
                    'id' => 'inp-floating',
                    'name' => 'Floating Label',
                    'code' => '<div class="relative w-full max-w-xs">
  <input type="text" id="floating_input" class="block px-4 pb-2.5 pt-5 w-full text-sm text-white bg-slate-800 rounded-lg border border-slate-600 appearance-none focus:outline-none focus:ring-0 focus:border-indigo-500 peer" placeholder=" " />
  <label for="floating_input" class="absolute text-sm text-slate-400 duration-300 transform -translate-y-4 scale-75 top-4 z-10 origin-[0] left-4 peer-focus:text-indigo-400 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-4">Email Address</label>
</div>'
                ],
                [
                    'id' => 'inp-search',
                    'name' => 'Search Bar',
                    'code' => '<div class="relative w-full max-w-xs group">
  <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
    <svg class="w-4 h-4 text-slate-400 group-focus-within:text-indigo-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
  </div>
  <input type="text" class="block w-full pl-10 pr-12 py-2.5 border border-slate-700 rounded-xl bg-slate-800 text-white placeholder-slate-400 focus:outline-none focus:ring-2 focus:ring-indigo-500/50 focus:border-indigo-500 sm:text-sm transition-all" placeholder="Search...">
  <div class="absolute inset-y-0 right-0 pr-2 flex items-center">
    <kbd class="px-2 py-0.5 bg-slate-700 border border-slate-600 rounded text-[10px] font-mono text-slate-300">⌘K</kbd>
  </div>
</div>'
                ],
                [
                    'id' => 'inp-file',
                    'name' => 'Drag & Drop Zone',
                    'code' => '<div class="flex items-center justify-center w-full max-w-sm">
  <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-slate-600 border-dashed rounded-xl cursor-pointer bg-slate-800/50 hover:bg-slate-800 hover:border-indigo-500/50 transition-all group">
    <div class="flex flex-col items-center justify-center pt-5 pb-6">
        <svg class="w-8 h-8 mb-2 text-slate-400 group-hover:text-indigo-400 transition" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
        <p class="text-xs text-slate-400"><span class="font-bold text-indigo-400">Click to upload</span> or drag and drop</p>
    </div>
    <input type="file" class="hidden" />
  </label>
</div>'
                ],
                [
                    'id' => 'inp-toggle',
                    'name' => 'Switch Toggle',
                    'code' => '<label class="inline-flex items-center cursor-pointer group">
  <input type="checkbox" value="" class="sr-only peer">
  <div class="w-11 h-6 bg-slate-700 peer-focus:outline-none peer-focus:ring-2 peer-focus:ring-indigo-500/50 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[\'\'] after:absolute after:top-[2px] after:left-[2px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-5 after:w-5 after:transition-all peer-checked:bg-indigo-500 relative"></div>
  <span class="ml-3 text-sm font-medium text-slate-300 group-hover:text-white transition">Dark Mode</span>
</label>'
                ],
                [
                    'id' => 'inp-checkbox',
                    'name' => 'Custom Checkbox',
                    'code' => '<label class="flex items-center gap-3 cursor-pointer group">
  <div class="relative flex items-center justify-center w-5 h-5">
    <input type="checkbox" class="peer appearance-none w-5 h-5 border-2 border-slate-500 rounded bg-slate-800 checked:bg-indigo-500 checked:border-indigo-500 transition-all cursor-pointer">
    <svg class="absolute w-3 h-3 text-white opacity-0 peer-checked:opacity-100 pointer-events-none transition-opacity" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path></svg>
  </div>
  <span class="text-sm text-slate-300 group-hover:text-white transition">Remember me</span>
</label>'
                ],
                [
                    'id' => 'inp-radiocard',
                    'name' => 'Radio Selection Card',
                    'code' => '<div class="grid grid-cols-2 gap-4 w-full max-w-sm">
  <label class="cursor-pointer">
    <input type="radio" name="plan" class="peer sr-only" checked>
    <div class="p-4 rounded-xl border-2 border-slate-700 bg-slate-800 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 transition-all text-center">
      <div class="text-white font-bold mb-1">Hobby</div>
      <div class="text-xs text-slate-400">$10/mo</div>
    </div>
  </label>
  <label class="cursor-pointer">
    <input type="radio" name="plan" class="peer sr-only">
    <div class="p-4 rounded-xl border-2 border-slate-700 bg-slate-800 peer-checked:border-indigo-500 peer-checked:bg-indigo-500/10 transition-all text-center">
      <div class="text-white font-bold mb-1">Pro</div>
      <div class="text-xs text-slate-400">$29/mo</div>
    </div>
  </label>
</div>'
                ],
                [
                    'id' => 'inp-password',
                    'name' => 'Password Reveal',
                    'code' => '<div class="w-full max-w-xs relative">
  <input type="password" class="w-full bg-slate-800 border border-slate-600 text-white rounded-lg px-4 py-2.5 pr-10 focus:ring-2 focus:ring-indigo-500 outline-none" placeholder="Enter password" value="secret123">
  <button class="absolute inset-y-0 right-0 pr-3 flex items-center text-slate-400 hover:text-white">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
  </button>
</div>'
                ],
                [
                    'id' => 'inp-number',
                    'name' => 'Number Stepper',
                    'code' => '<div class="flex items-center border border-slate-600 rounded-lg bg-slate-800 w-32 overflow-hidden">
  <button class="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-700 transition">-</button>
  <input type="text" class="w-full bg-transparent text-center text-white font-bold outline-none" value="1" readonly>
  <button class="px-3 py-2 text-slate-400 hover:text-white hover:bg-slate-700 transition">+</button>
</div>'
                ],
                [
                    'id' => 'inp-select',
                    'name' => 'Custom Select',
                    'code' => '<div class="relative w-full max-w-xs">
  <select class="w-full bg-slate-800 border border-slate-600 text-white appearance-none rounded-lg px-4 py-2.5 focus:ring-2 focus:ring-indigo-500 outline-none cursor-pointer">
    <option>United States</option>
    <option>Indonesia</option>
    <option>Japan</option>
  </select>
  <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-3 text-slate-400">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
  </div>
</div>'
                ],
                [
                    'id' => 'inp-subscribe',
                    'name' => 'Inline Subscribe',
                    'code' => '<div class="flex w-full max-w-sm">
  <input type="email" placeholder="Enter email" class="w-full bg-slate-800 border border-slate-600 rounded-l-lg px-4 py-2.5 text-white focus:outline-none focus:border-indigo-500">
  <button class="bg-indigo-600 text-white px-4 py-2.5 rounded-r-lg font-bold hover:bg-indigo-700 transition">Join</button>
</div>'
                ]
            ]
        ],
        'feedback' => [
            'title' => 'Feedback & Badges',
            'icon' => '🔔',
            'desc' => '10 Variasi Notifikasi, Badge, dan Indikator visual.',
            'items' => [
                [
                    'id' => 'alert-success',
                    'name' => 'Success Alert',
                    'code' => '<div class="flex items-center gap-4 p-4 rounded-xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 shadow-lg max-w-md w-full">
  <div class="w-10 h-10 rounded-full bg-emerald-500/20 flex items-center justify-center shrink-0 border border-emerald-500/30">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
  </div>
  <div>
    <h4 class="font-bold text-sm">Pembayaran Berhasil</h4>
    <p class="text-xs opacity-80 mt-1">Transaksi telah dikonfirmasi oleh sistem.</p>
  </div>
</div>'
                ],
                [
                    'id' => 'alert-warning',
                    'name' => 'Warning Glass',
                    'code' => '<div class="p-4 rounded-xl bg-yellow-500/10 border border-yellow-500/20 backdrop-blur-md flex gap-3 max-w-md w-full">
  <svg class="w-5 h-5 text-yellow-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
  <div>
    <h3 class="text-sm font-bold text-yellow-400">Penyimpanan Penuh</h3>
    <p class="text-xs text-yellow-200/70 mt-1">Harap hapus file untuk melanjutkan.</p>
  </div>
</div>'
                ],
                [
                    'id' => 'badge-group',
                    'name' => 'Status Badges',
                    'code' => '<div class="flex flex-wrap gap-3">
  <span class="px-2.5 py-1 rounded-md bg-blue-500/10 text-blue-400 text-xs font-bold border border-blue-500/20">Beta</span>
  <span class="px-3 py-1 rounded-full bg-emerald-500/10 text-emerald-400 text-xs font-bold border border-emerald-500/20 flex items-center gap-1.5"><span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span> Live</span>
  <span class="px-2.5 py-1 rounded-full bg-red-500/10 text-red-400 text-xs font-bold border border-red-500/20">Error</span>
  <span class="px-2.5 py-1 rounded text-xs font-mono bg-slate-800 text-slate-300 border border-slate-600">v2.0.1</span>
</div>'
                ],
                [
                    'id' => 'fb-tooltip',
                    'name' => 'CSS Tooltip',
                    'code' => '<div class="relative group inline-block">
  <button class="px-4 py-2 bg-slate-800 text-white rounded-lg border border-slate-700">Hover me</button>
  <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-3 py-1.5 bg-black text-white text-xs rounded opacity-0 group-hover:opacity-100 transition pointer-events-none whitespace-nowrap border border-white/10">
    This is a tooltip
    <div class="absolute top-full left-1/2 -translate-x-1/2 border-4 border-transparent border-t-black"></div>
  </div>
</div>'
                ],
                [
                    'id' => 'fb-skeleton',
                    'name' => 'Skeleton Loader',
                    'code' => '<div class="w-full max-w-sm flex gap-4 animate-pulse">
  <div class="w-12 h-12 bg-slate-700 rounded-full shrink-0"></div>
  <div class="flex-1 space-y-3 py-1">
    <div class="h-2 bg-slate-700 rounded w-3/4"></div>
    <div class="space-y-2">
      <div class="h-2 bg-slate-700 rounded"></div>
      <div class="h-2 bg-slate-700 rounded w-5/6"></div>
    </div>
  </div>
</div>'
                ],
                [
                    'id' => 'fb-progress',
                    'name' => 'Progress Bar',
                    'code' => '<div class="w-full max-w-sm">
  <div class="flex justify-between text-xs text-white mb-2">
    <span>Downloading...</span>
    <span class="font-mono text-cyan-400">45%</span>
  </div>
  <div class="w-full h-2 bg-slate-800 rounded-full overflow-hidden">
    <div class="h-full bg-cyan-500 w-[45%] rounded-full shadow-[0_0_10px_#06b6d4]"></div>
  </div>
</div>'
                ],
                [
                    'id' => 'fb-toast',
                    'name' => 'Floating Toast',
                    'code' => '<div class="flex items-center gap-3 px-4 py-3 bg-slate-800 border-l-4 border-l-indigo-500 rounded-r-lg shadow-xl w-72">
  <div class="text-indigo-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg></div>
  <div class="flex-1">
    <h4 class="text-white text-sm font-bold">New Update</h4>
    <p class="text-slate-400 text-xs">Version 3.0 is available.</p>
  </div>
  <button class="text-slate-500 hover:text-white"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
</div>'
                ],
                [
                    'id' => 'fb-empty',
                    'name' => 'Empty State',
                    'code' => '<div class="w-full max-w-sm p-6 text-center border-2 border-dashed border-slate-700 rounded-2xl bg-slate-800/50">
  <div class="w-12 h-12 bg-slate-800 rounded-full flex items-center justify-center mx-auto mb-3 text-slate-500">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
  </div>
  <h3 class="text-white font-bold text-sm">No Projects Found</h3>
  <p class="text-slate-400 text-xs mt-1 mb-4">Get started by creating a new project.</p>
  <button class="px-4 py-2 bg-indigo-600 text-white text-xs font-bold rounded-lg">+ New Project</button>
</div>'
                ],
                [
                    'id' => 'fb-pill',
                    'name' => 'Pill Icon Badges',
                    'code' => '<div class="flex gap-3">
  <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium bg-rose-500/10 text-rose-400 border border-rose-500/20">
    <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"></path></svg>
    Liked
  </span>
  <span class="inline-flex items-center gap-1.5 py-1 px-3 rounded-full text-xs font-medium bg-emerald-500/10 text-emerald-400 border border-emerald-500/20">
    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
    +2.4%
  </span>
</div>'
                ],
                [
                    'id' => 'fb-errorbox',
                    'name' => 'Error Message Box',
                    'code' => '<div class="p-4 bg-red-500/10 border border-red-500/30 rounded-lg max-w-md w-full">
  <h3 class="text-sm font-bold text-red-400 flex items-center gap-2 mb-2">
    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
    There were 2 errors with your submission
  </h3>
  <ul class="list-disc list-inside text-xs text-red-300/80 space-y-1 ml-6">
    <li>Password must be at least 8 characters.</li>
    <li>Email address is already in use.</li>
  </ul>
</div>'
                ]
            ]
        ],
        'navigation' => [
            'title' => 'Navigation & Layout',
            'icon' => '🧭',
            'desc' => '10 Komponen tata letak navigasi, pagination, dan menu.',
            'items' => [
                [
                    'id' => 'nav-tabs',
                    'name' => 'Pill Tabs',
                    'code' => '<div class="inline-flex p-1 bg-slate-800 rounded-xl border border-slate-700">
  <button class="px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-lg shadow-md">Account</button>
  <button class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition">Security</button>
  <button class="px-4 py-2 text-sm font-medium text-slate-400 hover:text-white hover:bg-white/5 rounded-lg transition">Billing</button>
</div>'
                ],
                [
                    'id' => 'nav-breadcrumbs',
                    'name' => 'Breadcrumbs',
                    'code' => '<nav class="flex px-5 py-3 text-sm bg-slate-800 border border-slate-700 rounded-xl" aria-label="Breadcrumb">
  <ol class="inline-flex items-center space-x-1 md:space-x-3">
    <li class="inline-flex items-center text-slate-400 hover:text-white transition cursor-pointer">
        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
        Home
    </li>
    <li><span class="mx-2 text-slate-600">/</span></li>
    <li class="text-slate-400 hover:text-white transition cursor-pointer">Projects</li>
    <li><span class="mx-2 text-slate-600">/</span></li>
    <li class="text-indigo-400 font-medium">Settings</li>
  </ol>
</nav>'
                ],
                [
                    'id' => 'av-group',
                    'name' => 'Avatar Stack',
                    'code' => '<div class="flex -space-x-3">
  <img class="w-10 h-10 border-2 border-slate-900 rounded-full" src="https://ui-avatars.com/api/?name=Alice&background=ef4444&color=fff" alt="">
  <img class="w-10 h-10 border-2 border-slate-900 rounded-full" src="https://ui-avatars.com/api/?name=Bob&background=3b82f6&color=fff" alt="">
  <img class="w-10 h-10 border-2 border-slate-900 rounded-full" src="https://ui-avatars.com/api/?name=Charlie&background=10b981&color=fff" alt="">
  <div class="flex items-center justify-center w-10 h-10 text-xs font-bold text-white bg-slate-700 border-2 border-slate-900 rounded-full hover:bg-slate-600 cursor-pointer">+9</div>
</div>'
                ],
                [
                    'id' => 'nav-pagination',
                    'name' => 'Pagination',
                    'code' => '<div class="inline-flex bg-slate-800 rounded-lg border border-slate-700 overflow-hidden shadow-sm">
  <button class="px-3 py-2 text-slate-400 hover:bg-slate-700 hover:text-white transition border-r border-slate-700"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg></button>
  <button class="px-4 py-2 text-sm text-white bg-indigo-600 font-bold border-r border-slate-700">1</button>
  <button class="px-4 py-2 text-sm text-slate-400 hover:bg-slate-700 hover:text-white transition border-r border-slate-700">2</button>
  <button class="px-4 py-2 text-sm text-slate-400 hover:bg-slate-700 hover:text-white transition border-r border-slate-700">3</button>
  <button class="px-3 py-2 text-slate-400 hover:bg-slate-700 hover:text-white transition"><svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg></button>
</div>'
                ],
                [
                    'id' => 'nav-sidebar',
                    'name' => 'Sidebar Menu Item',
                    'code' => '<div class="w-64 space-y-1">
  <a href="#" class="flex items-center gap-3 px-3 py-2.5 bg-indigo-500/10 text-indigo-400 rounded-lg border border-indigo-500/20 font-medium">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
    Dashboard
  </a>
  <a href="#" class="flex items-center gap-3 px-3 py-2.5 text-slate-400 hover:bg-white/5 hover:text-white rounded-lg transition font-medium">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
    Users
    <span class="ml-auto bg-slate-700 text-white text-[10px] py-0.5 px-2 rounded-full">12</span>
  </a>
</div>'
                ],
                [
                    'id' => 'nav-bottom',
                    'name' => 'Mobile Bottom Nav',
                    'code' => '<div class="w-full max-w-sm bg-slate-800 border-t border-slate-700 rounded-t-2xl flex justify-around p-3">
  <button class="flex flex-col items-center gap-1 text-indigo-400">
    <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20"><path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path></svg>
    <span class="text-[10px] font-bold">Home</span>
  </button>
  <button class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
    <span class="text-[10px] font-medium">Search</span>
  </button>
  <button class="flex flex-col items-center gap-1 text-slate-400 hover:text-white transition">
    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
    <span class="text-[10px] font-medium">Profile</span>
  </button>
</div>'
                ],
                [
                    'id' => 'nav-dropdown',
                    'name' => 'User Dropdown',
                    'code' => '<div class="relative group inline-block">
  <button class="flex items-center gap-2 p-1 pl-3 bg-slate-800 border border-slate-700 rounded-full hover:bg-slate-700 transition">
    <span class="text-sm font-medium text-white">Jane Doe</span>
    <img src="https://ui-avatars.com/api/?name=Jane+Doe" class="w-8 h-8 rounded-full">
  </button>
  <div class="absolute right-0 mt-2 w-48 bg-slate-800 border border-slate-700 rounded-xl shadow-xl opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all transform translate-y-2 group-hover:translate-y-0 overflow-hidden">
    <div class="px-4 py-3 border-b border-slate-700">
        <p class="text-sm text-white">Signed in as</p>
        <p class="text-xs text-slate-400 truncate">jane@example.com</p>
    </div>
    <div class="py-1">
        <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">Account settings</a>
        <a href="#" class="block px-4 py-2 text-sm text-slate-300 hover:bg-slate-700 hover:text-white">Support</a>
    </div>
    <div class="py-1 border-t border-slate-700">
        <a href="#" class="block px-4 py-2 text-sm text-red-400 hover:bg-slate-700">Sign out</a>
    </div>
  </div>
</div>'
                ],
                [
                    'id' => 'nav-steps',
                    'name' => 'Step Wizard',
                    'code' => '<ol class="flex items-center w-full max-w-md">
  <li class="flex w-full items-center text-indigo-500 after:content-[\'\'] after:w-full after:h-1 after:border-b after:border-indigo-500 after:border-4 after:inline-block">
    <span class="flex items-center justify-center w-8 h-8 bg-indigo-500 rounded-full lg:h-10 lg:w-10 shrink-0 text-white font-bold">
      ✓
    </span>
  </li>
  <li class="flex w-full items-center text-indigo-500 after:content-[\'\'] after:w-full after:h-1 after:border-b after:border-slate-700 after:border-4 after:inline-block">
    <span class="flex items-center justify-center w-8 h-8 bg-slate-800 border-2 border-indigo-500 rounded-full lg:h-10 lg:w-10 shrink-0 font-bold">
      2
    </span>
  </li>
  <li class="flex items-center">
    <span class="flex items-center justify-center w-8 h-8 bg-slate-800 border-2 border-slate-700 rounded-full lg:h-10 lg:w-10 shrink-0 text-slate-500 font-bold">
      3
    </span>
  </li>
</ol>'
                ],
                [
                    'id' => 'nav-header',
                    'name' => 'Simple Navbar',
                    'code' => '<nav class="w-full max-w-2xl bg-slate-800/80 backdrop-blur border border-slate-700 px-6 py-3 rounded-2xl flex justify-between items-center shadow-lg">
  <div class="text-white font-black text-xl tracking-tighter">LOGO<span class="text-indigo-500">.</span></div>
  <div class="hidden md:flex gap-6 text-sm font-medium text-slate-300">
    <a href="#" class="text-white">Home</a>
    <a href="#" class="hover:text-white transition">Features</a>
    <a href="#" class="hover:text-white transition">Pricing</a>
  </div>
  <button class="px-4 py-2 bg-white text-slate-900 text-sm font-bold rounded-lg hover:bg-slate-200 transition">Login</button>
</nav>'
                ],
                [
                    'id' => 'nav-timeline',
                    'name' => 'Vertical Timeline',
                    'code' => '<div class="relative border-l border-slate-700 ml-3 space-y-6">
  <div class="relative pl-6">
    <div class="absolute w-3 h-3 bg-indigo-500 rounded-full -left-[6.5px] top-1.5 ring-4 ring-slate-900"></div>
    <p class="text-xs text-indigo-400 font-mono mb-1">Feb 2026</p>
    <h4 class="text-white font-bold text-sm">Application Released</h4>
    <p class="text-slate-400 text-xs mt-1">v1.0 is now live for all users.</p>
  </div>
  <div class="relative pl-6 opacity-50">
    <div class="absolute w-3 h-3 bg-slate-600 rounded-full -left-[6.5px] top-1.5 ring-4 ring-slate-900"></div>
    <p class="text-xs text-slate-500 font-mono mb-1">Jan 2026</p>
    <h4 class="text-white font-bold text-sm">Beta Testing</h4>
  </div>
</div>'
                ]
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
        <aside class="w-64 hidden lg:block shrink-0 h-[calc(100vh-80px)] sticky top-20 overflow-y-auto custom-scrollbar border-r border-white/5 py-8 pl-6 pr-4 bg-[#020617]/50 backdrop-blur-sm">
            <div class="mb-6 px-2">
                <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-1">Explore Library</h3>
                <p class="text-[10px] text-white/30">Total {{ collect($gallery)->sum(fn($c) => count($c['items'])) }} Komponen UI</p>
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
                    <span class="text-cyan-400 drop-shadow-[0_0_8px_rgba(34,211,238,0.5)]">Kamus Komponen</span>
                </nav>
                {{-- BREADCRUMB END --}}

                <div class="flex flex-col xl:flex-row justify-between items-start xl:items-end gap-6 mb-8">
                    <div>
                        <h1 class="text-4xl md:text-5xl lg:text-6xl font-black text-white mb-4 tracking-tight">
                            Kamus <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 to-fuchsia-500">Komponen</span>
                        </h1>
                        <p class="text-white/50 text-base md:text-lg max-w-2xl leading-relaxed">
                            Kumpulan 50+ komponen antarmuka modern yang dibangun sepenuhnya dengan utilitas Tailwind CSS. Salin kodenya atau bedah langsung di Sandbox interaktif.
                        </p>
                    </div>
                    
                    {{-- Search Box --}}
                    <div class="relative group w-full xl:w-80 z-20 shrink-0">
                        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600 to-fuchsia-600 rounded-xl blur opacity-20 group-hover:opacity-40 transition duration-500"></div>
                        <div class="relative bg-[#0f141e] border border-white/10 rounded-xl flex items-center px-4 h-14 shadow-xl">
                            <svg class="w-5 h-5 text-white/30 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                            <input type="text" id="compSearch" placeholder="Cari tombol, card, form..." class="w-full bg-transparent border-none text-white placeholder-white/30 text-sm focus:ring-0 focus:outline-none font-medium">
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
                                    <div class="comp-card group bg-[#0f141e] border border-white/10 rounded-3xl overflow-hidden hover:border-cyan-500/30 transition-all duration-300 hover:shadow-2xl hover:shadow-cyan-900/10 flex flex-col h-full ring-1 ring-white/0 hover:ring-white/10">
                                        
                                        {{-- Header: Actions --}}
                                        <div class="px-6 py-4 border-b border-white/5 bg-white/[0.02] flex justify-between items-center">
                                            <h3 class="text-sm font-bold text-white/80 group-hover:text-white transition flex items-center gap-2 truncate pr-4">
                                                {{ $item['name'] }}
                                            </h3>
                                            
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
                                                <button onclick="sendToSandbox('{{ $item['id'] }}')" class="flex items-center gap-1.5 px-3 py-1.5 rounded-lg bg-cyan-500/10 text-cyan-400 border border-cyan-500/20 hover:bg-cyan-500 hover:text-white transition text-[10px] md:text-xs font-bold shadow-lg shadow-cyan-500/10" title="Edit di Sandbox">
                                                    <span>Try</span>
                                                    <svg class="w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14" /></svg>
                                                </button>
                                            </div>
                                        </div>

                                        {{-- Visual Preview --}}
                                        <div class="p-6 md:p-10 bg-[#020617] flex items-center justify-center min-h-[200px] md:min-h-[250px] relative overflow-hidden flex-1 group-hover:bg-[#050912] transition">
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
                    <p class="text-white/20 text-xs">Utilwind Component Library v2.0 &copy; {{ date('Y') }}</p>
                    <p class="text-white/10 text-[10px] mt-1">Total 50 Modular Components.</p>
                </div>

            </div>
        </main>
    </div>

    {{-- TOAST NOTIFICATION (Visual Feedback) --}}
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

    // 3. SEND TO SANDBOX
    function sendToSandbox(id) {
        const rawHtml = document.getElementById('raw-' + id).value;
        
        // Membungkus code dengan template background slate gelap persis seperti di preview Kamus
        const template = `<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sandbox Component</title>
    <script src="https://cdn.tailwindcss.com"><\/script>
</head>
<body class="bg-slate-900 min-h-screen flex items-center justify-center p-8 antialiased">
    
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