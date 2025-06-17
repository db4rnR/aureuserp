# Rector Type Perfect

## 1. Overview

Rector Type Perfect is an extension for Rector that focuses on improving type declarations in your PHP code. It provides additional rules specifically designed to enhance type safety, add missing type declarations, and fix incorrect types.

### 1.1. Package Information

- **Package Name**: rector/type-perfect
- **Version**: ^0.1.1
- **GitHub**: [https://github.com/rectorphp/type-perfect](https://github.com/rectorphp/type-perfect)
- **Documentation**: [https://github.com/rectorphp/type-perfect#readme](https://github.com/rectorphp/type-perfect#readme)

## 2. Key Features

- Advanced type inference
- Missing type declaration detection
- Incorrect type fixing
- Union type optimization
- Nullable type handling
- Return type improvement
- Parameter type enhancement
- Property type declaration
- PHPDoc type synchronization
- Integration with Rector
- Support for PHP 8.4 features

## 3. Installation

```bash
composer require --dev rector/type-perfect
```

## 4. Configuration

### 4.1. Basic Configuration

Add Type Perfect rules to your `rector.php` configuration file:

```php
<?php

declare(strict_types=1);

use Rector\Config\RectorConfig;
use Rector\TypePerfect\Set\TypePerfectSetList;

return static function (RectorConfig $rectorConfig): void {
    // ... your existing configuration
    
    // Add Type Perfect rule sets
    $rectorConfig->sets([
        TypePerfectSetList::STANDARD,
    ]);
};
```

### 4.2. Available Rule Sets

Type Perfect provides several rule sets:

```php
// Standard rules (recommended for most projects)
TypePerfectSetList::STANDARD

// Aggressive rules (may require more manual verification)
TypePerfectSetList::AGGRESSIVE

// PHPDoc synchronization rules
TypePerfectSetList::PHPDOC_SYNC
```

### 4.3. Individual Rules

You can also apply individual rules:

```php
use Rector\TypePerfect\Rector\ClassMethod\AddReturnTypeDeclarationRector;
use Rector\TypePerfect\Rector\Property\AddPropertyTypeDeclarationRector;

$rectorConfig->rule(AddReturnTypeDeclarationRector::class);
$rectorConfig->rule(AddPropertyTypeDeclarationRector::class);
```

## 5. Usage

### 5.1. Basic Usage

Run Rector with Type Perfect rules:

```bash
vendor/bin/rector process
```

### 5.2. Dry Run

Preview changes without modifying files:

```bash
vendor/bin/rector process --dry-run
```

### 5.3. Specific Directories

Process specific directories:

```bash
vendor/bin/rector process app/Models
```

### 5.4. Specific Files

Process specific files:

```bash
vendor/bin/rector process app/Models/User.php
```

## 6. Integration with Laravel 12 and PHP 8.4

Type Perfect is fully compatible with Laravel 12 and PHP 8.4. It helps:

1. Add PHP 8.4 specific type declarations
2. Improve type safety in Laravel applications
3. Fix incorrect types in Laravel models and controllers
4. Enhance PHPDoc comments for better IDE support

## 7. Key Rules

### 7.1. Return Type Declaration

Adds missing return type declarations:

```php
// Before
function getUserName()
{
    return $user->name;
}

// After
function getUserName(): string
{
    return $user->name;
}
```

### 7.2. Property Type Declaration

Adds missing property type declarations:

```php
// Before
class User
{
    private $name;
}

// After
class User
{
    private string $name;
}
```

### 7.3. Parameter Type Declaration

Adds missing parameter type declarations:

```php
// Before
function setName($name)
{
    $this->name = $name;
}

// After
function setName(string $name): void
{
    $this->name = $name;
}
```

### 7.4. Union Type Optimization

Optimizes union types:

```php
// Before
function process(string|null $value): string|null
{
    return $value;
}

// After
function process(?string $value): ?string
{
    return $value;
}
```

### 7.5. PHPDoc Synchronization

Synchronizes PHPDoc with type declarations:

```php
// Before
/**
 * @param string $name
 * @return void
 */
function setName($name)
{
    $this->name = $name;
}

// After
/**
 * @param string $name
 * @return void
 */
function setName(string $name): void
{
    $this->name = $name;
}
```

## 8. Best Practices

1. **Start with Dry Run**: Always use `--dry-run` first to preview changes
2. **Apply Gradually**: Apply Type Perfect rules gradually, starting with standard rules
3. **Review Changes**: Carefully review all changes, especially with aggressive rules
4. **Combine with PHPStan**: Use Type Perfect alongside PHPStan for comprehensive type checking
5. **Test After Refactoring**: Run tests after applying Type Perfect rules to ensure functionality

## 9. Troubleshooting

### 9.1. Incorrect Type Inference

If Type Perfect infers incorrect types:

1. Add PHPDoc annotations to provide type hints
2. Skip specific rules for problematic files
3. Use more conservative rule sets

### 9.2. Conflicts with Other Rules

If Type Perfect conflicts with other Rector rules:

1. Apply Type Perfect rules separately
2. Prioritize rules by applying them in a specific order
3. Skip conflicting rules

### 9.3. Performance Issues

If you experience performance issues:

1. Process smaller parts of your codebase at a time
2. Use parallel processing
3. Apply only specific rules instead of entire rule sets
