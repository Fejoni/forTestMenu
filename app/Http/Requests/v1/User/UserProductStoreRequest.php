<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserProductStoreRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'division_id' => ['required'],
            'category_id' => ['required'],
            'quantity' => ['required']
        ];
    }
}
