<?php

namespace Database\Factories;

use App\Models\AccountablePerson;
use App\Models\Office;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AccountablePersonFactory extends Factory
{
    protected $model = AccountablePerson::class;

    public function definition()
    {
        return [
            'office_id' => Office::factory(),
            'user_id'   => User::factory(),
            'status'    => $this->faker->numberBetween(0, 2),
        ];
    }
}
