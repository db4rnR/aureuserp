<?php

declare(strict_types=1);

use PHPUnit\Framework\Attributes\Description;
use PHPUnit\Framework\Attributes\Group;
use Webkul\Product\Models\Product;
use Webkul\Product\Models\Tag;
use Webkul\Security\Models\User;

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Tag model attributes and properties')]
function tag_model_attributes_and_properties(): void
{
    // Create a test tag
    $tag = Tag::factory()->create([
        'name' => 'Test Tag',
        'color' => '#FF0000',
    ]);

    // Test attributes
    expect($tag->name)->toBe('Test Tag');
    expect($tag->color)->toBe('#FF0000');

    // Test relationships
    expect($tag->creator())->toBeInstanceOf(Illuminate\Database\Eloquent\Relations\BelongsTo::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Tag model relationships with other models')]
function tag_model_relationships_with_other_models(): void
{
    // Create related models
    $user = User::factory()->create();
    $product = Product::factory()->create(['name' => 'Test Product']);

    // Create a tag with relationships
    $tag = Tag::factory()->create([
        'creator_id' => $user->id,
        'name' => 'Test Tag',
    ]);

    // Attach the tag to a product
    $product->tags()->attach($tag);

    // Test relationships
    expect($tag->creator->id)->toBe($user->id);

    // We can't directly test the many-to-many relationship from the Tag side
    // because the products() method is not defined in the Tag model.
    // Instead, we can verify that the tag is attached to the product
    expect($product->tags->contains($tag))->toBeTrue();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Tag model traits')]
function tag_model_traits(): void
{
    // Create a test tag
    $tag = Tag::factory()->create();

    // Test that the model uses the expected traits
    expect($tag)->toBeInstanceOf(Illuminate\Database\Eloquent\SoftDeletes::class);
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Tag model soft delete functionality')]
function tag_model_soft_delete_functionality(): void
{
    // Create a test tag
    $tag = Tag::factory()->create([
        'name' => 'Test Tag',
    ]);

    // Get the ID for later retrieval
    $tagId = $tag->id;

    // Soft delete the tag
    $tag->delete();

    // Test that the tag is soft deleted
    expect(Tag::find($tagId))->toBeNull();
    expect(Tag::withTrashed()->find($tagId))->not->toBeNull();
    expect(Tag::withTrashed()->find($tagId)->trashed())->toBeTrue();

    // Restore the tag
    Tag::withTrashed()->find($tagId)->restore();

    // Test that the tag is restored
    expect(Tag::find($tagId))->not->toBeNull();
    expect(Tag::find($tagId)->trashed())->toBeFalse();
}

#[Test]
#[Group('unit')]
#[Group('products')]
#[Description('Test Tag model many-to-many relationship with products from the Product side')]
function tag_model_many_to_many_relationship_with_products_from_product_side(): void
{
    // Create a tag
    $tag = Tag::factory()->create(['name' => 'Test Tag']);

    // Create multiple products
    $product1 = Product::factory()->create(['name' => 'Product 1']);
    $product2 = Product::factory()->create(['name' => 'Product 2']);
    $product3 = Product::factory()->create(['name' => 'Product 3']);

    // Attach the tag to products
    $product1->tags()->attach($tag);
    $product2->tags()->attach($tag);

    // Test the many-to-many relationship from the Product side
    expect($product1->tags->contains($tag))->toBeTrue();
    expect($product2->tags->contains($tag))->toBeTrue();
    expect($product3->tags->contains($tag))->toBeFalse();

    // Test detaching
    $product1->tags()->detach($tag);

    // Refresh the products from the database
    $product1->refresh();
    $product2->refresh();

    // Verify the tag is detached from product1 but still attached to product2
    expect($product1->tags->contains($tag))->toBeFalse();
    expect($product2->tags->contains($tag))->toBeTrue();
}
