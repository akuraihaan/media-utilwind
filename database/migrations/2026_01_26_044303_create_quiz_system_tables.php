<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // 1. Tabel Soal
        if (!Schema::hasTable('quiz_questions')) {
            Schema::create('quiz_questions', function (Blueprint $table) {
                $table->id();
                $table->string('chapter_id'); // Misal: "1", "2"
                $table->text('question_text');
                $table->timestamps();
            });
        }

        // 2. Tabel Pilihan Jawaban (A, B, C, D)
        if (!Schema::hasTable('quiz_options')) {
            Schema::create('quiz_options', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_question_id')->constrained()->onDelete('cascade');
                $table->string('option_text');
                $table->boolean('is_correct')->default(false);
                $table->timestamps();
            });
        }

        // 3. Tabel Riwayat Pengerjaan (Header)
        if (!Schema::hasTable('quiz_attempts')) {
            Schema::create('quiz_attempts', function (Blueprint $table) {
                $table->id();
                $table->foreignId('user_id')->constrained()->onDelete('cascade');
                $table->string('chapter_id');
                $table->integer('score')->default(0);
                $table->integer('time_spent_seconds')->default(0);
                $table->timestamp('completed_at')->nullable();
                $table->timestamps();
            });
        }

        // 4. Tabel Detail Jawaban Siswa (Untuk Analisis Guru)
        if (!Schema::hasTable('quiz_attempt_answers')) {
            Schema::create('quiz_attempt_answers', function (Blueprint $table) {
                $table->id();
                $table->foreignId('quiz_attempt_id')->constrained('quiz_attempts')->onDelete('cascade');
                $table->foreignId('quiz_question_id')->constrained('quiz_questions')->onDelete('cascade');
                $table->foreignId('quiz_option_id')->nullable()->constrained('quiz_options')->onDelete('cascade');
                $table->boolean('is_correct')->default(false); // Snapshot status benar/salah saat itu
                $table->boolean('is_flagged')->default(false);
                $table->timestamps();
            });
        }
    }

    public function down()
    {
        Schema::dropIfExists('quiz_attempt_answers');
        Schema::dropIfExists('quiz_attempts');
        Schema::dropIfExists('quiz_options');
        Schema::dropIfExists('quiz_questions');
    }
};
