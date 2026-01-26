<nav id="navbar" class="h-[74px] w-full bg-[#020617]/80 backdrop-blur-xl border-b border-white/5 shrink-0 z-50 flex items-center justify-between px-6 lg:px-8 transition-all duration-500 relative">
        <div class="flex items-center gap-3">
            <div class="w-11 h-11 rounded-xl bg-gradient-to-br from-fuchsia-500 to-cyan-400 flex items-center justify-center font-extrabold text-black shadow-xl">
                TW
            </div>
            <span class="font-semibold tracking-wide text-lg">TailwindLearn</span>
        </div>

        <div class="hidden md:flex gap-10 text-sm font-medium">
            <a href="{{ route('landing') }}" class="nav-link opacity-70 hover:opacity-100 transition">Beranda</a>
            <a href="{{ route('courses.htmldancss') }}" class="nav-link opacity-70 hover:opacity-100 transition">Course</a>
            <span class="nav-link active cursor-default">Dashboard</span> 
            <a href="{{ route('sandbox') }}" class="nav-link opacity-70 hover:opacity-100 transition">Sandbox</a>
        </div>

        <div class="flex gap-3 items-center">
            <span class="text-white/70 text-sm hidden sm:block font-bold">{{ Auth::user()->name }}</span>
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="cta-main px-6 py-2 rounded-xl bg-gradient-to-r from-fuchsia-500 to-purple-600 text-sm font-semibold shadow-xl hover:scale-105 transition border border-white/10">
                    Keluar
                </button>
            </form>
        </div>
    </nav>