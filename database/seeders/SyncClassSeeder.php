<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use App\Models\ClassGroup;

class SyncClassSeeder extends Seeder
{
    public function run()
    {
        // Ambil semua nama kelas yang unik dari tabel users yang tidak kosong
        $existingClasses = DB::table('users')
            ->whereNotNull('class_group')
            ->where('class_group', '!=', '')
            ->distinct()
            ->pluck('class_group');

        foreach ($existingClasses as $className) {
            // Masukkan ke tabel class_groups jika belum ada
            ClassGroup::firstOrCreate(
                ['name' => $className],
                [
                    'token' => strtoupper(Str::random(6)),
                    'is_active' => true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }

        $this->command->info('Sinkronisasi Kelas Berhasil! Token telah dibuat.');
    }
}