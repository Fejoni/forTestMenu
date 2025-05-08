<?php

namespace App\Services\Product;

use App\Models\Product\Product;
use Illuminate\Validation\ValidationException;

class ProductUpdateServices extends ProductServices
{
    /**
     * @throws ValidationException
     */
    public function edit(array $data)
    {
        return $this->update($data);
    }

    /**
     * @throws ValidationException
     */
    protected function update(array $data)
    {
        $product = Product::with('shops')->where('uuid', $data['id'])->firstOrFail();

        if ($product->name != $data['name'] and Product::query()->where('name', $data['name'])->exists()) {
            throw ValidationException::withMessages([
                'message' => 'Название уже занято'
            ]);
        }

        $product->update([
            'name' => $data['name'],
            'image' => $data['image'] ?? null,
            'unit_id' => $data['unit']['id'] ?? null,
            'categories_id' => $data['category']['id'] ?? null,
            'divisions_id' => $data['division']['id'] ?? null,
            'count' => $data['count'] ?? null,
        ]);

        $product->shops()->sync($this->shops($data['shops']));

        return $product;
    }
}
