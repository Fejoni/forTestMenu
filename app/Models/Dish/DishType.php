<?php

namespace App\Models\Dish;

use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;

class DishType extends UuidModel
{
    protected $fillable = ['name'];
}
