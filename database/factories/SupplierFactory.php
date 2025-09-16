<?php

namespace Database\Factories;

use App\Models\Supplier;
use Illuminate\Database\Eloquent\Factories\Factory;

class SupplierFactory extends Factory
{
    protected $model = Supplier::class;

    public function definition()
    {
        return [
            'supplier_name' => $this->faker->unique()->company(),
            'contact_no'    => $this->faker->optional()->phoneNumber(),
            'address'       => $this->faker->optional()->address(),
            'status'        => $this->faker->numberBetween(0, 2),
        ];
    }
}
