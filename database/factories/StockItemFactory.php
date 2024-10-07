<?php

namespace Database\Factories;

use App\Models\Unit;
use App\Models\Outlet;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\StockItem>
 */
class StockItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'item_name' => fake()->word,
            'outlet_id' => Outlet::factory(),
            'price' => fake()->numberBetween(1000, 100000),
            'quantity' => fake()->numberBetween(1, 100),
            'unit_id' => Unit::factory(),
        ];
    }
}
