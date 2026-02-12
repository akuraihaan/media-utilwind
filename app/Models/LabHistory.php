<?php
// app/Models/LabHistory.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabHistory extends Model
{
    protected $guarded = [];
    protected $dates = ['completed_at'];
protected $fillable = [
    'user_id', 
    'lab_id', 
    'final_score', 
    'duration_seconds', 
    'last_code_snapshot', 
    'completed_at',
    'status' // <--- PASTIKAN INI ADA
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