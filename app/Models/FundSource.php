<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FundSource extends Model
{
    use HasFactory;
    protected $table = 'fund_sources';
    public $timestamps = false;

    protected $fillable = ['code', 'description', 'status'];

    // 🔁 Reverse Relationships

    public function assets()
    {
        return $this->hasMany(Asset::class);
    }

    public function propertyAckReceipts()
    {
        return $this->hasMany(PropertyAckReceipt::class);
    }
}

