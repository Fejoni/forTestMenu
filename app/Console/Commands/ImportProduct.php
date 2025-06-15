<?php

namespace App\Console\Commands;

use App\Models\Dish\Dish;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class ImportProduct extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:import-product {file?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $filePath = 'app/public/data.json';

        $productCategory = ProductCategory::query()->where('name',  'Другое')->first();


        if (!file_exists($filePath)) {
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

//        $this->info(print_r($data, 1));

        foreach ($data as $item) {
            $this->info("Обращаемся к блюду: {$item['name']}");
            $dish = Dish::query()->where('name', $item['name'])->first();
            if ($dish) {
                foreach ($item['table']['products'] as $ingredient) {
                    $this->info('Продукт: ' . $ingredient['product_name']);
                    $unitName = mb_eregi_replace('[0-9]', '', $ingredient['measure']);
                    $unitName = mb_eregi_replace('[\s]', '', $unitName);
                    if($unitName == '' OR !$unitName){
                        $unitName = 'гр.';
                    }


                    $unit = ProductUnit::query()->firstOrCreate(['name' => $unitName ?? 'гр.']);
                    $this->info('Ед. изм ' . $unitName);

                    $product = Product::query()->firstOrCreate(
                        ['name' => $ingredient['product_name']],
                        [
                            'unit_id' => $unit->uuid,
                            'categories_id', $productCategory->uuid,
                            'protein' => $ingredient['protein'],
                            'carbohydrates' => $ingredient['carbs'],
                            'fats' => $ingredient['fats'],
                            'calories' => $ingredient['calories']
                        ],
                    );

                    $existingRecord = DB::table('dish_product')
                        ->where('dish_id', $dish->uuid)
                        ->where('product_id', $product->uuid)
                        ->first();

                    $weightIngredient = $ingredient['weight'];
                    if($weightIngredient == '1/2'){
                        $weightIngredient = 0.5;
                    }
                    if($weightIngredient == '1/8'){
                        $weightIngredient = 0.125;
                    }
                    if($weightIngredient == '1-2'){
                        $weightIngredient = 1;
                    }
                    if($weightIngredient == '1/4'){
                        $weightIngredient = 0.25;
                    }
                    if($weightIngredient == '2/3'){
                        $weightIngredient = 0.6;
                    }
                    if($weightIngredient == '1/3'){
                        $weightIngredient = 0.3;
                    }

                    if (!$existingRecord) {
                        DB::table('dish_product')->insert([
                            'dish_id' => $dish->uuid,
                            'product_id' => $product->uuid,
                            'quantity' => $weightIngredient ?? 1
                        ]);
                    } else {
                        $this->warn("⚠️ Запись уже существует для блюда: {$dish->name} и продукта: {$product->name}");
                    }
                }

                $this->info("✅ Добавлено в блюдо: {$dish->name}");
            } else {
                $this->warn("⚠️ Блюдо уже существует: {$item['name']}");
            }
        }

        Artisan::call('products:generate-images');
        $this->info('🎯 Запущена генерация изображений для продуктов без изображения.');

        return 0;
    }
}
