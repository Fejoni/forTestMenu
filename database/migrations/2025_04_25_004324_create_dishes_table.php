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
        Schema::create('dishes', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->float('calories');
            $table->string('photo');
            $table->string('recipe');
            $table->boolean('is_premium');
            $table->float('protein');
            $table->float('carbohydrates');
            $table->float('fats');
            $table->float('portions');
            $table->float('cookingTime');
            $table->float('weight');

            $table->uuid('category_id');
            $table->foreign('category_id')->references('uuid')->on('dish_categories')->onDelete('cascade');

            $table->uuid('time_id');
            $table->foreign('time_id')->references('uuid')->on('dish_times')->onDelete('cascade');

            $table->uuid('suitable_id');
            $table->foreign('suitable_id')->references('uuid')->on('dish_suitables')->onDelete('cascade');

            $table->uuid('type_id');
            $table->foreign('type_id')->references('uuid')->on('dish_types')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
