# Testing Packages

This directory contains documentation for all testing-related packages used in the project.

## 1. Overview

Testing is a critical part of the development process, ensuring that code works as expected and remains stable over time. This project uses a variety of testing tools to facilitate different types of tests.

## 2. Testing Packages

| Package | Description | Documentation |
|---------|-------------|---------------|
| [Pest](010-pest/000-index.md) | Modern testing framework for PHP | [010-pest/](010-pest/) |
| [Paratest](020-paratest.md) | Parallel testing for PHPUnit/Pest | [020-paratest.md](020-paratest.md) |
| [Laravel Dusk](030-dusk.md) | Browser testing for Laravel | [030-dusk.md](030-dusk.md) |
| [Infection](040-infection.md) | Mutation testing framework | [040-infection.md](040-infection.md) |
| [Mockery](050-mockery.md) | Mock object framework | [050-mockery.md](050-mockery.md) |

## 3. Testing Workflow

The typical testing workflow in this project includes:

1. Writing unit tests with Pest
2. Running tests in parallel with Paratest
3. Performing browser testing with Dusk
4. Validating test quality with Infection

## 4. Composer Commands

This project includes several Composer scripts for running tests:

```bash
# Run all tests
composer test

# Run tests in parallel
composer test:parallel

# Run specific test suites
composer test:unit
composer test:feature
composer test:integration

# Run tests with code coverage
composer test:coverage

# Run mutation tests
composer test:mutation

# Run browser tests
php artisan dusk
```

## 5. Configuration

Testing configuration is primarily managed through:

- `phpunit.xml` - PHPUnit/Pest configuration
- `tests/Pest.php` - Pest configuration
- `infection.json.dist` - Infection configuration

## 6. Best Practices

- Write tests before implementing features (TDD)
- Aim for high code coverage (90%+)
- Use descriptive test names
- Keep tests fast and independent
- Use data providers for testing multiple scenarios
