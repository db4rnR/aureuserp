<?php

declare(strict_types=1);

use Webkul\Account\AccountManager;
use Webkul\Account\Enums\JournalType;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\MoveLine;
use Webkul\Partner\Models\Partner;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test AccountManager state management methods')]
function account_manager_state_management_methods(): void
{
    // Create dependencies
    $journal = Journal::factory()->create(['type' => JournalType::SALE]);
    $company = Company::factory()->create();
    $partner = Partner::factory()->create();
    $currency = Currency::factory()->create();

    // Create a move in draft state
    $move = Move::factory()->create([
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::OUT_INVOICE,
    ]);

    // Create account manager instance
    $accountManager = app(AccountManager::class);

    // Test confirm method
    $confirmedMove = $accountManager->confirm($move);
    expect($confirmedMove->state)->toBe(MoveState::POSTED);

    // Test resetToDraft method
    $draftMove = $accountManager->resetToDraft($confirmedMove);
    expect($draftMove->state)->toBe(MoveState::DRAFT);

    // Test cancel method
    $cancelledMove = $accountManager->cancel($draftMove);
    expect($cancelledMove->state)->toBe(MoveState::CANCEL);
}

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test AccountManager computation methods')]
function account_manager_computation_methods(): void
{
    // Create dependencies
    $journal = Journal::factory()->create(['type' => JournalType::SALE]);
    $company = Company::factory()->create();
    $partner = Partner::factory()->create();
    $currency = Currency::factory()->create();
    $account = Account::factory()->create();

    // Create a move
    $move = Move::factory()->create([
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::OUT_INVOICE,
    ]);

    // Create move lines
    $line1 = MoveLine::factory()->create([
        'move_id' => $move->id,
        'account_id' => $account->id,
        'name' => 'Product Line',
        'price_unit' => 100,
        'quantity' => 2,
        'discount' => 10, // 10% discount
    ]);

    // Create account manager instance
    $accountManager = app(AccountManager::class);

    // Test computeMoveLine method
    $computedLine = $accountManager->computeMoveLine($move, $line1);

    // Verify computations
    // Note: The exact values will depend on the implementation of computeMoveLine
    // These assertions may need to be adjusted based on the actual implementation
    expect($computedLine->price_subtotal)->toBeGreaterThan(0);
    expect($computedLine->price_total)->toBeGreaterThan($computedLine->price_subtotal);

    // Test computeAccountMove method
    $computedMove = $accountManager->computeAccountMove($move);

    // Verify move computations
    expect($computedMove->amount_total)->toBeGreaterThan(0);
    expect($computedMove->amount_untaxed)->toBeGreaterThan(0);
    expect($computedMove->amount_tax)->toBeGreaterThanOrEqual(0);
}

#[Test]
#[Group('integration')]
#[Group('accounts')]
#[Description('Test AccountManager invoice date due computation')]
function account_manager_invoice_date_due_computation(): void
{
    // Create dependencies
    $journal = Journal::factory()->create(['type' => JournalType::SALE]);
    $company = Company::factory()->create();
    $partner = Partner::factory()->create();
    $currency = Currency::factory()->create();

    // Create a move with invoice date
    $move = Move::factory()->create([
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::OUT_INVOICE,
        'invoice_date' => now(),
        'invoice_date_due' => null, // This will be computed
    ]);

    // Create account manager instance
    $accountManager = app(AccountManager::class);

    // Test computeInvoiceDateDue method
    $moveWithDueDate = $accountManager->computeInvoiceDateDue($move);

    // Verify due date computation
    expect($moveWithDueDate->invoice_date_due)->not->toBeNull();
    expect($moveWithDueDate->invoice_date_due)->toBeInstanceOf(Carbon\Carbon::class);

    // The due date should be after or equal to the invoice date
    expect($moveWithDueDate->invoice_date_due->timestamp)->toBeGreaterThanOrEqual($moveWithDueDate->invoice_date->timestamp);
}
