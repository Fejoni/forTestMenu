<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use App\Services\Product\ProductStoreServices;
use App\Services\Product\ProductUpdateServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->with('shops')->get());
    }

    public function delete(Request $request): JsonResponse
    {
        Product::query()->where('uuid', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request, ProductUpdateServices $productUpdateServices)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string'],
            'unit' => ['required'],
            'shops' => ['required', 'array'],
            'category' => ['required'],
            'division' => ['required'],
        ]);

        return $productUpdateServices->edit($request->all());
    }

    public function store(Request $request, ProductStoreServices $services)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'image' => ['required', 'string'],
            'unit' => ['required'],
            'shops' => ['required', 'array'],
            'category' => ['required'],
            'division' => ['required'],
        ]);

        return $services->create($request->all());
    }
}
