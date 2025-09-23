<?php

namespace Database\Seeders;

use App\Models\Item;
use App\Models\Role;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ItemSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
<<<<<<< HEAD
        Role::factory()->count(5)->create();
        Item::factory()->count(10)->create();
=======
        Item::factory()->count(100)->create();
>>>>>>> 2c9e2cd91b4744a17e896ff9ed4aa2cb40b6e99c
    }
}
