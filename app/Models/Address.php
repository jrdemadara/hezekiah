<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Address extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'phone',
        'province',
        'city',
        'barangay',
        'street',
        'house_number',
        'zipcode',
        'landmark',
        'coordinates',
        'is_default',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
