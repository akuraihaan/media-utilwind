<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('class_groups', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // Contoh: 'Kelas A1', 'XII RPL 1'
            $table->string('major')->nullable(); // Jurusan/Program Studi
            $table->string('token', 8)->unique(); // Token gabung kelas (Unik)
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('class_groups');
    }
};