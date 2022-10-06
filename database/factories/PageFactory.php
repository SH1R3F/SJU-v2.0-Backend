<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Page>
 */
class PageFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title_ar'   => $title = $this->faker->sentence(),
            'title_en'   => $this->faker->sentence(),
            'slug'       => Str::slug($title),
            'content_ar' => $this->faker->paragraph(20),
            'content_en' => $this->faker->paragraph(20),
        ];
    }
}
