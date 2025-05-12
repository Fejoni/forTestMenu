<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('dish_times', function (Blueprint $table) {
            $table->uuid('uuid')->primary();  // Явно указываем имя столбца
            $table->string('name');
            $table->timestamps();
        });

        DB::table('dish_times')->insert([
            [
                'uuid' => Str::uuid(),
                'name' => 'Завтрак',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Ужин',
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);

        Schema::create('user_dish_times', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->uuid('dish_time_uuid');
            $table->foreign('dish_time_uuid')
                ->references('uuid')
                ->on('dish_times')
                ->onDelete('cascade');

            $table->integer('calories');
            $table->timestamps();

            $table->unique(['user_id', 'dish_time_uuid'], 'user_dish_time_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('user_dish_times');
        Schema::dropIfExists('dish_times');
    }
};
