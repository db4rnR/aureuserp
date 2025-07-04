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
        Schema::table('purchases_orders', function (Blueprint $table): void {
            if (Schema::hasTable('inventories_operation_types') && ! Schema::hasColumn('purchases_orders', 'operation_type_id')) {
                $table->foreignId('operation_type_id')
                    ->nullable()
                    ->constrained('inventories_operation_types')
                    ->restrictOnDelete();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('purchases_orders', function (Blueprint $table): void {
            if (Schema::hasColumn('purchases_orders', 'operation_type_id')) {
                $table->dropForeign(['operation_type_id']);
                $table->dropColumn('operation_type_id');
            }
        });
    }
};
