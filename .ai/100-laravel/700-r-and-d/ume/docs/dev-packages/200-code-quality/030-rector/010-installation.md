# Rector Installation and Setup

## 1. Overview

This guide covers the installation and initial setup of Rector for our Laravel 12 project running on PHP 8.4. Rector is an automated refactoring tool that helps upgrade and refactor PHP code.

## 2. Installation

### 2.1. Required Packages

Install Rector and Laravel-specific rules via Composer:

```bash
composer require --dev rector/rector driftingly/rector-laravel
```

### 2.2. Additional Packages

For enhanced functionality, consider these optional packages:

```bash
# For additional type-related rules
composer require --dev rector/type-perfect

# For Symfony component support
composer require --dev rector/rector-symfony
```

## 3. Initial Configuration

### 3.1. Create Configuration File

Initialize Rector configuration:

```bash
# Create the configuration file
vendor/bin/rector init
```

This will create a `rector.php` file in your project root.

### 3.2. Basic Configuration

The generated `rector.php` file will look something like this:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

    // register a single rule
    // $rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);

    // define sets of rules
    //    $rectorConfig->sets([
    //        LevelSetList::UP_TO_PHP_74
    //    ]);
};
```

## 4. Laravel-Specific Configuration

Update your `rector.php` file to include Laravel-specific rules:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;
use Driftingly\RectorLaravel\Set\LaravelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

    // PHP 8.4 features
    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84,
        SetList::CODE_QUALITY,
        SetList::DEAD_CODE,
        SetList::EARLY_RETURN,
        SetList::TYPE_DECLARATION,
        LaravelSetList::LARAVEL_100,
    ]);

    // Skip specific paths
    $rectorConfig->skip([
        __DIR__ . '/vendor',
        __DIR__ . '/storage',
        __DIR__ . '/bootstrap/cache',
    ]);

    // Enable parallel processing
    $rectorConfig->parallel();
};
```

## 5. Composer Scripts

Add these helpful scripts to your `composer.json`:

```json
"scripts": {
    "refactor": [ "./vendor/bin/rector process" ],
    "refactor:dry": [ "./vendor/bin/rector process --dry-run" ]
}
```

## 6. Initial Run

Run Rector in dry-run mode to see what changes it would make:

```bash
composer refactor:dry
```

## 7. IDE Integration

### 7.1. PHPStorm

PHPStorm has built-in support for Rector through the PHP Inspections plugin.

### 7.2. VS Code

Install the Rector extension:
- Name: Rector - PHP Refactoring
- ID: rector.rector-vscode

## 8. Next Steps

After installation:

1. Review the [Configuration](020-configuration.md) guide for advanced settings
2. Learn about [Laravel-specific rules](030-laravel-rules.md)
3. Integrate Rector into your development workflow
