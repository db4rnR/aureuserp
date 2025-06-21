# Testing Standards

## Table of Contents

1. [Introduction](#introduction)
2. [Test Organization](#test-organization)
3. [Naming Conventions](#naming-conventions)
4. [Test Data Management](#test-data-management)
5. [Assertion Best Practices](#assertion-best-practices)
6. [Documentation Standards](#documentation-standards)
7. [Test Categories and Grouping](#test-categories-and-grouping)
8. [Performance Considerations](#performance-considerations)
9. [Tools and Extensions](#tools-and-extensions)
10. [Continuous Integration](#continuous-integration)

## Introduction

This document outlines the testing standards for the project. Following these standards ensures consistency, maintainability, and effectiveness of our test suite. These guidelines apply to all types of tests: unit, feature, and integration tests.

## Test Organization

### Directory Structure

Tests should mirror the structure of the application code:

```
tests/
├── Unit/                  # Unit tests
│   └── Plugins/
│       └── [PluginName]/
│           ├── Models/    # Tests for models
│           └── Services/  # Tests for services
├── Feature/               # Feature tests
│   └── Plugins/
│       └── [PluginName]/
│           ├── Controllers/ # Tests for controllers
│           └── API/         # Tests for API endpoints
├── Integration/           # Integration tests
│   └── Plugins/
│       └── [PluginName]/
├── Traits/                # Shared test traits
├── Factories/             # Test data factories
└── Helpers/               # Test helper functions
```

### Test Types

1. **Unit Tests**: Test individual components in isolation
   - Located in `tests/Unit/`
   - Focus on testing a single class or method
   - Use mocks and stubs for dependencies

2. **Feature Tests**: Test complete features or user workflows
   - Located in `tests/Feature/`
   - Test HTTP endpoints, controllers, and resources
   - Often involve database interactions

3. **Integration Tests**: Test interactions between components
   - Located in `tests/Integration/`
   - Test how multiple components work together
   - May involve multiple services or subsystems

## Naming Conventions

### Test Class Naming

- Test classes should be named after the class they test, with "Test" suffix
- Example: `InvoiceTest.php` for testing the `Invoice` class

### Test Method Naming

- Test methods should clearly describe what they're testing
- Use the format: `test_[method_name]_[scenario]_[expected_result]`
- Examples:
  - `test_create_with_valid_data_returns_success()`
  - `test_update_with_invalid_id_throws_exception()`

## Test Data Management

### Factories

- Use Laravel's factory system for creating test data
- Define factories for all models in `database/factories/`
- Use factory states for different scenarios

### Test Data Isolation

- Tests should not depend on data created by other tests
- Use database transactions to isolate test data: `use RefreshDatabase;`
- Clean up any created files or external resources in tearDown methods

## Assertion Best Practices

### General Guidelines

- Each test should have a clear, single purpose
- Use descriptive assertion messages
- Test both positive and negative scenarios
- Avoid testing multiple behaviors in a single test

### Common Assertions

- Use appropriate assertions for the data type being tested
- Examples:
  - `assertEquals()` for exact matches
  - `assertContains()` for collections
  - `assertInstanceOf()` for object types
  - `assertDatabaseHas()` for database records

## Documentation Standards

### Test Class Documentation

- Each test class should have a PHPDoc block describing what it tests
- Include `@covers` annotation to indicate which class is being tested

### Test Method Documentation

- Each test method should have a PHPDoc block describing:
  - What is being tested
  - The expected outcome
  - Any special setup or conditions

## Test Categories and Grouping

### Using PHP Attributes

- Use PHP attributes to categorize tests
- Examples:
  ```php
  #[Group('api')]
  #[Group('database')]
  #[Group('slow')]
  ```

### Standard Categories

- Technical categories:
  - `api`: Tests involving API endpoints
  - `database`: Tests with database interactions
  - `ui`: Tests involving UI components
  - `performance`: Tests measuring performance
  - `slow`: Tests that take longer than average to run

- Domain-specific categories:
  - `invoices`: Tests related to invoice functionality
  - `payments`: Tests related to payment functionality
  - `products`: Tests related to product functionality

## Performance Considerations

### Optimizing Test Speed

- Use database transactions instead of migrations when possible
- Consider using in-memory SQLite for faster database tests
- Minimize external API calls in tests

### Parallel Testing

- Configure parallel testing in phpunit.xml
- Ensure tests are independent and can run in parallel
- Use the `--parallel` option when running tests

## Tools and Extensions

### Required Tools

- **PHPUnit/Pest**: Primary testing framework
- **Mockery**: For creating test doubles
- **Larastan**: For static analysis
- **Infection PHP**: For mutation testing

### Optional Tools

- **Laravel Dusk**: For browser testing
- **Codeception**: For BDD-style tests

## Continuous Integration

### CI Pipeline Integration

- All tests should run on every pull request
- Test coverage reports should be generated
- Minimum coverage thresholds should be enforced (70%)

### Reporting

- Test results should be reported in a readable format
- Coverage reports should highlight areas needing more tests
- Performance metrics should be tracked over time
