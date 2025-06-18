<?php

declare(strict_types=1);

use Webkul\Invoice\Models\Category;
use Webkul\Invoice\Models\Product;
use Webkul\Product\Enums\ProductType;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test products listing page loads successfully')]
function products_listing_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the products listing page
    $response = get(route('filament.admin.customer.resources.product.index'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test product creation page loads successfully')]
function product_creation_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Act as the user
    actingAs($user);

    // Visit the product creation page
    $response = get(route('filament.admin.customer.resources.product.create'));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test product can be created successfully')]
function product_can_be_created_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create dependencies
    $company = Company::factory()->create();
    Currency::factory()->create();
    $category = Category::factory()->create();

    // Act as the user
    actingAs($user);

    // Prepare product data
    $productData = [
        'type' => ProductType::GOODS->value,
        'name' => 'Test Product',
        'reference' => 'PROD001',
        'barcode' => '123456789',
        'price' => 100.00,
        'cost' => 80.00,
        'description' => 'Test product description',
        'enable_sales' => true,
        'enable_purchase' => true,
        'company_id' => $company->id,
        'category_id' => $category->id,
    ];

    // Submit the product creation form
    $response = post(route('filament.admin.customer.resources.product.store'), $productData);

    // Assert the product was created successfully
    $response->assertRedirect(route('filament.admin.customer.resources.product.index'));

    // Assert the product exists in the database
    expect(Product::where('name', 'Test Product')
        ->where('reference', 'PROD001')
        ->exists())->toBeTrue();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test product can be viewed successfully')]
function product_can_be_viewed_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a product
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'reference' => 'PROD001',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the product view page
    $response = get(route('filament.admin.customer.resources.product.view', $product));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test product can be edited successfully')]
function product_can_be_edited_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a product
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'reference' => 'PROD001',
    ]);

    // Act as the user
    actingAs($user);

    // Visit the product edit page
    $response = get(route('filament.admin.customer.resources.product.edit', $product));

    // Assert the page loads successfully
    $response->assertSuccessful();

    // Prepare updated product data
    $updatedData = [
        'name' => 'Updated Product',
        'reference' => 'PROD002',
    ];

    // Submit the product edit form
    $response = patch(route('filament.admin.customer.resources.product.update', $product), $updatedData);

    // Assert the product was updated successfully
    $response->assertRedirect(route('filament.admin.customer.resources.product.view', $product));

    // Assert the product was updated in the database
    $updatedProduct = Product::find($product->id);
    expect($updatedProduct->name)->toBe('Updated Product');
    expect($updatedProduct->reference)->toBe('PROD002');
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test product attributes page loads successfully')]
function product_attributes_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a product
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'reference' => 'PROD001',
        'is_configurable' => true,
    ]);

    // Act as the user
    actingAs($user);

    // Visit the product attributes page
    $response = get(route('filament.admin.customer.resources.product.attributes', $product));

    // Assert the page loads successfully
    $response->assertSuccessful();
}

#[Test]
#[Group('feature')]
#[Group('invoices')]
#[Description('Test product variants page loads successfully')]
function product_variants_page_loads_successfully(): void
{
    // Create a user with appropriate permissions
    $user = User::factory()->create();

    // Create a product
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'reference' => 'PROD001',
        'is_configurable' => true,
    ]);

    // Act as the user
    actingAs($user);

    // Visit the product variants page
    $response = get(route('filament.admin.customer.resources.product.variants', $product));

    // Assert the page loads successfully
    $response->assertSuccessful();
}
