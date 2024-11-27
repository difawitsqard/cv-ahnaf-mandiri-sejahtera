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
        $stock = fake()->numberBetween(1, 100);
        return [
            'name' => fake()->word,
            'price' => fake()->numberBetween(1000, 100000),
            'stock' =>  $stock,
            'min_stock' => fake()->numberBetween(1, 20),
            'total_stock' =>  $stock,
            'image_path' => null,
            'description' => fake()->sentence,
            'outlet_id' => Outlet::factory(),
            'unit_id' => Unit::factory(),
        ];
    }
}
