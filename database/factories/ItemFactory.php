<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Alirezasedghi\LaravelImageFaker\ImageFaker;
use Alirezasedghi\LaravelImageFaker\Services\Picsum;

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
        $imageFaker = new ImageFaker(new Picsum());
        return [
            'photo' => '/storage/images/items/' . $imageFaker->image(storage_path('app/public/images/items'), 640, 480, false, true, true),
            'name' => fake()->word(),
            'description' => fake()->sentence(),
            'stock' => $this->faker->numberBetween(1, 100),
            'price' => $this->faker->numberBetween(10000, 1000000),
            'category' => $this->faker->randomElement(['accessories', 'shoes', 'women', 'men', 'children', 'parfume']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
