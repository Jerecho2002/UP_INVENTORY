<?php

namespace App\Services;

use App\Models\InventoryTransaction;

class InventoryTransactionService
{
    public function filterAndPaginate($search = null, $costRange = null, $status)
    {
        return InventoryTransaction::with('inventoryItem')
            ->when($search, fn($query, $search) => $query->search($search))
            ->when($costRange, function ($query, $costRange) {
                [$min, $max] = explode('-', $costRange);

                $query->whereHas('inventoryItem', function ($q) use ($min, $max) {
                    if ($min !== '' && $max !== '') {
                        $q->whereBetween('unit_cost', [(float) $min, (float) $max]);
                    } elseif ($min !== '' && $max === '') {
                        $q->where('unit_cost', '>=', (float) $min);
                    } elseif ($min === '' && $max !== '') {
                        $q->where('unit_cost', '<=', (float) $max);
                    }
                });
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate(10)
            ->withQueryString();
    }

}