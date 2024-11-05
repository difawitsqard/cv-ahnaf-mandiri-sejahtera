<?php

namespace Database\Seeders;

use App\Models\CompanyInfo;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $this->call([
            OutletSeeder::class,
            RolesAndPermissionsSeeder::class,
            UserSeeder::class,

            UnitSeeder::class,
            StockItemSeeder::class,
        ]);

        CompanyInfo::factory()->create([
            'name' => 'CV.Ahnaf Mandiri Sejahtera',
            'short_name' => 'CV.Ahnaf',
        ]);

        // User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
    }
}
