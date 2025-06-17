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
        Schema::create('inventories_order_points', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('trigger');
            $table->date('snoozed_until')->nullable();
            $table->decimal('product_min_qty', 15, 4)->default(0);
            $table->decimal('product_max_qty', 15, 4)->default(0);
            $table->decimal('qty_multiple', 15, 4)->default(0);
            $table->decimal('qty_to_order_manual', 15, 4)->nullable()->default(0);

            $table->foreignId('product_id')
                ->constrained('products_products')
                ->cascadeOnDelete();

            $table->foreignId('product_category_id')
                ->nullable()
                ->constrained('products_categories')
                ->nullOnDelete();

            $table->foreignId('warehouse_id')
                ->constrained('inventories_warehouses')
                ->cascadeOnDelete();

            $table->foreignId('location_id')
                ->constrained('inventories_locations')
                ->cascadeOnDelete();

            $table->foreignId('route_id')
                ->nullable()
                ->constrained('inventories_routes')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_order_points');
    }
};
