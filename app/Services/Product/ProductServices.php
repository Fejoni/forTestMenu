<?php

namespace App\Services\Product;

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
}
