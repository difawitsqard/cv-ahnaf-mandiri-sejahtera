<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\CompanyInfo>
 */
class CompanyInfoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->company,
            'short_name' => $this->faker->lexify('??????'),
            'tagline' => $this->faker->text(10),
            'about_us' => $this->faker->paragraph,
            'email' => $this->faker->companyEmail,
            'phone' => $this->faker->phoneNumber,
            'whatsapp' => $this->faker->phoneNumber,
            'instagram' => $this->faker->userName,
            'address' => $this->faker->address,
        ];
    }
}
