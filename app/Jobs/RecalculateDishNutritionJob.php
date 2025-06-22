<?php

namespace App\Jobs;

use App\Models\Dish\Dish;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class RecalculateDishNutritionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Dish $dish)
    {
    }

    public function handle(): void
    {
        $weight = $this->dish->weight;
        if (!$weight || $this->dish->is_nutrition_recalculated) {
            return;
        }

        $this->dish->update([
            'calories' => $this->dish->calories / 100 * $weight,
            'protein' => $this->dish->protein / 100 * $weight,
            'fats' => $this->dish->fats / 100 * $weight,
            'is_nutrition_recalculated' => true,
        ]);
    }
}
