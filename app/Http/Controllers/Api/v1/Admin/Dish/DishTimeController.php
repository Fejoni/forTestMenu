<?php

namespace App\Http\Controllers\Api\v1\Admin\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\Time\DishTimeDestroyRequest;
use App\Http\Requests\v1\Dish\Time\DishTimeRequest;
use App\Http\Resources\Dish\DishTimeResource;
use App\Models\Dish\DishTime;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishTimeController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishTimeResource::collection(DishTime::query()->get());
    }

    public function store(DishTimeRequest $request): JsonResponse
    {
        DishTime::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json([
            'status' => true,
        ]);
    }

    public function update(DishTimeRequest $request): JsonResponse
    {
        DishTime::query()
            ->where('uuid', $request->get('id'))
            ->update([
                'name' => $request->get('name'),
            ]);

        return response()->json([
            'status' => true,
        ]);
    }
    public function destroy(DishTimeDestroyRequest $request): JsonResponse
    {
        DishTime::query()
            ->where('uuid', $request->get('id'))
            ->delete();

        return response()->json([
            'status' => true,
        ]);
    }
}
