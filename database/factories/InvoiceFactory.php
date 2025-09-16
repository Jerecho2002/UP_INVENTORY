<?php

namespace Database\Factories;

use App\Models\Invoice;
use App\Models\User;
use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class InvoiceFactory extends Factory
{
    protected $model = Invoice::class;

    public function definition()
    {
        return [
            'invoice_number' => $this->faker->unique()->bothify('INV-#####'),
            'invoice_date'   => $this->faker->date(),
            'supplier_id'    => Supplier::factory(),
            'created_by'     => User::factory(),
            'status'         => $this->faker->numberBetween(0, 2),
        ];
    }
}
