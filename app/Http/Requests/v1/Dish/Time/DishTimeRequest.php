<?php

namespace App\Http\Requests\v1\Dish\Time;

use Illuminate\Foundation\Http\FormRequest;

class DishTimeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'id' => ['string']
        ];
    }
}
