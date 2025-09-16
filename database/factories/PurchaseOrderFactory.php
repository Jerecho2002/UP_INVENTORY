<?php

namespace Database\Factories;

use App\Models\PurchaseOrder;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    protected $model = PurchaseOrder::class;

    public function definition()
    {
        return [
            'po_number'   => $this->faker->unique()->bothify('PO-#####'),
            'po_date'     => $this->faker->date(),
            'supplier_id' => Supplier::factory(),
            'created_by'  => User::factory(),
            'status'      => $this->faker->numberBetween(0, 2),
        ];
    }
}
