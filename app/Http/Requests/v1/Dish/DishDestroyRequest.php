<?php

namespace App\Http\Requests\v1\Dish;

use Illuminate\Foundation\Http\FormRequest;

class DishDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:dishes,uuid'],
        ];
    }
}
