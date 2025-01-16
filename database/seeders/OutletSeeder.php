<?php

namespace Database\Seeders;

use App\Models\Outlet;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class OutletSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $outlets = [
            [
                'name' => 'KitaSuka Fried Chiken',
                'address' => 'Gedong Panjang',
            ],
            [
                'name' => 'KitaSuka Fried Chiken',
                'address' => 'Cibungur',
            ],
        ];

        foreach ($outlets as $outlet) {
            Outlet::create([
                'name' => $outlet['name'],
                'address' => $outlet['address'],
            ]);
        }

        //Outlet::factory(10)->create();
    }
}
