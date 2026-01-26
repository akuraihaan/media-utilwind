<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttemptItem extends Model
{
    use HasFactory;

    protected $fillable = ['student_id','quiz_id','content_id','attempt_number','completed','time_spent_seconds','matrix'];

    protected $casts = [
        'matrix' => 'array',
        'completed' => 'boolean',
    ];

    public function items()
    {
        return $this->hasMany(AttemptItem::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class,'student_id');
    }

    public function quiz()
    {
        return $this->belongsTo(Quiz::class);
    }
}
