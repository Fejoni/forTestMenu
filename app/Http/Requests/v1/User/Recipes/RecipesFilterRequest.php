<?php

namespace App\Http\Requests\v1\User\Recipes;

use Illuminate\Foundation\Http\FormRequest;

class RecipesFilterRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'data' => ['required', 'array'],
            'data.*.uuid' => ['required', 'string', 'exists:products,uuid'],
            'data.*.quantity' => ['required', 'numeric', 'min:0'],
        ];
    }
}
