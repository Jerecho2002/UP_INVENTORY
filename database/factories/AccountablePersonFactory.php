<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Office;
use App\Models\User;

class AccountablePersonFactory extends Factory
{
    public function definition(): array
    {
        return [
            'full_name' => $this->faker->name(),
            'department' => $this->faker->randomElement([
                'Finance',
                'HR',
                'IT',
                'Procurement',
                'Operations',
                'Admin Office',
                'Supply Office',
            ]),
            'position' => $this->faker->jobTitle(),
            'status' => $this->faker->numberBetween(0, 1), // or always 0 (your choice)
        ];
    }
}
