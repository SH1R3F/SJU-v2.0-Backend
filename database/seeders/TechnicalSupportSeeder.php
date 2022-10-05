<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TechnicalSupportChat;
use App\Models\TechnicalSupportTicket;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class TechnicalSupportSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        TechnicalSupportTicket::factory()->count(200)->create();
        TechnicalSupportChat::factory()->count(1000)->create();
    }
}
