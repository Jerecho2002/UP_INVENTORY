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
}
