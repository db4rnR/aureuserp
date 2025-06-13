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
        Schema::create('sales_advance_payment_invoice_order_sales', function (Blueprint $table) {
            $table->unsignedBigInteger('advance_payment_invoice_id');
            $table->unsignedBigInteger('order_id');

            $table->foreign('advance_payment_invoice_id', 'sapios_api_fk')
                ->references('id')
                ->on('sales_advance_payment_invoices')
                ->cascadeOnDelete();

            $table->foreign('order_id', 'sapios_order_fk')
                ->references('id')
                ->on('sales_orders')
                ->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sales_advance_payment_invoice_order_sales');
    }
};
