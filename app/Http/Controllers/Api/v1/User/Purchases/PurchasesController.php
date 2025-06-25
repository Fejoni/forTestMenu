<?php

namespace App\Http\Controllers\Api\v1\User\Purchases;

use App\Http\Controllers\Controller;
use App\Http\Requests\v1\User\GetAvailableProductsGroupedByDivisionRequest;
use App\Http\Requests\v1\User\Purchases\ClearPurchasesRequest;
use App\Models\User\UserProducts;
use App\Services\Product\ProductServices;
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

    public function products(
        GetAvailableProductsGroupedByDivisionRequest $request,
        ProductServices $productServices
    ): JsonResponse
    {
        $result = $productServices->getAvailableProductsGroupedByDivision($request);
        return  response()->json($result);
    }

    public function acceptPurchase(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|exists:products,uuid',
        ]);

        UserProducts::query()
            ->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])
            ->update([
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

        UserProducts::query()
            ->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])
            ->delete();

        return response()->json([
            'message' => 'Продукт успешно обновлен'
        ]);
    }

    public function updatePurchase(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => ['required', 'exists:products,uuid'],
            'count' => ['required'],
        ]);

        UserProducts::query()
            ->where([['users_id', auth()->id()], ['product_id', $request->get('product_id')]])
            ->update([
                'count' => $request->get('count') ?? 1
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

    public function clear(ClearPurchasesRequest $request): JsonResponse
    {
        UserProducts::query()->where('users_id', auth()->id())->delete();

        return response()->json([
            'message' => 'Корзина очищена'
        ]);
    }
}
