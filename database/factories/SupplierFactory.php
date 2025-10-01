<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    public function definition(): array
    {
        return [
            'supplier_name' => $this->faker->unique()->company(),
            'contact_no' => $this->faker->phoneNumber(),
            'address' => $this->faker->address(),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
