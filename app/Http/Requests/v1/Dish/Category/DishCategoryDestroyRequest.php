<?php

namespace App\Http\Requests\v1\Dish\Category;

use Illuminate\Foundation\Http\FormRequest;

class DishCategoryDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:dish_categories,uuid'],
        ];
    }
}
