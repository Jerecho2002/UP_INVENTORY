<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Supplier extends Model
{
    use HasFactory;
    protected $fillable = ['supplier_name', 'contact_no', 'address', 'status'];

    public $timestamps = false;

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
            $q->where('supplier_name', 'like', "%{$term}%")
                ->orWhere('contact_no', 'like', "%{$term}%")
                ->orWhere('address', 'like', "%{$term}%");
        });
    }
}
