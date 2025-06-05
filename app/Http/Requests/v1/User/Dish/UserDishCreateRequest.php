<?php

namespace App\Http\Requests\v1\User\Dish;

use Illuminate\Foundation\Http\FormRequest;

class UserDishCreateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'recipe' => ['required'],
            'image' => ['nullable'],
            'dish_time_ids' => ['required'],
            'cookingTime' => ['required'],
            'category_id' => ['required'],
        ];
    }
}
