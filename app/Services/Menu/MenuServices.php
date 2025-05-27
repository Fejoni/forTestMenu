<?php

namespace App\Services\Menu;

use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use App\Models\FoodMenuDishProduct;
use App\Models\Telegram\FoodMenu;
use App\Models\User\UserDishTime;
use App\Models\User\UserProducts;
use Carbon\CarbonPeriod;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\App;

class MenuServices
{
    public function getDates(): array
    {
        App::setLocale('ru');
        Carbon::setLocale('ru');

        $today = Carbon::now();
        $period = CarbonPeriod::create($today, Carbon::now()->endOfWeek());

        $dates = [];

        foreach ($period as $date) {
            $dates[] = mb_strtolower($date->isoFormat('dd DD.MM'));
        }

        return $dates;
    }

    public function generate(): void
    {
        $foods = [];
        $dishTimes = UserDishTime::query()->where('user_id', auth()->id())->with(['dishTime'])->orderBy('id', 'ASC')->get();

        foreach ($this->getDates() as $date) {
            $usedDishUuids = [];

            foreach ($dishTimes as $dishTime) {
                $count = rand(1, 2);

                $foodMenu = FoodMenu::query()->create([
                    'dish_time_id' => $dishTime->dishTime?->uuid,
                    'users_id' => auth()->user()->id,
                    'day' => $date,
                ]);

                for ($i = 0; $i < $count; $i++) {
                    $dish = Dish::query()
                        ->whereHas('times', function ($query) use ($dishTime) {
                            $query->where('dish_dish_time.time_id', $dishTime->dishTime?->uuid);
                        })
                        ->whereNotIn('uuid', $usedDishUuids)
                        ->inRandomOrder()
                        ->with('products')
                        ->first();

                    if (!$dish) {
                        $dish = Dish::query()
                            ->whereHas('times', function ($query) use ($dishTime) {
                                $query->where('dish_dish_time.time_id', $dishTime->dishTime?->uuid);
                            })
                            ->inRandomOrder()
                            ->with('products')
                            ->first();
                    }

                    if ($dish) {
                        $this->productsBuy($dish);
                        $usedDishUuids[] = $dish->uuid;

                        $foods[$date][$dishTime->dishTime?->name][] = $dish;

                        FoodMenuDishProduct::query()->create([
                            'food_menus_id' => $foodMenu->uuid,
                            'dish_id' => $dish->uuid,
                        ]);
                    }
                }
            }
        }
    }

    public function productsBuy(Dish $dish): void
    {
        if (count($dish->products) > 0) {
            foreach ($dish->products as $product) {
                $userProduct = UserProducts::query()->where([['users_id', auth()->user()->id], ['product_id', $product->uuid]])->first();

                if (!$userProduct) {
                    UserProducts::query()->create([
                        'product_id' => $product->uuid,
                        'users_id' => auth()->user()->id,
                        'count' => $product->pivot->quantity
                    ]);
                } else {
                    $userProduct->count += $product->pivot->quantity;
                    $userProduct->status = false;
                    $userProduct->save();
                }
            }
        }
    }

    public function productsBuyDelete(Dish $dish): void
    {
        foreach ($dish->products as $product) {
            $userProduct = UserProducts::query()->where([['users_id', auth()->user()->id], ['product_id', $product->uuid]])->first();

            if ($userProduct) {
                $userProduct->count -= $product->pivot->quantity;
                $userProduct->save();
            }
        }
    }
}
