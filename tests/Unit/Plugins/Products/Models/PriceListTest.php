<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\PriceList;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceList model attributes and properties')]
function price_list_model_attributes_and_properties(): void
{
    // Create a test price list
    $priceList = PriceList::factory()->create([
        'name' => 'Test Price List',
        'is_active' => true,
        'sort' => 1,
    ]);

    // Test attributes
    expect($priceList->name)->toBe('Test Price List');
    expect($priceList->is_active)->toBeTrue();
    expect($priceList->sort)->toBe(1);

    // Test relationships
    expect($priceList->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceList->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceList->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceList model relationships with other models')]
function price_list_model_relationships_with_other_models(): void
{
    // Create related models
    $user = User::factory()->create();
    $company = Company::factory()->create();
    $currency = Currency::factory()->create();

    // Create a price list with relationships
    $priceList = PriceList::factory()->create([
        'currency_id' => $currency->id,
        'company_id' => $company->id,
        'creator_id' => $user->id,
    ]);

    // Test relationships
    expect($priceList->currency->id)->toBe($currency->id);
    expect($priceList->company->id)->toBe($company->id);
    expect($priceList->creator->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceList model traits and interfaces')]
function price_list_model_traits_and_interfaces(): void
{
    // Create a test price list
    $priceList = PriceList::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($priceList)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($priceList)->getProperty('sortable')->getValue($priceList);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}
