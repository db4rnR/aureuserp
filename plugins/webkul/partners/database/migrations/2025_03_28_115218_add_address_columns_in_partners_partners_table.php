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
        Schema::table('partners_partners', function (Blueprint $table) {
            $table->string('street1')->nullable()->comment('Street 1');
            $table->string('street2')->nullable()->comment('Street 2');
            $table->string('city')->nullable()->comment('City');
            $table->string('zip')->nullable()->comment('Zip');

            $table->foreignId('state_id')
                ->nullable()
                ->constrained('states')
                ->restrictOnDelete();

            $table->foreignId('country_id')
                ->nullable()
                ->constrained('countries')
                ->restrictOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('partners_partners', function (Blueprint $table) {
            $table->dropForeign(['state_id']);
            $table->dropForeign(['country_id']);

            $table->dropColumn([
                'street1',
                'street2',
                'city',
                'zip',
                'state_id',
                'country_id',
            ]);
        });
    }
};
