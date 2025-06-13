# Phase 1: Phase 0.13: Testing Environment Setup

**Version:** 1.0.2 **Date:** 2023-11-13 **Author:** AI Assistant **Status:** Updated **Progress:** Complete

---

<details>
<summary>Table of Contents</summary>

- [Overview](#overview)
- [Prerequisites](#prerequisites)
  - [Required Prior Steps](#required-prior-steps)
  - [Required Packages](#required-packages)
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Testing Stack](#testing-stack)
- [Installation](#installation)
- [Configuration](#configuration)
  - [PHPUnit Configuration](#phpunit-configuration)
  - [Pest Configuration](#pest-configuration)
  - [Infection Configuration](#infection-configuration)
- [Writing Tests](#writing-tests)
  - [Feature Tests](#feature-tests)
  - [Unit Tests](#unit-tests)
  - [Livewire Component Tests](#livewire-component-tests)
- [Running Tests](#running-tests)
- [Code Coverage](#code-coverage)
- [Mutation Testing](#mutation-testing)
- [Continuous Integration](#continuous-integration)
- [Best Practices](#best-practices)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed instructions for setting up the testing environment for the Enhanced Laravel Application
(ELA). It covers the configuration of PHPUnit, Pest, and Infection for comprehensive testing.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps

- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Database Setup](040-database/010-database-setup.md) completed
- [Security Setup](050-security-testing/010-security-setup.md) completed

### Required Packages

- Laravel Framework (`laravel/framework`) installed
- PHPUnit (`phpunit/phpunit`) installed
- Pest PHP (`pestphp/pest`) installed
- Infection PHP (`infection/infection`) installed

### Required Knowledge

- Basic understanding of testing methodologies
- Familiarity with PHPUnit and/or Pest PHP
- Understanding of test-driven development (TDD)

### Required Environment

- PHP 8.2 or higher with Xdebug extension
- Composer 2.x
- SQLite installed for testing database

## Estimated Time Requirements

| Task                            | Estimated Time  |
| ------------------------------- | --------------- |
| Install Testing Packages        | 15 minutes      |
| Configure PHPUnit               | 10 minutes      |
| Configure Pest PHP              | 10 minutes      |
| Configure Infection             | 15 minutes      |
| Set Up Feature Tests            | 20 minutes      |
| Set Up Unit Tests               | 15 minutes      |
| Set Up Livewire Component Tests | 20 minutes      |
| Configure Code Coverage         | 15 minutes      |
| Set Up Continuous Integration   | 30 minutes      |
| **Total**                       | **150 minutes** |

> **Note:** These time estimates assume familiarity with Laravel testing. Actual time may vary based on experience level
> and the complexity of your application.

## Testing Stack

The ELA uses the following testing stack:

| Tool                | Version    | Purpose                            |
| ------------------- | ---------- | ---------------------------------- |
| Pest PHP            | ^3.8.2     | Testing framework built on PHPUnit |
| Pest Laravel Plugin | ^3.2.0     | Laravel integration for Pest       |
| PHPUnit             | (via Pest) | Underlying testing framework       |
| Mockery             | ^1.6       | Mocking framework                  |
| Faker               | ^1.23      | Fake data generation               |
| Infection           | (optional) | Mutation testing framework         |

## Installation

Install the required testing packages:

````bash
# Install Pest and related packages
composer require pestphp/pest:"^3.8.2" --dev --with-all-dependencies
composer require pestphp/pest-plugin-laravel:"^3.2.0" --dev
composer require mockery/mockery:"^1.6" --dev
composer require fakerphp/faker:"^1.23" --dev
```text

## Configuration

### PHPUnit Configuration

The `phpunit.xml` file configures the testing environment:

```xml
<?xml version="1.0" encoding="UTF-8"?>
<phpunit xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance"
         xsi:noNamespaceSchemaLocation="vendor/phpunit/phpunit/phpunit.xsd"
         bootstrap="vendor/autoload.php"
         colors="true"
>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <source>
        <include>
            <directory>app</directory>
            <directory>packages/s-a-c/ai-prompt-addenda/src</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="APP_MAINTENANCE_DRIVER" value="file"/>
        <env name="BCRYPT_ROUNDS" value="4"/>
        <env name="CACHE_STORE" value="array"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
        <env name="MAIL_MAILER" value="array"/>
        <env name="PULSE_ENABLED" value="false"/>
        <env name="QUEUE_CONNECTION" value="sync"/>
        <env name="SESSION_DRIVER" value="array"/>
        <env name="TELESCOPE_ENABLED" value="false"/>
    </php>
</phpunit>
````php
### Pest Configuration

The `pest.config.php` file configures Pest-specific settings:

````php
<?php

declare(strict_types=1);

use Pest\Plugin;
use Pest\Plugins\Coverage;

return [
    'coverage' => [
        'min' => 100,
        'html' => true,
        'clover' => true,
        'output' => [
            'html' => 'coverage/html',
            'clover' => 'coverage/clover.xml',
        ],
    ],
    'test' => [
        'strict' => true,
        'parallel' => [
            'enabled' => true,
            'processes' => 4,
        ],
        'retry' => [
            'times' => 2,
            'sleep' => 1000,
        ],
    ],
    'plugins' => [
        Coverage::class,
    ],
];
```text

### Infection Configuration

The `infection.json5` file configures mutation testing:

```json
{
    "$schema": "vendor/infection/infection/resources/schema.json",
    "source": {
        "directories": [
            "app",
            "packages/s-a-c/ai-prompt-addenda/src"
        ],
        "excludes": [
            "vendor",
            "node_modules",
            "storage",
            "bootstrap/cache",
            "public",
            "tests",
            "reports/rector/cache"
        ]
    },
    "mutators": {
        "@default": true,
        "@function_signature": false
    },
    "testFramework": "pest",
    "bootstrap": "./vendor/autoload.php",
    "initialTestsPhpOptions": "-d memory_limit=-1",
    "timeout": 30,
    "threads": 8,
    "minMsi": 85,
    "minCoveredMsi": 90
}
````php
## Writing Tests

### Feature Tests

Feature tests test the application as a whole, including HTTP requests, database operations, and more:

````php
<?php

use function Pest\Laravel\get;

it('has welcome page', function () {
    get('/')
        ->assertStatus(200)
        ->assertSee('Welcome');
});
```text

### Unit Tests

Unit tests test individual components in isolation:

```php
<?php

it('calculates total correctly', function () {
    $calculator = new Calculator();

    expect($calculator->add(2, 3))->toBe(5);
});
````php
### Livewire Component Tests

Tests for Livewire components:

````php
<?php

use App\Livewire\Counter;
use function Pest\Livewire\livewire;

it('increments counter', function () {
    livewire(Counter::class)
        ->assertSet('count', 0)
        ->call('increment')
        ->assertSet('count', 1);
});
```text

## Running Tests

Run tests using the following commands:

```bash
# Run all tests
./vendor/bin/pest

# Run with coverage report
./vendor/bin/pest --coverage

# Run specific test file
./vendor/bin/pest tests/Feature/ExampleTest.php

# Run tests with specific filter
./vendor/bin/pest --filter=test_name

# Run tests in parallel
./vendor/bin/pest --parallel
````php
You can also use the Composer script:

````bash
composer test
```text

## Code Coverage

Generate code coverage reports:

```bash
./vendor/bin/pest --coverage
````html
This will generate HTML coverage reports in the `coverage/html` directory.

## Mutation Testing

Run mutation testing to evaluate the quality of your tests:

````bash
./vendor/bin/infection
```text

This will generate reports in the `reports/infection` directory.

## Continuous Integration

Configure GitHub Actions for continuous testing:

```yaml
name: Tests

on: [push, pull_request]

jobs:
  tests:
    runs-on: ubuntu-latest

    steps:
      - uses: actions/checkout@v3

      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
          extensions: dom, curl, libxml, mbstring, zip, pcntl, pdo, sqlite, pdo_sqlite
          coverage: pcov

      - name: Install dependencies
        run: composer install --prefer-dist --no-interaction

      - name: Execute tests
        run: vendor/bin/pest --coverage
````

## Best Practices

1. **Test Coverage**: Aim for 100% code coverage
2. **Test Isolation**: Each test should be independent
3. **Use Factories**: Use Laravel factories for test data
4. **Test Edge Cases**: Test boundary conditions and error cases
5. **Keep Tests Fast**: Tests should run quickly
6. **Descriptive Names**: Use descriptive test names
7. **Arrange-Act-Assert**: Structure tests with clear sections

## Troubleshooting

### Common Issues

1. **Database Issues**:

   - Problem: Tests fail with database errors
   - Solution: Ensure the SQLite database is configured correctly and migrations are run

2. **Memory Limits**:

   - Problem: Tests fail with memory limit errors
   - Solution: Increase PHP memory limit with `-d memory_limit=-1`

3. **Slow Tests**:

   - Problem: Tests run too slowly
   - Solution: Use parallel testing and optimize database operations

4. **Failed Assertions**:

   - Problem: Tests fail with assertion errors
   - Solution: Check the test output for specific failures and fix the code or tests

5. **Coverage Issues**:
   - Problem: Code coverage reports show low coverage
   - Solution: Add tests for uncovered code paths

## Related Documents

- [Security Setup](050-security-testing/010-security-setup.md) - For security configuration
- [Logging Setup](050-security-testing/030-logging-setup.md) - For logging configuration
- [Testing Configuration Details](060-configuration/030-testing-configuration.md) - For advanced testing configuration
- [Code Quality Tools](060-configuration/040-code-quality-tools.md) - For code quality tools configuration

## Version History

| Version | Date       | Changes                                                                                               | Author       |
| ------- | ---------- | ----------------------------------------------------------------------------------------------------- | ------------ |
| 1.0.0   | 2025-05-15 | Initial version                                                                                       | AI Assistant |
| 1.0.1   | 2025-05-17 | Updated file references and links                                                                     | AI Assistant |
| 1.0.2   | 2025-05-17 | Added standardized prerequisites, estimated time requirements, related documents, and version history | AI Assistant |

---

**Previous Step:** [Security Setup](050-security-testing/010-security-setup.md) | **Next Step:**
[Logging Setup](050-security-testing/030-logging-setup.md)
