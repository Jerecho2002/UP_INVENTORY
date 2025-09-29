<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserProfile extends Model
{
    use HasFactory;
    protected $fillable = ['user_id', 'full_name', 'contact_number', 'status'];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
