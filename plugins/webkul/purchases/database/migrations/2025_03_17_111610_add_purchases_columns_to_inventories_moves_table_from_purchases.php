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
        if (Schema::hasTable('inventories_moves')) {
            Schema::table('inventories_moves', function (Blueprint $table) {
                if (! Schema::hasColumn('inventories_moves', 'purchase_order_line_id')) {
                    $table->foreignId('purchase_order_line_id')
                        ->nullable()
                        ->constrained('purchases_order_lines')
                        ->restrictOnDelete();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('inventories_moves')) {
            Schema::table('inventories_moves', function (Blueprint $table) {
                if (Schema::hasColumn('inventories_moves', 'purchase_order_line_id')) {
                    $table->dropForeign(['purchase_order_line_id']);
                    $table->dropColumn('purchase_order_line_id');
                }
            });
        }
    }
};
