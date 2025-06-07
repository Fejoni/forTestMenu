<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class GetAvailableProductsGroupedByDivisionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => [
                'nullable',
                'string'
            ],
            'divisions_id' => [
                'nullable',
                'string',
                'exists:product_divisions,uuid'
            ],
            'pre_page' => [
                'nullable',
                'integer'
            ]
        ];
    }
}
