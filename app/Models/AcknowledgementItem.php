<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcknowledgementItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'acknowledgement_id',
        'quantity',
        'unit_cost',
        'status'
    ];

    public $timestamps = false;

    public function receipt()
    {
        return $this->belongsTo(AcknowledgementReceipt::class, 'acknowledgement_id');
    }
}
