<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\Attribute;
use Webkul\Product\Models\ProductAttribute;
use Webkul\Product\Models\AttributeOption;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttributeValue model attributes and properties')]
function product_attribute_value_model_attributes_and_properties()
{
    // Create a test product attribute value
    $productAttributeValue = ProductAttributeValue::factory()->create([
        'extra_price' => 10.00,
    ]);

    // Test attributes
    expect($productAttributeValue->extra_price)->toBe(10.00);

    // Test relationships
    expect($productAttributeValue->product())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productAttributeValue->attribute())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productAttributeValue->productAttribute())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productAttributeValue->attributeOption())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productAttributeValue->creator())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttributeValue model relationships with other models')]
function product_attribute_value_model_relationships_with_other_models()
{
    // Create related models
    $product = Product::factory()->create(['name' => 'Test Product']);
    $attribute = Attribute::factory()->create(['name' => 'Test Attribute']);
    $productAttribute = ProductAttribute::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
    ]);
    $attributeOption = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Test Option',
    ]);
    $user = User::factory()->create();

    // Create a product attribute value with relationships
    $productAttributeValue = ProductAttributeValue::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'product_attribute_id' => $productAttribute->id,
        'attribute_option_id' => $attributeOption->id,
        'extra_price' => 10.00,
    ]);

    // Test relationships
    expect($productAttributeValue->product->id)->toBe($product->id);
    expect($productAttributeValue->attribute->id)->toBe($attribute->id);
    expect($productAttributeValue->productAttribute->id)->toBe($productAttribute->id);
    expect($productAttributeValue->attributeOption->id)->toBe($attributeOption->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttributeValue model timestamps')]
function product_attribute_value_model_timestamps()
{
    // The ProductAttributeValue model has $timestamps = false, so it should not have created_at and updated_at fields

    // Create a test product attribute value
    $productAttributeValue = ProductAttributeValue::factory()->create();

    // Test that the timestamps are not set
    expect($productAttributeValue->timestamps)->toBeFalse();
    expect(isset($productAttributeValue->created_at))->toBeFalse();
    expect(isset($productAttributeValue->updated_at))->toBeFalse();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductAttributeValue model with different attribute options')]
function product_attribute_value_model_with_different_attribute_options()
{
    // Create a product
    $product = Product::factory()->create(['name' => 'Test Product']);

    // Create an attribute
    $attribute = Attribute::factory()->create(['name' => 'Test Attribute']);

    // Create a product attribute
    $productAttribute = ProductAttribute::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
    ]);

    // Create multiple attribute options
    $option1 = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Option 1',
    ]);

    $option2 = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Option 2',
        'extra_price' => 5.00,
    ]);

    // Create product attribute values with different options
    $value1 = ProductAttributeValue::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'product_attribute_id' => $productAttribute->id,
        'attribute_option_id' => $option1->id,
    ]);

    $value2 = ProductAttributeValue::factory()->create([
        'product_id' => $product->id,
        'attribute_id' => $attribute->id,
        'product_attribute_id' => $productAttribute->id,
        'attribute_option_id' => $option2->id,
        'extra_price' => 10.00,
    ]);

    // Test that the values have the correct relationships
    expect($value1->attributeOption->id)->toBe($option1->id);
    expect($value2->attributeOption->id)->toBe($option2->id);

    // Test that the extra_price is set correctly
    expect($value1->extra_price)->toBeNull();
    expect($value2->extra_price)->toBe(10.00);
}
