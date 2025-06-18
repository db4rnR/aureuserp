<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\Category;
use Webkul\Product\Models\PriceRuleItem;
use Webkul\Product\Models\Product;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Category model attributes and properties')]
function category_model_attributes_and_properties(): void
{
    // Create a test category
    $category = Category::factory()->create([
        'name' => 'Test Category',
        'full_name' => 'Test Category',
        'parent_path' => '/',
    ]);

    // Test attributes
    expect($category->name)->toBe('Test Category');
    expect($category->full_name)->toBe('Test Category');
    expect($category->parent_path)->toBe('/');

    // Test relationships
    expect($category->parent())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($category->children())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($category->products())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
    expect($category->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
    expect($category->priceRuleItems())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\HasMany::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Category model relationships with other models')]
function category_model_relationships_with_other_models(): void
{
    // Create related models
    $user = User::factory()->create();
    $parentCategory = Category::factory()->create(['name' => 'Parent Category']);

    // Create a category with relationships
    $category = Category::factory()->create([
        'name' => 'Child Category',
        'parent_id' => $parentCategory->id,
        'creator_id' => $user->id,
    ]);

    // Create a product in this category
    $product = Product::factory()->create([
        'category_id' => $category->id,
    ]);

    // Create a price rule item for this category
    $priceRuleItem = PriceRuleItem::factory()->create([
        'category_id' => $category->id,
    ]);

    // Test relationships
    expect($category->parent->id)->toBe($parentCategory->id);
    expect($category->creator->id)->toBe($user->id);
    expect($category->products->count())->toBe(1);
    expect($category->products->first()->id)->toBe($product->id);
    expect($category->priceRuleItems->count())->toBe(1);
    expect($category->priceRuleItems->first()->id)->toBe($priceRuleItem->id);
    expect($parentCategory->children->contains($category))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Category model hierarchy and full_name generation')]
function category_model_hierarchy_and_full_name_generation(): void
{
    // Create a hierarchy of categories
    $grandparent = Category::factory()->create(['name' => 'Grandparent']);
    $parent = Category::factory()->create([
        'name' => 'Parent',
        'parent_id' => $grandparent->id,
    ]);
    $child = Category::factory()->create([
        'name' => 'Child',
        'parent_id' => $parent->id,
    ]);

    // Refresh from database to get the updated full_name
    $child->refresh();
    $parent->refresh();
    $grandparent->refresh();

    // Test parent_path and full_name
    expect($grandparent->parent_path)->toBe('/');
    expect($parent->parent_path)->toContain($grandparent->id);
    expect($child->parent_path)->toContain($parent->id);

    expect($grandparent->full_name)->toBe('Grandparent');
    expect($parent->full_name)->toBe('Grandparent / Parent');
    expect($child->full_name)->toBe('Grandparent / Parent / Child');
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Category model validateNoRecursion method')]
function category_model_validate_no_recursion_method(): void
{
    // Create a category
    $category = Category::factory()->create(['name' => 'Test Category']);

    // Try to set the category as its own parent
    $category->parent_id = $category->id;

    // This should throw an InvalidArgumentException
    expect(function () use ($category): void {
        $category->save();
    })->toThrow(InvalidArgumentException::class, 'Circular reference detected in product category hierarchy');
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Category model traits')]
function category_model_traits(): void
{
    // Create a test category
    $category = Category::factory()->create();

    // Test that the model uses the expected traits
    expect($category)->toBeInstanceOf(Webkul\Chatter\Traits\HasChatter::class);
    expect($category)->toBeInstanceOf(Webkul\Chatter\Traits\HasLogActivity::class);

    // Test log attributes
    $logAttributes = new ReflectionClass($category)->getProperty('logAttributes')->getValue($category);
    expect($logAttributes)->toBeArray();
    expect($logAttributes)->toContain('name');
    expect($logAttributes)->toContain('full_name');
    expect($logAttributes)->toContain('parent_path');
    expect($logAttributes)->toHaveKey('parent.name');
    expect($logAttributes)->toHaveKey('creator.name');
}
