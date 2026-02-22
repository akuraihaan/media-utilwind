<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB; // WAJIB TAMBAHKAN INI

class ClassGroup extends Model
{
    use HasFactory;

    // Pastikan nama tabelnya benar
    protected $table = 'class_groups';

    protected $fillable = [
        'name',
        'major',
        'token',
        'is_active',
    ];

    // Relasi ke User
    public function students()
    {
        return $this->hasMany(User::class, 'class_group', 'name');
    }

    /**
     * =========================================================
     * ELOQUENT EVENTS (AUTO-SYNC SEPERTI TRIGGER DATABASE)
     * =========================================================
     */
    protected static function booted()
    {
        // 1. KETIKA DATA KELAS DI-UPDATE
        static::updated(function ($classGroup) {
            // Mengecek apakah yang diubah benar-benar 'name'-nya
            if ($classGroup->wasChanged('name')) {
                
                $oldName = trim($classGroup->getOriginal('name'));
                $newName = trim($classGroup->name);

                // Langsung hajar update ke tabel users!
                DB::table('users')
                    ->where('class_group', $oldName)
                    ->update(['class_group' => $newName]);
            }
        });

        // 2. KETIKA DATA KELAS DIHAPUS
        static::deleted(function ($classGroup) {
            $deletedName = trim($classGroup->name);

            // Kosongkan nama kelas untuk user yang kelasnya baru saja dihapus
            DB::table('users')
                ->where('class_group', $deletedName)
                ->update(['class_group' => null]);
        });
    }
}