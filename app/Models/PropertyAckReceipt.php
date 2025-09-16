<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PropertyAckReceipt extends Model
{
    use HasFactory;
    protected $table = 'property_ack_receipts';
    public $timestamps = false;

    protected $fillable = [
        'par_number',
        'par_date',
        'person_responsible_id',
        'office_id',
        'new_accountable_id',
        'issued_by_id',
        'previous_par_id',
        'fund_source_id',
        'location_id',
        'remark',
        'status',
        'created_by'
    ];

    public function personResponsible()
    {
        return $this->belongsTo(AccountablePerson::class, 'person_responsible_id');
    }

    public function newAccountable()
    {
        return $this->belongsTo(AccountablePerson::class, 'new_accountable_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(AccountablePerson::class, 'issued_by_id');
    }

    public function previousPar()
    {
        return $this->belongsTo(PropertyAckReceipt::class, 'previous_par_id');
    }

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
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
        return $this->hasMany(Item::class, 'par_id');
    }
}

