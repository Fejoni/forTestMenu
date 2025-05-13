<?php

namespace App\Http\Controllers\Api\v1\Admin\Dish;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\DishDestroyRequest;
use App\Http\Requests\v1\Dish\DishRequest;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DishController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishResource::collection(Dish::query()->with(['products', 'times', 'suitables'])->get());
    }

    public function store(DishRequest $request): JsonResponse
    {
        try {
            $dish = Dish::query()->create([
                'name' => $request->get('name'),
                'calories' => $request->get('calories'),
                'photo' => $request->get('photo'),
                'recipe' => $request->get('recipe'),
                'is_premium' => $request->get('is_premium'),
                'protein' => $request->get('protein'),
                'carbohydrates' => $request->get('carbohydrates'),
                'fats' => $request->get('fats'),
                'category_id' => $request->get('category_id'),
                'type_id' => $request->get('type_id'),
                'portions' => $request->get('portions'),
                'cookingTime' => $request->get('cookingTime'),
                'weight' => $request->get('weight'),
            ]);

            foreach ($request->get('products') as $product) {
                $dish->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
            }

            if (count($request->get('time_ids'))) {
                foreach ($request->get('time_ids') as $time) {
                    $dish->times()->attach($time);
                }
            }

            if (count($request->get('suitable_ids'))) {
                foreach ($request->get('suitable_ids') as $suitable) {
                    $dish->suitables()->attach($suitable);
                }
            }

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(DishRequest $request): JsonResponse
    {
        try {
            $dish = Dish::query()->where('uuid', $request->get('id'))->firstOrFail();

            $dish->update([
                'name' => $request->get('name'),
                'calories' => $request->get('calories'),
                'photo' => $request->get('photo'),
                'recipe' => $request->get('recipe'),
                'is_premium' => $request->get('is_premium'),
                'protein' => $request->get('protein'),
                'carbohydrates' => $request->get('carbohydrates'),
                'fats' => $request->get('fats'),
                'category_id' => $request->get('category_id'),
                'type_id' => $request->get('type_id'),
                'portions' => $request->get('portions'),
                'cookingTime' => $request->get('cookingTime'),
                'weight' => $request->get('weight'),
            ]);

            $dish->products()->detach();
            $dish->times()->detach();
            $dish->suitables()->detach();

            foreach ($request->get('products') as $product) {
                $dish->products()->attach($product['product_id'], ['quantity' => $product['quantity']]);
            }

            if (count($request->get('time_ids'))) {
                foreach ($request->get('time_ids') as $time) {
                    $dish->times()->attach($time);
                }
            }

            if (count($request->get('suitable_ids'))) {
                foreach ($request->get('suitable_ids') as $suitable) {
                    $dish->suitables()->attach($suitable);
                }
            }

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(DishDestroyRequest $request): JsonResponse
    {
        try {
            $dish = Dish::query()->where('uuid', $request->get('id'))->firstOrFail();
            $dish->products()->detach();
            $dish->delete();

            return response()->json([
                'status' => true,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
