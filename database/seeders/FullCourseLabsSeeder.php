<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class FullCourseLabsSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();

        DB::table('lab_steps')->truncate();
        DB::table('labs')->truncate();

        Schema::enableForeignKeyConstraints();

        foreach ($this->labs() as $lab) {
            $this->createLab($lab);
        }
    }

    private function createLab(array $lab): void
    {
        if (empty($lab['steps'])) {
            throw new InvalidArgumentException("Lab {$lab['title']} tidak memiliki task.");
        }

        $now = now();
        $labId = DB::table('labs')->insertGetId([
            'title' => $lab['title'],
            'chapter_id' => $lab['chapter_id'],
            'slug' => $lab['slug'],
            'description' => $lab['description'],
            'duration_minutes' => $lab['duration_minutes'],
            'passing_grade' => $lab['passing_grade'],
            'is_active' => 1,
            'created_at' => $now,
            'updated_at' => $now,
        ]);

        $steps = [];

        foreach ($lab['steps'] as $index => $step) {
            if (empty($step['rules']) || ! is_array($step['rules'])) {
                throw new InvalidArgumentException("Task {$step['title']} belum memiliki validation rules.");
            }

            $steps[] = [
                'lab_id' => $labId,
                'title' => $step['title'],
                'instruction' => $step['instruction'],
                'initial_code' => $step['initial_code'],
                'validation_rules' => json_encode(array_values($step['rules'])),
                'points' => $step['points'],
                'order_index' => $index + 1,
                'created_at' => $now,
                'updated_at' => $now,
            ];
        }

        DB::table('lab_steps')->insert($steps);
    }

    private function labs(): array
    {
        return [
            [
                'title' => 'Lab 01: Struktur HTML dan Tailwind CDN',
                'chapter_id' => 1,
                'slug' => 'lab-01-struktur-html-tailwind-cdn',
                'description' => 'Praktik membangun halaman profil sederhana dengan struktur HTML semantik, script CDN Tailwind CSS, padding, warna, sudut melengkung, dan kartu dasar.',
                'duration_minutes' => 45,
                'passing_grade' => 70,
                'steps' => [
                    [
                        'title' => 'Pasang Tailwind CDN',
                        'instruction' => 'Tambahkan script CDN Tailwind CSS di dalam tag <head> agar utility class Tailwind dapat digunakan tanpa instalasi.',
                        'initial_code' => <<<'HTML'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil Siswa</title>
</head>
<body>
    <p>Tailwind siap digunakan.</p>
</body>
</html>
HTML,
                        'rules' => ['<script'],
                        'points' => 15,
                    ],
                    [
                        'title' => 'Susun Bagian Semantik',
                        'instruction' => 'Buat struktur halaman yang terdiri dari <header>, <nav>, <main>, dan <footer>. Letakkan semuanya di dalam <body>.',
                        'initial_code' => <<<'HTML'
<body>
    <div>Judul halaman</div>
    <div>Menu</div>
    <div>Konten utama</div>
    <div>Bagian bawah</div>
</body>
HTML,
                        'rules' => ['<header', '<nav', '<main', '<footer'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Buat Tombol Nyaman Dibaca',
                        'instruction' => 'Pada tombol Simpan, gunakan utility class untuk latar biru, teks putih, padding horizontal px-4, padding vertikal py-2, sudut rounded-lg, dan teks semibold.',
                        'initial_code' => <<<'HTML'
<button>
    Simpan
</button>
HTML,
                        'rules' => ['bg-blue-600', 'text-white', 'px-4', 'py-2', 'rounded-lg', 'font-semibold'],
                        'points' => 25,
                    ],
                    [
                        'title' => 'Bentuk Kartu Profil',
                        'instruction' => 'Ubah pembungkus profil menjadi kartu: latar putih, padding p-6, sudut rounded-xl, bayangan shadow-md, dan teks deskripsi berwarna slate-600.',
                        'initial_code' => <<<'HTML'
<section class="bg-slate-100 p-6">
    <article class="">
        <h2 class="text-2xl font-bold">Rani Putri</h2>
        <p>Siswa SMK jurusan RPL.</p>
    </article>
</section>
HTML,
                        'rules' => ['bg-white', 'p-6', 'rounded-xl', 'shadow-md', 'text-slate-600'],
                        'points' => 25,
                    ],
                    [
                        'title' => 'Pusatkan Konten Halaman',
                        'instruction' => 'Batasi lebar konten dengan max-w-md, posisikan di tengah memakai mx-auto, dan beri jarak atas mt-6 agar kartu tidak menempel ke tepi halaman.',
                        'initial_code' => <<<'HTML'
<main>
    <section class="bg-white p-6 rounded-xl shadow-md">
        <h1 class="text-2xl font-bold">Profil</h1>
    </section>
</main>
HTML,
                        'rules' => ['max-w-md', 'mx-auto', 'mt-6'],
                        'points' => 15,
                    ],
                ],
            ],
            [
                'title' => 'Lab 02: Flexbox, Grid, dan Responsive Layout',
                'chapter_id' => 2,
                'slug' => 'lab-02-flex-grid-responsive-layout',
                'description' => 'Praktik menyusun navbar, pencarian, daftar kartu, kartu utama, dan breakpoint responsive sesuai materi layout terbaru.',
                'duration_minutes' => 60,
                'passing_grade' => 75,
                'steps' => [
                    [
                        'title' => 'Navbar Dua Sisi',
                        'instruction' => 'Atur elemen <nav> agar judul berada di kiri, menu berada di kanan, dan keduanya sejajar vertikal menggunakan flex, items-center, dan justify-between.',
                        'initial_code' => <<<'HTML'
<nav class="p-4 bg-white border-b">
    <h1>Dashboard</h1>
    <ul>
        <li>Profil</li>
        <li>Keluar</li>
    </ul>
</nav>
HTML,
                        'rules' => ['flex', 'items-center', 'justify-between'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Input Search Fleksibel',
                        'instruction' => 'Pada bar pencarian, buat input mengisi ruang kosong di antara judul dan tombol dengan utility flex-1.',
                        'initial_code' => <<<'HTML'
<div class="flex items-center gap-3">
    <span class="font-bold">Produk</span>
    <input class="border rounded-lg px-3 py-2" placeholder="Cari produk">
    <button class="bg-blue-600 text-white px-4 py-2 rounded-lg">Cari</button>
</div>
HTML,
                        'rules' => ['flex-1'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Grid Tiga Kolom',
                        'instruction' => 'Susun daftar produk menjadi grid tiga kolom dengan jarak antar kartu menggunakan grid, grid-cols-3, dan gap-4.',
                        'initial_code' => <<<'HTML'
<section class="">
    <article class="bg-white p-4 rounded-lg">Produk 1</article>
    <article class="bg-white p-4 rounded-lg">Produk 2</article>
    <article class="bg-white p-4 rounded-lg">Produk 3</article>
</section>
HTML,
                        'rules' => ['grid', 'grid-cols-3', 'gap-4'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Kartu Utama Lebih Lebar',
                        'instruction' => 'Pada grid tiga kolom, buat kartu utama menempati dua kolom menggunakan col-span-2.',
                        'initial_code' => <<<'HTML'
<section class="grid grid-cols-3 gap-4">
    <article class="bg-white p-4 rounded-lg">Produk Utama</article>
    <article class="bg-white p-4 rounded-lg">Produk Lain</article>
</section>
HTML,
                        'rules' => ['col-span-2'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Breakpoint Mobile-First',
                        'instruction' => 'Buat daftar kartu responsif: satu kolom pada layar kecil, dua kolom pada md, tiga kolom pada lg, dan tetap memiliki gap-4.',
                        'initial_code' => <<<'HTML'
<section class="">
    <article class="bg-white p-4 rounded-lg">A</article>
    <article class="bg-white p-4 rounded-lg">B</article>
    <article class="bg-white p-4 rounded-lg">C</article>
</section>
HTML,
                        'rules' => ['grid', 'grid-cols-1', 'gap-4', 'md:grid-cols-2', 'lg:grid-cols-3'],
                        'points' => 20,
                    ],
                ],
            ],
            [
                'title' => 'Lab 03: Styling, Tipografi, dan Komponen',
                'chapter_id' => 3,
                'slug' => 'lab-03-styling-tipografi-komponen',
                'description' => 'Praktik memperjelas tampilan elemen menggunakan font, leading, border, warna status, kartu, dan tombol rounded sesuai materi styling.',
                'duration_minutes' => 60,
                'passing_grade' => 70,
                'steps' => [
                    [
                        'title' => 'Teks Kode dengan Font Mono',
                        'instruction' => 'Gunakan font-mono pada potongan kode agar tampil dengan huruf berjarak tetap.',
                        'initial_code' => <<<'HTML'
<code class="">
    npm run build
</code>
HTML,
                        'rules' => ['font-mono'],
                        'points' => 15,
                    ],
                    [
                        'title' => 'Paragraf Lebih Nyaman Dibaca',
                        'instruction' => 'Tambahkan leading-7 pada paragraf deskripsi agar jarak antarbaris lebih nyaman saat teks menjadi beberapa baris.',
                        'initial_code' => <<<'HTML'
<p class="text-slate-600">
    Sepatu ini ringan untuk kegiatan harian. Bahannya nyaman digunakan dan cocok dipakai ke sekolah.
</p>
HTML,
                        'rules' => ['leading-7'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Alert Berhasil',
                        'instruction' => 'Buat kotak informasi keberhasilan dengan latar hijau muda dan teks hijau gelap menggunakan bg-green-100 dan text-green-700.',
                        'initial_code' => <<<'HTML'
<div class="p-4 rounded-lg">
    Data berhasil disimpan.
</div>
HTML,
                        'rules' => ['bg-green-100', 'text-green-700'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Garis Tepi Elemen',
                        'instruction' => 'Tambahkan border dan border-slate-300 pada input agar batas elemen terlihat jelas.',
                        'initial_code' => <<<'HTML'
<input class="px-3 py-2 rounded-lg" placeholder="Nama lengkap">
HTML,
                        'rules' => ['border', 'border-slate-300'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Kartu Produk Lengkap',
                        'instruction' => 'Lengkapi kartu produk dengan bg-white, rounded-xl, shadow-md, judul text-2xl font-bold, deskripsi text-slate-600, dan tombol bg-blue-600 text-white rounded-lg.',
                        'initial_code' => <<<'HTML'
<article class="p-6">
    <h2>Sepatu Sekolah</h2>
    <p>Ringan dan nyaman digunakan.</p>
    <button>Beli</button>
</article>
HTML,
                        'rules' => ['bg-white', 'rounded-xl', 'shadow-md', 'text-2xl', 'font-bold', 'text-slate-600', 'bg-blue-600', 'text-white', 'rounded-lg'],
                        'points' => 25,
                    ],
                ],
            ],
            [
                'title' => 'Evaluasi Lab: Landing Page Katalog Produk',
                'chapter_id' => 99,
                'slug' => 'evaluasi-lab-landing-page-katalog-produk',
                'description' => 'Proyek praktik akhir yang merangkum HTML semantik, Tailwind CDN/CLI concept, warna tema, flex, grid responsif, tipografi, dan komponen kartu.',
                'duration_minutes' => 90,
                'passing_grade' => 75,
                'steps' => [
                    [
                        'title' => 'Fondasi HTML dan Tailwind',
                        'instruction' => 'Siapkan struktur HTML lengkap, tambahkan script Tailwind CDN pada <head>, lalu gunakan <header>, <main>, dan <footer> sebagai struktur utama halaman.',
                        'initial_code' => <<<'HTML'
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog Produk</title>
</head>
<body>
    <div>Katalog Produk Sekolah</div>
</body>
</html>
HTML,
                        'rules' => ['<script', '<header', '<main', '<footer'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Navbar Responsif Dasar',
                        'instruction' => 'Buat navbar dengan judul di kiri dan menu di kanan. Gunakan flex, items-center, justify-between, padding p-4, dan border-b.',
                        'initial_code' => <<<'HTML'
<header>
    <nav>
        <h1>Katalog Sekolah</h1>
        <div>Produk Bantuan Kontak</div>
    </nav>
</header>
HTML,
                        'rules' => ['flex', 'items-center', 'justify-between', 'p-4', 'border-b'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Warna Tema Khusus',
                        'instruction' => 'Gunakan class bg-utama-500 pada tombol utama sebagai penerapan warna tema khusus utama-500. Lengkapi tombol dengan text-white, px-4, py-2, dan rounded-lg.',
                        'initial_code' => <<<'HTML'
<button>
    Lihat Produk
</button>
HTML,
                        'rules' => ['bg-utama-500', 'text-white', 'px-4', 'py-2', 'rounded-lg'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Grid Katalog Responsive',
                        'instruction' => 'Susun kartu katalog dalam grid mobile-first: grid-cols-1 pada layar kecil, md:grid-cols-2 pada layar sedang, lg:grid-cols-3 pada layar besar, dan gap-4.',
                        'initial_code' => <<<'HTML'
<section>
    <article>Tas</article>
    <article>Sepatu</article>
    <article>Buku</article>
</section>
HTML,
                        'rules' => ['grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-4'],
                        'points' => 20,
                    ],
                    [
                        'title' => 'Poles Kartu dan Teks',
                        'instruction' => 'Setiap kartu perlu terlihat jelas: gunakan bg-white, rounded-xl, shadow-md, judul text-2xl font-bold, deskripsi text-slate-600 leading-7, dan beri area halaman bg-slate-100.',
                        'initial_code' => <<<'HTML'
<main>
    <article>
        <h2>Tas Sekolah</h2>
        <p>Tas kuat dan ringan untuk kegiatan harian.</p>
    </article>
</main>
HTML,
                        'rules' => ['bg-slate-100', 'bg-white', 'rounded-xl', 'shadow-md', 'text-2xl', 'font-bold', 'text-slate-600', 'leading-7'],
                        'points' => 20,
                    ],
                ],
            ],
        ];
    }
}
