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
        Schema::create('products_product_suppliers', function (Blueprint $table) {
            $table->id();
            $table->integer('sort')->nullable();
            $table->integer('delay')->default(0);
            $table->string('product_name')->nullable();
            $table->string('product_code')->nullable();
            $table->date('starts_at')->nullable();
            $table->date('ends_at')->nullable();
            $table->decimal('min_qty', 12, 4)->default(0);
            $table->decimal('price', 15, 4)->default(0);
            $table->decimal('discount', 15, 4)->default(0);

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products_products')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->constrained('partners_partners')
                ->cascadeOnDelete();

            $table->foreignId('currency_id')
                ->constrained('currencies')
                ->restrictOnDelete();

            $table->foreignId('company_id')
                ->nullable()
                ->constrained('companies')
                ->nullOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products_product_suppliers');
    }
};
