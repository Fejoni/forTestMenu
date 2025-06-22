<?php

namespace App\Enums;

enum DishCategoryEnum: string
{
    case SECOND_DISH = 'Вторые блюда';
    case FIRST_DISH = 'Первые блюда';
    case SALAD = 'Салаты';
    case DESSERT = 'Десерты';
    case SAUCE = 'Соусы';

    public static function limitedForLunchDinner(): array
    {
        return [
            self::SECOND_DISH->value,
            self::FIRST_DISH->value,
            self::SALAD->value,
        ];
    }

    public static function excludedForBreakfast(): array
    {
        return [
            self::DESSERT->value,
            self::FIRST_DISH->value,
            self::SECOND_DISH->value,
            self::SAUCE->value,
        ];
    }
}
