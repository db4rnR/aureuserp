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
        Schema::create('products_product_price_lists', function (Blueprint $table) {
            $table->id();

            $table->integer('sort')->nullable()->comment('Sort Order');
            $table->foreignId('currency_id')->comment('Currency')->constrained('currencies')->restrictOnDelete();
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->comment('Creator')->constrained('users')->nullOnDelete();

            $table->string('name')->comment('Name');
            $table->boolean('is_active')->default(true)->comment('Status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_product_price_lists');
    }
};
