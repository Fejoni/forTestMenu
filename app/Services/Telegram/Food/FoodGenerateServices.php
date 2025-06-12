<?php

namespace App\Services\Telegram\Food;

use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\Product\Product;
use App\Models\Telegram\FoodMenu;

class FoodGenerateServices
{
    public function generate()
    {
       $foodMenu = FoodMenu::query()->create([
            'users_id' => auth()->user()->getAuthIdentifier()
       ]);

       $foodMenu->foodMenus()->attach($this->getIDs());

       return $this->getProducts();
    }

    protected function getProducts(): array
    {
        $categories = DishCategory::query()->get();
        $foods = [];

        foreach ($categories as $category) {
            $foods[] = Dish::query()
                ->where('category_id', $category->uuid)
                ->with(['time', 'suitable', 'type', 'products'])
                ->first();
        }

        return $foods;
    }

    protected function getIDs(): array
    {
        $products = $this->getProducts();
        $ids = [];

        foreach ($products as $product) {
            $ids[] = $product->uuid;
        }

        return $ids;
    }
}
