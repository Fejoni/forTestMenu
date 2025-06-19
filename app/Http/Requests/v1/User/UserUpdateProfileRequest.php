<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUpdateProfileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'count_persons' => ['sometimes', 'integer'],
            'weight' => ['required', 'integer'],
            'height' => ['required', 'integer'],
            'age' => ['required', 'integer'],
            'gender' => ['required', 'string', 'in:male,female'],
            'activity' => ['sometimes', 'string', 'in:low,medium,high,hard'],
            'user_task' => ['sometimes', 'string', 'in:lose,save,dial'],
            'count_per_day' => ['sometimes', 'integer', 'min:1', 'max:5'],
            'check_privacy' => ['required', 'accepted'],
        ];
    }

    public function messages(): array
    {
        return [
            'check_privacy.accepted' => 'Необходимо принять политику конфиденциальности',
        ];
    }
}
