<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use PhpOffice\PhpWord\IOFactory;
use App\Models\Course;
use App\Models\CourseModule;
use App\Models\CourseLesson;
use Illuminate\Support\Str;

class ImportModulAjarDocx extends Command
{
    protected $signature = 'course:import {file}';
    protected $description = 'Import modul ajar DOCX ke database course';

    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File tidak ditemukan: $path");
            return;
        }

        $this->info("Membaca file DOCX...");

        $phpWord = IOFactory::load($path);

        // 1️⃣ COURSE
        $course = Course::firstOrCreate(
    ['title' => 'Tailwind CSS'],
    [
        'slug' => Str::slug('Tailwind CSS'),
        'description' => 'Media pembelajaran interaktif Tailwind CSS'
    ]
);

        $currentModule = null;
        $currentLesson = null;
        $moduleOrder = 1;
        $lessonOrder = 1;

        foreach ($phpWord->getSections() as $section) {
            foreach ($section->getElements() as $element) {

                // Ambil teks
                if (!method_exists($element, 'getText')) continue;
                $text = $this->extractText($element);
if ($text === '') continue;
                if ($text === '') continue;

                // Ambil style
                $style = method_exists($element, 'getParagraphStyle')
                    ? $element->getParagraphStyle()
                    : null;

                $styleName = $style && method_exists($style,'getStyleName')
                    ? $style->getStyleName()
                    : null;

                // ===============================
                // HEADING 1 → MODULE
                // ===============================
                if ($styleName === 'Heading1') {
                    $currentModule = CourseModule::create([
                        'course_id' => $course->id,
                        'title' => $text,
                        'order' => $moduleOrder++
                    ]);
                    $lessonOrder = 1;
                    $this->info("➤ Modul: $text");
                    continue;
                }

                // ===============================
                // HEADING 2 → LESSON
                // ===============================
                if ($styleName === 'Heading2' && $currentModule) {
                    $currentLesson = CourseLesson::create([
                        'course_module_id' => $currentModule->id,
                        'title' => $text,
                        'content' => '',
                        'order' => $lessonOrder++
                    ]);
                    $this->line("   └ Lesson: $text");
                    continue;
                }

                // ===============================
                // PARAGRAF → ISI LESSON
                // ===============================
                if ($currentLesson) {
                    $currentLesson->update([
                        'content' => $currentLesson->content .
                            '<p>' . nl2br(e($text)) . '</p>'
                    ]);
                }
            }
        }

        $this->info("IMPORT SELESAI — Modul & lesson berhasil dimasukkan");
    }

    private function extractText($element): string
{
    // Jika element punya getText()
    if (method_exists($element, 'getText')) {

        $text = $element->getText();

        // Jika STRING → langsung
        if (is_string($text)) {
            return trim($text);
        }

        // Jika ARRAY / TextRun
        if (is_array($text)) {
            return trim(collect($text)
                ->map(fn($t) => is_string($t) ? $t : '')
                ->implode(' ')
            );
        }
    }

    // Jika TextRun (paragraph dengan style)
    if ($element instanceof \PhpOffice\PhpWord\Element\TextRun) {
        $output = '';
        foreach ($element->getElements() as $child) {
            if (method_exists($child, 'getText')) {
                $output .= $child->getText() . ' ';
            }
        }
        return trim($output);
    }

    return '';
}
}
