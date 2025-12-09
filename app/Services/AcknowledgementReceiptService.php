<?php

namespace App\Services;

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
            // Exclude items that already have acknowledgementReceipts
            ->whereDoesntHave('acknowledgementReceipts')
            ->paginate($perPage)
            ->withQueryString();
    }



    public function createAcknowledgements(array $data)
    {
        foreach ($data['inventory_item_id'] as $itemId) {
            AcknowledgementReceipt::create([
                'inventory_item_id' => $itemId,
                'accountable_persons_id' => $data['accountable_persons_id'],
                'issued_by_id' => $data['issued_by_id'],
                'created_by' => $data['created_by'],
                'par_date' => $data['par_date'],
                'remarks' => $data['remarks'] ?? null,
            ]);
        }
    }
}
