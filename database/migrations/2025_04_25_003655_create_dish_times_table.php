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
        Schema::create('dish_times', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->timestamps();
        });

        $datas = [
            [
                'name' => 'Завтрак'
            ],
            [
                'name' => 'Ужин'
            ]
        ];

        foreach ($datas as $data) {
            \App\Models\Dish\DishTime::query()->create($data);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dish_times');
    }
};
