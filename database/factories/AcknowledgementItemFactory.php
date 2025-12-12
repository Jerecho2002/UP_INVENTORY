<?php

namespace Database\Factories;

use App\Models\AcknowledgementReceipt;
use App\Models\InventoryItem;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcknowledgementItemFactory extends Factory
{
    public function definition(): array
    {
        return [
            'acknowledgement_id' => AcknowledgementReceipt::factory(),
            'inventory_item_id' => InventoryItem::factory(),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
