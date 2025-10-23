<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\FundSource;
use App\Models\ItemClassification;
use Illuminate\Database\Eloquent\Factories\Factory;

class InventoryItemFactory extends Factory
{
    public function definition(): array
    {
        $unitCost = $this->faker->randomFloat(2, 50, 1000);
        $qty = $this->faker->numberBetween(1, 20);

        return [
            'item_classification_id' => ItemClassification::factory(),
            'supplier_id' => Supplier::factory(),
            'location_id' => Location::factory(),
            'invoice_id' => Invoice::factory(),
            'fund_source_id' => FundSource::factory(),
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
            'property_number' => strtoupper($this->faker->unique()->bothify('PROP###')),
            'serial_number' => strtoupper($this->faker->unique()->bothify('SER###')),
            'pr_number' => strtoupper($this->faker->unique()->bothify('PR###')),
            'po_number' => strtoupper($this->faker->unique()->bothify('PO###')),
            'remarks' => strtoupper($this->faker->unique()->bothify('RM###')),
            'date_acquired' => $this->faker->date('Y-m-d', 'now'),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
