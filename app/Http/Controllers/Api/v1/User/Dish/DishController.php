<?php

namespace App\Http\Controllers\Api\v1\User\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\Dish\UserDishCreateRequest;
use App\Http\Requests\v1\User\Dish\UserDishUpdateRequest;
use App\Http\Resources\Dish\DishTimeResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\Dish\DishTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishController extends Controller
{
    public function time(): AnonymousResourceCollection
    {
        return DishTimeResource::collection(DishTime::query()->get());
    }

    public function timeDefaultSelect(): AnonymousResourceCollection
    {
        return DishTimeResource::collection(
            DishTime::query()
                ->where('name', 'Завтрак')
                ->orWhere('name', 'Обед')
                ->orWhere('name', 'Ужин')
                ->get()
        );
    }

    public function create(UserDishCreateRequest $request): JsonResponse
    {
        $userId = auth()->id();

        $isDishExists = Dish::query()
            ->where('name', $request->get('name'))
            ->where(function ($query) use ($userId) {
                $query->where('users_id', $userId)
                    ->orWhereNull('users_id');
            })
            ->exists();

        if ($isDishExists) {
            return response()->json([
                'error' => 'Блюдо с таким именем уже существует.'
            ], 403);
        }

        if (!DishTime::query()->where('uuid', $request->get('dish_time_id'))->exists()) {
            return response()->json([
                'error' => 'Время не найдено'
            ], 403);
        }

        if (!DishCategory::query()->where('uuid', $request->get('dish_category_id'))->exists()) {
            return response()->json([
                'error' => 'Категория не найдена'
            ], 403);
        }

        $dish = Dish::query()->create([
            'users_id' => $userId,
            'name' => $request->get('name'),
            'recipe' => $request->get('receipt'),
            'photo' => $request->get('image'),
            'cookingTime' => $request->get('cooking_time'),
            'category_id' => $request->get('dish_category_id'),
            'is_premium' => false
        ]);

        $dish->times()->attach($request->get('dish_time_id'));

        return response()->json([
            'message' => 'Блюдо успешно создано'
        ]);
    }

    public function update(UserDishUpdateRequest $request)
    {
        $dish = Dish::query()
            ->where('uuid', $request->get('id'))
            ->where('users_id', auth()->id())
            ->first();

        if (!$dish) {
            return response()->json([
                'error' => 'Блюдо не найдено.'
            ], 404);
        }

        if (!DishTime::query()->where('uuid', $request->get('dish_time_id'))->exists()) {
            return response()->json([
                'error' => 'Время не найдено'
            ], 403);
        }

        if (!DishCategory::query()->where('uuid', $request->get('dish_category_id'))->exists()) {
            return response()->json([
                'error' => 'Категория не найдена'
            ], 403);
        }

        $dish->update([
            'name' => $request->get('name'),
            'recipe' => $request->get('receipt'),
            'photo' => $request->get('image'),
            'cookingTime' => $request->get('cooking_time'),
            'category_id' => $request->get('dish_category_id')
        ]);

        $dish->times()->sync($request->get('dish_time_id'));

        return response()->json([
           'message' => 'Блюдо успешно изменено'
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'exists:dishes,uuid']
        ]);

        $dish = Dish::query()
            ->where('uuid', $request->get('id'))
            ->where('users_id', auth()->id())
            ->first();

        if (!$dish) {
            return response()->json([
                'error' => 'Блюдо не найдено.'
            ], 404);
        }

        $dish->delete();

        return response()->json([
           'message' => 'Блюдо успешно удалено'
        ]);
    }

}
