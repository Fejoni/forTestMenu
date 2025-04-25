<?php

namespace App\Http\Resources\Dish;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property string $uuid
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
 */

class DishResource extends JsonResource
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
            'calories' => $this->calories,
            'photo' => $this->photo,
            'recipe' => $this->recipe,
            'is_premium' => $this->is_premium,
            'protein' => $this->protein,
            'carbohydrates' => $this->carbohydrates,
            'fats' => $this->fats,
            'category_id' => new DishCategoryResource($this->category_id),
            'time_id' => new DishTimeResource($this->time_id),
            'suitable_id' => new DishSuitableResource($this->suitable_id),
            'type_id' => new DishTypeResource($this->type_id),
        ];
    }
}
