# Pest PHP

## 1. Overview

Pest is a modern testing framework for PHP with a focus on simplicity and developer experience. It builds on top of PHPUnit and provides a more expressive syntax for writing tests.

### 1.1. Package Information

- **Package Name**: pestphp/pest
- **Version**: ^3.8
- **GitHub**: [https://github.com/pestphp/pest](https://github.com/pestphp/pest)
- **Documentation**: [https://pestphp.com/docs/](https://pestphp.com/docs/)

### 1.2. Related Packages

- **pestphp/pest-plugin-laravel**: Laravel integration for Pest
- **pestphp/pest-plugin-arch**: Architecture testing plugin
- **spatie/pest-plugin-snapshots**: Snapshot testing plugin

## 2. Key Features

- Expressive, simple syntax for writing tests
- Compatible with existing PHPUnit tests
- Higher-order tests for reducing duplication
- Powerful expectations API
- Architecture testing capabilities
- Parallel testing support

## 3. Installation and Setup

See [010-installation.md](010-installation.md) for detailed installation instructions.

## 4. Writing Tests

See [020-writing-tests.md](020-writing-tests.md) for information on writing tests with Pest.

## 5. Plugins

See [030-plugins.md](030-plugins.md) for information on Pest plugins used in this project.

## 6. Usage in This Project

In this project, Pest is the primary testing framework used for:

- Unit tests (`tests/Unit`)
- Feature tests (`tests/Feature`)
- Integration tests (`tests/Integration`)
- Architecture tests (using pest-plugin-arch)
- Snapshot tests (using pest-plugin-snapshots)

## 7. Composer Commands

```bash
# Run all tests
composer test

# Run tests in parallel
composer test:parallel

# Run specific test suites
composer test:unit
composer test:feature
composer test:integration

# Run architecture tests
composer test:arch

# Run snapshot tests
composer test:snapshots
```

## 8. Configuration

Pest is configured through:

- `phpunit.xml` - Base PHPUnit configuration
- `tests/Pest.php` - Pest-specific configuration
- `tests/Arch.php` - Architecture testing rules

## 9. Best Practices

- Use descriptive test names that explain the behavior being tested
- Group related tests using `describe()` blocks
- Use datasets for testing multiple scenarios
- Keep tests focused on a single behavior
- Use architecture tests to enforce coding standards
