<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Alirezasedghi\LaravelImageFaker\ImageFaker;
use Alirezasedghi\LaravelImageFaker\Services\Picsum;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class BannerFactory extends Factory
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
            'imageUrl' => '/storage/images/banners/' . $imageFaker->image(storage_path('app/public/images/banners'), 640, 480, false, true, true),
            'redirectUrl' => $this->faker->url(),
        ];
    }
}
