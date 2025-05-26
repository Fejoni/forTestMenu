<?php

namespace App\Console\Commands;

use App\Models\Dish\Dish;
use App\Models\Product\Product;
use App\Models\Product\ProductUnit;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ImportDishes extends Command
{
    protected $signature = 'import:dishes {file?}';
    protected $description = 'Импортирует блюда из JSON файла';

    public function handle(): int
    {
        $filePath = $this->argument('file') ?? $this->ask('Введите путь к JSON-файлу (например: public/data.json)');

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
                $dish = new Dish;
                $dish->name = $item['name'];
                $dish->calories = $item['calories'];
                $dish->protein = $item['proteins'];
                $dish->carbohydrates = $item['carbs'];
                $dish->fats = $item['fats'];
                $dish->is_premium = 0;
                $dish->recipe = $item['recipe_no_tags'];
                $dish->portions = 1;
                $dish->weight = 100;
                $dish->save();

                foreach ($item['ingredients'] as $ingredient) {
                    $unit = ProductUnit::query()->firstOrCreate(['name' => $ingredient['unit']]);

                    $product = Product::query()->firstOrCreate(
                        ['name' => $ingredient['name']],
                        ['unit_id' => $unit->uuid]
                    );

                    $dish->products()->syncWithoutDetaching([
                        $product->uuid => ['quantity' => $ingredient['count'] ?? 1]
                    ]);
                }

                $url = 'https://neuroimg.art/api/v1/generate';
                $headers = ['Content-Type: application/json'];
                $post_data = [
                    "token" => "36327fcc-de17-4307-a3b1-0aef239f50c4",
                    "model" => "MaxRealFLux-v3.0fp8",
                    "prompt" => "Блюдо " . $item['name'] . " простое",
                    "width" => 512,
                    "height" => 512,
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
