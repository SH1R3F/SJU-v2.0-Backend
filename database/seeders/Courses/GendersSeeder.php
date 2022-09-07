<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Gender;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class GendersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $records = [
        ['name_ar' => 'الجنسان', 'status' => 1],
        ['name_ar' => 'ذكور', 'status' => 0],
        ['name_ar' => 'إناث', 'status' => 0]
      ];

      collect($records)->each(function( $record ) {
        Gender::create($record);
      });

    }
}
