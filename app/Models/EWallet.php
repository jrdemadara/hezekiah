<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EWallet extends Model
{

    use SoftDeletes;

    protected $table    = 'ewallets';
    protected $fillable = [
        'wallet',
        'account_number',
        'account_name',
        'user_id',
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
