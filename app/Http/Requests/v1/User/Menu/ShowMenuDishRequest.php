<?php

namespace App\Http\Requests\v1\User\Menu;

use Illuminate\Foundation\Http\FormRequest;

class ShowMenuDishRequest extends FormRequest
{
    public function validationData(): array
    {
        return array_merge($this->all(), [
            'uuid' => $this->route('uuid'),
        ]);
    }

    public function rules(): array
    {
        return [
            'uuid' => [
                'required',
                'string',
                'exists:dishes,uuid'
            ]
        ];
    }
}
