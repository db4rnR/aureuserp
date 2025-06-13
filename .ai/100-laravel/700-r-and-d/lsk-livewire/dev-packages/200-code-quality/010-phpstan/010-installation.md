# PHPStan Installation and Setup

## 1. Overview

This guide covers the installation and initial setup of PHPStan with Larastan for Laravel 12 projects running on PHP 8.4.

## 2. Installation

### 2.1. Required Packages

Install PHPStan and Larastan via Composer:

```bash
composer require --dev larastan/larastan
```

This will install both PHPStan and the Laravel-specific extension (Larastan).

### 2.2. Additional Packages

For enhanced functionality, consider these optional packages:

```bash
# For parallel analysis (recommended for large projects)
composer require --dev phpstan/phpstan-parallel

# For Symfony component support
composer require --dev phpstan/phpstan-symfony

# For deprecation detection
composer require --dev phpstan/phpstan-deprecation-rules
```

## 3. Initial Configuration

### 3.1. Create Configuration File

Create a basic PHPStan configuration file at the root of your project:

```bash
# Create the file manually
touch phpstan.neon
```

### 3.2. Basic Configuration

Add this minimal configuration to `phpstan.neon`:

```yaml
includes:
    - ./vendor/larastan/larastan/extension.neon

parameters:
    level: 5
    paths:
        - app
        - config
        - database
        - routes
    excludePaths:
        - ./tests/*
    checkMissingIterableValueType: false
```

### 3.3. Publish Larastan Configuration (Optional)

You can publish the Larastan configuration for more customization:

```bash
php artisan vendor:publish --provider="Larastan\LarastanServiceProvider"
```

## 4. Composer Scripts

Add these helpful scripts to your `composer.json`:

```json
"scripts": {
    "analyze": [
        "./vendor/bin/phpstan analyse --memory-limit=1G --ansi"
    ],
    "analyze:baseline": [
        "./vendor/bin/phpstan analyse --generate-baseline --memory-limit=1G --ansi"
    ],
    "analyze:clear-cache": [
        "rm -rf var/cache/phpstan/* && echo 'PHPStan cache cleared'"
    ],
    "analyze:show-errors": [
        "./vendor/bin/phpstan analyse --memory-limit=1G --error-format=table"
    ]
}
```

## 5. Initial Run

Run PHPStan for the first time to see what issues it finds:

```bash
composer analyze
```

## 6. Create Initial Baseline

If you're adding PHPStan to an existing project, create a baseline to ignore current errors:

```bash
composer analyze:baseline
```

This will create a `phpstan-baseline.neon` file with all current errors.

## 7. IDE Integration

### 7.1. PHPStorm

PHPStorm has built-in support for PHPStan. Enable it in:
- Settings → PHP → Quality Tools → PHPStan

### 7.2. VS Code

Install the PHPStan extension:
- Name: phpstan
- ID: sanderronde.phpstan-vscode

## 8. Next Steps

After installation:

1. Review the [Configuration](020-configuration.md) guide for advanced settings
2. Learn about [Baseline Management](040-baseline-management.md) for gradual adoption
3. Integrate PHPStan into your [Workflow](050-workflow.md)
