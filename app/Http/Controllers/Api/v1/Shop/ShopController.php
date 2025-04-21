<?php

namespace App\Http\Controllers\Api\v1\Shop;

use App\Http\Controllers\Controller;
use App\Http\Resources\Shop\ShopResource;
use App\Models\Shop;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class ShopController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return ShopResource::collection(Shop::query()->get());
    }

    public function delete(Request $request): JsonResponse
    {
        Shop::query()->where('id', $request->get('id'))->delete();

        return response()->json();
    }

    public function update(Request $request): JsonResponse
    {
        Shop::query()->where('id', $request->get('id'))->update([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }

    public function store(Request $request): JsonResponse
    {
        Shop::query()->create([
            'name' => $request->get('name'),
        ]);

        return response()->json();
    }
}
