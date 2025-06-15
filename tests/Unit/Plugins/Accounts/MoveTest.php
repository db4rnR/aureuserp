<?php

use PHPUnit\Framework\Attributes\Group;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\MoveLine;
use Webkul\Account\Models\Journal;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Partner\Models\Partner;

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test Move model attributes and relationships')]
function move_model_attributes_and_relationships()
{
    // Create a test move
    $move = Move::factory()->create([
        'name' => 'INV/2023/0001',
        'ref' => 'Customer Invoice',
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::ENTRY,
        'date' => now(),
        'invoice_date' => now(),
        'invoice_date_due' => now()->addDays(30),
        'auto_post' => false,
    ]);

    // Test attributes
    expect($move->name)->toBe('INV/2023/0001');
    expect($move->ref)->toBe('Customer Invoice');
    expect($move->state)->toBe(MoveState::DRAFT);
    expect($move->move_type)->toBe(MoveType::ENTRY);
    expect($move->date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($move->invoice_date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($move->invoice_date_due->format('Y-m-d'))->toBe(now()->addDays(30)->format('Y-m-d'));
    expect($move->auto_post)->toBeFalse();

    // Test relationships
    expect($move->journal())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($move->company())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($move->partner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($move->currency())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($move->lines())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test Move model relationships with other models')]
function move_model_relationships_with_other_models()
{
    // Create related models
    $journal = Journal::factory()->create();
    $company = Company::factory()->create();
    $partner = Partner::factory()->create();
    $currency = Currency::factory()->create();

    // Create a move with relationships
    $move = Move::factory()->create([
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
    ]);

    // Create move lines
    $line1 = MoveLine::factory()->create([
        'move_id' => $move->id,
        'name' => 'Line 1',
        'debit' => 100,
        'credit' => 0,
    ]);

    $line2 = MoveLine::factory()->create([
        'move_id' => $move->id,
        'name' => 'Line 2',
        'debit' => 0,
        'credit' => 100,
    ]);

    // Test relationships
    expect($move->journal->id)->toBe($journal->id);
    expect($move->company->id)->toBe($company->id);
    expect($move->partner->id)->toBe($partner->id);
    expect($move->currency->id)->toBe($currency->id);
    expect($move->lines->count())->toBe(2);
    expect($move->lines->contains($line1))->toBeTrue();
    expect($move->lines->contains($line2))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test Move model type determination methods')]
function move_model_type_determination_methods()
{
    // Create moves with different types
    $entryMove = Move::factory()->create(['move_type' => MoveType::ENTRY]);
    $invoiceMove = Move::factory()->create(['move_type' => MoveType::OUT_INVOICE]);
    $refundMove = Move::factory()->create(['move_type' => MoveType::OUT_REFUND]);
    $receiptMove = Move::factory()->create(['move_type' => MoveType::IN_RECEIPT]);

    // Test isEntry method
    expect($entryMove->isEntry())->toBeTrue();
    expect($invoiceMove->isEntry())->toBeFalse();

    // Test isInvoice method
    expect($invoiceMove->isInvoice(false))->toBeTrue();
    expect($entryMove->isInvoice(false))->toBeFalse();

    // Test isSaleDocument method
    expect($invoiceMove->isSaleDocument(false))->toBeTrue();
    expect($refundMove->isSaleDocument(false))->toBeTrue();
    expect($entryMove->isSaleDocument(false))->toBeFalse();

    // Test isPurchaseDocument method
    expect($receiptMove->isPurchaseDocument(true))->toBeTrue();
    expect($invoiceMove->isPurchaseDocument(false))->toBeFalse();
}
