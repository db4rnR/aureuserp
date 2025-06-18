<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Payment\Models\PaymentTransaction;

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test PaymentTransaction model basic properties')]
function payment_transaction_model_basic_properties(): void
{
    // Create a test payment transaction
    $paymentTransaction = new PaymentTransaction();

    // Test that it's an instance of the PaymentTransaction model and extends the base Eloquent Model
    expect($paymentTransaction)->toBeInstanceOf(PaymentTransaction::class);
    expect($paymentTransaction)->toBeInstanceOf(Model::class);

    // Test that the model uses the HasFactory trait
    expect($paymentTransaction)->toBeInstanceOf(Illuminate\Database\Eloquent\Factories\HasFactory::class);
}

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test PaymentTransaction model table name')]
function payment_transaction_model_table_name(): void
{
    // Create a test payment transaction
    $paymentTransaction = new PaymentTransaction();

    // Test that the table name is set correctly (assuming it follows Laravel conventions)
    expect($paymentTransaction->getTable())->toBe('payment_transactions');
}
