<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\Location;
use App\Models\Supplier;
use App\Models\FundSource;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PropertyFactory extends Factory
{
    public function definition(): array
    {
        return [
            'property_number' => strtoupper($this->faker->unique()->bothify('PROP###')),
            'date_acquired' => $this->faker->date(),
            'fund_source_id' => FundSource::factory(),
            'pr_id' => PurchaseRequest::factory(),
            'po_id' => PurchaseOrder::factory(),
            'invoice_id' => Invoice::factory(),
            'supplier_id' => Supplier::factory(),
            'location_id' => Location::factory(),
            'remarks' => $this->faker->sentence(),
            'status' => $this->faker->boolean ? 1 : 0,
        ];
    }
}
