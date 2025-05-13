<?php

namespace App\Http\Controllers\Api\v1\User\Menu;

use App\Http\Controllers\Controller;
use App\Models\Dish\Dish;
use App\Models\FoodMenuDishProduct;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class MenuFoodController extends Controller
{
    public function delete(Request $request): JsonResponse
    {
        if (FoodMenuDishProduct::query()->where([['uuid', $request->get('id')]])->exists()) {
            FoodMenuDishProduct::query()->where([
                ['uuid', $request->get('id')]
            ])->delete();

            return response()->json([
                'status' => 'success',
            ]);
        }

        return response()->json([
            'message' => 'Не найдено',
        ], 403);
    }

    public function repeat(Request $request): JsonResponse
    {
        if (FoodMenuDishProduct::query()->where([
            ['uuid', $request->get('id')]
        ])->exists()) {
            $food = FoodMenuDishProduct::query()->where([
                ['uuid', $request->get('id')]
            ])->first();

            $dish = Dish::query()->where('uuid', $food->dish_id)->with('times')->first();

            $dish = Dish::query()
                ->whereHas('times', function ($query) use ($dish) {
                    $query->where('dish_dish_time.time_id', $dish->times[0]['uuid']);
                })
                ->where('uuid', '!=', $food->dish_id)
                ->inRandomOrder()
                ->first();

            if ($dish) {
                $food->update([
                    'dish_id' => $dish->uuid,
                ]);

                return response()->json([
                    'message' => 'Успешно было обновлено меню',
                ]);
            } else {
                $food->delete();

                return response()->json([
                    'message' => 'Подходящих блюд не было найдено и оно было просто удалено',
                ]);
            }
        }

        return response()->json([
            'message' => 'Не найдено',
        ], 403);
    }
}
