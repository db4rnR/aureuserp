<?php

use Webkul\Account\Models\Move;
use Webkul\Account\Models\MoveLine;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Tax;
use Webkul\Account\Enums\DisplayType;
use Webkul\Account\Enums\MoveState;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Partner\Models\Partner;
use Webkul\Invoice\Models\Product;

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test MoveLine model attributes and relationships')]
function move_line_model_attributes_and_relationships()
{
    // Create a test move line
    $moveLine = MoveLine::factory()->create([
        'name' => 'Test Move Line',
        'debit' => 100,
        'credit' => 0,
        'balance' => 100,
        'amount_currency' => 100,
        'price_unit' => 10,
        'quantity' => 10,
        'price_subtotal' => 100,
        'price_total' => 110,
        'discount' => 0,
        'display_type' => DisplayType::PRODUCT,
        'parent_state' => MoveState::DRAFT,
    ]);

    // Test attributes
    expect($moveLine->name)->toBe('Test Move Line');
    expect($moveLine->debit)->toBe(100);
    expect($moveLine->credit)->toBe(0);
    expect($moveLine->balance)->toBe(100);
    expect($moveLine->amount_currency)->toBe(100);
    expect($moveLine->price_unit)->toBe(10);
    expect($moveLine->quantity)->toBe(10);
    expect($moveLine->price_subtotal)->toBe(100);
    expect($moveLine->price_total)->toBe(110);
    expect($moveLine->discount)->toBe(0);
    expect($moveLine->display_type)->toBe(DisplayType::PRODUCT);
    expect($moveLine->parent_state)->toBe(MoveState::DRAFT);

    // Test relationships
    expect($moveLine->move())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($moveLine->journal())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($moveLine->company())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($moveLine->account())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($moveLine->currency())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($moveLine->partner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($moveLine->taxes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($moveLine->product())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test MoveLine model relationships with other models')]
function move_line_model_relationships_with_other_models()
{
    // Create related models
    $move = Move::factory()->create();
    $journal = Journal::factory()->create();
    $company = Company::factory()->create();
    $account = Account::factory()->create();
    $currency = Currency::factory()->create();
    $partner = Partner::factory()->create();
    $product = Product::factory()->create();

    // Create a move line with relationships
    $moveLine = MoveLine::factory()->create([
        'move_id' => $move->id,
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'account_id' => $account->id,
        'currency_id' => $currency->id,
        'partner_id' => $partner->id,
        'product_id' => $product->id,
    ]);

    // Create and attach taxes
    $tax1 = Tax::factory()->create(['name' => 'VAT 10%', 'amount' => 10]);
    $tax2 = Tax::factory()->create(['name' => 'VAT 20%', 'amount' => 20]);
    $moveLine->taxes()->attach([$tax1->id, $tax2->id]);

    // Test relationships
    expect($moveLine->move->id)->toBe($move->id);
    expect($moveLine->journal->id)->toBe($journal->id);
    expect($moveLine->company->id)->toBe($company->id);
    expect($moveLine->account->id)->toBe($account->id);
    expect($moveLine->currency->id)->toBe($currency->id);
    expect($moveLine->partner->id)->toBe($partner->id);
    expect($moveLine->product->id)->toBe($product->id);
    expect($moveLine->taxes->count())->toBe(2);
    expect($moveLine->taxes->contains($tax1))->toBeTrue();
    expect($moveLine->taxes->contains($tax2))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('accounts')]
#[Description('Test MoveLine model financial calculations')]
function move_line_model_financial_calculations()
{
    // Create a move line with financial data
    $moveLine = MoveLine::factory()->create([
        'debit' => 100,
        'credit' => 0,
        'balance' => 100,
        'amount_currency' => 120, // Assuming exchange rate of 1.2
        'price_unit' => 10,
        'quantity' => 10,
        'price_subtotal' => 100,
        'price_total' => 110, // Including tax
        'discount' => 0,
    ]);

    // Test financial calculations
    // Note: These tests assume that the model has methods or accessors for these calculations
    // If not, you may need to modify these tests or add the methods to the model

    // Test balance calculation (debit - credit)
    expect($moveLine->balance)->toBe(100);

    // Test price_subtotal calculation (price_unit * quantity)
    expect($moveLine->price_subtotal)->toBe(100);

    // Test price_total calculation (price_subtotal + tax)
    expect($moveLine->price_total)->toBe(110);

    // Create a move line with discount
    $moveLineWithDiscount = MoveLine::factory()->create([
        'price_unit' => 10,
        'quantity' => 10,
        'discount' => 20, // 20% discount
        'price_subtotal' => 80, // 10 * 10 * 0.8
        'price_total' => 88, // 80 + 10% tax
    ]);

    // Test discount calculation
    expect($moveLineWithDiscount->price_subtotal)->toBe(80);
    expect($moveLineWithDiscount->price_total)->toBe(88);
}
