# Type Safety Workflow

This guide explains how to use our combined Larastan and Rector workflow to maximize type safety in the codebase.

## 1. Overview

The type safety workflow combines:

- **Larastan** (PHPStan for Laravel): For comprehensive type detection at a high strictness level
- **Rector**: For automated fixing of type-related issues

## 2. Workflow Steps

### 2.1. Detect Type Issues

```bash
composer type-safety:detect
```

This runs Larastan at level 8 (very strict) with a table output format to clearly show all type-related issues.

### 2.2. Auto-Fix Type Issues

```bash
composer type-safety:autofix
```

This runs Rector with specialized rules for adding:

- Return type declarations
- Parameter type declarations
- Property type declarations
- Void return types
- Closure return types

### 2.3. Verify Fixes

```bash
composer type-safety:verify
```

This runs Larastan again to verify which issues were fixed and which still need manual attention.

### 2.4. Run the Complete Workflow

```bash
composer type-safety:workflow
```

This runs all three steps in sequence: detect, autofix, and verify.

## 3. Managing the Baseline

For large codebases, it's often practical to create a baseline of existing issues and focus on preventing new ones:

```bash
composer type-safety:baseline
```

This generates a baseline file that excludes current issues from future error reports.

## 4. Best Practices

1. **Run the workflow regularly** - Ideally after each significant feature addition
2. **Focus on one component at a time** - Edit the configuration files to target specific directories
3. **Manual fixes** - Address issues that Rector couldn't fix automatically
4. **Commit type improvements separately** - Keep type-related changes in dedicated commits
5. **Update docblocks** - Ensure PHPDoc comments match the new type declarations

## 5. Configuration Files

- `phpstan-strict.neon`: Strict Larastan configuration
- `rector-type-safety.php`: Rector configuration focused on type declarations

## 6. Common Issues and Solutions

### 6.1. Mixed Types

When Larastan reports "Parameter $x of method Y() has no type specified":

```php
// Before
public function process($data)

// After
public function process(array $data): array
```

### 6.2. Nullable Types

When a value might be null:

```php
// Before
public function findUser($id)

// After
public function findUser(?int $id): ?User
```

### 6.3. Union Types

When a value can be one of several types:

```php
// Before
public function transform($input)

// After
public function transform(string|int $input): array|null
```
