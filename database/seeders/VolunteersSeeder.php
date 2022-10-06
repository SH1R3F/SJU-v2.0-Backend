<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Volunteer;
use Illuminate\Support\Facades\Hash;

class VolunteersSeeder extends Seeder
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
            'fname_ar' => 'محمد',
            'sname_ar' => 'عيسى',
            'tname_ar' => 'أحمد',
            'lname_ar' => 'عيسى',
            'fname_en' => 'Mohammed',
            'sname_en' => 'Eisa',
            'tname_en' => 'Ahmed',
            'lname_en' => 'Eisa',
            
            'gender'          => 0,
            'country'         => 1,
            'city'            => 3,
            'nationality'     => 1,
            'birthday_hijri'  => '1412-03-22',
            'birthday_meladi' => '1991-09-30',

            'qualification' => 6,

            'mobile'      => '558170052',
            'mobile_key'  => '966',
            'email'       => 'moheis@outlook.com',
            'password'    => Hash::make('secret'),
            'status'      => 1,

          ],
          [
            'fname_ar' => 'محمد',
            'sname_ar' => 'عبدالعزيز',
            'tname_ar' => 'عبدالله',
            'lname_ar' => 'القحطاني',
            'fname_en' => 'Mohammed',
            'sname_en' => 'Abdulaziz',
            'tname_en' => 'Abdullah',
            'lname_en' => 'Alqahtani',
            
            'gender'          => 0,
            'country'         => 1,
            'city'            => 10,
            'nationality'     => 1,
            'birthday_hijri'  => '1412-03-22',
            'birthday_meladi' => '1991-09-30',

            'qualification' => 6,

            'mobile'      => '558410052',
            'mobile_key'  => '966',
            'email'       => 'msadeis@outlook.com',
            'password'    => Hash::make('secret'),
            'status'      => 0,
          ]
        ];

        collect($records)->each(function( $record ) {
          Volunteer::create($record);
        });
    }
}