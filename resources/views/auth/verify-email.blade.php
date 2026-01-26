<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Verifikasi Email · Flowwind Learn</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="min-h-screen bg-gradient-to-br from-[#14032a] via-[#24074b] to-[#04000c]
             text-white flex items-center justify-center overflow-hidden">

<div id="glow"
     class="absolute w-[900px] h-[900px] bg-purple-600/30 rounded-full blur-[200px]">
</div>

<div class="relative z-10 w-full max-w-lg px-8">

  <div class="text-center mb-12">
    <div class="mx-auto mb-6 w-14 h-14 rounded-2xl bg-gradient-to-br from-fuchsia-500 to-cyan-400
                flex items-center justify-center text-black font-extrabold text-xl">
      TW
    </div>
    <h1 class="text-3xl font-extrabold">Verifikasi Email</h1>
    <p class="mt-3 text-white/70">
      Silakan verifikasi email Anda untuk melanjutkan pembelajaran
    </p>
  </div>

  @if (session('status') === 'verification-link-sent')
    <div class="mb-6 p-4 rounded-xl bg-emerald-500/20 border border-emerald-400 text-sm">
      Link verifikasi telah dikirim ke email Anda.
    </div>
  @endif

  <div class="bg-white/5 border border-white/10 rounded-3xl p-10 space-y-6 backdrop-blur-xl text-center">
    <p class="text-white/70">
      Kami telah mengirimkan email verifikasi ke alamat email Anda.
      Jika belum menerima, silakan kirim ulang.
    </p>

    <form method="POST" action="{{ route('verification.send') }}">
      @csrf
      <button
        class="w-full py-4 rounded-2xl bg-gradient-to-r from-fuchsia-500 to-purple-600
               font-semibold shadow-xl hover:scale-105 transition">
        Kirim Ulang Email Verifikasi
      </button>
    </form>

    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <button class="text-sm text-cyan-400 hover:underline">
        Logout
      </button>
    </form>
  </div>
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
