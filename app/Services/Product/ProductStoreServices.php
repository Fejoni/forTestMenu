<?php

namespace App\Services\Product;

use App\Models\Product\Product;
use Illuminate\Validation\ValidationException;

class ProductStoreServices extends ProductServices
{
    /**
     * @throws ValidationException
     */
    public function create(array $data)
    {
        return $this->store($data);
    }

    /**
     * @throws ValidationException
     */
    protected function store(array $data)
    {
        if (Product::query()->where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages([
                'message' => 'Название уже занято'
            ]);
        }

        $product = Product::query()->create([
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
            'unit_id' => $data['unit']['id'] ?? null,
            'categories_id' => $data['category']['id'] ?? null,
            'divisions_id' => $data['division']['id'] ?? null,
            'count' => $data['count'] ?? null,
            'protein' => $data['protein'] ?? null,
            'fat' => $data['fat'] ?? null,
            'carbohydrates' => $data['carbohydrates'] ?? null,
            'calories' => $data['calories']?? null,
        ]);

        $product->shops()->attach($this->shops($data['shops']));

        return $product;
    }
}
