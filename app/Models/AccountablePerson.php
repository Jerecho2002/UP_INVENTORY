<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AccountablePerson extends Model
{
    use HasFactory;
    protected $table = 'accountable_persons';
    protected $fillable = ['full_name', 'department', 'position', 'status'];

    public $timestamps = false;

    public function acknowledgementReceipts()
    {
        return $this->hasMany(AcknowledgementReceipt::class);
    }
}
