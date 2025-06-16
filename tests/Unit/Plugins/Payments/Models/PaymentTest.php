<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Payment\Models\Payment;
use Illuminate\Database\Eloquent\Model;

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test Payment model basic properties')]
function payment_model_basic_properties()
{
    // Create a test payment
    $payment = new Payment();

    // Test that it's an instance of the Payment model and extends the base Eloquent Model
    expect($payment)->toBeInstanceOf(Payment::class);
    expect($payment)->toBeInstanceOf(Model::class);

    // Test that the model uses the HasFactory trait
    expect($payment)->toBeInstanceOf(\Illuminate\Database\Eloquent\Factories\HasFactory::class);
}

#[Test]
#[Group('unit')]
#[Group('payments')]
#[Description('Test Payment model table name')]
function payment_model_table_name()
{
    // Create a test payment
    $payment = new Payment();

    // Test that the table name is set correctly (assuming it follows Laravel conventions)
    expect($payment->getTable())->toBe('payments');
}
