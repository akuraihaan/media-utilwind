<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>@yield('title')</title>
 
</head>

<body class="min-h-screen bg-gradient-to-br from-[#14032a] via-[#24074b] to-[#04000c] text-white">

<div class="flex">

<aside class="w-72 min-h-screen bg-black/40 border-r border-white/10 p-6 space-y-8">
  <div class="flex items-center gap-3">
    <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-fuchsia-500 to-cyan-400
                flex items-center justify-center font-bold text-black">
      FW
    </div>
    <span class="font-semibold">Panel Admin</span>
  </div>

  <nav class="space-y-6 text-sm" aria-label="Navigasi admin utama">
    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Utama</p>
      <a href="{{ route('admin.dashboard') }}" class="admin-link">Dasbor</a>
      <a href="{{ route('admin.guide') }}" class="admin-link">Panduan</a>
    </div>

    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Analitik Belajar</p>
      <a href="{{ route('admin.analytics.questions') }}" class="admin-link">Kuis</a>
      <a href="{{ route('admin.lab.analytics') }}" class="admin-link">Praktik Lab</a>
    </div>

    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Materi</p>
      <a href="{{ route('admin.labs.index') }}" class="admin-link">Modul Praktik</a>
      <a href="{{ route('admin.questions.create') }}" class="admin-link">Buat Soal</a>
    </div>

    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Pengelolaan</p>
      <a href="{{ route('admin.students.index') }}" class="admin-link">Siswa</a>
      <a href="{{ route('admin.classes.index') }}" class="admin-link">Kelas</a>
    </div>
  </nav>
</aside>

<main id="admin-main-content" class="flex-1 p-10 overflow-y-auto">
  @yield('content')
</main>

</div>
</body>
</html>
