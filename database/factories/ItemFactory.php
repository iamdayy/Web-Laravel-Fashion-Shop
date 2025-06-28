<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class ItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'photo' => '/storage/images/cat-3.jpg',
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'stock' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->numberBetween(10000, 1000000),
            'category' => $this->faker->randomElement(['clothing', 'accessories', 'shoes', 'electronics', 'home']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
