<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CourseModule;
use App\Models\CourseLesson;

class CourseSeeder extends Seeder
{
    public function run(): void
    {
        CourseLesson::truncate();
        CourseModule::truncate();

        /* ===============================
           MODUL 1 — PENGANTAR TAILWIND
        =============================== */
        $m1 = CourseModule::create([
            'title' => 'Pendahuluan Tailwind CSS',
            'order' => 1
        ]);

        CourseLesson::create([
            'course_module_id' => $m1->id,
            'title' => 'Apa itu Tailwind CSS?',
            'order' => 1,
            'content' => <<<HTML
<p>
Tailwind CSS adalah framework CSS berbasis <strong>utility-first</strong>
yang dirancang untuk membantu pengembang membangun antarmuka pengguna
secara cepat, konsisten, dan terstruktur.
</p>

<p>
Berbeda dengan pendekatan CSS tradisional yang menggunakan class semantik,
Tailwind menggunakan class kecil seperti <code>px-4</code>,
<code>bg-indigo-500</code>, dan <code>rounded-lg</code>
langsung di elemen HTML.
</p>

<p>
Pendekatan ini memberikan beberapa keuntungan utama:
</p>

<ul>
<li>Produktivitas meningkat karena tidak perlu menulis CSS berulang</li>
<li>Konsistensi desain terjaga</li>
<li>Ukuran CSS akhir lebih kecil karena proses purge</li>
</ul>

<p>
Dalam modul ini, Anda akan mempelajari filosofi Tailwind, cara kerja utility,
dan bagaimana Tailwind digunakan dalam proyek modern.
</p>
HTML
        ]);

        CourseLesson::create([
            'course_module_id' => $m1->id,
            'title' => 'Filosofi Utility-First',
            'order' => 2,
            'content' => <<<HTML
<p>
Utility-first adalah pendekatan di mana setiap class CSS merepresentasikan
satu properti spesifik, seperti margin, padding, warna, atau font.
</p>

<p>
Contoh:
</p>

<pre>
&lt;button class="px-6 py-3 bg-blue-600 text-white rounded-xl"&gt;
  Tombol
&lt;/button&gt;
</pre>

<p>
Dengan pendekatan ini, developer tidak perlu berpindah-pindah file
antara HTML dan CSS, sehingga proses pengembangan menjadi lebih cepat.
</p>

<p>
Pada tahap awal, penggunaan utility mungkin terasa tidak biasa,
namun dalam praktiknya pendekatan ini sangat efektif untuk skala besar.
</p>
HTML
        ]);

        /* ===============================
           MODUL 2 — BASIC UTILITY
        =============================== */
        $m2 = CourseModule::create([
            'title' => 'Utility Dasar & Typography',
            'order' => 2
        ]);

        CourseLesson::create([
            'course_module_id' => $m2->id,
            'title' => 'Spacing, Warna, dan Tipografi',
            'order' => 1,
            'content' => <<<HTML
<p>
Tailwind menyediakan utility lengkap untuk mengatur spacing
(<code>margin</code> dan <code>padding</code>), warna,
serta tipografi.
</p>

<p>
Contoh penggunaan:
</p>

<pre>
&lt;h1 class="text-3xl font-bold text-gray-800"&gt;
  Judul
&lt;/h1&gt;
</pre>

<p>
Dengan sistem skala yang konsisten, desain antarmuka menjadi lebih rapi
dan mudah dirawat.
</p>
HTML
        ]);

        CourseLesson::create([
            'course_module_id' => $m2->id,
            'title' => 'Responsive Utility',
            'order' => 2,
            'content' => <<<HTML
<p>
Tailwind menggunakan pendekatan <strong>mobile-first</strong>.
Utility tanpa prefix berlaku untuk mobile,
sedangkan breakpoint menggunakan prefix seperti <code>md:</code>,
<code>lg:</code>, dan <code>xl:</code>.
</p>

<p>
Contoh:
</p>

<pre>
&lt;div class="text-sm md:text-lg lg:text-xl"&gt;
  Teks Responsif
&lt;/div&gt;
</pre>

<p>
Pendekatan ini memudahkan pembuatan antarmuka responsif
tanpa menulis media query manual.
</p>
HTML
        ]);

        /* ===============================
           MODUL 3 — LAYOUT
        =============================== */
        $m3 = CourseModule::create([
            'title' => 'Layout Flexbox & Grid',
            'order' => 3
        ]);

        CourseLesson::create([
            'course_module_id' => $m3->id,
            'title' => 'Flexbox di Tailwind',
            'order' => 1,
            'content' => <<<HTML
<p>
Flexbox digunakan untuk mengatur layout satu dimensi
(horizontal atau vertikal).
</p>

<p>
Tailwind menyediakan utility seperti:
</p>

<ul>
<li><code>flex</code></li>
<li><code>items-center</code></li>
<li><code>justify-between</code></li>
</ul>

<p>
Flexbox sangat cocok untuk navbar, card, dan komponen UI lainnya.
</p>
HTML
        ]);

        CourseLesson::create([
            'course_module_id' => $m3->id,
            'title' => 'Grid di Tailwind',
            'order' => 2,
            'content' => <<<HTML
<p>
Grid digunakan untuk layout dua dimensi (baris dan kolom).
</p>

<p>
Contoh:
</p>

<pre>
&lt;div class="grid grid-cols-3 gap-4"&gt;
  &lt;div&gt;1&lt;/div&gt;
  &lt;div&gt;2&lt;/div&gt;
  &lt;div&gt;3&lt;/div&gt;
&lt;/div&gt;
</pre>

<p>
Grid sangat efektif untuk dashboard dan layout kompleks.
</p>
HTML
        ]);
    }
}
