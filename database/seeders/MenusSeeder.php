<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MenusSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $records = [
          [
            'title_ar' => 'الرئيسية',
            'title_en' => 'Home',
            'link' => '/',
            'open_in_same_page' => 1,
            'order' => 1,
          ],
          [
            'title_ar' => 'الأخبار',
            'title_en' => 'News',
            'link' => '/posts',
            'open_in_same_page' => 1,
            'order' => 2,
          ],
          [
            'title_ar' => 'عن الهيئة',
            'title_en' => 'About us',
            'link' => '/pages/about-us',
            'open_in_same_page' => 1,
            'order' => 3,
          ],
          [
            'title_ar' => 'ميثاق الشرف',
            'title_en' => 'Honor code',
            'link' => '/pages/honor-code',
            'open_in_same_page' => 1,
            'order' => 4,
          ],
          [
            'title_ar' => 'العضوية في الهيئة',
            'title_en' => 'Membership',
            'link' => '/pages/membership',
            'open_in_same_page' => 1,
            'order' => 5,
          ],
          [
            'title_ar' => 'اللوائح والأنظمة',
            'title_en' => 'Regulations',
            'link' => '/pages/regulations',
            'open_in_same_page' => 1,
            'order' => 6,
          ],
          [
            'title_ar' => 'الصحافة',
            'title_en' => 'Press',
            'link' => '/pages/press',
            'open_in_same_page' => 1,
            'order' => 7,
          ],
          [
            'title_ar' => 'الإذاعة والتلفزيون',
            'title_en' => 'Radio & TV',
            'link' => '#',
            'open_in_same_page' => 1,
            'order' => 8,
          ],
          [
            'title_ar' => 'شراكات',
            'title_en' => 'Partners',
            'link' => 'https://sju.org.sa/partnership',
            'open_in_same_page' => 1,
            'order' => 9,
          ]
        ];

        collect($records)->each(function( $record ) {
          Menu::create($record);
        });

    }
}
