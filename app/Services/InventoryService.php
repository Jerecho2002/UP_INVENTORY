<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryService
{
    public function getPaginatedInventory($search = null, $costRange = null)
    {
        // Start a query and eager-load 'property' and 'acknowledgementReceipts' relationships
        // This returns an Eloquent query builder instance
        return InventoryItem::with('acknowledgementReceipts', 'itemClassification')

            // Apply search filter only if $search has a value
            ->when($search, fn($query, $search) => $query->search($search))

            // Apply cost range filter only if $costRange has a value
            ->when($costRange, function ($query, $costRange) {
                [$min, $max] = explode('-', $costRange);

                if ($min !== '' && $max !== '') {
                    // Both bounds exist
                    $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
                } elseif ($min !== '' && $max === '') {
                    // Only minimum given → "₱50,000 and above"
                    $query->where('unit_cost', '>=', (float) $min);
                } elseif ($min === '' && $max !== '') {
                    // Only maximum given → "Up to ₱50,000"
                    $query->where('unit_cost', '<=', (float) $max);
                }
            })

            // Limit the result to 8 items per page
            ->paginate(8)

            // Keep the query string (like ?search=value) in pagination links
            ->withQueryString();
    }

}