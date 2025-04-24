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
        Schema::create('product_categories', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->string('name');
            $table->string('image');
            $table->timestamps();
        });

        \App\Models\Product\ProductCategory::query()
            ->create([
                'name' => 'test',
                'image' => 'https://images.chesscomfiles.com/uploads/v1/user/434189707.298bc8ef.160x160o.4011d522d108.jpg'
            ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
