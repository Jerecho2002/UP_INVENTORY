<?php

namespace Database\Factories;

use App\Models\Location;
use Illuminate\Database\Eloquent\Factories\Factory;

class LocationFactory extends Factory
{
    protected $model = Location::class;

    public function definition()
    {
        return [
            'location_name' => $this->faker->unique()->city(),
            'status'        => $this->faker->numberBetween(0, 2),
        ];
    }
}
