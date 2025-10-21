<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class FundSource extends Model
{
    use HasFactory;
    protected $fillable = ['code', 'description', 'status'];

    public $timestamps = false;

    // Relationships
    public function inventoryItems()
    {
        return $this->hasMany(InventoryItem::class);
    }

    public function acknowledgementReceipts()
    {
        return $this->hasMany(AcknowledgementReceipt::class);
    }
}
