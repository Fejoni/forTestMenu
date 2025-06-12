<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'persons' => ['required'],
            'selectedTimes' => ['required', 'array'],
            'selectedTimes.*.id' => ['required'],
            'selectedTimes.*.calories' => ['required']
        ];
    }
}
