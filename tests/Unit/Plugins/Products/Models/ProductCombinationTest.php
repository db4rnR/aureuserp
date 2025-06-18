<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\Attribute;
use Webkul\Product\Models\AttributeOption;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttribute;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductCombination;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductCombination model attributes and properties')]
function product_combination_model_attributes_and_properties(): void
{
    // Create a test product combination
    $productCombination = ProductCombination::factory()->create();

    // Test relationships
    expect($productCombination->product())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productCombination->productAttributeValue())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductCombination model relationships with other models')]
function product_combination_model_relationships_with_other_models(): void
{
    // Create a parent product
    $parentProduct = Product::factory()->create([
        'name' => 'Parent Product',
        'is_configurable' => true,
    ]);

    // Create a variant product
    $variantProduct = Product::factory()->create([
        'name' => 'Variant Product',
        'parent_id' => $parentProduct->id,
    ]);

    // Create an attribute
    $attribute = Attribute::factory()->create(['name' => 'Test Attribute']);

    // Create attribute options
    $option = AttributeOption::factory()->create([
        'attribute_id' => $attribute->id,
        'name' => 'Test Option',
    ]);

    // Create a product attribute
    $productAttribute = ProductAttribute::factory()->create([
        'product_id' => $parentProduct->id,
        'attribute_id' => $attribute->id,
    ]);

    // Create a product attribute value
    $productAttributeValue = ProductAttributeValue::factory()->create([
        'product_id' => $variantProduct->id,
        'attribute_id' => $attribute->id,
        'product_attribute_id' => $productAttribute->id,
        'attribute_option_id' => $option->id,
    ]);

    // Create a product combination
    $productCombination = ProductCombination::factory()->create([
        'product_id' => $variantProduct->id,
        'product_attribute_value_id' => $productAttributeValue->id,
    ]);

    // Test relationships
    expect($productCombination->product->id)->toBe($variantProduct->id);
    expect($productCombination->productAttributeValue->id)->toBe($productAttributeValue->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductCombination model with multiple combinations')]
function product_combination_model_with_multiple_combinations(): void
{
    // Create a parent product
    $parentProduct = Product::factory()->create([
        'name' => 'Parent Product',
        'is_configurable' => true,
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

    // Create attributes
    $colorAttribute = Attribute::factory()->create(['name' => 'Color']);
    $sizeAttribute = Attribute::factory()->create(['name' => 'Size']);

    // Create attribute options
    $redOption = AttributeOption::factory()->create([
        'attribute_id' => $colorAttribute->id,
        'name' => 'Red',
    ]);

    $blueOption = AttributeOption::factory()->create([
        'attribute_id' => $colorAttribute->id,
        'name' => 'Blue',
    ]);

    $smallOption = AttributeOption::factory()->create([
        'attribute_id' => $sizeAttribute->id,
        'name' => 'Small',
    ]);

    $largeOption = AttributeOption::factory()->create([
        'attribute_id' => $sizeAttribute->id,
        'name' => 'Large',
    ]);

    // Create product attributes
    $colorProductAttribute = ProductAttribute::factory()->create([
        'product_id' => $parentProduct->id,
        'attribute_id' => $colorAttribute->id,
    ]);

    $sizeProductAttribute = ProductAttribute::factory()->create([
        'product_id' => $parentProduct->id,
        'attribute_id' => $sizeAttribute->id,
    ]);

    // Create product attribute values for variant 1 (Red, Small)
    $redValue = ProductAttributeValue::factory()->create([
        'product_id' => $variant1->id,
        'attribute_id' => $colorAttribute->id,
        'product_attribute_id' => $colorProductAttribute->id,
        'attribute_option_id' => $redOption->id,
    ]);

    $smallValue = ProductAttributeValue::factory()->create([
        'product_id' => $variant1->id,
        'attribute_id' => $sizeAttribute->id,
        'product_attribute_id' => $sizeProductAttribute->id,
        'attribute_option_id' => $smallOption->id,
    ]);

    // Create product attribute values for variant 2 (Blue, Large)
    $blueValue = ProductAttributeValue::factory()->create([
        'product_id' => $variant2->id,
        'attribute_id' => $colorAttribute->id,
        'product_attribute_id' => $colorProductAttribute->id,
        'attribute_option_id' => $blueOption->id,
    ]);

    $largeValue = ProductAttributeValue::factory()->create([
        'product_id' => $variant2->id,
        'attribute_id' => $sizeAttribute->id,
        'product_attribute_id' => $sizeProductAttribute->id,
        'attribute_option_id' => $largeOption->id,
    ]);

    // Create product combinations
    $combination1Color = ProductCombination::factory()->create([
        'product_id' => $variant1->id,
        'product_attribute_value_id' => $redValue->id,
    ]);

    $combination1Size = ProductCombination::factory()->create([
        'product_id' => $variant1->id,
        'product_attribute_value_id' => $smallValue->id,
    ]);

    $combination2Color = ProductCombination::factory()->create([
        'product_id' => $variant2->id,
        'product_attribute_value_id' => $blueValue->id,
    ]);

    $combination2Size = ProductCombination::factory()->create([
        'product_id' => $variant2->id,
        'product_attribute_value_id' => $largeValue->id,
    ]);

    // Test that the combinations are correctly associated with the variants
    $variant1Combinations = ProductCombination::where('product_id', $variant1->id)->get();
    $variant2Combinations = ProductCombination::where('product_id', $variant2->id)->get();

    expect($variant1Combinations)->toHaveCount(2);
    expect($variant2Combinations)->toHaveCount(2);

    // Test that the combinations have the correct attribute values
    expect($variant1Combinations->contains($combination1Color))->toBeTrue();
    expect($variant1Combinations->contains($combination1Size))->toBeTrue();
    expect($variant2Combinations->contains($combination2Color))->toBeTrue();
    expect($variant2Combinations->contains($combination2Size))->toBeTrue();

    // Test that the attribute values are correctly associated with the combinations
    expect($combination1Color->productAttributeValue->id)->toBe($redValue->id);
    expect($combination1Size->productAttributeValue->id)->toBe($smallValue->id);
    expect($combination2Color->productAttributeValue->id)->toBe($blueValue->id);
    expect($combination2Size->productAttributeValue->id)->toBe($largeValue->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductCombination model HasFactory trait')]
function product_combination_model_has_factory_trait(): void
{
    // Test that the model uses the HasFactory trait
    expect(ProductCombination::factory())->toBeInstanceOf(Illuminate\Database\Eloquent\Factories\Factory::class);
}
