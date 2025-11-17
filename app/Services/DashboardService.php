<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class DashboardService
{
    public function getPaginatedInventory($search = null)
    {
        return InventoryItem::with('supplier')
            ->when($search, fn($query, $search) => $query->search($search))
            ->paginate(10)
            ->withQueryString();
    }

}