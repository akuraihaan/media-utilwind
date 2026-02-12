<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LabBab1RefactorSeeder extends Seeder
{
    public function run()
    {
        // 1. Setup Lab Master
        // Kita pakai ID spesifik biar gampang di-track relasinya nanti
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 01: Refactoring Legacy Code',
            'slug' => 'lab-01-refactoring-legacy',
            'description' => 'Misi Anda: Diberikan sebuah halaman profil karyawan yang kodenya "jorok" penuh CSS inline. Tugasmu membersihkannya dan mengubahnya menjadi desain modern menggunakan Tailwind CSS.',
            'duration_minutes' => 60, // 1 Jam karena task-nya banyak
            'total_points' => 100,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // 2. Daftar Task (Materi Bab 1 Lengkap)
        $steps = [
            // TASK 1: SETUP (Materi 1.6 - Instalasi)
            [
                'lab_id' => $labId,
                'title' => 'Task 1: Panggil Tailwind (CDN)',
                'instruction' => 'Browser belum kenal Tailwind. Di bagian <head>, kode-nya masih kosong. Masukkan script CDN Tailwind agar utility class bisa jalan. Gunakan: <script src="https://cdn.tailwindcss.com"></script>',
                'initial_code' => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Profil Karyawan</title>\n    \n</head>\n<body>\n    <h1>Data Karyawan</h1>\n</body>\n</html>",
                'validation_rules' => json_encode(['<script src="https://cdn.tailwindcss.com"></script>']),
                'points' => 10,
                'order_index' => 1,
            ],

            // TASK 2: CONTAINER & SPACING (Materi 1.3 - Struktur)
            [
                'lab_id' => $labId,
                'title' => 'Task 2: Bikin Container Rapi',
                'instruction' => 'Konten nempel banget ke pinggir layar. Ganti `style="width: 50%; margin: auto;"` itu dengan cara Tailwind. Gunakan `max-w-md` untuk lebar, `mx-auto` biar di tengah, dan kasih `p-6` (padding) biar lega. Jangan lupa set background `bg-slate-100` di body/div utama biar gak putih polos.',
                'initial_code' => '<div style="width: 50%; margin: 0 auto; background-color: #f0f0f0; padding: 20px;">\n  Isi Konten...\n</div>',
                'validation_rules' => json_encode(['max-w-md', 'mx-auto', 'p-6', 'bg-slate-100']),
                'points' => 15,
                'order_index' => 2,
            ],

            // TASK 3: CARD STYLING (Materi 1.1 - Konsep Dasar UI)
            [
                'lab_id' => $labId,
                'title' => 'Task 3: Membuat Kartu (Card)',
                'instruction' => 'Sekarang kita bungkus profilnya jadi kartu. Buat `div` pembungkus profil jadi warna putih (`bg-white`), sudutnya membulat (`rounded-xl`), dan kasih bayangan halus (`shadow-lg`). Hapus border hitam jelek itu.',
                'initial_code' => '<div style="border: 1px solid black; background: white;">\n  \n</div>',
                'validation_rules' => json_encode(['bg-white', 'rounded-xl', 'shadow-lg']),
                'points' => 15,
                'order_index' => 3,
            ],

            // TASK 4: TYPOGRAPHY (Materi 1.4 - Penerapan)
            [
                'lab_id' => $labId,
                'title' => 'Task 4: Hierarki Teks',
                'instruction' => 'Judul nama "Budi Santoso" kekecilan. Ubah jadi `text-2xl`, tebal (`font-bold`), dan warnanya abu gelap (`text-slate-800`). Untuk jabatannya "Web Developer", kasih warna accent `text-indigo-600` dan tebal medium (`font-medium`).',
                'initial_code' => '<div>\n  <h3>Budi Santoso</h3>\n  <p style="color: blue;">Web Developer</p>\n</div>',
                'validation_rules' => json_encode(['text-2xl', 'font-bold', 'text-slate-800', 'text-indigo-600', 'font-medium']),
                'points' => 15,
                'order_index' => 4,
            ],

            // TASK 5: GAMBAR PROFIL (Materi 1.3.1 - Utility Classes)
            [
                'lab_id' => $labId,
                'title' => 'Task 5: Styling Avatar',
                'instruction' => 'Gambarnya kotak kaku. Bikin jadi lingkaran sempurna pakai `rounded-full`. Atur ukurannya fix `w-24` dan `h-24`. Biar manis, kasih ring/border tebal di luarnya pakai `ring-4 ring-indigo-50`.',
                'initial_code' => '<img src="/avatar.jpg" style="width: 100px; height: 100px;">',
                'validation_rules' => json_encode(['rounded-full', 'w-24', 'h-24', 'ring-4', 'ring-indigo-50']),
                'points' => 15,
                'order_index' => 5,
            ],

            // TASK 6: INTERACTIVE BUTTON (Materi 1.4 - States/Hover)
            [
                'lab_id' => $labId,
                'title' => 'Task 6: Tombol Interaktif',
                'instruction' => 'Tombol "Hire Me" ini masih style bawaan browser. Ubah jadi tombol modern: `bg-indigo-600`, teks putih, `rounded-lg`. WAJIB: Tambahkan efek `hover:bg-indigo-700` supaya warnanya menggelap pas disorot mouse.',
                'initial_code' => '<button>Hire Me</button>',
                'validation_rules' => json_encode(['bg-indigo-600', 'text-white', 'rounded-lg', 'hover:bg-indigo-700']),
                'points' => 15,
                'order_index' => 6,
            ],

            // TASK 7: FINAL TOUCH (Materi 1.6 - Konfigurasi/Clean Code)
            [
                'lab_id' => $labId,
                'title' => 'Task 7: Layouting Akhir',
                'instruction' => 'Terakhir, konten di dalam kartu masih rata kiri semua. Gunakan `text-center` pada container kartu untuk meratakan teks dan gambar ke tengah. Dan kasih `space-y-4` biar ada jarak vertikal antar elemen otomatis.',
                'initial_code' => '<div class="bg-white rounded-xl shadow-lg p-6">\n  <img src="..." ...>\n  <h2>Budi</h2>\n  <p>Dev</p>\n</div>',
                'validation_rules' => json_encode(['text-center', 'space-y-4']),
                'points' => 15,
                'order_index' => 7,
            ],
        ];

        DB::table('lab_steps')->insert($steps);
    }
}