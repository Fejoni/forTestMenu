<?php

namespace App\Services\Product;

use App\Models\Product\Product;

class ProductStoreServices extends ProductServices
{
    public function create(array $data)
    {
        return $this->store($data);
    }

    protected function store(array $data)
    {
        $product = Product::query()->create([
            'name' => $data['name'],
            'image' => $data['image'],
            'unit_id' => $data['unit']['id'],
            'categories_id' => $data['category']['id'],
            'divisions_id' => $data['division']['id'],
        ]);

        $product->shops()->attach($this->shops($data['shops']));

        return $product;
    }
}
