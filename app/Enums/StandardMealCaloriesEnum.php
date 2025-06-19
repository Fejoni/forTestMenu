<?php

namespace App\Enums;

enum StandardMealCaloriesEnum
{
    case BREAKFAST;
    case LUNCH;
    case DINNER;

    public function calories(): int
    {
        return match ($this) {
            self::BREAKFAST => 500,
            self::LUNCH => 700,
            self::DINNER => 600,
        };
    }

    public function dishName(): string
    {
        return match ($this) {
            self::BREAKFAST => 'Завтрак',
            self::LUNCH => 'Обед',
            self::DINNER => 'Ужин',
        };
    }
}
