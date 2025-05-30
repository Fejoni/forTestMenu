<?php

namespace App\Http\Resources\Product;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $name
 * @property string $image
 * @property int $unit_id
 * @property int $categories_id
 * @property int $divisions_id
 * @property mixed $shops
 */
class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->uuid,
            'name' => $this->name,
            'image' => $this->image ?? 'https://i.pinimg.com/736x/85/72/04/8572049c242cfd4eb7fcae2fb7f220f6.jpg',
            'unit' => UnitResource::make($this->whenLoaded('unit')),
            'category' => CategoryResource::make($this->whenLoaded('category')),
            'division' => DivisionResource::make($this->whenLoaded('division')),
            'shops' => ShopResource::collection($this->whenLoaded('shops')),
            'count' => $this->count,
            'protein' => $this->protein,
            'fat' => $this->fats,
            'carbohydrates' => $this->carbohydrates,
            'calories' => $this->calories,
        ];
    }
}
