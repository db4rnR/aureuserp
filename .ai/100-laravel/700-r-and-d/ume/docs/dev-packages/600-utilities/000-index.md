# Utility Packages

This directory contains documentation for utility packages used in the project's development environment.

## 1. Overview

Utility packages provide additional functionality that enhances the development experience but doesn't fit neatly into other categories. These tools help with error reporting, test data generation, and debugging.

## 2. Utility Packages

| Package | Description | Documentation |
|---------|-------------|---------------|
| [Collision](010-collision.md) | Error reporting tool | [010-collision.md](010-collision.md) |
| [Faker](020-faker.md) | Test data generation | [020-faker.md](020-faker.md) |
| [Var Dumper](030-var-dumper.md) | Variable inspection | [030-var-dumper.md](030-var-dumper.md) |
| [Polyfill PHP 8.4](040-polyfill-php84.md) | PHP 8.4 features in lower PHP versions | [040-polyfill-php84.md](040-polyfill-php84.md) |

## 3. Usage in This Project

Utility packages are used throughout the development process:

1. Collision provides better error reporting in the console
2. Faker generates realistic test data for testing and seeding
3. Var Dumper helps inspect variables during debugging
4. Polyfill PHP 8.4 enables PHP 8.4 features in lower PHP versions

## 4. Composer Commands

These packages are typically used indirectly through other tools, but can also be used directly:

```bash
# Faker is used in database seeders and factories
php artisan db:seed

# Collision is used automatically with Pest/PHPUnit
composer test

# Var Dumper provides the dd() and dump() functions
# Used directly in code during development
```

## 5. Configuration

Most utility packages work out of the box with minimal configuration:

- Faker is configured in database factories
- Collision is automatically integrated with Laravel
- Var Dumper requires no configuration
- Polyfill PHP 8.4 works automatically once installed

## 6. Best Practices

- Use Faker for all test data to ensure realistic scenarios
- Remove debugging statements (dd(), dump()) before committing code
- Take advantage of Collision's detailed error reporting
- Use Polyfill PHP 8.4 when supporting multiple PHP versions

## 7. Tool Selection Guide

| When to Use | Recommended Tool |
|-------------|------------------|
| Console error reporting | Collision |
| Test data generation | Faker |
| Variable inspection | Var Dumper |
| PHP 8.4 compatibility | Polyfill PHP 8.4 |
