<?php

namespace App\Services\Product;

use App\Http\Requests\v1\User\GetAvailableProductsGroupedByDivisionRequest;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductDivision;
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
        $perPage = $request->integer('per_page', 5);
        $page = $request->integer('page', 1);
        $divisions = ProductDivision::all();

        $existUserProductsIds = UserProducts::query()
            ->where('users_id', auth()->id())
            ->select(['uuid', 'users_id', 'product_id'])
            ->pluck('uuid')
            ->toArray();

        $groupedProducts = [];
        $hasMore = false;

        foreach ($divisions as $division) {
            $query = Product::query()
                ->where(function ($query) {
                    $query->where('users_id', auth()->id())
                        ->orWhereNull('users_id');
                })
                ->where('divisions_id', $division->uuid)
                ->whereNotIn('uuid', $existUserProductsIds)
                ->when($request->filled('name'), function ($query) use ($request) {
                    $name = trim($request->input('name'));
                    $query->where('name', 'LIKE', "%{$name}%");
                })
                ->when($request->filled('divisions_id'), function ($query) use ($request) {
                    $query->where('divisions_id', $request->input('divisions_id'));
                })
                ->with(['division', 'unit']);

            $totalForCategory = (clone $query)->count();
            $items = $query->skip(($page - 1) * $perPage)->take($perPage)->get();

            $hasMore = $hasMore || ($totalForCategory > $page * $perPage);

            $groupedProducts[] = [
                'division' => $division->name,
                'division_uuid' => $division->uuid,
                'products' => $items,
            ];
        }

        return [
            'data' => $groupedProducts,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'next_page' => $hasMore ? $page + 1 : null,
                'has_more' => $hasMore,
            ],
        ];
    }

}
