<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    public function definition(): array
    {
        return [
            'location_name' => $this->faker->unique()->city(),
            'office_id' => Office::factory(),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
