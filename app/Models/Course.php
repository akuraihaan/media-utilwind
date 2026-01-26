<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model {
  protected $fillable = ['slug','title','description'];

  public function modules() {
        return $this->hasMany(CourseModule::class);
    }
}