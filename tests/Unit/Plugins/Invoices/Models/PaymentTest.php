<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Account\Models\Account;
use Webkul\Account\Models\Journal;
use Webkul\Account\Models\Move;
use Webkul\Account\Models\Payment as BasePayment;
use Webkul\Account\Models\PaymentMethod;
use Webkul\Account\Models\PaymentMethodLine;
use Webkul\Invoice\Models\Payment;
use Webkul\Partner\Models\BankAccount;
use Webkul\Partner\Models\Partner;
use Webkul\Payment\Models\PaymentToken;
use Webkul\Payment\Models\PaymentTransaction;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Payment model inheritance and properties')]
function payment_model_inheritance_and_properties(): void
{
    // Create a test payment
    $payment = Payment::factory()->create([
        'name' => 'Test Payment',
        'state' => 'draft',
        'payment_type' => 'inbound',
        'partner_type' => 'customer',
        'amount' => 1000.00,
        'is_reconciled' => false,
        'is_matched' => false,
        'is_sent' => false,
    ]);

    // Test that it's an instance of both the Invoice Payment and the base Account Payment
    expect($payment)->toBeInstanceOf(Payment::class);
    expect($payment)->toBeInstanceOf(BasePayment::class);

    // Test attributes
    expect($payment->name)->toBe('Test Payment');
    expect($payment->state)->toBe('draft');
    expect($payment->payment_type)->toBe('inbound');
    expect($payment->partner_type)->toBe('customer');
    expect($payment->amount)->toBe(1000.00);
    expect($payment->is_reconciled)->toBeFalse();
    expect($payment->is_matched)->toBeFalse();
    expect($payment->is_sent)->toBeFalse();

    // Test relationships inherited from the base class
    expect($payment->move())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->journal())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->partnerBank())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->pairedInternalTransferPayment())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->paymentMethodLine())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->paymentMethod())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->partner())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->outstandingAccount())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->destinationAccount())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->createdBy())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->paymentTransaction())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->sourcePayment())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->paymentToken())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($payment->accountMovePayment())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Payment model relationships with other models')]
function payment_model_relationships_with_other_models(): void
{
    // Create related models
    $move = Move::factory()->create();
    $journal = Journal::factory()->create();
    $company = Company::factory()->create();
    $partnerBank = BankAccount::factory()->create();
    $paymentMethodLine = PaymentMethodLine::factory()->create();
    $paymentMethod = PaymentMethod::factory()->create();
    $currency = Currency::factory()->create();
    $partner = Partner::factory()->create();
    $outstandingAccount = Account::factory()->create();
    $destinationAccount = Account::factory()->create();
    $user = User::factory()->create();
    $paymentTransaction = PaymentTransaction::factory()->create();
    $paymentToken = PaymentToken::factory()->create();

    // Create a payment with relationships
    $payment = Payment::factory()->create([
        'move_id' => $move->id,
        'journal_id' => $journal->id,
        'company_id' => $company->id,
        'partner_bank_id' => $partnerBank->id,
        'payment_method_line_id' => $paymentMethodLine->id,
        'payment_method_id' => $paymentMethod->id,
        'currency_id' => $currency->id,
        'partner_id' => $partner->id,
        'outstanding_account_id' => $outstandingAccount->id,
        'destination_account_id' => $destinationAccount->id,
        'created_by' => $user->id,
        'payment_transaction_id' => $paymentTransaction->id,
        'payment_token_id' => $paymentToken->id,
    ]);

    // Create a paired internal transfer payment
    $pairedPayment = Payment::factory()->create();
    $payment->update(['paired_internal_transfer_payment_id' => $pairedPayment->id]);

    // Create a source payment
    $sourcePayment = Payment::factory()->create();
    $payment->update(['source_payment_id' => $sourcePayment->id]);

    // Test relationships
    expect($payment->move->id)->toBe($move->id);
    expect($payment->journal->id)->toBe($journal->id);
    expect($payment->company->id)->toBe($company->id);
    expect($payment->partnerBank->id)->toBe($partnerBank->id);
    expect($payment->pairedInternalTransferPayment->id)->toBe($pairedPayment->id);
    expect($payment->paymentMethodLine->id)->toBe($paymentMethodLine->id);
    expect($payment->paymentMethod->id)->toBe($paymentMethod->id);
    expect($payment->currency->id)->toBe($currency->id);
    expect($payment->partner->id)->toBe($partner->id);
    expect($payment->outstandingAccount->id)->toBe($outstandingAccount->id);
    expect($payment->destinationAccount->id)->toBe($destinationAccount->id);
    expect($payment->createdBy->id)->toBe($user->id);
    expect($payment->paymentTransaction->id)->toBe($paymentTransaction->id);
    expect($payment->sourcePayment->id)->toBe($sourcePayment->id);
    expect($payment->paymentToken->id)->toBe($paymentToken->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Payment model traits')]
function payment_model_traits(): void
{
    // Create a test payment
    $payment = Payment::factory()->create();

    // Test that the model uses the expected traits
    expect($payment)->toBeInstanceOf(Webkul\Chatter\Traits\HasChatter::class);
    expect($payment)->toBeInstanceOf(Webkul\Chatter\Traits\HasLogActivity::class);

    // Test log attributes
    $logAttributes = new ReflectionClass($payment)->getProperty('logAttributes')->getValue($payment);
    expect($logAttributes)->toBeArray();
    expect($logAttributes)->toContain('name');
    expect($logAttributes)->toHaveKey('move.name');
    expect($logAttributes)->toHaveKey('company.name');
    expect($logAttributes)->toHaveKey('partner.name');
    expect($logAttributes)->toContain('partner_type');
    expect($logAttributes)->toHaveKey('paymentMethod.name');
    expect($logAttributes)->toHaveKey('currency.name');
    expect($logAttributes)->toContain('paymentToken');
    expect($logAttributes)->toHaveKey('sourcePayment.name');
    expect($logAttributes)->toHaveKey('paymentTransaction.name');
    expect($logAttributes)->toHaveKey('destinationAccount.name');
    expect($logAttributes)->toHaveKey('outstandingAccount.name');
    expect($logAttributes)->toContain('is_sent');
    expect($logAttributes)->toContain('state');
}
