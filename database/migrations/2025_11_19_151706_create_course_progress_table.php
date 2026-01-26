<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('course_progress', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('course'); // tailwind
            $table->integer('progress'); // 0 - 100
            $table->timestamps();

            $table->unique(['user_id','course']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('course_progress');
    }
};
