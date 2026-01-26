<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('learning_activities', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('course_id');
            $table->string('lesson_key'); // contoh: intro/what-is-tailwind
            $table->string('action')->default('completed'); // bisa diperluas nanti
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('learning_activities');
    }
};
