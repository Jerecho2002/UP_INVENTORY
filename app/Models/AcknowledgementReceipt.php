<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcknowledgementReceipt extends Model
{
    use HasFactory;
    protected $fillable = [
        'par_date',
        'accountable_persons_id',
        'issued_by_id',
        'fund_source_id',
        'property_id',
        'remarks',
        'created_by'
    ];

    public function accountablePerson()
    {
        return $this->belongsTo(AccountablePerson::class, 'accountable_persons_id');
    }

    public function issuedBy()
    {
        return $this->belongsTo(AccountablePerson::class, 'issued_by_id');
    }

    public function fundSource()
    {
        return $this->belongsTo(FundSource::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items()
    {
        return $this->hasMany(AcknowledgementItem::class, 'acknowledgement_id');
    }
}
