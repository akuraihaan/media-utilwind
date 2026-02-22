<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
class FullCourseLabsSeeder extends Seeder
{
    public function run()
    {
        Schema::disableForeignKeyConstraints();

        // Bersihkan data lama (Child dulu baru Parent)
        DB::table('lab_steps')->truncate();
        DB::table('labs')->truncate();

        Schema::enableForeignKeyConstraints();

        $this->seedLabBab1();
        $this->seedLabBab2();
        $this->seedLabBab3();
        $this->seedLabFinal();
    }

    /**
     * LAB BAB 1: REFACORING LEGACY CODE
     * Fokus: Konsep Dasar, Utility Classes, Spacing, Sizing
     */
    private function seedLabBab1()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 01: Refactoring Legacy Code',
            'slug' => 'lab-01-refactoring-legacy',
            'description' => 'Misi: Bersihkan kode "jorok" penuh CSS inline dan ubah menjadi desain modern menggunakan Tailwind CSS.',
            'duration_minutes' => 60,
            'passing_grade' => 70,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 10,
                'title' => 'Task 1: Setup Tailwind (CDN)',
                'instruction' => 'Browser belum kenal Tailwind. Masukkan script CDN Tailwind di dalam tag <head>.',
                'initial_code' => "<!DOCTYPE html>\n<html>\n<head>\n    <title>Profil Karyawan</title>\n    \n</head>\n<body>...</body>",
                'validation_rules' => json_encode(['<script src="https://cdn.tailwindcss.com"></script>']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 15,
                'title' => 'Task 2: Container & Spacing',
                'instruction' => 'Ganti style width/margin manual dengan `max-w-md mx-auto`. Tambahkan padding `p-6` dan background `bg-slate-100`.',
                'initial_code' => '<div style="width: 50%; margin: 0 auto; background-color: #f0f0f0; padding: 20px;">',
                'validation_rules' => json_encode(['max-w-md', 'mx-auto', 'p-6', 'bg-slate-100']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 15,
                'title' => 'Task 3: Card Styling',
                'instruction' => 'Buat tampilan kartu profesional. Gunakan `bg-white`, `rounded-xl` (sudut membulat), dan `shadow-lg` (bayangan).',
                'initial_code' => '<div style="border: 1px solid black; background: white;">',
                'validation_rules' => json_encode(['bg-white', 'rounded-xl', 'shadow-lg']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 15,
                'title' => 'Task 4: Typography Hierarchy',
                'instruction' => 'Judul nama harus menonjol: `text-2xl font-bold text-slate-800`. Jabatan: `text-indigo-600 font-medium`.',
                'initial_code' => '<h3>Budi Santoso</h3><p style="color: blue;">Web Developer</p>',
                'validation_rules' => json_encode(['text-2xl', 'font-bold', 'text-slate-800', 'text-indigo-600', 'font-medium']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 5, 'points' => 20,
                'title' => 'Task 5: Avatar Styling',
                'instruction' => 'Buat foto profil menjadi lingkaran sempurna (`rounded-full`) dengan ukuran fix `w-24 h-24` dan border `ring-4 ring-indigo-50`.',
                'initial_code' => '<img src="/avatar.jpg" style="width: 100px; height: 100px;">',
                'validation_rules' => json_encode(['rounded-full', 'w-24', 'h-24', 'ring-4', 'ring-indigo-50']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 6, 'points' => 10,
                'title' => 'Task 6: Interactive Button',
                'instruction' => 'Buat tombol "Hire Me": `bg-indigo-600`, teks putih, `rounded-lg`, dan efek `hover:bg-indigo-700`.',
                'initial_code' => '<button>Hire Me</button>',
                'validation_rules' => json_encode(['bg-indigo-600', 'text-white', 'rounded-lg', 'hover:bg-indigo-700']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 7, 'points' => 15,
                'title' => 'Task 7: Final Layout',
                'instruction' => 'Ratakan semua konten ke tengah dengan `text-center` pada container kartu dan beri jarak antar elemen dengan `space-y-4`.',
                'initial_code' => '<div class="bg-white rounded-xl shadow-lg p-6">',
                'validation_rules' => json_encode(['text-center', 'space-y-4']),
            ],
        ];

        DB::table('lab_steps')->insert($steps);
    }

    /**
     * LAB BAB 2: MODERN LAYOUTING
     * Fokus: Flexbox, Grid, Responsive Design
     */
    private function seedLabBab2()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 02: Dashboard Layout Construction',
            'slug' => 'lab-02-layouting-mastery',
            'description' => 'Misi: Membangun struktur dashboard admin yang responsif menggunakan kekuatan Flexbox dan CSS Grid.',
            'duration_minutes' => 75,
            'passing_grade' => 75,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 20,
                'title' => 'Task 1: Sidebar & Main Content (Flex)',
                'instruction' => 'Buat layout 2 kolom (Sidebar kiri, Konten kanan). Gunakan `flex` pada container utama. Sidebar `w-64`, Konten `flex-1`.',
                'initial_code' => '<div class="h-screen bg-gray-100">\n  \n  <aside>Sidebar</aside>\n  \n  <main>Content</main>\n</div>',
                'validation_rules' => json_encode(['flex', 'h-screen', 'w-64', 'flex-1']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 20,
                'title' => 'Task 2: Navbar Alignment (Flex Justify)',
                'instruction' => 'Di dalam <header>, buat logo di kiri dan profil user di kanan. Gunakan `flex`, `justify-between`, dan `items-center`.',
                'initial_code' => '<header class="bg-white p-4 shadow">\n  <div>Logo</div>\n  <div>User Profile</div>\n</header>',
                'validation_rules' => json_encode(['flex', 'justify-between', 'items-center']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 30,
                'title' => 'Task 3: Stats Grid (Responsive)',
                'instruction' => 'Buat grid kartu statistik. Mobile 1 kolom (`grid-cols-1`), Tablet 2 kolom (`md:grid-cols-2`), Desktop 4 kolom (`lg:grid-cols-4`). Beri `gap-6`.',
                'initial_code' => '<section class="mt-8">\n  \n  <div>\n    <div class="card">Stat 1</div>\n    <div class="card">Stat 2</div>\n    <div class="card">Stat 3</div>\n    <div class="card">Stat 4</div>\n  </div>\n</section>',
                'validation_rules' => json_encode(['grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-4', 'gap-6']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 30,
                'title' => 'Task 4: Table Layout (Overflow)',
                'instruction' => 'Tabel data seringkali lebar. Bungkus tabel dengan div yang memiliki `overflow-x-auto` agar bisa discroll horizontal pada layar kecil.',
                'initial_code' => '<div>\n  <table class="w-full">...</table>\n</div>',
                'validation_rules' => json_encode(['overflow-x-auto', 'w-full']),
            ]
        ];

        DB::table('lab_steps')->insert($steps);
    }

    /**
     * LAB BAB 3: ADVANCED STYLING
     * Fokus: Decoration, Effects, Transforms, Transitions
     */
    private function seedLabBab3()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Lab 03: Creative UI Components',
            'slug' => 'lab-03-styling-magic',
            'description' => 'Misi: Membuat komponen UI yang cantik dan interaktif menggunakan efek visual dan transisi.',
            'duration_minutes' => 60,
            'passing_grade' => 70,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 20,
                'title' => 'Task 1: Gradient Button',
                'instruction' => 'Buat tombol dengan background gradient. Gunakan `bg-gradient-to-r`, `from-purple-500`, `to-pink-500`.',
                'initial_code' => '<button class="px-6 py-2 rounded-lg text-white">\n  Magic Button\n</button>',
                'validation_rules' => json_encode(['bg-gradient-to-r', 'from-purple-500', 'to-pink-500']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 30,
                'title' => 'Task 2: Hover & Transform Card',
                'instruction' => 'Buat kartu produk. Saat di-hover, kartu harus naik sedikit (`hover:-translate-y-2`) dan shadow makin besar (`hover:shadow-2xl`). Jangan lupa `transition` dan `duration-300` agar halus.',
                'initial_code' => '<div class="bg-white p-6 rounded-xl shadow-md">\n  <h3>Product Name</h3>\n</div>',
                'validation_rules' => json_encode(['hover:-translate-y-2', 'hover:shadow-2xl', 'transition', 'duration-300']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 20,
                'title' => 'Task 3: Glassmorphism Effect',
                'instruction' => 'Buat panel transparan di atas background gelap. Gunakan `bg-white/10` (opacity), `backdrop-blur-md`, dan border tipis `border-white/20`.',
                'initial_code' => '<div class="bg-black p-10">\n  \n  <div class="p-6 rounded-xl text-white">\n    Glass Content\n  </div>\n</div>',
                'validation_rules' => json_encode(['bg-white/10', 'backdrop-blur-md', 'border-white/20']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 30,
                'title' => 'Task 4: Text Gradient (Clip)',
                'instruction' => 'Buat judul teks besar (`text-6xl`) yang warnanya gradient (bukan solid color). Gunakan `bg-clip-text` dan `text-transparent`.',
                'initial_code' => '<h1 class="font-bold text-6xl">AMAZING HEADLINE</h1>',
                'validation_rules' => json_encode(['bg-clip-text', 'text-transparent', 'bg-gradient-to']),
            ]
        ];

        DB::table('lab_steps')->insert($steps);
    }

    /**
     * FINAL PROJECT: CAPSTONE (BAB 1-3)
     */
    private function seedLabFinal()
    {
        $labId = DB::table('labs')->insertGetId([
            'title' => 'Final Project: DevStudio Landing Page',
            'slug' => 'final-project-ch1-3',
            'description' => 'Tantangan Akhir: Bangun Landing Page Responsif lengkap dari nol. Gabungkan Layouting, Styling, dan Interaktivitas.',
            'duration_minutes' => 90,
            'passing_grade' => 75,
            'is_active' => 1,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        $steps = [
            [
                'lab_id' => $labId, 'order_index' => 1, 'points' => 20,
                'title' => 'Step 1: Navigasi (Fixed & Glass)',
                'instruction' => "Buat navbar yang `fixed` di atas layar. \nGunakan `w-full`, `z-50`, `backdrop-blur-lg` untuk efek kaca. \nGunakan `flex` dan `justify-between` untuk logo dan menu.",
                'initial_code' => "\n<nav>\n  \n</nav>",
                'validation_rules' => json_encode(['fixed', 'w-full', 'z-50', 'backdrop-blur', 'flex', 'justify-between']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 2, 'points' => 30,
                'title' => 'Step 2: Hero Section (Center & Gradient)',
                'instruction' => "Buat Hero section dengan padding vertikal besar (`py-32`). \nKonten harus rata tengah (`text-center`). \nJudul H1 menggunakan Gradient Text (`bg-clip-text text-transparent`). \nTombol CTA harus punya efek hover scale.",
                'initial_code' => "<section>\n  <h1>Build Faster</h1>\n  <button>Get Started</button>\n</section>",
                'validation_rules' => json_encode(['py-32', 'text-center', 'bg-clip-text', 'text-transparent', 'hover:scale']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 3, 'points' => 40,
                'title' => 'Step 3: Features Grid (Responsive)',
                'instruction' => "Buat grid kartu fitur. \nMobile: 1 kolom. \nTablet: 2 kolom (`md:grid-cols-2`). \nDesktop: 3 kolom (`lg:grid-cols-3`). \nSetiap kartu harus punya `hover:shadow-xl` dan `transition`.",
                'initial_code' => "<section class=\"container mx-auto px-6\">\n  \n  <div>\n    \n    \n    \n  </div>\n</section>",
                'validation_rules' => json_encode(['grid', 'grid-cols-1', 'md:grid-cols-2', 'lg:grid-cols-3', 'gap-', 'hover:shadow']),
            ],
            [
                'lab_id' => $labId, 'order_index' => 4, 'points' => 10,
                'title' => 'Step 4: Footer Minimalis',
                'instruction' => "Buat footer dengan background gelap (`bg-slate-900`), teks putih pudar (`text-slate-400`), dan rata tengah.",
                'initial_code' => "<footer>\n  &copy; 2024 DevStudio\n</footer>",
                'validation_rules' => json_encode(['bg-slate-900', 'text-slate-400', 'text-center']),
            ]
        ];

        DB::table('lab_steps')->insert($steps);
    }
}