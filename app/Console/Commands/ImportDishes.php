<?php

namespace App\Console\Commands;

use App\Models\Dish\Dish;
use App\Models\Dish\DishCategory;
use App\Models\Dish\DishSuitable;
use App\Models\Dish\DishTime;
use App\Models\Dish\DishType;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductDivision;
use App\Models\Product\ProductUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportDishes extends Command
{
    protected $signature = 'import:dishes {file?}';
    protected $description = 'Импортирует блюда из JSON файла';

    public function rand($val)
    {
        $min = -3;
        $max = 4;
        return $val+($min + ($max - $min) * (mt_rand() / mt_getrandmax()));
    }

    public function handle(): int
    {
        $filePath = $this->argument('file') ?? $this->ask('Введите путь к JSON-файлу (например: public/data.json)');

        $productCategory = ProductCategory::query()->where('name',  'Другое')->first();


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
                if(isset($item['table']) AND isset($item['table']['total']) AND isset($item['table']['total']['weight'])){
                    $weight = $item['table']['total']['weight'];
                }

                $dish = new Dish;
                $dish->name = $item['name'];
                $dish->calories = number_format($this->rand($item['calories']), 1);
                $dish->protein = number_format($this->rand($item['proteins']), 1);
                $dish->carbohydrates = number_format($this->rand($item['carbs']), 1);
                $dish->fats = number_format($this->rand($item['fats']), 1);
                $dish->is_premium = 0;
                $dish->recipe = $item['recipe_no_tags'];
                $dish->portions = $item['recipes_portions'];
                $dish->timeText = $item['time'];
                $dish->weight = rand(-30, 30)+$weight;
                $dish->save();

                foreach ($item['type'] as $type) {
                    $time = DishTime::query()->where('name', $type)->first();
                    if($time){
                        $dish->times()->attach($time->uuid);
                    }
                    else{
                        $catDish = DishCategory::query()->where('name', $type)->first();
                        if($catDish){
                            $dish->category_id = $catDish->uuid;
                            $dish->save();
                        }
                        else{
                            $suitable = DishSuitable::query()->firstOrCreate(['name' => $type]);
                            $dish->suitables()->attach($suitable);
                        }
                    }
                }

                foreach ($item['ingredients'] as $ingredient) {
                    $unit = ProductUnit::query()->firstOrCreate(['name' => $ingredient['unit']]);

                    $product = Product::query()->firstOrCreate(
                        ['name' => $ingredient['name']],
                        ['unit_id' => $unit->uuid],
                        ['categories_id', $productCategory->uuid]
                    );

                    $quantity =  str_replace(',', '.', $ingredient['count']);
                    if($quantity == 'null' OR $quantity == null){
                        $quantity = 1;
                    }
                    $dish->products()->syncWithoutDetaching([
                        $product->uuid => ['quantity' =>
                            $quantity ?? 1
                        ]
                    ]);
                }

                $url = 'https://neuroimg.art/api/v1/generate';
                $headers = ['Content-Type: application/json'];
                $post_data = [
                    "token" => "36327fcc-de17-4307-a3b1-0aef239f50c4",
                    "model" => "HUBG_Flux.1丨BeautifulRealistic-Alpha",
                    "prompt" => "Блюдо " . $item['name'] . " простое",
                    "width" => 1024,
                    "height" => 1024,
                    "steps" => 30,
                    "stream" => false
                ];

                $curl = curl_init();
                curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
                curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($post_data));
                curl_setopt($curl, CURLOPT_URL, $url);
                curl_setopt($curl, CURLOPT_POST, true);

                $result = json_decode(curl_exec($curl), true);
                curl_close($curl);

                if (isset($result['status']) && $result['status'] === 'SUCCESS') {
                    $imageUrl = $result['image_url'];
                    $imageContents = file_get_contents($imageUrl);

                    if ($imageContents) {
                        $fileName = 'dishes/' . uniqid() . '.jpg';
                        Storage::disk('public')->put($fileName, $imageContents);
                        $dish->photo = url(Storage::url($fileName));
                        $dish->save();
                    }
                }

                $this->info("✅ Добавлено блюдо: {$dish->name}");
            } else {
                $this->warn("⚠️ Блюдо уже существует: {$item['name']}");
            }
        }

        return 0;
    }
}
