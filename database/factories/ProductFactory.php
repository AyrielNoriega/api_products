<?php

namespace Database\Factories;

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
        return [
            'title'         => fake()->name(),
            'image'         => 'https://goshofi.com/wp-content/uploads/2023/03/kit1-scaled.jpg', //fake()->imageUrl($width = 640, $height = 480),
            'description'   => fake()->sentence($nbWords = 7, $variableNbWords = true),
            'price'         => fake()->numberBetween(10000, 60000),
            'user_id'       => function () {
                return \App\Models\User::query()->inRandomOrder()->first()->id;
            },
        ];
    }
}
