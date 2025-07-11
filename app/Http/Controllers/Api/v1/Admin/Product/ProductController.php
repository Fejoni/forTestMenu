<?php

namespace App\Http\Controllers\Api\v1\Admin\Product;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ProductResource;
use App\Models\Product\Product;
use App\Services\Product\ProductStoreServices;
use App\Services\Product\ProductUpdateServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Validation\ValidationException;

class ProductController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->with(['shops', 'division', 'unit', 'category'])->get());
    }

    public function delete(Request $request): JsonResponse
    {
        Product::query()->where('uuid', $request->get('id'))->delete();

        return response()->json();
    }

    /**
     * @throws ValidationException
     */
    public function update(Request $request, ProductUpdateServices $productUpdateServices)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return $productUpdateServices->edit($request->all());
    }

    /**
     * @throws ValidationException
     */
    public function store(Request $request, ProductStoreServices $services)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        return $services->create($request->all());
    }
}
