<?php

namespace Database\Factories;

use App\Models\TechnicalSupportTicket;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechnicalSupportChat>
 */
class TechnicalSupportChatFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $ticket_id = TechnicalSupportTicket::inRandomOrder()->first()->id;
        return [
            'technical_support_ticket_id' => $ticket_id,
            'message' => $this->faker->sentence(),
            'sender'  => rand(1,2)
        ];
    }
}
