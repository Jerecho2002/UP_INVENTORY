<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class User extends Authenticatable
{
    use HasFactory;
    protected $fillable = ['email', 'password', 'status', 'role_id'];

    protected $hidden = ['password'];

    // Relationships
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'created_by');
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'created_by');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'created_by');
    }

    public function acknowledgementReceipts()
    {
        return $this->hasMany(AcknowledgementReceipt::class, 'created_by');
    }

    public function accountablePersons()
    {
        return $this->hasMany(AccountablePerson::class);
    }

    public function userProfiles()
    {
        return $this->hasOne(UserProfile::class);
    }

    public function userOffices()
    {
        return $this->hasMany(UserOffice::class);
    }
}
