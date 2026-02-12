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
    Schema::table('lab_histories', function (Blueprint $table) {
        // Tambahkan kolom status, default 'completed' atau nullable
        $table->string('status')->default('completed')->after('final_score'); 
    });
}

public function down()
{
    Schema::table('lab_histories', function (Blueprint $table) {
        $table->dropColumn('status');
    });
}
};
