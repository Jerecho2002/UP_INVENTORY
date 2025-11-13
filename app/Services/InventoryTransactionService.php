<?php

namespace App\Services;

use Illuminate\Http\Request;
use App\Models\InventoryTransaction;

class InventoryTransactionService
{
    public function getPaginatedInventory($search = null, $costRange = null, $status)
    {
        // Start a query and eager-load 'property' and 'acknowledgementReceipts' relationships
        // This returns an Eloquent query builder instance
        return InventoryTransaction::with('inventoryItem')

            // Apply search filter only if $search has a value
            ->when($search, fn($query, $search) => $query->search($search))

            // Cost filter inside the inventoryItem relation
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
            // Apply status filter
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })

            // Limit the result to 8 items per page
            ->paginate(8)

            // Keep the query string (like ?search=value) in pagination links
            ->withQueryString();
    }

}