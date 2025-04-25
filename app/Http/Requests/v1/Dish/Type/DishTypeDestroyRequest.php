<?php

namespace App\Http\Requests\v1\Dish\Type;

use Illuminate\Foundation\Http\FormRequest;

class DishTypeDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:dish_types,uuid'],
        ];
    }
}
