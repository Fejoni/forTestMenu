<?php

namespace App\Http\Controllers\Api\v1\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\Time\DishTimeRequest;
use App\Http\Requests\v1\Dish\Type\DishTypeDestroyRequest;
use App\Http\Requests\v1\Dish\Type\DishTypeRequest;
use App\Http\Resources\Dish\DishResource;
use App\Http\Resources\Dish\DishTypeResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishResource::collection(Dish::query()->get());
    }

    public function store(DishTimeRequest $request): JsonResponse
    {
        Dish::query()->create([
            'name' => $request->get('name'),
            'calories' => $request->get('calories'),
            'photo' => $request->get('photo'),
            'recipe' => $request->get('recipe'),
            'is_premium' => $request->get('is_premium'),
            'protein' => $request->get('protein'),
            'carbohydrates' => $request->get('carbohydrates'),
            'fats' => $request->get('fats'),
            'category_id' => $request->get('category_id'),
            'time_id' => $request->get('time_id'),
            'suitable_id' => $request->get('suitable_id'),
            'type_id' => $request->get('type_id'),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(DishTypeRequest $request): JsonResponse
    {
        DishType::query()
            ->where('uuid', $request->get('id'))
            ->update([
                'name' => $request->get('name'),
                'calories' => $request->get('calories'),
                'photo' => $request->get('photo'),
                'recipe' => $request->get('recipe'),
                'is_premium' => $request->get('is_premium'),
                'protein' => $request->get('protein'),
                'carbohydrates' => $request->get('carbohydrates'),
                'fats' => $request->get('fats'),
                'category_id' => $request->get('category_id'),
                'time_id' => $request->get('time_id'),
                'suitable_id' => $request->get('suitable_id'),
                'type_id' => $request->get('type_id'),
            ]);

        return response()->json([
            'status' => true,
        ]);
    }
    public function destroy(DishTypeDestroyRequest $request): JsonResponse
    {
        DishType::query()
            ->where('uuid', $request->get('id'))
            ->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
