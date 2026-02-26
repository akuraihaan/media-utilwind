<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'class_group',
        'password',
        'institution',
        'study_program',
        'phone',
        'role'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
        
        ];
    }

// === TAMBAHKAN RELASI INI (PENYEBAB ERROR ANDA) ===
    public function quizAttempts()
    {
        return $this->hasMany(QuizAttempt::class, 'user_id'); 
    }

    // Tambahan: Relasi ke Progress Lesson (Untuk Dashboard Admin juga)
    public function lessonProgress()
    {
        return $this->hasMany(UserLessonProgress::class);
    }

    public function labHistories() {
    return $this->hasMany(LabHistory::class)->latest();
}

// Di dalam class User
    public function classGroup()
    {
        // Relasi: Kolom 'class_group' di tabel users terhubung ke kolom 'name' di tabel class_groups
        return $this->belongsTo(ClassGroup::class, 'class_group', 'name');
    }

    public function badges() {
        return $this->belongsToMany(Badge::class, 'user_badges', 'user_id', 'badge_id');
    }

   // Update fungsi getDeveloperTitleAttribute Anda di Model User
    public function getDeveloperTitleAttribute() {
        $xp = $this->xp;
        if ($xp >= 4000) return 'Tailwind Architect'; // Level Tertinggi (Lulus Semua)
        if ($xp >= 2500) return 'Component Crafter';  // Level 4 (Bisa bikin komponen utuh)
        if ($xp >= 1000) return 'Frontend Stylist';   // Level 3 (Paham tata letak & warna)
        if ($xp >= 300)  return 'Utility Apprentice'; // Level 2 (Baru lulus bab 1)
        return 'CSS Novice';                          // Level 1 (Baru daftar)
    }

    // Update target XP agar sinkron
    public function getNextLevelXpAttribute() {
        $xp = $this->xp;
        if ($xp < 300) return 300;
        if ($xp < 1000) return 1000;
        if ($xp < 2500) return 2500;
        if ($xp < 4000) return 4000;
        return $xp; // Maxed out
    }

    public function getXpProgressAttribute() {
        $xp = $this->xp;
        $next = $this->next_level_xp;
        
        $prev = 0;
        if ($xp >= 2500) $prev = 2500;
        elseif ($xp >= 1000) $prev = 1000;
        elseif ($xp >= 300) $prev = 300;

        if ($next - $prev == 0) return 100;
        return round((($xp - $prev) / ($next - $prev)) * 100);
    }
}

