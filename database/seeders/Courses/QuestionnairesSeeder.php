<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Models\Course\Questionnaire;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class QuestionnairesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      // Questionnaires Seeding From .sql File
      Questionnaire::unguard();
      $sql = base_path('database/data/questionnaires.sql');
      DB::unprepared(file_get_contents($sql));

      // Questions Seeding From .sql File
      Question::unguard();
      $sql = base_path('database/data/questions.sql');
      DB::unprepared(file_get_contents($sql));
      
    }
}
