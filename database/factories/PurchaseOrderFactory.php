<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Supplier;
use App\Models\PurchaseRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseOrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'po_number' => strtoupper($this->faker->unique()->bothify('PO###')),
            'po_date' => $this->faker->date(),
            'supplier_id' => Supplier::factory(),
            'purchase_request_id' => PurchaseRequest::factory(),
            'status' => $this->faker->boolean ? 1 : 0,
            'created_by' => User::factory(),
        ];
    }
}
