<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            ['name' => 'Buah'],
            ['name' => 'Kilogram'],
            ['name' => 'Gram'],
            ['name' => 'Liter'],
            ['name' => 'Mililiter'],
            ['name' => 'Kotak'],
            ['name' => 'Paket'],
            ['name' => 'Lusin'],
        ];

        DB::table('units')->insert($units);
    }
}
