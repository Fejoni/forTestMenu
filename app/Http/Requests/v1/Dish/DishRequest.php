<?php

namespace App\Http\Requests\v1\Dish;

use Illuminate\Foundation\Http\FormRequest;

class DishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string'],
            'id' => ['nullable', 'uuid'],
            'products' => ['array'],
            'products.*.product_id' => ['uuid'],
            'products.*.quantity' => ['numeric'],
            'image' => [
                'nullable',
                'image',
                'mimes:jpeg,png,jpg,gif,svg,webp'
            ],
            'video' => [
                'nullable',
                'file',
            ],
            'is_premium' => [
                'required',
                'boolean'
            ],
        ];
    }
}
