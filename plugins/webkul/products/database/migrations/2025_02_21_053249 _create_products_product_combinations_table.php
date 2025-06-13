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
        Schema::create('products_product_combinations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('products_products')->cascadeOnDelete();
            $table->foreignId('product_attribute_value_id')->constrained('products_product_attribute_values')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_product_combinations');
    }
};
