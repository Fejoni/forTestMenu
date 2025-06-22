<?php

namespace App\Console\Commands;

use App\Jobs\AddSnackTimesJob;
use App\Models\Dish\Dish;
use App\Models\Dish\DishTime;
use Illuminate\Console\Command;

class AddSnackTimes extends Command
{
    protected $signature = 'dishes:add-snack-times';

    protected $description = 'Добавляет время Полдник и Ланч блюдам определённых категорий';

    public function handle(): int
    {
        $categoryNames = ['Бутерброды', 'Перекус', 'Закуски', 'Выпечка', 'салаты'];

        $times = DishTime::query()->whereIn('name', ['Полдник', 'Ланч'])->pluck('uuid');

        if ($times->count() < 2) {
            $this->error('Не найдены времена Полдник или Ланч.');
            return self::FAILURE;
        }

        $dishes = Dish::query()
            ->whereHas('category', fn ($q) => $q->whereIn('name', $categoryNames))
            ->get();

        foreach ($dishes as $dish) {
            AddSnackTimesJob::dispatch($dish, $times->all());
        }

        $this->info("Запущено задач: {$dishes->count()}");

        return self::SUCCESS;
    }
}
