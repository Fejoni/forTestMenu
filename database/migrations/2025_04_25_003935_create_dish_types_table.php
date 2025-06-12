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
        Schema::create('dish_types', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->timestamps();
        });

        $datas = [
            [
                'name' => 'пицца'
            ],
            [
                'name' => 'паста'
            ]
        ];

        foreach ($datas as $data) {
            \App\Models\Dish\DishType::query()->create($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_types');
    }
};
