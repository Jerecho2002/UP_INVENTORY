<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class RoleFactory extends Factory
{
    // The name of the factory's corresponding model.
    protected $model = \App\Models\Role::class;

    public function definition()
    {
        return [
            'name'       => fake()->randomElement(['admin', 'staff']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
