<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Property;
use App\Models\FundSource;
use App\Models\AccountablePerson;
use Illuminate\Database\Eloquent\Factories\Factory;

class AcknowledgementReceiptFactory extends Factory
{
    public function definition(): array
    {
        return [
            'par_date' => $this->faker->date(),
            'accountable_persons_id' => AccountablePerson::factory(),
            'issued_by_id' => AccountablePerson::factory(),
            'fund_source_id' => FundSource::factory(),
            'property_id' => Property::factory(),
            'remarks' => $this->faker->sentence(),
            'created_by' => User::factory(),
        ];
    }
}
