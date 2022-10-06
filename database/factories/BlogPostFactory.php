<?php

namespace Database\Factories;

use App\Models\BlogCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\BlogPost>
 */
class BlogPostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $category = BlogCategory::inRandomOrder()->first();
        return [
            'blog_category_id' => $category->id,
            'title_ar'         => $this->faker->sentence(),
            'title_en'         => $this->faker->sentence(),
            'post_Date'        => $this->faker->date(),
            'summary_ar'       => $this->faker->sentence(2),
            'summary_en'       => $this->faker->sentence(2),
            'content_ar'       => $this->faker->paragraph(20),
            'content_en'       => $this->faker->paragraph(20),
        ];
    }
}
