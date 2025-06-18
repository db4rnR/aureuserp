<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Payment\Models\PaymentToken;

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test PaymentToken model basic properties')]
function payment_token_model_basic_properties(): void
{
    // Create a test payment token
    $paymentToken = new PaymentToken();

    // Test that it's an instance of the PaymentToken model and extends the base Eloquent Model
    expect($paymentToken)->toBeInstanceOf(PaymentToken::class);
    expect($paymentToken)->toBeInstanceOf(Model::class);

    // Test that the model uses the HasFactory trait
    expect($paymentToken)->toBeInstanceOf(Illuminate\Database\Eloquent\Factories\HasFactory::class);
}

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test PaymentToken model table name')]
function payment_token_model_table_name(): void
{
    // Create a test payment token
    $paymentToken = new PaymentToken();

    // Test that the table name is set correctly (assuming it follows Laravel conventions)
    expect($paymentToken->getTable())->toBe('payment_tokens');
}
