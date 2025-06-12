<?php

namespace App\Http\Requests\v1\User\Menu;

use Illuminate\Foundation\Http\FormRequest;

class AppendMenuDishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'new' => [
                'required',
                'string'
            ],
            'time' => [
                'required',
                'string'
            ],
            'day' => [
                'required',
                'string'
            ],
            'portions' => [
                'nullable',
                'integer'
            ]
        ];
    }
}
