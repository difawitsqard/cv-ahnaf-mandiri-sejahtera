<?php

namespace Database\Seeders;

use App\Models\Outlet;
use App\Models\StockItem;
use App\Models\Unit;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StockItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        StockItem::factory(30)->recycle([
            Outlet::all(),
            Unit::all(),
        ])->create();
    }
}
