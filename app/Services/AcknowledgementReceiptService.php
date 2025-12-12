<?php

namespace App\Services;

use App\Models\AcknowledgementItem;
use App\Models\InventoryItem;
use App\Models\AcknowledgementReceipt;

class AcknowledgementReceiptService
{
    public function filterAndPaginateAcknowledgementReceipt(
        ?string $search = null,
        ?string $costRange = null,
        int $perPage = 10
    ) {
        return InventoryItem::with('supplier')
            ->when(
                $search,
                fn($query, $search) => $query->search($search)
            )
            ->when($costRange, function ($query, $costRange) {

                [$min, $max] = array_pad(explode('-', $costRange), 2, null);

                $min = $min !== '' ? $min : null;
                $max = $max !== '' ? $max : null;

                if ($min !== null && $max !== null) {
                    $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
                } elseif ($min !== null) {
                    $query->where('unit_cost', '>=', (float) $min);
                } elseif ($max !== null) {
                    $query->where('unit_cost', '<=', (float) $max);
                }
            })
            // Always exclude cancelled items
            ->where('status', '!=', 0)
            // Exclude items that already have acknowledgementItems
            ->whereDoesntHave('acknowledgementItems')
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }



    // public function createAcknowledgements(array $data)
    // {
    //     foreach ($data['inventory_item_id'] as $itemId) {
    //         $ack = AcknowledgementReceipt::create([
    //             'accountable_persons_id' => $data['accountable_persons_id'],
    //             'issued_by_id' => $data['issued_by_id'],
    //             'category' => $data['issued_by_id'],
    //             'created_by' => $data['created_by'],
    //             'par_date' => $data['par_date'],
    //             'remarks' => $data['remarks'] ?? null,
    //         ]);

    //         AcknowledgementItem::create([
    //             'acknowledgement_id' => $ack,
    //             'inventory_item_id' => $itemId
    //         ]);
    //     }
    // }
    public function createAcknowledgements(array $data)
    {
        foreach ($data['inventory_item_id'] as $itemId) {
        $ack = AcknowledgementReceipt::create([
            'accountable_persons_id' => $data['accountable_persons_id'],
            'issued_by_id' => $data['issued_by_id'],
            'category' => $data['category'],
            'created_by' => $data['created_by'],
            'par_date' => $data['par_date'],
            'remarks' => $data['remarks'] ?? null,
        ]);

        
            AcknowledgementItem::create([
                'acknowledgement_id' => $ack->id,
                'inventory_item_id' => $itemId
            ]);
        }
    }
}