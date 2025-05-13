<?php

namespace App\Http\Controllers\Api\v1\Admin\Product\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Product\ShopResource;
use App\Models\Product\ProductShop;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ShopResource::collection(ProductShop::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        ProductShop::query()->where('uuid', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        ProductShop::query()->where('uuid', $request->get('id'))->update([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        ProductShop::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }
}
