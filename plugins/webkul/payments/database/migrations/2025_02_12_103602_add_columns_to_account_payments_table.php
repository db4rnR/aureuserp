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
        Schema::table('accounts_account_payments', function (Blueprint $table) {
            $table->foreignId('payment_token_id')->nullable()->comment('Payment Token')->constrained('payments_payment_tokens')->nullOnDelete();
            $table->foreignId('payment_transaction_id')->nullable()->comment('Payment Transaction')->constrained('payments_payment_transactions')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('accounts_account_payments', function (Blueprint $table) {
            $table->dropForeign(['payment_token_id']);
            $table->dropForeign(['payment_transaction_id']);
            $table->dropColumn('payment_token_id');
            $table->dropColumn('payment_transaction_id');
        });
    }
};
