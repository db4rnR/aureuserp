<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Move as BaseMove;
use Webkul\Invoice\Models\CreditNote;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test CreditNote model inheritance and properties')]
function credit_note_model_inheritance_and_properties(): void
{
    // Create a test credit note
    $creditNote = CreditNote::factory()->create([
        'name' => 'Test Credit Note',
        'ref' => 'RINV/2023/001',
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::OUT_REFUND,
        'invoice_date' => now(),
        'date' => now(),
        'invoice_date_due' => now()->addDays(30),
        'auto_post' => false,
        'amount_untaxed' => 1000.00,
        'amount_tax' => 200.00,
        'amount_total' => 1200.00,
        'amount_residual' => 1200.00,
    ]);

    // Test that it's an instance of both the Invoice CreditNote and the base Account Move
    expect($creditNote)->toBeInstanceOf(CreditNote::class);
    expect($creditNote)->toBeInstanceOf(BaseMove::class);

    // Test attributes
    expect($creditNote->name)->toBe('Test Credit Note');
    expect($creditNote->ref)->toBe('RINV/2023/001');
    expect($creditNote->state)->toBe(MoveState::DRAFT);
    expect($creditNote->move_type)->toBe(MoveType::OUT_REFUND);
    expect($creditNote->invoice_date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($creditNote->date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($creditNote->invoice_date_due->format('Y-m-d'))->toBe(now()->addDays(30)->format('Y-m-d'));
    expect($creditNote->auto_post)->toBeFalse();
    expect($creditNote->amount_untaxed)->toBe(1000.00);
    expect($creditNote->amount_tax)->toBe(200.00);
    expect($creditNote->amount_total)->toBe(1200.00);
    expect($creditNote->amount_residual)->toBe(1200.00);

    // Test relationships inherited from the base class
    expect($creditNote->journal())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($creditNote->partner())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($creditNote->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($creditNote->createdBy())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($creditNote->lines())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test CreditNote model relationships with other models')]
function credit_note_model_relationships_with_other_models(): void
{
    // Create related models
    $currency = Currency::factory()->create();
    $user = User::factory()->create();
    $partner = Partner::factory()->create();
    $journal = Journal::factory()->create();

    // Create a credit note with relationships
    $creditNote = CreditNote::factory()->create([
        'currency_id' => $currency->id,
        'creator_id' => $user->id,
        'partner_id' => $partner->id,
        'journal_id' => $journal->id,
    ]);

    // Test relationships
    expect($creditNote->currency->id)->toBe($currency->id);
    expect($creditNote->createdBy->id)->toBe($user->id);
    expect($creditNote->partner->id)->toBe($partner->id);
    expect($creditNote->journal->id)->toBe($journal->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test CreditNote model methods')]
function credit_note_model_methods(): void
{
    // Create a test credit note
    $creditNote = CreditNote::factory()->create([
        'move_type' => MoveType::OUT_REFUND,
    ]);

    // Test inherited methods from Move model
    expect($creditNote->isInvoice(true))->toBeTrue();
    expect($creditNote->isSaleDocument(true))->toBeTrue();
    expect($creditNote->isInbound(true))->toBeFalse();
    expect($creditNote->isOutbound(true))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test CreditNote model sequence prefix generation')]
function credit_note_model_sequence_prefix_generation(): void
{
    // Create a test credit note
    $creditNote = CreditNote::factory()->create([
        'move_type' => MoveType::OUT_REFUND,
    ]);

    // Call the updateSequencePrefix method
    $creditNote->updateSequencePrefix();

    // Test that the sequence prefix was generated correctly
    $expectedPrefix = 'RINV/'.date('Y').'/'.date('m');
    expect($creditNote->sequence_prefix)->toBe($expectedPrefix);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test CreditNote model traits')]
function credit_note_model_traits(): void
{
    // Create a test credit note
    $creditNote = CreditNote::factory()->create();

    // Test that the model uses the expected traits
    expect($creditNote)->toBeInstanceOf(Webkul\Chatter\Traits\HasChatter::class);
    expect($creditNote)->toBeInstanceOf(Webkul\Field\Traits\HasCustomFields::class);
    expect($creditNote)->toBeInstanceOf(Webkul\Chatter\Traits\HasLogActivity::class);
    expect($creditNote)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);
}
