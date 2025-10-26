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

    public function inventoryItems()
    {
        return $this->belongsTo(InventoryItem::class);
    }
}
