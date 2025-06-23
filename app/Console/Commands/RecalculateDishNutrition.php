<?php

namespace App\Console\Commands;

use App\Jobs\RecalculateDishNutritionJob;
use App\Models\Dish\Dish;
use Illuminate\Console\Command;

class RecalculateDishNutrition extends Command
{
    protected $signature = 'dishes:recalculate-nutrition';

    protected $description = 'Пересчитать калории, белки и жиры по фактическому весу блюда';

    public function handle(): int
    {
        Dish::query()->chunk(100, function ($dishes) {
            foreach ($dishes as $dish) {
                $weight = $dish->weight;
                if ($weight ) {
                    $this->dish->update([
                        'calories' => $this->dish->calories / $weight * 100  ,
                        'protein' => $this->dish->protein / $weight * 100,
                        'fats' => $this->dish->fats / $weight * 100,
                        'is_nutrition_recalculated' => false,
                    ]);
//                if ($dish->is_nutrition_recalculated) {
//                    $this->info("Блюдо уже пересчитано: {$dish->name}");
//                    continue;
//                }

                    RecalculateDishNutritionJob::dispatchSync($dish);
                    $this->info("Пересчитан вес блюда: {$dish->name}");
                }


            }
        });

        return 0;
    }
}
