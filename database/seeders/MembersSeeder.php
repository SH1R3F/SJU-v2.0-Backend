<?php

namespace Database\Seeders;

use App\Models\Member;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class MembersSeeder extends Seeder
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
            'national_id' => '1019347416',
            'source' => 'تبوك',
            'date' => '1453-03-04',
            'fname_ar' => 'منى',
            'sname_ar' => 'عوده',
            'tname_ar' => 'بركي',
            'lname_ar' => 'الذبياني',
            'fname_en' => 'Mona',
            'sname_en' => 'Oudah',
            'tname_en' => 'Berki',
            'lname_en' => 'Althubyani',
            'gender' => 1,
            'nationality' => 1,
            'birthday_hijri' => '1399-09-28',
            'birthday_meladi' => '1979-08-21',
            'qualification' => 'بكالوريوس تربية',
            'major' => 'تربية خاصة',
            'journalist_job_title' => 'محرر صحفي',
            'journalist_employer' => 'جريدة الحياة',
            'newspaper_type' => 1,
            'job_title' => 'مدير مركز الإعلام والنشر',
            'employer' => 'جامعة الأمير محمد بن فهد',
            'worktel' => '138499250',
            'worktel_ext' => '9250',
            'fax' => '0',
            'fax_ext' => '0',
            'post_box' => '1664',
            'post_code' => '31952',
            'mobile' => '966504830702',
            'email' => 'aldawood14d@gmail.com',
            'city' => 3,
            // Experiences and fields [JSON]
            'experiences_and_fields' => [
              'experiences' => [
                [
                  'name' =>  'جريدة المدينة',
                  'years' => 8
                ],
                [
                  'name' =>  'ايلاف الإلكترونية',
                  'years' => 6
                ],
                [
                  'name' =>  'جريدة الحياة',
                  'years' => 13
                ],
                [
                  'name' =>  'مدير المركز الإعلامي جامعة الأمير محمد بن فهد',
                  'years' => 16
                ],
              ],
              'fields' => [],
              'languages' => [
                [
                  'name' => 'العربية',
                  'level' => 1
                ],
                [
                  'name' => 'الأنجليزية',
                  'level' => 3
                ],
              ]
            ],

            // Files
            'profile_image' => null,
            'national_image' => null,
            'employer_letter' => null,

            // To be updated options
            'updated_personal_information' => 0,
            'updated_profile_image' => 0,
            'updated_national_image' => 0,
            'updated_employer_letter' => 0,
            'updated_experiences_and_fields' => 0,

            // Membership information
            'membership_number' => '1-001',
            'membership_type' => 1,
            'membership_start_date' => '2022-04-09',
            'membership_end_date' => '2022-12-31',
            'invoice_id' => null,
            // 'invoice_status' => null,
            'status' => 0,
            'password' => bcrypt('123456')
          ],
          [
            'national_id' => '1019347415',
            'source' => 'تبوك',
            'date' => '1453-03-04',
            'fname_ar' => 'منى',
            'sname_ar' => 'عوده',
            'tname_ar' => 'بركي',
            'lname_ar' => 'الذبياني',
            'fname_en' => 'Mona',
            'sname_en' => 'Oudah',
            'tname_en' => 'Berki',
            'lname_en' => 'Althubyani',
            'gender' => 1,
            'nationality' => 1,
            'birthday_hijri' => '1399-09-28',
            'birthday_meladi' => '1979-08-21',
            'qualification' => 'بكالوريوس تربية',
            'major' => 'تربية خاصة',
            'journalist_job_title' => 'محرر صحفي',
            'journalist_employer' => 'جريدة الحياة',
            'newspaper_type' => 1,
            'job_title' => 'مدير مركز الإعلام والنشر',
            'employer' => 'جامعة الأمير محمد بن فهد',
            'worktel' => '138499250',
            'worktel_ext' => '9250',
            'fax' => '0',
            'fax_ext' => '0',
            'post_box' => '1664',
            'post_code' => '31952',
            'mobile' => '966504830703',
            'email' => 'aldawood14@gmail.com',
            'city' => 3,
            // Experiences and fields [JSON]
            'experiences_and_fields' => [
              'experiences' => [
                [
                  'name' =>  'جريدة المدينة',
                  'years' => 8
                ],
                [
                  'name' =>  'ايلاف الإلكترونية',
                  'years' => 6
                ],
                [
                  'name' =>  'جريدة الحياة',
                  'years' => 13
                ],
                [
                  'name' =>  'مدير المركز الإعلامي جامعة الأمير محمد بن فهد',
                  'years' => 16
                ],
              ],
              'fields' => [],
              'languages' => [
                [
                  'name' => 'العربية',
                  'level' => 1
                ],
                [
                  'name' => 'الأنجليزية',
                  'level' => 3
                ],
              ]
            ],

            // Files
            'profile_image' => null,
            'national_image' => null,
            'employer_letter' => null,

            // To be updated options
            'updated_personal_information' => 0,
            'updated_profile_image' => 0,
            'updated_national_image' => 0,
            'updated_employer_letter' => 0,
            'updated_experiences_and_fields' => 0,

            // Membership information
            'membership_number' => '1-001',
            'membership_type' => 1,
            'membership_start_date' => '2022-04-09',
            'membership_end_date' => '2022-12-31',
            'invoice_id' => null,
            // 'invoice_status' => null,
            'status' => 0,
            'password' => bcrypt('123456')
          ],
          [
            'national_id' => '1119347415',
            'source' => 'تبوك',
            'date' => '1453-03-04',
            'fname_ar' => 'منى',
            'sname_ar' => 'عوده',
            'tname_ar' => 'بركي',
            'lname_ar' => 'الذبياني',
            'fname_en' => 'Mona',
            'sname_en' => 'Oudah',
            'tname_en' => 'Berki',
            'lname_en' => 'Althubyani',
            'gender' => 1,
            'nationality' => 1,
            'birthday_hijri' => '1399-09-28',
            'birthday_meladi' => '1979-08-21',
            'qualification' => 'بكالوريوس تربية',
            'major' => 'تربية خاصة',
            'journalist_job_title' => 'محرر صحفي',
            'journalist_employer' => 'جريدة الحياة',
            'newspaper_type' => 1,
            'job_title' => 'مدير مركز الإعلام والنشر',
            'employer' => 'جامعة الأمير محمد بن فهد',
            'worktel' => '138499250',
            'worktel_ext' => '9250',
            'fax' => '0',
            'fax_ext' => '0',
            'post_box' => '1664',
            'post_code' => '31952',
            'mobile' => '966514830703',
            'email' => 'aldawood11@gmail.com',
            'city' => 3,
            // Experiences and fields [JSON]
            'experiences_and_fields' => [
              'experiences' => [
                [
                  'name' =>  'جريدة المدينة',
                  'years' => 8
                ],
                [
                  'name' =>  'ايلاف الإلكترونية',
                  'years' => 6
                ],
                [
                  'name' =>  'جريدة الحياة',
                  'years' => 13
                ],
                [
                  'name' =>  'مدير المركز الإعلامي جامعة الأمير محمد بن فهد',
                  'years' => 16
                ],
              ],
              'fields' => [],
              'languages' => [
                [
                  'name' => 'العربية',
                  'level' => 1
                ],
                [
                  'name' => 'الأنجليزية',
                  'level' => 3
                ],
              ]
            ],

            // Files
            'profile_image' => null,
            'national_image' => null,
            'employer_letter' => null,

            // To be updated options
            'updated_personal_information' => 0,
            'updated_profile_image' => 0,
            'updated_national_image' => 0,
            'updated_employer_letter' => 0,
            'updated_experiences_and_fields' => 0,

            // Membership information
            'membership_number' => '1-001',
            'membership_type' => 1,
            'membership_start_date' => '2022-04-09',
            'membership_end_date' => '2022-12-31',
            'invoice_id' => null,
            // 'invoice_status' => null,
            'status' => 0,
            'password' => bcrypt('123456')
          ],
          [
            'national_id' => '1219347415',
            'source' => 'تبوك',
            'date' => '1453-03-04',
            'fname_ar' => 'منى',
            'sname_ar' => 'عوده',
            'tname_ar' => 'بركي',
            'lname_ar' => 'الذبياني',
            'fname_en' => 'Mona',
            'sname_en' => 'Oudah',
            'tname_en' => 'Berki',
            'lname_en' => 'Althubyani',
            'gender' => 1,
            'nationality' => 1,
            'birthday_hijri' => '1399-09-28',
            'birthday_meladi' => '1979-08-21',
            'qualification' => 'بكالوريوس تربية',
            'major' => 'تربية خاصة',
            'journalist_job_title' => 'محرر صحفي',
            'journalist_employer' => 'جريدة الحياة',
            'newspaper_type' => 1,
            'job_title' => 'مدير مركز الإعلام والنشر',
            'employer' => 'جامعة الأمير محمد بن فهد',
            'worktel' => '138499250',
            'worktel_ext' => '9250',
            'fax' => '0',
            'fax_ext' => '0',
            'post_box' => '1664',
            'post_code' => '31952',
            'mobile' => '966524830703',
            'email' => 'aldawood24@gmail.com',
            'city' => 3,
            // Experiences and fields [JSON]
            'experiences_and_fields' => [
              'experiences' => [
                [
                  'name' =>  'جريدة المدينة',
                  'years' => 8
                ],
                [
                  'name' =>  'ايلاف الإلكترونية',
                  'years' => 6
                ],
                [
                  'name' =>  'جريدة الحياة',
                  'years' => 13
                ],
                [
                  'name' =>  'مدير المركز الإعلامي جامعة الأمير محمد بن فهد',
                  'years' => 16
                ],
              ],
              'fields' => [],
              'languages' => [
                [
                  'name' => 'العربية',
                  'level' => 1
                ],
                [
                  'name' => 'الأنجليزية',
                  'level' => 3
                ],
              ]
            ],

            // Files
            'profile_image' => null,
            'national_image' => null,
            'employer_letter' => null,

            // To be updated options
            'updated_personal_information' => 0,
            'updated_profile_image' => 0,
            'updated_national_image' => 0,
            'updated_employer_letter' => 0,
            'updated_experiences_and_fields' => 0,

            // Membership information
            'membership_number' => '1-001',
            'membership_type' => 1,
            'membership_start_date' => '2022-04-09',
            'membership_end_date' => '2022-12-31',
            'invoice_id' => null,
            // 'invoice_status' => null,
            'status' => 0,
            'password' => bcrypt('123456')
          ],
          [
            'national_id' => '1319347415',
            'source' => 'تبوك',
            'date' => '1453-03-04',
            'fname_ar' => 'منى',
            'sname_ar' => 'عوده',
            'tname_ar' => 'بركي',
            'lname_ar' => 'الذبياني',
            'fname_en' => 'Mona',
            'sname_en' => 'Oudah',
            'tname_en' => 'Berki',
            'lname_en' => 'Althubyani',
            'gender' => 1,
            'nationality' => 1,
            'birthday_hijri' => '1399-09-28',
            'birthday_meladi' => '1979-08-21',
            'qualification' => 'بكالوريوس تربية',
            'major' => 'تربية خاصة',
            'journalist_job_title' => 'محرر صحفي',
            'journalist_employer' => 'جريدة الحياة',
            'newspaper_type' => 1,
            'job_title' => 'مدير مركز الإعلام والنشر',
            'employer' => 'جامعة الأمير محمد بن فهد',
            'worktel' => '138499250',
            'worktel_ext' => '9250',
            'fax' => '0',
            'fax_ext' => '0',
            'post_box' => '1664',
            'post_code' => '31952',
            'mobile' => '966534830703',
            'email' => 'aldawood3@gmail.com',
            'city' => 3,
            // Experiences and fields [JSON]
            'experiences_and_fields' => [
              'experiences' => [
                [
                  'name' =>  'جريدة المدينة',
                  'years' => 8
                ],
                [
                  'name' =>  'ايلاف الإلكترونية',
                  'years' => 6
                ],
                [
                  'name' =>  'جريدة الحياة',
                  'years' => 13
                ],
                [
                  'name' =>  'مدير المركز الإعلامي جامعة الأمير محمد بن فهد',
                  'years' => 16
                ],
              ],
              'fields' => [],
              'languages' => [
                [
                  'name' => 'العربية',
                  'level' => 1
                ],
                [
                  'name' => 'الأنجليزية',
                  'level' => 3
                ],
              ]
            ],

            // Files
            'profile_image' => null,
            'national_image' => null,
            'employer_letter' => null,

            // To be updated options
            'updated_personal_information' => 0,
            'updated_profile_image' => 0,
            'updated_national_image' => 0,
            'updated_employer_letter' => 0,
            'updated_experiences_and_fields' => 0,

            // Membership information
            'membership_number' => '1-001',
            'membership_type' => 1,
            'membership_start_date' => '2022-04-09',
            'membership_end_date' => '2022-12-31',
            'invoice_id' => null,
            // 'invoice_status' => null,
            'status' => 0,
            'password' => bcrypt('123456')
          ],
        ];

        collect($records)->each(function( $record ) {
          Member::create($record);
        });
    }
}
