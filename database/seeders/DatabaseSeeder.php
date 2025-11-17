<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Supplier;
use App\Models\UserOffice;
use App\Models\UserProfile;
use App\Models\InventoryItem;
use Illuminate\Database\Seeder;
use App\Models\AccountablePerson;
use App\Models\AcknowledgementItem;
use App\Models\InventoryTransaction;
use App\Models\AcknowledgementReceipt;
use App\Models\ItemClassification;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Seed roles only once
        $roles = ['admin', 'staff'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ✅ Base data
        $users = User::factory(10)->create();
        $suppliers = Supplier::factory(5)->create();
        $itemClassifications = ItemClassification::factory(13)->create();


        // ✅ Inventory Items
        $inventoryItems = InventoryItem::factory(100)->create([
            'item_classification_id' => fn() => $itemClassifications->random()->id,
            'supplier_id' => fn() => $suppliers->random()->id,
        ]);

        // ✅ Inventory Transactions
        InventoryTransaction::factory(15)->create([
            'inventory_item_id' => fn() => $inventoryItems->random()->id,
        ]);

        // ✅ Accountable Persons
        $accountablePersons = AccountablePerson::factory(5)->create([
            'user_id'   => fn() => $users->random()->id,
        ]);

        // ✅ Acknowledgement Receipts
        $ackReceipts = AcknowledgementReceipt::factory(30)->create([
            'accountable_persons_id' => fn() => $accountablePersons->random()->id,
            'issued_by_id'           => fn() => $accountablePersons->random()->id,
            'inventory_item_id'      => fn() => $inventoryItems->random()->id,
            'created_by'             => fn() => $users->random()->id,
        ]);

        // ✅ Acknowledgement Items
        AcknowledgementItem::factory(10)->create([
            'acknowledgement_id' => fn() => $ackReceipts->random()->id,
        ]);

        // ✅ User Offices
        UserOffice::factory(5)->create([
            'user_id'   => fn() => $users->random()->id,
        ]);

        // ✅ User Profiles
        UserProfile::factory(10)->create([
            'user_id' => fn() => $users->random()->id,
        ]);

        // ✅ Guaranteed Admin account
        User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'password' => bcrypt('password'),
                'role_id'  => Role::where('name', 'admin')->first()->id,
                'status'   => 1,
            ]
        );
    }
}
