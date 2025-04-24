<?php

namespace App\Models\Product;

use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Product extends UuidModel
{
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'image',
        'unit_id',
        'categories_id',
        'divisions_id',
    ];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(ProductShop::class);
    }
}
