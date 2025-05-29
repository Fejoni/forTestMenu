<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserDishStoreRequest extends FormRequest
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
        ];
    }
}
