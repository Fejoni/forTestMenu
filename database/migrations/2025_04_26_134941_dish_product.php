<?php

use App\Models\Dish\Dish;
use App\Models\Product\Product;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dish_product', function (Blueprint $table) {
            $table->uuid('dish_id')->index();
            $table->uuid('product_id')->index();
            $table->integer('quantity');

            $table->foreign('dish_id')->references('uuid')->on('dishes')->onDelete('cascade');
            $table->foreign('product_id')->references('uuid')->on('products')->onDelete('cascade');

            $table->primary(['dish_id', 'product_id']);
        });

        $dish1 = Dish::query()->first()?->uuid;
        $dish2 = Dish::query()->skip(1)->first()?->uuid;

        $product1 = Product::query()->first()?->uuid;
        $product2 = Product::query()->skip(1)->first()?->uuid;

        $dishProducts = [
            [
                'dish_id' => $dish1,
                'product_id' => $product1,
                'quantity' => 2,
            ],
            [
                'dish_id' => $dish1,
                'product_id' => $product2,
                'quantity' => 1,
            ],
        ];

        DB::table('dish_product')->insert($dishProducts);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_product');
    }
};
