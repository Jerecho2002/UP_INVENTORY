<?php

namespace App\Services;

use App\Models\User;

class UserService
{
    public function filterAndPaginateUsers(
        ?string $search = null,
        int|string|null $status = null,
        int $perPage = 10
    ) {
        return User::with('userProfiles')
            ->when(
                $search,
                fn($query, $search) =>
                $query->search($search)
            )
            ->when(
                !is_null($status),
                fn($query) =>
                $query->where('status', $status)
            )
            ->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }
}
