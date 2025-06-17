<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\Refund;
use Webkul\Account\Models\Move as BaseMove;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\MoveState;
use Webkul\Support\Models\Currency;
use Webkul\Security\Models\User;
use Webkul\Partner\Models\Partner;
use Webkul\Account\Models\Journal;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Refund model inheritance and properties')]
function refund_model_inheritance_and_properties()
{
    // Create a test refund
    $refund = Refund::factory()->create([
        'name' => 'Test Refund',
        'ref' => 'RBILL/2023/001',
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::IN_REFUND,
        'invoice_date' => now(),
        'date' => now(),
        'invoice_date_due' => now()->addDays(30),
        'auto_post' => false,
        'amount_untaxed' => 1000.00,
        'amount_tax' => 200.00,
        'amount_total' => 1200.00,
        'amount_residual' => 1200.00,
    ]);

    // Test that it's an instance of both the Invoice Refund and the base Account Move
    expect($refund)->toBeInstanceOf(Refund::class);
    expect($refund)->toBeInstanceOf(BaseMove::class);

    // Test attributes
    expect($refund->name)->toBe('Test Refund');
    expect($refund->ref)->toBe('RBILL/2023/001');
    expect($refund->state)->toBe(MoveState::DRAFT);
    expect($refund->move_type)->toBe(MoveType::IN_REFUND);
    expect($refund->invoice_date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($refund->date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($refund->invoice_date_due->format('Y-m-d'))->toBe(now()->addDays(30)->format('Y-m-d'));
    expect($refund->auto_post)->toBeFalse();
    expect($refund->amount_untaxed)->toBe(1000.00);
    expect($refund->amount_tax)->toBe(200.00);
    expect($refund->amount_total)->toBe(1200.00);
    expect($refund->amount_residual)->toBe(1200.00);

    // Test relationships inherited from the base class
    expect($refund->journal())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($refund->partner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($refund->currency())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($refund->createdBy())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($refund->lines())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Refund model relationships with other models')]
function refund_model_relationships_with_other_models()
{
    // Create related models
    $currency = Currency::factory()->create();
    $user = User::factory()->create();
    $partner = Partner::factory()->create();
    $journal = Journal::factory()->create();

    // Create a refund with relationships
    $refund = Refund::factory()->create([
        'currency_id' => $currency->id,
        'creator_id' => $user->id,
        'partner_id' => $partner->id,
        'journal_id' => $journal->id,
    ]);

    // Test relationships
    expect($refund->currency->id)->toBe($currency->id);
    expect($refund->createdBy->id)->toBe($user->id);
    expect($refund->partner->id)->toBe($partner->id);
    expect($refund->journal->id)->toBe($journal->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Refund model methods')]
function refund_model_methods()
{
    // Create a test refund
    $refund = Refund::factory()->create([
        'move_type' => MoveType::IN_REFUND,
    ]);

    // Test inherited methods from Move model
    expect($refund->isInvoice(true))->toBeTrue();
    expect($refund->isPurchaseDocument(true))->toBeTrue();
    expect($refund->isInbound(true))->toBeFalse();
    expect($refund->isOutbound(true))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Refund model sequence prefix generation')]
function refund_model_sequence_prefix_generation()
{
    // Create a test refund
    $refund = Refund::factory()->create([
        'move_type' => MoveType::IN_REFUND,
    ]);

    // Call the updateSequencePrefix method
    $refund->updateSequencePrefix();

    // Test that the sequence prefix was generated correctly
    $expectedPrefix = 'RBILL/' . date('Y') . '/' . date('m');
    expect($refund->sequence_prefix)->toBe($expectedPrefix);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Refund model traits')]
function refund_model_traits()
{
    // Create a test refund
    $refund = Refund::factory()->create();

    // Test that the model uses the expected traits
    expect($refund)->toBeInstanceOf(\Webkul\Chatter\Traits\HasChatter::class);
    expect($refund)->toBeInstanceOf(\Webkul\Field\Traits\HasCustomFields::class);
    expect($refund)->toBeInstanceOf(\Webkul\Chatter\Traits\HasLogActivity::class);
    expect($refund)->toBeInstanceOf(\Spatie\EloquentSortable\Sortable::class);
}
