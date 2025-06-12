<?php

namespace App\Models\User;

use App\Models\Dish\DishTime;
use Illuminate\Database\Eloquent\Model;

class UserDishTime extends Model
{
    public function dishTime()
    {
        return $this->belongsTo(DishTime::class, 'dish_time_uuid');
    }
}
