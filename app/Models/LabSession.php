<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\Factories\HasFactory;

class LabSession extends Model
{
    use HasFactory;

    protected $table = 'lab_sessions';

    protected $fillable = [
        'user_id',
        'lab_id',
        'status',
        'started_at',
        'expires_at',    // Sesuai gambar (menggantikan last_activity_at)
        'completed_at',
        'current_score', // Sesuai gambar
        'current_code',  // Sesuai gambar
    ];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}