<?php

namespace App\Services\Product;

use App\Http\Requests\v1\User\GetAvailableProductsGroupedByDivisionRequest;
use App\Models\Product\Product;
use App\Models\User\UserProducts;

class ProductServices
{
    protected function shops(array $shops): array
    {
        $ids = [];

        foreach ($shops as $shop) {
            $ids[] = $shop['id'];
        }

        return $ids;
    }

    public function getAvailableProductsGroupedByDivision(GetAvailableProductsGroupedByDivisionRequest $request)
    {
        $existUserProductsIds = UserProducts::query()
            ->where('users_id', auth()->id())
            ->select(['uuid', 'users_id', 'product_id'])->get()
            ->pluck('uuid')
            ->toArray();

        $products = Product::query()->where(function ($query) {
            $query->where('users_id', auth()->id())
                ->orWhereNull('users_id');
        })
            ->when($request->filled('name'), function ($query) use ($request) {
                $name = trim($request->input('name'));
                $query->where('name', 'LIKE', "%{$name}%");
            })
            ->when($request->filled('divisions_id'), function ($query) use ($request) {
                $query->where('divisions_id', $request->input('divisions_id'));
            })
            ->whereNot('uuid', $existUserProductsIds)
            ->select('name', 'image', 'uuid', 'divisions_id', 'unit_id')
            ->with(['division', 'unit'])
            ->paginate($request->filled('per_page') ? (int)$request->input('per_page') : 5);

        $filterProducts = [];

        foreach ($products as $product) {
            $filterProducts[$product->division?->name ?? ''][] = $product->toArray();
        }

        return [
            'data' => $filterProducts,
            'meta' => [
                'current_page' => $products->currentPage(),
                'last_page' => $products->lastPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'next_page_url' => $products->nextPageUrl(),
                'prev_page_url' => $products->previousPageUrl(),
            ],
        ];
    }
}
