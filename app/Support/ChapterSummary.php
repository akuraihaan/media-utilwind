<?php

namespace App\Support;

class ChapterSummary
{
    public static function for(int|string|null $chapterId): array
    {
        $chapterId = (int) $chapterId;

        $chapters = [
            1 => [
                'id' => 1,
                'number' => '01',
                'title' => 'Pendahuluan',
                'subtitle' => 'Dasar HTML, CSS, dan Tailwind CSS',
                'theme' => 'cyan',
                'summary' => 'Bab ini membangun fondasi web dasar: struktur HTML, peran CSS, cara kerja utility-first Tailwind, penggunaan CDN, instalasi lokal, dan konfigurasi awal.',
                'key_points' => [
                    'HTML menyusun struktur konten, sedangkan CSS mengatur tampilan dan keterbacaan antarmuka.',
                    'Tailwind CSS memakai utility class sehingga styling dapat dibuat cepat, konsisten, dan mudah dipelihara.',
                    'CDN cocok untuk percobaan cepat, sementara instalasi lokal lebih tepat untuk proyek yang butuh proses build dan konfigurasi.',
                    'Konfigurasi dasar membantu menyesuaikan tema seperti warna, font, radius, dan token desain proyek.',
                ],
                'next_step' => 'Pastikan konsep utility class dan alur build sudah jelas sebelum masuk ke layouting.',
            ],
            2 => [
                'id' => 2,
                'number' => '02',
                'title' => 'Layouting',
                'subtitle' => 'Ruang, Flexbox, Grid, dan Responsif',
                'theme' => 'indigo',
                'summary' => 'Bab ini merangkum cara menyusun halaman dengan spacing yang rapi, Flexbox untuk arah dan perataan, Grid untuk struktur kolom/baris, serta breakpoint responsif.',
                'key_points' => [
                    'Spacing menjaga jarak antar elemen agar tampilan mudah dipindai dan tidak terasa padat.',
                    'Flexbox efektif untuk susunan satu dimensi seperti navbar, tombol berjajar, dan alignment konten.',
                    'Grid membantu membuat layout dua dimensi seperti kartu, galeri, dan area konten yang lebih kompleks.',
                    'Breakpoint responsif memastikan tampilan tetap nyaman di layar kecil, sedang, dan besar.',
                ],
                'next_step' => 'Latih kombinasi spacing, flex, grid, dan breakpoint pada komponen nyata agar pola layout makin terbaca.',
            ],
            3 => [
                'id' => 3,
                'number' => '03',
                'title' => 'Styling',
                'subtitle' => 'Tipografi, Warna, Border, dan Efek Visual',
                'theme' => 'fuchsia',
                'summary' => 'Bab ini memperkuat tampilan visual melalui tipografi, warna, latar belakang, border, radius, bayangan, dan efek sederhana untuk membangun hierarki antarmuka.',
                'key_points' => [
                    'Tipografi membantu membedakan judul, isi, label, dan informasi pendukung.',
                    'Warna dan latar belakang memberi penekanan, status, dan identitas visual pada komponen.',
                    'Border dan radius membantu membingkai area, memisahkan konten, dan membuat UI terasa teratur.',
                    'Shadow dan efek visual sebaiknya dipakai secukupnya untuk kedalaman, fokus, dan interaksi.',
                ],
                'next_step' => 'Tinjau kembali konsistensi warna, ukuran teks, dan efek agar desain tetap jelas tanpa terasa berlebihan.',
            ],
        ];

        return $chapters[$chapterId] ?? [
            'id' => $chapterId,
            'number' => str_pad((string) max(0, $chapterId), 2, '0', STR_PAD_LEFT),
            'title' => 'Materi Tailwind CSS',
            'subtitle' => 'Rangkuman Pembelajaran',
            'theme' => 'cyan',
            'summary' => 'Bab ini merangkum konsep utama yang sudah dipelajari dan menghubungkannya dengan praktik membangun antarmuka menggunakan Tailwind CSS.',
            'key_points' => [
                'Pahami tujuan setiap utility class sebelum menggabungkannya ke dalam komponen.',
                'Gunakan pola desain yang konsisten agar halaman mudah dibaca dan dipelihara.',
                'Tinjau bagian yang masih salah atau belum selesai sebagai prioritas belajar berikutnya.',
            ],
            'next_step' => 'Ulangi bagian yang masih lemah, lalu lanjutkan latihan dengan komponen yang lebih nyata.',
        ];
    }

    public static function forLab(object|null $lab): array
    {
        $chapterId = $lab?->chapter_id ?: $lab?->id;

        return self::for($chapterId);
    }
}
