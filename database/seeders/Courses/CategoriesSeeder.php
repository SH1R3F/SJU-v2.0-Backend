<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Category;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategoriesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $records = [
        ['name_ar' => 'عبر الإتصال المرئي', 'description_ar' => 'zoom', 'status' => 1],
        ['name_ar' => 'مباشر - وتبث عبر حساب الهيئة في منصة انستغرام', 'status' => 1],
        ['name_ar' => 'الصالون الإعلامي', 'status' => 1],
      ];

      collect($records)->each(function( $record ) {
        Category::create($record);
      });

    }
}
