<?php

namespace Database\Factories;

use App\Models\UsersProfile;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserProfileFactory extends Factory
{
    protected $model = UsersProfile::class;

    public function definition()
    {
        return [
            'user_id'         => User::factory(),
            'full_name'       => $this->faker->name(),
            'contact_number'  => $this->faker->phoneNumber(),
            'status'          => $this->faker->numberBetween(0, 2),
        ];
    }
}
