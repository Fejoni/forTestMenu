<?php

namespace App\Http\Controllers\Api\v1\User\Menu;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\FoodMenuDishProduct;
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
    public function replacement(Request $request): JsonResponse
    {
        if (FoodMenuDishProduct::query()->where([
            ['uuid', $request->get('old')]
        ])->exists()) {
            $dish = Dish::query()->where('uuid', $request->get('new'))->first();

            if ($dish) {
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
}
