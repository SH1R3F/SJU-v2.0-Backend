<?php

namespace Database\Factories;

use App\Models\Member;
use App\Models\Volunteer;
use App\Models\Subscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TechnicalSupport>
 */
class TechnicalSupportTicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {

        $userInstances = [
          new Member,
          new Subscriber,
          new Volunteer
        ];

        $userInstance = $userInstances[array_rand($userInstances)];
        $userType     = get_class($userInstance);
        $user         = $userInstance->inRandomOrder()->first();

        return [
            'title' => $this->faker->sentence(),
            'status' => $this->faker->boolean(),
            'ticketable_id' => $user->id,
            'ticketable_type' => $userType
        ];
    }
}
