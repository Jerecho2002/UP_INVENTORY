<?php

namespace App\Services;

use App\Models\InventoryItem;

class ReportService
{
    public function getPaginatedInventory($search = null, $quantity = null, $from = null, $to = null)
    {
        return InventoryItem::with('location')
            ->when($search, function ($query) use ($search) {
                return $query->search($search);
            })
            ->when(
                $quantity,
                fn($query, $quantity) =>
                $query->where('quantity', $quantity === 'greater_than_zero' ? '>' : '<=', 0)
            )
            ->when($from, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->paginate(8)
            ->withQueryString();
    }

}
