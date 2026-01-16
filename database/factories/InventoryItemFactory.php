<?php

namespace Database\Factories;

use App\Models\Supplier;
use App\Models\ItemClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    public function definition(): array
    {
        $unitCost = $this->faker->randomFloat(2, 30000, 99000);
        $qty = $this->faker->numberBetween(1, 20);

        static $groupCounters = []; // Keeps track of last "group" for each classification code

        return [
            'item_classification_id' => ItemClassification::factory(),
            'fund_source' => strtoupper($this->faker->unique()->bothify('FS-###')),
            'invoice' => strtoupper($this->faker->unique()->bothify('INV-###')),
            'supplier_id' => Supplier::factory(),
            'item_name' => $this->faker->word(),
            'description' => $this->faker->sentence(),
            'quantity' => 1,
            'unit' => $this->faker->randomElement(['pcs', 'box', 'unit']),
            'unit_cost' => $unitCost = $this->faker->randomFloat(2, 30000, 99000),
            'total_amount' => $qty * $unitCost,
            'pr_number' => strtoupper($this->faker->unique()->bothify('PR-###')),
            'po_number' => strtoupper($this->faker->unique()->bothify('PO-###')),
            // Property number format: 250-1-1, 250-1-2, ...
            'property_number' => function () use (&$groupCounters) {
                $code = $this->faker->randomElement(['250', '451', '320', '150']);

                // Initialize if not set yet
                if (!isset($groupCounters[$code])) {
                    $groupCounters[$code] = [
                        'group' => 1,
                        'counter' => 1,
                    ];
                }

                // Get current group and counter
                $group = $groupCounters[$code]['group'];
                $itemNum = $groupCounters[$code]['counter']++;

                // Example output: 250-1-1
                return "{$code}-{$group}-{$itemNum}";
            },


            'serial_number' => strtoupper($this->faker->unique()->bothify('SER-###')),
            'remarks' => strtoupper($this->faker->unique()->bothify('RM-###')),
            'date_acquired' => $this->faker->date('Y-m-d', 'now'),
            'status' => $this->faker->numberBetween(0, 1),
        ];

    }
}
