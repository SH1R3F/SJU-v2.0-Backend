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
      Gender::unguard();
      $records = [
        ['id' => 3, 'name_ar' => 'الجنسان', 'status' => 1],
        ['id' => 9, 'name_ar' => 'ذكور', 'status' => 0],
        ['id' => 10, 'name_ar' => 'إناث', 'status' => 0]
      ];

      collect($records)->each(function( $record ) {
        Gender::create($record);
      });

    }
}
