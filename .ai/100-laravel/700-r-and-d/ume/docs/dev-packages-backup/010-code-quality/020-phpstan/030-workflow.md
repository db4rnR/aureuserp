# PHPStan Workflow
# PHPStan Workflow

## 1. Development Workflow

This guide outlines how PHPStan integrates into our daily development workflow with Laravel 12 and PHP 8.4.

## 2. Local Development

### 2.1. Before Committing Code

Run PHPStan on changed files before committing:
This document outlines the recommended workflow for using PHPStan in our Laravel 12 project.

## Daily Development Workflow

### Before Starting Work

1. Pull the latest code and baseline:

```bash
git pull
```

2. Run PHPStan to ensure you're starting clean:

```bash
composer analyze
```

### During Development

1. Write your code with type hints and docblocks
2. Check for errors regularly:

```bash
composer analyze
```

3. Fix any new errors as they're introduced

### Before Committing

1. Run a final check:

```bash
composer analyze
```

2. If you've fixed existing baseline errors:

```bash
composer analyze:fresh-baseline
git add phpstan-baseline.neon
```

## Feature Development Workflow

For larger features, you may want a more structured approach:

### 1. Establish Feature Baseline

Before starting work on a new feature:

```bash
# Create a branch-specific baseline
git checkout -b feature/new-feature
composer analyze:fresh-baseline
git add phpstan-baseline.neon
git commit -m "Add initial PHPStan baseline for feature"
```

### 2. Implement with Progressive Checking

As you implement your feature:

```bash
# Check regularly
composer analyze

# If you fix existing errors, update the baseline
composer analyze:fresh-baseline
git add phpstan-baseline.neon
git commit -m "Update PHPStan baseline with fixes"
```

### 3. Feature Completion

Before merging your feature:

```bash
# Final check
composer analyze

# Ensure baseline is current
composer analyze:fresh-baseline
git add phpstan-baseline.neon
git commit -m "Final PHPStan baseline update for feature"
```

## Integration with Other Tools

### Combining with Laravel Pint

For comprehensive code quality checks:

```bash
# Format, then analyze
composer format
composer analyze
```

### CI Pipeline Integration

Our CI pipeline runs:

```bash
# First check formatting
composer test:lint

# Then run static analysis
composer analyze
```

## Error Resolution Strategies

### 1. Fix the Underlying Issue

The best approach is to fix the actual type issue:
# PHPStan Workflow

This guide covers the recommended workflow for using PHPStan in our Laravel 12 project's development lifecycle.

## 1. Development Workflow

### 1.1. Local Development

During active development, follow these steps:

1. **Before writing code**:
   Run PHPStan to ensure your starting point is clean:
   ```bash
   composer analyze
   ```

2. **While writing code**:
   Periodically check for new type errors:
   ```bash
   # Analyze only changed files (faster)
   composer analyze:changed
   
   # Analyze specific directories
   composer analyze app/Services
   ```

3. **Before committing**:
   Run a full analysis:
   ```bash
   composer analyze
   ```

4. **When fixing existing errors**:
   Update the baseline after fixing errors:
   ```bash
   composer analyze:fresh-baseline
   ```

### 1.2. Pull Request Workflow

When working on pull requests:

1. **Creating a PR**:
   - Ensure PHPStan passes without new errors
   - Include baseline updates if you've fixed baseline errors

2. **Reviewing PRs**:
   - Check that PHPStan CI checks pass
   - Verify baseline changes reflect actual error fixes
   - Reject PRs that introduce new type errors

3. **After merging**:
   - Verify PHPStan passes on the target branch
   - Update baseline if necessary

## 2. Optimization Strategies

### 2.1. Incremental Analysis

For faster feedback during development:
```php
// Before: Mixed type
function getValue($id) {
    return Data::find($id);
}

// After: Specific return type
function getValue(int $id): ?Model {
    return Data::find($id);
}
```

### 2. Add PHPDoc Annotations

When types can't be expressed in PHP type hints:

```php
// Before: No type information
function getConfig() {
    return config('app.items');
}

// After: With PHPDoc type
/**
 * @return array<string, string>
 */
function getConfig(): array {
    return config('app.items');
}
```

### 3. Baseline as Last Resort

Only use the baseline for:
- Legacy code you can't immediately fix
- Third-party code issues
- Framework-specific patterns not recognized by PHPStan
