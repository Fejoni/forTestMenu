<?php

namespace App\Models;

use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DishLeftovers extends UuidModel
{
    protected $fillable = [
        'user_id',
        'dish_id',
        'dish_time_uuid',
        'portions',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function dish(): BelongsTo
    {
        return $this->belongsTo(Dish::class, 'dish_id', 'uuid');
    }

    public function dishTime(): BelongsTo
    {
        return $this->belongsTo(DishTime::class, 'dish_time_uuid', 'uuid');
    }
}
