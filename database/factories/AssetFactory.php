<?php

namespace Database\Factories;

use App\Models\Asset;
use App\Models\FundSource;
use App\Models\PurchaseRequest;
use App\Models\PurchaseOrder;
use App\Models\Invoice;
use App\Models\Supplier;
use App\Models\Location;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AssetFactory extends Factory
{
    protected $model = Asset::class;

    public function definition()
    {
        return [
            'property_no'     => $this->faker->unique()->bothify('PROP-#####'),
            'description'     => $this->faker->sentence(),
            'unit'            => $this->faker->randomElement(['pcs', 'box', 'set', 'unit']),
            'unit_cost'       => $this->faker->randomFloat(2, 10, 10000),
            'date_acquired'   => $this->faker->date(),
            'fund_source_id'  => FundSource::factory(),
            'pr_id'           => PurchaseRequest::factory(),
            'po_id'           => PurchaseOrder::factory(),
            'invoice_id'      => Invoice::factory(),
            'supplier_id'     => Supplier::factory(),
            'location_id'     => Location::factory(),
            'sticker_code'    => $this->faker->optional()->bothify('STK-#####'),
            'warranty_months' => $this->faker->optional()->numberBetween(6, 36),
            'remark'          => $this->faker->optional()->sentence(),
            'status'          => $this->faker->numberBetween(0, 2),
            'created_by'      => User::factory(),
        ];
    }
}
