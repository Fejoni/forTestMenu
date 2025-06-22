<?php

namespace App\Enums;

enum DishTimeEnum: string
{
    case BREAKFAST = 'Завтрак';
    case LUNCH = 'Обед';
    case AFTERNOON_SNACK = 'Полдник';
    case DINNER = 'Ужин';
    case BRUNCH = 'Ланч';

    public static function lunchOrDinner(): array
    {
        return [
            self::LUNCH->value,
            self::DINNER->value,
        ];
    }
}
