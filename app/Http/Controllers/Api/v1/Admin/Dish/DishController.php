<?php

namespace App\Http\Controllers\Api\v1\Admin\Dish;

use App\Enums\MediaPathEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\v1\Dish\DishDestroyRequest;
use App\Http\Requests\v1\Dish\DishRequest;
use App\Http\Resources\Dish\DishResource;
use App\Models\Dish\Dish;
use App\Services\Media\MediaService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Support\Facades\Storage;

class DishController extends Controller
{
    public function index(): AnonymousResourceCollection
    {
        return DishResource::collection(Dish::query()->with(['products', 'times', 'suitables'])->get());
    }

    public function store(DishRequest $request, MediaService $mediaService): JsonResponse
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

            if ($request->hasFile('video')) {
                $path = $mediaService->uploadMedia($request->file('video'), MediaPathEnum::DISHES_VIDEO->value);
                $dish->video = $path;
                $dish->save();
            }

            if ($request->hasFile('image')) {
                $path = $mediaService->uploadMedia($request->file('image'), MediaPathEnum::DISHES_IMAGE->value);
                $dish->photo = $path;
                $dish->save();
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

    public function update(DishRequest $request, MediaService $mediaService): JsonResponse
    {
        try {
            $dish = Dish::query()->where('uuid', $request->route('dish'))->firstOrFail();

            $dish->update([
                'name' => $request->get('name'),
                'calories' => $request->get('calories'),
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

            if ($request->hasFile('video')) {
                if ($dish->photo) {
                    $oldPathVideo = str_replace('/storage/', '', parse_url($dish->video, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPathVideo);
                }
                $path = $mediaService->uploadMedia($request->file('video'), MediaPathEnum::DISHES_VIDEO->value);
                $dish->video = $path;
                $dish->save();
            }

            if ($request->hasFile('image')) {
                if ($dish->photo) {
                    $oldPathPhoto = str_replace('/storage/', '', parse_url($dish->photo, PHP_URL_PATH));
                    Storage::disk('public')->delete($oldPathPhoto);
                }
                $path = $mediaService->uploadMedia($request->file('image'), MediaPathEnum::DISHES_IMAGE->value);
                $dish->photo = $path;
                $dish->save();
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
