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
            'name' => $this->faker->unique()->jobTitle(), // Generates unique job titles like "Manager", "Developer", etc.
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
