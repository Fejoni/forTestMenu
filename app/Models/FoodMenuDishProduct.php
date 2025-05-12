<?php

namespace App\Models;

class FoodMenuDishProduct extends UuidModel
{
    protected $table = 'food_menu_dish_product';

    protected $fillable = [
        'food_menus_id',
        'dish_id'
    ];
}
