<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class DashboardService
{
    public function getPaginatedInventory($search = null)
    {
        // Start a query and eager-load relationships
        // This returns an Eloquent query builder instance
        return InventoryItem::with('supplier')

            // Apply search filter only if $search has a value
            ->when($search, fn($query, $search) => $query->search($search))

            // Limit the result to 8 items per page
            ->paginate(8)

            // Keep the query string (like ?search=value) in pagination links
            ->withQueryString();
    }

}