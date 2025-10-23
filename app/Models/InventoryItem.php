<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InventoryItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'item_classification_id',
        'fund_source_id',
        'invoice_id',
        'supplier_id',
        'location_id',
        'item_name',
        'description',
        'category',
        'quantity',
        'unit',
        'unit_cost',
        'total_amount',
        'pr_number',
        'po_number',
        'property_number',
        'remarks',
        'date_acquired',
        'status'
    ];

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
    }

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function supplier()
    {
        return $this->belongsTo(Supplier::class);
    }

    public function location()
    {
        return $this->belongsTo(Location::class);
    }

    public function acknowledgementReceipts()
    {
        return $this->hasMany(AcknowledgementReceipt::class);
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
                ->orWhere('property_number', 'like', "%{$term}%")
                ->orWhere('category', 'like', "%{$term}%")
                ->orWhereHas('location', function ($q) use ($term) {
                    $q->where('location_name', 'like', "%{$term}%");
                });
        });
    }

}
