<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\Attribute;
use Webkul\Product\Models\AttributeOption;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttribute;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttribute model attributes and properties')]
function product_attribute_model_attributes_and_properties(): void
{
    // Create a test product attribute
    $productAttribute = ProductAttribute::factory()->create([
        'sort' => 1,
    ]);

    // Test attributes
    expect($productAttribute->sort)->toBe(1);

    // Test relationships
    expect($productAttribute->product())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productAttribute->attribute())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productAttribute->options())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($productAttribute->values())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($productAttribute->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttribute model relationships with other models')]
function product_attribute_pivot_model_relationships_with_other_models(): void
{
    // Create related models
    $product = Product::factory()->create(['name' => 'Test Product']);
    $attribute = Attribute::factory()->create(['name' => 'Test Attribute']);
    $user = User::factory()->create();

    // Create a product attribute with relationships
    $productAttribute = ProductAttribute::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'creator_id' => $user->id,
        'sort' => 1,
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

    // Create product attribute values
    $value1 = ProductAttributeValue::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'product_attribute_id' => $productAttribute->id,
        'attribute_option_id' => $option1->id,
    ]);

    // Attach options to the product attribute
    $productAttribute->options()->attach($option1);
    $productAttribute->options()->attach($option2);

    // Test relationships
    expect($productAttribute->product->id)->toBe($product->id);
    expect($productAttribute->attribute->id)->toBe($attribute->id);
    expect($productAttribute->creator->id)->toBe($user->id);
    expect($productAttribute->options->count())->toBe(2);
    expect($productAttribute->options->contains($option1))->toBeTrue();
    expect($productAttribute->options->contains($option2))->toBeTrue();
    expect($productAttribute->values->count())->toBe(1);
    expect($productAttribute->values->first()->id)->toBe($value1->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttribute model traits and interfaces')]
function product_attribute_pivot_model_traits_and_interfaces(): void
{
    // Create a test product attribute
    $productAttribute = ProductAttribute::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($productAttribute)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($productAttribute)->getProperty('sortable')->getValue($productAttribute);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttribute model boot method for deleting variants')]
function product_attribute_model_boot_method_for_deleting_variants(): void
{
    // Create a parent product
    $parentProduct = Product::factory()->create([
        'name' => 'Parent Product',
        'is_configurable' => true,
    ]);

    // Create an attribute
    $attribute = Attribute::factory()->create(['name' => 'Test Attribute']);

    // Create a product attribute for the parent product
    $productAttribute = ProductAttribute::factory()->create([
        'product_id' => $parentProduct->id,
        'attribute_id' => $attribute->id,
    ]);

    // Create variant products
    $variant1 = Product::factory()->create([
        'name' => 'Variant 1',
        'parent_id' => $parentProduct->id,
    ]);

    $variant2 = Product::factory()->create([
        'name' => 'Variant 2',
        'parent_id' => $parentProduct->id,
    ]);

    // Verify that variants exist
    expect(Product::find($variant1->id))->not->toBeNull();
    expect(Product::find($variant2->id))->not->toBeNull();

    // Delete the product attribute
    $productAttribute->delete();

    // Verify that variants are deleted
    expect(Product::find($variant1->id))->toBeNull();
    expect(Product::find($variant2->id))->toBeNull();
}
