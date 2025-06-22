<?php

namespace App\Http\Resources\Dish;

use App\Models\Dish\DishCategory;
use App\Models\Dish\DishSuitable;
use App\Models\Dish\DishTime;
use App\Models\Dish\DishType;
use App\Models\Product\Product;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\Product\ProductResource;

/**
 * @property mixed $uuid
 * @property string $name
 * @property float|int $calories
 * @property string $photo
 * @property string $recipe
 * @property bool $is_premium
 * @property float|int $protein
 * @property float|int $carbohydrates
 * @property float|int $fats
 * @property int $category_id
 * @property int $time_id
 * @property int $suitable_id
 * @property int $type_id
 * @property Collection $products
 */

class DishResource extends JsonResource
{
    protected static bool $withoutProducts = false;

    public static function withoutProducts(): self
    {
        static::$withoutProducts = true;
        return new static(null);
    }

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
            'calories' => $this->calories,
            'photo' => $this->photo ?? 'https://i.pinimg.com/736x/85/72/04/8572049c242cfd4eb7fcae2fb7f220f6.jpg',
            'recipe' => $this->recipe,
            'is_premium' => $this->is_premium,
            'protein' => $this->protein,
            'carbohydrates' => $this->carbohydrates,
            'fats' => $this->fats,
            'category' => new DishCategoryResource(DishCategory::query()->where('uuid', $this->category_id)->first()),

            'time' => $this->times->map(function (DishTime $dishTime) {
                return new DishTimeResource($dishTime);
            }),
            'suitable' => $this->suitables->map(function (DishSuitable $dishSuitable) {
                return new DishSuitableResource($dishSuitable);
            }),

            'type' => new DishTypeResource(DishType::query()->where('uuid', $this->type_id)->first()),
            'products' => self::$withoutProducts ? [] : $this->products->map(function (Product $product) {
                return [
                    'product' => new ProductResource($product),
                    'quantity' => $product->pivot->quantity,
                ];
            }),
            'portions' => $this->portions,
            'cookingTime' => $this->cookingTime,
            'timeText' => $this->timeText,
            'weight' => $this->weight,
            'is_view' => $this->is_view,
        ];
    }
}

