<?php

namespace App\Http\Controllers\Api\v1\Product\Division;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\DivisionResource;
use App\Models\Product\ProductDivision;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DivisionController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DivisionResource::collection(ProductDivision::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        ProductDivision::query()->where('uuid', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        ProductDivision::query()->where('uuid', $request->get('id'))->update([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        ProductDivision::query()->create([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }
}
