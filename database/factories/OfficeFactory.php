<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'office_name' => $this->faker->unique()->company . ' Office',
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
