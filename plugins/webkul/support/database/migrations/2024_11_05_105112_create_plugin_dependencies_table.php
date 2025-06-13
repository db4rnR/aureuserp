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
        Schema::create('plugin_dependencies', function (Blueprint $table) {
            $table->foreignId('plugin_id')
                ->constrained('plugins')
                ->cascadeOnDelete();

            $table->foreignId('dependency_id')
                ->constrained('plugins')
                ->cascadeOnDelete();

            $table->unique(['plugin_id', 'dependency_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('plugin_dependencies');
    }
};
