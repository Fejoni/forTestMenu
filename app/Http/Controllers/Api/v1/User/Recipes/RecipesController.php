<?php

namespace App\Http\Controllers\Api\v1\User\Recipes;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\GetAvailableProductsGroupedByDivisionRequest;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Services\Product\ProductServices;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RecipesController extends Controller
{
    public function filter(Request $request): JsonResponse
    {
        $categories = DishCategory::query()->get();
        $productsData = $request->get('data');

        $productsID = [];
        $productsQuantities = [];

        foreach ($productsData as $data) {
            $productsID[] = $data['uuid'];
            $productsQuantities[$data['uuid']] = $data['quantity'];
        }

        $filteredDishes = [];

        foreach ($categories as $category) {
            $dishes = Dish::query()
                ->where('category_id', $category->uuid)
                ->where(function ($query) {
                    $query->where('users_id', auth()->id())
                        ->orWhereNull('users_id');
                })
                ->with(['products' => function($query) use ($productsID) {
                    $query->whereIn('products.uuid', $productsID);
                }])
                ->get();


            $filteredDishes[$category->name] = DishResource::collection(
                $dishes->filter(function ($dish) use ($productsQuantities) {
                    if ($dish->products->isEmpty()) {
                        return false;
                    }

                    // Check if all required products are available in sufficient quantity
                    foreach ($dish->products as $product) {
                        $requiredQuantity = $product->pivot->quantity;
                        $availableQuantity = $productsQuantities[$product->uuid] ?? 0;

                        if ($availableQuantity < $requiredQuantity) {
                            return false;
                        }
                    }
                    return true;
                })
            );
        }

        return response()->json($filteredDishes);
    }

    public function products(
        GetAvailableProductsGroupedByDivisionRequest $request,
        ProductServices $productServices
    ): JsonResponse
    {
        $result = $productServices->getAvailableProductsGroupedByDivision($request);
        return  response()->json($result);
    }
}
