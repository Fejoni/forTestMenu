<?php

use App\Models\Dish\DishCategory;
use App\Models\Dish\DishSuitable;
use App\Models\Dish\DishTime;
use App\Models\Dish\DishType;
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
        Schema::create('dishes', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->float('calories')->nullable();
            $table->string('photo')->nullable();
            $table->text('recipe')->nullable();
            $table->boolean('is_premium');
            $table->float('protein')->nullable();
            $table->float('carbohydrates')->nullable();
            $table->float('fats')->nullable();
            $table->float('portions')->nullable();
            $table->float('cookingTime')->nullable();
            $table->string('timeText')->nullable();
            $table->float('weight')->nullable();

            $table->uuid('category_id')->nullable()->index();
            $table->foreign('category_id')->references('uuid')->on('dish_categories')->onDelete('cascade');

            $table->uuid('type_id')->nullable()->index();
            $table->foreign('type_id')->references('uuid')->on('dish_types')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('dish_dish_time', function (Blueprint $table) {
            $table->uuid('dish_id')->index();
            $table->uuid('time_id')->index();

            $table->foreign('dish_id')->references('uuid')->on('dishes')->onDelete('cascade');
            $table->foreign('time_id')->references('uuid')->on('dish_times')->onDelete('cascade');

//            $table->primary(['dish_id', 'time_id']);
        });


        Schema::create('dish_dish_suitable', function (Blueprint $table) {
            $table->uuid('dish_id')->index();
            $table->uuid('suitable_id')->index();

            $table->foreign('dish_id')->references('uuid')->on('dishes')->onDelete('cascade');
            $table->foreign('suitable_id')->references('uuid')->on('dish_suitables')->onDelete('cascade');

//            $table->primary(['dish_id', 'suitable_id']);
        });


        // Получаем случайные UUID'ы из связанных таблиц
        $category1 = DishCategory::query()->inRandomOrder()->first()?->uuid;
        $category2 = DishCategory::query()->skip(1)->inRandomOrder()->first()?->uuid;

        $time1 = DishTime::query()->inRandomOrder()->first()?->uuid;
        $time2 = DishTime::query()->skip(1)->inRandomOrder()->first()?->uuid;

        $suitable1 = DishSuitable::query()->inRandomOrder()->first()?->uuid;
        $suitable2 = DishSuitable::query()->skip(1)->inRandomOrder()->first()?->uuid;

        $type1 = DishType::query()->inRandomOrder()->first()?->uuid;
        $type2 = DishType::query()->skip(1)->inRandomOrder()->first()?->uuid;

        $dishes = [
            [
                'uuid' => Str::uuid(),
                'name' => 'Овсянка с фруктами',
                'calories' => 320,
                'photo' => 'https://i.pinimg.com/736x/0e/52/85/0e5285f5430e8f957ebd730c978b98d6.jpg',
                'recipe' => 'Смешать овсянку с молоком, добавить фрукты и орехи.',
                'is_premium' => false,
                'protein' => 10.5,
                'carbohydrates' => 45.2,
                'fats' => 6.1,
                'portions' => 1,
                'cookingTime' => 10,
                'weight' => 250,
                'category_id' => $category1,
                'type_id' => $type1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Куриное филе с овощами',
                'calories' => 410,
                'photo' => 'https://i.pinimg.com/736x/9e/78/ba/9e78ba551c7c9ee3b7ae1554e9511758.jpg',
                'recipe' => 'Обжарить куриное филе с овощами и специями.',
                'is_premium' => true,
                'protein' => 35,
                'carbohydrates' => 20,
                'fats' => 12,
                'portions' => 2,
                'cookingTime' => 25,
                'weight' => 400,
                'category_id' => $category2,
                'type_id' => $type2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'uuid' => Str::uuid(),
                'name' => 'Смузи с бананом и шпинатом',
                'calories' => 180,
                'photo' => 'https://i.pinimg.com/736x/02/d0/34/02d03418d5d15b7d597fe1bbb9090404.jpg',
                'recipe' => 'Смешать банан, шпинат и йогурт в блендере.',
                'is_premium' => false,
                'protein' => 7,
                'carbohydrates' => 30,
                'fats' => 2,
                'portions' => 1,
                'cookingTime' => 5,
                'weight' => 300,
                'category_id' => $category1,
                'type_id' => $type1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ];

        \Illuminate\Support\Facades\DB::table('dishes')->insert($dishes);

        DB::table('dish_dish_time')->insert([
            ['dish_id' => $dishes[0]['uuid'], 'time_id' => $time1],
            ['dish_id' => $dishes[1]['uuid'], 'time_id' => $time1],
            ['dish_id' => $dishes[2]['uuid'], 'time_id' => $time2],
        ]);

        DB::table('dish_dish_suitable')->insert([
            ['dish_id' => $dishes[0]['uuid'], 'suitable_id' => $suitable1],
            ['dish_id' => $dishes[1]['uuid'], 'suitable_id' => $suitable2],
            ['dish_id' => $dishes[2]['uuid'], 'suitable_id' => $suitable1],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('dishes');
    }
};
