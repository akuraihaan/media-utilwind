<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('course_modules', function (Blueprint $table) {
        $table->foreignId('course_id')
              ->after('id')
              ->constrained('courses')
              ->cascadeOnDelete();
    });
}

public function down()
{
    Schema::table('course_modules', function (Blueprint $table) {
        $table->dropForeign(['course_id']);
        $table->dropColumn('course_id');
    });
}

};
