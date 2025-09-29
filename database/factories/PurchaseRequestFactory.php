<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseRequestFactory extends Factory
{
    public function definition(): array
    {
        return [
            'pr_number' => strtoupper($this->faker->unique()->bothify('PR###')),
            'pr_date' => $this->faker->date(),
            'status' => $this->faker->boolean ? 1 : 0,
            'created_by' => User::factory(),
        ];
    }
}
