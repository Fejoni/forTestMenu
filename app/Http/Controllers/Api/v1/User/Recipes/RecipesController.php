<?php

namespace App\Http\Controllers\Api\v1\User\Recipes;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\GetAvailableProductsGroupedByDivisionRequest;
use App\Http\Requests\v1\User\Recipes\RecipesFilterRequest;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Services\Product\ProductServices;
use Illuminate\Http\JsonResponse;

class RecipesController extends Controller
{
    public function filter(RecipesFilterRequest $request): JsonResponse
    {
        $categories = DishCategory::all();
        $productsData = $request->validated('data');

        $productsQuantities = collect($productsData)
            ->mapWithKeys(fn ($item) => [$item['uuid'] => $item['quantity']]);
        $productIds = $productsQuantities->keys()->all();

        $filteredDishes = [];
        foreach ($categories as $category) {
            $dishes = Dish::query()
                ->where('category_id', $category->uuid)
                ->where(function ($query) {
                    $query->where('users_id', auth()->id())
                        ->orWhereNull('users_id');
                })
                ->whereHas('products', fn ($query) => $query->whereIn('products.uuid', $productIds))
                ->with('products')
                ->get();

            $filteredDishes[$category->name] = DishResource::collection(
                $dishes->filter(function ($dish) use ($productsQuantities) {
                    $available = $dish->products->filter(function ($product) use ($productsQuantities) {
                        return isset($productsQuantities[$product->uuid]) &&
                            $productsQuantities[$product->uuid] >= $product->pivot->quantity;
                    });

                    return $available->isNotEmpty();
                })->sortByDesc(function ($dish) use ($productsQuantities) {
                    $total = $dish->products->count();
                    if ($total === 0) {
                        return 0;
                    }

                    $matched = $dish->products->filter(function ($product) use ($productsQuantities) {
                        return isset($productsQuantities[$product->uuid]) &&
                            $productsQuantities[$product->uuid] >= $product->pivot->quantity;
                    })->count();

                    return $matched / $total;
                })->values()
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
