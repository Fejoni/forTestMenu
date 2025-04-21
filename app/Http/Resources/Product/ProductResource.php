<?php

namespace App\Http\Resources\Product;

use App\Http\Resources\Category\CategoryResource;
use App\Http\Resources\Division\DivisionResource;
use App\Http\Resources\Shop\ShopResource;
use App\Http\Resources\Unit\UnitResource;
use App\Models\Category;
use App\Models\Division;
use App\Models\Unit;
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
            'id' => $this->id,
            'name' => $this->name,
            'image' => $this->image,
            'unit' => new UnitResource(Unit::query()->where('id', $this->unit_id)->first()),
            'category' => new CategoryResource(Category::query()->where('id', $this->categories_id)->first()),
            'division' => new DivisionResource(Division::query()->where('id', $this->divisions_id)->first()),
            'shops' => ShopResource::collection($this->shops),
        ];
    }
}
