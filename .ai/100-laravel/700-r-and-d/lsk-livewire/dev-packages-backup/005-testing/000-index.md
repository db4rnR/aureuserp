# Testing Packages
# Testing Packages Documentation

## 1. Overview

This section documents all testing packages available in the project's require-dev dependencies. Testing packages are configured to maximize compliance with Laravel best practices and conventions, with parallelization enabled by default where supported.

## 2. Packages Covered

### 2.1. Test Frameworks
- [PHPUnit](010-phpunit.md) - Primary testing framework
- [Pest](015-pest.md) - Expressive testing framework built on PHPUnit

### 2.2. Browser Testing
- [Laravel Dusk](020-dusk.md) - Browser automation and testing

### 2.3. Mocking and Fixtures
- [Mockery](025-mockery.md) - Mock object framework
- [Faker](030-faker.md) - Fake data generation

### 2.4. Test Utilities
- [PHP Test Watcher](035-test-watcher.md) - Automatic test execution on file changes
- [Parallel Testing](040-parallel-testing.md) - Executing tests in parallel

## 3. Common Testing Workflows

- [Setting Up Test Database](045-test-database.md)
- [Creating Factories](050-factories.md)
- [Writing Feature Tests](055-feature-tests.md)
- [Writing Unit Tests](060-unit-tests.md)
- [Testing API Endpoints](065-api-testing.md)

## 4. CI/CD Integration

- [GitHub Actions Configuration](070-github-actions.md)
- [Test Reports](075-test-reports.md)
- [Coverage Reports](080-coverage-reports.md)

## 5. Best Practices

- [Testing Best Practices](085-best-practices.md)
- [Test Organization](090-organization.md)
- [Performance Optimization](095-performance.md)
This documentation covers all testing-related packages used for development.

## 1. Package List

The following testing packages are used in this project:

| Package | Version | Description |
|---------|---------|-------------|
| pestphp/pest | ^3.8 | Modern testing framework |
| pestphp/pest-plugin-arch | ^3.1.0 | Architecture testing plugin |
| pestphp/pest-plugin-laravel | ^3.1.0 | Laravel integration for Pest |
| spatie/pest-plugin-snapshots | ^2.2.0 | Snapshot testing for Pest |
| brianium/paratest | ^7.8.3 | Parallel testing library |
| mockery/mockery | ^1.6 | Mocking framework |
| fakerphp/faker | ^1.23 | Fake data generation |
| infection/infection | ^0.29.14 | Mutation testing framework |
| laravel/dusk | ^8.3 | Browser testing framework |

## 2. Configuration Files

Testing configuration is handled through the following files:

- `phpunit.xml` - PHPUnit configuration
- `pest.config.php` - Pest configuration

## 3. Running Tests

Tests can be run using the composer scripts defined in `composer.json`:

```bash
// Run all tests
composer test

// Run unit tests with coverage
composer test:unit

// Run feature tests
composer test:feature

// Run mutation tests
composer test:mutation
```
