<?php

namespace Database\Seeders\Courses;

use App\Models\Course\Template;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TemplatesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Templates Seeding From .sql File
        Template::unguard();
        $sql = base_path('database/data/templates.sql');
        DB::unprepared(file_get_contents($sql));

    }
}
