<?php

namespace App\Http\Requests\v1\Dish\Type;

use Illuminate\Foundation\Http\FormRequest;

class DishTypeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'id' => ['string']
        ];
    }
}
