<?php

namespace Database\Seeders;

use App\Models\Subscriber;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class SubscribersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // Subscribers Seeding From .sql File
        // Subscriber::unguard();
        // $sql = base_path('database/data/subscribers.sql');
        // DB::unprepared(file_get_contents($sql));

    }
}
