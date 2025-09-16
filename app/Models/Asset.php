<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asset extends Model
{
    use HasFactory;
    protected $table = 'assets';
    public $timestamps = false;

    protected $fillable = [
        'property_no',
        'description',
        'unit',
        'unit_cost',
        'date_acquired',
        'fund_source_id',
        'pr_id',
        'po_id',
        'invoice_id',
        'supplier_id',
        'location_id',
        'sticker_code',
        'warranty_months',
        'remark',
        'status',
        'created_by'
    ];

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
    }

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'pr_id');
    }

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'po_id');
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

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(Item::class);
    }
}

