<?php

declare(strict_types=1);

use Illuminate\Database\Eloquent\Model;
use Webkul\Payment\Models\Payment;

/**
 * Test Payment model basic properties
 *
 * This test verifies that the Payment model is correctly instantiated,
 * extends the base Eloquent Model, and uses the HasFactory trait.
 */
it('can test payment model basic properties', function (): void {
    // Create a test payment
    $payment = new Payment();

    // Test that it's an instance of the Payment model and extends the base Eloquent Model
    expect($payment)->toBeInstanceOf(Payment::class);
    expect($payment)->toBeInstanceOf(Model::class);

    // Test that the model has the expected traits
    expect(class_uses($payment))->toContain('Illuminate\Database\Eloquent\Factories\HasFactory');
})->group('unit', 'payments');

/**
 * Test Payment model table name
 *
 * This test verifies that the Payment model's table name is correctly set
 * according to Laravel conventions.
 */
it('can test payment model table name', function (): void {
    // Create a test payment
    $payment = new Payment();

    // Test that the table name is set correctly (assuming it follows Laravel conventions)
    expect($payment->getTable())->toBe('payments');
})->group('unit', 'payments');
