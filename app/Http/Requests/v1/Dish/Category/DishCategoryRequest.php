<?php

namespace App\Http\Requests\v1\Dish\Category;

use Illuminate\Foundation\Http\FormRequest;

class DishCategoryRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'id' => ['string']
        ];
    }
}
