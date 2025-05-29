<?php

namespace App\Http\Requests\v1\User\Dish;

use Illuminate\Foundation\Http\FormRequest;

class UserDishUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'receipt' => ['required'],
            'image' => ['required'],
            'dish_time_id' => ['required'],
            'cooking_time' => ['required'],
            'dish_category_id' => ['required'],
            'id' => ['required', 'string'],
        ];
    }
}
