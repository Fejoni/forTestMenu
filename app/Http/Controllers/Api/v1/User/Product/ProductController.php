<?php

namespace App\Http\Controllers\Api\v1\User\Product;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\Product\UserProductCreateRequest;
use App\Http\Requests\v1\User\Product\UserProductUpdateRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductDivision;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function create(UserProductCreateRequest $request): JsonResponse
    {
        $userId = auth()->id();

        $isProductExists = Product::query()
            ->where('name', $request->get('name'))
            ->where(function ($query) use ($userId) {
                $query->where('users_id', $userId)
                    ->orWhereNull('users_id');
            })
            ->exists();

        if ($isProductExists) {
            return response()->json([
                'error' => 'Товар с таким именем уже существует.'
            ], 403);
        }

        if (!ProductDivision::query()->where('uuid', $request->get('division_id'))->exists()) {
            return response()->json([
                'error' => 'Неверный отдел.'
            ], 403);
        }

        if (!Product::query()->where('categories_id', $request->get('category_id'))->exists()) {
            return response()->json([
                'error' => 'Неверная категория.'
            ], 403);
        }

        Product::query()->create([
            'name' => $request->get('name'),
            'users_id' => $userId,
            'categories_id' => $request->get('category_id'),
            'division_id' => $request->get('division_id'),
            'count' => $request->get('quantity'),
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Продукт успешно создан.'
        ]);
    }

    public function update(UserProductUpdateRequest $request): JsonResponse
    {
        $product = Product::query()
            ->where('uuid', $request->get('id'))
            ->where('users_id', auth()->id())
            ->first();

        if (!$product) {
            return response()->json([
                'error' => 'Продукт не найден.'
            ], 404);
        }

        if (!ProductDivision::query()->where('uuid', $request->get('division_id'))->exists()) {
            return response()->json([
                'error' => 'Неверный отдел.'
            ], 403);
        }

        if (!Product::query()->where('categories_id', $request->get('category_id'))->exists()) {
            return response()->json([
                'error' => 'Неверная категория.'
            ], 403);
        }

        $product->update([
            'name' => $request->get('name'),
            'categories_id' => $request->get('category_id'),
            'division_id' => $request->get('division_id'),
            'count' => $request->get('quantity')
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Продукт успешно изменен.'
        ]);
    }

    public function delete(Request $request): JsonResponse
    {
        $request->validate([
            'id' => ['required', 'exists:products,uuid']
        ]);

        $product = Product::query()
            ->where('uuid', $request->get('id'))
            ->where('users_id', auth()->id())
            ->first();

        if (!$product) {
            return response()->json([
                'error' => 'Продукт не найден.'
            ], 404);
        }

        $product->delete();

        return response()->json([
           'success' => true,
           'message' => 'Продукт успешно удален.'
        ]);
    }

}
