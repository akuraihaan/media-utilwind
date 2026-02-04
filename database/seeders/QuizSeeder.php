<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\QuizQuestion;
use App\Models\QuizOption;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema; // Tambahan untuk cara ya

class QuizSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        // =================================================================
        // SOAL BAB 1: Konsep Dasar HTML, CSS, dan Tailwind CSS (1.1 - 1.6)
        // =================================================================
        $chapterId = 2;

        // 1. Konsep Dasar HTML & CSS
        // Soal 1: Flex Direction (Materi 2.1.2)
        $this->createQuestion('2', 'Kelas utilitas mana yang digunakan untuk menyusun item Flexbox secara vertikal (kolom)?', [
            ['text' => 'flex-row', 'correct' => false],
            ['text' => 'flex-col', 'correct' => true], // Benar
            ['text' => 'items-center', 'correct' => false],
            ['text' => 'grid-cols-1', 'correct' => false],
        ]);

        // Soal 2: Flex Wrap (Materi 2.1.3)
        $this->createQuestion('2', 'Apa fungsi dari kelas "flex-wrap" pada elemen induk?', [
            ['text' => 'Mencegah elemen anak untuk menyusut.', 'correct' => false],
            ['text' => 'Membalik urutan elemen anak.', 'correct' => false],
            ['text' => 'Membungkus elemen anak ke baris baru jika tidak muat.', 'correct' => true], // Benar
            ['text' => 'Memaksa semua elemen tetap dalam satu baris.', 'correct' => false],
        ]);

        // Soal 3: Flex Grow/Shrink (Materi 2.1.4)
        $this->createQuestion('2', 'Kelas manakah yang membuat elemen mengabaikan ukuran awalnya dan mengisi ruang yang tersedia (setara flex-grow: 1)?', [
            ['text' => 'flex-1', 'correct' => true], // Benar
            ['text' => 'flex-none', 'correct' => false],
            ['text' => 'flex-initial', 'correct' => false],
            ['text' => 'w-full', 'correct' => false],
        ]);

        // Soal 4: Flex Order (Materi 2.1.7)
        $this->createQuestion('2', 'Jika Anda ingin elemen tertentu muncul paling awal terlepas dari urutan HTML-nya, kelas apa yang digunakan?', [
            ['text' => 'order-0', 'correct' => false],
            ['text' => 'order-last', 'correct' => false],
            ['text' => 'order-first', 'correct' => true], // Benar
            ['text' => 'z-10', 'correct' => false],
        ]);

        // Soal 5: Grid Columns (Materi 2.2.1)
        $this->createQuestion('2', 'Untuk membagi halaman menjadi 3 kolom dengan lebar yang sama menggunakan Grid, kelas apa yang digunakan?', [
            ['text' => 'grid-rows-3', 'correct' => false],
            ['text' => 'cols-3', 'correct' => false],
            ['text' => 'grid-cols-3', 'correct' => true], // Benar
            ['text' => 'flex-3', 'correct' => false],
        ]);

        // Soal 6: Grid Span (Materi 2.2.3)
        $this->createQuestion('2', 'Bagaimana cara membuat sebuah elemen Grid melebar (merge) menempati 2 kolom?', [
            ['text' => 'col-span-2', 'correct' => true], // Benar
            ['text' => 'row-span-2', 'correct' => false],
            ['text' => 'col-start-2', 'correct' => false],
            ['text' => 'w-2/4', 'correct' => false],
        ]);

        // Soal 7: Grid Alignment (Materi 2.2.2)
        $this->createQuestion('2', 'Kelas "justify-items-center" pada Grid berfungsi untuk...', [
            ['text' => 'Menyusun item secara vertikal ke tengah.', 'correct' => false],
            ['text' => 'Menyusun item secara horizontal ke tengah di dalam kolomnya.', 'correct' => true], // Benar
            ['text' => 'Membuat item memenuhi lebar kolom.', 'correct' => false],
            ['text' => 'Menghilangkan gap antar item.', 'correct' => false],
        ]);

        // Soal 8: Container (Materi 3.4.1)
        $this->createQuestion('2', 'Kelas .container di Tailwind tidak otomatis berada di tengah. Kelas apa yang perlu ditambahkan agar posisinya di tengah?', [
            ['text' => 'text-center', 'correct' => false],
            ['text' => 'align-center', 'correct' => false],
            ['text' => 'mx-auto', 'correct' => true], // Benar
            ['text' => 'justify-center', 'correct' => false],
        ]);

        // Soal 9: Position (Materi 3.4.3)
        $this->createQuestion('2', 'Agar properti "absolute" pada elemen anak bekerja relatif terhadap elemen induknya (bukan body), kelas apa yang wajib ada pada elemen induk?', [
            ['text' => 'static', 'correct' => false],
            ['text' => 'relative', 'correct' => true], // Benar
            ['text' => 'fixed', 'correct' => false],
            ['text' => 'sticky', 'correct' => false],
        ]);

        // Soal 10: Table Layout (Materi 3.4.4)
        $this->createQuestion('2', 'Kelas manakah yang digunakan agar lebar kolom tabel ditetapkan secara eksplisit dan tidak berubah otomatis berdasarkan konten?', [
            ['text' => 'table-auto', 'correct' => false],
            ['text' => 'table-fixed', 'correct' => true], // Benar
            ['text' => 'border-collapse', 'correct' => false],
            ['text' => 'w-screen', 'correct' => false],
        ]);
    }

    /**
     * Helper function untuk membuat pertanyaan dan opsi jawaban.
     *
     * @param int|string $chapter
     * @param string $text
     * @param array $options
     * @return void
     */
    private function createQuestion($chapter, $text, $options)
    {
        // 1. Simpan Pertanyaan
        $q = QuizQuestion::create([
            'chapter_id' => $chapter,
            'question_text' => $text
        ]);

        // 2. Simpan Opsi Jawaban
        foreach ($options as $opt) {
            QuizOption::create([
                'quiz_question_id' => $q->id,
                'option_text' => $opt['text'],
                'is_correct' => $opt['correct']
            ]);
        }
    }
}