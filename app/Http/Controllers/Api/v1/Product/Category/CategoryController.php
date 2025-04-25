<?php

namespace App\Http\Controllers\Api\v1\Product\Category;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\CategoryResource;
use App\Models\Product\ProductCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class CategoryController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return CategoryResource::collection(ProductCategory::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        ProductCategory::query()->where('uuid', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        ProductCategory::query()->where('uuid', $request->get('id'))->update([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        ProductCategory::query()->create([
            'name' => $request->get('name'),
            'image' => $request->get('image'),
        ]);

        return response()->json();
    }
}
