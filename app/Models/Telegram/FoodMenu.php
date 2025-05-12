<?php

namespace App\Models\Telegram;

use App\Models\Dish\Dish;
use App\Models\UuidModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodMenu extends UuidModel
{
    protected $fillable = [
        'users_id', 'dish_time_id', 'day'
    ];

    public function foodMenus()
    {
        return $this->belongsToMany(FoodMenu::class, 'food_menu_dish_product', 'dish_id', 'food_menus_id')
            ->withPivot('product_id')
            ->withTimestamps();
    }

    public function dishes(): BelongsToMany
    {
        return $this->belongsToMany(
            Dish::class,
            'food_menu_dish_product',
            'food_menus_id',
            'dish_id'
        );
    }
}
