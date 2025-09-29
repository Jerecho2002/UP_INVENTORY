<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    public function definition(): array
    {
        return [
            'invoice_number' => strtoupper($this->faker->unique()->bothify('INV###')),
            'invoice_date' => $this->faker->date(),
            'supplier_id' => Supplier::factory(),
            'status' => $this->faker->boolean ? 1 : 0,
            'created_by' => User::factory(),
        ];
    }
}
