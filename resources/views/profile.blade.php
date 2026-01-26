<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil • Interactive Tailwind Course</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.tailwindcss.com"></script>
<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          brand: {
            50:  '#EEF2FF',
            100: '#E0EAFF',
            200: '#C7D2FE',
            500: '#6366F1',
            600: '#4F46E5',
            700: '#4338CA',
            900: '#1E1B4B',
          },
          accent: {
            50:  '#ECFEFF',
            100: '#CFFAFE',
            500: '#06B6D4',
            600: '#0891B2',
          },
          surface: {
            50:  '#F8FAFC',
            100: '#F1F5F9',
            900: '#020617',
          },
          success: {
            500: '#22C55E',
          },
          warning: {
            500: '#FACC15',
          }
        },
        boxShadow: {
          'soft': '0 18px 45px rgba(15,23,42,0.14)',
        },
        borderRadius: {
          'xl': '1rem',
          '2xl': '1.25rem',
        }
      }
    }
  };
</script>

</head>
<body class="bg-slate-50 min-h-screen">
<div class="max-w-3xl mx-auto px-4 py-6 space-y-4">

    <header class="flex items-center justify-between">
        <div>
            <h1 class="text-xl font-semibold text-slate-900">Profil pengguna</h1>
            <p class="text-xs text-slate-500">Atur data dasar yang dipakai untuk identitas dan pelaporan.</p>
        </div>
        <a href="{{ route('dashboard') }}" class="text-xs text-slate-500 hover:text-slate-800">
            &larr; Kembali ke dashboard
        </a>
    </header>

    <section class="bg-white border border-slate-200 rounded-2xl p-4 shadow-sm">
        @if(session('status'))
            <div class="mb-3 text-xs text-emerald-700 bg-emerald-50 border border-emerald-200 rounded-lg px-3 py-2">
                {{ session('status') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="mb-3 text-xs text-red-700 bg-red-50 border border-red-200 rounded-lg px-3 py-2">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $e)
                        <li>{{ $e }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Nama lengkap</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}" required
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Email (tidak bisa diubah di sini)</label>
                    <input type="email" value="{{ $user->email }}" disabled
                           class="w-full rounded-lg border-slate-200 text-sm bg-slate-50 text-slate-500">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Institusi / Kampus</label>
                    <input type="text" name="institution" value="{{ old('institution', $user->institution) }}"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Program studi</label>
                    <input type="text" name="study_program" value="{{ old('study_program', $user->study_program) }}"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="grid sm:grid-cols-2 gap-3">
                <div>
                    <label class="block text-xs font-semibold text-slate-600 mb-1">No. HP</label>
                    <input type="text" name="phone" value="{{ old('phone', $user->phone) }}"
                           class="w-full rounded-lg border-slate-300 text-sm focus:ring-blue-500 focus:border-blue-500">
                </div>
            </div>

            <div class="flex justify-end gap-2 pt-2">
                <button type="submit"
                        class="px-4 py-2 rounded-lg bg-blue-600 text-white text-sm font-medium hover:bg-blue-700">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </section>
</div>
</body>
</html>
