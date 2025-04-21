<?php

namespace App\Http\Controllers\Api\v1\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product;
use App\Services\Product\ProductStoreServices;
use App\Services\Product\ProductUpdateServices;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->with('shops')->get());
    }

    public function delete()
    {

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
