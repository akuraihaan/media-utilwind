@extends('layouts.landing')

@section('title', 'Kamus & Cheat Sheet Utilitas · Utilwind')

@php
    // =======================================================================
    // DATABASE KAMUS UTILITAS (ULTIMATE EDITION)
    // =======================================================================
    $dictionary = [
        'layout' => [
            'title' => 'Layout & Display',
            'icon' => '📐',
            'color' => 'text-blue-400',
            'bg' => 'bg-blue-500/20',
            'items' => [
                ['class' => 'block', 'desc' => 'display: block', 'type' => 'layout'],
                ['class' => 'inline-block', 'desc' => 'display: inline-block', 'type' => 'layout'],
                ['class' => 'inline', 'desc' => 'display: inline', 'type' => 'text'],
                ['class' => 'flex', 'desc' => 'display: flex', 'type' => 'layout'],
                ['class' => 'grid', 'desc' => 'display: grid', 'type' => 'layout'],
                ['class' => 'hidden', 'desc' => 'display: none', 'type' => 'empty'],
                ['class' => 'relative', 'desc' => 'position: relative', 'type' => 'layout'],
                ['class' => 'absolute', 'desc' => 'position: absolute', 'type' => 'layout'],
                ['class' => 'fixed', 'desc' => 'position: fixed', 'type' => 'layout'],
                ['class' => 'sticky', 'desc' => 'position: sticky', 'type' => 'layout'],
                ['class' => 'z-10', 'desc' => 'z-index: 10', 'type' => 'layer'],
                ['class' => 'z-50', 'desc' => 'z-index: 50', 'type' => 'layer'],
                ['class' => 'overflow-hidden', 'desc' => 'Clip content', 'type' => 'layout'],
                ['class' => 'overflow-auto', 'desc' => 'Scrollbars if needed', 'type' => 'layout'],
            ]
        ],
        'flexbox' => [
            'title' => 'Flexbox & Grid',
            'icon' => '🍱',
            'color' => 'text-fuchsia-400',
            'bg' => 'bg-fuchsia-500/20',
            'items' => [
                ['class' => 'flex-row', 'desc' => 'Direction Horizontal', 'type' => 'flex'],
                ['class' => 'flex-col', 'desc' => 'Direction Vertical', 'type' => 'flex-v'],
                ['class' => 'flex-wrap', 'desc' => 'Allow wrapping', 'type' => 'flex'],
                ['class' => 'justify-center', 'desc' => 'Main-axis Center', 'type' => 'flex'],
                ['class' => 'justify-between', 'desc' => 'Space Between', 'type' => 'flex'],
                ['class' => 'justify-around', 'desc' => 'Space Around', 'type' => 'flex'],
                ['class' => 'items-center', 'desc' => 'Cross-axis Center', 'type' => 'flex'],
                ['class' => 'items-end', 'desc' => 'Cross-axis End', 'type' => 'flex'],
                ['class' => 'gap-4', 'desc' => 'Gap 1rem (16px)', 'type' => 'flex'],
                ['class' => 'grid-cols-2', 'desc' => '2 Columns', 'type' => 'grid'],
                ['class' => 'grid-cols-3', 'desc' => '3 Columns', 'type' => 'grid'],
                ['class' => 'grid-cols-4', 'desc' => '4 Columns', 'type' => 'grid'],
                ['class' => 'col-span-2', 'desc' => 'Span 2 cols', 'type' => 'grid-span'],
            ]
        ],
        'spacing' => [
            'title' => 'Spacing & Sizing',
            'icon' => '↔',
            'color' => 'text-green-400',
            'bg' => 'bg-green-500/20',
            'items' => [
                ['class' => 'p-4', 'desc' => 'Padding All', 'type' => 'box'],
                ['class' => 'px-4', 'desc' => 'Padding X', 'type' => 'box'],
                ['class' => 'py-4', 'desc' => 'Padding Y', 'type' => 'box'],
                ['class' => 'm-4', 'desc' => 'Margin All', 'type' => 'box-out'],
                ['class' => 'mx-auto', 'desc' => 'Center Container', 'type' => 'layout'],
                ['class' => 'w-full', 'desc' => 'Width 100%', 'type' => 'size'],
                ['class' => 'w-1/2', 'desc' => 'Width 50%', 'type' => 'size'],
                ['class' => 'w-screen', 'desc' => 'Width 100vw', 'type' => 'size'],
                ['class' => 'h-screen', 'desc' => 'Height 100vh', 'type' => 'size'],
                ['class' => 'min-h-screen', 'desc' => 'Min-Height 100vh', 'type' => 'size'],
                ['class' => 'max-w-md', 'desc' => 'Max Width Medium', 'type' => 'size'],
            ]
        ],
        'typography' => [
            'title' => 'Typography',
            'icon' => 'Aa',
            'color' => 'text-yellow-400',
            'bg' => 'bg-yellow-500/20',
            'items' => [
                ['class' => 'text-xs', 'desc' => '0.75rem', 'type' => 'text'],
                ['class' => 'text-sm', 'desc' => '0.875rem', 'type' => 'text'],
                ['class' => 'text-base', 'desc' => '1rem (Default)', 'type' => 'text'],
                ['class' => 'text-lg', 'desc' => '1.125rem', 'type' => 'text'],
                ['class' => 'text-xl', 'desc' => '1.25rem', 'type' => 'text'],
                ['class' => 'text-3xl', 'desc' => '1.875rem', 'type' => 'text'],
                ['class' => 'font-bold', 'desc' => 'Weight 700', 'type' => 'text'],
                ['class' => 'font-black', 'desc' => 'Weight 900', 'type' => 'text'],
                ['class' => 'tracking-widest', 'desc' => 'Letter Spacing', 'type' => 'text'],
                ['class' => 'leading-loose', 'desc' => 'Line Height', 'type' => 'text-long'],
                ['class' => 'text-center', 'desc' => 'Align Center', 'type' => 'text-align'],
                ['class' => 'uppercase', 'desc' => 'Transform Upper', 'type' => 'text'],
                ['class' => 'capitalize', 'desc' => 'Transform Capital', 'type' => 'text'],
                ['class' => 'truncate', 'desc' => 'Overflow Ellipsis', 'type' => 'text'],
                ['class' => 'underline', 'desc' => 'Text Decoration', 'type' => 'text'],
            ]
        ],
        'colors' => [
            'title' => 'Colors & Backgrounds',
            'icon' => '🎨',
            'color' => 'text-rose-400',
            'bg' => 'bg-rose-500/20',
            'items' => [
                ['class' => 'bg-slate-800', 'desc' => 'Background Color', 'type' => 'color'],
                ['class' => 'bg-indigo-500', 'desc' => 'Background Color', 'type' => 'color'],
                ['class' => 'bg-rose-500', 'desc' => 'Background Color', 'type' => 'color'],
                ['class' => 'bg-emerald-500', 'desc' => 'Background Color', 'type' => 'color'],
                ['class' => 'text-cyan-400', 'desc' => 'Text Color', 'type' => 'text'],
                ['class' => 'text-fuchsia-400', 'desc' => 'Text Color', 'type' => 'text'],
                ['class' => 'bg-opacity-50', 'desc' => 'Alpha Channel', 'type' => 'color'],
                ['class' => 'bg-gradient-to-r', 'desc' => 'Gradient Direction', 'type' => 'gradient'],
                ['class' => 'from-blue-500', 'desc' => 'Gradient Start', 'type' => 'gradient'],
                ['class' => 'via-purple-500', 'desc' => 'Gradient Middle', 'type' => 'gradient'],
                ['class' => 'to-pink-500', 'desc' => 'Gradient End', 'type' => 'gradient'],
            ]
        ],
        'effects' => [
            'title' => 'Borders & Effects',
            'icon' => '✨',
            'color' => 'text-cyan-400',
            'bg' => 'bg-cyan-500/20',
            'items' => [
                ['class' => 'rounded-md', 'desc' => 'Radius Medium', 'type' => 'border'],
                ['class' => 'rounded-xl', 'desc' => 'Radius Extra Large', 'type' => 'border'],
                ['class' => 'rounded-full', 'desc' => 'Radius Circle', 'type' => 'border'],
                ['class' => 'border-2', 'desc' => 'Width 2px', 'type' => 'border'],
                ['class' => 'border-dashed', 'desc' => 'Style Dashed', 'type' => 'border'],
                ['class' => 'border-indigo-500', 'desc' => 'Border Color', 'type' => 'border'],
                ['class' => 'shadow-md', 'desc' => 'Shadow Medium', 'type' => 'shadow'],
                ['class' => 'shadow-2xl', 'desc' => 'Shadow Extra Large', 'type' => 'shadow'],
                ['class' => 'opacity-50', 'desc' => 'Opacity 0.5', 'type' => 'opacity'],
                ['class' => 'blur-sm', 'desc' => 'Filter Blur', 'type' => 'filter'],
                ['class' => 'grayscale', 'desc' => 'Filter Grayscale', 'type' => 'filter'],
                ['class' => 'backdrop-blur-md', 'desc' => 'Glass Effect', 'type' => 'filter'],
            ]
        ],
        'interactivity' => [
            'title' => 'Interactivity',
            'icon' => '🖱️',
            'color' => 'text-amber-400',
            'bg' => 'bg-amber-500/20',
            'items' => [
                ['class' => 'cursor-pointer', 'desc' => 'Cursor Hand', 'type' => 'cursor'],
                ['class' => 'cursor-not-allowed', 'desc' => 'Cursor Blocked', 'type' => 'cursor'],
                ['class' => 'select-none', 'desc' => 'Prevent Selection', 'type' => 'text'],
                ['class' => 'hover:bg-white', 'desc' => 'Hover State', 'type' => 'state'],
                ['class' => 'active:scale-95', 'desc' => 'Click State', 'type' => 'state'],
                ['class' => 'group-hover:opacity-100', 'desc' => 'Parent Hover', 'type' => 'state'],
                ['class' => 'focus:ring', 'desc' => 'Focus Ring', 'type' => 'input'],
                ['class' => 'transition-all', 'desc' => 'Enable Transition', 'type' => 'anim'],
                ['class' => 'duration-500', 'desc' => 'Timing 500ms', 'type' => 'anim'],
                ['class' => 'ease-in-out', 'desc' => 'Easing Function', 'type' => 'anim'],
                ['class' => 'animate-bounce', 'desc' => 'Keyframe Bounce', 'type' => 'anim'],
                ['class' => 'animate-pulse', 'desc' => 'Keyframe Pulse', 'type' => 'anim'],
                ['class' => 'animate-spin', 'desc' => 'Keyframe Spin', 'type' => 'anim'],
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
        <aside class="w-64 hidden lg:block shrink-0 h-[calc(100vh-80px)] sticky top-20 overflow-y-auto custom-scrollbar border-r border-white/5 py-8 pl-6 pr-4">
            <h3 class="text-xs font-bold text-white/40 uppercase tracking-widest mb-6 px-2">Table of Contents</h3>
            <ul class="space-y-1" id="toc-list">
                @foreach($dictionary as $key => $category)
                    <li>
                        <a href="#{{ $key }}" class="nav-item group flex items-center gap-3 px-3 py-2.5 rounded-lg transition text-sm text-white/60 hover:text-white hover:bg-white/5 border border-transparent">
                            <span class="{{ $category['color'] }} text-lg opacity-70 group-hover:opacity-100 transition">{{ $category['icon'] }}</span>
                            <span class="font-medium">{{ $category['title'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </aside>

        {{-- MAIN CONTENT --}}
        <main class="flex-1 p-6 lg:p-10 w-full overflow-hidden">
            
            {{-- Header & Search --}}
            <div class="max-w-4xl mx-auto mb-16 text-center">
                <span class="inline-flex items-center gap-2 py-1 px-3 rounded-full bg-fuchsia-500/10 border border-fuchsia-500/20 text-fuchsia-400 text-[10px] font-bold uppercase tracking-widest mb-4">
                    <span class="w-1.5 h-1.5 rounded-full bg-fuchsia-500 animate-pulse"></span> Ultimate Reference
                </span>
                <h1 class="text-4xl md:text-6xl font-black text-white mb-6">Kamus <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Utilitas</span></h1>
                
                {{-- SEARCH INPUT --}}
                <div class="relative max-w-xl mx-auto group z-20">
                    <div class="absolute inset-0 bg-gradient-to-r from-fuchsia-600 to-cyan-600 rounded-2xl blur opacity-25 group-hover:opacity-50 transition duration-500"></div>
                    <div class="relative bg-[#0f141e] border border-white/10 rounded-2xl flex items-center px-5 shadow-2xl transition-all group-focus-within:border-white/30">
                        <svg class="w-6 h-6 text-white/30 mr-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                        <input type="text" id="searchInput" placeholder="Cari class (contoh: flex, text-center, shadow...)" 
                            class="w-full bg-transparent border-none text-white placeholder-white/30 h-16 focus:ring-0 focus:outline-none text-base font-medium">
                        <span class="hidden md:block text-[10px] font-mono text-white/20 border border-white/10 px-2 py-1 rounded bg-white/5">CTRL + K</span>
                    </div>
                </div>
            </div>

            {{-- CONTENT SECTIONS --}}
            <div class="max-w-6xl mx-auto space-y-24 pb-32">

                @foreach($dictionary as $key => $category)
                    <section id="{{ $key }}" class="section-group scroll-mt-32">
                        
                        {{-- Section Header --}}
                        <div class="flex items-center gap-4 mb-8 border-b border-white/10 pb-6">
                            <div class="w-14 h-14 rounded-2xl {{ $category['bg'] }} {{ $category['color'] }} border border-white/10 flex items-center justify-center text-3xl shadow-lg">
                                {{ $category['icon'] }}
                            </div>
                            <div>
                                <h2 class="text-3xl font-bold text-white">{{ $category['title'] }}</h2>
                                <p class="text-sm text-white/40 font-mono mt-1">#{{ $key }}</p>
                            </div>
                        </div>
                        
                        {{-- Cards Grid --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($category['items'] as $item)
                                <div class="cheat-card group relative bg-[#0f141e] border border-white/10 rounded-2xl overflow-hidden hover:border-fuchsia-500/30 transition-all duration-300 hover:shadow-2xl hover:shadow-fuchsia-900/10 flex flex-col" data-class="{{ $item['class'] }}">
                                    
                                    {{-- Card Header --}}
                                    <div class="px-5 py-4 border-b border-white/5 bg-white/[0.02] flex justify-between items-center group-hover:bg-white/[0.04] transition">
                                        <code class="text-sm font-bold text-cyan-400 font-mono cursor-pointer hover:underline hover:text-cyan-300 transition" onclick="copyToClipboard('{{ $item['class'] }}')">
                                            .{{ $item['class'] }}
                                        </code>
                                        
                                        <div class="flex gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300 translate-x-2 group-hover:translate-x-0">
                                            {{-- Copy Button --}}
                                            <button onclick="copyToClipboard('{{ $item['class'] }}')" class="p-1.5 rounded-lg bg-white/5 text-white/50 hover:bg-white/20 hover:text-white transition" title="Copy Class">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z" /></svg>
                                            </button>
                                            {{-- Sandbox Button (Base64 Encoded for URL Safety) --}}
                                            <button onclick="sendToSandbox('{{ $item['class'] }}')" class="p-1.5 rounded-lg bg-fuchsia-500/20 text-fuchsia-400 hover:bg-fuchsia-500 hover:text-white transition" title="Try in Sandbox">
                                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                            </button>
                                        </div>
                                    </div>

                                    {{-- Preview Area (Checkerboard Background for Transparency) --}}
                                    <div class="flex-1 p-6 bg-[#020617] flex items-center justify-center min-h-[140px] relative overflow-hidden group-hover:bg-[#050912] transition">
                                        {{-- Checkerboard Pattern --}}
                                        <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#4b5563 1px, transparent 1px); background-size: 10px 10px;"></div>
                                        
                                        {{-- Dynamic Visualization Logic --}}
                                        <div class="relative z-10 w-full flex justify-center items-center">
                                            @if($item['type'] == 'color')
                                                <div class="w-20 h-20 rounded-2xl {{ $item['class'] }} border-4 border-white/10 shadow-lg"></div>
                                            @elseif($item['type'] == 'text')
                                                <p class="{{ $item['class'] }} text-white truncate max-w-full">Aa</p>
                                            @elseif($item['type'] == 'text-align')
                                                <p class="{{ $item['class'] }} w-full text-white bg-white/5 p-2 rounded">Align Me</p>
                                            @elseif($item['type'] == 'layout')
                                                <div class="w-3/4 h-20 border-2 border-dashed border-white/30 rounded-lg flex items-center justify-center relative">
                                                    <div class="w-8 h-8 bg-indigo-500 rounded {{ $item['class'] }} absolute-center"></div>
                                                </div>
                                            @elseif($item['type'] == 'flex')
                                                <div class="flex gap-2 {{ $item['class'] }} w-full bg-white/5 p-2 rounded-lg h-20 border border-white/5">
                                                    <div class="w-8 h-8 bg-purple-500 rounded flex items-center justify-center text-xs">1</div>
                                                    <div class="w-8 h-8 bg-purple-500/70 rounded flex items-center justify-center text-xs">2</div>
                                                    <div class="w-8 h-8 bg-purple-500/40 rounded flex items-center justify-center text-xs">3</div>
                                                </div>
                                            @elseif($item['type'] == 'flex-v')
                                                <div class="flex gap-1 {{ $item['class'] }} w-full bg-white/5 p-2 rounded-lg h-24 border border-white/5">
                                                    <div class="w-full h-4 bg-purple-500 rounded"></div>
                                                    <div class="w-full h-4 bg-purple-500/70 rounded"></div>
                                                    <div class="w-full h-4 bg-purple-500/40 rounded"></div>
                                                </div>
                                            @elseif($item['type'] == 'grid')
                                                <div class="grid gap-2 {{ $item['class'] }} w-full bg-white/5 p-2 rounded-lg border border-white/5">
                                                    <div class="h-8 bg-cyan-500 rounded"></div>
                                                    <div class="h-8 bg-cyan-500/70 rounded"></div>
                                                    <div class="h-8 bg-cyan-500/40 rounded"></div>
                                                    <div class="h-8 bg-cyan-500/20 rounded"></div>
                                                </div>
                                            @elseif($item['type'] == 'grid-span')
                                                <div class="grid grid-cols-3 gap-2 w-full bg-white/5 p-2 rounded-lg border border-white/5">
                                                    <div class="h-8 bg-cyan-500 rounded {{ $item['class'] }}"></div>
                                                    <div class="h-8 bg-cyan-500/40 rounded"></div>
                                                    <div class="h-8 bg-cyan-500/40 rounded"></div>
                                                </div>
                                            @elseif($item['type'] == 'box' || $item['type'] == 'box-out')
                                                <div class="bg-emerald-500/20 border border-emerald-500/50 inline-block">
                                                    <div class="bg-emerald-500 text-[10px] text-white {{ $item['class'] }} font-bold">BOX</div>
                                                </div>
                                            @elseif($item['type'] == 'size')
                                                <div class="bg-green-500 h-8 rounded {{ $item['class'] }} max-w-full flex items-center justify-center text-xs shadow-lg">Size</div>
                                            @elseif($item['type'] == 'border')
                                                <div class="w-20 h-20 bg-transparent border-white {{ $item['class'] }} flex items-center justify-center"></div>
                                            @elseif($item['type'] == 'shadow')
                                                <div class="w-20 h-20 bg-[#1e293b] rounded-xl {{ $item['class'] }}"></div>
                                            @elseif($item['type'] == 'opacity')
                                                <div class="flex gap-2">
                                                    <div class="w-10 h-10 bg-indigo-500 rounded"></div>
                                                    <div class="w-10 h-10 bg-indigo-500 rounded {{ $item['class'] }}"></div>
                                                </div>
                                            @elseif($item['type'] == 'gradient')
                                                <div class="w-full h-16 rounded-xl bg-gradient-to-r from-gray-700 to-gray-600 {{ $item['class'] }}"></div>
                                            @elseif($item['type'] == 'anim')
                                                <div class="w-12 h-12 bg-pink-500 rounded-xl {{ $item['class'] }} shadow-lg"></div>
                                            @elseif($item['type'] == 'state')
                                                <button class="px-6 py-2 bg-white/10 border border-white/20 rounded-lg text-xs font-bold transition {{ $item['class'] }}">Interact Me</button>
                                            @elseif($item['type'] == 'filter')
                                                <div class="relative w-20 h-20 overflow-hidden rounded-xl border border-white/10">
                                                    <div class="absolute inset-0 bg-gradient-to-br from-orange-400 to-purple-600 {{ $item['class'] }}"></div>
                                                    <span class="absolute inset-0 flex items-center justify-center text-xs font-bold">IMG</span>
                                                </div>
                                            @elseif($item['type'] == 'input')
                                                <input type="text" class="w-full bg-black border border-white/20 rounded px-3 py-2 text-xs outline-none {{ $item['class'] }}" placeholder="Click to Focus">
                                            @else
                                                <span class="text-white/30 text-xs italic">Visual preview N/A</span>
                                            @endif
                                        </div>
                                    </div>

                                    {{-- Footer Info --}}
                                    <div class="px-5 py-3 bg-[#0b0f19] border-t border-white/5">
                                        <p class="text-[10px] font-mono text-white/40 flex items-center gap-2">
                                            <span class="w-1.5 h-1.5 rounded-full bg-white/20"></span>
                                            {{ $item['desc'] }}
                                        </p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endforeach

            </div>

            {{-- Footer Page --}}
            <div class="border-t border-white/5 pt-10 text-center pb-20">
                <p class="text-white/30 text-sm">&copy; {{ date('Y') }} TailwindLearn. <br><span class="text-white/20 text-xs">Designed for Education.</span></p>
            </div>

        </main>
    </div>

    {{-- TOAST NOTIFICATION (Visual Feedback) --}}
    <div id="toast" class="fixed bottom-10 left-1/2 -translate-x-1/2 bg-[#0f141e] border border-emerald-500/50 text-white px-6 py-3 rounded-full shadow-2xl shadow-emerald-900/50 transform translate-y-24 opacity-0 transition-all duration-300 z-50 flex items-center gap-3 backdrop-blur-xl">
        <div class="w-6 h-6 rounded-full bg-emerald-500 flex items-center justify-center text-[#020617]">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" /></svg>
        </div>
        <span class="font-bold text-sm tracking-wide">Class Copied to Clipboard!</span>
    </div>

</div>

{{-- JAVASCRIPT LOGIC --}}
<script>
    // 1. COPY FUNCTION WITH TOAST
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            const toast = document.getElementById('toast');
            toast.classList.remove('translate-y-24', 'opacity-0');
            setTimeout(() => {
                toast.classList.add('translate-y-24', 'opacity-0');
            }, 2000);
        });
    }

    // 2. SEND TO SANDBOX (Smart Link with Base64)
    function sendToSandbox(className) {
        // Template standar untuk membungkus class
        const template = `<div class="flex items-center justify-center h-screen bg-slate-900">
  <div class="${className} bg-indigo-500 text-white p-10 rounded-xl shadow-2xl transition-all hover:scale-110 cursor-pointer">
    Hello, World!
  </div>
</div>`;
        const encoded = btoa(encodeURIComponent(template));
        window.open("{{ route('sandbox') }}?import=" + encoded, '_blank');
    }

    // 3. SEARCH ENGINE
    const searchInput = document.getElementById('searchInput');
    const sections = document.querySelectorAll('.section-group');

    searchInput.addEventListener('input', (e) => {
        const term = e.target.value.toLowerCase();
        
        sections.forEach(section => {
            let visibleCards = 0;
            const cards = section.querySelectorAll('.cheat-card');
            
            cards.forEach(card => {
                const className = card.dataset.class.toLowerCase();
                const desc = card.querySelector('p').innerText.toLowerCase();
                
                if (className.includes(term) || desc.includes(term)) {
                    card.style.display = 'flex';
                    visibleCards++;
                } else {
                    card.style.display = 'none';
                }
            });

            section.style.display = visibleCards > 0 ? 'block' : 'none';
        });
    });

    // 4. KEYBOARD SHORTCUT (CTRL+K)
    document.addEventListener('keydown', (e) => {
        if ((e.ctrlKey || e.metaKey) && e.key === 'k') {
            e.preventDefault();
            searchInput.focus();
        }
    });

    // 5. SMART SCROLLSPY (Intersection Observer)
    document.addEventListener('DOMContentLoaded', () => {
        const observerOptions = {
            root: null,
            rootMargin: '-10% 0px -80% 0px', 
            threshold: 0
        };

        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    const id = entry.target.getAttribute('id');
                    document.querySelectorAll('.nav-item').forEach(link => {
                        link.classList.remove('bg-white/10', 'text-white', 'border-white/10');
                        link.classList.add('text-white/60', 'border-transparent');
                    });
                    const activeLink = document.querySelector(`.nav-item[href="#${id}"]`);
                    if (activeLink) {
                        activeLink.classList.remove('text-white/60', 'border-transparent');
                        activeLink.classList.add('bg-white/10', 'text-white', 'border-white/10');
                    }
                }
            });
        }, observerOptions);

        document.querySelectorAll('section').forEach(section => {
            observer.observe(section);
        });
    });
</script>

<style>
    .custom-scrollbar::-webkit-scrollbar { width: 4px; }
    .custom-scrollbar::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
    .custom-scrollbar::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }
</style>
