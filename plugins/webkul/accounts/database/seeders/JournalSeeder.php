<?php

declare(strict_types=1);

namespace Webkul\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

class JournalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts_journals')->delete();

        $user = User::first();

        $company = Company::first();

        $journals = [
            [
                'id' => 1,
                'default_account_id' => 27,
                'suspense_account_id' => null,
                'sort' => 5,
                'currency_id' => null,
                'company_id' => $company?->id,
                'profit_account_id' => null,
                'loss_account_id' => null,
                'bank_account_id' => null,
                'creator_id' => $user?->id,
                'color' => 11,
                'access_token' => null,
                'code' => 'INV',
                'type' => 'sale',
                'invoice_reference_type' => 'invoice',
                'invoice_reference_model' => 'aureus',
                'bank_statements_source' => null,
                'name' => 'Customer Invoices',
                'order_override_regex' => null,
                'auto_check_on_post' => true,
                'restrict_mode_hash_table' => false,
                'refund_order' => true,
                'payment_order' => false,
                'show_on_dashboard' => true,
            ],
            [
                'id' => 2,
                'default_account_id' => 33,
                'suspense_account_id' => null,
                'sort' => 6,
                'currency_id' => null,
                'company_id' => $company?->id,
                'profit_account_id' => null,
                'loss_account_id' => null,
                'bank_account_id' => null,
                'creator_id' => $user?->id,
                'color' => 11,
                'access_token' => null,
                'code' => 'BILL',
                'type' => 'purchase',
                'invoice_reference_type' => 'invoice',
                'invoice_reference_model' => 'aureus',
                'bank_statements_source' => null,
                'name' => 'Vendor Bills',
                'order_override_regex' => null,
                'auto_check_on_post' => true,
                'restrict_mode_hash_table' => false,
                'refund_order' => true,
                'payment_order' => false,
                'show_on_dashboard' => true,
            ],
            [
                'id' => 3,
                'default_account_id' => null,
                'suspense_account_id' => null,
                'sort' => 9,
                'currency_id' => null,
                'company_id' => $company?->id,
                'profit_account_id' => null,
                'loss_account_id' => null,
                'bank_account_id' => null,
                'creator_id' => $user?->id,
                'color' => 0,
                'access_token' => null,
                'code' => 'MISC',
                'type' => 'general',
                'invoice_reference_type' => 'invoice',
                'invoice_reference_model' => 'aureus',
                'bank_statements_source' => null,
                'name' => 'Miscellaneous Operations',
                'order_override_regex' => null,
                'auto_check_on_post' => true,
                'restrict_mode_hash_table' => false,
                'refund_order' => false,
                'payment_order' => false,
                'show_on_dashboard' => false,
            ],
            [
                'id' => 4,
                'default_account_id' => null,
                'suspense_account_id' => null,
                'sort' => 10,
                'currency_id' => null,
                'company_id' => $company?->id,
                'profit_account_id' => null,
                'loss_account_id' => null,
                'bank_account_id' => null,
                'creator_id' => $user?->id,
                'color' => 0,
                'access_token' => null,
                'code' => 'EXCH',
                'type' => 'general',
                'invoice_reference_type' => 'invoice',
                'invoice_reference_model' => 'aureus',
                'bank_statements_source' => null,
                'name' => 'Exchange Difference',
                'order_override_regex' => null,
                'auto_check_on_post' => true,
                'restrict_mode_hash_table' => false,
                'refund_order' => false,
                'payment_order' => false,
                'show_on_dashboard' => false,
            ],
            [
                'id' => 5,
                'default_account_id' => 46,
                'suspense_account_id' => 47,
                'sort' => null,
                'currency_id' => null,
                'company_id' => $company?->id,
                'profit_account_id' => null,
                'loss_account_id' => null,
                'bank_account_id' => null,
                'creator_id' => $user?->id,
                'color' => null,
                'access_token' => null,
                'code' => 'BANK',
                'type' => 'bank',
                'invoice_reference_type' => 'invoice',
                'invoice_reference_model' => 'aureus',
                'bank_statements_source' => null,
                'name' => 'Bank Transactions',
                'order_override_regex' => null,
                'auto_check_on_post' => true,
                'restrict_mode_hash_table' => false,
                'refund_order' => false,
                'payment_order' => false,
                'show_on_dashboard' => false,
            ],

            [
                'id' => 6,
                'default_account_id' => null,
                'suspense_account_id' => null,
                'sort' => null,
                'currency_id' => null,
                'company_id' => $company?->id,
                'profit_account_id' => null,
                'loss_account_id' => null,
                'bank_account_id' => null,
                'creator_id' => $user?->id,
                'color' => null,
                'access_token' => null,
                'code' => 'CASH',
                'type' => 'cash',
                'invoice_reference_type' => 'invoice',
                'invoice_reference_model' => 'aureus',
                'bank_statements_source' => null,
                'name' => 'Cash Transactions',
                'order_override_regex' => null,
                'auto_check_on_post' => true,
                'restrict_mode_hash_table' => false,
                'refund_order' => false,
                'payment_order' => false,
                'show_on_dashboard' => false,
            ],
        ];

        DB::table('accounts_journals')->insert($journals);
    }
}
