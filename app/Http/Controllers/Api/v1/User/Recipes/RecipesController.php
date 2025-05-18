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
    public function filter(Request $request)
    {
        $categories = DishCategory::query()->get();
        $dish = [];

        foreach ($categories as $category) {
            $dish[$category->name] = DishResource::collection(
                Dish::query()
                    ->where('category_id', $category->uuid)
                    ->get()
            );
        }

        $mainArrayKeys = array_keys($request->data);
        $productsID = [];

        foreach ($mainArrayKeys as $key) {
            foreach ($request->data[$key] as $datum) {
                $productsID[] = $datum['uuid'];
            }
        }

        $filteredDishes = [];

        foreach ($dish as $categoryName => $dishes) {
            $filteredDishes[$categoryName] = DishResource::collection(
                Dish::query()
                    ->where('category_id', $categories->where('name', $categoryName)->first()->uuid)
                    ->whereHas('products', function ($query) use ($productsID) {
                        $query->whereIn('products.uuid', $productsID);
                    })
                    ->get()
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
