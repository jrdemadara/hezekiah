<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Cashout extends Model
{

    use SoftDeletes;

    protected $fillable = [
        'gross_amount',
        'transaction_fee',
        'net_amount',
        'referral_deduction',
        'order_deduction',
        'status',
        'pooled',
        'user_id',
    ];

    protected $hidden = [
        'updated_at',
        'deleted_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
