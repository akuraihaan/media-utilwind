@extends('layouts.landing')

@section('title', 'Profil Saya')

@section('content')
{{-- 
    PERBAIKAN:
    - pt-32: Padding Top 128px. 
      (Navbar 80px + 48px Space agar tidak mepet).
--}}
<div class="min-h-screen bg-[#020617] text-white font-sans selection:bg-fuchsia-500/30 pb-20 pt-32">

    {{-- Background Decoration (Tetap) --}}
    <div class="fixed inset-0 -z-50 pointer-events-none overflow-hidden">
        <div class="absolute top-[-10%] left-[-10%] w-[500px] h-[500px] bg-fuchsia-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute bottom-[-10%] right-[-10%] w-[500px] h-[500px] bg-blue-600/10 rounded-full blur-[120px]"></div>
        <div class="absolute inset-0 bg-[url('https://grainy-gradients.vercel.app/noise.svg')] opacity-[0.03]"></div>
    </div>

    @include('layouts.partials.navbar')

    <main class="max-w-7xl mx-auto px-6 lg:px-8">
        
        {{-- Header Section --}}
        <div class="flex flex-col md:flex-row justify-between items-end gap-6 mb-12">
            <div>
                <h1 class="text-3xl md:text-4xl font-black text-white tracking-tight mb-2">
                    Profil <span class="text-transparent bg-clip-text bg-gradient-to-r from-fuchsia-400 to-cyan-400">Pengguna</span>
                </h1>
                <p class="text-white/50">Kelola informasi pribadi, statistik belajar, dan keamanan akun.</p>
            </div>
            
            {{-- Breadcrumb / Back --}}
            
        </div>

        <div class="grid lg:grid-cols-12 gap-8 items-start">

            {{-- LEFT COLUMN: CARD PROFILE & STATS (4 Columns) --}}
            <div class="lg:col-span-4 space-y-6">
                
                {{-- 1. Identity Card --}}
                <div class="relative bg-[#0f141e] border border-white/10 rounded-3xl p-8 text-center overflow-hidden shadow-2xl">
                    {{-- Avatar Wrapper --}}
                    <div class="relative w-32 h-32 mx-auto mb-6 group">
                        <div class="absolute inset-0 bg-gradient-to-tr from-fuchsia-500 to-cyan-500 rounded-full blur opacity-40 group-hover:opacity-60 transition duration-500"></div>
                        
                        @if(Auth::user()->avatar)
                            <img src="{{ asset('storage/' . Auth::user()->avatar) }}" 
                                 class="relative w-full h-full object-cover rounded-full border-4 border-[#0f141e] shadow-xl" 
                                 alt="Profile Photo">
                        @else
                            <div class="relative w-full h-full flex items-center justify-center bg-gradient-to-br from-slate-700 to-slate-900 rounded-full border-4 border-[#0f141e] text-3xl font-bold text-white shadow-xl">
                                {{ substr(Auth::user()->name, 0, 2) }}
                            </div>
                        @endif

                        {{-- Role Badge --}}
                        <div class="absolute bottom-0 right-0 translate-x-2 translate-y-1">
                            <span class="flex items-center gap-1 px-3 py-1 bg-[#020617] border border-white/10 rounded-full text-[10px] font-bold uppercase tracking-wider text-cyan-400 shadow-lg">
                                <span class="w-1.5 h-1.5 rounded-full bg-cyan-400 animate-pulse"></span>
                                {{ Auth::user()->role ?? 'Student' }}
                            </span>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold text-white">{{ Auth::user()->name }}</h2>
                    <p class="text-sm text-white/40 font-mono mt-1">{{ Auth::user()->email }}</p>

                    <div class="mt-6 pt-6 border-t border-white/5 flex justify-between text-left">
                        <div>
                            <p class="text-[10px] uppercase tracking-widest text-white/30 font-bold">Institusi</p>
                            <p class="text-sm font-medium text-white/90 mt-1 truncate max-w-[120px]">{{ Auth::user()->institution ?? '-' }}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-[10px] uppercase tracking-widest text-white/30 font-bold">Program Studi</p>
                            <p class="text-sm font-medium text-white/90 mt-1 truncate max-w-[120px]">{{ Auth::user()->study_program ?? '-' }}</p>
                        </div>
                    </div>
                </div>

                {{-- 2. Insight Stats --}}
                <div class="bg-[#0f141e] border border-white/10 rounded-3xl p-6 shadow-xl">
                    <h3 class="text-xs font-bold text-white/50 uppercase tracking-widest mb-6">Learning Insights</h3>
                    
                    <div class="grid grid-cols-2 gap-4">
                        {{-- XP Card --}}
                        <div class="col-span-2 p-4 rounded-2xl bg-gradient-to-br from-yellow-500/10 to-transparent border border-yellow-500/20 flex items-center justify-between">
                            <div>
                                <p class="text-xs text-yellow-500/60 font-bold uppercase">Total XP</p>
                                <p class="text-2xl font-black text-yellow-400">{{ number_format($stats['xp'] ?? 0) }}</p>
                            </div>
                            <div class="text-3xl">⚡</div>
                        </div>

                        {{-- Avg Score --}}
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center">
                            <p class="text-[10px] text-white/40 uppercase font-bold mb-1">Rata-rata</p>
                            <p class="text-xl font-black text-fuchsia-400">{{ $stats['avg_score'] ?? 0 }}</p>
                        </div>

                        {{-- Completed Labs --}}
                        <div class="p-4 rounded-2xl bg-white/5 border border-white/5 text-center">
                            <p class="text-[10px] text-white/40 uppercase font-bold mb-1">Lab Selesai</p>
                            <p class="text-xl font-black text-cyan-400">{{ $stats['labs_done'] ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- RIGHT COLUMN: FORMS (8 Columns) --}}
            <div class="lg:col-span-8 space-y-8">

                {{-- Flash Message --}}
                @if(session('success'))
                    <div x-data="{show: true}" x-show="show" class="p-4 rounded-2xl bg-emerald-500/10 border border-emerald-500/20 text-emerald-400 text-sm font-bold flex items-center justify-between animate-fade-in-down">
                        <div class="flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" /></svg>
                            {{ session('success') }}
                        </div>
                        <button @click="show = false" class="hover:text-emerald-300">✕</button>
                    </div>
                @endif

                {{-- FORM 1: UPDATE PROFILE --}}
                <div class="bg-[#0f141e] border border-white/10 rounded-3xl p-8 shadow-xl">
                    <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                        <div class="w-10 h-10 rounded-xl bg-blue-500/20 text-blue-400 flex items-center justify-center font-bold text-lg">1</div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Edit Informasi</h3>
                            <p class="text-xs text-white/40">Perbarui foto dan data diri Anda.</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        @method('PATCH')

                        {{-- Upload UI --}}
                        <div class="flex items-center gap-6 p-4 rounded-2xl bg-white/[0.02] border border-white/5 border-dashed">
                            <div class="shrink-0">
                                <img id="preview-img" 
                                     src="{{ Auth::user()->avatar ? asset('storage/' . Auth::user()->avatar) : 'https://ui-avatars.com/api/?name='.Auth::user()->name.'&background=random' }}" 
                                     class="h-16 w-16 object-cover rounded-full border border-white/10" />
                            </div>
                            <div class="flex-1">
                                <label class="block text-sm font-medium text-white/70 mb-1">Foto Profil</label>
                                <input type="file" name="avatar" id="avatar-input" onchange="previewFile(event)"
                                    class="block w-full text-xs text-slate-500
                                    file:mr-4 file:py-2 file:px-4
                                    file:rounded-full file:border-0
                                    file:text-xs file:font-semibold
                                    file:bg-fuchsia-600 file:text-white
                                    hover:file:bg-fuchsia-500 cursor-pointer transition
                                "/>
                                <p class="text-[10px] text-white/30 mt-2">JPG, PNG, GIF (Max. 2MB)</p>
                            </div>
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            {{-- Nama --}}
                            <div class="group">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2 group-focus-within:text-fuchsia-400 transition">Nama Lengkap</label>
                                <input type="text" name="name" value="{{ old('name', Auth::user()->name) }}" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:border-fuchsia-500 focus:ring-1 focus:ring-fuchsia-500 outline-none transition">
                                @error('name') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Email --}}
                            <div class="group">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2 group-focus-within:text-fuchsia-400 transition">Alamat Email</label>
                                <input type="email" name="email" value="{{ old('email', Auth::user()->email) }}" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:border-fuchsia-500 focus:ring-1 focus:ring-fuchsia-500 outline-none transition">
                                @error('email') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            {{-- Phone --}}
                            <div class="group">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2 group-focus-within:text-blue-400 transition">Nomor WhatsApp</label>
                                <input type="text" name="phone" value="{{ old('phone', Auth::user()->phone) }}" placeholder="08..." class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                            </div>

                            {{-- Institution --}}
                            <div class="group">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2 group-focus-within:text-blue-400 transition">Asal Institusi</label>
                                <input type="text" name="institution" value="{{ old('institution', Auth::user()->institution) }}" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                            </div>

                            {{-- Study Program (Full Width) --}}
                            <div class="group md:col-span-2">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2 group-focus-within:text-blue-400 transition">Program Studi / Jurusan</label>
                                <input type="text" name="study_program" value="{{ old('study_program', Auth::user()->study_program) }}" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white placeholder-white/20 focus:border-blue-500 focus:ring-1 focus:ring-blue-500 outline-none transition">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3 rounded-xl bg-gradient-to-r from-blue-600 to-indigo-600 text-white font-bold shadow-lg shadow-blue-900/20 hover:scale-105 hover:shadow-blue-600/30 transition transform">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                {{-- FORM 2: SECURITY --}}
                <div class="bg-[#0f141e] border border-white/10 rounded-3xl p-8 shadow-xl">
                    <div class="flex items-center gap-4 mb-8 border-b border-white/5 pb-6">
                        <div class="w-10 h-10 rounded-xl bg-red-500/20 text-red-400 flex items-center justify-center font-bold text-lg">2</div>
                        <div>
                            <h3 class="text-lg font-bold text-white">Keamanan Akun</h3>
                            <p class="text-xs text-white/40">Ubah password untuk menjaga akun tetap aman.</p>
                        </div>
                    </div>

                    <form action="{{ route('profile.password') }}" method="POST" class="space-y-6">
                        @csrf
                        @method('PUT')

                        <div class="group">
                            <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2">Password Saat Ini</label>
                            <input type="password" name="current_password" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition">
                            @error('current_password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                        </div>

                        <div class="grid md:grid-cols-2 gap-6">
                            <div class="group">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2">Password Baru</label>
                                <input type="password" name="password" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition">
                                @error('password') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                            </div>

                            <div class="group">
                                <label class="block text-[10px] font-bold text-white/40 uppercase tracking-wider mb-2">Konfirmasi Password</label>
                                <input type="password" name="password_confirmation" class="w-full bg-[#020617] border border-white/10 rounded-xl px-4 py-3 text-white focus:border-red-500 focus:ring-1 focus:ring-red-500 outline-none transition">
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" class="px-8 py-3 rounded-xl bg-white/5 border border-white/10 text-white font-bold hover:bg-red-500/10 hover:text-red-400 hover:border-red-500/30 transition">
                                Update Password
                            </button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </main>
</div>

{{-- Javascript untuk Preview Gambar --}}
<script>
    function previewFile(event) {
        const input = event.target;
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = function (e) {
                document.getElementById('preview-img').src = e.target.result;
            }
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>

{{-- AlpineJS (Optional for dismissible alert) --}}
<script src="//unpkg.com/alpinejs" defer></script>
@endsection