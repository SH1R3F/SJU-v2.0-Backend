<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Database\Seeders\SubscribersSeeder;
use Database\Seeders\RolesAndPermissionsSeeder;
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
        SubscribersSeeder::class,
      ]);
    }
}
