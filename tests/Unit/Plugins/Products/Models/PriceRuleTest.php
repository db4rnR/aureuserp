<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\PriceRule;
use Webkul\Product\Models\PriceRuleItem;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRule model attributes and properties')]
function price_rule_model_attributes_and_properties(): void
{
    // Create a test price rule
    $priceRule = PriceRule::factory()->create([
        'name' => 'Test Price Rule',
        'sort' => 1,
    ]);

    // Test attributes
    expect($priceRule->name)->toBe('Test Price Rule');
    expect($priceRule->sort)->toBe(1);

    // Test relationships
    expect($priceRule->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRule->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRule->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRule->items())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRule model relationships with other models')]
function price_rule_model_relationships_with_other_models(): void
{
    // Create related models
    $currency = Currency::factory()->create();
    $company = Company::factory()->create();
    $user = User::factory()->create();

    // Create a price rule with relationships
    $priceRule = PriceRule::factory()->create([
        'currency_id' => $currency->id,
        'company_id' => $company->id,
        'creator_id' => $user->id,
        'name' => 'Test Price Rule',
    ]);

    // Create price rule items
    $item1 = PriceRuleItem::factory()->create([
        'price_rule_id' => $priceRule->id,
        'min_quantity' => 10,
    ]);

    $item2 = PriceRuleItem::factory()->create([
        'price_rule_id' => $priceRule->id,
        'min_quantity' => 20,
    ]);

    // Test relationships
    expect($priceRule->currency->id)->toBe($currency->id);
    expect($priceRule->company->id)->toBe($company->id);
    expect($priceRule->creator->id)->toBe($user->id);
    expect($priceRule->items->count())->toBe(2);
    expect($priceRule->items->contains($item1))->toBeTrue();
    expect($priceRule->items->contains($item2))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRule model traits and interfaces')]
function price_rule_model_traits_and_interfaces(): void
{
    // Create a test price rule
    $priceRule = PriceRule::factory()->create();

    // Test that the model uses the expected traits and implements interfaces
    expect($priceRule)->toBeInstanceOf(Spatie\EloquentSortable\Sortable::class);
    expect($priceRule)->toBeInstanceOf(Illuminate\Database\Eloquent\SoftDeletes::class);

    // Test sortable configuration
    $sortable = new ReflectionClass($priceRule)->getProperty('sortable')->getValue($priceRule);
    expect($sortable)->toBeArray();
    expect($sortable)->toHaveKey('order_column_name');
    expect($sortable['order_column_name'])->toBe('sort');
    expect($sortable)->toHaveKey('sort_when_creating');
    expect($sortable['sort_when_creating'])->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRule model soft delete functionality')]
function price_rule_model_soft_delete_functionality(): void
{
    // Create a test price rule
    $priceRule = PriceRule::factory()->create([
        'name' => 'Test Price Rule',
    ]);

    // Get the ID for later retrieval
    $priceRuleId = $priceRule->id;

    // Soft delete the price rule
    $priceRule->delete();

    // Test that the price rule is soft deleted
    expect(PriceRule::find($priceRuleId))->toBeNull();
    expect(PriceRule::withTrashed()->find($priceRuleId))->not->toBeNull();
    expect(PriceRule::withTrashed()->find($priceRuleId)->trashed())->toBeTrue();

    // Restore the price rule
    PriceRule::withTrashed()->find($priceRuleId)->restore();

    // Test that the price rule is restored
    expect(PriceRule::find($priceRuleId))->not->toBeNull();
    expect(PriceRule::find($priceRuleId)->trashed())->toBeFalse();
}
