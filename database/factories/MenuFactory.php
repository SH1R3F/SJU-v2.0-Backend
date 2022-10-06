<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Menu>
 */
class MenuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title_ar' => $this->faker->word,
            'title_en' => $this->faker->word,
            'link'     => 'http://localhost/phpmyadmin/',
            'open_in_same_page' => 1,
        ];
    }
}
