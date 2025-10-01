<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use App\Models\Office;
use App\Models\Invoice;
use App\Models\Location;
use App\Models\Property;
use App\Models\Supplier;
use App\Models\FundSource;
use App\Models\UserOffice;
use App\Models\UserProfile;
use App\Models\InventoryItem;
use App\Models\PurchaseOrder;
use App\Models\PurchaseRequest;
use Illuminate\Database\Seeder;
use App\Models\AccountablePerson;
use App\Models\AcknowledgementItem;
use App\Models\InventoryTransaction;
use App\Models\AcknowledgementReceipt;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ✅ Seed roles only once
        $roles = ['admin', 'staff', 'manager', 'user'];
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // ✅ Base data
        $users = User::factory(10)->create();
        $suppliers = Supplier::factory(5)->create();
        $fundSources = FundSource::factory(3)->create();
        $offices = Office::factory(3)->create();
        $locations = Location::factory(5)->create();

        // ✅ Invoices
        $invoices = Invoice::factory(5)->create([
            'supplier_id' => fn() => $suppliers->random()->id,
            'created_by'  => fn() => $users->random()->id,
        ]);

        // ✅ Purchase Requests
        $purchaseRequests = PurchaseRequest::factory(5)->create([
            'created_by' => fn() => $users->random()->id,
        ]);

        // ✅ Purchase Orders
        $purchaseOrders = PurchaseOrder::factory(5)->create([
            'supplier_id'         => fn() => $suppliers->random()->id,
            'purchase_request_id' => fn() => $purchaseRequests->random()->id,
            'created_by'          => fn() => $users->random()->id,
        ]);

        // ✅ Properties
        $properties = Property::factory(20)->create([
            'fund_source_id' => fn() => $fundSources->random()->id,
            'pr_id'          => fn() => $purchaseRequests->random()->id,
            'po_id'          => fn() => $purchaseOrders->random()->id,
            'invoice_id'     => fn() => $invoices->random()->id,
            'supplier_id'    => fn() => $suppliers->random()->id,
            'location_id'    => fn() => $locations->random()->id,
        ]);

        // ✅ Inventory Items
        $inventoryItems = InventoryItem::factory(30)->create([
            'property_id' => fn() => $properties->random()->id,
        ]);

        // ✅ Inventory Transactions
        InventoryTransaction::factory(15)->create([
            'item_id' => fn() => $inventoryItems->random()->id,
        ]);

        // ✅ Accountable Persons
        $accountablePersons = AccountablePerson::factory(5)->create([
            'office_id' => fn() => $offices->random()->id,
            'user_id'   => fn() => $users->random()->id,
        ]);

        // ✅ Acknowledgement Receipts
        $ackReceipts = AcknowledgementReceipt::factory(5)->create([
            'accountable_persons_id' => fn() => $accountablePersons->random()->id,
            'issued_by_id'           => fn() => $accountablePersons->random()->id,
            'fund_source_id'         => fn() => $fundSources->random()->id,
            'property_id'            => fn() => $properties->random()->id,
            'created_by'             => fn() => $users->random()->id,
        ]);

        // ✅ Acknowledgement Items
        AcknowledgementItem::factory(10)->create([
            'acknowledgement_id' => fn() => $ackReceipts->random()->id,
        ]);

        // ✅ User Offices
        UserOffice::factory(5)->create([
            'user_id'   => fn() => $users->random()->id,
            'office_id' => fn() => $offices->random()->id,
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
