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
        Schema::create('inventories_move_destinations', function (Blueprint $table) {
            $table->foreignId('origin_move_id')
                ->constrained('inventories_moves')
                ->cascadeOnDelete();

            $table->foreignId('destination_move_id')
                ->constrained('inventories_moves')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventories_move_destinations');
    }
};
