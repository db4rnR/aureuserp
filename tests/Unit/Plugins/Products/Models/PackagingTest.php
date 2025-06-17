<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Product\Models\Packaging;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Packaging model attributes and properties')]
function packaging_model_attributes_and_properties()
{
    // Create a test packaging
    $packaging = Packaging::factory()->create([
        'name' => 'Test Packaging',
        'barcode' => '123456789',
        'qty' => 10,
        'sort' => 1,
    ]);

    // Test attributes
    expect($packaging->name)->toBe('Test Packaging');
    expect($packaging->barcode)->toBe('123456789');
    expect($packaging->qty)->toBe(10);
    expect($packaging->sort)->toBe(1);

    // Test relationships
    expect($packaging->product())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($packaging->company())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($packaging->creator())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Packaging model relationships with other models')]
function packaging_model_relationships_with_other_models()
{
    // Create related models
    $product = Product::factory()->create(['name' => 'Test Product']);
    $company = Company::factory()->create();
    $user = User::factory()->create();

    // Create a packaging with relationships
    $packaging = Packaging::factory()->create([
        'product_id' => $product->id,
        'company_id' => $company->id,
        'creator_id' => $user->id,
        'name' => 'Test Packaging',
    ]);

    // Test relationships
    expect($packaging->product->id)->toBe($product->id);
    expect($packaging->company->id)->toBe($company->id);
    expect($packaging->creator->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Packaging model traits and interfaces')]
function packaging_model_traits_and_interfaces()
{
    // Create a test packaging
    $packaging = Packaging::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($packaging)->toBeInstanceOf(\Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = (new \ReflectionClass($packaging))->getProperty('sortable')->getValue($packaging);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Packaging model with soft deleted product')]
function packaging_model_with_soft_deleted_product()
{
    // Create a product
    $product = Product::factory()->create(['name' => 'Test Product']);

    // Create a packaging for the product
    $packaging = Packaging::factory()->create([
        'product_id' => $product->id,
        'name' => 'Test Packaging',
    ]);

    // Soft delete the product
    $product->delete();

    // Refresh the packaging from the database
    $packaging->refresh();

    // Test that the packaging can still access the soft-deleted product
    // This is possible because of the withTrashed() scope in the relationship
    expect($packaging->product)->not->toBeNull();
    expect($packaging->product->id)->toBe($product->id);
    expect($packaging->product->trashed())->toBeTrue();
}
