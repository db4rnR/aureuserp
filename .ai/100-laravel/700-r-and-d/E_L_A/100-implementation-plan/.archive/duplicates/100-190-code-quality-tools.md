# Phase 1: Phase 0.19: Code Quality Tools Configuration

**Version:** 1.0.2
**Date:** 2023-11-13
**Author:** AI Assistant
**Status:** Updated
**Progress:** Complete

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
- [Code Quality Tools](#code-quality-tools)
  - [Laravel Pint](#laravel-pint)
  - [PHPStan](#phpstan)
  - [Rector](#rector)
  - [PHP Insights](#php-insights)
  - [Infection](#infection)
  - [Prettier](#prettier)
- [Testing Tools](#testing-tools)
  - [Pest PHP](#pest-php)
  - [PHPUnit](#phpunit)
- [Standardized Configuration](#standardized-configuration)
  - [Path Standardization](#path-standardization)
  - [PHP Version](#php-version)
  - [Performance Settings](#performance-settings)
- [Integration with CI/CD](#integration-with-cicd)
- [Usage Guide](#usage-guide)
- [Troubleshooting](#troubleshooting)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides detailed information about the code quality and testing tools configured in the Enhanced Laravel Application (ELA). It covers the configuration of tools like Laravel Pint, PHPStan, Rector, PHP Insights, Infection, Prettier, Pest PHP, and PHPUnit.

## Prerequisites

Before starting, ensure you have:

### Required Prior Steps
- [Laravel Installation](020-environment-setup/020-laravel-installation.md) completed
- [Package Installation](030-core-components/010-package-installation.md) completed
- [Testing Configuration](060-configuration/030-testing-configuration.md) completed

### Required Packages
- Laravel Framework (`laravel/framework`) installed
- Laravel Pint (`laravel/pint`) installed
- PHPStan (`phpstan/phpstan`) installed
- Rector (`rector/rector`) installed
- PHP Insights (`nunomaduro/phpinsights`) installed
- Infection (`infection/infection`) installed
- Prettier (`prettier/prettier`) installed
- Pest PHP (`pestphp/pest`) installed

### Required Knowledge
- Basic understanding of code quality concepts
- Familiarity with PHP coding standards
- Understanding of static analysis tools

### Required Environment
- PHP 8.2 or higher
- Composer 2.x
- Node.js 22.x or higher
- npm/pnpm 10.x or higher

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Configure Laravel Pint | 10 minutes |
| Configure PHPStan | 15 minutes |
| Configure Rector | 15 minutes |
| Configure PHP Insights | 10 minutes |
| Configure Infection | 15 minutes |
| Configure Prettier | 10 minutes |
| Configure Testing Tools | 15 minutes |
| Set Up Standardized Configuration | 10 minutes |
| Integrate with CI/CD | 20 minutes |
| **Total** | **120 minutes** |

> **Note:** These time estimates assume familiarity with code quality tools. Actual time may vary based on experience level and the complexity of your project.

## Code Quality Tools

### Laravel Pint

Laravel Pint is an opinionated PHP code style fixer based on PHP-CS-Fixer.

**Configuration File**: `pint.json`

**Key Configuration**:
```json
{
    "preset": "laravel",
    "rules": {
        "@PSR12": true,
        "declare_strict_types": true,
        "ordered_imports": {
            "sort_algorithm": "alpha"
        },
        "single_quote": true,
        "array_syntax": {
            "syntax": "short"
        }
    },
    "parallel": {
        "enabled": true,
        "processes": "auto",
        "max_processes": 8
    }
}
```text

**Usage**:
```bash
# Run Pint to fix code style issues
./vendor/bin/pint

# Run Pint in test mode (no changes)
./vendor/bin/pint --test
```php
### PHPStan

PHPStan is a static analysis tool that finds errors in your code without running it.

**Configuration File**: `phpstan.neon`

**Key Configuration**:
```yaml
includes:
    - ./vendor/nunomaduro/larastan/extension.neon

parameters:
    phpVersion: 80400
    level: 5
    paths:
        - app
        - config
        - database
        - routes
        - tests
        - bootstrap
        - resources
        - packages/s-a-c/ai-prompt-addenda/src
        - packages/s-a-c/ai-prompt-addenda/tests
    checkOctaneCompatibility: true
    checkModelProperties: true
```text

**Usage**:
```bash
# Run PHPStan analysis
./vendor/bin/phpstan analyse

# Run with specific configuration
./vendor/bin/phpstan analyse -c phpstan.neon
```php
### Rector

Rector is a tool for automated refactoring of PHP code.

**Configuration Files**:
- `rector.php` (main configuration)
- `rector-type-safety.php` (type safety specific rules)

**Key Configuration**:
```php
// rector.php
$rectorConfig->sets([
    LevelSetList::UP_TO_PHP_84,
    SetList::CODE_QUALITY,
    SetList::CODING_STYLE,
    SetList::DEAD_CODE,
    SetList::EARLY_RETURN,
    SetList::NAMING,
    SetList::PRIVATIZATION,
    SetList::TYPE_DECLARATION,
    LaravelSetList::LARAVEL_CODE_QUALITY,
]);

// rector-type-safety.php
$rectorConfig->rules([
    AddReturnTypeDeclarationRector::class,
    AddVoidReturnTypeWhereNoReturnRector::class,
    ReturnTypeFromStrictNativeCallRector::class,
    AddPropertyTypeDeclarationRector::class,
    TypedPropertyFromStrictConstructorRector::class,
]);
```text

**Usage**:
```bash
# Run Rector to refactor code
./vendor/bin/rector process

# Run Rector in dry-run mode
./vendor/bin/rector process --dry-run

# Run type safety rules only
./vendor/bin/rector process --config=rector-type-safety.php
```php
### PHP Insights

PHP Insights is a tool that provides metrics and insights about code quality.

**Configuration File**: `phpinsights.php`

**Key Configuration**:
```php
return [
    'preset'       => 'laravel',
    'ide'          => 'phpstorm',
    'php'          => [
        'version' => '8.4',
    ],
    'requirements' => [
        'min-quality'            => 85,
        'min-complexity'         => 85,
        'min-architecture'       => 85,
        'min-style'              => 85,
    ],
    'threads'      => 8,
];
```text

**Usage**:
```bash
# Run PHP Insights analysis
./vendor/bin/phpinsights

# Run analysis on specific directory
./vendor/bin/phpinsights analyse app
```php
### Infection

Infection is a PHP mutation testing framework.

**Configuration File**: `infection.json5`

**Key Configuration**:
```json
{
    "source": {
        "directories": [
            "app",
            "packages/s-a-c/ai-prompt-addenda/src"
        ]
    },
    "testFramework": "pest",
    "threads": 8,
    "minMsi": 85,
    "minCoveredMsi": 90
}
```text

**Usage**:
```bash
# Run Infection mutation testing
./vendor/bin/infection

# Run with specific configuration
./vendor/bin/infection --configuration=infection.json5
```javascript
### Prettier

Prettier is a code formatter for JavaScript, CSS, and other frontend languages.

**Configuration File**: `.prettierrc.js`

**Key Configuration**:
```js
export default {
  singleQuote: true,
  trailingComma: 'all',
  printWidth: 120,
  tabWidth: 2,
  overrides: [
    {
      files: '*.blade.php',
      options: {
        parser: 'html',
        htmlWhitespaceSensitivity: 'ignore',
      },
    },
    {
      files: '*.php',
      options: {
        parser: 'php',
        phpVersion: '8.2',
        braceStyle: 'psr-2',
      },
    }
  ]
};
```text

**Usage**:
```bash
# Format files with Prettier
npx prettier --write .

# Check formatting without changing files
npx prettier --check .
```php
## Testing Tools

### Pest PHP

Pest is a testing framework with a focus on simplicity.

**Configuration File**: `pest.config.php`

**Key Configuration**:
```php
return [
    'coverage' => [
        'min' => 100,
        'html' => true,
        'clover' => true,
    ],
    'test' => [
        'strict' => true,
        'parallel' => [
            'enabled' => true,
            'processes' => 4,
        ],
    ],
];
```text

**Usage**:
```bash
# Run all tests
./vendor/bin/pest

# Run with coverage report
./vendor/bin/pest --coverage

# Run specific test file
./vendor/bin/pest tests/Feature/ExampleTest.php
```php
### PHPUnit

PHPUnit is the underlying testing framework used by Pest.

**Configuration File**: `phpunit.xml`

**Key Configuration**:
```xml
<phpunit>
    <testsuites>
        <testsuite name="Unit">
            <directory>tests/Unit</directory>
        </testsuite>
        <testsuite name="Feature">
            <directory>tests/Feature</directory>
        </testsuite>
    </testsuites>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```text

**Usage**:
```bash
# Run PHPUnit tests
./vendor/bin/phpunit

# Run specific test suite
./vendor/bin/phpunit --testsuite=Unit
```php
## Standardized Configuration

### Path Standardization

All tools are configured to use the same standardized paths for analysis:

- `app`: Application code
- `config`: Configuration files
- `database`: Database migrations and seeders
- `routes`: Route definitions
- `tests`: Test files
- `bootstrap`: Bootstrap files
- `resources`: Frontend resources
- `packages/s-a-c/ai-prompt-addenda/src`: Package source code
- `packages/s-a-c/ai-prompt-addenda/tests`: Package tests

### PHP Version

All tools are configured to target PHP 8.4:

- PHPStan: `phpVersion: 80400`
- Rector: `$rectorConfig->phpVersion(80400)`
- PHP Insights: `'version' => '8.4'`

### Performance Settings

Performance settings are standardized across tools:

- Parallel processing: 8 threads/processes
- Memory limits: 1-2GB
- Cache directories: `reports/{tool}/cache`

## Integration with CI/CD

These tools can be integrated into CI/CD pipelines for automated quality checks:

1. **GitHub Actions Example**:
   ```yaml
   name: Code Quality

   on: [push, pull_request]

   jobs:
     quality:
       runs-on: ubuntu-latest
       steps:
         - uses: actions/checkout@v3
         - name: Setup PHP
           uses: shivammathur/setup-php@v2
           with:
             php-version: '8.2'
             coverage: pcov
         - name: Install dependencies
           run: composer install --prefer-dist --no-progress
         - name: Run Pint
           run: ./vendor/bin/pint --test
         - name: Run PHPStan
           run: ./vendor/bin/phpstan analyse
         - name: Run Tests
           run: ./vendor/bin/pest --coverage
   ```markdown
## Usage Guide

### Recommended Workflow

1. **During Development**:
   - Use Laravel Pint to maintain code style
   - Run Pest tests frequently to ensure functionality

2. **Before Committing**:
   - Run PHPStan to catch potential errors
   - Run Pest with coverage to ensure test coverage

3. **Periodic Code Quality Checks**:
   - Run PHP Insights for comprehensive analysis
   - Run Infection to test the quality of your tests
   - Use Rector to apply automated refactorings

### Composer Scripts

Add these scripts to your `composer.json` for easier access:

```json
"scripts": {
    "test": "pest",
    "test:coverage": "pest --coverage",
    "analyse": "phpstan analyse",
    "format": "pint",
    "insights": "phpinsights",
    "rector": "rector process --dry-run",
    "rector:fix": "rector process",
    "quality": [
        "@format",
        "@analyse",
        "@test:coverage"
    ]
}
```text

Then run them with:
```bash
composer test
composer quality
```

## Troubleshooting

<details>
<summary>Common Issues and Solutions</summary>

### Issue: Laravel Pint fails to format code

**Symptoms:**
- Pint reports errors during formatting
- Formatting is inconsistent

**Possible Causes:**
- Conflicting configuration files
- Incompatible PHP version
- Syntax errors in code

**Solutions:**
1. Check for conflicting `.php-cs-fixer.php` or other formatting configuration files
2. Ensure you're using PHP 8.2 or higher
3. Fix syntax errors in your code before running Pint

### Issue: PHPStan reports too many errors

**Symptoms:**
- PHPStan reports hundreds of errors
- Many false positives

**Possible Causes:**
- Level set too high for current codebase
- Missing PHPStan extensions
- Incorrect configuration

**Solutions:**
1. Start with a lower level (e.g., level 0 or 1) and gradually increase
2. Add necessary PHPStan extensions for Laravel
3. Configure baseline file to ignore legacy code

### Issue: Rector changes break functionality

**Symptoms:**
- Code stops working after Rector changes
- Tests fail after running Rector

**Possible Causes:**
- Overly aggressive rules
- Incompatible rule sets
- Complex code that Rector can't safely transform

**Solutions:**
1. Use more conservative rule sets
2. Add specific files or directories to the skip configuration
3. Run with `--dry-run` first to review changes

### Issue: PHP Insights reports low scores

**Symptoms:**
- Very low scores in certain categories
- Many minor issues reported

**Possible Causes:**
- Legacy code not following modern practices
- Third-party packages with issues
- Overly strict configuration

**Solutions:**
1. Focus on improving one category at a time
2. Exclude third-party code from analysis
3. Customize thresholds in the configuration

### Issue: Infection reports low mutation score

**Symptoms:**
- Low mutation score
- Many escaped mutants

**Possible Causes:**
- Insufficient test coverage
- Tests not asserting correct behavior
- Timeout issues with complex mutations

**Solutions:**
1. Improve test coverage
2. Add more specific assertions to tests
3. Adjust timeout settings for complex code

</details>

## Related Documents

- [Testing Configuration](060-configuration/030-testing-configuration.md) - For testing configuration details
- [Sanctum Setup](060-configuration/050-sanctum-setup.md) - For API authentication setup
- [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) - For a summary of Phase 0 implementation

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, troubleshooting, and version history | AI Assistant |

---

**Previous Step:** [Testing Configuration Details](060-configuration/030-testing-configuration.md) | **Next Step:** [Laravel Sanctum Setup](060-configuration/050-sanctum-setup.md)
