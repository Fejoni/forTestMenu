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
        'type_id',
        'portions',
        'cookingTime',
        'weight',
        'timeText',
        'users_id'
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DishCategory::class, 'category_id', 'uuid');
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
            ->withPivot('quantity')->with(['unit', 'division']);
    }

    public function times(): BelongsToMany
    {
        return $this->belongsToMany(DishTime::class, 'dish_dish_time', 'dish_id', 'time_id');
    }

    public function suitables(): BelongsToMany
    {
        return $this->belongsToMany(DishSuitable::class, 'dish_dish_suitable', 'dish_id', 'suitable_id');
    }

}
