<?php

namespace App\Http\Controllers\Api\v1\User\Recipes;

use App\Http\Controllers\Controller;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\Product\Product;
use App\Models\User\UserProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

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

    public function products(): JsonResponse
    {
        $products = Product::query()->select('name', 'image', 'uuid', 'categories_id')->with(['category'])->get();

        $filterProducts = [];

        foreach ($products as $product) {
            $filterProducts[$product->category->name][] = $product->toArray();
        }

        return response()->json($filterProducts);
    }
}
