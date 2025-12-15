<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\OrderItem>
 */
class OrderItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'order_id' => fake()->numberBetween(1, 2),
            'menu_item_id' => fake()->numberBetween(1, 25),
            'size_id' => fake()->numberBetween(1, 2),
            'quantity' => fake()->numberBetween(1, 5)
        ];
    }
}
