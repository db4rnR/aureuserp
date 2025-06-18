<?php

declare(strict_types=1);

use Webkul\Account\Enums\JournalType;
use Webkul\Account\Enums\MoveState;
use Webkul\Account\Enums\MoveType;
use Webkul\Account\Enums\PaymentState;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\Payment;
use Webkul\Partner\Models\Partner;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test payments listing page loads successfully')]
function payments_listing_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the payments listing page
    $response = get(route('filament.admin.resources.accounts.payments.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test payment creation page loads successfully')]
function payment_creation_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the payment creation page
    $response = get(route('filament.admin.resources.accounts.payments.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
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

    // Create an invoice to pay
    $invoice = Move::factory()->create([
        'move_type' => MoveType::OUT_INVOICE,
        'state' => MoveState::POSTED,
        'partner_id' => $partner->id,
        'amount_total' => 1000,
        'amount_residual' => 1000,
    ]);

    // Act as the user
    actingAs($user);

    // Prepare payment data
    $paymentData = [
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'payment_date' => now()->format('Y-m-d'),
        'amount' => 1000,
        'payment_type' => 'inbound',
        'partner_type' => 'customer',
        'communication' => 'Payment for invoice '.$invoice->name,
        'invoice_ids' => [$invoice->id],
    ];

    // Submit the payment creation form
    $response = post(route('filament.admin.resources.accounts.payments.store'), $paymentData);

    // Assert the payment was created successfully
    $response->assertRedirect(route('filament.admin.resources.accounts.payments.index'));

    // Assert the payment exists in the database
    expect(Payment::where('amount', 1000)
        ->where('partner_id', $partner->id)
        ->exists())->toBeTrue();

    // Assert the invoice amount_residual was updated
    $updatedInvoice = Move::find($invoice->id);
    expect($updatedInvoice->amount_residual)->toBe(0);
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test payment can be viewed successfully')]
function payment_can_be_viewed_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a payment
    $payment = Payment::factory()->create([
        'amount' => 1000,
        'payment_type' => 'inbound',
        'partner_type' => 'customer',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the payment view page
    $response = get(route('filament.admin.resources.accounts.payments.view', $payment));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('accounts')]
#[Description('Test payment can be cancelled successfully')]
function payment_can_be_cancelled_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a payment
    $payment = Payment::factory()->create([
        'state' => PaymentState::PAID,
        'amount' => 1000,
    ]);

    // Create an invoice that was paid by this payment
    $invoice = Move::factory()->create([
        'move_type' => MoveType::OUT_INVOICE,
        'state' => MoveState::POSTED,
        'amount_total' => 1000,
        'amount_residual' => 0, // Fully paid
    ]);

    // Associate the payment with the invoice
    // This would typically be done through a reconciliation process
    // For testing purposes, we'll assume this association exists

    // Act as the user
    actingAs($user);

    // Cancel the payment
    $response = post(route('filament.admin.resources.accounts.payments.cancel', $payment));

    // Assert the response is successful
    $response->assertSuccessful();

    // Assert the payment state was updated to cancelled
    $updatedPayment = Payment::find($payment->id);
    expect($updatedPayment->state)->toBe(PaymentState::REVERSED);

    // Assert the invoice amount_residual was updated back to the original amount
    $updatedInvoice = Move::find($invoice->id);
    expect($updatedInvoice->amount_residual)->toBe(1000);
}
