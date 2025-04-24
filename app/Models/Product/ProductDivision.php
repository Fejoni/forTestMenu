<?php

namespace App\Models\Product;

use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;

class ProductDivision extends UuidModel
{
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'image'
    ];
}
