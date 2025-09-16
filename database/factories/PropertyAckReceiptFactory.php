<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Office;
use App\Models\Location;
use App\Models\FundSource;
use App\Models\AccountablePerson;
use App\Models\PropertyAckReceipt;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\PropertyAckReceipt>
 */
class PropertyAckReceiptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
         return [
            'par_number'         => $this->faker->unique()->bothify('PAR-#####'),
            'par_date'           => $this->faker->date(),
            'person_responsible_id' => AccountablePerson::factory(),
            'office_id'          => Office::factory(),
            'new_accountable_id' => AccountablePerson::factory(),
            'issued_by_id'       => AccountablePerson::factory(),
            'previous_par_id'    => null, // handled later
            'fund_source_id'     => FundSource::factory(),
            'location_id'        => Location::factory(),
            'remark'             => $this->faker->optional()->sentence(),
            'status'             => $this->faker->numberBetween(0, 2),
            'created_by'         => User::factory(),
        ];
    }
}
