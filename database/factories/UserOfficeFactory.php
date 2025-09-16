<?php

namespace Database\Factories;

use App\Models\UsersOffice;
use App\Models\User;
use App\Models\Office;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserOfficeFactory extends Factory
{
    protected $model = UsersOffice::class;

    public function definition()
    {
        return [
            'user_id'   => User::factory(),
            'office_id' => Office::factory(),
            'status'    => $this->faker->numberBetween(0, 2),
        ];
    }
}
