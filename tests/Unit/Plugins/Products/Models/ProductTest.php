<?php

declare(strict_types=1);

use App\Tests\Attributes\PluginTest;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Test;
use Webkul\Product\Enums\ProductType;
use Webkul\Product\Models\Category;
use Webkul\Product\Models\PriceRuleItem;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductAttribute;
use Webkul\Product\Models\ProductAttributeValue;
use Webkul\Product\Models\ProductCombination;
use Webkul\Product\Models\ProductSupplier;
use Webkul\Product\Models\Tag;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\UOM;

/**
 * Test Product model attributes and properties
 *
 * This test verifies that the Product model's attributes are correctly set
 * and that its relationships with other models are properly defined.
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[PluginTest('Products')]
#[CoversClass(Product::class)]
#[Description('Test Product model attributes and properties')]
function product_model_attributes_and_properties(): void
{
    // Create a test product
    $product = Product::factory()->create([
        'type' => ProductType::GOODS,
        'name' => 'Test Product',
        'service_tracking' => null,
        'reference' => 'PROD001',
        'barcode' => '123456789',
        'price' => 100.00,
        'cost' => 80.00,
        'volume' => 2.5,
        'weight' => 1.5,
        'description' => 'Test product description',
        'description_purchase' => 'Purchase description',
        'description_sale' => 'Sale description',
        'enable_sales' => true,
        'enable_purchase' => true,
        'is_favorite' => false,
        'is_configurable' => false,
        'images' => ['image1.jpg', 'image2.jpg'],
        'sort' => 1,
    ]);

    // Test attributes
    expect($product->type)->toBe(ProductType::GOODS);
    expect($product->name)->toBe('Test Product');
    expect($product->reference)->toBe('PROD001');
    expect($product->barcode)->toBe('123456789');
    expect($product->price)->toBe(100.00);
    expect($product->cost)->toBe(80.00);
    expect($product->volume)->toBe(2.5);
    expect($product->weight)->toBe(1.5);
    expect($product->description)->toBe('Test product description');
    expect($product->description_purchase)->toBe('Purchase description');
    expect($product->description_sale)->toBe('Sale description');
    expect($product->enable_sales)->toBeTrue();
    expect($product->enable_purchase)->toBeTrue();
    expect($product->is_favorite)->toBeFalse();
    expect($product->is_configurable)->toBeFalse();
    expect($product->images)->toBe(['image1.jpg', 'image2.jpg']);
    expect($product->sort)->toBe(1);

    // Test relationships
    expect($product->parent())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->uom())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->uomPO())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->category())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->tags())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($product->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->attributes())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($product->attribute_values())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($product->variants())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($product->combinations())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($product->priceRuleItems())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($product->supplierInformation())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
}

/**
 * Test Product model relationships with other models
 *
 * This test verifies that the Product model's relationships with other models
 * are correctly established and accessible, including parent-child relationships,
 * many-to-many relationships, and one-to-many relationships.
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[PluginTest('Products')]
#[CoversClass(Product::class)]
#[Description('Test Product model relationships with other models')]
function products_product_model_relationships_with_other_models(): void
{
    // Create related models
    $user = User::factory()->create();
    $company = Company::factory()->create();
    $category = Category::factory()->create();
    $uom = UOM::factory()->create();
    $parentProduct = Product::factory()->create(['name' => 'Parent Product']);
    $tag = Tag::factory()->create();

    // Create a product with relationships
    $product = Product::factory()->create([
        'parent_id' => $parentProduct->id,
        'uom_id' => $uom->id,
        'uom_po_id' => $uom->id,
        'category_id' => $category->id,
        'company_id' => $company->id,
        'creator_id' => $user->id,
    ]);

    // Attach tags
    $product->tags()->attach($tag);

    // Create product attributes, attribute values, combinations, price rule items, and supplier information
    $attribute = ProductAttribute::factory()->create(['product_id' => $product->id]);
    $attributeValue = ProductAttributeValue::factory()->create(['product_id' => $product->id]);
    $combination = ProductCombination::factory()->create(['product_id' => $product->id]);
    $priceRuleItem = PriceRuleItem::factory()->create(['product_id' => $product->id]);
    $supplierInfo = ProductSupplier::factory()->create(['product_id' => $product->id]);

    // Create a variant product
    $variant = Product::factory()->create([
        'parent_id' => $product->id,
        'name' => 'Variant Product',
    ]);

    // Test relationships
    expect($product->parent->id)->toBe($parentProduct->id);
    expect($product->uom->id)->toBe($uom->id);
    expect($product->uomPO->id)->toBe($uom->id);
    expect($product->category->id)->toBe($category->id);
    expect($product->company->id)->toBe($company->id);
    expect($product->creator->id)->toBe($user->id);
    expect($product->tags->count())->toBe(1);
    expect($product->tags->first()->id)->toBe($tag->id);
    expect($product->attributes()->count())->toBe(1);
    expect($product->attributes()->first()->id)->toBe($attribute->id);
    expect($product->attribute_values()->count())->toBe(1);
    expect($product->attribute_values()->first()->id)->toBe($attributeValue->id);
    expect($product->variants->count())->toBe(1);
    expect($product->variants->first()->id)->toBe($variant->id);
    expect($product->combinations->count())->toBe(1);
    expect($product->combinations->first()->id)->toBe($combination->id);
    expect($product->priceRuleItems->count())->toBe(1);
    expect($product->priceRuleItems->first()->id)->toBe($priceRuleItem->id);
    expect($product->supplierInformation->count())->toBe(1);
    expect($product->supplierInformation->first()->id)->toBe($supplierInfo->id);
}

/**
 * Test Product model traits and interfaces
 *
 * This test verifies that the Product model uses the expected traits and implements
 * the correct interfaces, including HasChatter, HasLogActivity, SoftDeletes, and Sortable.
 * It also tests the configuration of these traits.
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[PluginTest('Products')]
#[CoversClass(Product::class)]
#[Description('Test Product model traits and interfaces')]
function product_model_traits_and_interfaces(): void
{
    // Create a test product
    $product = Product::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($product)->toBeInstanceOf(Webkul\Chatter\Traits\HasChatter::class);
    expect($product)->toBeInstanceOf(Webkul\Chatter\Traits\HasLogActivity::class);
    expect($product)->toBeInstanceOf(Illuminate\Database\Eloquent\SoftDeletes::class);
    expect($product)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);

    // Test log attributes
    $logAttributes = new ReflectionClass($product)->getProperty('logAttributes')->getValue($product);
    expect($logAttributes)->toBeArray();
    expect($logAttributes)->toContain('type');
    expect($logAttributes)->toContain('name');
    expect($logAttributes)->toContain('price');
    expect($logAttributes)->toContain('cost');
    expect($logAttributes)->toHaveKey('parent.name');
    expect($logAttributes)->toHaveKey('category.name');
    expect($logAttributes)->toHaveKey('company.name');
    expect($logAttributes)->toHaveKey('creator.name');

    // Test sortable configuration
    $sortable = new ReflectionClass($product)->getProperty('sortable')->getValue($product);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}

/**
 * Test Product model casts
 *
 * This test verifies that the Product model's attribute casts are correctly defined,
 * ensuring that attributes are cast to the appropriate types (enum, boolean, array).
 */
#[Test]
#[Group('unit')]
#[Group('products')]
#[PluginTest('Products')]
#[CoversClass(Product::class)]
#[Description('Test Product model casts')]
function product_model_casts(): void
{
    // Create a test product
    $product = Product::factory()->create([
        'type' => ProductType::GOODS,
        'enable_sales' => true,
        'enable_purchase' => true,
        'is_favorite' => false,
        'is_configurable' => false,
        'images' => ['image1.jpg', 'image2.jpg'],
    ]);

    // Test casts
    expect($product->type)->toBeInstanceOf(ProductType::class);
    expect($product->enable_sales)->toBeBool();
    expect($product->enable_purchase)->toBeBool();
    expect($product->is_favorite)->toBeBool();
    expect($product->is_configurable)->toBeBool();
    expect($product->images)->toBeArray();
}
