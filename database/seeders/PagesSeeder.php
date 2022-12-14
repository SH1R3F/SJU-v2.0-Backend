<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      // Pages Seeding From .sql File
      Page::unguard();
      $sql = base_path('database/data/pages.sql');
      DB::unprepared(file_get_contents($sql));

    }
}
