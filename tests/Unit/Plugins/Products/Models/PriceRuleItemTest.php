<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Enums\PriceRuleApplyTo;
use Webkul\Product\Enums\PriceRuleBase;
use Webkul\Product\Enums\PriceRuleType;
use Webkul\Product\Models\Category;
use Webkul\Product\Models\PriceRule;
use Webkul\Product\Models\PriceRuleItem;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;
use Webkul\Support\Models\Company;
use Webkul\Support\Models\Currency;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRuleItem model attributes and properties')]
function price_rule_item_model_attributes_and_properties(): void
{
    // Create a test price rule item
    $priceRuleItem = PriceRuleItem::factory()->create([
        'apply_to' => PriceRuleApplyTo::PRODUCT,
        'base' => PriceRuleBase::LIST_PRICE,
        'type' => PriceRuleType::PERCENTAGE,
        'min_quantity' => 10,
        'fixed_price' => 100.00,
        'price_discount' => 15.00,
        'price_round' => 0.99,
        'price_surcharge' => 5.00,
        'price_markup' => 10.00,
        'price_min_margin' => 5.00,
        'percent_price' => 20.00,
        'starts_at' => now(),
        'ends_at' => now()->addDays(30),
    ]);

    // Test attributes
    expect($priceRuleItem->apply_to)->toBe(PriceRuleApplyTo::PRODUCT);
    expect($priceRuleItem->base)->toBe(PriceRuleBase::LIST_PRICE);
    expect($priceRuleItem->type)->toBe(PriceRuleType::PERCENTAGE);
    expect($priceRuleItem->min_quantity)->toBe(10);
    expect($priceRuleItem->fixed_price)->toBe(100.00);
    expect($priceRuleItem->price_discount)->toBe(15.00);
    expect($priceRuleItem->price_round)->toBe(0.99);
    expect($priceRuleItem->price_surcharge)->toBe(5.00);
    expect($priceRuleItem->price_markup)->toBe(10.00);
    expect($priceRuleItem->price_min_margin)->toBe(5.00);
    expect($priceRuleItem->percent_price)->toBe(20.00);
    expect($priceRuleItem->starts_at->format('Y-m-d'))->toBe(now()->format('Y-m-d'));
    expect($priceRuleItem->ends_at->format('Y-m-d'))->toBe(now()->addDays(30)->format('Y-m-d'));

    // Test relationships
    expect($priceRuleItem->priceRule())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRuleItem->basePriceRule())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRuleItem->product())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRuleItem->category())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRuleItem->currency())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRuleItem->company())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($priceRuleItem->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRuleItem model relationships with other models')]
function price_rule_item_model_relationships_with_other_models(): void
{
    // Create related models
    $priceRule = PriceRule::factory()->create(['name' => 'Test Price Rule']);
    $basePriceRule = PriceRule::factory()->create(['name' => 'Base Price Rule']);
    $product = Product::factory()->create(['name' => 'Test Product']);
    $category = Category::factory()->create(['name' => 'Test Category']);
    $currency = Currency::factory()->create();
    $company = Company::factory()->create();
    $user = User::factory()->create();

    // Create a price rule item with relationships
    $priceRuleItem = PriceRuleItem::factory()->create([
        'price_rule_id' => $priceRule->id,
        'base_price_rule_id' => $basePriceRule->id,
        'product_id' => $product->id,
        'category_id' => $category->id,
        'currency_id' => $currency->id,
        'company_id' => $company->id,
        'creator_id' => $user->id,
        'apply_to' => PriceRuleApplyTo::PRODUCT,
    ]);

    // Test relationships
    expect($priceRuleItem->priceRule->id)->toBe($priceRule->id);
    expect($priceRuleItem->basePriceRule->id)->toBe($basePriceRule->id);
    expect($priceRuleItem->product->id)->toBe($product->id);
    expect($priceRuleItem->category->id)->toBe($category->id);
    expect($priceRuleItem->currency->id)->toBe($currency->id);
    expect($priceRuleItem->company->id)->toBe($company->id);
    expect($priceRuleItem->creator->id)->toBe($user->id);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRuleItem model enum casts')]
function price_rule_item_model_enum_casts(): void
{
    // Create price rule items with different enum values
    $productItem = PriceRuleItem::factory()->create(['apply_to' => PriceRuleApplyTo::PRODUCT]);
    $categoryItem = PriceRuleItem::factory()->create(['apply_to' => PriceRuleApplyTo::CATEGORY]);

    $listPriceItem = PriceRuleItem::factory()->create(['base' => PriceRuleBase::LIST_PRICE]);
    $standardPriceItem = PriceRuleItem::factory()->create(['base' => PriceRuleBase::STANDARD_PRICE]);
    $priceRulesItem = PriceRuleItem::factory()->create(['base' => PriceRuleBase::PRICE_RULES]);

    $percentageItem = PriceRuleItem::factory()->create(['type' => PriceRuleType::PERCENTAGE]);
    $formulaItem = PriceRuleItem::factory()->create(['type' => PriceRuleType::FORMULA]);
    $fixedItem = PriceRuleItem::factory()->create(['type' => PriceRuleType::FIXED]);

    // Test that the enums are correctly cast
    expect($productItem->apply_to)->toBeInstanceOf(PriceRuleApplyTo::class);
    expect($categoryItem->apply_to)->toBeInstanceOf(PriceRuleApplyTo::class);

    expect($listPriceItem->base)->toBeInstanceOf(PriceRuleBase::class);
    expect($standardPriceItem->base)->toBeInstanceOf(PriceRuleBase::class);
    expect($priceRulesItem->base)->toBeInstanceOf(PriceRuleBase::class);

    expect($percentageItem->type)->toBeInstanceOf(PriceRuleType::class);
    expect($formulaItem->type)->toBeInstanceOf(PriceRuleType::class);
    expect($fixedItem->type)->toBeInstanceOf(PriceRuleType::class);

    // Test enum values
    expect($productItem->apply_to)->toBe(PriceRuleApplyTo::PRODUCT);
    expect($categoryItem->apply_to)->toBe(PriceRuleApplyTo::CATEGORY);

    expect($listPriceItem->base)->toBe(PriceRuleBase::LIST_PRICE);
    expect($standardPriceItem->base)->toBe(PriceRuleBase::STANDARD_PRICE);
    expect($priceRulesItem->base)->toBe(PriceRuleBase::PRICE_RULES);

    expect($percentageItem->type)->toBe(PriceRuleType::PERCENTAGE);
    expect($formulaItem->type)->toBe(PriceRuleType::FORMULA);
    expect($fixedItem->type)->toBe(PriceRuleType::FIXED);

    // Test enum string values
    expect($productItem->apply_to->value)->toBe('product');
    expect($categoryItem->apply_to->value)->toBe('category');

    expect($listPriceItem->base->value)->toBe('list_price');
    expect($standardPriceItem->base->value)->toBe('standard_price');
    expect($priceRulesItem->base->value)->toBe('price_rules');

    expect($percentageItem->type->value)->toBe('percentage');
    expect($formulaItem->type->value)->toBe('formula');
    expect($fixedItem->type->value)->toBe('fixed');
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test PriceRuleItem model date casts')]
function price_rule_item_model_date_casts(): void
{
    // Create a price rule item with date fields
    $startDate = now()->subDays(5);
    $endDate = now()->addDays(30);

    $priceRuleItem = PriceRuleItem::factory()->create([
        'starts_at' => $startDate,
        'ends_at' => $endDate,
    ]);

    // Test that the dates are correctly cast to Carbon instances
    expect($priceRuleItem->starts_at)->toBeInstanceOf(Carbon\Carbon::class);
    expect($priceRuleItem->ends_at)->toBeInstanceOf(Carbon\Carbon::class);

    // Test date values
    expect($priceRuleItem->starts_at->format('Y-m-d'))->toBe($startDate->format('Y-m-d'));
    expect($priceRuleItem->ends_at->format('Y-m-d'))->toBe($endDate->format('Y-m-d'));
}
