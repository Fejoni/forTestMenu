<?php

namespace App\Http\Requests\v1\User\Product;

use Illuminate\Foundation\Http\FormRequest;

class UserProductUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'divisions_id' => ['required'],
            'categories_id' => ['required'],
            'unit_id' => ['required'],
            'count' => ['nullable'],
            'id' => ['required']
        ];
    }
}
