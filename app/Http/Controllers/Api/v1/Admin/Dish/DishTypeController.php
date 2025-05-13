<?php

namespace App\Http\Controllers\Api\v1\Admin\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\Time\DishTimeRequest;
use App\Http\Requests\v1\Dish\Type\DishTypeDestroyRequest;
use App\Http\Requests\v1\Dish\Type\DishTypeRequest;
use App\Http\Resources\Dish\DishTypeResource;
use App\Models\Dish\DishType;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishTypeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishTypeResource::collection(DishType::query()->get());
    }

    public function store(DishTimeRequest $request): JsonResponse
    {
        DishType::query()->create([
            'name' => $request->get('name'),
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
