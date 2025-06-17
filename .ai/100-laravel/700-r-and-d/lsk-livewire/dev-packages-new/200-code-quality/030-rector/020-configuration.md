# Rector Configuration

## 1. Overview

This guide covers the configuration options for Rector in our Laravel 12 project. Proper configuration ensures that Rector applies the right rules and transformations to our codebase.

## 2. Configuration File

The main configuration file is `rector.php` in the project root. This file defines which rules to apply, which directories to scan, and other settings.

### 2.1. Basic Structure

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;
use Rector\Set\ValueObject\SetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->paths([
        __DIR__ . '/app',
        __DIR__ . '/config',
        __DIR__ . '/database',
        __DIR__ . '/routes',
        __DIR__ . '/tests',
    ]);

    $rectorConfig->sets([
        LevelSetList::UP_TO_PHP_84,
        SetList::CODE_QUALITY,
    ]);
};
```

## 3. Key Configuration Options

### 3.1. Paths

Specify which directories to analyze:

```php
$rectorConfig->paths([
    __DIR__ . '/app',
    __DIR__ . '/config',
    __DIR__ . '/database',
    __DIR__ . '/routes',
    __DIR__ . '/tests',
]);
```

### 3.2. Rule Sets

Apply predefined sets of rules:

```php
$rectorConfig->sets([
    LevelSetList::UP_TO_PHP_84,  // PHP 8.4 compatibility
    SetList::CODE_QUALITY,       // Code quality improvements
    SetList::DEAD_CODE,          // Remove dead code
    SetList::EARLY_RETURN,       // Simplify conditionals with early returns
    SetList::TYPE_DECLARATION,   // Add type declarations
]);
```

### 3.3. Individual Rules

Apply specific rules:

```php
use Rector\CodeQuality\Rector\Class_\InlineConstructorDefaultToPropertyRector;
use Rector\TypeDeclaration\Rector\Property\TypedPropertyFromStrictConstructorRector;

$rectorConfig->rule(InlineConstructorDefaultToPropertyRector::class);
$rectorConfig->rule(TypedPropertyFromStrictConstructorRector::class);
```

### 3.4. Skip Rules

Skip specific rules:

```php
$rectorConfig->skip([
    // Skip a specific rule
    InlineConstructorDefaultToPropertyRector::class,
    
    // Skip a rule for specific paths
    TypedPropertyFromStrictConstructorRector::class => [
        __DIR__ . '/app/Legacy',
    ],
    
    // Skip specific paths entirely
    __DIR__ . '/vendor',
    __DIR__ . '/storage',
]);
```

### 3.5. Parallel Processing

Enable parallel processing for faster execution:

```php
$rectorConfig->parallel();
```

Configure parallel processing:

```php
$rectorConfig->parallel(
    maxNumberOfProcesses: 4,
    jobSize: 20
);
```

### 3.6. Import Names

Configure how Rector handles class imports:

```php
$rectorConfig->importNames();                 // Import classes
$rectorConfig->importShortClasses(false);     // Use fully qualified names
```

### 3.7. PHP Version

Specify the target PHP version:

```php
$rectorConfig->phpVersion(PhpVersion::PHP_84);
```

## 4. Laravel-Specific Configuration

### 4.1. Laravel Rule Sets

Include Laravel-specific rule sets:

```php
use Driftingly\RectorLaravel\Set\LaravelSetList;

$rectorConfig->sets([
    LaravelSetList::LARAVEL_100,  // Laravel 10.0 compatibility
]);
```

### 4.2. Laravel-Specific Rules

Apply specific Laravel rules:

```php
use Driftingly\RectorLaravel\Rector\ClassMethod\AddParentRegisterRector;

$rectorConfig->rule(AddParentRegisterRector::class);
```

## 5. Advanced Configuration

### 5.1. Custom Rules

Register custom rules:

```php
use App\Rector\CustomRule;

$rectorConfig->rule(CustomRule::class);
```

### 5.2. Bootstrap File

Specify a bootstrap file for additional setup:

```php
$rectorConfig->bootstrapFiles([
    __DIR__ . '/rector-bootstrap.php',
]);
```

### 5.3. Auto Import Names

Configure auto-import behavior:

```php
$rectorConfig->importNames();
$rectorConfig->importShortClasses();
```

## 6. Environment-Specific Configuration

For different environments, use environment-specific configuration files:

```php
// rector.dev.php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\Set\ValueObject\LevelSetList;

return static function (RectorConfig $rectorConfig): void {
    $rectorConfig->import(__DIR__ . '/rector.php');
    
    // Add development-specific configuration
    $rectorConfig->skip([
        __DIR__ . '/app/Experimental',
    ]);
};
```

## 7. Example Configuration

Here's our project's complete configuration:

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
        
        // Skip specific rules for specific paths
        TypedPropertyFromStrictConstructorRector::class => [
            __DIR__ . '/app/Legacy',
        ],
    ]);

    // Enable parallel processing
    $rectorConfig->parallel();
    
    // Auto-import names
    $rectorConfig->importNames();
};
```

## 8. Troubleshooting

If you encounter configuration issues:

1. Validate your PHP syntax: `php -l rector.php`
2. Run Rector with debug output: `vendor/bin/rector process --debug`
3. Check for conflicting rules
4. Ensure all imported classes exist
