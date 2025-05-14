<?php

namespace App\Services\Menu;

use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use App\Models\FoodMenuDishProduct;
use App\Models\Telegram\FoodMenu;
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

    public function generate()
    {
        $foods = [];
        $dishTimes = DishTime::all();

        foreach ($this->getDates() as $date) {
            $usedDishUuids = []; // UUID блюд, уже добавленных на эту дату

            foreach ($dishTimes as $dishTime) {
                $count = rand(1, 2);

                for ($i = 0; $i < $count; $i++) {
                    // Пытаемся найти блюдо, исключая уже выбранные
                    $dish = Dish::query()
                        ->whereHas('times', function ($query) use ($dishTime) {
                            $query->where('dish_dish_time.time_id', $dishTime->uuid);
                        })
                        ->whereNotIn('uuid', $usedDishUuids)
                        ->inRandomOrder()
                        ->first();

                    // Если ничего не нашли, пробуем взять любое подходящее по времени
                    if (!$dish) {
                        $dish = Dish::query()
                            ->whereHas('times', function ($query) use ($dishTime) {
                                $query->where('dish_dish_time.time_id', $dishTime->uuid);
                            })
                            ->inRandomOrder()
                            ->first();
                    }

                    if ($dish) {
                        $usedDishUuids[] = $dish->uuid;

                        $foods[$date][$dishTime->name][] = $dish;

                        $foodMenu = FoodMenu::query()->create([
                            'dish_time_id' => $dishTime->uuid,
                            'users_id' => auth()->user()->id,
                            'day' => $date,
                        ]);

                        FoodMenuDishProduct::query()->create([
                            'food_menus_id' => $foodMenu->uuid,
                            'dish_id' => $dish->uuid,
                        ]);
                    }
                }
            }
        }
    }
}
