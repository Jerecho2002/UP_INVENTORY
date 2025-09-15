<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Item extends Model
{
    use HasFactory;
    protected $table = 'items';
    public $timestamps = false;

    protected $fillable = [
        'par_id',
        'asset_id',
        'property_no_raw',
        'item_name',
        'description',
        'quantity',
        'unit',
        'unit_cost',
        'sticker_code',
        'warranty_months',
        'status'
    ];

    protected $casts = [
        'quantity' => 'decimal:3',
        'unit_cost' => 'decimal:2',
        'total_amount' => 'decimal:2'
    ];

    public function asset()
    {
        return $this->belongsTo(Asset::class);
    }

    public function propertyAckReceipt()
    {
        return $this->belongsTo(PropertyAckReceipt::class, 'par_id');
    }

    public function scopeSearch($query, $term)
    {
        return $query
        // ->where('status', 1) uncomment this if wanna see only active status in items table
        ->where(function ($q) use ($term) {
        // Search item_name, unit in the items table
          $q->where('item_name', 'like', "%{$term}%")
            ->orWhere('unit', 'like', "%{$term}%")
        // Search property_no, par_number from another table assets and property_ack_receipts
            ->orWhereHas('asset', fn($q) => $q->where('property_no', 'like', "%{$term}%"))
            ->orWhereHas('propertyAckReceipt', fn($q) => $q->where('par_number', 'like', "%{$term}%"));
        });
    }
}

