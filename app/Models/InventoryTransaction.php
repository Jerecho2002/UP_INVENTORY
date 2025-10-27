<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryTransaction extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_id',
        'quantity',
        'date_released',
    ];

    public function inventoryItem()
    {
        return $this->belongsTo(InventoryItem::class);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('quantity', 'like', "%{$term}%")
                ->orWhere('date_released', 'like', "%{$term}%")
                ->orWhereHas('inventoryItem', function ($q) use ($term) {
                    $q->where('item_name', 'like', "%{$term}%");
                });
        });
    }
}
