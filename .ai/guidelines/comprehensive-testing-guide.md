# Comprehensive Testing Guide for AureusERP

This guide provides a complete overview of testing practices, standards, and tools for the AureusERP project. It serves as the main entry point for all testing-related documentation.

## Table of Contents

1. [Introduction](#introduction)
2. [Testing Philosophy](#testing-philosophy)
3. [Test Types](#test-types)
4. [Test Categories](#test-categories)
5. [Test Data](#test-data)
6. [Plugin-Specific Testing](#plugin-specific-testing)
7. [Test Examples](#test-examples)
8. [Test Coverage](#test-coverage)
9. [Test Performance](#test-performance)
10. [Troubleshooting](#troubleshooting)
11. [References](#references)

## Introduction

Testing is a critical part of the AureusERP development process. It ensures that the application functions correctly, meets requirements, and maintains quality over time. This guide outlines the testing practices, standards, and tools used in the AureusERP project.

### Purpose of Testing

- **Verify Functionality**: Ensure that the application functions as expected
- **Prevent Regressions**: Catch issues before they reach production
- **Document Behavior**: Tests serve as executable documentation
- **Improve Design**: Testing encourages better code design
- **Enable Refactoring**: Tests provide confidence when refactoring code

### Testing Tools

AureusERP uses the following testing tools:

- **Pest PHP**: A testing framework built on top of PHPUnit with a focus on simplicity
- **Laravel Testing Utilities**: Laravel's built-in testing utilities
- **Mockery**: A mocking framework for PHP
- **Larastan**: A static analysis tool for Laravel
- **Infection PHP**: A mutation testing framework
- **Codecov**: A code coverage reporting tool

## Testing Philosophy

Our testing philosophy is guided by the following principles:

1. **Test Behavior, Not Implementation**: Focus on testing what the code does, not how it does it
2. **Test at the Right Level**: Use the appropriate test type for the functionality being tested
3. **Keep Tests Fast**: Tests should run quickly to encourage frequent testing
4. **Keep Tests Independent**: Tests should not depend on each other
5. **Keep Tests Readable**: Tests should be easy to understand
6. **Keep Tests Maintainable**: Tests should be easy to maintain

## Test Types

AureusERP uses three main types of tests:

### Unit Tests

Unit tests focus on testing individual components in isolation. They are fast, focused, and help ensure that each component works correctly on its own.

**Location**: `tests/Unit/`

**When to Use**: Use unit tests for:
- Testing models
- Testing services
- Testing repositories
- Testing utility classes

**Example**:
```php
#[Test]
#[Group('unit')]
#[Group('products')]
function product_has_correct_attributes()
{
    $product = Product::factory()->create([
        'name' => 'Test Product',
        'price' => 100.00,
    ]);
    
    expect($product->name)->toBe('Test Product');
    expect($product->price)->toBe(100.00);
}
```

For more examples, see the [Test Examples](#test-examples) section.

### Feature Tests

Feature tests focus on testing features from an HTTP perspective. They test how different components work together to deliver a feature.

**Location**: `tests/Feature/`

**When to Use**: Use feature tests for:
- Testing controllers
- Testing API endpoints
- Testing form submissions
- Testing authentication and authorization

**Example**:
```php
#[Test]
#[Group('feature')]
#[Group('products')]
#[Group('api')]
function product_api_returns_list_of_products()
{
    $products = Product::factory()->count(3)->create();
    
    $response = $this->getJson('/api/products');
    
    $response->assertStatus(200);
    $response->assertJsonCount(3, 'data');
}
```

For more examples, see the [Test Examples](#test-examples) section.

### Integration Tests

Integration tests focus on testing how different components work together. They are more focused than feature tests but test more than a single unit.

**Location**: `tests/Integration/`

**When to Use**: Use integration tests for:
- Testing service interactions
- Testing repository interactions
- Testing event handling
- Testing complex workflows

**Example**:
```php
#[Test]
#[Group('integration')]
#[Group('products')]
function product_service_creates_product_with_categories()
{
    $categories = Category::factory()->count(2)->create();
    $categoryIds = $categories->pluck('id')->toArray();
    
    $productData = [
        'name' => 'Test Product',
        'price' => 100.00,
        'category_ids' => $categoryIds,
    ];
    
    $service = app(ProductService::class);
    $product = $service->create($productData);
    
    expect($product->categories)->toHaveCount(2);
}
```

For more examples, see the [Test Examples](#test-examples) section.

## Test Categories

Tests in AureusERP are categorized using PHP attributes with the `#[Group]` attribute. These categories help organize tests and make it easier to run specific groups of tests.

For a complete list of test categories and how to use them, see the [Test Categories Guidelines](test-categories.md).

### Running Tests by Category

Tests can be run by category using the `--group` option with Pest:

```bash
# Run all tests in the 'api' group
./vendor/bin/pest --group=api

# Run all tests in the 'database' group
./vendor/bin/pest --group=database

# Run all tests in both 'api' and 'database' groups
./vendor/bin/pest --group=api,database
```

We've also added convenient Composer scripts for common categories:

```bash
# Run all unit tests
composer test:unit

# Run all feature tests
composer test:feature

# Run all integration tests
composer test:integration

# Run all database tests
composer test:database

# Run all API tests
composer test:api
```

## Test Data

Proper test data is crucial for effective testing. AureusERP uses several sources for test data:

- **Factories**: Model factories are the preferred way to create test data
- **Seeders**: Database seeders can be used to populate the database with a standard set of data
- **Test Helpers**: The `TestHelpers` class provides methods for generating test data
- **In-Memory Data**: For unit tests that don't require database interaction

For more information on test data requirements and assumptions, see the [Test Data Requirements and Assumptions](test-data-requirements.md) document.

## Plugin-Specific Testing

Each plugin in AureusERP has unique functionality and requirements that need to be considered when writing tests. The [Plugin-Specific Testing Guidelines](plugin-testing-guidelines.md) document provides guidelines for testing specific plugins.

## Test Examples

The [Test Examples](test-examples.md) document provides examples for each test type and category in the AureusERP testing framework. These examples can be used as templates when writing new tests.

## Test Coverage

AureusERP aims to maintain a minimum of 70% code coverage across the codebase. Test coverage is measured using Pest's coverage reporting tools and is integrated into the CI/CD pipeline.

For more information on test coverage requirements and tools, see the [Test Coverage Guidelines](test-coverage.md) document.

### Running Tests with Coverage

Tests can be run with coverage using the following Composer scripts:

```bash
# Run all tests with coverage
composer test:coverage

# Run all tests with HTML coverage report
composer test:coverage-html
```

### Coverage Dashboard

AureusERP provides a custom dashboard script that displays coverage metrics for the entire project and individual plugins:

```bash
php scripts/coverage-dashboard.php
```

## Test Performance

Test performance is important to ensure that tests can be run quickly and frequently. AureusERP uses several techniques to optimize test performance:

### Parallel Testing

Tests can be run in parallel using the following Composer script:

```bash
composer test:parallel
```

The number of parallel processes can be configured in the `pest.config.php` file:

```php
Parallel::class => [
    'processes' => 8,
    'timeout' => 120,
],
```

### Database Optimizations

- **Database Transactions**: Tests use database transactions to avoid expensive database resets
- **In-Memory SQLite**: Tests can use an in-memory SQLite database for faster execution
- **Selective Testing**: Tests can be run selectively to focus on specific areas

### Test Caching

Pest supports test caching, which can significantly improve test performance. Test caching is enabled by default in the `phpunit.xml` file:

```xml
<phpunit cacheDirectory=".phpunit.cache">
    <!-- ... -->
</phpunit>
```

## Troubleshooting

### Common Issues

1. **Tests Failing Due to Database Issues**
   - Ensure that the database connection is configured correctly
   - Check that the database migrations are up to date
   - Verify that the database transactions are being used correctly

2. **Tests Failing Due to Authentication Issues**
   - Ensure that the authentication is configured correctly
   - Check that the user has the appropriate permissions
   - Verify that the authentication middleware is being applied correctly

3. **Tests Running Slowly**
   - Check if parallel testing is enabled
   - Verify that database transactions are being used
   - Consider using an in-memory SQLite database for testing

### Getting Help

If you encounter issues with testing, you can:
- Check the [Test Examples](test-examples.md) document for examples of similar tests
- Review the [Plugin-Specific Testing Guidelines](plugin-testing-guidelines.md) for guidance on testing specific plugins
- Ask for help in the project's communication channels

## References

- [Pest PHP Documentation](https://pestphp.com/docs/introduction)
- [Laravel Testing Documentation](https://laravel.com/docs/testing)
- [Mockery Documentation](https://docs.mockery.io/en/latest/)
- [Larastan Documentation](https://github.com/larastan/larastan)
- [Infection PHP Documentation](https://infection.github.io/guide/)
- [Codecov Documentation](https://docs.codecov.io/docs)

## Related Documents

- [Test Categories Guidelines](test-categories.md)
- [Plugin-Specific Testing Guidelines](plugin-testing-guidelines.md)
- [Test Data Requirements and Assumptions](test-data-requirements.md)
- [Test Examples](test-examples.md)
- [Test Coverage Guidelines](test-coverage.md)
