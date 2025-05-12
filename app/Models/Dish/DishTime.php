<?php

namespace App\Models\Dish;

use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;

class DishTime extends UuidModel
{
    protected $fillable = ['name', 'uuid'];
}
