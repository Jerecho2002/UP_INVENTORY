<?php

namespace App\Services;

use App\Models\ItemClassification;

class CategoriesService
{
    public function filterAndPaginateCategories(
        ?string $search = null,
        ?string $costRange = null,
        int|string|null $status = null,
        int $perPage = 10
    ) {
        return ItemClassification::query()
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

    public function createCategories(array $data): ItemClassification
    {
        return ItemClassification::create([
            'classification_name' => $data['classification_name'],
            'classification_code' => $data['classification_code'] ?? null,
            'status' => 1,
        ]);
    }
}