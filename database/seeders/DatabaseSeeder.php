<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\MembersSeeder;
use Database\Seeders\SiteOptionsSeeder;
use Database\Seeders\SubscribersSeeder;
use Database\Seeders\Courses\TypesSeeder;
use Database\Seeders\Courses\CoursesSeeder;
use Database\Seeders\Courses\GendersSeeder;
use Database\Seeders\TechnicalSupportSeeder;
use Database\Seeders\Courses\LocationsSeeder;
use Database\Seeders\Courses\TemplatesSeeder;
use Database\Seeders\Courses\CategoriesSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
use Database\Seeders\Courses\QuestionnairesSeeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
      $this->call([
        RolesAndPermissionsSeeder::class,
        MembersSeeder::class,
        SubscribersSeeder::class,
        VolunteersSeeder::class,
        TypesSeeder::class,
        GendersSeeder::class,
        LocationsSeeder::class,
        CategoriesSeeder::class,
        TemplatesSeeder::class,
        QuestionnairesSeeder::class,
        CoursesSeeder::class,
        SiteOptionsSeeder::class,
        TechnicalSupportSeeder::class,
      ]);
    }
}
