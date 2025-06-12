<?php

namespace App\Http\Requests\v1\Dish\Time;

use Illuminate\Foundation\Http\FormRequest;

class DishTimeDestroyRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'id' => ['required', 'string'],
        ];
    }
}
