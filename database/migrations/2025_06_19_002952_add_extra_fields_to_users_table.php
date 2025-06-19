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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('weight')->nullable()->comment('Вес, кг');
            $table->integer('height')->nullable()->comment('Рост, см');
            $table->integer('age')->nullable()->comment('Возраст, лет');
            $table->string('gender')->nullable()->comment('Пол пользователя (male, female)');
            $table->boolean('start_setting_page_view')->default(false)->comment('Завершена стартовая настройка');
            $table->string('activity')->nullable()->comment('Уровень активности (low, medium, high, hard)');
            $table->string('user_task')->nullable()->comment('Цель пользователя (lose, save, dial)');
            $table->boolean('check_privacy')->default(false)->comment('Принята политика конфиденциальности');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'weight',
                'height',
                'age',
                'gender',
                'start_setting_page_view',
                'activity',
                'user_task',
                'check_privacy',
            ]);
        });
    }
};
