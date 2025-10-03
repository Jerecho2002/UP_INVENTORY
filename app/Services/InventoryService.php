<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class InventoryService{
    public function getPaginatedInventory($search = null, $costRange = null)
    {
        // Start a query and eager-load 'asset' and 'propertyAckReceipt' relationships
        // This returns an Eloquent query builder instance
        return InventoryItem::with('property', 'acknowledgementReceipts')
            // Apply the 'search' scope only if $search has a value
            // $query here is the query builder, passed automatically into the closure
            ->when($search, fn($query, $search) => $query->search($search))

            ->when($costRange, function($query, $costRange){
                [$min, $max] = explode('-', $costRange);
                $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
            })
            // Limit the result to 5 items per page
            ->paginate(8)
            // Keep the query string (like ?search=value) in pagination links
            ->withQueryString();
    }
}