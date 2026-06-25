<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Panel') · TailwindLearn</title>
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .custom-scrollbar::-webkit-scrollbar { width: 5px; }
        .custom-scrollbar::-webkit-scrollbar-track { background: #0f172a; }
        .custom-scrollbar::-webkit-scrollbar-thumb { background: #3730a3; border-radius: 10px; }
    </style>
</head>
<body class="bg-[#0f172a] text-white overflow-hidden h-screen flex">

    <aside class="w-64 bg-[#1e293b] border-r border-white/5 flex flex-col shrink-0 transition-all duration-300">
        
        <div class="h-20 flex items-center px-6 border-b border-white/5">
            <div class="flex items-center gap-3 text-indigo-400">
                <svg class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z" /></svg>
                <span class="font-bold text-lg text-white">GuruPanel</span>
            </div>
        </div>

        <nav class="flex-1 overflow-y-auto py-6 px-3 space-y-6">
            <div class="space-y-1">
                <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Ikhtisar</p>
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('admin.dashboard') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z"/></svg>
                <span>Dashboard</span>
                </a>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Kuis & TP</p>
                <a href="{{ route('admin.analytics.questions') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('admin.analytics.questions') || Route::is('admin.learning-outcomes.*') || Route::is('admin.questions.*') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>
                    <span>Manajemen Kuis</span>
                </a>
                <div class="ml-8 border-l border-white/10 pl-3 space-y-1">
                    <a href="{{ route('admin.analytics.questions') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.analytics.questions') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Bank Soal</a>
                    <a href="{{ route('admin.learning-outcomes.index') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.learning-outcomes.*') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Pemetaan TP</a>
                    <a href="{{ route('admin.questions.create') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.questions.create') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Buat Soal</a>
                </div>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Lab</p>
                <a href="{{ route('admin.labs.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('admin.labs.*') || Route::is('admin.lab.analytics') || Route::is('admin.student.analytics') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.384-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/></svg>
                    <span>Lab</span>
                </a>
                <div class="ml-8 border-l border-white/10 pl-3 space-y-1">
                    <a href="{{ route('admin.labs.index') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.labs.index') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Konfigurasi Lab</a>
                    <a href="{{ route('admin.lab.analytics') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.lab.analytics') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Analitik Lab</a>
                </div>
            </div>

            <div class="space-y-1">
                <p class="px-3 text-xs font-bold text-gray-500 uppercase tracking-widest mb-2">Siswa</p>
                <a href="{{ route('admin.students.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-lg {{ Route::is('admin.students.*') || Route::is('admin.classes.*') || Route::is('admin.student.detail') ? 'bg-indigo-600 text-white shadow-lg shadow-indigo-500/30' : 'text-gray-400 hover:bg-white/5 hover:text-white' }} transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg>
                    <span>Manajemen Siswa</span>
                </a>
                <div class="ml-8 border-l border-white/10 pl-3 space-y-1">
                    <a href="{{ route('admin.students.index') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.students.*') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Direktori Siswa</a>
                    <a href="{{ route('admin.classes.index') }}" class="block rounded-lg px-3 py-2 text-xs font-bold {{ Route::is('admin.classes.*') ? 'bg-white/10 text-white' : 'text-gray-500 hover:bg-white/5 hover:text-white' }}">Kelas & Token</a>
                </div>
            </div>
        </nav>

        <div class="p-4 border-t border-white/5">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button class="flex items-center gap-3 w-full px-4 py-3 rounded-lg hover:bg-red-500/10 hover:text-red-400 text-gray-400 transition-all">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/></svg>
                    <span>Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="flex-1 h-full overflow-y-auto custom-scrollbar">
        @yield('content')
    </main>

</body>
</html>
