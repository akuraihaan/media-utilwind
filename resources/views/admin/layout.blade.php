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
    <span class="font-semibold">Admin Panel</span>
  </div>

  <nav class="space-y-3 text-sm">
    <a href="{{ route('admin.dashboard') }}" class="admin-link">Dashboard</a>
    <a href="{{ route('admin.courses.index') }}" class="admin-link">Courses</a>
    <a href="{{ route('admin.progress') }}" class="admin-link">Student Progress</a>
  </nav>
</aside>

<main class="flex-1 p-10 overflow-y-auto">
  @yield('content')
</main>

</div>
</body>
</html>
