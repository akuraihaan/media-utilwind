<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Reset Password · Flowwind Learn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#14032a] via-[#24074b] to-[#04000c]
             text-white flex items-center justify-center overflow-hidden">

<div id="glow"
     class="absolute w-[900px] h-[900px] bg-purple-600/30 rounded-full blur-[200px]">
</div>

<div class="relative z-10 w-full max-w-md px-8">

  <div class="text-center mb-12">
    <div class="mx-auto mb-6 w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-cyan-400
                flex items-center justify-center text-black font-extrabold text-xl">
      TW
    </div>
    <h1 class="text-3xl font-extrabold">Reset Password</h1>
    <p class="mt-3 text-white/70">
      Buat password baru untuk akun Anda
    </p>
  </div>

  <form method="POST" action="{{ route('password.update') }}"
        class="bg-white/5 border border-white/10 rounded-3xl p-10 space-y-6 backdrop-blur-xl">
    @csrf
     <input type="hidden" name="token" value="{{ $token }}">


    <div>
      <label class="text-sm text-white/70">Password Baru</label>
      <input name="password" type="password" required
             class="mt-2 w-full px-4 py-3 rounded-xl bg-black/30 border border-white/10">
    </div>

    <div>
      <label class="text-sm text-white/70">Konfirmasi Password</label>
      <input name="password_confirmation" type="password" required
             class="mt-2 w-full px-4 py-3 rounded-xl bg-black/30 border border-white/10">
    </div>

    <button
      class="w-full py-4 rounded-2xl bg-gradient-to-r from-fuchsia-500 to-purple-600
             font-semibold shadow-xl hover:scale-105 transition">
      Reset Password
    </button>
  </form>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script>
$(window).on('mousemove', e=>{
  const x=(e.clientX/window.innerWidth-.5)*40;
  const y=(e.clientY/window.innerHeight-.5)*40;
  $('#glow').css('transform',`translate(${x}px,${y}px)`);
});
</script>

</body>
</html>
