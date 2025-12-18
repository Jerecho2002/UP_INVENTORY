<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ItemClassification extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'classification_code',
        'classification_name',
        'status',
    ];

    public $timestamps = true;

    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function scopeSearch($query, $term)
    {
        if (!$term) {
            return $query;
        }

        return $query->where(function ($q) use ($term) {
            $q->where('classification_code', 'like', "%{$term}%")
                ->orWhere('classification_name', 'like', "%{$term}%");
        });
    }
}
