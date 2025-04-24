<?php

namespace App\Services\Product;

use App\Models\Product\Product;

class ProductUpdateServices extends ProductServices
{
    public function edit(array $data)
    {
        return $this->update($data);
    }

    protected function update(array $data)
    {
        $product = Product::with('shops')->where('uuid', $data['id'])->firstOrFail();

        $product->update([
            'name' => $data['name'],
            'image' => $data['image'],
            'unit_id' => $data['unit']['id'],
            'categories_id' => $data['category']['id'],
            'divisions_id' => $data['division']['id'],
        ]);

        $product->shops()->sync($this->shops($data['shops']));

        return $product;
    }
}
