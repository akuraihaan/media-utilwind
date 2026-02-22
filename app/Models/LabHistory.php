<?php
// app/Models/LabHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabHistory extends Model
{
    protected $guarded = [];
    protected $dates = ['completed_at'];
// app/Models/LabHistory.php

protected $fillable = [
    'user_id', 
    'lab_id', 
    'last_code_snapshot', 
    'source_code',
    'status', 
    'final_score', 
    'duration_seconds',
    'completed_steps', // <--- TAMBAHKAN INI
    'completed_at'
];
    // Relasi ke tabel Labs (judul lab, slug, dll)
    public function lab()
    {
        return $this->belongsTo(Lab::class, 'lab_id');
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    // Accessor: Mengubah detik (int) ke format "10m 30s"
    public function getDurationReadableAttribute() {
        $minutes = floor($this->duration_seconds / 60);
        $seconds = $this->duration_seconds % 60;
        return "{$minutes}m {$seconds}s";
    }
}