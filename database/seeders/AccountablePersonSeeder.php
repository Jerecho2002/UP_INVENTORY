<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AccountablePerson;

class AccountablePersonSeeder extends Seeder
{
    public function run(): void
    {
        AccountablePerson::factory(10)->create();
    }
}
