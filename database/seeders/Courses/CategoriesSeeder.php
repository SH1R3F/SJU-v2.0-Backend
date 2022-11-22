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
      Category::unguard();
      $records = [
        ['id' => 2, 'name_ar' => 'عبر الإتصال المرئي', 'description_ar' => 'zoom', 'status' => 1],
        ['id' => 12, 'name_ar' => 'مباشر - حضوري', 'status' => 1],
        ['id' => 13, 'name_ar' => 'مباشر - وتبث عبر حساب الهيئة في منصة انستغرام', 'status' => 1],
        ['id' => 27, 'name_ar' => 'الصالون الإعلامي', 'status' => 1],
      ];

      collect($records)->each(function( $record ) {
        Category::create($record);
      });

    }
}
