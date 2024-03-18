<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Category>
 */
class CategoryFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $categoryNames = ['Fashion', 'Accessories', 'Electronics', 'Home and Decor'];
        $randomCategoryName = $this->faker->randomElement($categoryNames);

        return [
            'name' => $randomCategoryName,
            'description' => fake()->text(),
            'image' => fake()->imageUrl(),
        ];
    }
}
