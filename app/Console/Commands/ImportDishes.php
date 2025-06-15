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

class ImportDishes extends Command
{
    protected $signature = 'import:dishes {file?}';
    protected $description = 'Импортирует блюда из JSON файла';

    public function rand($val)
    {
        $min = -3;
        $max = 4;
        $newval = $val + ($min + ($max - $min) * (mt_rand() / mt_getrandmax()));
        if($newval < 0){
            $newval = $newval * -1;
        }
        return $newval;
    }

    public function handle(): int
    {
        $filePath = $this->argument('file') ?? $this->ask('Введите путь к JSON-файлу (например: public/data.json)');

        $productCategory = ProductCategory::query()->where('name', 'Другое')->first();
        $productDivision= ProductDivision::query()->where('name', 'Гастрономия')->first();


        if (!file_exists($filePath)) {
            // Попробуем привести путь к абсолютному, если он относительный
            $filePath = storage_path(str_starts_with($filePath, '/') ?
                str_replace(base_path(), '', $filePath) : $filePath);
        }

        if (!file_exists($filePath)) {
            $this->error("Файл не найден: {$filePath}");
            return 1;
        }

        $json = file_get_contents($filePath);


        $data = json_decode($json, true);

        if (!$data || !is_array($data)) {
            $this->error('Некорректный JSON.');
            return 1;
        }

        foreach ($data as $item) {
            $this->info("Создаем блюдо: {$item['name']}");
            if (!Dish::query()->where('name', $item['name'])->exists()) {

                $weight = 100;
                if (isset($item['table']) AND isset($item['table']['total']) AND isset($item['table']['total']['weight'])) {
                    $weight = $item['table']['total']['weight'];
                }

                $dish = new Dish;
                $dish->name = $item['name'];
                $dish->calories = number_format($this->rand($item['calories']), 1);
                $dish->protein = number_format($this->rand($item['proteins']), 1);
                $dish->carbohydrates = number_format($this->rand($item['carbs']), 1);
                $dish->fats = number_format($this->rand($item['fats']), 1);
                $dish->is_premium = 0;
                $dish->recipe = null;
                $dish->portions = $item['recipes_portions'] ?? 1;
                $dish->timeText = $item['time'];
                $dish->weight = rand(-30, 30) + $weight;
                $dish->save();

                GenerateRecipeTextJob::dispatch($dish, $item['recipe_no_tags']);

                foreach ($item['type'] as $type) {
                    $time = DishTime::query()->where('name', $type)->first();
                    if ($time) {
                        $dish->times()->attach($time->uuid);
                    } else {
                        $catDish = DishCategory::query()->where('name', $type)->first();
                        if ($catDish) {
                            $dish->category_id = $catDish->uuid;
                            $dish->save();
                        } else {
                            $suitable = DishSuitable::query()->firstOrCreate(['name' => $type]);
                            $dish->suitables()->attach($suitable);
                        }
                    }
                }

                if(count($item['ingredients']) > 0){
                    foreach ($item['ingredients'] as $ingredient) {
                        $unit = ProductUnit::query()->firstOrCreate(['name' => $ingredient['unit']]);

                        $product = Product::query()->firstOrCreate(
                            ['name' => $ingredient['name']],
                            ['unit_id' => $unit->uuid],
                            ['divisions_id'=> '18f12d86-55fa-423d-ac55-0b1da2cb5add'],
                            ['categories_id', 'f7596b7e-84b9-4e90-8888-d8f94c907aaa']
                        );

                        $quantity = str_replace(',', '.', $ingredient['count']);
                        if ($quantity == 'null' OR $quantity == null) {
                            $quantity = 1;
                        }

                        $dish->products()->syncWithoutDetaching([
                            $product->uuid => ['quantity' =>
                                $quantity ?? 1
                            ]
                        ]);
                    }
                }
                elseif($item['table'] AND $item['table']['products']){
                    foreach ($item['table']['products'] as $ingredient) {
                        $unitName = mb_eregi_replace('[0-9]', '', $ingredient['measure']);
                        $unitName = mb_eregi_replace('[\s]', '', $unitName);
                        if($unitName == '' OR !$unitName){
                            $unitName = 'гр';
                        }


                        $unit = ProductUnit::query()->firstOrCreate(['name' => $unitName ?? 'гр']);
                        $this->info('Ед. изм ' . $unitName);

                        $product = Product::query()->firstOrCreate(
                            ['name' => $ingredient['product_name']],
                            [
                                'unit_id' => $unit->uuid,
                                'categories_id','f7596b7e-84b9-4e90-8888-d8f94c907aaa',
                                'divisions_id', '18f12d86-55fa-423d-ac55-0b1da2cb5add',
                                'protein' => $ingredient['protein'],
                                'carbohydrates' => $ingredient['carbs'],
                                'fats' => $ingredient['fats'],
                                'calories' => $ingredient['calories']
                            ],
                        );

                        $dish->products()->syncWithoutDetaching([
                            $product->uuid => ['quantity' =>
                                $ingredient['weight'] ?? 1
                            ]
                        ]);
                    }
                }


                GenerateImageFromTextJob::dispatch($dish, $item['recipe_no_tags']);

                $this->info("✅ Добавлено блюдо: {$dish->name}");
            } else {
                $this->warn("⚠️ Блюдо уже существует: {$item['name']}");
            }
        }

//        Artisan::call('products:generate-images');
//        $this->info('🎯 Запущена генерация изображений для продуктов без изображения.');

        return 0;
    }
}
