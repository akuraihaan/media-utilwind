@extends('layouts.landing')
@section('title', 'Peta Konsep & Daftar Materi')

@section('content')
{{-- 
    DATA SOURCE: 
    Mendefinisikan struktur materi sesuai daftar isi yang diberikan.
--}}
@php
    $chapters = [
        [
            'id' => 1,
            'number' => '01',
            'title' => 'PENDAHULUAN',
            'desc' => 'Fondasi, Filosofi, dan Instalasi',
            'color' => 'cyan', // Theme color
            'route' => 'courses.htmldancss', // Link ke awal bab
            'topics' => [
                ['code' => '1.1', 'name' => 'Konsep Dasar HTML dan CSS'],
                ['code' => '1.2', 'name' => 'Konsep Dasar Tailwind CSS'],
                ['code' => '1.3', 'name' => 'Latar Belakang Tailwind CSS', 'subs' => [
                    '1.3.1 Struktur Dasar',
                    '1.3.2 Pengelolaan Duplikasi Kode'
                ]],
                ['code' => '1.4', 'name' => 'Menerapkan Tailwind pada HTML'],
                ['code' => '1.5', 'name' => 'Keunggulan Tailwind CSS'],
                ['code' => '1.6', 'name' => 'Instalasi & Konfigurasi', 'subs' => [
                    '1.6.1 Langkah Instalasi',
                    '1.6.2 Integrasi File CSS',
                    '1.6.3 Proses Kompilasi',
                    '1.6.4 Konfigurasi Tema',
                    '1.6.5 Pengujian Instalasi'
                ]],
            ]
        ],
        [
            'id' => 2,
            'number' => '02',
            'title' => 'LAYOUTING',
            'desc' => 'Menguasai Tata Letak Modern',
            'color' => 'indigo',
            'route' => 'courses.flexbox',
            'topics' => [
                ['code' => '2.1', 'name' => 'Membangun Layout dengan Flexbox', 'subs' => [
                    '2.1.1 Ukuran Flexbox', '2.1.2 Arah Flexbox', '2.1.3 Flex Wrap', 
                    '2.1.4 Flex', '2.1.5 Flex Grow', '2.1.6 Flex Shrink', '2.1.7 Flex Order'
                ]],
                ['code' => '2.2', 'name' => 'Membangun Layout dengan Grid', 'subs' => [
                    '2.2.1 Konsep Grid Layout', '2.2.2 Penjajaran Grid', '2.2.3 Span & Start/End',
                    '2.2.4 Grid Template Rows', '2.2.5 Grid Template Columns', '2.2.6 Grid Auto Flow'
                ]],
                ['code' => '2.3', 'name' => 'Mengelola Layout Tingkat Lanjut', 'subs' => [
                    '2.3.1 Container', '2.3.2 Float and Clear', 
                    '2.3.3 Position and Z-Index', '2.3.4 Table Layout'
                ]],
            ]
        ],
        [
            'id' => 3,
            'number' => '03',
            'title' => 'STYLING',
            'desc' => 'Dekorasi & Efek Visual',
            'color' => 'fuchsia',
            'route' => 'courses.typography',
            'topics' => [
                ['code' => '3.1', 'name' => 'Tipografi', 'subs' => [
                    '3.1.1 Font Family', '3.1.2 Font Size', '3.1.3 Font Smoothing', 
                    '3.1.4 Font Style', '3.1.5 Font Weight', '3.1.6 Letter Spacing', 
                    '3.1.7 List Style', '3.1.8 Text Align', '3.1.9 Color & Decoration',
                    '3.1.10 Text Transform', '3.1.11 Text Overflow', '3.1.12 Word Break'
                ]],
                ['code' => '3.2', 'name' => 'Background', 'subs' => [
                    '3.2.1 Attachment & Clip', '3.2.2 Color', '3.2.3 Origin', 
                    '3.2.4 Position', '3.2.5 Repeat', '3.2.6 Size', '3.2.7 Image & Gradient'
                ]],
                ['code' => '3.3', 'name' => 'Borders', 'subs' => [
                    '3.3.1 Border Radius', '3.3.2 Border Width', '3.3.3 Color & Style',
                    '3.3.4 Divide', '3.3.5 Outline', '3.3.6 Ring & Offset'
                ]],
                ['code' => '3.4', 'name' => 'Efek Visual', 'subs' => [
                    '3.4.1 Box Shadow', '3.4.2 Opacity', '3.4.3 Filters',
                    '3.4.4 Transisi & Animasi', '3.4.5 Transforms'
                ]],
            ]
        ]
    ];
