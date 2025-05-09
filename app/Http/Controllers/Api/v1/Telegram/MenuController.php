<?php

namespace App\Http\Controllers\Api\v1\Telegram;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use App\Models\Telegram\FoodMenu;
use App\Services\Menu\MenuServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class MenuController extends Controller
{
    public function index()
    {
        $getDates = (new MenuServices())->getDates();

        if (!FoodMenu::query()
            ->where([
                ['users_id', auth()->user()->getAuthIdentifier()],
                ['day', $getDates[6]]])
            ->exists()) {

            return response()->json([
                'message' => 'Меню не сгенерировано'
            ]);
        }

        return FoodMenu::query()
            ->where('users_id', auth()->id())
            ->get()
            ->groupBy('day')
            ->map(function ($group) {
                $dishesByDay = [];

                foreach ($group as $dishTime) {
                    $dishQueryTime = DishTime::query()->where('uuid', $dishTime->dish_time_id)->first();

                    if ($dishQueryTime) {
                        $products = DB::table('food_menu_dish_product')
                            ->where('food_menu_id', $dishTime->id)
                            ->first();

                        if ($products) {
                            $dishesByDay[$dishQueryTime->name][] = new DishResource(
                                Dish::query()->where('uuid', $products->dish_id)->first()
                            );
                        }
                    }
                }

                return collect(['Завтрак', 'Обед', 'Ужин'])
                    ->filter(fn($time) => isset($dishesByDay[$time]))
                    ->mapWithKeys(fn($time) => [$time => $dishesByDay[$time]]);
            });

    }

    public function generate(): JsonResponse
    {
        $getDates = (new MenuServices())->getDates();
        $userId = auth()->id();

        if (!FoodMenu::query()->where('users_id', $userId)->where('day', $getDates[6])->exists()) {
            $foods = [];
            $dishTimes = DishTime::all();

            foreach ($getDates as $date) {
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
                                'users_id' => $userId,
                                'day' => $date,
                            ]);

                            $foodMenu->dishes()->attach($dish->uuid);
                        }
                    }
                }
            }

            return response()->json([
                'message' => 'Успешно сгенерировано'
            ]);
        }

        return response()->json([
            'message' => 'Меню уже сгенерировано'
        ], 401);
    }
}
