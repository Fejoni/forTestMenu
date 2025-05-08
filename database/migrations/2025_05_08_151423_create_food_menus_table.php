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
        Schema::create('food_menus', function (Blueprint $table) {
            $table->id();
            $table->foreignId('users_id')->nullable()->index()->constrained('users')->onDelete('cascade');

            $table->uuid('dish_time_id')->nullable()->index();
            $table->foreign('dish_time_id')->references('uuid')->on('dish_times')->onDelete('cascade');

            $table->string('day');
            $table->timestamps();
        });

        Schema::create('food_menu_dish_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('food_menu_id')->nullable()->index()->constrained('food_menus')->onDelete('cascade');

            $table->uuid('dish_id')->nullable()->index();
            $table->foreign('dish_id')->references('uuid')->on('dishes')->onDelete('cascade');

            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('food_menus');
        Schema::dropIfExists('food_menu_dish_product');
    }
};
