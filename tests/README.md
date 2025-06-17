# AureusERP Testing Guide

This document provides an overview of the testing approach for AureusERP and instructions on how to run tests.

## Testing Approach

AureusERP uses [Pest PHP](https://pestphp.com/) for testing, which is a testing framework built on top of PHPUnit with a focus on simplicity and developer experience.

### Test Types

The test suite is organized into three main types of tests:

1. **Unit Tests** (`tests/Unit/`): Test individual components in isolation, focusing on a single class or function.
2. **Feature Tests** (`tests/Feature/`): Test features from an HTTP perspective, simulating requests to your application.
3. **Integration Tests** (`tests/Integration/`): Test the interaction between different components of the system.

### Test Structure

Tests are written using Pest's functional style with PHP attributes instead of PHPDoc comments. This makes the tests more concise and easier to read.

Example:

```php
#[Test]
#[Group('unit')]
#[Description('Test that basic arithmetic operations work correctly')]
function basic_arithmetic_operations()
{
    expect(1 + 1)->toBe(2);
}
```

### Test Coverage

The test suite is configured to generate coverage reports for both code coverage and type coverage. The coverage configuration excludes the `packages/` and `vendor/` directories as specified in the requirements.

## Running Tests

You can run tests using the following Composer scripts:

```bash
# Run all tests
composer test

# Run tests with coverage report
composer test:coverage

# Run tests with HTML coverage report
composer test:coverage-html

# Run tests in parallel
composer test:parallel

# Run tests with type coverage
composer test:type-coverage

# Run architecture tests
composer test:arch

# Run stress tests
composer test:stress

# Run unit tests only
composer test:unit

# Run feature tests only
composer test:feature

# Run integration tests only
composer test:integration
```

## Writing Tests

### Creating a New Test

1. Create a new PHP file in the appropriate test directory (`Unit`, `Feature`, or `Integration`).
2. Write your test using Pest's functional style with PHP attributes.

### Using Attributes

Pest supports the following attributes:

- `#[Test]`: Marks a function as a test.
- `#[Group('group-name')]`: Assigns the test to a group.
- `#[Description('Test description')]`: Provides a description for the test.
- `#[DataProvider('provider_function')]`: Specifies a data provider for the test.
- `#[Depends('another_test')]`: Specifies that this test depends on another test.
- `#[BeforeEach]`: Marks a function to run before each test.
- `#[AfterEach]`: Marks a function to run after each test.

### Example Test

```php
#[Test]
#[Group('feature')]
#[Description('Test that the home page loads successfully')]
function home_page_loads_successfully()
{
    $response = get('/');

    $response->assertStatus(200);
}
```

## Testing Utilities and Helpers

AureusERP provides a set of utilities and helpers to make testing easier and more consistent.

### Base TestCase Class

The `TestCase` class (`tests/TestCase.php`) provides a foundation for all tests in the AureusERP application. It includes utility methods for common operations, assertions, and test setup/teardown.

#### Key Methods

- **setUp()**: Clears the cache before each test
- **useRefreshDatabase()**: Resets the database after each test
- **useInMemoryDatabase()**: Configures an in-memory SQLite database for testing
- **createTestFile()**: Creates a test file in storage
- **deleteTestFile()**: Deletes a test file from storage
- **assertDatabaseHasTable()**: Asserts that a database has a given table
- **assertDatabaseDoesNotHaveTable()**: Asserts that a database does not have a given table
- **assertJsonStructure()**: Asserts that a JSON response has a given structure
- **assertModelHasAttributes()**: Asserts that a model has the expected attributes
- **assertModelHasRelationships()**: Asserts that a model has the expected relationships
- **assertObjectHasMethod()**: Asserts that an object has a method

### Testing Traits

#### API Testing Trait

The `ApiTestingTrait` (`tests/Traits/ApiTestingTrait.php`) provides methods for API testing, including making API requests and asserting responses.

```php
use Tests\Traits\ApiTestingTrait;

class ApiTest extends TestCase
{
    use ApiTestingTrait;

    public function test_api_endpoint()
    {
        // Make an API request
        $response = $this->getJson('users');

        // Assert response
        $this->assertSuccessful($response);
        $this->assertPaginated($response);
    }
}
```

#### Authentication Testing Trait

The `AuthenticationTestingTrait` (`tests/Traits/AuthenticationTestingTrait.php`) provides methods for authentication testing, including acting as different users and asserting authentication status.

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

#### Database Testing Trait

The `DatabaseTestingTrait` (`tests/Traits/DatabaseTestingTrait.php`) provides methods for database testing, including transaction management and database assertions.

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
        $user = $this->createRecord(User::class, [
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

### Test Helpers

The `TestHelpers` class (`tests/Helpers/TestHelpers.php`) provides static helper methods for generating test data and working with models.

```php
use Tests\Helpers\TestHelpers;

// Generate random data
$email = TestHelpers::randomEmail();
$date = TestHelpers::randomDate();

// Create a test file
$file = TestHelpers::createTestFile('test.txt');

// Get random models
$users = TestHelpers::getRandomModels(User::class, 5);

// Clean up
TestHelpers::cleanupTestFiles();
```

## Best Practices

1. **Use descriptive test names**: Test names should clearly describe what is being tested.
2. **One assertion per test**: Each test should focus on testing one specific behavior.
3. **Use data providers**: Use data providers to test multiple scenarios with the same test logic.
4. **Use appropriate test type**: Choose the appropriate test type (unit, feature, integration) based on what you're testing.
5. **Use attributes**: Use PHP attributes instead of PHPDoc comments for better readability and maintainability.
6. **Group tests**: Use the `#[Group]` attribute to organize tests into logical groups.
7. **Write testable code**: Design your code to be testable by following SOLID principles and dependency injection.
8. **Use the appropriate trait for your test type**: Use `ApiTestingTrait` for API tests, `AuthenticationTestingTrait` for authentication tests, and `DatabaseTestingTrait` for database tests.
9. **Combine traits as needed**: You can use multiple traits in a single test class to access different functionality.
10. **Use test helpers for generating test data**: Use the `TestHelpers` class to generate random test data and work with models.
11. **Clean up after your tests**: Use the appropriate methods to clean up files and database changes after your tests.
