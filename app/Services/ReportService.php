<?php

namespace App\Services;

use App\Models\InventoryItem;

class ReportService
{
    public function getPaginatedInventory($search = null, $quantity = null, $from = null, $to = null)
    {
        return InventoryItem::with('supplier')
            ->when($search, function ($query) use ($search) {
                return $query->search($search);
            })
            ->when($quantity, function ($query) use ($quantity) {

                if ($quantity === 'in_stock') {
                    // quantity > 5
                    return $query->where('quantity', '>', 5);
                }

                if ($quantity === 'low_stock') {
                    // quantity 1–5
                    return $query->whereBetween('quantity', [1, 5]);
                }

                if ($quantity === 'out_of_stock') {
                    // quantity <= 0
                    return $query->where('quantity', '<=', 0);
                }

                return $query;
            })
            ->when($from, function ($query) use ($from) {
                return $query->whereDate('created_at', '>=', $from);
            })
            ->when($to, function ($query) use ($to) {
                return $query->whereDate('created_at', '<=', $to);
            })
            ->paginate(10)
            ->withQueryString();
    }
}
