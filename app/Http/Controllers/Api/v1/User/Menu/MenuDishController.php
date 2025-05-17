<?php

namespace App\Http\Controllers\Api\v1\User\Menu;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\Dish\DishTime;
use App\Models\FoodMenuDishProduct;
use App\Models\Telegram\FoodMenu;
use App\Services\Menu\MenuServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuDishController extends Controller
{
    public function index(): JsonResponse
    {
        $categories = DishCategory::query()->get();
        $dish = [];

        foreach ($categories as $category) {
            $dish[$category->name] = DishResource::collection(
                Dish::query()
                    ->where('category_id', $category->uuid)
                    ->get()
            );
        }

        return response()->json($dish);
    }
    public function delete(Request $request): JsonResponse
    {
        $foodMenuDishProduct = FoodMenuDishProduct::query()->where([['uuid', $request->get('id')]])->first();
        if ($foodMenuDishProduct) {
            FoodMenuDishProduct::query()->where([
                ['uuid', $request->get('id')]
            ])->delete();

            (new MenuServices())->productsBuyDelete(Dish::query()->where('uuid', $foodMenuDishProduct->dish_id)->with('products')->first());

            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'Не найдено',
        ], 403);
    }
    public function replacement(Request $request): JsonResponse
    {
        $foodMenuDishProduct = FoodMenuDishProduct::query()->where([
            ['uuid', $request->get('old')]
        ])->first();

        if ($foodMenuDishProduct) {
            $dish = Dish::query()->where('uuid', $request->get('new'))->with('products')->first();
            $oldDish = Dish::query()->where('uuid', $foodMenuDishProduct->dish_id)->with('products')->first();

            if ($dish) {
                (new MenuServices())->productsBuyDelete($oldDish);
                (new MenuServices())->productsBuy($dish);

                FoodMenuDishProduct::query()
                    ->where([
                        ['uuid', $request->get('old')]
                    ])->update([
                        'dish_id' => $dish->uuid,
                    ]);

                return response()->json([
                    'message' => 'Блюдо успешно обновлено',
                ]);
            } else {
                return response()->json([
                    'message' => 'Новое блюдо не было найдено в базе',
                ], 403);
            }
        }

        return response()->json([
            'message' => 'Не найдено',
        ], 403);
    }

    public function append(Request $request): JsonResponse
    {
        $request->validate([
            'new' => ['required'],
            'time' => ['required'],
            'day' => ['required'],
        ]);

        $dishTime = DishTime::query()->where('name', $request->get('time'))->first();

        if (!$dishTime) {
            return response()->json([
                'message' => 'Время не найдено'
            ], 403);
        }

        $dish = Dish::query()->where('uuid', $request->get('new'))->with('products')->first();

        if (!$dish) {
            return response()->json([
                'message' => 'Блюдо не найдено'
            ], 403);
        }

        $foodMenu = FoodMenu::query()->create([
            'users_id' => auth()->user()->id,
            'dish_time_id' => $dishTime->uuid,
            'day' => $request->get('day'),
        ]);

        FoodMenuDishProduct::query()->create([
            'food_menus_id' => $foodMenu->uuid,
            'dish_id' => $dish->uuid,
        ]);

        (new MenuServices())->productsBuy($dish);

        return response()->json([
            'message' => 'Вы успешно добавили дополнительное блюдо'
        ]);
    }
}
