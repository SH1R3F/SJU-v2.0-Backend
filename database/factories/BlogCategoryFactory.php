<?php

namespace Database\Factories;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogCategory>
 */
class BlogCategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'title_ar'       => $title = $this->faker->sentence(),
            'title_en'       => $this->faker->sentence(),
            'slug'           => Str::slug($title),
            'description_ar' => $this->faker->paragraph(2),
            'description_en' => $this->faker->paragraph(2),
        ];
    }
}
