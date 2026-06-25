<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class RefreshModuleQuizQuestionsSeeder extends Seeder
{
    private string $regularFont = '';
    private string $boldFont = '';
    private string $monoFont = '';

    public function run(): void
    {
        $this->prepareFonts();
        $media = $this->createMediaAssets();
        $questions = $this->questions($media);
        $now = now();

        DB::transaction(function () use ($questions, $now) {
            $oldQuestionIds = DB::table('quiz_questions')
                ->whereIn('chapter_id', [1, 2, 3])
                ->pluck('id');

            if ($oldQuestionIds->isNotEmpty()) {
                DB::table('quiz_attempt_answers')->whereIn('quiz_question_id', $oldQuestionIds)->delete();
                DB::table('quiz_options')->whereIn('quiz_question_id', $oldQuestionIds)->delete();
                DB::table('quiz_questions')->whereIn('id', $oldQuestionIds)->delete();
            }

            foreach ($questions as $question) {
                $media = $question['media'] ?? null;
                $questionId = DB::table('quiz_questions')->insertGetId([
                    'chapter_id' => $question['chapter_id'],
                    'learning_objective_code' => $question['tp_code'],
                    'learning_objective_title' => $question['tp_title'],
                    'remediation_hint' => $question['remediation_hint'],
                    'interaction_type' => $media ? 'image_context' : 'multiple_choice',
                    'interaction_prompt' => $media ? 'Amati media soal, lalu pilih jawaban yang paling tepat.' : null,
                    'media_type' => $media ? 'image' : null,
                    'media_url' => $media['url'] ?? null,
                    'media_path' => $media['path'] ?? null,
                    'media_caption' => $media['caption'] ?? null,
                    'question_text' => $question['question_text'],
                    'created_at' => $now,
                    'updated_at' => $now,
                ]);

                foreach ($question['options'] as $key => $optionText) {
                    DB::table('quiz_options')->insert([
                        'quiz_question_id' => $questionId,
                        'option_text' => $optionText,
                        'is_correct' => $key === $question['correct'] ? 1 : 0,
                        'created_at' => $now,
                        'updated_at' => $now,
                    ]);
                }
            }
        });
    }

    private function questions(array $media): array
    {
        return [
            $this->q(1, 'TP1', 'Peran utama HTML dalam pembuatan halaman web adalah...', [
                'A' => 'Mengatur warna latar halaman',
                'B' => 'Menyusun struktur dan isi halaman',
                'C' => 'Memberi bayangan pada elemen',
                'D' => 'Mengatur ukuran layar perangkat',
            ], 'B', null, 'Tinjau kembali peran HTML sebagai pembentuk struktur halaman.'),
            $this->q(1, 'TP1', 'Bagian HTML yang berisi konten yang tampil langsung kepada pengguna adalah...', [
                'A' => '<head>',
                'B' => '<meta>',
                'C' => '<title>',
                'D' => '<body>',
            ], 'D', null, 'Pelajari ulang perbedaan fungsi head dan body pada dokumen HTML.'),
            $this->q(1, 'TP1', 'Perhatikan kode tautan pada gambar. Fungsi atribut <code>href</code> pada kode tersebut adalah...', [
                'A' => 'Memberi warna pada teks tautan',
                'B' => 'Menentukan alamat tujuan tautan',
                'C' => 'Membuat teks menjadi tebal',
                'D' => 'Menampilkan gambar pada halaman',
            ], 'B', $media['bab1-q3-href'], 'Tinjau fungsi atribut href pada tag tautan HTML.'),
            $this->q(1, 'TP1', 'Sebuah halaman memiliki bagian atas, menu navigasi, isi utama, dan bagian bawah. Susunan tag semantik yang paling tepat antara lain...', [
                'A' => '<header>, <nav>, <main>, dan <footer>',
                'B' => '<div>, <span>, <br>, dan <style>',
                'C' => '<meta>, <title>, <link>, dan <script>',
                'D' => '<p>, <a>, <img>, dan <input>',
            ], 'A', null, 'Pelajari ulang tag semantik untuk menyusun bagian halaman.'),
            $this->q(1, 'TP2', 'Perhatikan kode tombol pada gambar. Bagian yang mengatur padding kiri dan kanan adalah...', [
                'A' => 'bg-blue-600',
                'B' => 'text-white',
                'C' => 'px-4',
                'D' => 'rounded-lg',
            ], 'C', $media['bab1-q5-padding'], 'Tinjau pola class Tailwind untuk padding horizontal.'),
            $this->q(1, 'TP3', 'Seseorang ingin mencoba Tailwind CSS tanpa instalasi. Langkah yang paling tepat yaitu...', [
                'A' => 'Menambahkan script CDN Tailwind CSS pada bagian <head>',
                'B' => 'Membuat file output.css terlebih dahulu',
                'C' => 'Menjalankan Tailwind CLI sebelum membuat HTML',
                'D' => 'Menulis konfigurasi @theme pada package.json',
            ], 'A', null, 'Pelajari kembali penggunaan CDN Tailwind untuk percobaan cepat.'),
            $this->q(1, 'TP2', 'Perhatikan kode kartu pada gambar. Class <code>rounded-lg</code> digunakan untuk...', [
                'A' => 'Memberi bayangan pada kartu',
                'B' => 'Memberi warna latar putih',
                'C' => 'Membuat sudut kartu melengkung',
                'D' => 'Memberi padding pada kartu',
            ], 'C', $media['bab1-q7-rounded'], 'Tinjau fungsi class rounded pada elemen Tailwind CSS.'),
            $this->q(1, 'TP4', 'Perhatikan alur pada gambar. Makna alur tersebut adalah...', [
                'A' => 'input.css langsung dibuka di browser sebagai halaman utama',
                'B' => 'output.css menjadi file CSS hasil yang digunakan oleh HTML',
                'C' => 'index.html diproses menjadi input.css',
                'D' => 'package.json menggantikan fungsi output.css',
            ], 'B', $media['bab1-q8-flow'], 'Pelajari kembali alur build Tailwind CLI dari input menuju output CSS.'),
            $this->q(1, 'TP4', 'Dalam instalasi Tailwind CSS menggunakan Tailwind CLI, file <code>output.css</code> berfungsi sebagai...', [
                'A' => 'File sumber yang berisi struktur HTML',
                'B' => 'File hasil build yang dihubungkan ke HTML',
                'C' => 'File untuk menyimpan perintah NPM',
                'D' => 'File untuk mengganti package.json',
            ], 'B', null, 'Tinjau kembali fungsi file hasil build pada instalasi Tailwind CLI.'),
            $this->q(1, 'TP4', 'Perhatikan konfigurasi warna pada gambar. Setelah konfigurasi diproses, class yang tepat untuk menggunakan warna tersebut sebagai latar adalah...', [
                'A' => 'bg-sekolah-500',
                'B' => 'bg-color-sekolah',
                'C' => 'theme-sekolah-500',
                'D' => 'warna-sekolah-500',
            ], 'A', $media['bab1-q10-color'], 'Pelajari kembali pola penamaan class warna dari konfigurasi Tailwind.'),

            $this->q(2, 'TP1', 'Fungsi utama layout dalam halaman web adalah...', [
                'A' => 'Menghapus elemen yang tidak digunakan pada halaman',
                'B' => 'Mengatur susunan elemen agar halaman rapi dan mudah dibaca',
                'C' => 'Mengubah semua teks menjadi gambar agar lebih menarik',
                'D' => 'Menentukan jenis database yang digunakan oleh halaman web',
            ], 'B', null, 'Tinjau konsep layout sebagai pengatur susunan elemen halaman.'),
            $this->q(2, 'TP1', 'Pada struktur halaman web, bagian yang paling tepat digunakan untuk menampung isi utama halaman adalah...', [
                'A' => '<header>',
                'B' => '<nav>',
                'C' => '<main>',
                'D' => '<footer>',
            ], 'C', null, 'Pelajari kembali fungsi tag main pada struktur halaman.'),
            $this->q(2, 'TP1', 'Perhatikan gambar berikut. Class Tailwind CSS yang paling tepat digunakan agar isi elemen tidak menempel pada tepi adalah...', [
                'A' => 'p-6',
                'B' => 'mx-auto',
                'C' => 'grid-cols-3',
                'D' => 'justify-between',
            ], 'A', $media['bab2-q3-padding'], 'Tinjau penggunaan padding untuk memberi ruang di dalam elemen.'),
            $this->q(2, 'TP2', 'Sebuah menu berisi Profil, Pesanan, dan Keluar perlu disusun dari atas ke bawah dengan jarak yang cukup. Susunan class yang paling tepat adalah...', [
                'A' => 'flex flex-col gap-3',
                'B' => 'grid grid-cols-3 gap-3',
                'C' => 'flex flex-row justify-between',
                'D' => 'block mx-auto max-w-md',
            ], 'A', null, 'Pelajari kembali flex-col dan gap untuk susunan vertikal.'),
            $this->q(2, 'TP2', 'Perhatikan rancangan navbar pada gambar. Judul berada di kiri, menu berada di kanan, dan keduanya sejajar secara vertikal. Susunan class yang paling tepat untuk elemen <code>&lt;nav&gt;</code> adalah...', [
                'A' => 'flex flex-col gap-4',
                'B' => 'flex items-center justify-between',
                'C' => 'grid grid-cols-1 items-center',
                'D' => 'block text-center justify-between',
            ], 'B', $media['bab2-q5-navbar'], 'Tinjau fungsi items-center dan justify-between pada navbar.'),
            $this->q(2, 'TP2', 'Perhatikan kode pada gambar. Fungsi class <code>flex-1</code> pada tag <code>&lt;input&gt;</code> tersebut yaitu...', [
                'A' => 'Membuat input tersembunyi pada layar kecil',
                'B' => 'Membuat input mengisi ruang kosong di antara judul dan tombol',
                'C' => 'Membuat input berubah menjadi tiga kolom',
                'D' => 'Membuat input berada di bawah tombol',
            ], 'B', $media['bab2-q6-flex1'], 'Pelajari kembali fungsi flex-1 dalam pembagian ruang fleksibel.'),
            $this->q(2, 'TP2', 'Perhatikan rancangan daftar produk pada gambar. Susunan class yang paling tepat untuk membuat daftar produk menjadi tiga kolom dengan jarak antar kartu adalah...', [
                'A' => 'flex flex-col gap-4',
                'B' => 'grid grid-cols-3 gap-4',
                'C' => 'grid grid-cols-1 col-span-3',
                'D' => 'block max-w-md mx-auto',
            ], 'B', $media['bab2-q7-grid'], 'Tinjau kembali grid-cols-3 dan gap untuk daftar kartu.'),
            $this->q(2, 'TP2', 'Perhatikan rancangan grid pada gambar. Pada grid tiga kolom, class yang tepat agar produk utama menempati dua kolom adalah...', [
                'A' => 'grid-cols-2',
                'B' => 'gap-2',
                'C' => 'col-span-2',
                'D' => 'flex-1',
            ], 'C', $media['bab2-q8-span'], 'Pelajari kembali fungsi col-span pada sistem grid.'),
            $this->q(2, 'TP3', 'Perhatikan gambar perubahan tampilan responsif. Class yang sesuai untuk membuat perubahan tersebut adalah...', [
                'A' => 'grid grid-cols-3',
                'B' => 'grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3',
                'C' => 'flex flex-row gap-4',
                'D' => 'hidden md:block lg:hidden',
            ], 'B', $media['bab2-q9-responsive'], 'Tinjau kembali breakpoint md dan lg untuk layout responsif.'),
            $this->q(2, 'TP3', 'Sebuah bagian halaman perlu memiliki padding kecil pada layar kecil, tetapi lebih besar pada layar sedang ke atas. Susunan class yang paling tepat adalah...', [
                'A' => 'p-4 md:p-8',
                'B' => 'gap-4 md:grid-cols-2',
                'C' => 'flex-col md:flex-row',
                'D' => 'grid-cols-1 lg:text-4xl',
            ], 'A', null, 'Pelajari kembali penggunaan prefix breakpoint untuk mengubah padding.'),

            $this->q(3, 'TP1', 'Fungsi utama styling dalam halaman web adalah...', [
                'A' => 'Menghapus struktur HTML yang tidak digunakan',
                'B' => 'Memperjelas tampilan elemen agar halaman lebih rapi dan mudah dibaca',
                'C' => 'Mengubah semua elemen menjadi gambar',
                'D' => 'Membuat halaman web tanpa memerlukan CSS',
            ], 'B', null, 'Tinjau fungsi styling dalam memperjelas tampilan halaman.'),
            $this->q(3, 'TP2', 'Dalam Tailwind CSS, class <code>font-mono</code> paling tepat digunakan untuk...', [
                'A' => 'Teks yang berkaitan dengan kode atau tampilan huruf berjarak tetap',
                'B' => 'Tombol utama yang harus berwarna biru',
                'C' => 'Kartu produk yang memiliki bayangan',
                'D' => 'Paragraf biasa yang harus selalu rata tengah',
            ], 'A', null, 'Pelajari kembali penggunaan font-mono untuk teks bergaya kode.'),
            $this->q(3, 'TP2', 'Perhatikan gambar berikut. Class Tailwind CSS yang tepat untuk mengatur jarak antarbaris adalah...', [
                'A' => 'leading-7',
                'B' => 'rounded-lg',
                'C' => 'bg-blue-600',
                'D' => 'border-slate-300',
            ], 'A', $media['bab3-q3-leading'], 'Tinjau fungsi leading untuk mengatur jarak antarbaris.'),
            $this->q(3, 'TP2', 'Perhatikan kode pada gambar. Fungsi class <code>font-bold</code> pada kode tersebut adalah...', [
                'A' => 'Membuat teks menjadi berwarna',
                'B' => 'Membuat teks menjadi tebal',
                'C' => 'Membuat teks elemen melengkung',
                'D' => 'Memberi bayangan pada teks',
            ], 'B', $media['bab3-q4-bold'], 'Pelajari kembali fungsi font-bold pada tipografi Tailwind.'),
            $this->q(3, 'TP2', 'Sebuah judul bagian ingin diletakkan di tengah area halaman. Class Tailwind CSS yang paling tepat digunakan adalah...', [
                'A' => 'text-center',
                'B' => 'text-right',
                'C' => 'font-bold',
                'D' => 'leading-7',
            ], 'A', null, 'Tinjau penggunaan text-center untuk perataan teks.'),
            $this->q(3, 'TP2', 'Sebuah kotak informasi bertuliskan "Data berhasil disimpan" perlu diberi latar hijau muda dan teks hijau gelap. Kombinasi class yang paling tepat adalah...', [
                'A' => 'bg-green-100 text-green-700',
                'B' => 'bg-red-100 text-red-700',
                'C' => 'bg-blue-600 text-white',
                'D' => 'border border-slate-300',
            ], 'A', null, 'Pelajari kembali kombinasi warna background dan teks pada Tailwind CSS.'),
            $this->q(3, 'TP2', 'Perhatikan gambar berikut. Class yang digunakan untuk memberi garis tepi pada elemen adalah...', [
                'A' => 'border',
                'B' => 'rounded',
                'C' => 'shadow-md',
                'D' => 'text-base',
            ], 'A', $media['bab3-q7-border'], 'Tinjau fungsi border untuk memberi garis tepi elemen.'),
            $this->q(3, 'TP2', 'Perhatikan gambar berikut. Class Tailwind CSS yang tepat untuk membuat sudut tombol melengkung adalah...', [
                'A' => 'rounded-lg',
                'B' => 'shadow-lg',
                'C' => 'leading-7',
                'D' => 'font-semibold',
            ], 'A', $media['bab3-q8-rounded-button'], 'Pelajari kembali fungsi rounded-lg pada bentuk tombol.'),
            $this->q(3, 'TP2', 'Sebuah paragraf deskripsi terlihat terlalu padat saat terdiri dari beberapa baris. Class Tailwind CSS yang dapat digunakan agar jarak antarbaris lebih nyaman adalah...', [
                'A' => 'leading-7',
                'B' => 'rounded-xl',
                'C' => 'shadow-md',
                'D' => 'border',
            ], 'A', null, 'Tinjau kembali pengaturan line-height menggunakan class leading.'),
            $this->q(3, 'TP2', 'Sebuah kartu produk perlu memiliki tampilan rapi. Judul dibuat besar dan tebal, deskripsi dibuat abu-abu, kartu berlatar putih, tombol berlatar biru, dan sudut kartu melengkung. Pilihan class yang paling sesuai adalah...', [
                'A' => 'bg-white rounded-xl text-2xl font-bold text-slate-600 bg-blue-600 text-white',
                'B' => 'grid grid-cols-3 gap-4 col-span-2 flex-1 justify-between',
                'C' => 'hidden md:block lg:grid p-0 text-left border-none',
                'D' => 'font-mono leading-7 grid-cols-1 justify-center',
            ], 'A', $media['bab3-q10-product'], 'Pelajari kembali kombinasi class untuk kartu, teks, dan tombol.'),
        ];
    }

    private function q(int $chapterId, string $tpCode, string $questionText, array $options, string $correct, ?array $media, string $remediationHint): array
    {
        $titles = [
            1 => [
                'TP1' => 'Menjelaskan fungsi HTML dan CSS dalam halaman web.',
                'TP2' => 'Memahami konsep dasar Tailwind CSS.',
                'TP3' => 'Menerapkan Tailwind CSS pada HTML melalui CDN.',
                'TP4' => 'Melakukan instalasi dan konfigurasi Tailwind CSS.',
            ],
            2 => [
                'TP1' => 'Memahami konsep layout dalam penyusunan elemen halaman web.',
                'TP2' => 'Menerapkan class flex dan grid untuk mengatur layout.',
                'TP3' => 'Mengatur layout responsif sederhana menggunakan breakpoint.',
            ],
            3 => [
                'TP1' => 'Memahami fungsi styling dalam memperjelas tampilan web.',
                'TP2' => 'Menerapkan class Tailwind CSS untuk mengatur tampilan elemen web.',
            ],
        ];

        return [
            'chapter_id' => $chapterId,
            'tp_code' => $tpCode,
            'tp_title' => $titles[$chapterId][$tpCode],
            'remediation_hint' => $remediationHint,
            'question_text' => $questionText,
            'options' => $options,
            'correct' => $correct,
            'media' => $media,
        ];
    }

    private function createMediaAssets(): array
    {
        $directory = public_path('uploads/quiz-media');
        File::ensureDirectoryExists($directory);

        $assets = [
            'bab1-q3-href' => ['title' => 'Kode Tautan HTML', 'caption' => 'Contoh tag tautan HTML; atribut href menentukan alamat tujuan.', 'type' => 'code', 'lines' => ['<a href="https://tailwindcss.com">', '  Dokumentasi Tailwind', '</a>']],
            'bab1-q5-padding' => ['title' => 'Kode Tombol Tailwind', 'caption' => 'Class px-4 mengatur padding kiri dan kanan tombol.', 'type' => 'code-button', 'lines' => ['<button class="bg-blue-600 text-white px-4 py-2 rounded-lg">', '  Simpan', '</button>']],
            'bab1-q7-rounded' => ['title' => 'Kartu dengan rounded-lg', 'caption' => 'Class rounded-lg membuat sudut kartu tampak melengkung.', 'type' => 'card', 'lines' => ['class="bg-white rounded-lg shadow p-4"']],
            'bab1-q8-flow' => ['title' => 'Alur Build Tailwind CLI', 'caption' => 'input.css diproses Tailwind CLI menjadi output.css, lalu dihubungkan ke HTML.', 'type' => 'flow', 'nodes' => ['input.css', 'Tailwind CLI', 'output.css', 'index.html']],
            'bab1-q10-color' => ['title' => 'Konfigurasi Warna Tailwind', 'caption' => 'Nama warna sekolah-500 dipakai dengan pola class bg-sekolah-500.', 'type' => 'code-swatch', 'lines' => ['colors: {', '  sekolah: { 500: "#2563eb" }', '}']],
            'bab2-q3-padding' => ['title' => 'Ruang Dalam Elemen', 'caption' => 'Class p-6 memberi jarak antara isi elemen dan tepi kotak.', 'type' => 'padding'],
            'bab2-q5-navbar' => ['title' => 'Rancangan Navbar', 'caption' => 'Judul di kiri dan menu di kanan dapat diatur dengan flex items-center justify-between.', 'type' => 'navbar'],
            'bab2-q6-flex1' => ['title' => 'Input Mengisi Ruang', 'caption' => 'Class flex-1 membuat input mengambil ruang kosong pada baris flex.', 'type' => 'flex1'],
            'bab2-q7-grid' => ['title' => 'Grid Tiga Kolom', 'caption' => 'grid grid-cols-3 gap-4 menyusun kartu produk menjadi tiga kolom.', 'type' => 'grid'],
            'bab2-q8-span' => ['title' => 'Grid dengan Produk Utama', 'caption' => 'Class col-span-2 membuat item menempati dua kolom pada grid.', 'type' => 'span'],
            'bab2-q9-responsive' => ['title' => 'Layout Responsif', 'caption' => 'Grid berubah dari 1 kolom, 2 kolom, lalu 3 kolom sesuai breakpoint.', 'type' => 'responsive'],
            'bab3-q3-leading' => ['title' => 'Jarak Antarbaris', 'caption' => 'Class leading-7 membuat paragraf beberapa baris lebih nyaman dibaca.', 'type' => 'leading'],
            'bab3-q4-bold' => ['title' => 'Kode Tipografi', 'caption' => 'Class font-bold membuat teks tampil lebih tebal.', 'type' => 'code-bold', 'lines' => ['<h2 class="text-xl font-bold">', '  Judul Materi', '</h2>']],
            'bab3-q7-border' => ['title' => 'Garis Tepi Elemen', 'caption' => 'Class border memberi garis tepi pada kotak informasi.', 'type' => 'border'],
            'bab3-q8-rounded-button' => ['title' => 'Tombol Melengkung', 'caption' => 'Class rounded-lg membuat sudut tombol melengkung.', 'type' => 'button'],
            'bab3-q10-product' => ['title' => 'Kartu Produk Sederhana', 'caption' => 'Kombinasi class mengatur kartu, judul, deskripsi, tombol, dan sudut elemen.', 'type' => 'product'],
        ];

        return collect($assets)->mapWithKeys(function (array $asset, string $key) use ($directory) {
            $filename = 'module-' . $key . '.png';
            $path = 'quiz-media/' . $filename;
            $this->renderImage($directory . DIRECTORY_SEPARATOR . $filename, $asset);

            return [$key => [
                'path' => $path,
                'url' => '/uploads/' . $path,
                'caption' => $asset['caption'],
            ]];
        })->all();
    }

    private function renderImage(string $file, array $asset): void
    {
        $image = imagecreatetruecolor(1280, 720);
        imageantialias($image, true);

        $this->fill($image, '#f8fafc');
        $this->roundedRect($image, 46, 42, 1234, 678, 34, '#ffffff', '#dbe3ef');
        $this->roundedRect($image, 46, 42, 1234, 150, 34, '#eef2ff', '#dbe3ef');
        $this->text($image, $asset['title'], 30, 82, 94, '#0f172a', true);
        $this->text($image, $asset['caption'], 15, 84, 128, '#475569');

        match ($asset['type']) {
            'code' => $this->drawCode($image, $asset['lines']),
            'code-button' => $this->drawCodeButton($image, $asset['lines']),
            'code-swatch' => $this->drawCodeSwatch($image, $asset['lines']),
            'code-bold' => $this->drawCodeBold($image, $asset['lines']),
            'flow' => $this->drawFlow($image, $asset['nodes']),
            'card' => $this->drawCard($image),
            'padding' => $this->drawPadding($image),
            'navbar' => $this->drawNavbar($image),
            'flex1' => $this->drawFlex1($image),
            'grid' => $this->drawGrid($image),
            'span' => $this->drawSpanGrid($image),
            'responsive' => $this->drawResponsive($image),
            'leading' => $this->drawLeading($image),
            'border' => $this->drawBorder($image),
            'button' => $this->drawButton($image),
            'product' => $this->drawProduct($image),
            default => $this->drawCard($image),
        };

        imagepng($image, $file, 9);
        imagedestroy($image);
    }

    private function drawCode($image, array $lines): void
    {
        $this->roundedRect($image, 150, 230, 1130, 545, 24, '#0f172a', '#1e293b');
        foreach ($lines as $index => $line) {
            $this->text($image, $line, 24, 200, 305 + ($index * 54), '#dbeafe', false, true);
        }
    }

    private function drawCodeButton($image, array $lines): void
    {
        $this->drawCode($image, $lines);
        $this->roundedRect($image, 740, 398, 855, 448, 10, '#fef3c7', '#f59e0b');
        $this->text($image, 'px-4', 18, 773, 431, '#92400e', true);
        $this->roundedRect($image, 500, 575, 780, 635, 18, '#2563eb', '#1d4ed8');
        $this->centerText($image, 'Simpan', 20, 640, 613, '#ffffff', true);
    }

    private function drawCodeSwatch($image, array $lines): void
    {
        $this->drawCode($image, $lines);
        $this->roundedRect($image, 860, 310, 1028, 478, 24, '#2563eb', '#1d4ed8');
        $this->text($image, 'bg-sekolah-500', 22, 815, 535, '#1e3a8a', true, true);
    }

    private function drawCodeBold($image, array $lines): void
    {
        $this->drawCode($image, $lines);
        $this->text($image, 'Judul Materi', 42, 470, 620, '#0f172a', true);
    }

    private function drawFlow($image, array $nodes): void
    {
        $x = 105;
        foreach ($nodes as $index => $node) {
            $this->roundedRect($image, $x, 325, $x + 210, 430, 22, $index === 2 ? '#dcfce7' : '#eff6ff', '#bfdbfe');
            $this->centerText($image, $node, 20, $x + 105, 387, '#0f172a', true);
            if ($index < count($nodes) - 1) {
                $this->arrow($image, $x + 230, 378, $x + 305, 378, '#64748b');
            }
            $x += 295;
        }
        $this->text($image, 'File hasil build dipakai oleh HTML', 20, 455, 515, '#166534', true);
    }

    private function drawCard($image): void
    {
        $this->roundedRect($image, 430, 225, 850, 555, 34, '#ffffff', '#cbd5e1');
        $this->roundedRect($image, 480, 285, 800, 348, 12, '#e0e7ff', '#c7d2fe');
        $this->text($image, 'Kartu Materi', 28, 500, 420, '#0f172a', true);
        $this->text($image, 'Sudut kartu terlihat melengkung.', 18, 500, 462, '#64748b');
        $this->text($image, 'rounded-lg', 24, 560, 610, '#4f46e5', true, true);
    }

    private function drawPadding($image): void
    {
        $this->roundedRect($image, 330, 230, 950, 560, 24, '#e0f2fe', '#7dd3fc');
        $this->roundedRect($image, 460, 330, 820, 460, 20, '#ffffff', '#38bdf8');
        $this->text($image, 'Isi elemen tidak menempel pada tepi', 22, 482, 405, '#0f172a', true);
        $this->text($image, 'p-6', 34, 615, 615, '#0369a1', true, true);
        $this->arrow($image, 410, 300, 455, 330, '#0284c7');
        $this->arrow($image, 870, 500, 825, 460, '#0284c7');
    }

    private function drawNavbar($image): void
    {
        $this->roundedRect($image, 170, 300, 1110, 420, 26, '#ffffff', '#cbd5e1');
        $this->text($image, 'Utilwind', 30, 220, 375, '#1e40af', true);
        foreach (['Materi', 'Kuis', 'Profil'] as $i => $label) {
            $this->text($image, $label, 20, 770 + ($i * 105), 375, '#334155', true);
        }
        $this->text($image, 'flex items-center justify-between', 24, 410, 530, '#4f46e5', true, true);
    }

    private function drawFlex1($image): void
    {
        $this->roundedRect($image, 160, 300, 1120, 430, 24, '#ffffff', '#cbd5e1');
        $this->text($image, 'Cari', 24, 210, 380, '#0f172a', true);
        $this->roundedRect($image, 330, 330, 900, 392, 16, '#f8fafc', '#94a3b8');
        $this->text($image, 'input.flex-1', 20, 530, 370, '#64748b', true, true);
        $this->roundedRect($image, 930, 330, 1060, 392, 16, '#2563eb', '#1d4ed8');
        $this->centerText($image, 'Cari', 18, 995, 370, '#ffffff', true);
    }

    private function drawGrid($image): void
    {
        for ($i = 0; $i < 3; $i++) {
            $x = 215 + ($i * 300);
            $this->roundedRect($image, $x, 250, $x + 250, 500, 22, '#ffffff', '#cbd5e1');
            $this->roundedRect($image, $x + 30, 285, $x + 220, 350, 14, '#e0e7ff', '#c7d2fe');
            $this->text($image, 'Produk ' . ($i + 1), 22, $x + 55, 420, '#0f172a', true);
        }
        $this->text($image, 'grid grid-cols-3 gap-4', 24, 485, 590, '#4f46e5', true, true);
    }

    private function drawSpanGrid($image): void
    {
        $this->roundedRect($image, 190, 240, 760, 475, 22, '#dbeafe', '#60a5fa');
        $this->centerText($image, 'Produk Utama', 28, 475, 365, '#1e3a8a', true);
        $this->roundedRect($image, 790, 240, 1065, 475, 22, '#ffffff', '#cbd5e1');
        $this->centerText($image, 'Produk 2', 24, 928, 365, '#0f172a', true);
        $this->text($image, 'col-span-2', 28, 540, 570, '#1d4ed8', true, true);
    }

    private function drawResponsive($image): void
    {
        $labels = ['sm: 1 kolom', 'md: 2 kolom', 'lg: 3 kolom'];
        $cols = [1, 2, 3];
        foreach ($cols as $i => $count) {
            $x = 120 + ($i * 370);
            $this->text($image, $labels[$i], 18, $x + 40, 235, '#334155', true);
            $this->roundedRect($image, $x, 270, $x + 290, 545, 18, '#ffffff', '#cbd5e1');
            for ($j = 0; $j < $count; $j++) {
                $cellW = (250 - (($count - 1) * 12)) / $count;
                $cx = $x + 20 + ($j * ($cellW + 12));
                $this->roundedRect($image, (int) $cx, 330, (int) ($cx + $cellW), 455, 12, '#e0f2fe', '#7dd3fc');
            }
        }
    }

    private function drawLeading($image): void
    {
        $this->roundedRect($image, 260, 235, 1020, 565, 22, '#ffffff', '#cbd5e1');
        foreach ([0, 1, 2, 3] as $i) {
            $this->roundedRect($image, 340, 310 + ($i * 55), 940, 325 + ($i * 55), 8, '#94a3b8', '#94a3b8');
        }
        $this->text($image, 'leading-7', 30, 560, 620, '#4f46e5', true, true);
    }

    private function drawBorder($image): void
    {
        $this->roundedRect($image, 360, 260, 920, 520, 26, '#ffffff', '#2563eb');
        imagesetthickness($image, 7);
        imagerectangle($image, 360, 260, 920, 520, $this->color($image, '#2563eb'));
        imagesetthickness($image, 1);
        $this->centerText($image, 'Kotak informasi', 30, 640, 395, '#0f172a', true);
        $this->text($image, 'border', 30, 590, 610, '#2563eb', true, true);
    }

    private function drawButton($image): void
    {
        $this->roundedRect($image, 430, 305, 850, 445, 34, '#2563eb', '#1d4ed8');
        $this->centerText($image, 'Simpan Data', 32, 640, 392, '#ffffff', true);
        $this->text($image, 'rounded-lg', 30, 555, 560, '#1d4ed8', true, true);
    }

    private function drawProduct($image): void
    {
        $this->roundedRect($image, 405, 220, 875, 580, 30, '#ffffff', '#cbd5e1');
        $this->roundedRect($image, 455, 270, 825, 360, 18, '#dbeafe', '#bfdbfe');
        $this->text($image, 'Produk Premium', 30, 455, 430, '#0f172a', true);
        $this->text($image, 'Deskripsi abu-abu dan mudah dibaca.', 19, 455, 472, '#64748b');
        $this->roundedRect($image, 455, 505, 650, 558, 14, '#2563eb', '#1d4ed8');
        $this->centerText($image, 'Beli', 18, 552, 540, '#ffffff', true);
    }

    private function prepareFonts(): void
    {
        $this->regularFont = $this->firstExisting([
            'C:\\Windows\\Fonts\\arial.ttf',
            'C:\\Windows\\Fonts\\segoeui.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans.ttf',
        ]);
        $this->boldFont = $this->firstExisting([
            'C:\\Windows\\Fonts\\arialbd.ttf',
            'C:\\Windows\\Fonts\\seguisb.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSans-Bold.ttf',
        ]) ?: $this->regularFont;
        $this->monoFont = $this->firstExisting([
            'C:\\Windows\\Fonts\\consola.ttf',
            '/usr/share/fonts/truetype/dejavu/DejaVuSansMono.ttf',
        ]) ?: $this->regularFont;
    }

    private function firstExisting(array $paths): string
    {
        foreach ($paths as $path) {
            if (is_file($path)) {
                return $path;
            }
        }

        return '';
    }

    private function fill($image, string $hex): void
    {
        imagefilledrectangle($image, 0, 0, imagesx($image), imagesy($image), $this->color($image, $hex));
    }

    private function color($image, string $hex): int
    {
        $hex = ltrim($hex, '#');

        return imagecolorallocate($image, hexdec(substr($hex, 0, 2)), hexdec(substr($hex, 2, 2)), hexdec(substr($hex, 4, 2)));
    }

    private function text($image, string $text, int $size, int $x, int $y, string $hex, bool $bold = false, bool $mono = false): void
    {
        $font = $mono ? $this->monoFont : ($bold ? $this->boldFont : $this->regularFont);
        $color = $this->color($image, $hex);

        if ($font) {
            imagettftext($image, $size, 0, $x, $y, $color, $font, $text);
            return;
        }

        imagestring($image, 5, $x, $y - 18, $text, $color);
    }

    private function centerText($image, string $text, int $size, int $centerX, int $baselineY, string $hex, bool $bold = false): void
    {
        $font = $bold ? $this->boldFont : $this->regularFont;
        if ($font) {
            $box = imagettfbbox($size, 0, $font, $text);
            $width = abs($box[4] - $box[0]);
            $this->text($image, $text, $size, (int) ($centerX - ($width / 2)), $baselineY, $hex, $bold);
            return;
        }

        $this->text($image, $text, $size, $centerX - 60, $baselineY, $hex, $bold);
    }

    private function roundedRect($image, int $x1, int $y1, int $x2, int $y2, int $radius, string $fill, string $stroke): void
    {
        $fillColor = $this->color($image, $fill);
        $strokeColor = $this->color($image, $stroke);

        imagefilledrectangle($image, $x1 + $radius, $y1, $x2 - $radius, $y2, $fillColor);
        imagefilledrectangle($image, $x1, $y1 + $radius, $x2, $y2 - $radius, $fillColor);
        imagefilledellipse($image, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, $fillColor);
        imagefilledellipse($image, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, $fillColor);
        imagefilledellipse($image, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, $fillColor);
        imagefilledellipse($image, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, $fillColor);

        imageline($image, $x1 + $radius, $y1, $x2 - $radius, $y1, $strokeColor);
        imageline($image, $x2, $y1 + $radius, $x2, $y2 - $radius, $strokeColor);
        imageline($image, $x1 + $radius, $y2, $x2 - $radius, $y2, $strokeColor);
        imageline($image, $x1, $y1 + $radius, $x1, $y2 - $radius, $strokeColor);
        imagearc($image, $x1 + $radius, $y1 + $radius, $radius * 2, $radius * 2, 180, 270, $strokeColor);
        imagearc($image, $x2 - $radius, $y1 + $radius, $radius * 2, $radius * 2, 270, 360, $strokeColor);
        imagearc($image, $x2 - $radius, $y2 - $radius, $radius * 2, $radius * 2, 0, 90, $strokeColor);
        imagearc($image, $x1 + $radius, $y2 - $radius, $radius * 2, $radius * 2, 90, 180, $strokeColor);
    }

    private function arrow($image, int $x1, int $y1, int $x2, int $y2, string $hex): void
    {
        $color = $this->color($image, $hex);
        imagesetthickness($image, 4);
        imageline($image, $x1, $y1, $x2, $y2, $color);
        imagefilledpolygon($image, [$x2, $y2, $x2 - 14, $y2 - 9, $x2 - 14, $y2 + 9], 3, $color);
        imagesetthickness($image, 1);
    }
}
