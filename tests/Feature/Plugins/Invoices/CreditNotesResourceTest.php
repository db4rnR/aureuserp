<?php

use Webkul\Invoice\Models\CreditNote;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Models\Journal;
use Webkul\Account\Enums\JournalType;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Account\Models\Account;

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit notes listing page loads successfully')]
function credit_notes_listing_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the credit notes listing page
    $response = get(route('filament.admin.customer.resources.credit-notes.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit note creation page loads successfully')]
function credit_note_creation_page_loads_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the credit note creation page
    $response = get(route('filament.admin.customer.resources.credit-notes.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit note can be created successfully')]
function credit_note_can_be_created_successfully()
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

    // Prepare credit note data
    $creditNoteData = [
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'move_type' => MoveType::OUT_REFUND->value,
        'invoice_date' => now()->format('Y-m-d'),
        'invoice_date_due' => now()->addDays(30)->format('Y-m-d'),
        'ref' => 'RINV/2023/0001',
        'narration' => 'Test Credit Note',
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

    // Submit the credit note creation form
    $response = post(route('filament.admin.customer.resources.credit-notes.store'), $creditNoteData);

    // Assert the credit note was created successfully
    $response->assertRedirect(route('filament.admin.customer.resources.credit-notes.index'));

    // Assert the credit note exists in the database
    expect(CreditNote::where('ref', 'RINV/2023/0001')
        ->where('move_type', MoveType::OUT_REFUND)
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit note can be viewed successfully')]
function credit_note_can_be_viewed_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a credit note
    $creditNote = CreditNote::factory()->create([
        'move_type' => MoveType::OUT_REFUND,
        'state' => MoveState::DRAFT,
        'ref' => 'RINV/2023/0001',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the credit note view page
    $response = get(route('filament.admin.customer.resources.credit-notes.view', $creditNote));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit note can be edited successfully')]
function credit_note_can_be_edited_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a credit note in draft state
    $creditNote = CreditNote::factory()->create([
        'move_type' => MoveType::OUT_REFUND,
        'state' => MoveState::DRAFT,
        'narration' => 'Original narration',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the credit note edit page
    $response = get(route('filament.admin.customer.resources.credit-notes.edit', $creditNote));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Prepare updated credit note data
    $updatedData = [
        'narration' => 'Updated narration',
    ];

    // Submit the credit note edit form
    $response = patch(route('filament.admin.customer.resources.credit-notes.update', $creditNote), $updatedData);

    // Assert the credit note was updated successfully
    $response->assertRedirect(route('filament.admin.customer.resources.credit-notes.view', $creditNote));

    // Assert the credit note was updated in the database
    $updatedCreditNote = CreditNote::find($creditNote->id);
    expect($updatedCreditNote->narration)->toBe('Updated narration');
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit note can be confirmed successfully')]
function credit_note_can_be_confirmed_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a credit note in draft state
    $creditNote = CreditNote::factory()->create([
        'move_type' => MoveType::OUT_REFUND,
        'state' => MoveState::DRAFT,
    ]);

    // Act as the user
    actingAs($user);

    // Confirm the credit note
    $response = post(route('filament.admin.customer.resources.credit-notes.confirm', $creditNote));

    // Assert the response is successful
    $response->assertSuccessful();

    // Assert the credit note state was updated to posted
    $updatedCreditNote = CreditNote::find($creditNote->id);
    expect($updatedCreditNote->state)->toBe(MoveState::POSTED);
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test credit note can be cancelled successfully')]
function credit_note_can_be_cancelled_successfully()
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a credit note in draft state
    $creditNote = CreditNote::factory()->create([
        'move_type' => MoveType::OUT_REFUND,
        'state' => MoveState::DRAFT,
    ]);

    // Act as the user
    actingAs($user);

    // Cancel the credit note
    $response = post(route('filament.admin.customer.resources.credit-notes.cancel', $creditNote));

    // Assert the response is successful
    $response->assertSuccessful();

    // Assert the credit note state was updated to cancel
    $updatedCreditNote = CreditNote::find($creditNote->id);
    expect($updatedCreditNote->state)->toBe(MoveState::CANCEL);
}
