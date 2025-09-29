<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Location extends Model
{
    use HasFactory;
    protected $fillable = ['location_name', 'office_id', 'status'];

    public $timestamps = false;

    public function office()
    {
        return $this->belongsTo(Office::class);
    }

    public function properties()
    {
        return $this->hasMany(Property::class);
    }
}
