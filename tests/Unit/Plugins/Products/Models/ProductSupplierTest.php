<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Partner\Models\Partner;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\ProductSupplier;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductSupplier model attributes and properties')]
function product_supplier_model_attributes_and_properties(): void
{
    // Create a test product supplier
    $productSupplier = ProductSupplier::factory()->create([
        'product_name' => 'Supplier Product Name',
        'product_code' => 'SUP001',
        'delay' => 5,
        'min_qty' => 10,
        'price' => 100.00,
        'discount' => 5.00,
        'starts_at' => now(),
        'ends_at' => now()->addMonths(6),
    ]);

    // Test attributes
    expect($productSupplier->product_name)->toBe('Supplier Product Name');
    expect($productSupplier->product_code)->toBe('SUP001');
    expect($productSupplier->delay)->toBe(5);
    expect($productSupplier->min_qty)->toBe(10);
    expect($productSupplier->price)->toBe(100.00);
    expect($productSupplier->discount)->toBe(5.00);
    expect($productSupplier->starts_at->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($productSupplier->ends_at->format('Y-m-d'))->toBe(now()->addMonths(6)->format('Y-m-d'));

    // Test relationships
    expect($productSupplier->product())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productSupplier->partner())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productSupplier->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productSupplier->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($productSupplier->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductSupplier model relationships with other models')]
function product_supplier_model_relationships_with_other_models(): void
{
    // Create related models
    $product = Product::factory()->create(['name' => 'Test Product']);
    $partner = Partner::factory()->create(['name' => 'Test Supplier']);
    $currency = Currency::factory()->create();
    $company = Company::factory()->create();
    $user = User::factory()->create();

    // Create a product supplier with relationships
    $productSupplier = ProductSupplier::factory()->create([
        'product_id' => $product->id,
        'partner_id' => $partner->id,
        'currency_id' => $currency->id,
        'company_id' => $company->id,
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($productSupplier->product->id)->toBe($product->id);
    expect($productSupplier->partner->id)->toBe($partner->id);
    expect($productSupplier->currency->id)->toBe($currency->id);
    expect($productSupplier->company->id)->toBe($company->id);
    expect($productSupplier->creator->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductSupplier model date range functionality')]
function product_supplier_model_date_range_functionality(): void
{
    // Create product suppliers with different date ranges
    $currentSupplier = ProductSupplier::factory()->create([
        'starts_at' => now()->subDays(10),
        'ends_at' => now()->addDays(10),
    ]);

    $pastSupplier = ProductSupplier::factory()->create([
        'starts_at' => now()->subDays(30),
        'ends_at' => now()->subDays(20),
    ]);

    $futureSupplier = ProductSupplier::factory()->create([
        'starts_at' => now()->addDays(20),
        'ends_at' => now()->addDays(30),
    ]);

    $noEndDateSupplier = ProductSupplier::factory()->create([
        'starts_at' => now()->subDays(10),
        'ends_at' => null,
    ]);

    // Test date casts
    expect($currentSupplier->starts_at)->toBeInstanceOf(Illuminate\Support\Carbon::class);
    expect($currentSupplier->ends_at)->toBeInstanceOf(Illuminate\Support\Carbon::class);

    // Test date range queries (these would typically be in a scope or repository)
    $currentDate = now();

    $activeSuppliers = ProductSupplier::query()
        ->where(function ($query) use ($currentDate): void {
            $query->where(function ($q) use ($currentDate): void {
                $q->where('starts_at', '<=', $currentDate)
                    ->where('ends_at', '>=', $currentDate);
            })->orWhere(function ($q) use ($currentDate): void {
                $q->where('starts_at', '<=', $currentDate)
                    ->whereNull('ends_at');
            });
        })
        ->get();

    expect($activeSuppliers)->toHaveCount(2);
    expect($activeSuppliers->contains($currentSupplier))->toBeTrue();
    expect($activeSuppliers->contains($noEndDateSupplier))->toBeTrue();
    expect($activeSuppliers->contains($pastSupplier))->toBeFalse();
    expect($activeSuppliers->contains($futureSupplier))->toBeFalse();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductSupplier model sortable trait')]
function product_supplier_model_sortable_trait(): void
{
    // Create product suppliers with different sort values
    $supplier1 = ProductSupplier::factory()->create(['sort' => 1]);
    ProductSupplier::factory()->create(['sort' => 2]);
    $supplier3 = ProductSupplier::factory()->create(['sort' => 3]);

    // Test that the model uses the SortableTrait
    expect($supplier1)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($supplier1)->getProperty('sortable')->getValue($supplier1);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();

    // Test ordered scope
    $orderedSuppliers = ProductSupplier::ordered()->get();
    expect($orderedSuppliers->first()->id)->toBe($supplier1->id);
    expect($orderedSuppliers->last()->id)->toBe($supplier3->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test ProductSupplier model with multiple suppliers for a product')]
function product_supplier_model_with_multiple_suppliers_for_a_product(): void
{
    // Create a product
    $product = Product::factory()->create(['name' => 'Test Product']);

    // Create multiple suppliers
    $supplier1 = Partner::factory()->create(['name' => 'Supplier 1']);
    $supplier2 = Partner::factory()->create(['name' => 'Supplier 2']);
    $supplier3 = Partner::factory()->create(['name' => 'Supplier 3']);

    // Create product suppliers for the product with different prices
    $productSupplier1 = ProductSupplier::factory()->create([
        'product_id' => $product->id,
        'partner_id' => $supplier1->id,
        'price' => 100.00,
        'min_qty' => 10,
        'sort' => 1,
    ]);

    $productSupplier2 = ProductSupplier::factory()->create([
        'product_id' => $product->id,
        'partner_id' => $supplier2->id,
        'price' => 95.00,
        'min_qty' => 20,
        'sort' => 2,
    ]);

    $productSupplier3 = ProductSupplier::factory()->create([
        'product_id' => $product->id,
        'partner_id' => $supplier3->id,
        'price' => 90.00,
        'min_qty' => 30,
        'sort' => 3,
    ]);

    // Test that all suppliers are associated with the product
    $productSuppliers = ProductSupplier::where('product_id', $product->id)->get();
    expect($productSuppliers)->toHaveCount(3);

    // Test that we can find the cheapest supplier
    $cheapestSupplier = $productSuppliers->sortBy('price')->first();
    expect($cheapestSupplier->id)->toBe($productSupplier3->id);
    expect($cheapestSupplier->price)->toBe(90.00);

    // Test that we can find suppliers that can fulfill a specific quantity
    $requiredQty = 25;
    $eligibleSuppliers = $productSuppliers->filter(fn ($supplier): bool => $supplier->min_qty <= $requiredQty);

    expect($eligibleSuppliers)->toHaveCount(2);
    expect($eligibleSuppliers->contains($productSupplier1))->toBeTrue();
    expect($eligibleSuppliers->contains($productSupplier2))->toBeTrue();
    expect($eligibleSuppliers->contains($productSupplier3))->toBeFalse();
}
