<?php

namespace Database\Factories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Factories\Factory;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition()
    {
        static $roleIds;

        // Cache role IDs so we don't query every time
        if (!$roleIds) {
            $roleIds = Role::pluck('id')->all();

            // If roles table is empty (e.g., running this factory in isolation), create them
            if (empty($roleIds)) {
                $admin = Role::firstOrCreate(['name' => 'admin']);
                $staff = Role::firstOrCreate(['name' => 'staff']);
                $roleIds = [$admin->id, $staff->id];
            }
        }

        return [
            'email'    => $this->faker->unique()->safeEmail(),
            'password' => Hash::make('password'), // or bcrypt('password')
            'status'   => $this->faker->numberBetween(0, 1),
            'role_id'    => fake()->randomElement($roleIds),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}
