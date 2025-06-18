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
        Schema::create('blogs_post_tags', function (Blueprint $table): void {
            $table->foreignId('tag_id')
                ->constrained('blogs_tags')
                ->cascadeOnDelete();

            $table->foreignId('post_id')
                ->constrained('blogs_posts')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blogs_post_tags');
    }
};
