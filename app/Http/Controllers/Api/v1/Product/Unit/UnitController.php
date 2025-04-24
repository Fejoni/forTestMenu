<?php

namespace App\Http\Controllers\Api\v1\Product\Unit;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\Unit\UnitResource;
use App\Models\Product\ProductUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class UnitController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return UnitResource::collection(ProductUnit::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        ProductUnit::query()->where('uuid', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        ProductUnit::query()->where('uuid', $request->get('id'))->update([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        ProductUnit::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }
}
