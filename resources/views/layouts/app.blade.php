<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Flowwind Learn')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Tailwind CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Tailwind Config (Academic Theme) --}}
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#06b6d4',   // cyan-500
                        secondary: '#10b981', // emerald-500
                        surface: '#f8fafc',
                        ink: '#0f172a',
                        muted: '#64748b',
                    },
                    boxShadow: {
                        soft: '0 10px 30px rgba(15,23,42,.08)'
                    },
                    borderRadius: {
                        xl: '1rem',
                        '2xl': '1.25rem'
                    }
                }
            }
        }
    </script>
</head>

<body class="min-h-screen bg-surface text-ink antialiased">

{{-- ================= NAVBAR ================= --}}
<header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-6 py-4 flex items-center justify-between">

        {{-- Brand --}}
        <a href="{{ route('landing') }}" class="flex items-center gap-3">
            <div class="h-10 w-10 rounded-xl bg-gradient-to-br from-primary to-secondary flex items-center justify-center text-white font-bold shadow-soft">
                FW
            </div>
            <div class="leading-tight">
                <p class="font-bold text-slate-900">Flowwind Learn</p>
                <p class="text-xs text-muted">Media Pembelajaran Tailwind CSS</p>
            </div>
        </a>

        {{-- Navigation --}}
        <nav class="flex items-center gap-6 text-sm font-medium">

            {{-- Public --}}
            <a href="{{ route('landing') }}" class="hover:text-primary transition">
                Beranda
            </a>

            <a href="{{ route('landing') }}#fitur" class="hover:text-primary transition">
                Fitur
            </a>

            <a href="{{ route('landing') }}#alur" class="hover:text-primary transition">
                Alur Belajar
            </a>

            {{-- Authenticated --}}
            @auth
                @if (!Auth::user()->hasVerifiedEmail())
                    <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-800 text-xs">
                        Email belum diverifikasi
                    </span>

                    <a href="{{ route('verification.notice') }}"
                       class="px-4 py-2 rounded-xl bg-yellow-500 text-white hover:bg-yellow-600 transition">
                        Verifikasi Email
                    </a>
                @else
                    <a href="{{ route('dashboard') }}" class="hover:text-primary transition">
                        Dashboard
                    </a>

                    <a href="{{ route('courses.tailwind') }}" class="hover:text-primary transition">
                        Course
                    </a>

                    <a href="{{ route('dashboard') }}#progress" class="hover:text-primary transition">
                        Progress
                    </a>
                @endif

                {{-- User Menu --}}
                <div class="relative group">
                    <button class="flex items-center gap-2 px-4 py-2 rounded-xl bg-slate-100 hover:bg-slate-200 transition">
                        <span>{{ Auth::user()->name }}</span>
                        <svg class="w-4 h-4 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div class="absolute right-0 mt-2 w-44 bg-white rounded-xl shadow-soft border
                                opacity-0 invisible group-hover:opacity-100 group-hover:visible transition">
                        <a href="{{ route('dashboard') }}"
                           class="block px-4 py-2 text-sm hover:bg-slate-100">
                            Dashboard
                        </a>
                        <a href="{{ route('dashboard') }}#profil"
                           class="block px-4 py-2 text-sm hover:bg-slate-100">
                            Profil
                        </a>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button class="w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-red-50">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            @else
                {{-- Guest --}}
                <a href="{{ route('login') }}" class="hover:text-primary transition">
                    Login
                </a>
                <a href="{{ route('register') }}"
                   class="px-5 py-2 rounded-xl bg-primary text-white hover:bg-cyan-600 transition shadow-soft">
                    Daftar
                </a>
            @endauth
        </nav>
    </div>
</header>

{{-- ================= PAGE CONTENT ================= --}}
<main class="max-w-7xl mx-auto px-6 py-10">
    @yield('content')
</main>

{{-- ================= FOOTER ================= --}}
<footer class="border-t border-slate-200 bg-white">
    <div class="max-w-7xl mx-auto px-6 py-6 text-sm text-muted flex flex-col md:flex-row justify-between gap-4">
        <p>
            © {{ date('Y') }} <strong>Flowwind Learn</strong>. Media Pembelajaran Interaktif Tailwind CSS.
        </p>
        <p>
            Dikembangkan untuk pembelajaran web modern & monitoring progres mahasiswa.
        </p>
    </div>
</footer>

</body>
</html>
