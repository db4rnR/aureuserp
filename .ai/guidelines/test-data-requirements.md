# Test Data Requirements and Assumptions

This document outlines the requirements and assumptions for test data in the testing framework. Proper test data is crucial for effective testing, and understanding the assumptions made about test data helps ensure tests are reliable and maintainable.

## General Test Data Principles

1. **Isolation**: Test data should be isolated from other tests to prevent interference.
2. **Relevance**: Test data should be relevant to the test case and represent realistic scenarios.
3. **Minimalism**: Use the minimum amount of test data necessary to test the functionality.
4. **Consistency**: Test data should be consistent across related tests.
5. **Reproducibility**: Test data should be reproducible to ensure tests can be run repeatedly with the same results.

## Test Data Sources

Tests can use several sources for test data:

### 1. Factories

Model factories are the preferred way to create test data. They provide a consistent way to create models with default values that can be overridden as needed.

```php
// Create a user with default values
$user = User::factory()->create();

// Create a user with specific values
$user = User::factory()->create([
    'name' => 'Test User',
    'email' => 'test@example.com',
]);

// Create multiple users
$users = User::factory()->count(3)->create();

// Create a user with a specific state
$admin = User::factory()->admin()->create();
```

### 2. Seeders

Database seeders can be used to populate the database with a standard set of data for testing. This is useful for tests that require a specific set of data to be present.

```php
// Run a specific seeder
$this->seed(UserSeeder::class);

// Run all seeders
$this->seed();
```

### 3. Test Helpers

The `TestHelpers` class provides methods for generating test data:

```php
// Generate random data
$email = TestHelpers::randomEmail();
$date = TestHelpers::randomDate();

// Create a test file
$file = TestHelpers::createTestFile('test.txt');

// Get random models
$users = TestHelpers::getRandomModels(User::class, 5);
```

### 4. In-Memory Data

For unit tests that don't require database interaction, in-memory data can be used:

```php
// Create an array of data
$data = [
    'name' => 'Test User',
    'email' => 'test@example.com',
];

// Create a mock model
$user = Mockery::mock(User::class);
$user->shouldReceive('getAttribute')->with('name')->andReturn('Test User');
```

## Test Data Requirements by Test Type

### Unit Tests

Unit tests should use minimal test data focused on the specific component being tested:

- **Models**: Use factories to create models with only the attributes needed for the test.
- **Services**: Use mock objects for dependencies and focus on the service's logic.
- **Repositories**: Use factories to create models and test the repository's methods.

### Feature Tests

Feature tests should use more comprehensive test data that represents realistic scenarios:

- **Controllers**: Use factories to create models and test the controller's actions.
- **API Endpoints**: Use factories to create models and test the API's responses.
- **Forms**: Use factories to create models and test form submissions.

### Integration Tests

Integration tests should use test data that exercises the interactions between components:

- **Component Interactions**: Use factories to create models and test how components interact.
- **Workflow Tests**: Use factories to create models and test complete workflows.

## Test Data Assumptions

When writing tests, the following assumptions are made about test data:

1. **Database State**: The database is empty at the start of each test unless explicitly seeded.
2. **Factory Definitions**: Factory definitions are up-to-date and create valid models.
3. **Relationships**: Model relationships are properly defined and can be used in tests.
4. **Transactions**: Tests run in database transactions that are rolled back after each test.
5. **Isolation**: Tests do not interfere with each other's data.

## Test Data for Specific Plugins

### Invoices Plugin

The Invoices plugin requires the following test data:

1. **Basic Entities**:
   - Customers and vendors (Partner model)
   - Products with pricing information
   - Payment terms
   - Journals for sales and purchases
   - Currencies

2. **Document Data**:
   - Invoices in various states (draft, posted, paid)
   - Credit notes
   - Payments
   - Refunds

3. **Edge Cases**:
   - Invoices with multiple tax rates
   - Invoices with discounts
   - Invoices in foreign currencies
   - Invoices with payment terms that include multiple installments

### Products Plugin

The Products plugin requires the following test data:

1. **Basic Entities**:
   - Product categories
   - Product attributes and options
   - Price lists
   - Products

2. **Complex Structures**:
   - Products with multiple attributes
   - Products with variants
   - Products with complex pricing rules
   - Products in multiple categories

### Payments Plugin

The Payments plugin requires the following test data:

1. **Basic Entities**:
   - Payment methods
   - Payment gateway configurations
   - Customers

2. **Transaction Data**:
   - Payments in various states
   - Payment transactions with different payment methods
   - Payment transactions with different currencies

## Creating Test Data Factories

When creating test data factories, follow these guidelines:

1. **Define Default Values**: Define sensible default values for all required attributes.
2. **Define States**: Define states for common variations of the model.
3. **Handle Relationships**: Define methods to create related models.
4. **Use Faker**: Use Faker to generate random data where appropriate.

Example factory definition:

```php
namespace Database\Factories;

use App\Models\User;
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

    public function withProfile()
    {
        return $this->has(Profile::factory(), 'profile');
    }
}
```

## Test Data Cleanup

Test data should be cleaned up after tests to prevent interference with other tests:

1. **Database Transactions**: Use database transactions to automatically roll back changes after each test.
2. **File Cleanup**: Clean up any files created during tests.
3. **Cache Cleanup**: Clear the cache after tests that modify cached data.

Example cleanup:

```php
protected function tearDown(): void
{
    // Clean up files
    TestHelpers::cleanupTestFiles();

    // Clear cache
    Cache::flush();

    parent::tearDown();
}
```

## Troubleshooting Test Data Issues

Common test data issues and how to resolve them:

1. **Test Interference**: If tests are interfering with each other, ensure they use database transactions and clean up after themselves.
2. **Invalid Data**: If tests are failing due to invalid data, check the factory definitions and ensure they create valid models.
3. **Missing Relationships**: If tests are failing due to missing relationships, ensure the factory definitions create related models as needed.
4. **Inconsistent Results**: If tests are producing inconsistent results, ensure they use fixed seeds for random data.

By following these guidelines, you can ensure that your tests have the data they need to effectively test the functionality of the system.
