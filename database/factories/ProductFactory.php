<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Product>
 */
class ProductFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $userId = User::inRandomOrder()->first()->id;
        return [
            'title' => fake()->sentence(5),
            'slug' => fake()->slug(),
            'price' => $this->faker->randomFloat(2, 1, 1000),
            'image' => fake()->imageUrl(640, 480),
            'user_id' => $userId
        ];
    }
}
