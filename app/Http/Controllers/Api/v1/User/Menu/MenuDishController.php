<?php

namespace App\Http\Controllers\Api\v1\User\Menu;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\Menu\AppendMenuDishRequest;
use App\Http\Requests\v1\User\Menu\IndexMenuDishRequest;
use App\Http\Requests\v1\User\Menu\ReplacementMenuDishRequest;
use App\Http\Requests\v1\User\Menu\ShowMenuDishRequest;
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
    public function index(IndexMenuDishRequest $request)
    {
        $categories = DishCategory::all();
        $perPage = $request->integer('per_page', 5);
        $page = $request->integer('page', 1);

        $dish = [];
        $hasMore = false;

        foreach ($categories as $category) {
            $query = Dish::query()->where(function ($query) {
                $query->where('users_id', auth()->id())
                    ->orWhereNull('users_id');
            })
                ->where('category_id', $category->uuid)
                ->when($request->filled('name'), function ($query) use ($request) {
                    $name = trim($request->input('name'));
                    $query->where('name', 'LIKE', "%{$name}%");
                })
                ->when($request->filled('dish_time_ids'), function ($query) use ($request) {
                    $query->whereHas('times', function ($query) use ($request) {
                        $query->whereIn('uuid', $request->input('dish_time_ids'));
                    });
                })
                ->when($request->filled('category_ids'), function ($query) use ($request) {
                    $query->whereIn('category_id', $request->input('category_ids'));
                })
                ->when($request->filled('type_ids'), function ($query) use ($request) {
                    $query->whereIn('type_id', $request->input('type_ids'));
                })
                ->when($request->filled('dish_suitable_ids'), function ($query) use ($request) {
                    $query->whereHas('suitables', function ($query) use ($request) {
                        $query->whereIn('uuid', $request->input('dish_suitable_ids'));
                    });
                });

            $totalForCategory = (clone $query)->count();
            $items = $query->skip(($page - 1) * $perPage)
                ->take($perPage)
                ->get();

            $hasMore = $hasMore || ($totalForCategory > $page * $perPage);

            $dish[] = [
                'category' => $category->name,
                'dishes' => DishResource::withoutProducts()->collection($items),
            ];
        }

        return response()->json([
            'data' => $dish,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'next_page' => $hasMore ? $page + 1 : null,
                'has_more' => $hasMore,
            ]
        ]);
    }


    public function show(ShowMenuDishRequest $request): DishResource
    {
        $dish = Dish::query()
            ->where('uuid', $request->uuid)
            ->where(function ($query) {
                $query->where('users_id', auth()->id())
                    ->orWhereNull('users_id');
            })->first();
        return DishResource::make($dish);
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

    public function replacement(ReplacementMenuDishRequest $request): JsonResponse
    {
        $foodMenuDishProduct = FoodMenuDishProduct::query()->where([
            ['uuid', $request->get('old')]
        ])->first();

        if ($foodMenuDishProduct) {
            $dish = Dish::query()
                ->where('uuid', $request->get('new'))
                ->where(function ($query) {
                    $query->where('users_id', auth()->id())
                        ->orWhereNull('users_id');
                })
                ->with('products')
                ->first();

            $oldDish = Dish::query()
                ->where('uuid', $foodMenuDishProduct->dish_id)
                ->where(function ($query) {
                    $query->where('users_id', auth()->id())
                        ->orWhereNull('users_id');
                })
                ->with('products')
                ->first();

            if ($dish) {
                $portions = $request->integer('portions', 1);
                (new MenuServices())->productsBuyDeleteForPortions($oldDish, $foodMenuDishProduct->portions);
                (new MenuServices())->productsBuyForPortions($dish, $portions);

                FoodMenuDishProduct::query()
                    ->where([
                        ['uuid', $request->get('old')]
                    ])->update([
                        'dish_id' => $dish->uuid,
                        'portions' => $portions
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

    public function append(AppendMenuDishRequest $request): JsonResponse
    {
        $dishTime = DishTime::query()->where('name', $request->get('time'))->first();

        if (!$dishTime) {
            return response()->json([
                'message' => 'Время не найдено'
            ], 403);
        }

        $dish = Dish::query()
            ->where('uuid', $request->get('new'))
            ->where(function ($query) {
                $query->where('users_id', auth()->id())
                    ->orWhereNull('users_id');
            })
            ->with('products')
            ->first();

        if (!$dish) {
            return response()->json([
                'message' => 'Блюдо не найдено'
            ], 403);
        }

        $portions = $request->integer('portions', 1);

        $foodMenu = FoodMenu::query()->create([
            'users_id' => auth()->user()->id,
            'dish_time_id' => $dishTime->uuid,
            'day' => $request->get('day'),
        ]);

        FoodMenuDishProduct::query()->create([
            'food_menus_id' => $foodMenu->uuid,
            'dish_id' => $dish->uuid,
            'portions' => $portions
        ]);

        (new MenuServices())->productsBuyForPortions($dish, $portions);

        return response()->json([
            'message' => 'Вы успешно добавили дополнительное блюдо'
        ]);
    }
}
