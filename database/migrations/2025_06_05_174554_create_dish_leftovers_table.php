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
        Schema::create('dish_leftovers', function (Blueprint $table) {
            $table->comment('Хранение остатков блюд для будущего использования');

            $table->uuid()->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignUuid('dish_id')->constrained('dishes', 'uuid')->cascadeOnDelete()->cascadeOnUpdate();
            $table->uuid('dish_time_uuid')->comment('UUID времени приёма пищи, к которому применим остаток');
            $table->integer('portions')->comment('Количество оставшихся порций');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_leftovers');
    }
};
