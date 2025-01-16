<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\StockItemCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class StockItemCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Etalase',
                'is_static' => true,
            ],
            [
                'name' => 'Dapur',
                'is_static' => false,
            ],
            [
                'name' => 'Lainnya',
                'is_static' => false,
            ],
        ];

        foreach ($categories as $category) {
            StockItemCategory::create([
                'name' => $category['name'],
                'is_static' => $category['is_static'],
            ]);
        }

        // StockItemCategory::factory(10)->create();
    }
}
