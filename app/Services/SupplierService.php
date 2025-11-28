<?php

namespace App\Services;

use App\Models\Supplier;

class SupplierService{
    public function filterAndPaginateSuppliers(
        ?string $search = null,
        ?string $costRange = null,
        int|string|null $status = null,
        int $perPage = 10
    ) {
        return Supplier::with('inventoryItems')
            ->when(
                $search,
                fn($query, $search) =>
                $query->search($search)
            )
            ->when($costRange, function ($query, $costRange) {

                [$min, $max] = array_pad(explode('-', $costRange), 2, null);

                $min = $min !== '' ? $min : null;
                $max = $max !== '' ? $max : null;

                if ($min !== null && $max !== null) {
                    $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
                } elseif ($min !== null) {
                    $query->where('unit_cost', '>=', (float) $min);
                } elseif ($max !== null) {
                    $query->where('unit_cost', '<=', (float) $max);
                }
            })
            ->when(
                !is_null($status),
                fn($query) =>
                $query->where('status', $status)
            )
            ->paginate($perPage)
            ->withQueryString();
    }

    public function getPaginatedInventory($search = null, $quantity = null, $from = null, $to = null)
    {
        return Supplier::with( 'inventoryItems')
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
            ->paginate(10)
            ->withQueryString();
    }
}