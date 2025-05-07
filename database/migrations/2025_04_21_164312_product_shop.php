<?php

use App\Models\Product\ProductCategory;
use App\Models\Product\ProductDivision;
use App\Models\Product\ProductUnit;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('product_product_shop', function (Blueprint $table) {
            $table->id();

            $table->uuid('product_uuid');
            $table->uuid('product_shop_uuid');

            $table->foreign('product_uuid')->references('uuid')->on('products')->onDelete('cascade');
            $table->foreign('product_shop_uuid')->references('uuid')->on('product_shops')->onDelete('cascade');

            $table->timestamps();
        });

        $unit1 = ProductUnit::inRandomOrder()->first()?->uuid;
        $unit2 = ProductUnit::inRandomOrder()->first()?->uuid;

        $category1 = ProductCategory::inRandomOrder()->first()?->uuid;
        $category2 = ProductCategory::inRandomOrder()->first()?->uuid;

        $division1 = ProductDivision::inRandomOrder()->first()?->uuid;
        $division2 = ProductDivision::inRandomOrder()->first()?->uuid;

        $products = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Яблоко Гала',
                'image' => 'https://i.pinimg.com/736x/0e/52/85/0e5285f5430e8f957ebd730c978b98d6.jpg',
                'unit_id' => $unit1,
                'categories_id' => $category1,
                'divisions_id' => $division1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Молоко 3.2%',
                'image' => 'https://i.pinimg.com/736x/9e/78/ba/9e78ba551c7c9ee3b7ae1554e9511758.jpg',
                'unit_id' => $unit1,
                'categories_id' => $category1,
                'divisions_id' => $division2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Картофель молодой',
                'image' => 'https://i.pinimg.com/736x/02/d0/34/02d03418d5d15b7d597fe1bbb9090404.jpg',
                'unit_id' => $unit2,
                'categories_id' => $category2,
                'divisions_id' => $division1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \Illuminate\Support\Facades\DB::table('products')->insert($products);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_shop');
    }
};
