<?php

namespace Database\Factories;

use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryTransactionFactory extends Factory
{
    public function definition(): array
    {
        return [
            'item_id' => InventoryItem::factory(),
            'quantity' => $this->faker->numberBetween(1, 10),
            'date_released' => $this->faker->date(),
        ];
    }
}
