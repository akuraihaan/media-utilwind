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

  <nav class="space-y-6 text-sm">
    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Ikhtisar</p>
      <a href="{{ route('admin.dashboard') }}" class="admin-link">Dasbor</a>
    </div>

    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Kuis & TP</p>
      <a href="{{ route('admin.analytics.questions') }}" class="admin-link">Manajemen Kuis</a>
      <div class="ml-3 space-y-1 border-l border-white/10 pl-3 text-xs">
        <a href="{{ route('admin.analytics.questions') }}" class="block text-white/55 hover:text-white">Bank Soal</a>
        <a href="{{ route('admin.learning-outcomes.index') }}" class="block text-white/55 hover:text-white">Pemetaan TP</a>
        <a href="{{ route('admin.questions.create') }}" class="block text-white/55 hover:text-white">Buat Soal</a>
      </div>
    </div>

    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Lab</p>
      <a href="{{ route('admin.labs.index') }}" class="admin-link">Konfigurasi Lab</a>
      <div class="ml-3 space-y-1 border-l border-white/10 pl-3 text-xs">
        <a href="{{ route('admin.labs.index') }}" class="block text-white/55 hover:text-white">Daftar Lab</a>
        <a href="{{ route('admin.lab.analytics') }}" class="block text-white/55 hover:text-white">Analitik Lab</a>
      </div>
    </div>

    <div class="space-y-2">
      <p class="text-[10px] font-black uppercase tracking-[0.2em] text-white/35">Siswa</p>
      <a href="{{ route('admin.students.index') }}" class="admin-link">Manajemen Siswa</a>
      <div class="ml-3 space-y-1 border-l border-white/10 pl-3 text-xs">
        <a href="{{ route('admin.students.index') }}" class="block text-white/55 hover:text-white">Direktori Siswa</a>
        <a href="{{ route('admin.classes.index') }}" class="block text-white/55 hover:text-white">Kelas & Token</a>
      </div>
    </div>
  </nav>
</aside>

<main class="flex-1 p-10 overflow-y-auto">
  @yield('content')
</main>

</div>
</body>
</html>
