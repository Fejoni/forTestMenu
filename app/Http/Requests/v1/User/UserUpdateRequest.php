<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required'],
            'password' => ['required', 'min:6'],
            'email' => ['required', 'email'],
            'family' => ['required'],
            'selectedTimes' => ['required'],
        ];
    }

    public function messages(): array
    {
        return [
            'selectedTimes.required' => 'Поле приема пищи обязательно для заполнения',
        ];
    }
}
