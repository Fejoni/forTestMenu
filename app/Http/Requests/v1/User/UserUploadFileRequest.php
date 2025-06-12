<?php

namespace App\Http\Requests\v1\User;

use Illuminate\Foundation\Http\FormRequest;

class UserUploadFileRequest extends FormRequest
{

    public function rules(): array
    {
        return [
            'file' => 'required|image|mimes:jpeg,png,jpg,gif,svg,webp', // до 2MB
        ];
    }
}
