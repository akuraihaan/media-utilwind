<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class BadgeSeeder extends Seeder
{
    public function run()
    {
        // Kosongkan tabel sebelum seeding ulang agar tidak duplikat
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('user_badges')->truncate();
        DB::table('badges')->truncate();
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        $badges = [
            [
                'name' => 'First Render',
                'description' => 'Langkah pertama seorang developer. Diberikan setelah Anda berhasil menyelesaikan kuis Evaluasi Bab 1.',
                'color' => 'slate', // Silver/Abu-abu
                // Shield / Perisai Pemula
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M12 2L3 5v6c0 5.55 3.84 10.74 9 12 5.16-1.26 9-6.45 9-12V5l-9-3z"/><path d="M12 4L5 7v5c0 4.5 2.5 8.5 7 10 4.5-1.5 7-5.5 7-10V7l-7-3zm0 11c-1.1 0-2-.9-2-2h4c0 1.1-.9 2-2 2zm3-4H9v-2h6v2z"/></svg>'
            ],
            [
                'name' => 'Sandbox Explorer',
                'description' => 'Berani mencoba hal baru. Diberikan setelah Anda berhasil memvalidasi (lulus) minimal 1 Praktikum Lab.',
                'color' => 'blue',
                // Hexagon Code
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M21 16.5l-9 5.196-9-5.196V7.5L12 2.304 21 7.5v9z"/><path d="M12 4.613L19.5 8.94v6.12L12 19.387 4.5 15.06V8.94L12 4.613zM12 2L3 7.2v9.6L12 22l9-5.2V7.2L12 2z"/><path d="M8 10l-2 2 2 2M16 10l2 2-2 2" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>'
            ],
            [
                'name' => 'Flexbox Master',
                'description' => 'Menguasai alignment, justify, dan tata letak modern. Diberikan setelah lulus Kuis Evaluasi Bab 2.',
                'color' => 'indigo',
                // Bintang / Star
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M12 2l3.09 6.26L22 9.27l-5 4.87 1.18 6.88L12 17.77l-6.18 3.25L7 14.14 2 9.27l6.91-1.01L12 2z"/><path d="M12 15.4l-3.76 2.27 1-4.28-3.32-2.88 4.38-.38L12 6.1l1.71 4.03 4.38.38-3.32 2.88 1 4.28L12 15.4z"/><path d="M13 9h-2L9 14h3v3l4-5h-3l1-3z" stroke="currentColor" stroke-width="1" fill="currentColor"/></svg>'
            ],
            [
                'name' => 'Responsive Ninja',
                'description' => 'Desain Anda tidak hancur di HP! Diberikan kepada siswa yang berhasil mengumpulkan total 1.000 XP.',
                'color' => 'emerald',
                // Diamond Multi-layer
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M12 2L2 12l10 10 10-10L12 2zm0 17L5 12l7-7 7 7-7 7z"/><path d="M12 4L4 12l8 8 8-8-8-8zm0 14l-6-6 6-6 6 6-6 6z"/><circle cx="12" cy="12" r="2" fill="currentColor"/></svg>'
            ],
            [
                'name' => 'Arbitrary Hacker',
                'description' => 'Keluar dari batasan default theme dengan kurung siku [ ]. Diberikan setelah menuntaskan minimal 3 Praktikum Lab.',
                'color' => 'fuchsia',
                // Emblem Tech 
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M12 2l2.4 2.4L12 6.8 9.6 4.4 12 2zm0 20l-2.4-2.4 2.4-2.4 2.4 2.4L12 22zM2 12l2.4-2.4L6.8 12 4.4 14.4 2 12zm20 0l-2.4 2.4-2.4-2.4 2.4-2.4L22 12z"/><path d="M12 4l4 4-4 4-4-4 4-4zm0 16l-4-4 4-4 4 4-4 4zM4 12l4-4 4 4-4 4-4-4zm16 0l-4 4-4-4 4-4 4 4z"/><circle cx="12" cy="12" r="3" fill="currentColor" stroke="currentColor" stroke-width="1" fill-opacity="0.5"/></svg>'
            ],
            [
                'name' => 'Pixel Perfect',
                'description' => 'Kesempurnaan pemahaman. Diberikan secara eksklusif jika Anda berhasil meraih skor sempurna (100) pada evaluasi kuis manapun.',
                'color' => 'yellow', // Emas
                // Sun / Burst Medal
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.3" d="M12 1.5L14.7 8h6.8l-5.5 4.5 2.1 6.5L12 15l-6.1 4 2.1-6.5L2.5 8h6.8L12 1.5z"/><path d="M12 4l1.8 4.5H19l-4 3 1.5 5L12 13.5l-4.5 3 1.5-5-4-3h5.2L12 4zm0 6a2.5 2.5 0 100 5 2.5 2.5 0 000-5z"/></svg>'
            ],
            [
                'name' => 'Consistency King',
                'description' => 'Dedikasi luar biasa! Diberikan kepada siswa yang berhasil menembus 2.500 Total XP.',
                'color' => 'cyan',
                // Gem / Crystal
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M12 2l8 6v8l-8 6-8-6V8l8-6z"/><path d="M12 4l6 4.5v7L12 20l-6-4.5v-7L12 4zm0 3.5L8.5 10v4L12 16.5 15.5 14v-4L12 7.5z"/></svg>'
            ],
            [
                'name' => 'Tailwind Architect',
                'description' => 'Lencana Ultimate! Terbuka setelah menamatkan semua Kuis (Bab 1, 2, 3), minimal 3 Lab, dan meraih 4000 XP.',
                'color' => 'rose', // Merah Delima
                // Crown / Mahkota
                'icon' => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-full h-full"><path fill-opacity="0.2" d="M3 18h18v3H3v-3zM4 6l3 7 5-4 5 4 3-7-4 2-4-5-4 5-4-2z"/><path d="M4.5 8.5l2.5 4 5-4 5 4 2.5-4-2 6h-11l-2-6zM3 20h18v-1H3v1z"/><circle cx="12" cy="7" r="1.5" fill="currentColor"/><circle cx="7" cy="11" r="1" fill="currentColor"/><circle cx="17" cy="11" r="1" fill="currentColor"/></svg>'
            ]
        ];

        DB::table('badges')->insert($badges);
    }
}