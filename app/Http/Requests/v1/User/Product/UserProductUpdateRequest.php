<?php

namespace App\Http\Requests\v1\User\Product;

use Illuminate\Foundation\Http\FormRequest;

class UserProductUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'division_id' => ['required'],
            'category_id' => ['required'],
            'quantity' => ['required'],
            'id' => ['required']
        ];
    }
}
