<?php

declare(strict_types=1);

use App\Tests\Attributes\PluginTest;
use Illuminate\Database\Eloquent\Model;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Webkul\Payment\Models\Payment;

/**
 * Test Payment model basic properties
 *
 * This test verifies that the Payment model is correctly instantiated,
 * extends the base Eloquent Model, and uses the HasFactory trait.
 */
#[Test]
#[Group('unit')]
#[Group('payments')]
#[PluginTest('Payments')]
#[CoversClass(Payment::class)]
#[Description('Test Payment model basic properties')]
function payment_model_basic_properties(): void
{
    // Create a test payment
    $payment = new Payment();

    // Test that it's an instance of the Payment model and extends the base Eloquent Model
    expect($payment)->toBeInstanceOf(Payment::class);
    expect($payment)->toBeInstanceOf(Model::class);

    // Test that the model uses the HasFactory trait
    expect($payment)->toBeInstanceOf(Illuminate\Database\Eloquent\Factories\HasFactory::class);
}

/**
 * Test Payment model table name
 *
 * This test verifies that the Payment model's table name is correctly set
 * according to Laravel conventions.
 */
#[Test]
#[Group('unit')]
#[Group('payments')]
#[PluginTest('Payments')]
#[CoversClass(Payment::class)]
#[Description('Test Payment model table name')]
function payment_model_table_name(): void
{
    // Create a test payment
    $payment = new Payment();

    // Test that the table name is set correctly (assuming it follows Laravel conventions)
    expect($payment->getTable())->toBe('payments');
}
