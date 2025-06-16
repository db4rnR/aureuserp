<?php

use PHPUnit\Framework\Attributes\Group;
use PHPUnit\Framework\Attributes\Description;
use Webkul\Invoice\Models\Product;
use Webkul\Account\Models\Tax;
use Webkul\Support\Models\Company;
use Webkul\Security\Models\User;
use Webkul\Product\Models\Category;

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Product model attributes and relationships')]
function product_model_attributes_and_relationships()
{
    // Create a test product
    $product = Product::factory()->create([
        'name' => 'Test Product',
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
        'service_type' => 'manual',
        'invoice_policy' => 'order',
        'expense_policy' => 'no',
        'sale_line_warn' => 'no-message',
        'sale_line_warn_msg' => '',
        'sales_ok' => true,
        'purchase_ok' => true,
    ]);

    // Test attributes
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
    expect($product->service_type)->toBe('manual');
    expect($product->invoice_policy)->toBe('order');
    expect($product->expense_policy)->toBe('no');
    expect($product->sale_line_warn)->toBe('no-message');
    expect($product->sale_line_warn_msg)->toBe('');
    expect($product->sales_ok)->toBeTrue();
    expect($product->purchase_ok)->toBeTrue();

    // Test relationships
    expect($product->company())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->createdBy())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->category())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($product->productTaxes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
    expect($product->supplierTaxes())->toBeInstanceOf(\Illuminate\Database\Eloquent\Relations\BelongsToMany::class);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Product model relationships with other models')]
function product_model_relationships_with_other_models()
{
    // Create related models
    $company = Company::factory()->create();
    $user = User::factory()->create();
    $category = Category::factory()->create();
    $tax1 = Tax::factory()->create();
    $tax2 = Tax::factory()->create();

    // Create a product with relationships
    $product = Product::factory()->create([
        'company_id' => $company->id,
        'creator_id' => $user->id,
        'category_id' => $category->id,
    ]);

    // Attach taxes
    $product->productTaxes()->attach($tax1);
    $product->supplierTaxes()->attach($tax2);

    // Test relationships
    expect($product->company->id)->toBe($company->id);
    expect($product->createdBy->id)->toBe($user->id);
    expect($product->category->id)->toBe($category->id);
    expect($product->productTaxes->first()->id)->toBe($tax1->id);
    expect($product->supplierTaxes->first()->id)->toBe($tax2->id);
}

#[Test]
#[Group('unit')]
#[Group('invoices')]
#[Description('Test Product model traits')]
function product_model_traits()
{
    // Create a test product
    $product = Product::factory()->create();

    // Test that the model uses the expected traits
    expect($product)->toBeInstanceOf(\Webkul\Chatter\Traits\HasChatter::class);
    expect($product)->toBeInstanceOf(\Webkul\Field\Traits\HasCustomFields::class);
    expect($product)->toBeInstanceOf(\Webkul\Chatter\Traits\HasLogActivity::class);

    // Test log attributes
    $logAttributes = (new \ReflectionClass($product))->getProperty('logAttributes')->getValue($product);
    expect($logAttributes)->toBeArray();
    expect($logAttributes)->toContain('name');
    expect($logAttributes)->toContain('type');
    expect($logAttributes)->toContain('price');
    expect($logAttributes)->toContain('cost');
    expect($logAttributes)->toHaveKey('parent.name');
    expect($logAttributes)->toHaveKey('category.name');
    expect($logAttributes)->toHaveKey('company.name');
    expect($logAttributes)->toHaveKey('creator.name');
}
