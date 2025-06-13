<?php

namespace App\Observers;

use App\Models\User;
use App\Models\Dish\DishTime;

class UserObserver
{
    public function created(User $user): void
    {
        $defaultTimes = [
            'Завтрак' => 500,
            'Обед' => 600,
            'Ужин' => 600,
        ];

        $times = DishTime::query()
            ->whereIn('name', array_keys($defaultTimes))
            ->get()
            ->keyBy('name');

        foreach ($defaultTimes as $name => $calories) {
            if (isset($times[$name])) {
                $user->dishTimes()->attach($times[$name]->uuid, ['calories' => $calories]);
            }
        }
    }
}
