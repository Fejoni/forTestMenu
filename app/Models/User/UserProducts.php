<?php

namespace App\Models\User;

use App\Models\Product\Product;
use App\Models\UuidModel;

class UserProducts extends UuidModel
{
    protected $table = 'user_products';

    protected $fillable = [
        'product_id',
        'users_id',
        'count',
        'status',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id', 'uuid')->with(['unit', 'category', 'division']);
    }
}
