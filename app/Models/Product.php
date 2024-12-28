<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use League\CommonMark\Extension\CommonMark\Node\Inline\Code;

class Product extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'stock_quantity',
        'category_id',
        'image_url',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function bags()
    {
        return $this->hasMany(Bag::class, 'product_id');
    }

    public function codes()
    {
        return $this->hasMany(Code::class, 'product_id');
    }
}
