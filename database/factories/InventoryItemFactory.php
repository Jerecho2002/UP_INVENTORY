<?php

namespace Database\Factories;

use App\Models\Property;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    public function definition(): array
    {
        $unitCost = $this->faker->randomFloat(2, 50, 1000);
        $qty = $this->faker->numberBetween(1, 20);

        return [
            'property_id' => Property::factory(),
            'item_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement([
                'PAR-' . str_pad($this->faker->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT),
                'ICS-' . str_pad($this->faker->numberBetween(0, 9999), 4, '0', STR_PAD_LEFT),
            ]),
            'quantity' => $qty,
            'unit' => $this->faker->randomElement(['pcs', 'box', 'unit']),
            'unit_cost' => $unitCost,
            'total_amount' => $qty * $unitCost,
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
