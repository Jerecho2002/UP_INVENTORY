<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\AccountablePerson;
use App\Models\AcknowledgementReceipt;
use App\Models\AcknowledgementItem;

class AcknowledgementSeeder extends Seeder
{
    public function run(): void
    {
        // Base data (must already exist)
        $users = User::all();
        $accPersons = AccountablePerson::all();

        // Safety checks (avoid empty-table crashes)
        if ($users->isEmpty() || $accPersons->isEmpty()) {
            $this->command?->warn('AcknowledgementSeeder skipped: missing users or accountable persons.');
            return;
        }

        // Acknowledgement Receipts
        $ackReceipts = AcknowledgementReceipt::factory(30)->create([
            'accountable_persons_id' => fn () => $accPersons->random()->id,
            'issued_by_id'           => fn () => $users->random()->id,
            'created_by'             => fn () => $users->random()->id,
        ]);

        // Acknowledgement Items
        AcknowledgementItem::factory(10)->create([
            'acknowledgement_id' => fn () => $ackReceipts->random()->id,
        ]);
    }
}
