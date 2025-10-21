<?php

namespace App\Services;

use App\Models\InventoryItem;

class ReportService
{
    public function getPaginatedInventory($search = null, $status = null, $from = null, $to = null)
    {
        return InventoryItem::with('location')
            ->when($search, fn($query, $search) => $query->search($search))
            ->when($status !== null && $status !== '', fn($query) => 
                $query->where('status', $status)
            )
            ->when($from, fn($query) => 
                $query->whereDate('created_at', '>=', $from)
            )
            ->when($to, fn($query) => 
                $query->whereDate('created_at', '<=', $to)
            )
            ->paginate(8)
            ->withQueryString();
    }
}
