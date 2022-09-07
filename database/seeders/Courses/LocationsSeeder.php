<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Location;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class LocationsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
      $records = [
        ['name_ar' => 'الرياض', 'description_ar' => 'مقر الهيئة - حي الصحافة', 'status' => 1],
        ['name_ar' => 'الأحساء', 'description_ar' => 'قاعة 23 سبتمبر - قاعة 22 فبراير - المبرز', 'status' => 1],
        ['name_ar' => 'نجران', 'description_ar' => 'قاعة فندق جلوريا إن', 'status' => 1],
        ['name_ar' => 'الحدود الشمالية', 'description_ar' => 'قاعة هيئة الصحفيين السعوديين في عرعر', 'status' => 1],
        ['name_ar' => 'الجوف', 'description_ar' => 'مسرح جمعية الثقافة والفنون في سكاكا', 'status' => 1],
        ['name_ar' => 'الباحة', 'description_ar' => 'قاعة بلدية محافظة بلجرشي', 'status' => 1],
        ['name_ar' => 'حفر الباطن', 'description_ar' => 'مسرح غرفة حفر الباطن', 'status' => 1],
        ['name_ar' => 'عسير', 'description_ar' => 'قاعة نادي أبها الادبي', 'status' => 1],
        ['name_ar' => 'مكة المكرمة', 'status' => 1],
        ['name_ar' => 'جازان', 'status' => 1],
        ['name_ar' => 'الدمام', 'status' => 1],
        ['name_ar' => 'تبوك', 'status' => 1],
        ['name_ar' => 'القصيم', 'description_ar' => 'مسرح مركز التنمية الاجتماعية في بريدة', 'status' => 1],
        ['name_ar' => 'حائل', 'status' => 1],
        ['name_ar' => 'الجبيل', 'status' => 1],
        ['name_ar' => 'الطائف', 'status' => 1],
        ['name_ar' => 'المدينة المنورة', 'status' => 1]
      ];

      collect($records)->each(function( $record ) {
        Location::create($record);
      });

    }
}
