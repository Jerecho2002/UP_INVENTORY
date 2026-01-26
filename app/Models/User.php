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

    public function accountableReceipts()
    {
        return $this->hasMany(AcknowledgementReceipt::class, 'accountable_persons_id');
    }

    public function issuedReceipts()
    {
        return $this->hasMany(AcknowledgementReceipt::class, 'issued_by_id');
    }

    public function createdReceipts()
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
}
