<?php

namespace App\Observers;

use App\Enums\StandardMealCaloriesEnum;
use App\Models\Dish\DishTime;
use App\Models\User;

class UserObserver
{
    public function created(User $user): void
    {
        $uuids = DishTime::query()
            ->whereIn('name', array_map(
                static fn (StandardMealCaloriesEnum $meal) => $meal->dishName(),
                StandardMealCaloriesEnum::cases()
            ))
            ->pluck('uuid', 'name');

        $data = [];

        foreach (StandardMealCaloriesEnum::cases() as $meal) {
            $name = $meal->dishName();

            if (isset($uuids[$name])) {
                $data[$uuids[$name]] = ['calories' => $meal->calories()];
            }
        }

        if (!empty($data)) {
            $user->dishTimes()->sync($data);
        }
    }
}
