<?php

namespace App\Models\Dish;

use App\Models\Product\Product;
use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Dish extends UuidModel
{
    use HasFactory;

    protected $fillable = [
        'name',
        'calories',
        'photo',
        'recipe',
        'is_premium',
        'protein',
        'carbohydrates',
        'fats',
        'category_id',
        'time_id',
        'suitable_id',
        'type_id',
        'portions',
        'cookingTime',
        'weight'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DishCategory::class, 'category_id', 'uuid');
    }
    public function time(): BelongsTo
    {
        return $this->belongsTo(DishTime::class, 'time_id', 'uuid');
    }
    public function suitable(): BelongsTo
    {
        return $this->belongsTo(DishSuitable::class, 'suitable_id', 'uuid');
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(DishType::class, 'type_id', 'uuid');
    }

    public function products(): BelongsToMany
    {
        return $this
            ->belongsToMany(Product::class, 'dish_product', 'dish_id', 'product_id')
            ->withPivot('quantity');
    }
}
