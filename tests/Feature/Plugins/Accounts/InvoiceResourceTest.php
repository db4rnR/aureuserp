<?php

use Webkul\Account\Models\Move;
use Webkul\Account\Models\MoveLine;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Journal;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\JournalType;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test invoice listing page loads successfully')]
function invoice_listing_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the invoices listing page
    $response = get(route('filament.admin.resources.accounts.invoices.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test invoice creation page loads successfully')]
function invoice_creation_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the invoice creation page
    $response = get(route('filament.admin.resources.accounts.invoices.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test invoice can be created successfully')]
function invoice_can_be_created_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create dependencies
    $journal = Journal::factory()->create(['type' => JournalType::SALE]);
    $company = Company::factory()->create();
    $partner = Partner::factory()->create();
    $currency = Currency::factory()->create();
    $account = Account::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare invoice data
    $invoiceData = [
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'move_type' => MoveType::OUT_INVOICE->value,
        'invoice_date' => now()->format('Y-m-d'),
        'invoice_date_due' => now()->addDays(30)->format('Y-m-d'),
        'ref' => 'INV/2023/0001',
        'narration' => 'Test Invoice',
        'lines' => [
            [
                'account_id' => $account->id,
                'name' => 'Product Line',
                'quantity' => 2,
                'price_unit' => 100,
                'discount' => 0,
            ],
        ],
    ];

    // Submit the invoice creation form
    $response = post(route('filament.admin.resources.accounts.invoices.store'), $invoiceData);

    // Assert the invoice was created successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.invoices.index'));

    // Assert the invoice exists in the database
    expect(Move::where('ref', 'INV/2023/0001')
        ->where('move_type', MoveType::OUT_INVOICE)
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test invoice can be viewed successfully')]
function invoice_can_be_viewed_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create an invoice
    $invoice = Move::factory()->create([
        'move_type' => MoveType::OUT_INVOICE,
        'state' => MoveState::DRAFT,
        'ref' => 'INV/2023/0001',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the invoice view page
    $response = get(route('filament.admin.resources.accounts.invoices.view', $invoice));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test invoice can be confirmed successfully')]
function invoice_can_be_confirmed_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create an invoice in draft state
    $invoice = Move::factory()->create([
        'move_type' => MoveType::OUT_INVOICE,
        'state' => MoveState::DRAFT,
    ]);

    // Act as the user
    actingAs($user);

    // Confirm the invoice
    $response = post(route('filament.admin.resources.accounts.invoices.confirm', $invoice));

    // Assert the response is successful
    $response->assertSuccessful();

    // Assert the invoice state was updated to posted
    $updatedInvoice = Move::find($invoice->id);
    expect($updatedInvoice->state)->toBe(MoveState::POSTED);
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test invoice can be cancelled successfully')]
function invoice_can_be_cancelled_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create an invoice in draft state
    $invoice = Move::factory()->create([
        'move_type' => MoveType::OUT_INVOICE,
        'state' => MoveState::DRAFT,
    ]);

    // Act as the user
    actingAs($user);

    // Cancel the invoice
    $response = post(route('filament.admin.resources.accounts.invoices.cancel', $invoice));

    // Assert the response is successful
    $response->assertSuccessful();

    // Assert the invoice state was updated to cancel
    $updatedInvoice = Move::find($invoice->id);
    expect($updatedInvoice->state)->toBe(MoveState::CANCEL);
}
