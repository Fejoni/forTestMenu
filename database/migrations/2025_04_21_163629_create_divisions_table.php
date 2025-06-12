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
        Schema::create('product_divisions', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
//            $table->string('image');
            $table->timestamps();
        });

        \App\Models\Product\ProductDivision::query()->create(['name' => 'test']);
        \App\Models\Product\ProductDivision::query()->create(['name' => 'test2']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('divisions');
    }
};
