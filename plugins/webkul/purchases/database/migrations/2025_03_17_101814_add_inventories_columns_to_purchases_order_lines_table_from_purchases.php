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
        Schema::table('purchases_order_lines', function (Blueprint $table): void {
            if (Schema::hasTable('inventories_locations') && ! Schema::hasColumn('purchases_order_lines', 'final_location_id')) {
                $table->foreignId('final_location_id')
                    ->nullable()
                    ->constrained('inventories_locations')
                    ->restrictOnDelete();
            }

            if (Schema::hasTable('inventories_order_points') && ! Schema::hasColumn('purchases_order_lines', 'order_point_id')) {
                $table->foreignId('order_point_id')
                    ->nullable()
                    ->constrained('inventories_order_points')
                    ->restrictOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases_order_lines', function (Blueprint $table): void {
            if (Schema::hasColumn('purchases_order_lines', 'final_location_id')) {
                $table->dropForeign(['final_location_id']);
                $table->dropColumn('final_location_id');
            }

            if (Schema::hasColumn('purchases_order_lines', 'order_point_id')) {
                $table->dropForeign(['order_point_id']);
                $table->dropColumn('order_point_id');
            }
        });
    }
};
