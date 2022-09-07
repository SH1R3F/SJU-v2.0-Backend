<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Type;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $records = [
        ['name_ar' => 'حلقة إثرائية', 'status' => 1],
        ['name_ar' => 'ورشة عمل', 'status' => 1],
        ['name_ar' => 'حلقة نقاش', 'status' => 1],
        ['name_ar' => 'برنامج تدريبي', 'status' => 1],
        ['name_ar' => 'ملتقى', 'status' => 1],
        ['name_ar' => 'مؤتمر صحفي', 'status' => 1],
        ['name_ar' => 'مؤتمر', 'status' => 1],
        ['name_ar' => 'ندوة', 'status' => 1],
        ['name_ar' => 'لقاء أخوي ترفيهي', 'status' => 1],
        ['name_ar' => 'حفل خطابي', 'status' => 1],
        ['name_ar' => 'الصالون الإعلامي', 'status' => 1]
      ];

      collect($records)->each(function( $record ) {
        Type::create($record);
      });
    }
}
