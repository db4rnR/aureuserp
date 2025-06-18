<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Invoice\Models\PaymentTerm;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test PaymentTerm model attributes and relationships')]
function payment_term_model_attributes_and_relationships(): void
{
    // Create a test payment term
    $paymentTerm = PaymentTerm::factory()->create([
        'name' => 'Net 30 Days',
        'active' => true,
        'note' => 'Payment due within 30 days',
        'company_id' => Company::factory()->create()->id,
        'creator_id' => User::factory()->create()->id,
    ]);

    // Test attributes
    expect($paymentTerm->name)->toBe('Net 30 Days');
    expect($paymentTerm->active)->toBeTrue();
    expect($paymentTerm->note)->toBe('Payment due within 30 days');

    // Test relationships
    expect($paymentTerm->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($paymentTerm->createdBy())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($paymentTerm->lines())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test PaymentTerm model relationships with other models')]
function payment_term_model_relationships_with_other_models(): void
{
    // Create related models
    $company = Company::factory()->create();
    $user = User::factory()->create();

    // Create a payment term with relationships
    $paymentTerm = PaymentTerm::factory()->create([
        'company_id' => $company->id,
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($paymentTerm->company->id)->toBe($company->id);
    expect($paymentTerm->createdBy->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test PaymentTerm model with lines')]
function payment_term_model_with_lines(): void
{
    // Create a payment term
    $paymentTerm = PaymentTerm::factory()->create();

    // Create payment term lines
    $line1 = $paymentTerm->lines()->create([
        'value' => 'percent',
        'value_amount' => 50,
        'days' => 0,
    ]);

    $line2 = $paymentTerm->lines()->create([
        'value' => 'percent',
        'value_amount' => 50,
        'days' => 30,
    ]);

    // Test that the lines were created and associated with the payment term
    expect($paymentTerm->lines->count())->toBe(2);
    expect($paymentTerm->lines->contains($line1))->toBeTrue();
    expect($paymentTerm->lines->contains($line2))->toBeTrue();

    // Test line attributes
    expect($paymentTerm->lines->first()->value)->toBe('percent');
    expect($paymentTerm->lines->first()->value_amount)->toBe(50);
    expect($paymentTerm->lines->first()->days)->toBe(0);
}
