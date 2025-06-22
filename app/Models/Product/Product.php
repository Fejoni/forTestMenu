<?php

namespace App\Models\Product;

use App\Models\Dish\Dish;
use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends UuidModel
{
    protected $primaryKey = 'uuid';

    protected $fillable = [
        'name',
        'image',
        'is_view',
        'unit_id',
        'categories_id',
        'divisions_id',
        'count',
        'protein',
        'carbohydrates',
        'fats',
        'calories',
        'users_id'
    ];

    public function shops(): BelongsToMany
    {
        return $this->belongsToMany(ProductShop::class);
    }

    public function unit(): HasOne
    {
        return $this->hasOne(ProductUnit::class, 'uuid', 'unit_id');
    }

    public function category(): HasOne
    {
        return $this->hasOne(ProductCategory::class, 'uuid', 'categories_id');
    }

    public function division(): HasOne
    {
        return $this->hasOne(ProductDivision::class, 'uuid', 'divisions_id');
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(Dish::class, 'dish_product', 'product_id', 'dish_id');
    }
}
