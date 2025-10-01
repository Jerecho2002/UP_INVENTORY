<?php

namespace Database\Factories;

use App\Models\AcknowledgementReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcknowledgementItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'acknowledgement_id' => AcknowledgementReceipt::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'unit_cost' => $this->faker->randomFloat(2, 100, 5000),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
