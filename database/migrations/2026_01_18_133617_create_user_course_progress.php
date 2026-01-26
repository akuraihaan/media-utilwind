<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
  public function up(){
    Schema::create('user_course_progress', function (Blueprint $table) {
      $table->id();
      $table->foreignId('user_id')->constrained()->cascadeOnDelete();
      $table->string('course_slug');
      $table->json('completed_lessons')->nullable();
      $table->unsignedTinyInteger('progress_percent')->default(0);
      $table->enum('current_level',['beginner','intermediate','advanced','expert'])->default('beginner');
      $table->timestamps();
    });
  }
  public function down(){ Schema::dropIfExists('user_course_progress'); }
};
