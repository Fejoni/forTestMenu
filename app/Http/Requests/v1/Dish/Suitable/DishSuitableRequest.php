<?php

namespace App\Http\Requests\v1\Dish\Suitable;

use Illuminate\Foundation\Http\FormRequest;

class DishSuitableRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'id' => ['string']
        ];
    }
}
