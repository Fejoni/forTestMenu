<?php

namespace App\Http\Controllers\Api\v1\User\Purchases;

use App\Http\Controllers\Controller;
use App\Models\Product\Product;
use App\Models\User\UserProducts;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PurchasesController extends Controller
{
    public function index(): JsonResponse
    {
        $userProducts = UserProducts::query()->where('users_id', auth()->id())->with(['product'])->get();
        $categories = [];

        foreach ($userProducts as $userProduct) {
            $categories[$userProduct->product->division?->name][] = array_merge($userProduct->product->toArray(), ['count' => $userProduct->count, 'status' => $userProduct->status]);
        }

        return response()->json($categories);
    }

    public function products(): JsonResponse
    {
        $products = Product::query()->select('name', 'image', 'uuid', 'categories_id')->with(['division'])->get();

        $filterProducts = [];

        foreach ($products as $product) {
            if (!UserProducts::query()->where([['users_id', auth()->id()], ['product_id', $product->uuid]])->exists()) {
                $filterProducts[$product->division?->name][] = $product->toArray();
            }
        }

        return response()->json($filterProducts);
    }

    public function acceptPurchase(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,uuid',
        ]);

        UserProducts::query()->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])->update([
            'status' => true
        ]);

        return response()->json([
            'message' => 'Продукт успешно обновлен'
        ]);
    }

    public function removePurchase(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,uuid',
        ]);

        UserProducts::query()->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])->delete();

        return response()->json([
            'message' => 'Продукт успешно обновлен'
        ]);
    }

    public function updatePurchase(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,uuid'],
            'quantity' => ['required', 'numeric', 'min:1'],
        ]);

        UserProducts::query()->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])->update([
            'count' => $request->get('quantity')
        ]);

        return response()->json([
            'message' => 'Количество продукта успешно изменено'
        ]);
    }

    public function storeProduct(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,uuid'],
        ]);

        if (UserProducts::query()->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])->exists()) {
            return response()->json([
                'message' => 'Продукт уже добавлен в корзину'
            ], 403);
        }

        UserProducts::query()->create([
            'users_id' => auth()->id(),
            'product_id' => $request->get('product_id'),
            'count' => 1,
            'status' => false,
        ]);

        return response()->json([
            'message' => 'Продукт успешно добавлен'
        ]);
    }
}
