<?php

namespace Database\Factories;

use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class OfficeFactory extends Factory
{
    protected $model = Office::class;

    public function definition()
    {
        return [
            'office_name' => $this->faker->unique()->company,
            'status'      => $this->faker->numberBetween(0, 2),
        ];
    }
}
