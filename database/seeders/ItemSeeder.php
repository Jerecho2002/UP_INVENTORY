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
        Role::factory()->count(5)->create();
        Item::factory()->count(10)->create();
    }
}
