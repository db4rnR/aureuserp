<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\Attribute;
use Webkul\Product\Models\AttributeOption;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test AttributeOption model attributes and properties')]
function attribute_option_model_attributes_and_properties(): void
{
    // Create a test attribute option
    $attributeOption = AttributeOption::factory()->create([
        'name' => 'Test Option',
        'color' => '#FF0000',
        'extra_price' => 10.00,
        'sort' => 1,
    ]);

    // Test attributes
    expect($attributeOption->name)->toBe('Test Option');
    expect($attributeOption->color)->toBe('#FF0000');
    expect($attributeOption->extra_price)->toBe(10.00);
    expect($attributeOption->sort)->toBe(1);

    // Test relationships
    expect($attributeOption->attribute())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($attributeOption->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test AttributeOption model relationships with other models')]
function attribute_option_model_relationships_with_other_models(): void
{
    // Create related models
    $attribute = Attribute::factory()->create(['name' => 'Test Attribute']);
    $user = User::factory()->create();

    // Create an attribute option with relationships
    $attributeOption = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'creator_id' => $user->id,
        'name' => 'Test Option',
    ]);

    // Test relationships
    expect($attributeOption->attribute->id)->toBe($attribute->id);
    expect($attributeOption->creator->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test AttributeOption model traits and interfaces')]
function attribute_option_model_traits_and_interfaces(): void
{
    // Create a test attribute option
    $attributeOption = AttributeOption::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($attributeOption)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($attributeOption)->getProperty('sortable')->getValue($attributeOption);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}
