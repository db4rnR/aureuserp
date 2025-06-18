<?php

declare(strict_types=1);

use Webkul\Account\Enums\JournalType;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Journal;
use Webkul\Invoice\Models\Payment;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test payments listing page loads successfully')]
function payments_listing_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the payments listing page
    $response = get(route('filament.admin.customer.resources.payment.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test payment creation page loads successfully')]
function payment_creation_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the payment creation page
    $response = get(route('filament.admin.customer.resources.payment.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test payment can be created successfully')]
function payment_can_be_created_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create dependencies
    $journal = Journal::factory()->create(['type' => JournalType::BANK]);
    $company = Company::factory()->create();
    $partner = Partner::factory()->create();
    $currency = Currency::factory()->create();
    $account = Account::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare payment data
    $paymentData = [
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'payment_type' => 'inbound',
        'partner_type' => 'customer',
        'date' => now()->format('Y-m-d'),
        'amount' => 1000.00,
        'memo' => 'Test Payment',
        'destination_account_id' => $account->id,
    ];

    // Submit the payment creation form
    $response = post(route('filament.admin.customer.resources.payment.store'), $paymentData);

    // Assert the payment was created successfully
    $response->assertRedirect(route('filament.admin.customer.resources.payment.index'));

    // Assert the payment exists in the database
    expect(Payment::where('memo', 'Test Payment')
        ->where('amount', 1000.00)
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test payment can be viewed successfully')]
function payment_can_be_viewed_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a payment
    $payment = Payment::factory()->create([
        'memo' => 'Test Payment',
        'amount' => 1000.00,
    ]);

    // Act as the user
    actingAs($user);

    // Visit the payment view page
    $response = get(route('filament.admin.customer.resources.payment.view', $payment));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test payment can be edited successfully')]
function payment_can_be_edited_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a payment
    $payment = Payment::factory()->create([
        'memo' => 'Test Payment',
        'amount' => 1000.00,
    ]);

    // Act as the user
    actingAs($user);

    // Visit the payment edit page
    $response = get(route('filament.admin.customer.resources.payment.edit', $payment));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Prepare updated payment data
    $updatedData = [
        'memo' => 'Updated Payment',
        'amount' => 1500.00,
    ];

    // Submit the payment edit form
    $response = patch(route('filament.admin.customer.resources.payment.update', $payment), $updatedData);

    // Assert the payment was updated successfully
    $response->assertRedirect(route('filament.admin.customer.resources.payment.view', $payment));

    // Assert the payment was updated in the database
    $updatedPayment = Payment::find($payment->id);
    expect($updatedPayment->memo)->toBe('Updated Payment');
    expect($updatedPayment->amount)->toBe(1500.00);
}
