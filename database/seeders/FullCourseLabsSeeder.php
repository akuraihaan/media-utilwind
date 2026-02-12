<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FullCourseLabsSeeder extends Seeder
{
    public function run()
    {
        // Reset tabel untuk mencegah duplikasi saat seeding ulang
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('lab_steps')->truncate();
        DB::table('labs')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // =========================================================================
        // LAB 1: FUNDAMENTAL & KONFIGURASI (Mencakup BAB 1)
        // Fokus: Refactoring Inline CSS -> Utility Classes -> Config
        // =========================================================================
        
        $lab1Id = DB::table('labs')->insertGetId([
            'title' => 'Lab 1: Fundamental & Konfigurasi',
            'slug' => 'lab-01-fundamental',
            'description' => 'Memahami transisi dari CSS Tradisional ke Tailwind dan struktur utilitas.',
            'duration_minutes' => 45,
            'passing_grade' => 100,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // --- TASK 1: CONTAINER (Masih Inline CSS) ---
        $l1_t1_code = '<div style="background-color: white; padding: 24px; border: 1px solid #e5e7eb; border-radius: 8px; max-width: 400px; margin: 0 auto;">
  <h2 style="font-size: 20px; font-weight: bold; margin-bottom: 10px;">Selamat Datang</h2>
  <p style="color: #6b7280;">Mari belajar Tailwind CSS dengan cara yang benar.</p>
</div>';

        // --- TASK 2: TYPOGRAPHY (Container sudah bersih, Font masih Inline) ---
        $l1_t2_code = '<div class="bg-white p-6 border border-gray-200 rounded-lg max-w-md mx-auto shadow-sm">
  <h2 style="font-size: 24px; font-weight: 700; margin-bottom: 16px;">Selamat Datang</h2>
  <p style="color: #4b5563; line-height: 1.625;">Mari belajar Tailwind CSS dengan cara yang benar.</p>
</div>';

        // --- TASK 3: BUTTON (Semua sudah bersih, Tambah Tombol) ---
        $l1_t3_code = '<div class="bg-white p-6 border border-gray-200 rounded-lg max-w-md mx-auto shadow-sm">
  <h2 class="text-2xl font-bold mb-4">Selamat Datang</h2>
  <p class="text-gray-600 leading-relaxed mb-6">Mari belajar Tailwind CSS dengan cara yang benar.</p>
  </div>';

        // --- TASK 4: CONFIG (File berbeda) ---
        $l1_t4_code = '<script>
  tailwind.config = {
    theme: {
      extend: {
        colors: {
          // Instruksi: Tambahkan warna "brand-blue": "#1e40af"
        }
      }
    }
  }
</script>
<div class="bg-brand-blue p-4 text-white text-center rounded">
  Testing Custom Theme
</div>';

        DB::table('lab_steps')->insert([
            [
                'lab_id' => $lab1Id,
                'title' => 'Task 1: Refactor Container (1.4)',
                'instruction' => "Hapus atribut `style` pada `div` pembungkus. Ganti dengan Utility Class:\n1. Background putih: `bg-white`\n2. Padding: `p-6`\n3. Border halus: `border border-gray-200`\n4. Radius: `rounded-lg`\n5. Lebar & Tengah: `max-w-md mx-auto`",
                'initial_code' => $l1_t1_code,
                'validation_rules' => json_encode(['bg-white', 'p-6', 'border-gray-200', 'rounded-lg', 'max-w-md', 'mx-auto']),
                'points' => 25, 'order_index' => 1, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab1Id,
                'title' => 'Task 2: Refactor Typography (1.4)',
                'instruction' => "Container sudah rapi. Sekarang perbaiki teks:\n1. Ubah `h2` menjadi: `text-2xl font-bold mb-4`.\n2. Ubah `p` menjadi warna abu-abu gelap (`text-gray-600`) dengan spasi baris santai (`leading-relaxed`).\n3. Tambahkan margin bawah pada paragraf (`mb-6`).",
                'initial_code' => $l1_t2_code,
                'validation_rules' => json_encode(['text-2xl', 'font-bold', 'text-gray-600', 'leading-relaxed', 'mb-6']),
                'points' => 25, 'order_index' => 2, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab1Id,
                'title' => 'Task 3: Membuat Tombol (1.4)',
                'instruction' => "Tambahkan elemen `<button>` di bawah paragraf.\nStyle tombol:\n1. Background biru: `bg-blue-600`\n2. Teks putih: `text-white`\n3. Padding: `px-4 py-2`\n4. Radius: `rounded`\n5. Efek Hover: `hover:bg-blue-700`",
                'initial_code' => $l1_t3_code,
                'validation_rules' => json_encode(['bg-blue-600', 'text-white', 'px-4', 'py-2', 'rounded', 'hover:bg-blue-700']),
                'points' => 25, 'order_index' => 3, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab1Id,
                'title' => 'Task 4: Konfigurasi Tema (1.6)',
                'instruction' => "Kita akan memodifikasi konfigurasi Tailwind.\nDi dalam objek `colors`, tambahkan properti `'brand-blue': '#1e40af'`. Ini akan memungkinkan penggunaan class `bg-brand-blue`.",
                'initial_code' => $l1_t4_code,
                'validation_rules' => json_encode(["'brand-blue':", "'#1e40af'"]),
                'points' => 25, 'order_index' => 4, 'created_at' => now(), 'updated_at' => now(),
            ]
        ]);


        // =========================================================================
        // LAB 2: LAYOUTING SYSTEM (Mencakup BAB 2)
        // Fokus: Flexbox Sidebar -> Grid Main Content -> Responsive
        // =========================================================================

        $lab2Id = DB::table('labs')->insertGetId([
            'title' => 'Lab 2: Layouting System',
            'slug' => 'lab-02-layouting',
            'description' => 'Membangun layout dashboard responsif menggunakan Flexbox dan Grid System.',
            'duration_minutes' => 60,
            'passing_grade' => 100,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Task 1: Sidebar Basic
        $l2_t1_code = '<div class="flex h-screen bg-gray-100">
  <aside class="w-64 bg-slate-800 text-white p-6">
    <div class="mb-8 font-bold text-xl">Dashboard</div>
    <nav>
      <a href="#" class="block py-2">Home</a>
      <a href="#" class="block py-2">Analytics</a>
      <a href="#" class="block py-2">Settings</a>
    </nav>
  </aside>
  <main class="flex-1 p-8">Konten Utama</main>
</div>';

        // Task 2: Flex Column pada Sidebar (Sidebar sudah flex dari task 1)
        $l2_t2_code = '<div class="flex h-screen bg-gray-100">
  <aside class="w-64 bg-slate-800 text-white p-6 flex flex-col justify-between">
    <div>
      <div class="mb-8 font-bold text-xl">Dashboard</div>
      <nav class="flex flex-col gap-2">
        <a href="#" class="block py-2 px-4 bg-slate-700 rounded">Home</a>
        <a href="#" class="block py-2 px-4 hover:bg-slate-700 rounded">Analytics</a>
      </nav>
    </div>
    <div class="text-sm text-slate-400">© 2024 App</div>
  </aside>
  <main class="flex-1 p-8">
    <div class="">
      <div class="bg-white p-6 rounded shadow">Card 1</div>
      <div class="bg-white p-6 rounded shadow">Card 2</div>
      <div class="bg-white p-6 rounded shadow">Card 3</div>
    </div>
  </main>
</div>';

        // Task 3: Responsive Grid (Grid sudah ada, tinggal responsive)
        $l2_t3_code = '<main class="flex-1 p-8">
  <div class="grid grid-cols-3 gap-6">
    <div class="bg-white p-6 rounded shadow">Statistik A</div>
    <div class="bg-white p-6 rounded shadow">Statistik B</div>
    <div class="bg-white p-6 rounded shadow">Statistik C</div>
  </div>
</main>';

        // Task 4: Internal Card Flex
        $l2_t4_code = '<div class="grid grid-cols-1 md:grid-cols-3 gap-6">
  <div class="bg-white p-6 rounded shadow">
    <div>
      <h3 class="text-gray-500 text-sm">Total User</h3>
      <span class="text-3xl font-bold">1,250</span>
    </div>
    <div class="bg-green-100 text-green-600 p-2 rounded-full">
      Icon
    </div>
  </div>
</div>';

        DB::table('lab_steps')->insert([
            [
                'lab_id' => $lab2Id,
                'title' => 'Task 1: Sidebar Structure (2.1)',
                'instruction' => "Rapikan Sidebar menggunakan Flexbox:\n1. Tambahkan `flex flex-col` pada elemen `<aside>` agar isinya vertikal.\n2. Tambahkan `justify-between` agar konten terpisah (menu di atas, footer di bawah nanti).\n3. Pada `<nav>`, tambahkan `flex flex-col gap-2` untuk jarak antar menu.",
                'initial_code' => $l2_t1_code,
                'validation_rules' => json_encode(['flex', 'flex-col', 'justify-between', 'gap-2']),
                'points' => 25, 'order_index' => 1, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab2Id,
                'title' => 'Task 2: Main Grid System (2.2)',
                'instruction' => "Sekarang kita kerjakan area Main Content.\nUbah `div` pembungkus Card menjadi Grid Container:\n1. Class: `grid`\n2. Kolom: `grid-cols-3`\n3. Jarak: `gap-6`",
                'initial_code' => $l2_t2_code,
                'validation_rules' => json_encode(['grid', 'grid-cols-3', 'gap-6']),
                'points' => 25, 'order_index' => 2, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab2Id,
                'title' => 'Task 3: Responsive Grid (2.2)',
                'instruction' => "Grid 3 kolom terlihat buruk di HP. Ubah menjadi Responsif:\n1. Default (Mobile): `grid-cols-1`\n2. Tablet/Desktop (md ke atas): `md:grid-cols-3`\n3. Ganti `grid-cols-3` yang lama dengan konfigurasi di atas.",
                'initial_code' => $l2_t3_code,
                'validation_rules' => json_encode(['grid-cols-1', 'md:grid-cols-3']),
                'points' => 25, 'order_index' => 3, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab2Id,
                'title' => 'Task 4: Card Alignment (2.1)',
                'instruction' => "Konten di dalam kartu masih berantakan (atas-bawah). Gunakan Flexbox untuk menyejajarkan kiri-kanan.\nPada `div` pembungkus konten kartu:\n1. Tambahkan `flex`\n2. Tambahkan `items-center` (vertikal tengah)\n3. Tambahkan `justify-between` (ujung ke ujung)",
                'initial_code' => $l2_t4_code,
                'validation_rules' => json_encode(['flex', 'items-center', 'justify-between']),
                'points' => 25, 'order_index' => 4, 'created_at' => now(), 'updated_at' => now(),
            ]
        ]);


        // =========================================================================
        // LAB 3: STYLING & EFFECTS (Mencakup BAB 3)
        // Fokus: Gradient, Typography, Ring, Animation
        // =========================================================================

        $lab3Id = DB::table('labs')->insertGetId([
            'title' => 'Lab 3: Advanced Styling',
            'slug' => 'lab-03-styling',
            'description' => 'Polesan akhir UI menggunakan Gradient, Efek, dan Animasi.',
            'duration_minutes' => 60,
            'passing_grade' => 100,
            'created_at' => now(), 'updated_at' => now(),
        ]);

        // Task 1: Gradient
        $l3_t1_code = '<div class="flex justify-center items-center min-h-screen bg-gray-50">
  <div class="w-80 h-48 bg-gray-800 rounded-xl p-6 text-white">
    <h2 class="font-bold text-xl">Premium Card</h2>
  </div>
</div>';

        // Task 2: Typography (Gradient sudah diterapkan)
        $l3_t2_code = '<div class="w-80 h-48 bg-gradient-to-r from-purple-600 to-blue-600 rounded-xl p-6 text-white shadow-xl">
  <h2>Membership Pro</h2>
  <p class="mt-2 text-sm opacity-90">Akses tak terbatas ke semua fitur.</p>
</div>';

        // Task 3: Button Ring (Card sudah rapi)
        $l3_t3_code = '<div class="w-80 p-6 bg-white rounded-xl shadow-lg border border-gray-100">
  <h2 class="font-bold text-gray-800 text-lg">Konfirmasi</h2>
  <p class="text-gray-500 text-sm mb-4">Apakah Anda yakin ingin melanjutkan?</p>
  <button class="w-full bg-indigo-600 text-white py-2 rounded-lg">
    Terima & Lanjut
  </button>
</div>';

        // Task 4: Animation & Transform
        $l3_t4_code = '<div class="group w-64 p-4 bg-white border rounded-lg cursor-pointer">
  <div class="w-12 h-12 bg-indigo-100 rounded-full mb-3"></div>
  <h3 class="font-bold">Interactive Card</h3>
  <p class="text-sm text-gray-500">Hover saya untuk melihat efek.</p>
</div>';

        DB::table('lab_steps')->insert([
            [
                'lab_id' => $lab3Id,
                'title' => 'Task 1: Background Gradient (3.2)',
                'instruction' => "Ganti warna solid `bg-gray-800` menjadi Gradient:\n1. Arah: `bg-gradient-to-r` (kiri ke kanan)\n2. Warna Mulai: `from-purple-600`\n3. Warna Akhir: `to-blue-600`\n4. Tambahkan shadow: `shadow-xl`",
                'initial_code' => $l3_t1_code,
                'validation_rules' => json_encode(['bg-gradient-to-r', 'from-purple-600', 'to-blue-600', 'shadow-xl']),
                'points' => 25, 'order_index' => 1, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab3Id,
                'title' => 'Task 2: Advanced Typography (3.1)',
                'instruction' => "Styling Judul `h2`:\n1. Ukuran: `text-2xl`\n2. Tebal: `font-extrabold`\n3. Huruf kapital semua: `uppercase`\n4. Jarak antar huruf: `tracking-wider`",
                'initial_code' => $l3_t2_code,
                'validation_rules' => json_encode(['text-2xl', 'font-extrabold', 'uppercase', 'tracking-wider']),
                'points' => 25, 'order_index' => 2, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab3Id,
                'title' => 'Task 3: Focus Ring (Accessibility) (3.3)',
                'instruction' => "Buat tombol aksesibel saat di-klik/tab.\nTambahkan class pada `<button>`:\n1. `focus:outline-none`\n2. `focus:ring-2`\n3. `focus:ring-offset-2`\n4. `focus:ring-indigo-500`",
                'initial_code' => $l3_t3_code,
                'validation_rules' => json_encode(['focus:ring-2', 'focus:ring-offset-2', 'focus:ring-indigo-500']),
                'points' => 25, 'order_index' => 3, 'created_at' => now(), 'updated_at' => now(),
            ],
            [
                'lab_id' => $lab3Id,
                'title' => 'Task 4: Hover & Transform (3.4)',
                'instruction' => "Berikan efek interaktif pada div pembungkus (Card):\n1. Transisi halus: `transition duration-300`\n2. Saat Hover naik sedikit: `hover:-translate-y-1`\n3. Saat Hover bayangan menebal: `hover:shadow-lg`\n4. Border berubah warna: `hover:border-indigo-300`",
                'initial_code' => $l3_t4_code,
                'validation_rules' => json_encode(['transition', 'duration-300', 'hover:-translate-y-1', 'hover:shadow-lg', 'hover:border-indigo-300']),
                'points' => 25, 'order_index' => 4, 'created_at' => now(), 'updated_at' => now(),
            ]
        ]);
    }
}