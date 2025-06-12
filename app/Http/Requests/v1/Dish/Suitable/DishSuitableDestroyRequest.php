<?php

namespace App\Http\Requests\v1\Dish\Suitable;

use Illuminate\Foundation\Http\FormRequest;

class DishSuitableDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'string', 'exists:dish_suitables,uuid'],
        ];
    }
}
