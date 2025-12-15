<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Order>
 */
class OrderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => fake()->numberBetween(1, 10),
            'status_id' => fake()->numberBetween(1, 7),
            'delivery_id' => fake()->numberBetween(1, 2),
            'total' => fake()->randomFloat(2, 1, 1000)
        ];
    }
}
