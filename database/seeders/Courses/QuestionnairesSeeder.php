<?php

namespace Database\Seeders\Courses;

use Illuminate\Database\Seeder;
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
      $records = [
        ['name_ar' => 'حلقة نقاش حول مسرعة الاعلام السعودي', 'status' => 1],
        ['name_ar' => 'حلقة نقاش افتراضية الإعلام الرياضي .. مواجهات دون تفويض', 'status' => 0],
        ['name_ar' => 'حفل تدشين برامج وفعاليات بيت شباب الأحساء الإعلامي', 'status' => 1],
        ['name_ar' => 'كيف نكون فاعلين في تويتر ؟', 'status' => 1],
        ['name_ar' => 'التنظيم القانوني للنشر الإعلامي وحقوق الانسان', 'status' => 0],
        ['name_ar' => 'دور الإعلام أثناء الأزمات و الحروب', 'status' => 1],
        ['name_ar' => 'الأحساء .. إنجازات وتطلعات في وطن ال 90', 'status' => 1],
        ['name_ar' => 'صناعة المحتوى الإعلامي', 'status' => 1],
        ['name_ar' => 'إعداد التقارير الإعلامية', 'status' => 1],
        ['name_ar' => 'محمية بحيرة الأصفر ..', 'status' => 1],
        ['name_ar' => 'الإعلام بين التقليد و التجديد من التراث و العمارة الإسلامية في الأحساء', 'status' => 1],
        ['name_ar' => 'تصوير المنتجات', 'status' => 1],
        ['name_ar' => 'الصحفي الشامل', 'status' => 1],
        ['name_ar' => 'الزاوية الأخيرة', 'status' => 1],
        ['name_ar' => 'تصوير الطبيعة', 'status' => 1],
        ['name_ar' => 'هواة الإعلام', 'status' => 1],
        ['name_ar' => 'التصوير بالجوال', 'status' => 1],
        ['name_ar' => 'اساسيات التصوير الرياضي', 'status' => 1],
        ['name_ar' => '3 قرون من المجد .. إضاءات على يوم التأسيس', 'status' => 1]
      ];

      collect($records)->each(function( $record ) {
        Questionnaire::create($record);
      });

    }
}
