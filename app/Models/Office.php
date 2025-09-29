<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Office extends Model
{
    use HasFactory;
    protected $fillable = ['office_name', 'status'];

    public $timestamps = false;

    public function locations()
    {
        return $this->hasMany(Location::class);
    }

    public function accountablePersons()
    {
        return $this->hasMany(AccountablePerson::class);
    }

    public function userOffices()
    {
        return $this->hasMany(UserOffice::class);
    }
}
