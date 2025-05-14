<?php

namespace App\Http\Controllers\Api\v1\User\Menu;

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
                ['day', $getDates[0]]
            ])
            ->exists()) {
            return response()->json([
                'message' => 'Меню не сгенерировано'
            ]);
        }

        return FoodMenu::query()
            ->where('users_id', auth()->id())
            ->orderByRaw("FIELD(SUBSTRING(day, 1, 2), 'пн', 'вт', 'ср', 'чт', 'пт', 'сб', 'вс')")
            ->whereIn('day', $getDates)
            ->get()
            ->groupBy('day')
            ->map(function ($group) {
                $dishesByDay = [];

                foreach ($group as $dishTime) {
                    $dishQueryTime = DishTime::query()->where('uuid', $dishTime->dish_time_id)->first();

                    if ($dishQueryTime) {
                        $products = DB::table('food_menu_dish_product')
                            ->where('food_menus_id', $dishTime->uuid)
                            ->first();

                        if ($products) {
                            $dishesByDay[$dishQueryTime->name][] = [
                                'data' => new DishResource(
                                    Dish::query()->where('uuid', $products->dish_id)->first()
                                ),
                                'id' => $products->uuid
                            ];
                        }
                    }
                }

                return collect(['Завтрак', 'Ланч', 'Обед', 'Полдник', 'Ужин'])
                    ->filter(fn($time) => isset($dishesByDay[$time]))
                    ->mapWithKeys(fn($time) => [$time => $dishesByDay[$time]]);
            });
    }

    public function generate(): JsonResponse
    {
        $getDates = (new MenuServices())->getDates();

        if (!FoodMenu::query()->where('users_id', auth()->user()->id)->where('day', $getDates[0])->exists()) {
            (new MenuServices())->generate();

            return response()->json([
                'message' => 'Успешно сгенерировано'
            ]);
        }

        return response()->json([
            'message' => 'Меню уже сгенерировано'
        ], 401);
    }

    public function replacementGenerate(): JsonResponse
    {
        if (FoodMenu::query()->where('users_id', auth()->user()->id)->exists()) {
            FoodMenu::query()->where('users_id', auth()->user()->id)->delete();
        }

        (new MenuServices())->generate();

        return response()->json([
            'message' => 'Успешно сгенерировано'
        ]);
    }
}
