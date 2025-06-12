<?php

namespace App\Console\Commands;

use App\Jobs\GenerateProductImageFromTextJob;
use App\Models\Product\Product;
use Illuminate\Console\Command;

class GenerateImagesForProducts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:generate-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Генерирует изображения для продуктов без изображений';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $products = Product::whereNull('image')->orWhere('image', '')->get();

        if ($products->isEmpty()) {
            $this->info('Нет продуктов без изображения.');
            return 0;
        }

        foreach ($products as $product) {
            GenerateProductImageFromTextJob::dispatch($product);
            $this->info("Запущена генерация изображения для: {$product->name}");
        }

        return 0;
    }
}
