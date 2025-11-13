<?php

namespace App\Services;

use App\Models\AcknowledgementReceipt;
use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\InventoryItem;

class AcknowledgementRecieptService
{
    public function getPaginatedInventory($search = null, $costRange = null, $status = null)
    {
        // Start a query and eager-load 'property' and 'acknowledgementReceipts' relationships
        // This returns an Eloquent query builder instance
        return AcknowledgementReceipt::with( 'accountablePerson.user')

            // Apply search filter only if $search has a value
            ->when($search, fn($query, $search) => $query->search($search))

            // Apply cost range filter only if $costRange has a value
            ->when($costRange, function ($query, $costRange) {
                [$min, $max] = explode('-', $costRange);

                if (!is_null($min) && !is_null($max)) {
                    // Both bounds exist
                    $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
                } elseif (!is_null($min) && !is_null($max)) {
                    // Only minimum given → "₱50,000 and above"
                    $query->where('unit_cost', '>=', (float) $min);
                } elseif (!is_null($min) && !is_null($max)) {
                    // Only maximum given → "Up to ₱50,000"
                    $query->where('unit_cost', '<=', (float) $max);
                }
            })
            // Apply status filter
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })

            // Limit the result to 8 items per page
            ->paginate(8)

            // Keep the query string (like ?search=value) in pagination links
            ->withQueryString();
    }

}