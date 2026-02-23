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
            return redirect()->route('dashboard')->with('error', 'Akses Ditolak! Anda harus memasukkan Token Kelas terlebih dahulu.');
        }

        // 3. Cek apakah kelasnya Valid dan Aktif
        $class = ClassGroup::where('name', $user->class_group)->where('is_active', true)->first();
        if (!$class) {
            return redirect()->route('dashboard')->with('error', 'Akses Terkunci! Kelas Anda saat ini sedang ditutup atau tidak ditemukan.');
        }

        return $next($request);
    }
}