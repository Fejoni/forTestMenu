<?php

namespace App\Http\Requests\v1\User\Menu;

use Illuminate\Foundation\Http\FormRequest;

class IndexMenuDishRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'pre_page' => [
                'nullable',
                'integer'
            ],
            'name' => [
                'nullable',
                'string'
            ],
            'dish_time_ids' => [
                'nullable',
                'array',
            ],
            'dish_time_ids.*' => [
                'nullable',
                'string',
                'exists:dish_times,uuid'
            ],
            'category_ids' => [
                'nullable',
                'array',
            ],
            'category_ids.*' => [
                'nullable',
                'string',
                'exists:dish_categories,uuid'
            ],
            'type_ids' => [
                'nullable',
                'array',
            ],
            'type_ids.*' => [
                'nullable',
                'string',
                'exists:dish_types,uuid'
            ],
            'dish_suitable_ids' => [
                'nullable',
                'array',
            ],
            'dish_suitable_ids.*' => [
                'nullable',
                'string',
                'exists:dish_suitables,uuid'
            ],
        ];
    }
}
