<?php

namespace App\Console\Commands;

use App\Jobs\GenerateImageFromTextJob;
use App\Jobs\GenerateRecipeTextJob;
use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\Dish\DishSuitable;
use App\Models\Dish\DishTime;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductDivision;
use App\Models\Product\ProductUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;

class DishesChangeUrl extends Command
{
    protected $signature = 'changeurl:dishes';
    protected $description = 'Замена url dish';



    public function handle(): int
    {
        $items = Dish::all();
        foreach ($items as $item){
            if($item->photo){
                $item->photo = str_replace('https://api.youamm.ru/', 'https://api.yomun.ru/',$items->photo ) ;
                $item->save();
            }
        }



        return 0;
    }
}
