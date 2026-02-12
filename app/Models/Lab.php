<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lab extends Model
{
    protected $guarded = [];

    public function steps() {
        return $this->hasMany(LabStep::class)->orderBy('order_index');
    }
}