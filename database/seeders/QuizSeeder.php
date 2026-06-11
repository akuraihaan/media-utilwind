<?php

namespace Database\Seeders;

use App\Models\QuizOption;
use App\Models\QuizQuestion;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use InvalidArgumentException;

class QuizSeeder extends Seeder
{
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        QuizOption::truncate();
        QuizQuestion::truncate();
        Schema::enableForeignKeyConstraints();

        DB::transaction(function () {
            foreach ($this->questions() as $question) {
                $this->createQuestion(
                    $question['chapter'],
                    $question['text'],
                    $question['options'],
                    $question['answer']
                );
            }
        });
    }

    private function questions(): array
    {
        return [
            // BAB 1
            [
                'chapter' => 1,
                'answer' => 'B',
                'text' => 'Peran utama HTML dalam pembuatan halaman web adalah...',
                'options' => [
                    'A' => 'Mengatur warna latar halaman',
                    'B' => 'Menyusun struktur dan isi halaman',
                    'C' => 'Memberi bayangan pada elemen',
                    'D' => 'Mengatur ukuran layar perangkat',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'B',
                'text' => 'Bagian HTML yang berisi konten yang tampil langsung kepada pengguna adalah ....',
                'options' => [
                    'A' => '<head>',
                    'B' => '<body>',
                    'C' => '<meta>',
                    'D' => '<title>',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'C',
                'text' => 'Perhatikan kode berikut: <a href="https://www.example.com">Kunjungi</a>. Fungsi atribut href pada kode tersebut adalah ....',
                'options' => [
                    'A' => 'Memberi warna pada teks tautan',
                    'B' => 'Membuat teks menjadi tebal',
                    'C' => 'Menentukan alamat tujuan tautan',
                    'D' => 'Menampilkan gambar pada halaman',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'B',
                'text' => 'Sebuah halaman memiliki bagian atas, menu navigasi, isi utama, dan bagian bawah. Susunan tag semantik yang paling tepat antara lain...',
                'options' => [
                    'A' => '<div>, <span>, <br>, dan <style>',
                    'B' => '<header>, <nav>, <main>, dan <footer>',
                    'C' => '<meta>, <title>, <link>, dan <script>',
                    'D' => '<p>, <a>, <img>, dan <input>',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'C',
                'text' => 'Perhatikan kode tombol berikut: <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">Simpan</button>. Bagian yang mengatur padding kiri dan kanan adalah ....',
                'options' => [
                    'A' => 'bg-blue-600',
                    'B' => 'text-white',
                    'C' => 'px-4',
                    'D' => 'rounded-lg',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'B',
                'text' => 'Perhatikan class rounded-lg pada komponen kartu. Class rounded-lg digunakan untuk...',
                'options' => [
                    'A' => 'Memberi bayangan pada kartu',
                    'B' => 'Membuat sudut kartu melengkung',
                    'C' => 'Memberi warna latar putih',
                    'D' => 'Memberi padding pada kartu',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'C',
                'text' => 'Perhatikan alur kerja Tailwind CSS. Makna alur tersebut adalah ....',
                'options' => [
                    'A' => 'input.css langsung dibuka di browser sebagai halaman utama',
                    'B' => 'index.html diproses menjadi input.css',
                    'C' => 'output.css menjadi file CSS hasil yang digunakan oleh HTML',
                    'D' => 'package.json menggantikan fungsi output.css',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'A',
                'text' => 'Dalam instalasi Tailwind CSS menggunakan Tailwind CLI, file output.css berfungsi sebagai ...',
                'options' => [
                    'A' => 'File hasil build yang dihubungkan ke HTML',
                    'B' => 'File sumber yang berisi struktur HTML',
                    'C' => 'File untuk menyimpan perintah NPM',
                    'D' => 'File untuk mengganti package.json',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'B',
                'text' => 'Perhatikan konfigurasi warna sekolah. Setelah konfigurasi diproses, class yang tepat untuk menggunakan warna tersebut sebagai latar adalah ....',
                'options' => [
                    'A' => 'bg-color-sekolah',
                    'B' => 'bg-sekolah-500',
                    'C' => 'theme-sekolah-500',
                    'D' => 'warna-sekolah-500',
                ],
            ],
            [
                'chapter' => 1,
                'answer' => 'A',
                'text' => 'Seseorang ingin mencoba Tailwind CSS tanpa instalasi. Langkah yang paling tepat yaitu....',
                'options' => [
                    'A' => 'menambahkan script CDN Tailwind CSS pada bagian <head>',
                    'B' => 'membuat file output.css terlebih dahulu',
                    'C' => 'menjalankan Tailwind CLI sebelum membuat HTML',
                    'D' => 'menulis konfigurasi @theme pada package.json',
                ],
            ],

            // BAB 2
            [
                'chapter' => 2,
                'answer' => 'B',
                'text' => 'Fungsi utama layout dalam halaman web adalah ....',
                'options' => [
                    'A' => 'Menghapus elemen yang tidak digunakan pada halaman',
                    'B' => 'Mengatur susunan elemen agar halaman rapi dan mudah dibaca',
                    'C' => 'Mengubah semua teks menjadi gambar agar lebih menarik',
                    'D' => 'Menentukan jenis database yang digunakan oleh halaman web',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'C',
                'text' => 'Pada struktur halaman web, bagian yang paling tepat digunakan untuk menampung isi utama halaman adalah....',
                'options' => [
                    'A' => '<header>',
                    'B' => '<nav>',
                    'C' => '<main>',
                    'D' => '<footer>',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'A',
                'text' => 'Class Tailwind CSS yang paling tepat digunakan agar isi elemen tidak menempel pada tepi adalah....',
                'options' => [
                    'A' => 'p-6',
                    'B' => 'mx-auto',
                    'C' => 'grid-cols-3',
                    'D' => 'justify-between',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'A',
                'text' => 'Sebuah menu berisi Profil, Pesanan, dan Keluar perlu disusun dari atas ke bawah dengan jarak yang cukup. Susunan class yang paling tepat adalah....',
                'options' => [
                    'A' => 'flex flex-col gap-3',
                    'B' => 'grid grid-cols-3 gap-3',
                    'C' => 'flex flex-row justify-between',
                    'D' => 'block mx-auto max-w-md',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'B',
                'text' => 'Judul berada di kiri, menu berada di kanan, dan keduanya sejajar secara vertikal. Susunan class yang paling tepat untuk elemen <nav> adalah ....',
                'options' => [
                    'A' => 'flex flex-col gap-4',
                    'B' => 'flex items-center justify-between',
                    'C' => 'grid grid-cols-1 items-center',
                    'D' => 'block text-center justify-between',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'B',
                'text' => 'Perhatikan kode input pada navbar. Fungsi class flex-1 pada tag <input> tersebut yaitu...',
                'options' => [
                    'A' => 'Membuat input tersembunyi pada layar kecil',
                    'B' => 'Membuat input mengisi ruang kosong di antara judul dan tombol',
                    'C' => 'Membuat input berubah menjadi tiga kolom',
                    'D' => 'Membuat input berada di bawah tombol',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'B',
                'text' => 'Susunan class yang paling tepat untuk membuat daftar produk menjadi tiga kolom dengan jarak antar kartu adalah ....',
                'options' => [
                    'A' => 'flex flex-col gap-4',
                    'B' => 'grid grid-cols-3 gap-4',
                    'C' => 'grid grid-cols-1 col-span-3',
                    'D' => 'block max-w-md mx-auto',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'C',
                'text' => 'Pada Grid tiga kolom, class yang tepat agar produk utama menempati dua kolom adalah ....',
                'options' => [
                    'A' => 'grid-cols-2',
                    'B' => 'gap-2',
                    'C' => 'col-span-2',
                    'D' => 'flex-1',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'B',
                'text' => 'Class yang sesuai untuk membuat kartu tampil satu kolom di layar kecil, dua kolom di layar sedang, dan tiga kolom di layar besar adalah ....',
                'options' => [
                    'A' => 'grid grid-cols-3',
                    'B' => 'grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3',
                    'C' => 'flex flex-row gap-4',
                    'D' => 'hidden md:block lg:hidden',
                ],
            ],
            [
                'chapter' => 2,
                'answer' => 'A',
                'text' => 'Sebuah bagian halaman perlu memiliki padding kecil pada layar kecil, tetapi lebih besar pada layar sedang ke atas. Susunan class yang paling tepat adalah....',
                'options' => [
                    'A' => 'p-4 md:p-8',
                    'B' => 'gap-4 md:grid-cols-2',
                    'C' => 'flex-col md:flex-row',
                    'D' => 'grid-cols-1 lg:text-4xl',
                ],
            ],

            // BAB 3
            [
                'chapter' => 3,
                'answer' => 'B',
                'text' => 'Fungsi utama styling dalam halaman web adalah ....',
                'options' => [
                    'A' => 'Menghapus struktur HTML yang tidak digunakan',
                    'B' => 'Memperjelas tampilan elemen agar halaman lebih rapi dan mudah dibaca',
                    'C' => 'Mengubah semua elemen menjadi gambar',
                    'D' => 'Membuat halaman web tanpa memerlukan CSS',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Dalam Tailwind CSS, class font-mono paling tepat digunakan untuk ....',
                'options' => [
                    'A' => 'Teks yang berkaitan dengan kode atau tampilan huruf berjarak tetap',
                    'B' => 'Tombol utama yang harus berwarna biru',
                    'C' => 'Kartu produk yang memiliki bayangan',
                    'D' => 'Paragraf biasa yang harus selalu rata tengah',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Class Tailwind CSS yang tepat untuk mengatur jarak antarbaris adalah ....',
                'options' => [
                    'A' => 'leading-7',
                    'B' => 'rounded-lg',
                    'C' => 'bg-blue-600',
                    'D' => 'border-slate-300',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'B',
                'text' => 'Perhatikan kode berikut. Fungsi class font-bold pada kode tersebut adalah ....',
                'options' => [
                    'A' => 'Membuat teks menjadi berwarna',
                    'B' => 'Membuat teks menjadi tebal',
                    'C' => 'Membuat teks elemen melengkung',
                    'D' => 'Memberi bayangan pada teks',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Sebuah judul bagian ingin diletakkan di tengah area halaman. Class Tailwind CSS yang paling tepat digunakan adalah ....',
                'options' => [
                    'A' => 'text-center',
                    'B' => 'text-right',
                    'C' => 'font-bold',
                    'D' => 'leading-7',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Sebuah kotak informasi bertuliskan "Data berhasil disimpan" perlu diberi latar hijau muda dan teks hijau gelap. Kombinasi class yang paling tepat adalah ....',
                'options' => [
                    'A' => 'bg-green-100 text-green-700',
                    'B' => 'bg-red-100 text-red-700',
                    'C' => 'bg-blue-600 text-white',
                    'D' => 'border border-slate-300',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Class yang digunakan untuk memberi garis tepi pada elemen adalah....',
                'options' => [
                    'A' => 'border',
                    'B' => 'rounded',
                    'C' => 'shadow-md',
                    'D' => 'text-base',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Class Tailwind CSS yang tepat untuk membuat sudut tombol melengkung adalah....',
                'options' => [
                    'A' => 'rounded-lg',
                    'B' => 'shadow-lg',
                    'C' => 'leading-7',
                    'D' => 'font-semibold',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Sebuah paragraf deskripsi terlihat terlalu padat saat terdiri dari beberapa baris. Class Tailwind CSS yang dapat digunakan agar jarak antarbaris lebih nyaman adalah ....',
                'options' => [
                    'A' => 'leading-7',
                    'B' => 'rounded-xl',
                    'C' => 'shadow-md',
                    'D' => 'border',
                ],
            ],
            [
                'chapter' => 3,
                'answer' => 'A',
                'text' => 'Sebuah kartu produk perlu memiliki tampilan rapi. Judul dibuat besar dan tebal, deskripsi dibuat abu-abu, kartu berlatar putih, tombol berlatar biru, dan sudut kartu melengkung. Pilihan class yang paling sesuai adalah ....',
                'options' => [
                    'A' => 'bg-white rounded-xl text-2xl font-bold text-slate-600 bg-blue-600 text-white',
                    'B' => 'grid grid-cols-3 gap-4 col-span-2 flex-1 justify-between',
                    'C' => 'hidden md:block lg:grid p-0 text-left border-none',
                    'D' => 'font-mono leading-7 grid-cols-1 justify-center',
                ],
            ],

            // EVALUASI AKHIR
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Dalam pembuatan halaman web, HTML berperan untuk ...',
                'options' => [
                    'A' => 'menyusun struktur dan isi utama halaman web',
                    'B' => 'menyimpan semua file yang digunakan dalam halaman web',
                    'C' => 'menjalankan perintah untuk membuka halaman web',
                    'D' => 'mengirim halaman web agar tampil di mesin pencari',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'D',
                'text' => 'Bagian HTML yang berisi konten yang tampil langsung kepada pengguna adalah ...',
                'options' => [
                    'A' => '<head>',
                    'B' => '<meta>',
                    'C' => '<title>',
                    'D' => '<body>',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Perhatikan kode berikut: <a href="https://www.example.com">Kunjungi</a>. Fungsi atribut href pada kode tersebut adalah ...',
                'options' => [
                    'A' => 'menentukan alamat tujuan tautan',
                    'B' => 'memberi warna pada teks tautan',
                    'C' => 'membuat teks menjadi lebih tebal',
                    'D' => 'menampilkan gambar pada halaman',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Sebuah halaman memiliki bagian atas, menu navigasi, isi utama, dan bagian bawah. Susunan tag semantik yang paling tepat yaitu ....',
                'options' => [
                    'A' => '<header>, <nav>, <main>, dan <footer>',
                    'B' => '<div>, <span>, <br>, dan <style>',
                    'C' => '<meta>, <title>, <link>, dan <script>',
                    'D' => '<p>, <a>, <img>, dan <input>',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'C',
                'text' => 'Perhatikan kode berikut: <button class="tombol">Simpan</button> .tombol-utama { background-color: #2563eb; color: white; }. Tampilan tombol tidak berubah sesuai aturan CSS karena ....',
                'options' => [
                    'A' => 'elemen <button> harus diganti menjadi <a> agar dapat diberi warna',
                    'B' => 'property background-color seharusnya ditulis pada HTML',
                    'C' => 'nama class pada HTML tidak sesuai dengan selector CSS',
                    'D' => 'selector class hanya dapat digunakan pada elemen paragraf',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'C',
                'text' => 'Perhatikan kode berikut: <button class="bg-blue-600 text-white px-4 py-2 rounded-lg font-semibold">Simpan</button>. Pernyataan yang paling tepat tentang kode tersebut yaitu ...',
                'options' => [
                    'A' => 'semua tampilan tombol diatur oleh satu class utama',
                    'B' => 'class tersebut hanya digunakan untuk menandai struktur HTML',
                    'C' => 'setiap utility class mengatur bagian kecil dari tampilan tombol',
                    'D' => 'tampilan tombol tetap harus ditulis ulang pada file CSS terpisah',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Perhatikan kode tombol berikut: <button class="bg-blue-600 text-white rounded-lg">Simpan</button>. Tombol sudah memiliki warna latar, warna teks, dan sudut melengkung. Namun, ruang dalam tombol belum nyaman. Class yang paling tepat ditambahkan yaitu ....',
                'options' => [
                    'A' => 'px-4 py-2',
                    'B' => 'mt-4 mb-4',
                    'C' => 'shadow-md border',
                    'D' => 'text-center font-mono',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'B',
                'text' => 'Seseorang ingin mencoba Tailwind CSS tanpa instalasi. Langkah yang paling tepat dilakukan adalah ...',
                'options' => [
                    'A' => 'membuat file input.css, lalu menjalankan build',
                    'B' => 'menambahkan script CDN Tailwind CSS pada bagian <head>',
                    'C' => 'memasang package tailwindcss melalui NPM terlebih dahulu',
                    'D' => 'menulis nilai desain khusus menggunakan @theme',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Class Tailwind CSS sudah ditulis pada elemen HTML melalui CDN, tetapi tampilan belum berubah. Pemeriksaan awal yang paling tepat adalah...',
                'options' => [
                    'A' => 'memastikan script CDN ditulis dengan benar dan perangkat terhubung ke internet',
                    'B' => 'mengganti semua class Tailwind CSS dengan selector CSS biasa',
                    'C' => 'memindahkan semua class Tailwind CSS ke dalam bagian <head>',
                    'D' => 'menghubungkan output.css karena CDN tidak dapat langsung digunakan',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'B',
                'text' => 'Elemen input search harus memenuhi sisa padding navbar tanpa mengganggu tombol lain. Utilitas yang tepat adalah ...',
                'options' => [
                    'A' => 'shrink-0',
                    'B' => 'flex-1',
                    'C' => 'flex-wrap',
                    'D' => 'block',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'B',
                'text' => 'Dalam instalasi Tailwind CSS, alur kerja file yang tepat adalah ...',
                'options' => [
                    'A' => 'output.css diproses menjadi input.css, lalu dihubungkan ke HTML',
                    'B' => 'input.css diproses oleh Tailwind CLI menjadi output.css, lalu dihubungkan ke HTML',
                    'C' => 'index.html diproses menjadi output.css, lalu dihubungkan ke package.json',
                    'D' => 'package-lock.json diproses menjadi input.css, lalu dibuka di browser',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'B',
                'text' => 'Sebuah navbar memiliki judul di kiri dan menu di kanan. Keduanya perlu sejajar secara vertikal. Susunan class yang paling tepat untuk elemen pembungkus navbar adalah ...',
                'options' => [
                    'A' => 'flex flex-col gap-4',
                    'B' => 'flex items-center justify-between',
                    'C' => 'grid grid-cols-3 gap-4',
                    'D' => 'items-start justify-center text-center',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'C',
                'text' => 'Perhatikan kebutuhan layout berikut: Daftar kartu perlu tampil dalam tiga kolom. Setiap kartu perlu memiliki jarak agar tidak menempel. Susunan class Tailwind CSS yang paling sesuai yaitu...',
                'options' => [
                    'A' => 'flex flex-col gap-4',
                    'B' => 'grid grid-cols-1 gap-4',
                    'C' => 'grid grid-cols-3 gap-4',
                    'D' => 'grid col-span-3 gap-4',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'B',
                'text' => 'Pada Grid tiga kolom, satu kartu utama perlu dibuat lebih lebar dan menempati dua kolom. Class yang tepat ditulis pada kartu utama adalah ...',
                'options' => [
                    'A' => 'grid-cols-2',
                    'B' => 'col-span-2',
                    'C' => 'gap-2',
                    'D' => 'flex-1',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Perhatikan kebutuhan layout berikut: layar kecil kartu tampil satu kolom, layar sedang dua kolom, layar besar tiga kolom, dan jarak antar kartu tetap rapi. Susunan class yang paling tepat yaitu ...',
                'options' => [
                    'A' => 'grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3',
                    'B' => 'grid grid-cols-3 gap-4 md:grid-cols-2 lg:grid-cols-1',
                    'C' => 'flex flex-col gap-4 md:grid-cols-2 lg:grid-cols-3',
                    'D' => 'grid gap-4 flex-col md:flex-row lg:grid-cols-3',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'D',
                'text' => 'Sebuah proyek membutuhkan warna khusus bernama utama-500 agar dapat digunakan sebagai class bg-utama-500. Konfigurasi yang tepat ditulis pada @theme yaitu ...',
                'options' => [
                    'A' => '--radius-utama-500: #16a34a;',
                    'B' => '--shadow-utama-500: #16a34a;',
                    'C' => '--font-utama-500: #16a34a;',
                    'D' => '--color-utama-500: #16a34a;',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Sebuah kartu informasi perlu memiliki tampilan berikut: latar putih, sudut melengkung besar, bayangan sedang, judul besar dan tebal, deskripsi berwarna abu-abu. Susunan class yang paling tepat untuk membentuk tampilan tersebut yaitu ...',
                'options' => [
                    'A' => 'bg-white rounded-xl shadow-md text-3xl font-bold text-slate-600',
                    'B' => 'bg-white rounded-md shadow-none text-base font-medium text-slate-900',
                    'C' => 'bg-slate-100 rounded-full shadow-lg text-sm font-bold text-white',
                    'D' => 'bg-blue-600 text-white border-slate-300 font-mono leading-7',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Perhatikan potongan kode berikut: <p class="text-slate-600">Sepatu ini ringan untuk kegiatan harian. Bahannya nyaman digunakan dan cocok dipakai ke sekolah.</p>. Jika paragraf tersebut menjadi dua atau tiga baris, tampilannya masih terasa padat saat dibaca. Class yang paling tepat ditambahkan pada elemen <p> adalah ...',
                'options' => [
                    'A' => 'leading-7',
                    'B' => 'font-bold',
                    'C' => 'text-center',
                    'D' => 'font-mono',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Kartu informasi terlihat kurang menonjol dibanding area di sekitarnya. Pengguna menjadi sulit membedakan batas antara area halaman dan isi kartu. Perbaikan yang paling sesuai adalah....',
                'options' => [
                    'A' => 'mengganti latar kartu menjadi bg-white',
                    'B' => 'mengganti teks kartu menjadi text-white',
                    'C' => 'mengganti tombol menjadi bg-slate-100',
                    'D' => 'mengganti kartu menjadi text-slate-600',
                ],
            ],
            [
                'chapter' => 99,
                'answer' => 'A',
                'text' => 'Tombol B terlihat lebih halus karena sudutnya melengkung. Class Tailwind CSS yang sesuai untuk membuat bentuk tersebut adalah....',
                'options' => [
                    'A' => 'rounded-lg',
                    'B' => 'shadow-none',
                    'C' => 'text-slate-600',
                    'D' => 'bg-white',
                ],
            ],
        ];
    }

    private function createQuestion(int $chapter, string $text, array $options, string $answer): void
    {
        $expectedLabels = ['A', 'B', 'C', 'D'];
        $labels = array_keys($options);
        sort($labels);

        if ($labels !== $expectedLabels) {
            throw new InvalidArgumentException("Soal chapter {$chapter} harus memiliki opsi A, B, C, dan D.");
        }

        if (!array_key_exists($answer, $options)) {
            throw new InvalidArgumentException("Kunci {$answer} tidak ditemukan untuk soal: {$text}");
        }

        $question = QuizQuestion::create([
            'chapter_id' => $chapter,
            'question_text' => $text,
        ]);

        foreach ($expectedLabels as $label) {
            QuizOption::create([
                'quiz_question_id' => $question->id,
                'option_text' => $options[$label],
                'is_correct' => $label === $answer,
            ]);
        }
    }
}
