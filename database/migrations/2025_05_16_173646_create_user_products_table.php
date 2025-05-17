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
        Schema::create('user_products', function (Blueprint $table) {
            $table->uuid()->primary();

            $table->foreignId('users_id')->nullable()->index()->constrained('users')->onDelete('cascade');

            $table->uuid('product_id');
            $table->foreign('product_id')->references('uuid')->on('products')->onDelete('cascade');

            $table->float('count');
            $table->boolean('status')->default(false);
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_products');
    }
};
