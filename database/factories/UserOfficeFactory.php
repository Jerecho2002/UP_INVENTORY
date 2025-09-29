<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserOfficeFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'office_id' => Office::factory(),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
