<?php

namespace App\Http\Requests\v1\User\Menu;

use Illuminate\Foundation\Http\FormRequest;

class ReplacementMenuDishRequest extends FormRequest
{

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'old' => [
                'required',
                'string',
            ],
            'new' => [
                'required',
                'string',
            ],
            'portions' => [
                'nullable',
                'integer',
            ],
        ];
    }
}
