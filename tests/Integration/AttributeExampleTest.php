<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Pest\Attributes\Test;
use Pest\Attributes\Group;
use Pest\Attributes\Description;
use Pest\Attributes\DataProvider;
use Pest\Attributes\BeforeEach;
use Pest\Attributes\AfterEach;

/**
 * Example of integration tests using attributes instead of PHPDoc comments.
 * These tests focus on testing the interaction between different components of the system.
 */

#[BeforeEach]
function setup_database()
{
    // Begin a database transaction
    DB::beginTransaction();
}

#[AfterEach]
function cleanup_database()
{
    // Roll back the transaction to clean up
    DB::rollBack();
}

#[Test]
#[Group('integration')]
#[Description('Test user creation and retrieval from database')]
function user_creation_and_retrieval()
{
    // Create a user
    $user = User::factory()->create([
        'name' => 'Integration Test User',
        'email' => 'integration_test@example.com',
    ]);

    // Retrieve the user from the database
    $retrievedUser = User::find($user->id);

    // Verify the user was retrieved correctly
    expect($retrievedUser)->not->toBeNull()
        ->and($retrievedUser->name)->toBe('Integration Test User')
        ->and($retrievedUser->email)->toBe('integration_test@example.com');
}

#[Test]
#[Group('integration')]
#[Description('Test relationship between users and their posts')]
function user_posts_relationship()
{
    // This is just an example. Adjust according to your actual models and relationships.

    // Create a user
    $user = User::factory()->create();

    // Create posts for the user
    $user->posts()->create([
        'title' => 'First Post',
        'content' => 'This is the first post content',
    ]);

    $user->posts()->create([
        'title' => 'Second Post',
        'content' => 'This is the second post content',
    ]);

    // Retrieve the user with posts
    $userWithPosts = User::with('posts')->find($user->id);

    // Verify the relationship
    expect($userWithPosts->posts)->toHaveCount(2)
        ->and($userWithPosts->posts[0]->title)->toBe('First Post')
        ->and($userWithPosts->posts[1]->title)->toBe('Second Post');
}

#[Test]
#[Group('integration')]
#[Description('Test database transactions and rollback')]
function database_transactions_and_rollback()
{
    // Create a user
    $user = User::factory()->create([
        'email' => 'transaction_test@example.com',
    ]);

    // Verify the user exists
    expect(User::where('email', 'transaction_test@example.com')->exists())->toBeTrue();

    // After the test, the transaction will be rolled back by the AfterEach hook
    // So the user should not exist in subsequent tests
}

#[Test]
#[Group('integration')]
#[Description('Test that the previous transaction was rolled back')]
function previous_transaction_was_rolled_back()
{
    // Verify the user from the previous test doesn't exist
    expect(User::where('email', 'transaction_test@example.com')->exists())->toBeFalse();
}

#[Test]
#[Group('integration')]
#[DataProvider('user_types_provider')]
#[Description('Test different user types and their capabilities')]
function user_types_and_capabilities($userType, $expectedCapabilities)
{
    // Create a user with the specified type
    // This is just an example. Adjust according to your actual user model and capabilities.
    $user = User::factory()->create([
        'type' => $userType,
    ]);

    // Get the user's capabilities
    $capabilities = $user->getCapabilities();

    // Verify the capabilities
    foreach ($expectedCapabilities as $capability) {
        expect($capabilities)->toContain($capability);
    }
}

function user_types_provider()
{
    return [
        'admin user has all capabilities' => ['admin', ['create', 'read', 'update', 'delete']],
        'editor user has limited capabilities' => ['editor', ['create', 'read', 'update']],
        'viewer user has minimal capabilities' => ['viewer', ['read']],
    ];
}
