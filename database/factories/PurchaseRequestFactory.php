<?php

namespace Database\Factories;

use App\Models\PurchaseRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PurchaseRequestFactory extends Factory
{
    protected $model = PurchaseRequest::class;

    public function definition()
    {
        return [
            'pr_number'  => $this->faker->unique()->bothify('PR-#####'),
            'pr_date'    => $this->faker->date(),
            'created_by' => User::factory(),
            'status'     => $this->faker->numberBetween(0, 2),
        ];
    }
}