@endphp

<div class="relative min-h-screen bg-[#020617] text-white font-sans overflow-x-hidden selection:bg-indigo-500/30 pt-24 pb-20" x-data="{ search: '' }">

    {{-- BACKGROUND ATMOSPHERE --}}
    <div class="fixed inset-0 -z-50 pointer-events-none">
        <div class="absolute top-0 left-1/4 w-[800px] h-[800px] bg-indigo-900/10 rounded-full blur-[120px] animate-pulse"></div>
        <div class="absolute bottom-0 right-1/4 w-[600px] h-[600px] bg-cyan-900/10 rounded-full blur-[100px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
    </div>

    @include('layouts.partials.navbar')

    <div class="max-w-7xl mx-auto px-6 lg:px-8 relative z-10">

        {{-- HEADER SECTION --}}
        <div class="text-center mb-16 space-y-4">
            
            
            <h1 class="text-4xl mt-20 md:text-6xl font-black tracking-tight text-white mb-2">
                Peta Konsep <br class="hidden md:block" />
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-cyan-400 via-indigo-400 to-fuchsia-400">Tailwind Mastery</span>
            </h1>
            
            <p class="text-slate-400 max-w-2xl mx-auto text-lg leading-relaxed">
                Jelajahi kurikulum komprehensif dari dasar hingga mahir. Gunakan pencarian untuk menemukan topik spesifik.
            </p>

            {{-- SEARCH BAR --}}
            <div class="max-w-xl mx-auto mt-8 relative group">
                <div class="absolute -inset-1 bg-gradient-to-r from-cyan-500 to-fuchsia-500 rounded-2xl opacity-20 group-hover:opacity-40 transition duration-500 blur"></div>
                <div class="relative">
                    <input x-model="search" type="text" placeholder="Cari materi (contoh: Grid, Flex, Border...)" 
                        class="w-full bg-[#0f172a] text-white placeholder-slate-500 rounded-xl border border-white/10 py-4 pl-12 pr-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition-all shadow-2xl">
                    <svg class="w-5 h-5 text-slate-500 absolute left-4 top-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
            </div>
        </div>

        {{-- COURSE GRID --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 items-start">

            @foreach($chapters as $chapter)
                {{-- CARD BAB --}}
                <div x-show="!search || JSON.stringify({{ json_encode($chapter) }}).toLowerCase().includes(search.toLowerCase())" 
                     class="group relative bg-[#0f172a]/80 backdrop-blur-md rounded-3xl border border-white/10 overflow-hidden hover:border-{{ $chapter['color'] }}-500/30 transition-all duration-300 hover:shadow-2xl hover:shadow-{{ $chapter['color'] }}-500/10">
                    
                    {{-- Decorative Header --}}
                    <div class="absolute top-0 right-0 p-4 opacity-10 group-hover:opacity-20 transition-opacity">
                        <span class="text-6xl font-black text-{{ $chapter['color'] }}-500">{{ $chapter['number'] }}</span>
                    </div>

                    <div class="p-8">
                        {{-- Title --}}
                        <div class="mb-6">
                            <span class="text-xs font-bold text-{{ $chapter['color'] }}-400 tracking-widest uppercase mb-1 block">{{ $chapter['desc'] }}</span>
                            <h2 class="text-2xl font-bold text-white group-hover:text-{{ $chapter['color'] }}-300 transition-colors">
                                {{ $chapter['title'] }}
                            </h2>
                        </div>

                        {{-- Lessons List --}}
                        <div class="space-y-6 relative">
                            {{-- Vertical Line --}}
                            <div class="absolute left-[11px] top-2 bottom-2 w-px bg-white/10 group-hover:bg-{{ $chapter['color'] }}-500/30 transition-colors"></div>

                            @foreach($chapter['topics'] as $topic)
                                <div x-show="!search || '{{ strtolower($topic['name']) }}'.includes(search.toLowerCase()) || {{ isset($topic['subs']) ? 'true' : 'false' }}" 
                                     class="relative pl-8">
                                    
                                    {{-- Dot --}}
                                    <div class="absolute left-[7px] top-[7px] w-2.5 h-2.5 rounded-full bg-[#0f172a] border-2 border-slate-600 group-hover:border-{{ $chapter['color'] }}-400 transition-colors z-10"></div>
                                    
                                    {{-- Topic Name --}}
                                    <h3 class="text-sm font-bold text-slate-200 mb-1 leading-snug">
                                        <span class="text-slate-500 mr-1">{{ $topic['code'] }}</span> {{ $topic['name'] }}
                                    </h3>

                                    {{-- Sub Topics (Jika ada) --}}
                                    @if(isset($topic['subs']))
                                        <ul class="space-y-1 mt-2 mb-3">
                                            @foreach($topic['subs'] as $sub)
                                                <li x-show="!search || '{{ strtolower($sub) }}'.includes(search.toLowerCase())"
                                                    class="text-[11px] text-slate-500 flex items-start gap-2 hover:text-white transition-colors cursor-default">
                                                    <span class="w-1 h-1 rounded-full bg-white/20 mt-1.5 shrink-0"></span>
                                                    {{ $sub }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Footer Action --}}
                    <div class="p-4 bg-white/[0.02] border-t border-white/5 flex justify-between items-center group-hover:bg-white/[0.04] transition-colors">
                        <span class="text-xs text-slate-400 font-mono">{{ count($chapter['topics']) }} Materi Utama</span>
                        <a href="{{ route($chapter['route']) }}" class="flex items-center gap-2 text-xs font-bold text-{{ $chapter['color'] }}-400 hover:text-white transition-colors">
                            MULAI BELAJAR <span aria-hidden="true">&rarr;</span>
                        </a>
                    </div>
                </div>
            @endforeach

        </div>

        {{-- BOTTOM SECTION --}}
        <div class="mt-20 border-t border-white/10 pt-10 grid md:grid-cols-2 gap-8 text-slate-400 text-sm">
            <div>
                <h4 class="text-white font-bold mb-4 uppercase tracking-widest text-xs">Referensi & Sumber</h4>
                <ul class="space-y-2">
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.668 5.477 15.246 5 17 5c1.754 0 3.332.477 4.5 1.253v13C20.332 18.477 18.754 18 17 18c-1.754 0-3.332.477-4.5 1.253"/></svg> Tailwind CSS Official Documentation</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.668 5.477 15.246 5 17 5c1.754 0 3.332.477 4.5 1.253v13C20.332 18.477 18.754 18 17 18c-1.754 0-3.332.477-4.5 1.253"/></svg> Modern CSS with Tailwind by Noel Rappin</li>
                    <li class="flex items-center gap-2"><svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.668 5.477 15.246 5 17 5c1.754 0 3.332.477 4.5 1.253v13C20.332 18.477 18.754 18 17 18c-1.754 0-3.332.477-4.5 1.253"/></svg> Ultimate Tailwind CSS Handbook by Kartik Bhat</li>
                </ul>
            </div>
           
        </div>

    </div>
</div>

<script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.13.3/dist/cdn.min.js"></script>
<style>
    /* Custom Scrollbar for better UX */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #020617; }
    ::-webkit-scrollbar-thumb { background: #334155; border-radius: 4px; }
    ::-webkit-scrollbar-thumb:hover { background: #475569; }
</style>
@endsection