<?php

namespace App\Services;

use App\Models\InventoryItem;
use Illuminate\Http\Request;

class DashboardService
{
    public function filterAndPaginateInventory(
        ?string $search = null,
        int $perPage = 10
    ) {
        return InventoryItem::with( 'supplier')
            ->when(
                $search,
                fn($query, $search) =>
                $query->search($search)
            )
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

}