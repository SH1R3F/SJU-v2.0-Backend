<?php

namespace Database\Seeders;

use Carbon\Carbon;
use App\Models\Volunteer;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class VolunteersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Volunteers Seeding From .sql File
        Volunteer::unguard();
        $sql = base_path('database/data/volunteers.sql');
        DB::unprepared(file_get_contents($sql));
    }
}
