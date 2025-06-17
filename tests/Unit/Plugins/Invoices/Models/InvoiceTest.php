<?php
/**
 * Invoice Model Unit Tests
 *
 * This file contains unit tests for the Invoice model in the Invoices plugin.
 * It tests the model's attributes, relationships, and methods to ensure they
 * function correctly.
 *
 * Test Categories:
 * - unit: These are unit tests focusing on a single component
 * - invoices: These tests are specific to the Invoices plugin
 * - database: These tests involve database operations
 * - validation: These tests verify data validation
 * - billing: These tests relate to billing functionality
 * - workflow: These tests verify workflow operations
 *
 * Requirements:
 * - Database connection configured for testing
 * - Factories for Invoice, Currency, User, Partner, and Journal models
 * - Proper configuration of model relationships
 *
 * @package Tests\Unit\Plugins\Invoices\Models
 */

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\CoversClass;
use App\Tests\Attributes\PluginTest;
use Webkul\Invoice\Models\Invoice;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\MoveState;
use Webkul\Support\Models\Currency;
use Webkul\Security\Models\User;
use Webkul\Partner\Models\Partner;
use Webkul\Account\Models\Journal;

/**
 * Test Invoice model attributes and relationships
 *
 * This test verifies that the Invoice model's attributes are correctly set
 * and that its relationships with other models are properly defined.
 *
 * Test data:
 * - Creates a new Invoice with specific attributes using the factory
 * - Tests both scalar attributes (name, ref, amounts) and enum attributes (state, move_type)
 * - Tests date attributes to ensure they're properly cast to Carbon instances
 * - Tests boolean attributes (auto_post)
 *
 * Assumptions:
 * - The Invoice factory is properly configured
 * - The Invoice model has the expected attributes and relationships
 * - The Invoice model correctly casts date fields to Carbon instances
 *
 * Expected behavior:
 * - All attributes should match the values set during creation
 * - All relationships should be of the expected type
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('validation')]
#[Group('billing')]
#[PluginTest('Invoices')]
#[CoversClass(Invoice::class)]
#[Description('Test Invoice model attributes and relationships')]
function invoice_model_attributes_and_relationships()
{
    // Create a test invoice
    $invoice = Invoice::factory()->create([
        'name' => 'Test Invoice',
        'ref' => 'INV/2023/001',
        'state' => MoveState::DRAFT,
        'move_type' => MoveType::OUT_INVOICE,
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
    expect($invoice->name)->toBe('Test Invoice');
    expect($invoice->ref)->toBe('INV/2023/001');
    expect($invoice->state)->toBe(MoveState::DRAFT);
    expect($invoice->move_type)->toBe(MoveType::OUT_INVOICE);
    expect($invoice->invoice_date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($invoice->date->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($invoice->invoice_date_due->format('Y-m-d'))->toBe(now()->addDays(30)->format('Y-m-d'));
    expect($invoice->auto_post)->toBeFalse();
    expect($invoice->amount_untaxed)->toBe(1000.00);
    expect($invoice->amount_tax)->toBe(200.00);
    expect($invoice->amount_total)->toBe(1200.00);
    expect($invoice->amount_residual)->toBe(1200.00);

    // Test relationships
    expect($invoice->journal())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($invoice->partner())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($invoice->currency())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($invoice->createdBy())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($invoice->lines())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\HasMany::class);
}

/**
 * Test Invoice model relationships with other models
 *
 * This test verifies that the Invoice model's relationships with other models
 * (Currency, User, Partner, Journal) are correctly established and accessible.
 *
 * Test data:
 * - Creates related models (Currency, User, Partner, Journal) using factories
 * - Creates an Invoice with foreign keys pointing to the related models
 * - Tests that the relationship accessors return the correct related models
 *
 * Assumptions:
 * - The Invoice model has properly defined relationships with Currency, User, Partner, and Journal
 * - The factories for all models are properly configured
 * - The relationship methods use the correct foreign key columns
 *
 * Expected behavior:
 * - The Invoice should be successfully created with the specified foreign keys
 * - The relationship accessors should return the correct related models
 * - The IDs of the related models should match the foreign keys set during creation
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('billing')]
#[PluginTest('Invoices')]
#[CoversClass(Invoice::class)]
#[Description('Test Invoice model relationships with other models')]
function invoice_model_relationships_with_other_models()
{
    // Create related models
    $currency = Currency::factory()->create();
    $user = User::factory()->create();
    $partner = Partner::factory()->create();
    $journal = Journal::factory()->create();

    // Create an invoice with relationships
    $invoice = Invoice::factory()->create([
        'currency_id' => $currency->id,
        'creator_id' => $user->id,
        'partner_id' => $partner->id,
        'journal_id' => $journal->id,
    ]);

    // Test relationships
    expect($invoice->currency->id)->toBe($currency->id);
    expect($invoice->createdBy->id)->toBe($user->id);
    expect($invoice->partner->id)->toBe($partner->id);
    expect($invoice->journal->id)->toBe($journal->id);
}

/**
 * Test Invoice model methods
 *
 * This test verifies that the Invoice model's methods, particularly those
 * inherited from the Move model, function correctly.
 *
 * Test data:
 * - Creates an Invoice with move_type set to OUT_INVOICE using the factory
 * - Tests the behavior of methods inherited from the Move model
 *
 * Assumptions:
 * - The Invoice model extends the Move model
 * - The Move model has methods isInvoice(), isSaleDocument(), and isOutbound()
 * - These methods determine document type based on the move_type attribute
 * - OUT_INVOICE represents an outbound invoice (customer invoice)
 *
 * Expected behavior:
 * - isInvoice(true) should return true for an invoice
 * - isSaleDocument(true) should return true for a sales document (customer invoice)
 * - isOutbound(true) should return true for an outbound document
 * - These methods help determine the document's type and direction for business logic
 */
#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Group('database')]
#[Group('billing')]
#[Group('workflow')]
#[PluginTest('Invoices')]
#[CoversClass(Invoice::class)]
#[Description('Test Invoice model methods')]
function invoice_model_methods()
{
    // Create a test invoice
    $invoice = Invoice::factory()->create([
        'move_type' => MoveType::OUT_INVOICE,
    ]);

    // Test inherited methods from Move model
    expect($invoice->isInvoice(true))->toBeTrue();
    expect($invoice->isSaleDocument(true))->toBeTrue();
    expect($invoice->isOutbound(true))->toBeTrue();
}
