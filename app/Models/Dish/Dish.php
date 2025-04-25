<?php

namespace App\Models\Dish;

use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Dish extends UuidModel
{
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
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(DishCategory::class);
    }
    public function time(): BelongsTo
    {
        return $this->belongsTo(DishTime::class);
    }
    public function suitable(): BelongsTo
    {
        return $this->belongsTo(DishSuitable::class);
    }
    public function type(): BelongsTo
    {
        return $this->belongsTo(DishType::class);
    }

}
