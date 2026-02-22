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
    'expires_at', 
    'current_code', 
    'current_score',
    'completed_steps' // <--- TAMBAHKAN INI
];

    public function lab()
    {
        return $this->belongsTo(Lab::class);
    }
}