<?php

namespace App\Http\Requests\v1\User\Dish;

use Illuminate\Foundation\Http\FormRequest;

class UserDishRandomRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'category_id' => ['nullable', 'string', 'exists:dish_categories,uuid'],
            'dish_time_id' => ['nullable', 'string', 'exists:dish_times,uuid'],
            'type_id' => ['nullable', 'string', 'exists:dish_types,uuid'],
            'dish_suitable_id' => ['nullable', 'string', 'exists:dish_suitables,uuid'],
            'cookingTime' => ['nullable', 'integer'],
            'previous_dish_id' => ['nullable', 'string', 'exists:dishes,uuid'],
        ];
    }
}
