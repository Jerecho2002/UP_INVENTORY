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
            'status' => $this->faker->boolean ? 1 : 0,
            'user_id' => User::factory(),
        ];
    }
}
