<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Roles yang akan diassign ke user
        $roles = Role::pluck('name');

        foreach ($roles as $roleName) {
            // Buat user untuk setiap role
            $user = User::factory()->create([
                'name' => ucfirst($roleName),
                'email' => strtolower($roleName) . '@example.com',
            ]);

            // Assign role ke user
            $user->assignRole($roleName);
        }

        // $roles = $roles->reject(function ($role) {
        //     return in_array($role, ['superadmin']);
        // });

        // //fake
        // User::factory(51)->create()->each(function ($user) use ($roles) {
        //     $user->assignRole($roles->random());
        // });
    }
}
