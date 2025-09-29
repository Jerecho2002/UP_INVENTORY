<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'full_name' => $this->faker->name(),
            'contact_number' => $this->faker->phoneNumber(),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
