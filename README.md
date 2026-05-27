# Utilwind

Utilwind adalah aplikasi media pembelajaran interaktif berbasis web untuk membantu siswa mempelajari dasar HTML, CSS, dan Tailwind CSS. Aplikasi ini dirancang sebagai platform belajar terstruktur yang menyediakan materi per bab, latihan praktik, quiz, progres belajar, manajemen kelas, serta dashboard admin untuk memantau aktivitas siswa.

Proyek ini dibangun menggunakan Laravel dan Blade dengan dukungan Tailwind CSS serta Vite untuk proses pengembangan frontend.

## Daftar Isi

- [Fitur Utama](#fitur-utama)
- [Teknologi](#teknologi)
- [Struktur Proyek](#struktur-proyek)
- [Persyaratan Sistem](#persyaratan-sistem)
- [Instalasi](#instalasi)
- [Konfigurasi Environment](#konfigurasi-environment)
- [Menjalankan Aplikasi](#menjalankan-aplikasi)
- [Database dan Seeder](#database-dan-seeder)
- [Akun dan Hak Akses](#akun-dan-hak-akses)
- [Modul Pembelajaran](#modul-pembelajaran)
- [Ringkasan Route](#ringkasan-route)
- [Pengujian](#pengujian)
- [Catatan Pengembangan](#catatan-pengembangan)

## Fitur Utama

### Siswa

- Registrasi, login, logout, dan reset password.
- Dashboard siswa untuk melihat ringkasan progres belajar.
- Bergabung ke kelas menggunakan token kelas.
- Akses materi pembelajaran setelah tergabung dalam kelas aktif.
- Penyelesaian materi per subbab.
- Quiz per bagian pembelajaran.
- Penyimpanan progres quiz selama pengerjaan.
- Lab praktik coding berbasis instruksi dan validasi jawaban.
- Sandbox untuk latihan mandiri.
- Pengelolaan profil pengguna.

### Admin

- Dashboard admin untuk melihat ringkasan data siswa dan pembelajaran.
- Manajemen pengguna.
- Import dan export data pengguna.
- Detail progres masing-masing siswa.
- Export laporan siswa dalam format CSV/PDF.
- Manajemen kelas dan token kelas.
- Aktivasi atau penonaktifan kelas.
- Manajemen soal quiz.
- Analitik soal quiz.
- Konfigurasi lab praktik.
- Analitik pengerjaan lab.
- Audit log untuk aktivitas administratif.

## Teknologi

- **Backend:** Laravel 12
- **Bahasa:** PHP 8.2+
- **Frontend:** Blade Template, Tailwind CSS 4
- **Build Tool:** Vite
- **Database:** MySQL
- **Package Manager Backend:** Composer
- **Package Manager Frontend:** npm
- **Testing:** PHPUnit

Dependency penting:

- `laravel/framework`
- `laravel/tinker`
- `phpoffice/phpword`
- `tailwindcss`
- `@tailwindcss/vite`
- `laravel-vite-plugin`
- `vite`
- `axios`

## Struktur Proyek

```text
app/
  Console/Commands/        Command tambahan seperti import modul dan sinkronisasi data
  Http/Controllers/        Controller utama aplikasi
  Http/Middleware/         Middleware autentikasi, admin, dan akses kelas
  Models/                  Model Eloquent

database/
  migrations/              Struktur tabel database
  seeders/                 Data awal untuk materi, quiz, lab, dan kelas

public/
  images/                  Aset gambar publik
  uploads/                 File upload seperti avatar dan foto profil

resources/
  views/                   Halaman Blade untuk siswa, admin, quiz, lab, auth, dan materi
  css/                     Entry CSS aplikasi
  js/                      Entry JavaScript aplikasi

routes/
  web.php                  Route utama aplikasi web
  api.php                  Route API
  console.php              Route command console
```

## Persyaratan Sistem

Pastikan perangkat sudah memiliki:

- PHP 8.2 atau lebih baru
- Composer
- Node.js dan npm
- MySQL atau MariaDB
- Web server lokal seperti Laragon, XAMPP, Laravel Herd, atau Laravel Sail

## Instalasi

Clone atau salin proyek ke direktori lokal, lalu masuk ke folder proyek:

```bash
cd media-skripsi
```

Install dependency backend:

```bash
composer install
```

Install dependency frontend:

```bash
npm install
```

Salin file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

## Konfigurasi Environment

Sesuaikan konfigurasi database pada file `.env`.

Contoh konfigurasi lokal:

```env
APP_NAME="Utilwind"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=media_skripsi
DB_USERNAME=root
DB_PASSWORD=
```

Buat database dengan nama yang sama seperti nilai `DB_DATABASE`, misalnya:

```sql
CREATE DATABASE media_skripsi;
```

## Menjalankan Aplikasi

Jalankan migrasi database:

```bash
php artisan migrate
```

Jalankan server Laravel:

```bash
php artisan serve
```

Jalankan Vite untuk proses development frontend:

```bash
npm run dev
```

Aplikasi dapat dibuka melalui:

```text
http://127.0.0.1:8000
```

Untuk build asset produksi:

```bash
npm run build
```

## Database dan Seeder

Folder `database/migrations` berisi struktur tabel untuk:

- Users
- Classes atau class groups
- Course progress
- Lessons
- Course modules
- Course lessons
- Course activities
- Quiz
- Quiz questions
- Quiz attempts
- Lab
- Lab sessions
- Lab histories
- Admin audit logs

Seeder yang tersedia antara lain:

- `TailwindCourseSeeder`
- `QuizSeeder`
- `FullCourseLabsSeeder`
- `LabBab1RefactorSeeder`
- `SyncClassSeeder`

Seeder dapat dijalankan sesuai kebutuhan:

```bash
php artisan db:seed --class=TailwindCourseSeeder
php artisan db:seed --class=QuizSeeder
php artisan db:seed --class=FullCourseLabsSeeder
```

Atau jalankan seluruh seeder yang sudah didaftarkan:

```bash
php artisan db:seed
```

## Akun dan Hak Akses

Aplikasi memiliki dua area utama:

- **Siswa:** mengakses dashboard, materi, quiz, sandbox, lab, dan profil.
- **Admin:** mengelola pengguna, kelas, soal, lab, laporan, dan analitik.

Akses pembelajaran dibatasi oleh kelas aktif. Siswa harus memasukkan token kelas terlebih dahulu sebelum dapat membuka materi, quiz, dan lab.

## Modul Pembelajaran

Materi pembelajaran dibagi menjadi beberapa bagian:

### Bab 1

- HTML dan CSS
- Dasar Tailwind CSS
- Latar belakang penggunaan Tailwind CSS
- Implementasi Tailwind CSS
- Keunggulan Tailwind CSS
- Instalasi Tailwind CSS

### Bab 2

- Flexbox
- Grid
- Manajemen layout

### Bab 3

- Typography
- Background
- Border
- Effects

Setiap bagian dapat memiliki progres penyelesaian, aktivitas belajar, serta keterkaitan dengan quiz atau lab.

## Ringkasan Route

Route utama aplikasi didefinisikan pada `routes/web.php`.

### Public

- `/` - Landing page
- `/login` - Login
- `/register` - Register
- `/forgot-password` - Lupa password
- `/reset-password/{token}` - Reset password

### User Login

- `/dashboard` - Dashboard siswa
- `/student/join-class` - Bergabung ke kelas dengan token
- `/profile` - Edit profil
- `/developer-info` - Informasi pengembang
- `/cheatsheet` - Cheatsheet
- `/gallery` - Galeri komponen

### Pembelajaran

- `/learning-path` - Peta konsep atau kurikulum
- `/courses/html-css`
- `/courses/tailwind-basic`
- `/courses/background-story`
- `/courses/implementation`
- `/courses/advantages`
- `/courses/installation`
- `/courses/flexbox`
- `/courses/grid`
- `/courses/layout-management`
- `/courses/typography`
- `/courses/backgrounds`
- `/courses/borders`
- `/courses/effects`

### Quiz

- `/quiz/intro/{chapterId}`
- `/quiz/start`
- `/quiz/attempt/{chapterId}`
- `/quiz/save-progress`
- `/quiz/submit`

### Lab

- `/labs/start/{id}`
- `/labs/workspace/{id}`
- `/labs/session/{id}/check`
- `/labs/session/{id}/end`

### Admin

- `/admin`
- `/admin/classes`
- `/admin/analytics/questions`
- `/admin/labs`
- `/admin/analytics/lab/{labId?}`
- `/admin/student/{id}`

## Pengujian

Jalankan test dengan:

```bash
php artisan test
```

Atau melalui script Composer:

```bash
composer test
```

## Catatan Pengembangan

- Dokumentasi ini menjelaskan fitur aktif aplikasi pembelajaran Utilwind.
- Pastikan database sudah dibuat sebelum menjalankan migrasi.
- Jika terjadi masalah cache konfigurasi, jalankan:

```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
```

- Untuk development harian, biasanya diperlukan dua proses yang berjalan bersamaan: `php artisan serve` dan `npm run dev`.
- Route pembelajaran dilindungi middleware autentikasi dan akses kelas aktif.
- Area admin dilindungi middleware autentikasi dan role admin.

## Lisensi

Proyek ini dibuat untuk kebutuhan media pembelajaran dan pengembangan skripsi. Sesuaikan informasi lisensi dengan kebijakan pemilik proyek atau institusi.
