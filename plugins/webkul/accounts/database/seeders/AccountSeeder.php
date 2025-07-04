<?php

declare(strict_types=1);

namespace Webkul\Account\Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Currency;

class AccountSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('accounts_payment_method_lines')->delete();

        DB::table('accounts_journals')->delete();

        DB::table('accounts_accounts')->delete();

        $user = User::first();

        $currency = Currency::find(1);

        $now = now();

        $accounts = [
            [
                'id' => 1,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Current Assets',
                'code' => '101000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 2,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Stock Valuation',
                'code' => '110100',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 3,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Stock Interim (Received)',
                'code' => '110200',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 4,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Stock Interim (Delivered)',
                'code' => '110300',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 5,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Cost of Production',
                'code' => '110400',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 6,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Work in Progress',
                'code' => '110500',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 7,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_receivable',
                'name' => 'Account Receivable',
                'code' => '121000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 8,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Products to receive',
                'code' => '121100',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 9,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Prepaid Expenses',
                'code' => '128000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 10,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Tax Paid',
                'code' => '131000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 11,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Tax Receivable',
                'code' => '132000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 12,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_prepayments',
                'name' => 'Prepayments',
                'code' => '141000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 13,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_fixed',
                'name' => 'Fixed Asset',
                'code' => '151000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 14,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_non_current',
                'name' => 'Non-current assets',
                'code' => '191000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 15,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Current Liabilities',
                'code' => '201000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 16,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_payable',
                'name' => 'Account Payable',
                'code' => '211000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 17,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Bills to receive',
                'code' => '211100',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 18,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Deferred Revenue',
                'code' => '212000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 19,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Salary Payable',
                'code' => '230000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 20,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Employee Payroll Taxes',
                'code' => '230100',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 21,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Employer Payroll Taxes',
                'code' => '230200',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 22,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Tax Received',
                'code' => '251000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 23,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_current',
                'name' => 'Tax Payable',
                'code' => '252000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 24,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_non_current',
                'name' => 'Non-current Liabilities',
                'code' => '291000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 25,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'equity',
                'name' => 'Capital',
                'code' => '301000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 26,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'equity',
                'name' => 'Dividends',
                'code' => '302000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 27,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'income',
                'name' => 'Product Sales',
                'code' => '400000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 28,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'income',
                'name' => 'Foreign Exchange Gain',
                'code' => '441000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 29,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'income',
                'name' => 'Cash Difference Gain',
                'code' => '442000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 30,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Cash Discount Loss',
                'code' => '443000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 31,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'income_other',
                'name' => 'Other Income',
                'code' => '450000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 32,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense_direct_cost',
                'name' => 'Cost of Goods Sold',
                'code' => '500000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 33,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Expenses',
                'code' => '600000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 34,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Purchase of Equipments',
                'code' => '611000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 35,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Rent',
                'code' => '612000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 36,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Bank Fees',
                'code' => '620000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 37,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Salary Expenses',
                'code' => '630000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 38,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Foreign Exchange Loss',
                'code' => '641000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 39,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Cash Difference Loss',
                'code' => '642000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 40,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'income',
                'name' => 'Cash Discount Gain',
                'code' => '643000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 41,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'RD Expenses',
                'code' => '961000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 42,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'expense',
                'name' => 'Sales Expenses',
                'code' => '962000',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 43,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_receivable',
                'name' => 'Account Receivable (PoS)',
                'code' => '101300',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 44,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'liability_credit_card',
                'name' => 'Credit Card',
                'code' => '201100',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 45,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_cash',
                'name' => 'Bank',
                'code' => '101401',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],

            [
                'id' => 46,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_cash',
                'name' => 'Cash',
                'code' => '101501',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 47,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Bank Suspense Account',
                'code' => '101402',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 48,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Liquidity Transfer',
                'code' => '101701',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 49,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Outstanding Receipts',
                'code' => '101403',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 50,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'asset_current',
                'name' => 'Outstanding Payments',
                'code' => '101404',
                'note' => null,
                'deprecated' => false,
                'reconcile' => true,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'id' => 51,
                'currency_id' => $currency?->id,
                'creator_id' => $user?->id,
                'account_type' => 'equity_unaffected',
                'name' => 'Undistributed Profits/Losses',
                'code' => '999999',
                'note' => null,
                'deprecated' => false,
                'reconcile' => false,
                'non_trade' => false,
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('accounts_accounts')->insert($accounts);
    }
}
