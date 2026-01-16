<?php

namespace App\Services;

use App\Models\AccountablePerson;

class AccountablePersonService
{
    public function filterAndPaginateAccountablePerson(
        ?string $search = null,
        ?string $costRange = null,
        int|string|null $status = null,
        int $perPage = 10
    ) {
        return AccountablePerson::query()
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
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    public function createAccountablePerson(array $data): AccountablePerson
    {
        return AccountablePerson::create([
            'full_name' => $data['full_name'],
            'department' => $data['department'] ?? null,
            'position' => $data['position'] ?? null,
            'status' => 1,
        ]);
    }

    public function updateAccountablePerson(int $id, array $data): void
    {
        $supplier = AccountablePerson::findOrFail($id);

        $supplier->update([
            'full_name' => $data['full_name'],
            'department' => $data['department'] ?? null,
            'position' => $data['position'] ?? null,
            // 'status' => 1,
        ]);
    }
}
