<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\Incoterm;
use Webkul\Account\Models\Incoterm as BaseIncoterm;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Incoterm model inheritance and properties')]
function incoterm_model_inheritance_and_properties()
{
    // Create a test incoterm
    $incoterm = Incoterm::factory()->create([
        'code' => 'FOB',
        'name' => 'Free On Board',
    ]);

    // Test that it's an instance of both the Invoice Incoterm and the base Account Incoterm
    expect($incoterm)->toBeInstanceOf(Incoterm::class);
    expect($incoterm)->toBeInstanceOf(BaseIncoterm::class);

    // Test attributes
    expect($incoterm->code)->toBe('FOB');
    expect($incoterm->name)->toBe('Free On Board');

    // Test relationships inherited from the base class
    expect($incoterm->createdBy())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Incoterm model relationships with other models')]
function incoterm_model_relationships_with_other_models()
{
    // Create related models
    $user = User::factory()->create();

    // Create an incoterm with relationships
    $incoterm = Incoterm::factory()->create([
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($incoterm->createdBy->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Incoterm model traits')]
function incoterm_model_traits()
{
    // Create a test incoterm
    $incoterm = Incoterm::factory()->create();

    // Test that the model uses the expected traits
    expect($incoterm)->toBeInstanceOf(\Illuminate\Database\Eloquent\SoftDeletes::class);
}
