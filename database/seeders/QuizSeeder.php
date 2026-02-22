<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class QuizSeeder extends Seeder
{
    public function run()
    {
        // Opsional: Bersihkan tabel dulu agar tidak duplikat saat seeding ulang
        Schema::disableForeignKeyConstraints();
        QuizQuestion::truncate();
        QuizOption::truncate();
        Schema::enableForeignKeyConstraints();

        // =================================================================
        // BAB 1: PENDAHULUAN (10 SOAL)
        // =================================================================
        $bab1 = 1;

        $this->createQuestion($bab1, 'Apa filosofi utama dari Tailwind CSS?', [
            ['text' => 'Component-First Framework', 'correct' => false],
            ['text' => 'Utility-First Framework', 'correct' => true],
            ['text' => 'Object-Oriented CSS', 'correct' => false],
            ['text' => 'Semantic CSS Framework', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Perintah CLI apa yang digunakan untuk membuat file konfigurasi tailwind.config.js?', [
            ['text' => 'npx tailwindcss init', 'correct' => true],
            ['text' => 'npm install tailwindcss', 'correct' => false],
            ['text' => 'npx create-tailwind', 'correct' => false],
            ['text' => 'tailwind --config', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Di file manakah kita mendefinisikan path file HTML/Blade agar Tailwind bisa melakukan Tree Shaking (menghapus CSS tak terpakai)?', [
            ['text' => 'package.json', 'correct' => false],
            ['text' => 'style.css', 'correct' => false],
            ['text' => 'tailwind.config.js (bagian content)', 'correct' => true],
            ['text' => 'webpack.mix.js', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Mana cara penulisan padding 1rem (16px) pada semua sisi yang benar di Tailwind?', [
            ['text' => 'padding: 4', 'correct' => false],
            ['text' => 'pa-4', 'correct' => false],
            ['text' => 'p-4', 'correct' => true],
            ['text' => 'p-16px', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Apa arti dari utility class "m-auto"?', [
            ['text' => 'Margin otomatis hanya di atas dan bawah.', 'correct' => false],
            ['text' => 'Margin otomatis di semua sisi.', 'correct' => true],
            ['text' => 'Margin otomatis hanya di kiri dan kanan.', 'correct' => false],
            ['text' => 'Membuat elemen menjadi absolute.', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Apa keuntungan utama menggunakan Utility-First CSS seperti Tailwind?', [
            ['text' => 'Kode HTML menjadi lebih bersih tanpa class.', 'correct' => false],
            ['text' => 'Tidak perlu memikirkan nama class yang aneh-aneh (Naming Fatigue).', 'correct' => true],
            ['text' => 'File CSS otomatis menjadi sangat besar.', 'correct' => false],
            ['text' => 'Hanya bisa digunakan dengan React.', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Directive Tailwind manakah yang digunakan untuk menyuntikkan style dasar (reset CSS)?', [
            ['text' => '@tailwind base', 'correct' => true],
            ['text' => '@tailwind components', 'correct' => false],
            ['text' => '@tailwind utilities', 'correct' => false],
            ['text' => '@tailwind reset', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Bagaimana cara menambahkan kustomisasi warna baru di tailwind.config.js?', [
            ['text' => 'Di dalam properti "plugins".', 'correct' => false],
            ['text' => 'Di dalam properti "theme" > "extend" > "colors".', 'correct' => true],
            ['text' => 'Langsung di file CSS.', 'correct' => false],
            ['text' => 'Tidak bisa dikustomisasi.', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Class "w-full" setara dengan properti CSS apa?', [
            ['text' => 'width: 100vw', 'correct' => false],
            ['text' => 'width: 100%', 'correct' => true],
            ['text' => 'width: auto', 'correct' => false],
            ['text' => 'max-width: 100%', 'correct' => false],
        ]);

        $this->createQuestion($bab1, 'Untuk mengatur ukuran teks menjadi sangat kecil (0.75rem), class apa yang digunakan?', [
            ['text' => 'text-sm', 'correct' => false],
            ['text' => 'text-xs', 'correct' => true],
            ['text' => 'text-tiny', 'correct' => false],
            ['text' => 'font-small', 'correct' => false],
        ]);


        // =================================================================
        // BAB 2: LAYOUTING (10 SOAL)
        // =================================================================
        $bab2 = 2;

        $this->createQuestion($bab2, 'Kelas utilitas mana yang digunakan untuk menyusun item Flexbox secara vertikal (kolom)?', [
            ['text' => 'flex-row', 'correct' => false],
            ['text' => 'flex-col', 'correct' => true],
            ['text' => 'items-center', 'correct' => false],
            ['text' => 'grid-cols-1', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Apa fungsi dari kelas "flex-wrap" pada elemen induk?', [
            ['text' => 'Mencegah elemen anak untuk menyusut.', 'correct' => false],
            ['text' => 'Membalik urutan elemen anak.', 'correct' => false],
            ['text' => 'Membungkus elemen anak ke baris baru jika tidak muat.', 'correct' => true],
            ['text' => 'Memaksa semua elemen tetap dalam satu baris.', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Kelas manakah yang membuat elemen mengisi sisa ruang yang tersedia (setara flex-grow: 1)?', [
            ['text' => 'flex-1', 'correct' => true],
            ['text' => 'flex-none', 'correct' => false],
            ['text' => 'flex-initial', 'correct' => false],
            ['text' => 'w-full', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Jika Anda ingin elemen tertentu muncul paling awal terlepas dari urutan HTML-nya, kelas apa yang digunakan?', [
            ['text' => 'order-0', 'correct' => false],
            ['text' => 'order-last', 'correct' => false],
            ['text' => 'order-first', 'correct' => true],
            ['text' => 'z-10', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Untuk membagi halaman menjadi 3 kolom grid yang sama besar, kelas apa yang digunakan?', [
            ['text' => 'grid-rows-3', 'correct' => false],
            ['text' => 'cols-3', 'correct' => false],
            ['text' => 'grid-cols-3', 'correct' => true],
            ['text' => 'flex-3', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Bagaimana cara membuat sebuah elemen Grid melebar (merge) menempati 2 kolom?', [
            ['text' => 'col-span-2', 'correct' => true],
            ['text' => 'row-span-2', 'correct' => false],
            ['text' => 'col-start-2', 'correct' => false],
            ['text' => 'w-2/4', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Kelas "justify-between" pada Flexbox berfungsi untuk...', [
            ['text' => 'Menengahkan semua item.', 'correct' => false],
            ['text' => 'Memberi jarak merata di antara item, item pertama di awal, item terakhir di akhir.', 'correct' => true],
            ['text' => 'Memberi jarak merata di sekeliling item.', 'correct' => false],
            ['text' => 'Meratakan item ke kanan.', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Agar container berada di tengah layar secara horizontal, kelas apa yang digunakan bersama dengan width tertentu?', [
            ['text' => 'text-center', 'correct' => false],
            ['text' => 'align-center', 'correct' => false],
            ['text' => 'mx-auto', 'correct' => true],
            ['text' => 'justify-center', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Apa fungsi kelas "gap-4" pada container Grid atau Flex?', [
            ['text' => 'Memberikan margin luar sebesar 1rem.', 'correct' => false],
            ['text' => 'Memberikan jarak (gutter) antar item sebesar 1rem.', 'correct' => true],
            ['text' => 'Memberikan padding dalam sebesar 1rem.', 'correct' => false],
            ['text' => 'Membuat border setebal 4px.', 'correct' => false],
        ]);

        $this->createQuestion($bab2, 'Kelas untuk membuat elemen absolute diposisikan relatif terhadap parent-nya adalah...', [
            ['text' => 'Parent harus memiliki class "static"', 'correct' => false],
            ['text' => 'Parent harus memiliki class "relative"', 'correct' => true],
            ['text' => 'Parent harus memiliki class "fixed"', 'correct' => false],
            ['text' => 'Tidak perlu class tambahan pada parent', 'correct' => false],
        ]);


        // =================================================================
        // BAB 3: STYLING (10 SOAL)
        // =================================================================
        $bab3 = 3;

        $this->createQuestion($bab3, 'Bagaimana cara membuat teks menjadi tebal (bold) di Tailwind?', [
            ['text' => 'text-bold', 'correct' => false],
            ['text' => 'font-bold', 'correct' => true],
            ['text' => 'weight-bold', 'correct' => false],
            ['text' => 'style-bold', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Kelas apa yang digunakan untuk mengubah warna teks menjadi biru standar?', [
            ['text' => 'color-blue-500', 'correct' => false],
            ['text' => 'text-blue-500', 'correct' => true],
            ['text' => 'font-blue-500', 'correct' => false],
            ['text' => 'bg-blue-500', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Untuk membuat sudut elemen menjadi benar-benar bulat (lingkaran), gunakan kelas...', [
            ['text' => 'rounded', 'correct' => false],
            ['text' => 'rounded-lg', 'correct' => false],
            ['text' => 'rounded-full', 'correct' => true],
            ['text' => 'circle', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Bagaimana cara menerapkan warna background HANYA saat mouse diarahkan ke elemen (hover)?', [
            ['text' => 'bg-red-500-hover', 'correct' => false],
            ['text' => 'hover:bg-red-500', 'correct' => true],
            ['text' => 'on-hover:bg-red-500', 'correct' => false],
            ['text' => 'bg-hover-red-500', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Kelas "shadow-lg" digunakan untuk memberikan efek apa?', [
            ['text' => 'Bayangan teks (Text Shadow)', 'correct' => false],
            ['text' => 'Bayangan kotak (Box Shadow) berukuran besar', 'correct' => true],
            ['text' => 'Garis tepi (Border)', 'correct' => false],
            ['text' => 'Efek transparan (Opacity)', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Untuk membuat huruf pertama setiap kata menjadi kapital, gunakan kelas...', [
            ['text' => 'uppercase', 'correct' => false],
            ['text' => 'lowercase', 'correct' => false],
            ['text' => 'capitalize', 'correct' => true],
            ['text' => 'text-caps', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Kelas manakah yang digunakan untuk mengatur ketebalan border?', [
            ['text' => 'border-4', 'correct' => true],
            ['text' => 'border-width-4', 'correct' => false],
            ['text' => 'stroke-4', 'correct' => false],
            ['text' => 'outline-4', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Apa fungsi dari kelas "opacity-50"?', [
            ['text' => 'Membuat elemen 50% lebih kecil.', 'correct' => false],
            ['text' => 'Membuat elemen menjadi 50% transparan.', 'correct' => true],
            ['text' => 'Memutar elemen 50 derajat.', 'correct' => false],
            ['text' => 'Mengubah warna menjadi abu-abu.', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Manakah kelas untuk membuat background gradient dari kiri ke kanan?', [
            ['text' => 'bg-gradient-to-r', 'correct' => true],
            ['text' => 'bg-gradient-to-l', 'correct' => false],
            ['text' => 'bg-gradient-to-b', 'correct' => false],
            ['text' => 'linear-gradient-right', 'correct' => false],
        ]);

        $this->createQuestion($bab3, 'Untuk menghilangkan garis bawah pada link (anchor), gunakan kelas...', [
            ['text' => 'no-underline', 'correct' => true],
            ['text' => 'decoration-none', 'correct' => false],
            ['text' => 'text-plain', 'correct' => false],
            ['text' => 'remove-decoration', 'correct' => false],
        ]);


        // =================================================================
        // EVALUASI AKHIR (GABUNGAN BAB 1-3) - 20 SOAL
        // =================================================================
        // Anggap ID Chapter untuk Evaluasi Akhir adalah 99 atau sesuai kebutuhan
        $evaluasi = 99; 

        // Soal 1-5: Konsep Dasar
        $this->createQuestion($evaluasi, 'Dalam konsep Utility-First, bagaimana cara kita membuat style?', [
            ['text' => 'Menulis CSS kustom di file terpisah.', 'correct' => false],
            ['text' => 'Menggabungkan class-class kecil langsung di HTML.', 'correct' => true],
            ['text' => 'Menggunakan tag style inline.', 'correct' => false],
            ['text' => 'Menggunakan preprocessor SASS.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Satuan ukuran default yang digunakan Tailwind (seperti p-4, m-2) adalah...', [
            ['text' => 'Pixel (px)', 'correct' => false],
            ['text' => 'REM (root em)', 'correct' => true],
            ['text' => 'Persentase (%)', 'correct' => false],
            ['text' => 'Point (pt)', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Class "hidden" pada Tailwind setara dengan...', [
            ['text' => 'visibility: hidden', 'correct' => false],
            ['text' => 'display: none', 'correct' => true],
            ['text' => 'opacity: 0', 'correct' => false],
            ['text' => 'z-index: -1', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Bagaimana cara membuat elemen responsif yang hanya muncul di layar desktop (lg)?', [
            ['text' => 'hidden lg:block', 'correct' => true],
            ['text' => 'block lg:hidden', 'correct' => false],
            ['text' => 'visible-lg', 'correct' => false],
            ['text' => 'desktop:show', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Breakpoint "md" di Tailwind secara default menargetkan lebar layar minimal...', [
            ['text' => '640px', 'correct' => false],
            ['text' => '768px', 'correct' => true],
            ['text' => '1024px', 'correct' => false],
            ['text' => '1280px', 'correct' => false],
        ]);

        // Soal 6-12: Layouting (Flex & Grid)
        $this->createQuestion($evaluasi, 'Kombinasi kelas untuk memusatkan item Flexbox secara total (tengah vertikal & horizontal)?', [
            ['text' => 'flex justify-center items-center', 'correct' => true],
            ['text' => 'flex align-middle text-center', 'correct' => false],
            ['text' => 'flex center-all', 'correct' => false],
            ['text' => 'flex justify-between items-start', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Apa efek dari "flex-row-reverse"?', [
            ['text' => 'Menyusun item dari bawah ke atas.', 'correct' => false],
            ['text' => 'Menyusun item secara horizontal dari kanan ke kiri.', 'correct' => true],
            ['text' => 'Membalik warna item.', 'correct' => false],
            ['text' => 'Menghapus item flex.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Untuk membuat grid responsif: 1 kolom di HP dan 3 kolom di tablet ke atas, kodenya adalah...', [
            ['text' => 'grid-cols-1 md:grid-cols-3', 'correct' => true],
            ['text' => 'grid-cols-3 mobile:grid-cols-1', 'correct' => false],
            ['text' => 'cols-1 cols-md-3', 'correct' => false],
            ['text' => 'grid-responsive-3', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Kelas "space-y-4" berfungsi untuk...', [
            ['text' => 'Memberi margin atas pada semua elemen anak kecuali yang pertama.', 'correct' => true],
            ['text' => 'Memberi padding vertikal 1rem.', 'correct' => false],
            ['text' => 'Memberi jarak antar huruf secara vertikal.', 'correct' => false],
            ['text' => 'Membuat spasi baris (line-height).', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Posisi "sticky" akan membuat elemen...', [
            ['text' => 'Selalu diam di posisi awal.', 'correct' => false],
            ['text' => 'Menempel pada sisi layar saat di-scroll melewati batas tertentu.', 'correct' => true],
            ['text' => 'Melayang di atas elemen lain secara permanen.', 'correct' => false],
            ['text' => 'Hilang saat di-scroll.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Kelas "z-50" digunakan untuk...', [
            ['text' => 'Zoom level 50%.', 'correct' => false],
            ['text' => 'Mengatur tumpukan elemen (z-index) agar berada di atas.', 'correct' => true],
            ['text' => 'Memberi jarak 50 unit.', 'correct' => false],
            ['text' => 'Mengatur transparansi.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Class "object-cover" biasanya digunakan pada tag <img> untuk...', [
            ['text' => 'Membuat gambar menjadi hitam putih.', 'correct' => false],
            ['text' => 'Menjaga rasio aspek gambar sambil memenuhi container (crop jika perlu).', 'correct' => true],
            ['text' => 'Memaksa gambar gepeng sesuai container.', 'correct' => false],
            ['text' => 'Membuat gambar menjadi background.', 'correct' => false],
        ]);

        // Soal 13-20: Styling & Effects
        $this->createQuestion($evaluasi, 'Bagaimana cara membuat efek transisi halus saat warna berubah?', [
            ['text' => 'transition duration-300', 'correct' => true],
            ['text' => 'animate-smooth', 'correct' => false],
            ['text' => 'effect-fade', 'correct' => false],
            ['text' => 'transform-color', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Class "cursor-pointer" akan mengubah kursor mouse menjadi...', [
            ['text' => 'Tanda panah standar.', 'correct' => false],
            ['text' => 'Ikon tangan menunjuk (hand).', 'correct' => true],
            ['text' => 'Ikon teks (I-beam).', 'correct' => false],
            ['text' => 'Ikon loading.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Untuk membuat elemen menjadi lingkaran penuh, kita menggunakan border-radius sejauh...', [
            ['text' => '50%', 'correct' => false],
            ['text' => '9999px (rounded-full)', 'correct' => true],
            ['text' => '100px', 'correct' => false],
            ['text' => '360deg', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Warna "bg-transparent" berarti...', [
            ['text' => 'Warna putih.', 'correct' => false],
            ['text' => 'Tidak memiliki warna latar belakang (tembus pandang).', 'correct' => true],
            ['text' => 'Warna hitam pekat.', 'correct' => false],
            ['text' => 'Warna default browser.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Kelas "tracking-widest" mempengaruhi properti CSS...', [
            ['text' => 'word-spacing', 'correct' => false],
            ['text' => 'letter-spacing', 'correct' => true],
            ['text' => 'line-height', 'correct' => false],
            ['text' => 'text-indent', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Efek "hover:scale-110" akan membuat elemen...', [
            ['text' => 'Berputar 110 derajat saat disorot.', 'correct' => false],
            ['text' => 'Membesar 10% saat disorot mouse.', 'correct' => true],
            ['text' => 'Pindah posisi ke kanan.', 'correct' => false],
            ['text' => 'Menjadi transparan.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Apa fungsi dari "ring-2 ring-blue-500"?', [
            ['text' => 'Membuat lingkaran biru.', 'correct' => false],
            ['text' => 'Menambahkan outline/box-shadow solid di luar elemen berwarna biru.', 'correct' => true],
            ['text' => 'Membuat elemen berdering.', 'correct' => false],
            ['text' => 'Mengubah warna border menjadi biru.', 'correct' => false],
        ]);
        $this->createQuestion($evaluasi, 'Pseudo-class "focus:outline-none" biasanya digunakan pada input form untuk...', [
            ['text' => 'Menghilangkan garis biru default browser saat diklik.', 'correct' => true],
            ['text' => 'Membuat input tidak bisa diklik.', 'correct' => false],
            ['text' => 'Menghapus border input secara permanen.', 'correct' => false],
            ['text' => 'Mengubah warna teks input.', 'correct' => false],
        ]);

    }

    /**
     * Helper function untuk membuat pertanyaan dan opsi jawaban.
     */
    private function createQuestion($chapter, $text, $options)
    {
        $q = QuizQuestion::create([
            'chapter_id' => $chapter,
            'question_text' => $text
        ]);

        foreach ($options as $opt) {
            QuizOption::create([
                'quiz_question_id' => $q->id,
                'option_text' => $opt['text'],
                'is_correct' => $opt['correct']
            ]);
        }
    }
}