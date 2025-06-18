<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Invoice\Models\Attribute;
use Webkul\Product\Enums\AttributeType;
use Webkul\Product\Models\Attribute as BaseAttribute;
use Webkul\Product\Models\AttributeOption;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Attribute model inheritance and properties')]
function attribute_model_inheritance_and_properties(): void
{
    // Create a test attribute
    $attribute = Attribute::factory()->create([
        'name' => 'Test Attribute',
        'type' => AttributeType::SELECT,
        'sort' => 1,
    ]);

    // Test that it's an instance of both the Invoice Attribute and the base Product Attribute
    expect($attribute)->toBeInstanceOf(Attribute::class);
    expect($attribute)->toBeInstanceOf(BaseAttribute::class);

    // Test attributes
    expect($attribute->name)->toBe('Test Attribute');
    expect($attribute->type)->toBe(AttributeType::SELECT);
    expect($attribute->sort)->toBe(1);

    // Test relationships inherited from the base class
    expect($attribute->options())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($attribute->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Attribute model relationships with other models')]
function attribute_model_relationships_with_other_models(): void
{
    // Create related models
    $user = User::factory()->create();

    // Create an attribute with relationships
    $attribute = Attribute::factory()->create([
        'creator_id' => $user->id,
    ]);

    // Create attribute options
    AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Test Option',
    ]);

    // Test relationships
    expect($attribute->creator->id)->toBe($user->id);
    expect($attribute->options->count())->toBe(1);
    expect($attribute->options->first()->name)->toBe('Test Option');
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Attribute model traits and interfaces')]
function attribute_model_traits_and_interfaces(): void
{
    // Create a test attribute
    $attribute = Attribute::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($attribute)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);
    expect($attribute)->toBeInstanceOf(Illuminate\Database\Eloquent\SoftDeletes::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($attribute)->getProperty('sortable')->getValue($attribute);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}
