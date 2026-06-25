<!DOCTYPE html>
<html lang="id" class="h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Persiapan Evaluasi - Utilwind</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class'
        }
    </script>

    <script>
        (function () {
            const savedTheme = localStorage.getItem('color-theme');
            const useDarkTheme = savedTheme === 'dark'
                || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);

            document.documentElement.classList.toggle('dark', useDarkTheme);
            document.documentElement.style.colorScheme = useDarkTheme ? 'dark' : 'light';
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Fira+Code:wght@500;600&display=swap" rel="stylesheet">

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .font-mono {
            font-family: 'Fira Code', monospace;
        }

        .page-surface {
            background-image:
                radial-gradient(circle at 18% 16%, rgba(6, 182, 212, 0.10), transparent 28%),
                radial-gradient(circle at 84% 84%, rgba(79, 70, 229, 0.08), transparent 26%);
        }

        .dark .page-surface {
            background-image:
                radial-gradient(circle at 18% 16%, rgba(6, 182, 212, 0.11), transparent 28%),
                radial-gradient(circle at 84% 84%, rgba(79, 70, 229, 0.10), transparent 26%);
        }

        .start-button:not(:disabled):hover {
            transform: translateY(-1px);
        }

        .agreement input:checked + .agreement-box {
            border-color: #06b6d4;
            background: #06b6d4;
        }

        .agreement input:checked + .agreement-box svg {
            display: block;
        }

        .dark .agreement input:checked + .agreement-box {
            border-color: #22d3ee;
            background: #22d3ee;
        }
    </style>
</head>
<body class="min-h-screen bg-slate-100 text-slate-900 transition-colors duration-200 dark:bg-[#020617] dark:text-white">
    <main class="page-surface flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:py-12">
        <section class="w-full max-w-4xl overflow-hidden rounded-[1.35rem] border border-slate-200 bg-white shadow-[0_20px_55px_rgba(15,23,42,0.10)] transition-colors duration-200 dark:border-white/10 dark:bg-[#0f141e] dark:shadow-[0_24px_70px_rgba(2,6,23,0.35)]">
            <div class="h-1 w-full bg-cyan-500 dark:bg-cyan-400"></div>

            <div class="border-b border-slate-200 px-5 py-6 sm:px-8 lg:px-10 dark:border-white/10">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-start sm:justify-between">
                    <div class="flex min-w-0 items-start gap-4">
                      

                        <div class="min-w-0">
                            <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-700 dark:text-cyan-300">Persiapan Evaluasi</p>
                            <h1 class="mt-2 text-2xl font-extrabold tracking-tight text-slate-950 sm:text-3xl dark:text-white">
                                {{ $chapterId == 99 ? 'Evaluasi Akhir' : 'Evaluasi Bab ' . $chapterId }}
                            </h1>
                            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-600 dark:text-slate-400">
                                Bacalah ketentuan berikut sebelum memulai evaluasi. Pastikan Anda berada dalam kondisi siap dan dapat menyelesaikan seluruh soal.
                            </p>
                        </div>
                    </div>

                    
                </div>
            </div>

            <div class="px-5 py-6 sm:px-8 sm:py-8 lg:px-10">
                <div>
                    <h2 class="text-base font-extrabold text-slate-950 dark:text-white">Ketentuan Pengerjaan</h2>
                    <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Informasi berikut berlaku selama evaluasi berlangsung.</p>
                </div>

                <div class="mt-5 grid gap-3 sm:grid-cols-2">
                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition-colors hover:border-cyan-200 hover:bg-cyan-50/40 dark:border-white/10 dark:bg-[#020617] dark:hover:border-cyan-400/20 dark:hover:bg-cyan-400/[0.04]">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-cyan-200 bg-cyan-50 text-cyan-700 dark:border-cyan-400/20 dark:bg-cyan-400/10 dark:text-cyan-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Durasi 20 Menit</h3>
                                <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-400">Waktu dimulai setelah evaluasi dibuka dan dihitung oleh sistem.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition-colors hover:border-emerald-200 hover:bg-emerald-50/40 dark:border-white/10 dark:bg-[#020617] dark:hover:border-emerald-400/20 dark:hover:bg-emerald-400/[0.04]">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-emerald-200 bg-emerald-50 text-emerald-700 dark:border-emerald-400/20 dark:bg-emerald-400/10 dark:text-emerald-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Batas Ketuntasan 70</h3>
                                <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-400">Tinjau materi dan hasil evaluasi apabila nilai belum mencapai ketuntasan.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition-colors hover:border-rose-200 hover:bg-rose-50/40 dark:border-white/10 dark:bg-[#020617] dark:hover:border-rose-400/20 dark:hover:bg-rose-400/[0.04]">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-rose-200 bg-rose-50 text-rose-700 dark:border-rose-400/20 dark:bg-rose-400/10 dark:text-rose-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M8 11V7a4 4 0 018 0v4m-10 0h12a2 2 0 012 2v7a2 2 0 01-2 2H6a2 2 0 01-2-2v-7a2 2 0 012-2z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Tetap pada Halaman Evaluasi</h3>
                                <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-400">Jangan berpindah tab, memuat ulang halaman, atau kembali ke halaman sebelumnya.</p>
                            </div>
                        </div>
                    </article>

                    <article class="rounded-xl border border-slate-200 bg-slate-50 p-4 transition-colors hover:border-amber-200 hover:bg-amber-50/40 dark:border-white/10 dark:bg-[#020617] dark:hover:border-amber-400/20 dark:hover:bg-amber-400/[0.04]">
                        <div class="flex items-start gap-3">
                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-amber-200 bg-amber-50 text-amber-700 dark:border-amber-400/20 dark:bg-amber-400/10 dark:text-amber-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                    <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-sm font-bold text-slate-900 dark:text-slate-100">Penyimpanan Jawaban</h3>
                                <p class="mt-1 text-xs leading-5 text-slate-600 dark:text-slate-400">Jawaban disimpan otomatis setiap kali Anda memilih salah satu opsi.</p>
                            </div>
                        </div>
                    </article>
                </div>

                <div class="mt-5 flex items-start gap-3 rounded-xl border border-amber-200 bg-amber-50 px-4 py-3.5 dark:border-amber-400/20 dark:bg-amber-400/[0.07]">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-amber-700 dark:text-amber-300" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01M4.93 19h14.14c1.54 0 2.5-1.67 1.73-3L13.73 4c-.77-1.33-2.69-1.33-3.46 0L3.2 16c-.77 1.33.19 3 1.73 3z" />
                    </svg>
                    <p class="text-xs leading-5 text-amber-900 dark:text-amber-100/85">
                        Setelah evaluasi dimulai, waktu tetap berjalan. Pastikan seluruh soal telah dijawab sebelum menggunakan tombol <strong>Kumpulkan</strong> pada halaman evaluasi.
                    </p>
                </div>

                <form action="{{ route('quiz.startSession') }}" method="POST" class="mt-8">
                    @csrf
                    <input type="hidden" name="chapter_id" value="{{ $chapterId }}">

                    <div>
                        <h2 class="text-base font-extrabold text-slate-950 dark:text-white">Pernyataan Kesiapan</h2>
                        <p class="mt-1 text-sm text-slate-600 dark:text-slate-400">Centang pernyataan berikut untuk melanjutkan.</p>
                    </div>

                    <label class="agreement mt-4 flex cursor-pointer items-start gap-3 rounded-xl border border-slate-200 bg-slate-50 p-4 transition hover:border-cyan-200 hover:bg-cyan-50/40 dark:border-white/10 dark:bg-[#020617] dark:hover:border-cyan-400/20 dark:hover:bg-cyan-400/[0.04]">
                        <input type="checkbox" id="agreement" class="sr-only">
                        <span class="agreement-box mt-0.5 flex h-5 w-5 shrink-0 items-center justify-center rounded-md border-2 border-slate-400 bg-white text-slate-950 transition dark:border-slate-500 dark:bg-[#0f141e]">
                            <svg class="hidden h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-width="3" stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7" />
                            </svg>
                        </span>
                        <span class="text-sm leading-6 text-slate-700 dark:text-slate-300">
                            Saya menyatakan siap mengerjakan evaluasi secara jujur, tidak melakukan kecurangan, serta memahami seluruh ketentuan yang berlaku.
                        </span>
                    </label>

                    <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                        <a href="{{ route('dashboard') }}" class="inline-flex min-h-11 items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-bold text-slate-700 transition hover:bg-slate-50 hover:text-slate-950 dark:border-white/10 dark:bg-[#020617] dark:text-slate-300 dark:hover:bg-white/10 dark:hover:text-white">
                            Kembali ke Dashboard
                        </a>

                        <button type="submit" id="startBtn" disabled class="start-button inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-slate-950 px-6 py-3 text-sm font-extrabold text-white shadow-sm transition disabled:cursor-not-allowed disabled:opacity-50 enabled:hover:bg-slate-800 dark:bg-white dark:text-slate-950 dark:enabled:hover:bg-slate-200">
                            <span>Mulai Mengerjakan</span>
                            <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-width="2" stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                            </svg>
                        </button>
                    </div>
                </form>
            </div>
        </section>
    </main>

    <script>
        const checkbox = document.getElementById('agreement');
        const startButton = document.getElementById('startBtn');

        checkbox.addEventListener('change', function () {
            startButton.disabled = !this.checked;
        });

        function syncThemeFromStorage() {
            const savedTheme = localStorage.getItem('color-theme');
            const useDarkTheme = savedTheme === 'dark'
                || (!savedTheme && window.matchMedia('(prefers-color-scheme: dark)').matches);

            document.documentElement.classList.toggle('dark', useDarkTheme);
            document.documentElement.style.colorScheme = useDarkTheme ? 'dark' : 'light';
        }

        window.addEventListener('storage', function (event) {
            if (event.key === 'color-theme') {
                syncThemeFromStorage();
            }
        });

        window.addEventListener('focus', syncThemeFromStorage);
    </script>
</body>
</html>
