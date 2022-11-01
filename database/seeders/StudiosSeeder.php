<?php

namespace Database\Seeders;

use App\Models\Studio;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StudiosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

      // Studios Seeding From .sql File
      Studio::unguard();
      $sql = base_path('database/data/studios.sql');
      DB::unprepared(file_get_contents($sql));

    }
}
