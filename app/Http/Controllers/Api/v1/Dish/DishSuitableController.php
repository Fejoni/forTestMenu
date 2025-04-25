<?php

namespace App\Http\Controllers\Api\v1\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\Suitable\DishSuitableDestroyRequest;
use App\Http\Requests\v1\Dish\Suitable\DishSuitableRequest;
use App\Http\Resources\Dish\DishSuitableResource;
use App\Models\Dish\DishSuitable;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishSuitableController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishSuitableResource::collection(DishSuitable::query()->get());
    }

    public function store(DishSuitableRequest $request): JsonResponse
    {
        DishSuitable::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(DishSuitableRequest $request): JsonResponse
    {
        DishSuitable::query()
            ->where('uuid', $request->get('id'))
            ->update([
                'name' => $request->get('name'),
            ]);

        return response()->json([
            'status' => true,
        ]);
    }
    public function destroy(DishSuitableDestroyRequest $request): JsonResponse
    {
        DishSuitable::query()
            ->where('uuid', $request->get('id'))
            ->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
