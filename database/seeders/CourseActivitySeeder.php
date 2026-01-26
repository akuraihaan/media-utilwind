<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseActivitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
   public function run(): void
{
    CourseActivity::firstOrCreate([
        'course_id' => 1, // sesuaikan
        'title'     => 'Aktivitas 1.1 – HTML & CSS',
        'type'      => 'html-css'
    ]);
}

}
