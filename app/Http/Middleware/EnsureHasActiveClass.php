<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ClassGroup;

class EnsureHasActiveClass
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        // 1. Admin bebas akses kemana saja
        if ($user && $user->role === 'admin') {
            return $next($request);
        }

        // 2. Cek apakah user punya class_group
        if (!$user || empty($user->class_group)) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses Ditolak! Anda harus memasukkan Token Kelas terlebih dahulu.')
                ->with('learning_access_error', [
                    'title' => 'Materi Masih Terkunci',
                    'message' => 'Silabus dapat dilihat tanpa token, tetapi isi materi, lab, dan evaluasi hanya terbuka setelah akun Anda bergabung ke kelas aktif.',
                    'action' => 'Masukkan token kelas dari instruktur melalui tombol Gabung Kelas di dasbor.',
                ]);
        }

        // 3. Cek apakah kelasnya Valid dan Aktif
        $class = ClassGroup::where('name', $user->class_group)->where('is_active', true)->first();
        if (!$class) {
            return redirect()->route('dashboard')
                ->with('error', 'Akses Terkunci! Kelas Anda saat ini sedang ditutup atau tidak ditemukan.')
                ->with('learning_access_error', [
                    'title' => 'Kelas Belum Aktif',
                    'message' => 'Akun Anda sudah memiliki kelas, tetapi kelas tersebut sedang ditutup atau tidak ditemukan.',
                    'action' => 'Hubungi instruktur untuk mengaktifkan kelas atau mendapatkan token kelas baru.',
                ]);
        }

        return $next($request);
    }
}
