<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create(config('commentions.tables.comment_reactions', 'comment_reactions'), function (Blueprint $table): void {
            $table->id();
            $table->foreignId('comment_id')->constrained(config('commentions.table_name'))->cascadeOnDelete();
            $table->morphs('reactor');

            if (config('database.default') === 'mysql') {
                $table->string('reaction', 50)->collation('utf8mb4_bin');
            } else {
                $table->string('reaction', 50);
            }

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists(config('commentions.tables.comment_reactions', 'comment_reactions'));
    }
};
