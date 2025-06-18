<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Enums\AttributeType;
use Webkul\Product\Models\Attribute;
use Webkul\Product\Models\AttributeOption;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Attribute model attributes and properties')]
function attribute_model_attributes_and_properties(): void
{
    // Create a test attribute
    $attribute = Attribute::factory()->create([
        'name' => 'Test Attribute',
        'type' => AttributeType::SELECT,
        'sort' => 1,
    ]);

    // Test attributes
    expect($attribute->name)->toBe('Test Attribute');
    expect($attribute->type)->toBe(AttributeType::SELECT);
    expect($attribute->sort)->toBe(1);

    // Test relationships
    expect($attribute->options())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($attribute->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Attribute model relationships with other models')]
function attribute_model_relationships_with_other_models(): void
{
    // Create related models
    $user = User::factory()->create();

    // Create an attribute with relationships
    $attribute = Attribute::factory()->create([
        'creator_id' => $user->id,
        'name' => 'Test Attribute',
        'type' => AttributeType::SELECT,
    ]);

    // Create attribute options
    $option1 = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Option 1',
    ]);

    $option2 = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Option 2',
    ]);

    // Test relationships
    expect($attribute->creator->id)->toBe($user->id);
    expect($attribute->options->count())->toBe(2);
    expect($attribute->options->contains($option1))->toBeTrue();
    expect($attribute->options->contains($option2))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
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

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Attribute model with enum type')]
function attribute_model_with_enum_type(): void
{
    // Create attributes with different types
    $selectAttribute = Attribute::factory()->create(['type' => AttributeType::SELECT]);
    $radioAttribute = Attribute::factory()->create(['type' => AttributeType::RADIO]);
    $colorAttribute = Attribute::factory()->create(['type' => AttributeType::COLOR]);

    // Test that the type is correctly cast to an enum
    expect($selectAttribute->type)->toBeInstanceOf(AttributeType::class);
    expect($radioAttribute->type)->toBeInstanceOf(AttributeType::class);
    expect($colorAttribute->type)->toBeInstanceOf(AttributeType::class);

    // Test enum values
    expect($selectAttribute->type)->toBe(AttributeType::SELECT);
    expect($radioAttribute->type)->toBe(AttributeType::RADIO);
    expect($colorAttribute->type)->toBe(AttributeType::COLOR);

    // Test enum string values
    expect($selectAttribute->type->value)->toBe('select');
    expect($radioAttribute->type->value)->toBe('radio');
    expect($colorAttribute->type->value)->toBe('color');
}
