# Test Helpers and Utilities

This document provides documentation for the test helpers and utilities available in the AureusERP testing framework. These helpers and utilities make it easier to write tests by providing common functionality and reducing boilerplate code.

## Table of Contents

1. [Introduction](#introduction)
2. [Base TestCase Class](#base-testcase-class)
3. [Testing Traits](#testing-traits)
4. [Test Helpers Class](#test-helpers-class)
5. [Factory Helpers](#factory-helpers)
6. [Assertion Helpers](#assertion-helpers)
7. [Creating Custom Helpers](#creating-custom-helpers)
8. [Best Practices](#best-practices)

## Introduction

AureusERP provides a set of helpers and utilities to make testing easier and more consistent. These include:

- **Base TestCase Class**: Provides common functionality for all tests
- **Testing Traits**: Provide specific functionality for different types of tests
- **Test Helpers Class**: Provides static helper methods for common testing tasks
- **Factory Helpers**: Make it easier to create test data
- **Assertion Helpers**: Make it easier to assert conditions in tests

## Base TestCase Class

The `TestCase` class (`tests/TestCase.php`) is the base class for all tests in AureusERP. It extends Laravel's `TestCase` class and provides additional functionality.

### Key Methods

#### Setup and Teardown

```php
/**
 * Set up the test environment.
 */
protected function setUp(): void
{
    parent::setUp();
    
    // Clear the cache before each test
    Cache::flush();
}

/**
 * Clean up after the test.
 */
protected function tearDown(): void
{
    // Clean up any test files
    $this->cleanupTestFiles();
    
    parent::tearDown();
}
```

#### Database Helpers

```php
/**
 * Use a refresh database approach for testing.
 * This will reset the database after each test.
 */
protected function useRefreshDatabase(): void
{
    $this->refreshDatabase();
}

/**
 * Use an in-memory SQLite database for testing.
 * This is faster than using a real database.
 */
protected function useInMemoryDatabase(): void
{
    $this->app['config']->set('database.default', 'sqlite');
    $this->app['config']->set('database.connections.sqlite', [
        'driver' => 'sqlite',
        'database' => ':memory:',
        'prefix' => '',
    ]);
}

/**
 * Begin a database transaction.
 * This is useful for tests that need to make database changes
 * but don't want to affect other tests.
 */
protected function beginDatabaseTransaction(): void
{
    $this->app['db']->beginTransaction();
}

/**
 * Rollback a database transaction.
 */
protected function rollbackDatabaseTransaction(): void
{
    $this->app['db']->rollBack();
}
```

#### File Helpers

```php
/**
 * Create a test file in the storage directory.
 *
 * @param string $filename The name of the file to create
 * @param string $content The content to write to the file
 * @return string The path to the created file
 */
protected function createTestFile(string $filename, string $content = 'Test content'): string
{
    $path = storage_path('testing/' . $filename);
    $directory = dirname($path);
    
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    file_put_contents($path, $content);
    
    return $path;
}

/**
 * Delete a test file from the storage directory.
 *
 * @param string $filename The name of the file to delete
 * @return bool True if the file was deleted, false otherwise
 */
protected function deleteTestFile(string $filename): bool
{
    $path = storage_path('testing/' . $filename);
    
    if (file_exists($path)) {
        return unlink($path);
    }
    
    return false;
}

/**
 * Clean up all test files in the storage directory.
 */
protected function cleanupTestFiles(): void
{
    $directory = storage_path('testing');
    
    if (is_dir($directory)) {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        
        rmdir($directory);
    }
}
```

#### Assertion Helpers

```php
/**
 * Assert that a database has a given table.
 *
 * @param string $table The name of the table
 * @param string|null $connection The name of the database connection
 */
protected function assertDatabaseHasTable(string $table, ?string $connection = null): void
{
    $connection = $connection ?: $this->app['config']->get('database.default');
    $schema = $this->app['db']->connection($connection)->getSchemaBuilder();
    
    $this->assertTrue($schema->hasTable($table), "Table {$table} does not exist.");
}

/**
 * Assert that a database does not have a given table.
 *
 * @param string $table The name of the table
 * @param string|null $connection The name of the database connection
 */
protected function assertDatabaseDoesNotHaveTable(string $table, ?string $connection = null): void
{
    $connection = $connection ?: $this->app['config']->get('database.default');
    $schema = $this->app['db']->connection($connection)->getSchemaBuilder();
    
    $this->assertFalse($schema->hasTable($table), "Table {$table} exists.");
}

/**
 * Assert that a JSON response has a given structure.
 *
 * @param \Illuminate\Testing\TestResponse $response The response to check
 * @param array $structure The expected structure
 */
protected function assertJsonStructure(\Illuminate\Testing\TestResponse $response, array $structure): void
{
    $response->assertJsonStructure($structure);
}

/**
 * Assert that a model has the expected attributes.
 *
 * @param \Illuminate\Database\Eloquent\Model $model The model to check
 * @param array $attributes The expected attributes
 */
protected function assertModelHasAttributes(\Illuminate\Database\Eloquent\Model $model, array $attributes): void
{
    foreach ($attributes as $key => $value) {
        $this->assertEquals($value, $model->{$key}, "Model attribute {$key} does not match expected value.");
    }
}

/**
 * Assert that a model has the expected relationships.
 *
 * @param \Illuminate\Database\Eloquent\Model $model The model to check
 * @param array $relationships The expected relationships
 */
protected function assertModelHasRelationships(\Illuminate\Database\Eloquent\Model $model, array $relationships): void
{
    foreach ($relationships as $relationship => $type) {
        $this->assertTrue(method_exists($model, $relationship), "Model does not have relationship {$relationship}.");
        $this->assertInstanceOf($type, $model->{$relationship}(), "Relationship {$relationship} is not of type {$type}.");
    }
}

/**
 * Assert that an object has a method.
 *
 * @param object $object The object to check
 * @param string $method The method to check for
 */
protected function assertObjectHasMethod(object $object, string $method): void
{
    $this->assertTrue(method_exists($object, $method), "Object does not have method {$method}.");
}
```

## Testing Traits

AureusERP provides several traits that can be used to add specific functionality to your tests.

### API Testing Trait

The `ApiTestingTrait` (`tests/Traits/ApiTestingTrait.php`) provides methods for testing API endpoints.

```php
use Tests\Traits\ApiTestingTrait;

class ApiTest extends TestCase
{
    use ApiTestingTrait;
    
    public function test_api_endpoint()
    {
        // Make an API request
        $response = $this->getJson('api/products');
        
        // Assert response
        $this->assertSuccessful($response);
        $this->assertPaginated($response);
    }
}
```

#### Key Methods

```php
/**
 * Get the authentication headers for API requests.
 *
 * @return array The authentication headers
 */
protected function getAuthHeaders(): array
{
    $token = $this->getAuthToken();
    
    return [
        'Authorization' => 'Bearer ' . $token,
        'Accept' => 'application/json',
    ];
}

/**
 * Get an authentication token for API requests.
 *
 * @return string The authentication token
 */
protected function getAuthToken(): string
{
    // Implementation depends on your authentication system
}

/**
 * Assert that a response is successful.
 *
 * @param \Illuminate\Testing\TestResponse $response The response to check
 */
protected function assertSuccessful(\Illuminate\Testing\TestResponse $response): void
{
    $response->assertSuccessful();
}

/**
 * Assert that a response is paginated.
 *
 * @param \Illuminate\Testing\TestResponse $response The response to check
 */
protected function assertPaginated(\Illuminate\Testing\TestResponse $response): void
{
    $response->assertJsonStructure([
        'data',
        'links' => ['first', 'last', 'prev', 'next'],
        'meta' => ['current_page', 'from', 'last_page', 'path', 'per_page', 'to', 'total'],
    ]);
}
```

### Authentication Testing Trait

The `AuthenticationTestingTrait` (`tests/Traits/AuthenticationTestingTrait.php`) provides methods for testing authentication.

```php
use Tests\Traits\AuthenticationTestingTrait;

class AuthTest extends TestCase
{
    use AuthenticationTestingTrait;
    
    public function test_authenticated_user_can_access_protected_route()
    {
        // Act as a user
        $this->actingAs();
        
        // Make a request with authentication
        $response = $this->get('/protected-route', $this->getAuthHeaders());
        
        // Assert response
        $response->assertSuccessful();
        $this->assertAuthenticated();
    }
}
```

#### Key Methods

```php
/**
 * Act as a user with the given role.
 *
 * @param string|null $role The role to assign to the user
 * @return \Webkul\Security\Models\User The user
 */
protected function actingAs(?string $role = null): \Webkul\Security\Models\User
{
    $user = \Webkul\Security\Models\User::factory()->create();
    
    if ($role) {
        $role = \Webkul\Security\Models\Role::where('name', $role)->first()
            ?? \Webkul\Security\Models\Role::factory()->create(['name' => $role]);
        
        $user->roles()->attach($role);
    }
    
    $this->actingAs($user);
    
    return $user;
}

/**
 * Assert that the user is authenticated.
 */
protected function assertAuthenticated(): void
{
    $this->assertTrue(auth()->check(), 'User is not authenticated.');
}

/**
 * Assert that the user is not authenticated.
 */
protected function assertNotAuthenticated(): void
{
    $this->assertFalse(auth()->check(), 'User is authenticated.');
}

/**
 * Assert that the user has a given role.
 *
 * @param string $role The role to check for
 */
protected function assertHasRole(string $role): void
{
    $user = auth()->user();
    
    $this->assertTrue(
        $user->roles()->where('name', $role)->exists(),
        "User does not have role {$role}."
    );
}
```

### Database Testing Trait

The `DatabaseTestingTrait` (`tests/Traits/DatabaseTestingTrait.php`) provides methods for testing database operations.

```php
use Tests\Traits\DatabaseTestingTrait;

class DatabaseTest extends TestCase
{
    use DatabaseTestingTrait;
    
    public function test_database_operations()
    {
        // Use in-memory database
        $this->useInMemoryDatabase();
        
        // Begin transaction
        $this->beginDatabaseTransaction();
        
        // Create records
        $user = $this->createRecord(\Webkul\Security\Models\User::class, [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Assert model attributes
        $this->assertModelAttributes($user, [
            'name' => 'Test User',
            'email' => 'test@example.com',
        ]);
        
        // Rollback transaction
        $this->rollbackDatabaseTransaction();
    }
}
```

#### Key Methods

```php
/**
 * Create a record in the database.
 *
 * @param string $model The model class
 * @param array $attributes The attributes for the model
 * @return \Illuminate\Database\Eloquent\Model The created model
 */
protected function createRecord(string $model, array $attributes = []): \Illuminate\Database\Eloquent\Model
{
    return $model::factory()->create($attributes);
}

/**
 * Assert that a model has the expected attributes.
 *
 * @param \Illuminate\Database\Eloquent\Model $model The model to check
 * @param array $attributes The expected attributes
 */
protected function assertModelAttributes(\Illuminate\Database\Eloquent\Model $model, array $attributes): void
{
    foreach ($attributes as $key => $value) {
        $this->assertEquals($value, $model->{$key}, "Model attribute {$key} does not match expected value.");
    }
}

/**
 * Assert that a database has a record with the given attributes.
 *
 * @param string $table The table to check
 * @param array $attributes The attributes to check for
 */
protected function assertDatabaseHasRecord(string $table, array $attributes): void
{
    $this->assertDatabaseHas($table, $attributes);
}

/**
 * Assert that a database does not have a record with the given attributes.
 *
 * @param string $table The table to check
 * @param array $attributes The attributes to check for
 */
protected function assertDatabaseDoesNotHaveRecord(string $table, array $attributes): void
{
    $this->assertDatabaseMissing($table, $attributes);
}
```

## Test Helpers Class

The `TestHelpers` class (`tests/Helpers/TestHelpers.php`) provides static helper methods for common testing tasks.

```php
use Tests\Helpers\TestHelpers;

// Generate random data
$email = TestHelpers::randomEmail();
$date = TestHelpers::randomDate();

// Create a test file
$file = TestHelpers::createTestFile('test.txt');

// Get random models
$users = TestHelpers::getRandomModels(\Webkul\Security\Models\User::class, 5);

// Clean up
TestHelpers::cleanupTestFiles();
```

### Key Methods

```php
/**
 * Generate a random email address.
 *
 * @return string A random email address
 */
public static function randomEmail(): string
{
    return 'test_' . uniqid() . '@example.com';
}

/**
 * Generate a random date.
 *
 * @param string|null $format The format for the date
 * @param string|null $min The minimum date
 * @param string|null $max The maximum date
 * @return string A random date
 */
public static function randomDate(?string $format = 'Y-m-d', ?string $min = '-1 year', ?string $max = 'now'): string
{
    $timestamp = mt_rand(strtotime($min), strtotime($max));
    
    return date($format, $timestamp);
}

/**
 * Create a test file in the storage directory.
 *
 * @param string $filename The name of the file to create
 * @param string $content The content to write to the file
 * @return string The path to the created file
 */
public static function createTestFile(string $filename, string $content = 'Test content'): string
{
    $path = storage_path('testing/' . $filename);
    $directory = dirname($path);
    
    if (!is_dir($directory)) {
        mkdir($directory, 0755, true);
    }
    
    file_put_contents($path, $content);
    
    return $path;
}

/**
 * Get random models from the database.
 *
 * @param string $model The model class
 * @param int $count The number of models to get
 * @return \Illuminate\Database\Eloquent\Collection The models
 */
public static function getRandomModels(string $model, int $count = 1): \Illuminate\Database\Eloquent\Collection
{
    $models = $model::inRandomOrder()->limit($count)->get();
    
    if ($models->count() < $count) {
        $models = $models->merge($model::factory()->count($count - $models->count())->create());
    }
    
    return $models;
}

/**
 * Clean up all test files in the storage directory.
 */
public static function cleanupTestFiles(): void
{
    $directory = storage_path('testing');
    
    if (is_dir($directory)) {
        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($directory, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::CHILD_FIRST
        );
        
        foreach ($files as $file) {
            if ($file->isDir()) {
                rmdir($file->getRealPath());
            } else {
                unlink($file->getRealPath());
            }
        }
        
        rmdir($directory);
    }
}
```

## Factory Helpers

AureusERP provides factory helpers to make it easier to create test data. These are defined in the `database/factories` directory.

### Using Factories

```php
// Create a user with default values
$user = \Webkul\Security\Models\User::factory()->create();

// Create a user with specific values
$user = \Webkul\Security\Models\User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
]);

// Create multiple users
$users = \Webkul\Security\Models\User::factory()->count(3)->create();

// Create a user with a specific state
$admin = \Webkul\Security\Models\User::factory()->admin()->create();

// Create a user with relationships
$user = \Webkul\Security\Models\User::factory()
    ->has(\Webkul\Security\Models\Role::factory()->count(2), 'roles')
    ->create();
```

### Creating Custom Factory States

```php
namespace Database\Factories;

use Webkul\Security\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;
    
    public function definition()
    {
        return [
            'name' => $this->faker->name(),
            'email' => $this->faker->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => bcrypt('password'),
            'remember_token' => Str::random(10),
        ];
    }
    
    public function admin()
    {
        return $this->state(function (array $attributes) {
            return [
                'is_admin' => true,
            ];
        });
    }
    
    public function withRoles(array $roles = [])
    {
        return $this->afterCreating(function (User $user) use ($roles) {
            $roles = empty($roles)
                ? \Webkul\Security\Models\Role::factory()->count(2)->create()
                : \Webkul\Security\Models\Role::whereIn('name', $roles)->get();
            
            $user->roles()->attach($roles);
        });
    }
}
```

## Assertion Helpers

AureusERP provides assertion helpers to make it easier to assert conditions in tests. These are defined in the `TestCase` class and in testing traits.

### Using Assertion Helpers

```php
// Assert that a model has the expected attributes
$this->assertModelHasAttributes($user, [
    'name' => 'Test User',
    'email' => 'test@example.com',
]);

// Assert that a model has the expected relationships
$this->assertModelHasRelationships($user, [
    'roles' => \Illuminate\Database\Eloquent\Relations\BelongsToMany::class,
    'profile' => \Illuminate\Database\Eloquent\Relations\HasOne::class,
]);

// Assert that a database has a record with the given attributes
$this->assertDatabaseHasRecord('users', [
    'name' => 'Test User',
    'email' => 'test@example.com',
]);

// Assert that a response is successful and paginated
$response = $this->getJson('api/users');
$this->assertSuccessful($response);
$this->assertPaginated($response);
```

## Creating Custom Helpers

You can create custom helpers to make your tests more readable and maintainable. Here's how to create a custom helper:

### Creating a Custom Test Trait

```php
namespace Tests\Traits;

trait CustomTestingTrait
{
    /**
     * Create a test product with the given attributes.
     *
     * @param array $attributes The attributes for the product
     * @return \Webkul\Product\Models\Product The created product
     */
    protected function createTestProduct(array $attributes = []): \Webkul\Product\Models\Product
    {
        return \Webkul\Product\Models\Product::factory()->create(array_merge([
            'name' => 'Test Product',
            'sku' => 'TEST-' . uniqid(),
            'price' => 100.00,
        ], $attributes));
    }
    
    /**
     * Create a test order with the given products.
     *
     * @param \Webkul\Product\Models\Product[] $products The products to include in the order
     * @param array $attributes The attributes for the order
     * @return \Webkul\Order\Models\Order The created order
     */
    protected function createTestOrder(array $products, array $attributes = []): \Webkul\Order\Models\Order
    {
        $customer = \Webkul\Partner\Models\Partner::factory()->create();
        
        $order = \Webkul\Order\Models\Order::factory()->create(array_merge([
            'customer_id' => $customer->id,
        ], $attributes));
        
        foreach ($products as $product) {
            \Webkul\Order\Models\OrderItem::factory()->create([
                'order_id' => $order->id,
                'product_id' => $product->id,
                'quantity' => 1,
                'price' => $product->price,
            ]);
        }
        
        return $order->fresh();
    }
}
```

### Using the Custom Test Trait

```php
use Tests\Traits\CustomTestingTrait;

class OrderTest extends TestCase
{
    use CustomTestingTrait;
    
    public function test_order_creation()
    {
        // Create test products
        $products = [
            $this->createTestProduct(['name' => 'Product A', 'price' => 100.00]),
            $this->createTestProduct(['name' => 'Product B', 'price' => 200.00]),
        ];
        
        // Create a test order
        $order = $this->createTestOrder($products);
        
        // Assert the order was created correctly
        $this->assertEquals(2, $order->items->count());
        $this->assertEquals(300.00, $order->total);
    }
}
```

## Best Practices

1. **Use the Right Helper for the Job**: Choose the appropriate helper for the task at hand. For example, use the `ApiTestingTrait` for API tests and the `DatabaseTestingTrait` for database tests.

2. **Keep Helpers Focused**: Each helper should have a single responsibility. If a helper is doing too much, consider splitting it into multiple helpers.

3. **Document Helpers**: Document your helpers with PHPDoc comments to make it clear what they do and how to use them.

4. **Test Your Helpers**: Write tests for your helpers to ensure they work correctly.

5. **Use Descriptive Names**: Use descriptive names for your helpers to make it clear what they do.

6. **Avoid Duplication**: If you find yourself writing the same code in multiple tests, consider creating a helper for it.

7. **Keep Helpers Simple**: Helpers should be simple and easy to understand. If a helper is too complex, consider refactoring it.

8. **Use Type Hints**: Use type hints to make it clear what types of parameters a helper expects and what type of value it returns.

9. **Use Default Values**: Use default values for parameters to make helpers more flexible.

10. **Use Method Chaining**: Use method chaining to make helpers more readable and to allow for fluent interfaces.
