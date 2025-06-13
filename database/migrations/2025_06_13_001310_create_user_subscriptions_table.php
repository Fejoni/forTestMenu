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
        Schema::create('user_subscriptions', function (Blueprint $table) {
            $table->comment('Покупки подписок пользователями');

            $table->uuid()->primary();
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete()
                ->cascadeOnUpdate();

            $table->string('payment_id')
                ->nullable()
                ->comment('Идентификатор платежа');

            $table->unsignedInteger('amount')
                ->comment('Сумма платежа');

            $table->string('currency', 10)
                ->default('RUB')
                ->comment('Валюта платежа');

            $table->string('receipt_url')
                ->nullable()
                ->comment('Ссылка на чек');

            $table->timestamp('valid_from')
                ->nullable()
                ->comment('Подписка действует с');

            $table->timestamp('valid_until')
                ->nullable()
                ->comment('Подписка действует до');

            $table->string('status')
                ->default('pending')
                ->comment('Статус оплаты');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_subscriptions');
    }
};
