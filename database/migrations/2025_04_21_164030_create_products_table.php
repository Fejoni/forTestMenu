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
        Schema::create('products', function (Blueprint $table) {
            $table->uuid('uuid')->primary();
            $table->string('name');
            $table->string('image')->nullable();
            $table->float('count')->nullable();

            $table->uuid('unit_id')->nullable()->index();
            $table->uuid('categories_id')->nullable()->index();
            $table->uuid('divisions_id')->nullable()->index();

            $table->foreign('unit_id')->references('uuid')->on('product_units')->onDelete('cascade');
            $table->foreign('categories_id')->references('uuid')->on('product_categories')->onDelete('cascade');
            $table->foreign('divisions_id')->references('uuid')->on('product_divisions')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
