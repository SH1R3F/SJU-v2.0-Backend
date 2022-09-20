<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Course;
use Illuminate\Database\Seeder;
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
      $records = [
        [ 'SN' => 'SJU-0001', 'name_ar' => 'حلقة نقاش حول مسرعة الاعلام السعودي ', 'date_from' => '2020-07-26', 'date_to' => '2020-07-26 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0002', 'name_ar' => 'حلقة نقاش افتراضية الإعلام الرياضي .. مواجهات دون تفويض ', 'date_from' => '2020-08-02', 'date_to' => '2020-08-02 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0003', 'name_ar' => 'حفل تدشين برامج وفعاليات بيت شباب الأحساء الإعلامي ', 'date_from' => '2020-08-12', 'date_to' => '2020-08-12 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0004', 'name_ar' => 'كيف نكون فاعلين في تويتر ؟ ', 'date_from' => '2020-08-18', 'date_to' => '2020-08-18 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'حفر الباطن ' ],
        [ 'SN' => 'SJU-0005', 'name_ar' => 'التنظيم القانوني للنشر الإعلامي وحقوق الإنسان في السعودية ', 'date_from' => '2020-08-24', 'date_to' => '2020-08-24 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0006', 'name_ar' => 'كورونا والاعلام السعودي ', 'date_from' => '2020-08-29', 'date_to' => '2020-08-29 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الجبيل ' ],
        [ 'SN' => 'SJU-0007', 'name_ar' => 'دور الإعلام أثناء الأزمات و الحروب ', 'date_from' => '2020-09-02', 'date_to' => '2020-09-02 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0008', 'name_ar' => 'الإعلام السعودي والاقتصاد النفطي ', 'date_from' => '2020-09-06', 'date_to' => '2020-09-06 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'مكة المكرمة ' ],
        [ 'SN' => 'SJU-0009', 'name_ar' => 'إنجازات وتطلعات وطن ال 90 ', 'date_from' => '2020-09-22', 'date_to' => '2020-09-22 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0010', 'name_ar' => 'أمسية فرع حفرالباطن الشعرية ', 'date_from' => '2020-09-23', 'date_to' => '2020-09-23 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'حفر الباطن ' ],
        [ 'SN' => 'SJU-0011', 'name_ar' => 'صناعة المحتوى الاعلامي ', 'date_from' => '2020-10-07', 'date_to' => '2020-10-07 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0012', 'name_ar' => '. محمية الأصفر ..مستقبل السياحة البيئية الواعد في المملكة ', 'date_from' => '2020-10-13', 'date_to' => '2020-10-13 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0013', 'name_ar' => 'إعداد التقارير الإعلامية ', 'date_from' => '2020-10-13', 'date_to' => '2020-10-13 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0014', 'name_ar' => 'الإعلام بين التقليد والتجديد من التراث و العمارة الإسلامية في الأحساء ', 'date_from' => '2020-10-21', 'date_to' => '2020-10-21 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0015', 'name_ar' => 'إنتاج الفديو الصحفي ', 'date_from' => '2020-10-22', 'date_to' => '2020-10-23 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0016', 'name_ar' => 'أساسيات التصوير بالجوال ', 'date_from' => '2020-10-28', 'date_to' => '2020-10-29 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0017', 'name_ar' => 'فن المراسم و البروتوكول في العلاقات العامة ', 'date_from' => '2020-11-06', 'date_to' => '2020-11-07 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0018', 'name_ar' => 'أساسيات الفوتوشوب ', 'date_from' => '2020-11-11', 'date_to' => '2020-11-12 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0019', 'name_ar' => 'حفر الباطن .. مستقبل الإعلام ', 'date_from' => '2020-11-16', 'date_to' => '2020-11-16 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'حفر الباطن ' ],
        [ 'SN' => 'SJU-0020', 'name_ar' => 'أساسيات التصوير الضوئي ', 'date_from' => '2020-11-29', 'date_to' => '2020-11-30 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0021', 'name_ar' => 'التعليق الصوتي .. صوتك يعبر عنك ', 'date_from' => '2020-12-09', 'date_to' => '2020-12-11 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0022', 'name_ar' => 'التدريب العملي لطالبات الاعلام في كلية الاداب بجامعة الملك فيصل بالأحساء ', 'date_from' => '2020-12-09', 'date_to' => '2020-12-24 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0023', 'name_ar' => 'تصوير المنتجات ', 'date_from' => '2020-12-16', 'date_to' => '2020-12-18 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0024', 'name_ar' => 'توثيق حياة الناس ', 'date_from' => '2021-01-30', 'date_to' => '2021-01-31 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0025', 'name_ar' => 'بيت المصورين بالاحساء ', 'date_from' => '2021-01-30', 'date_to' => '2021-02-28 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0026', 'name_ar' => 'برنامج صناعة المحتوى الإعلامي ', 'date_from' => '2021-02-24', 'date_to' => '2021-02-24 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0027', 'name_ar' => 'الملكية الفكرية في المملكة ', 'date_from' => '2021-02-28', 'date_to' => '2021-02-28 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الجبيل ' ],
        [ 'SN' => 'SJU-0028', 'name_ar' => 'تسويق الذات للاعلاميين ', 'date_from' => '2021-03-16', 'date_to' => '2021-03-17 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0030', 'name_ar' => 'التعليق الرياضي ', 'date_from' => '2021-03-14', 'date_to' => '2021-03-15 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0031', 'name_ar' => 'اطلق صوتك ', 'date_from' => '2021-03-17', 'date_to' => '2021-03-17 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0032', 'name_ar' => 'تصميم الانفوجرافيك ', 'date_from' => '2021-03-24', 'date_to' => '2021-03-24 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0033', 'name_ar' => 'مهارات المتحدث الإعلامي الرسمي ', 'date_from' => '2021-03-29', 'date_to' => '2021-03-31 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0034', 'name_ar' => 'الصالون الإعلامي - بالأحساء يستضيف الاستاذ قاسم الشافعي ', 'date_from' => '2021-11-09', 'date_to' => '2021-11-09 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0035', 'name_ar' => 'صناعة المحتوى عبر الهواتف الذكية ', 'date_from' => '2021-03-31', 'date_to' => '2021-03-31 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0036', 'name_ar' => 'صحافة الموبايل MOJO ', 'date_from' => '2021-04-04', 'date_to' => '2021-04-06 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0037', 'name_ar' => 'الخيل بين الجمال ولحظة الالتقاط ', 'date_from' => '2021-04-06', 'date_to' => '2021-04-10 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0038', 'name_ar' => 'فن التحرير الإعلامي ', 'date_from' => '2021-04-07', 'date_to' => '2021-04-07 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0039', 'name_ar' => 'نقاط حول تصوير الفديو ', 'date_from' => '2021-04-14', 'date_to' => '2021-04-14 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0040', 'name_ar' => 'صناعة المحتوى الإبداعي ', 'date_from' => '2021-04-11', 'date_to' => '2021-04-11 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
        [ 'SN' => 'SJU-0041', 'name_ar' => 'الصحفي الشامل ', 'date_from' => '2021-04-21', 'date_to' => '2021-04-21 ', 'category_id' => '1', 'gender_id' => '1', 'location_id' => '1', 'region' => 'الأحساء ' ],
      ];

      collect($records)->each(function( $record ) {
        Course::create($record);
      });

    }
}
