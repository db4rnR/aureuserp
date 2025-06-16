<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Payment\Models\PaymentToken;
use Illuminate\Database\Eloquent\Model;

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test PaymentToken model basic properties')]
function payment_token_model_basic_properties()
{
    // Create a test payment token
    $paymentToken = new PaymentToken();

    // Test that it's an instance of the PaymentToken model and extends the base Eloquent Model
    expect($paymentToken)->toBeInstanceOf(PaymentToken::class);
    expect($paymentToken)->toBeInstanceOf(Model::class);

    // Test that the model uses the HasFactory trait
    expect($paymentToken)->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\HasFactory::class);
}

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test PaymentToken model table name')]
function payment_token_model_table_name()
{
    // Create a test payment token
    $paymentToken = new PaymentToken();

    // Test that the table name is set correctly (assuming it follows Laravel conventions)
    expect($paymentToken->getTable())->toBe('payment_tokens');
}
