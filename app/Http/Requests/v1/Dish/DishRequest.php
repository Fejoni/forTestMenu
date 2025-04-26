<?php

namespace App\Http\Requests\v1\Dish;

use Illuminate\Foundation\Http\FormRequest;

class DishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'calories' => ['required', 'numeric'],
            'photo' => ['required', 'string'],
            'recipe' => ['required', 'string'],
            'is_premium' => ['required', 'boolean'],
            'protein' => ['required', 'numeric'],
            'carbohydrates' => ['required', 'numeric'],
            'fats' => ['required', 'numeric'],
            'category_id' => ['required', 'uuid'],
            'time_id' => ['required', 'uuid'],
            'suitable_id' => ['required', 'uuid'],
            'type_id' => ['required', 'uuid'],
            'id' => ['nullable', 'uuid'],
            'products' => ['array'],
            'products.*.product_id' => ['uuid'],
            'products.*.quantity' => ['numeric'],
        ];
    }
}
