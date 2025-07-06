<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Offer>
 */
class OfferFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $imageFaker = new \Alirezasedghi\LaravelImageFaker\ImageFaker(new \Alirezasedghi\LaravelImageFaker\Services\Picsum());
        return [
            'title' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'discount_percentage' => $this->faker->numberBetween(5, 50),
            'start_date' => $this->faker->dateTimeBetween('-1 month', 'now'),
            'end_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'image' => '/storage/images/offers/' .  $imageFaker->image(storage_path('app/public/images/offers'), 640, 480, false, true, true),
            'link' => $this->faker->optional()->url(),
        ];
    }
}
