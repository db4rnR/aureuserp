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
        Schema::create('purchases_orders', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->string('priority')->default(0);
            $table->string('origin')->nullable();
            $table->string('partner_reference')->nullable();
            $table->string('state')->nullable();
            $table->string('invoice_status')->default('no');
            $table->string('receipt_status')->default('no');
            $table->decimal('untaxed_amount', 15, 4)->default(0);
            $table->decimal('tax_amount', 15, 4)->default(0);
            $table->decimal('total_amount', 15, 4)->default(0);
            $table->decimal('total_cc_amount', 15, 4)->default(0);
            $table->decimal('currency_rate', 15, 6)->default(0);
            $table->integer('invoice_count')->default(0);
            $table->datetime('ordered_at');
            $table->datetime('approved_at')->nullable();
            $table->datetime('planned_at')->nullable();
            $table->datetime('calendar_start_at')->nullable();
            $table->datetime('effective_date')->nullable();
            $table->string('incoterm_location')->nullable();
            $table->boolean('mail_reminder_confirmed')->nullable()->default(0);
            $table->boolean('mail_reception_confirmed')->nullable()->default(0);
            $table->boolean('mail_reception_declined')->nullable()->default(0);
            $table->boolean('report_grids')->nullable()->default(0);

            $table->foreignId('requisition_id')
                ->nullable()
                ->constrained('purchases_requisitions')
                ->nullOnDelete();

            $table->foreignId('purchases_group_id')
                ->nullable()
                ->constrained('purchases_order_groups')
                ->nullOnDelete();

            $table->foreignId('partner_id')
                ->constrained('partners_partners')
                ->restrictOnDelete();

            $table->foreignId('currency_id')
                ->constrained('currencies')
                ->restrictOnDelete();

            $table->foreignId('fiscal_position_id')
                ->nullable()
                ->constrained('accounts_fiscal_positions')
                ->nullOnDelete();

            $table->foreignId('payment_term_id')
                ->nullable()
                ->constrained('accounts_payment_terms')
                ->nullOnDelete();

            $table->foreignId('incoterm_id')
                ->nullable()
                ->constrained('accounts_incoterms')
                ->nullOnDelete();

            $table->foreignId('user_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->foreignId('company_id')
                ->constrained('companies')
                ->restrictOnDelete();

            $table->foreignId('creator_id')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            // Indexes
            $table->index('name');
            $table->index('state');
            $table->index('priority');
            $table->index('ordered_at');
            $table->index('approved_at');
            $table->index('planned_at');
            $table->index('user_id');
            $table->index('company_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchases_orders');
    }
};
