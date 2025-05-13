<?php

namespace App\Http\Controllers\Api\v1\Admin\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\Category\DishCategoryDestroyRequest;
use App\Http\Requests\v1\Dish\Category\DishCategoryRequest;
use App\Http\Resources\Dish\DishCategoryResource;
use App\Models\Dish\DishCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishCategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishCategoryResource::collection(DishCategory::query()->get());
    }

    public function store(DishCategoryRequest $request): JsonResponse
    {
        DishCategory::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(DishCategoryRequest $request): JsonResponse
    {
        DishCategory::query()
            ->where('uuid', $request->get('id'))
            ->update([
                'name' => $request->get('name'),
            ]);

        return response()->json([
            'status' => true,
        ]);
    }
    public function destroy(DishCategoryDestroyRequest $request): JsonResponse
    {
        DishCategory::query()
            ->where('uuid', $request->get('id'))
            ->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
