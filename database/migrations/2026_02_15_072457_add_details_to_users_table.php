<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Menambahkan kolom baru setelah email
            $table->string('class_group')->nullable()->after('email'); // Contoh: Kelas A1
            $table->string('institution')->nullable()->after('class_group'); // Contoh: Universitas X
            $table->string('study_program')->nullable()->after('institution'); // Contoh: Informatika
            $table->string('phone')->nullable()->after('study_program');
            $table->string('avatar')->nullable()->after('phone'); // Foto profil
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['class_group', 'institution', 'study_program', 'phone', 'avatar']);
        });
    }
};