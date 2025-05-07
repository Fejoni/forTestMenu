<?php

namespace App\Http\Requests\v1\Telegram\Family;

use Illuminate\Foundation\Http\FormRequest;

class FamilyAddRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'count' => ['required', 'integer'],
        ];
    }
}
