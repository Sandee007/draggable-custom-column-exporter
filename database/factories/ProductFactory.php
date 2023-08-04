<?php

namespace Database\Factories;

use Illuminate\Support\Str;
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
            'name' => fake()->name(),
            'vendor' => fake()->company(),
            'brand' => fake()->companySuffix(),
            'phone' => fake()->phoneNumber(),
            'email' => fake()->email(),
            'price' => rand(1, 1000),
            'address' => fake()->address(),
            'category' => Str::random(10),
            'stock' => rand(1, 1000),
        ];
    }
}
