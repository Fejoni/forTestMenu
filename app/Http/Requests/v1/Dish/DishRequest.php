<?php

namespace App\Http\Requests\v1\Dish;

use Illuminate\Foundation\Http\FormRequest;

class DishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'calories' => ['required'],
            'photo' => ['required', 'string'],
            'recipe' => ['required', 'string'],
            'is_premium' => ['required', 'boolean'],
            'protein' => ['required'],
            'carbohydrates' => ['required'],
            'fats' => ['required'],
            'category_id' => ['required'],
            'time_id' => ['required'],
            'suitable_id' => ['required'],
            'type_id' => ['required'],
            'id' => ['string'],
        ];
    }
}
