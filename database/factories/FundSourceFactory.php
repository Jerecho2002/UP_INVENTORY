<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class FundSourceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'code' => strtoupper($this->faker->unique()->bothify('FS###')),
            'description' => $this->faker->sentence(4),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
