<?php

namespace App\Services;

use App\Models\AcknowledgementReceipt;

class AcknowledgementRecieptService
{
    public function getPaginatedInventory($search = null, $costRange = null, $status = null)
    {
        return AcknowledgementReceipt::with( 'accountablePerson.user', 'inventoryItems')
            ->when($search, fn($query, $search) => $query->search($search))
            ->when($costRange, function ($query, $costRange) {
                [$min, $max] = explode('-', $costRange);
                if (!is_null($min) && !is_null($max)) {
                    $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
                } elseif (!is_null($min) && !is_null($max)) {
                    $query->where('unit_cost', '>=', (float) $min);
                } elseif (!is_null($min) && !is_null($max)) {
                    $query->where('unit_cost', '<=', (float) $max);
                }
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate(8)
            ->withQueryString();
    }

}