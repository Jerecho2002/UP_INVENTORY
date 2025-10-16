<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'property_id',
        'item_classification_id',
        'item_name',
        'description',
        'category',
        'quantity',
        'unit',
        'unit_cost',
        'total_amount',
        'status'
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function acknowledgementReceipts()
    {
        return $this->hasManyThrough(
            AcknowledgementReceipt::class,
            Property::class,
            'id',             // FK on properties
            'property_id',    // FK on acknowledgement_receipts
            'property_id',    // FK on items
            'id'              // local key on properties
        );
    }

    public function itemClassification()
    {
        return $this->belongsTo(ItemClassification::class, 'item_classification_id');
    }


    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('item_name', 'like', "%{$term}%")
                ->orWhere('unit', 'like', "%{$term}%")
                ->orWhereHas('property', function ($q) use ($term) {
                    $q->where('property_number', 'like', "%{$term}%")
                        ->orWhereHas('location', function ($q) use ($term) {
                            $q->where('location_name', 'like', "%{$term}%");
                        });
                });
        });
    }

}
