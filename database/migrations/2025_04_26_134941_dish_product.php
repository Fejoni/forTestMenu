<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dish_product', function (Blueprint $table) {
            $table->uuid('dish_id');
            $table->uuid('product_id');
            $table->integer('quantity');

            $table->foreign('dish_id')->references('uuid')->on('dishes')->onDelete('cascade');
            $table->foreign('product_id')->references('uuid')->on('products')->onDelete('cascade');

            $table->primary(['dish_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_product');
    }
};
