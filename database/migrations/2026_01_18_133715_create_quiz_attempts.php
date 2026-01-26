<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(){
    Schema::create('quiz_attempts', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('course_slug');
      $table->string('lesson_id');
      $table->boolean('is_correct');
      $table->timestamps();
    });
  }
  public function down(){ Schema::dropIfExists('quiz_attempts'); }
};
