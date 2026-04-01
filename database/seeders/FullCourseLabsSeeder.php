<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class FullCourseLabsSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Pembersihan data lama agar seeder selalu fresh saat dijalankan ulang
        DB::table('lab_steps')->truncate();
        DB::table('labs')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->seedLabBab1();
        $this->seedLabBab2();
        $this->seedLabBab3();
        $this->seedLabFinal();
    }

    /**
     * LAB BAB 1: FUNDAMENTALS & SPACING
     * Fokus: Konversi CSS Tradisional ke Utility-First, Box Model, Typography.
     */
    private function seedLabBab1()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 01: Modernisasi Profil Komponen',
            'slug' => 'lab-01-refactoring-legacy',
            'description' => 'Requirement: Bangun ulang komponen Profile Card lawas. Terapkan sistem Grid 4-Point Tailwind untuk margin/padding dan gunakan palet warna bawaan.',
            'duration_minutes' => 45,
            'passing_grade' => 70,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 10,
                'title' => 'Integrasi Lingkungan Tailwind',
                'instruction' => 'Engine Tailwind belum dimuat. Sisipkan skrip CDN Tailwind CSS secara langsung ke dalam tag <head> dokumen HTML Anda untuk memulai.',
                'initial_code' => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Profile Component</title>\n    \n</head>\n<body>\n    <div>Setup Berhasil</div>\n</body>\n</html>",
                'validation_rules' => json_encode(['<script src="https://cdn.tailwindcss.com"></script>']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 15,
                'title' => 'Konfigurasi Kontainer Utama',
                'instruction' => 'Atur div pembungkus utama. Batasi lebar maksimalnya ke ukuran menengah (md), posisikan tepat di tengah layar secara horizontal, berikan ruang padding internal sebesar 24px (skala 6), dan atur warna latar menjadi slate-100.',
                'initial_code' => "<body>\n    <div>\n        \n    </div>\n</body>",
                'validation_rules' => json_encode(['max-w-md', 'mx-auto', 'p-6', 'bg-slate-100']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 15,
                'title' => 'Elevasi dan Bentuk Kartu',
                'instruction' => 'Ubah div child menjadi bentuk kartu. Berikan latar belakang putih solid, bulatkan sudut elemen secara ekstra besar (xl), dan tambahkan efek bayangan berukuran besar (lg) agar tampak menonjol dari latar belakang.',
                'initial_code' => "<div class=\"max-w-md mx-auto p-6 bg-slate-100\">\n    <div class=\"\">\n        \n    </div>\n</div>",
                'validation_rules' => json_encode(['bg-white', 'rounded-xl', 'shadow-lg']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 20,
                'title' => 'Hierarki Tipografi',
                'instruction' => 'Format judul nama (H2) dengan ukuran 2xl, ketebalan bold, dan warna slate-800. Untuk teks profesi (P), gunakan warna indigo-600 dengan ketebalan font medium.',
                'initial_code' => "<div class=\"bg-white rounded-xl shadow-lg p-6\">\n    <h2>Budi Santoso</h2>\n    <p>Fullstack Developer</p>\n</div>",
                'validation_rules' => json_encode(['text-2xl', 'font-bold', 'text-slate-800', 'text-indigo-600', 'font-medium']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 5, 'points' => 20,
                'title' => 'Penyesuaian Media Avatar',
                'instruction' => 'Format tag gambar agar memiliki lebar dan tinggi absolut 96px (skala 24). Buat agar bentuknya melingkar sempurna, dan tambahkan cincin border (ring) setebal 4px berwarna indigo-50.',
                'initial_code' => "<img src=\"/api/placeholder/150/150\" alt=\"Profile\">\n<h2>Budi Santoso</h2>",
                'validation_rules' => json_encode(['w-24', 'h-24', 'rounded-full', 'ring-4', 'ring-indigo-50']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 6, 'points' => 20,
                'title' => 'Interaksi Tombol Aksi',
                'instruction' => 'Buat elemen <button> dengan latar indigo-600, teks putih putih, sudut membulat standar (lg), dan transisi hover yang mengubah warna latar menjadi indigo-700.',
                'initial_code' => "<div>\n    <button>Hubungi Saya</button>\n</div>",
                'validation_rules' => json_encode(['bg-indigo-600', 'text-white', 'rounded-lg', 'hover:bg-indigo-700']),
            ],
        ];

        DB::table('lab_steps')->insert($steps);
    }

    /**
     * LAB BAB 2: MODERN LAYOUTING
     * Fokus: Flexbox, CSS Grid, Responsive Breakpoints (Mobile-First).
     */
    private function seedLabBab2()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 02: Arsitektur Admin Dashboard',
            'slug' => 'lab-02-layouting-mastery',
            'description' => 'Requirement: Bangun struktur layout Dashboard responsif. Implementasikan Flexbox untuk navigasi makro dan CSS Grid untuk area statistik data.',
            'duration_minutes' => 60,
            'passing_grade' => 75,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 25,
                'title' => 'Makro Layout (Flex Container)',
                'instruction' => 'Buat layout pembagian layar utama. Jadikan kontainer div terluar sebagai flexbox dengan tinggi memenuhi layar (100vh). Set elemen aside (Sidebar) memiliki lebar statis 256px (skala 64), dan biarkan elemen main (Konten) menyita sisa ruang fleksibel yang tersedia.',
                'initial_code' => "<div class=\"bg-slate-50\">\n    <aside class=\"bg-white border-r\">\n        Sidebar Menu\n    </aside>\n    <main>\n        Area Konten Utama\n    </main>\n</div>",
                'validation_rules' => json_encode(['flex', 'h-screen', 'w-64', 'flex-1']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 25,
                'title' => 'Distribusi Navigasi Topbar',
                'instruction' => 'Pada elemen header, jadikan sebagai kontainer flex. Distribusikan elemen Logo dan Profil sejauh mungkin ke sisi yang berlawanan secara horizontal, lalu pastikan keduanya sejajar secara vertikal di tengah poros.',
                'initial_code' => "<header class=\"bg-white p-4 border-b\">\n    <div class=\"font-bold\">DevPanel</div>\n    <div class=\"user-avatar\">Profil</div>\n</header>",
                'validation_rules' => json_encode(['flex', 'justify-between', 'items-center']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 25,
                'title' => 'Grid Sistem Responsif',
                'instruction' => 'Atur div kontainer statistik agar menggunakan CSS Grid. Implementasikan Mobile-First: mulai dengan 1 kolom secara default. Pada layar medium (md) ubah menjadi 2 kolom, dan pada layar besar (lg) menjadi 4 kolom. Berikan celah antar kolom/baris sebesar 24px (skala 6).',
                'initial_code' => "<div class=\"mt-8\">\n    <div class=\"p-4 bg-white shadow rounded\">Stat 1</div>\n    <div class=\"p-4 bg-white shadow rounded\">Stat 2</div>\n    <div class=\"p-4 bg-white shadow rounded\">Stat 3</div>\n    <div class=\"p-4 bg-white shadow rounded\">Stat 4</div>\n</div>",
                'validation_rules' => json_encode(['grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-4', 'gap-6']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 25,
                'title' => 'Manajemen Overflow Konten',
                'instruction' => 'Tabel data yang lebar dapat merusak layout pada perangkat mobile. Berikan utilitas agar kontainer tabel ini memunculkan scrollbar horizontal secara otomatis hanya ketika kontennya melewati batas lebar layar (overflow). Pastikan lebar tabel tersetting 100%.',
                'initial_code' => "<div class=\"mt-8 bg-white p-4 rounded shadow\">\n    <table>\n        \n    </table>\n</div>",
                'validation_rules' => json_encode(['overflow-x-auto', 'w-full']),
            ]
        ];

        DB::table('lab_steps')->insert($steps);
    }

    /**
     * LAB BAB 3: ADVANCED STYLING & EFFECTS
     * Fokus: Pseudo-classes (Hover, Focus), Transisi, dan Dekorasi Visual Kompleks.
     */
    private function seedLabBab3()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 03: Implementasi UI Lanjutan',
            'slug' => 'lab-03-advanced-styling',
            'description' => 'Requirement: Modifikasi komponen UI menggunakan utilitas tingkat lanjut. Integrasikan efek Glassmorphism, transisi state (hover), dan manipulasi rendering background.',
            'duration_minutes' => 60,
            'passing_grade' => 70,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 25,
                'title' => 'Linear Background Gradient',
                'instruction' => 'Hapus warna latar solid pada tombol. Terapkan background gradient dengan arah dari kiri ke kanan. Gunakan warna titik awal violet-500 dan titik akhir fuchsia-500.',
                'initial_code' => "<button class=\"px-8 py-3 rounded-lg text-white font-bold\">\n    Deploy App\n</button>",
                'validation_rules' => json_encode(['bg-gradient-to-r', 'from-violet-500', 'to-fuchsia-500']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 25,
                'title' => 'Micro-Interactions (Hover State)',
                'instruction' => 'Tambahkan efek interaktif pada kartu saat di-hover oleh kursor: angkat posisi kartu ke atas sumbu Y sebesar skala 2, dan perbesar ukuran bayangannya menjadi skala 2xl. Pastikan Anda menambahkan utilitas transisi dan durasi 300ms agar animasi tidak kasar.',
                'initial_code' => "<div class=\"bg-white p-6 rounded-xl shadow-md cursor-pointer\">\n    <h3>Server Server Alpha</h3>\n</div>",
                'validation_rules' => json_encode(['hover:-translate-y-2', 'hover:shadow-2xl', 'transition', 'duration-300']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 25,
                'title' => 'Penerapan Efek Glassmorphism',
                'instruction' => 'Ubah div konten menjadi efek kaca tembus pandang (glassmorphism). Set background menjadi putih dengan opasitas 10%. Tambahkan efek backdrop blur skala medium (md), dan buat border tipis menggunakan warna putih beropasitas 20%.',
                'initial_code' => "<div class=\"bg-slate-900 p-10 min-h-screen\">\n    <div class=\"p-8 rounded-2xl\">\n        <p class=\"text-white\">Data Rahasia Terenkripsi</p>\n    </div>\n</div>",
                'validation_rules' => json_encode(['bg-white/10', 'backdrop-blur-md', 'border-white/20']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 25,
                'title' => 'Masking Gradient Text',
                'instruction' => 'Buat teks H1 seolah dicat dengan warna gradient. Pertama, atur warna teks menjadi transparan penuh. Kemudian, mask (clip) background gradient agar hanya muncul di dalam batas area teks tersebut.',
                'initial_code' => "<h1 class=\"text-5xl font-black bg-gradient-to-r from-cyan-400 to-blue-500\">\n    Kinerja Tanpa Batas\n</h1>",
                'validation_rules' => json_encode(['text-transparent', 'bg-clip-text']),
            ]
        ];

        DB::table('lab_steps')->insert($steps);
    }

    /**
     * FINAL PROJECT: CAPSTONE
     * Fokus: Sintesis seluruh materi untuk membuat layout landing page komersial utuh.
     */
    private function seedLabFinal()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Ujian Akhir: DevStudio Landing Page',
            'slug' => 'final-project-ch1-3',
            'description' => 'Requirement: Kompilasi semua pemahaman Anda (Layout, Spacing, Responsive, Effects) untuk menstrukturkan satu halaman pendaratan (Landing Page) yang modern dan fungsional.',
            'duration_minutes' => 90,
            'passing_grade' => 75,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 25,
                'title' => 'Arsitektur Fixed Navigation',
                'instruction' => 'Ubah elemen <nav> agar melayang statis di area teratas layar (fixed) dengan lebar maksimal layar (100%). Tinggikan z-index ke 50 agar menutupi konten bawah, lalu implementasikan Flexbox untuk menyejajarkan logo ke kiri dan menu ke kanan layar.',
                'initial_code' => "<nav class=\"bg-white/80 backdrop-blur px-6 py-4 border-b\">\n    <div class=\"font-bold\">DevStudio</div>\n    <ul class=\"gap-4\">\n        <li>Features</li>\n    </ul>\n</nav>",
                'validation_rules' => json_encode(['fixed', 'top-0', 'w-full', 'z-50', 'flex', 'justify-between']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 25,
                'title' => 'Hero Section Alignment',
                'instruction' => 'Beri padding vertikal yang sangat besar (skala 32) pada Hero section. Pusatkan seluruh perataan teks di dalamnya (text-center). Pada elemen tag judul, implementasikan teknik Gradient Text transparan dengan arah gradasi ke kanan.',
                'initial_code' => "<section class=\"bg-slate-50 px-6\">\n    <div class=\"max-w-4xl mx-auto\">\n        <h1 class=\"text-5xl font-black from-cyan-600 to-blue-600\">\n            Bangun Masa Depan Web\n        </h1>\n    </div>\n</section>",
                'validation_rules' => json_encode(['py-32', 'text-center', 'bg-clip-text', 'text-transparent', 'bg-gradient-to-r']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 25,
                'title' => 'Responsivitas Blok Fitur',
                'instruction' => 'Konfigurasikan area Features menjadi Grid. Susun secara default menjadi 1 kolom. Untuk tablet (md) naikkan jadi 2 kolom, dan desktop (lg) menjadi 3 kolom. Berikan celah grid ruang sebesar skala 8. Tambahkan juga interaksi bayangan membesar (shadow-lg) saat kartu di-hover.',
                'initial_code' => "<section class=\"py-20 px-6 max-w-6xl mx-auto\">\n    <div class=\"\">\n        <div class=\"p-6 bg-white rounded-xl border transition-all\">Cepat</div>\n        <div class=\"p-6 bg-white rounded-xl border transition-all\">Aman</div>\n        <div class=\"p-6 bg-white rounded-xl border transition-all\">Skalabel</div>\n    </div>\n</section>",
                'validation_rules' => json_encode(['grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-8', 'hover:shadow-lg']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 25,
                'title' => 'Formatting Footer',
                'instruction' => 'Lengkapi area footer. Terapkan warna background slate-900 pekat. Atur perataan teks ke tengah, dan ubah warna font menjadi abu-abu redup (slate-400) agar tidak terlalu kontras.',
                'initial_code' => "<footer class=\"py-8\">\n    <p>&copy; 2024 DevStudio Open Source.</p>\n</footer>",
                'validation_rules' => json_encode(['bg-slate-900', 'text-slate-400', 'text-center']),
            ]
        ];

        DB::table('lab_steps')->insert($steps);
    }
}