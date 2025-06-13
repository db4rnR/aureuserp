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
        Schema::create('sales_advance_payment_invoices', function (Blueprint $table) {
            $table->id();

            $table->foreignId('currency_id')->nullable()->comment('Currency')->constrained('currencies')->nullOnDelete();
            $table->foreignId('company_id')->nullable()->comment('Company')->constrained('companies')->nullOnDelete();
            $table->foreignId('creator_id')->nullable()->comment('Created By')->constrained('users')->nullOnDelete();
            $table->string('advance_payment_method')->comment('Create Invoice');
            $table->decimal('fixed_amount', 15, 4)->default(0)->nullable()->comment('Fixed Amount');
            $table->decimal('amount', 15, 4)->default(0)->nullable()->comment('Amount');
            $table->boolean('deduct_down_payments')->default(0)->comment('Deduct Down Payments');
            $table->boolean('consolidated_billing')->default(0)->comment('Consolidated Billing');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_advance_payment_invoices');
    }
};
