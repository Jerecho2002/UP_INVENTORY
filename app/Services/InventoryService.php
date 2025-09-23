<?php

namespace App\Services;

use App\Models\Item;
use Illuminate\Http\Request;

class InventoryService{
    public function getPaginatedInventory($search = null)
    {
        // Start a query and eager-load 'asset' and 'propertyAckReceipt' relationships
        // This returns an Eloquent query builder instance
        return Item::with('asset', 'propertyAckReceipt')
            // Apply the 'search' scope only if $search has a value
            // $query here is the query builder, passed automatically into the closure
            ->when($search, fn($query, $search) => $query->search($search))
            // Limit the result to 5 items per page
            ->paginate(8)
            // Keep the query string (like ?search=value) in pagination links
            ->withQueryString();
    }
}