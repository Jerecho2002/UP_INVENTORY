<?php

namespace App\Services;

use App\Models\UserProfile;
use Illuminate\Http\Request;
use App\Models\InventoryItem;
use App\Models\InventoryTransaction;

class InventoryService
{
    public function filterAndPaginateInventory($search = null, $costRange = null, $status = null)
    {
        return InventoryItem::with('acknowledgementReceipts', 'itemClassification', 'supplier')
            ->when($search, fn($query, $search) => $query->search($search))
            ->when($costRange, function ($query, $costRange) {
                [$min, $max] = explode('-', $costRange);

                if (!is_null($min) && !is_null($max)) {
                    $query->whereBetween('unit_cost', [(float) $min, (float) $max]);
                } elseif (!is_null($min) && !is_null($max)) {
                    $query->where('unit_cost', '>=', (float) $min);
                } elseif (!is_null($min) && !is_null($max)) {
                    $query->where('unit_cost', '<=', (float) $max);
                }
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate(10)
            ->withQueryString();
    }

    public function filterAndPaginateTransaction($search = null, $costRange = null, $status)
    {
        return InventoryTransaction::with('inventoryItem')
            ->when($search, fn($query, $search) => $query->search($search))
            ->when($costRange, function ($query, $costRange) {
                [$min, $max] = explode('-', $costRange);

                $query->whereHas('inventoryItem', function ($q) use ($min, $max) {
                    if ($min !== '' && $max !== '') {
                        $q->whereBetween('unit_cost', [(float) $min, (float) $max]);
                    } elseif ($min !== '' && $max === '') {
                        $q->where('unit_cost', '>=', (float) $min);
                    } elseif ($min === '' && $max !== '') {
                        $q->where('unit_cost', '<=', (float) $max);
                    }
                });
            })
            ->when(!is_null($status), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->paginate(10)
            ->withQueryString();
    }

     public function createInventoryItems(array $data): void
    {
        foreach ($data['serial_numbers'] as $index => $serialNumber) {
            $propertyNumber = $data['property_number'] . '-' . str_pad($index + 1, 2, '0', STR_PAD_LEFT);

            InventoryItem::create([
                'item_classification_id' => $data['item_classification_id'],
                'supplier_id' => $data['supplier_id'],
                'invoice' => $data['invoice'],
                'fund_source' => $data['fund_source'],
                'item_name' => $data['item_name'],
                'description' => $data['description'],
                'category' => $data['category'],
                'quantity' => 1,
                'unit' => $data['unit'],
                'unit_cost' => $data['unit_cost'],
                'total_amount' => $data['unit_cost'],
                'property_number' => $propertyNumber,
                'serial_number' => $serialNumber,
                'pr_number' => $data['pr_number'],
                'po_number' => $data['po_number'],
                'remarks' => $data['remarks'],
                'date_acquired' => $data['date_acquired'],
                'status' => $data['status'],
            ]);
        }
    }

    public function updateInventoryItem(int $id, array $data): void
    {
        $item = InventoryItem::findOrFail($id);

        $totalAmount = $data['quantity'] * $data['unit_cost'];

        $item->update([
            'item_classification_id' => $data['item_classification_id'],
            'supplier_id' => $data['supplier_id'],
            'invoice' => $data['invoice'],
            'fund_source' => $data['fund_source'],
            'item_name' => $data['item_name'],
            'description' => $data['description'] ?? null,
            'category' => $data['category'] ?? null,
            'quantity' => $data['quantity'],
            'unit' => $data['unit'],
            'unit_cost' => $data['unit_cost'],
            'total_amount' => $totalAmount,
            'property_number' => $data['property_number'],
            'serial_number' => $data['serial_number'],
            'pr_number' => $data['pr_number'],
            'po_number' => $data['po_number'],
            'remarks' => $data['remarks'] ?? null,
            'date_acquired' => $data['date_acquired'],
            'status' => $data['status'] ?? 1,
        ]);
    }

}