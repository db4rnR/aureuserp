<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\Bill;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\MoveState;
use Webkul\Support\Models\Currency;
use Webkul\Security\Models\User;
use Webkul\Partner\Models\Partner;
use Webkul\Account\Models\Journal;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Bill model attributes and relationships')]
function bill_model_attributes_and_relationships()
{
    // Create a test bill
    $bill = Bill::factory()->create([
        'name' => 'Test Bill',
        'ref' => 'BILL/2023/001',
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::IN_INVOICE,
        'invoice_date' => now(),
        'date' => now(),
        'invoice_date_due' => now()->addDays(30),
        'auto_post' => false,
        'amount_untaxed' => 1000.00,
        'amount_tax' => 200.00,
        'amount_total' => 1200.00,
        'amount_residual' => 1200.00,
    ]);

    // Test attributes
    expect($bill->name)->toBe('Test Bill');
    expect($bill->ref)->toBe('BILL/2023/001');
    expect($bill->state)->toBe(MoveState::DRAFT);
    expect($bill->move_type)->toBe(MoveType::IN_INVOICE);
    expect($bill->invoice_date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($bill->date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($bill->invoice_date_due->format('Y-m-d'))->toBe(now()->addDays(30)->format('Y-m-d'));
    expect($bill->auto_post)->toBeFalse();
    expect($bill->amount_untaxed)->toBe(1000.00);
    expect($bill->amount_tax)->toBe(200.00);
    expect($bill->amount_total)->toBe(1200.00);
    expect($bill->amount_residual)->toBe(1200.00);

    // Test relationships
    expect($bill->journal())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($bill->partner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($bill->currency())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($bill->createdBy())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($bill->lines())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Bill model relationships with other models')]
function bill_model_relationships_with_other_models()
{
    // Create related models
    $currency = Currency::factory()->create();
    $user = User::factory()->create();
    $partner = Partner::factory()->create();
    $journal = Journal::factory()->create();

    // Create a bill with relationships
    $bill = Bill::factory()->create([
        'currency_id' => $currency->id,
        'creator_id' => $user->id,
        'partner_id' => $partner->id,
        'journal_id' => $journal->id,
    ]);

    // Test relationships
    expect($bill->currency->id)->toBe($currency->id);
    expect($bill->createdBy->id)->toBe($user->id);
    expect($bill->partner->id)->toBe($partner->id);
    expect($bill->journal->id)->toBe($journal->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Bill model methods')]
function bill_model_methods()
{
    // Create a test bill
    $bill = Bill::factory()->create([
        'move_type' => MoveType::IN_INVOICE,
    ]);

    // Test inherited methods from Move model
    expect($bill->isInvoice(true))->toBeTrue();
    expect($bill->isPurchaseDocument(true))->toBeTrue();
    expect($bill->isOutbound(true))->toBeTrue();
}
