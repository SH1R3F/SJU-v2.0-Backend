<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Course;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      // Courses Seeding From .sql File
      Course::unguard();
      $sql = base_path('database/data/courses.sql');
      DB::unprepared(file_get_contents($sql));

      // Add members, subscribers, volunteers to first course
      $course = Course::first();
      $course->members()->sync([1,3,4]);
      $course->subscribers()->sync([1,2]);
      $course->volunteers()->sync([1,2]);
    }
}
