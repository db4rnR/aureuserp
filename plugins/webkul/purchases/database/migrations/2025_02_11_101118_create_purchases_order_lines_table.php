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
        Schema::create('purchases_order_lines', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->string('state')->nullable();
            $table->integer('sort')->nullable();
            $table->string('qty_received_method')->nullable();
            $table->string('display_type')->nullable();
            $table->decimal('product_qty', 15, 2);
            $table->double('product_uom_qty')->nullable();
            $table->double('product_packaging_qty')->nullable();
            $table->double('price_tax')->nullable();
            $table->decimal('discount', 15, 2)->default(0);
            $table->decimal('price_unit', 15, 2)->default(0);
            $table->decimal('price_subtotal', 15, 2)->default(0);
            $table->decimal('price_total', 15, 2)->default(0);
            $table->decimal('qty_invoiced', 15, 2)->default(0);
            $table->decimal('qty_received', 15, 2)->default(0);
            $table->decimal('qty_received_manual', 15, 2)->default(0);
            $table->decimal('qty_to_invoice', 15, 2)->default(0);
            $table->boolean('is_downpayment')->default(0);
            $table->timestamp('planned_at')->nullable();
            $table->string('product_description_variants')->nullable();
            $table->boolean('propagate_cancel')->nullable();
            $table->decimal('price_total_cc', 15, 2)->default(0);

            // Indexes
            $table->index('planned_at');
            $table->index('product_id');
            $table->index('order_id');
            $table->index('partner_id');

            $table->foreignId('uom_id')
                ->nullable()
                ->constrained('unit_of_measures')
                ->nullOnDelete();

            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products_products')
                ->nullOnDelete();

            $table->foreignId('product_packaging_id')
                ->nullable()
                ->constrained('products_packagings')
                ->nullOnDelete();

            $table->foreignId('order_id')
                ->constrained('purchases_orders')
                ->cascadeOnDelete();

            $table->foreignId('partner_id')
                ->nullable()
                ->constrained('partners_partners')
                ->nullOnDelete();

            $table->foreignId('currency_id')
                ->nullable()
                ->constrained('currencies')
                ->nullOnDelete();

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
        Schema::dropIfExists('purchases_order_lines');
    }
};
