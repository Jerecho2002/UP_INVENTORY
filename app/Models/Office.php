<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    use HasFactory;
    protected $table = 'offices';
    public $timestamps = false;

    protected $fillable = ['office_name', 'status'];

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_office', 'office_id', 'user_id')
                    ->withPivot('status');
    }
}
