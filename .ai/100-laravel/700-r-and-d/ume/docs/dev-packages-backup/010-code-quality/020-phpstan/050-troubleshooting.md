# PHPStan Troubleshooting Guide

This document covers common PHPStan issues encountered in our Laravel 12 project and their solutions.

## Common Issues and Solutions

### 1. Duplicate Parameters Error

**Problem:**
```
Error while loading phpstan-baseline.neon:
Duplicated key 'parameters' on line X
```

**Solution:**
Generate a fresh baseline using our custom script:

```bash
composer analyze:fresh-baseline
```

This creates a clean baseline file without dependency issues.

### 2. Memory Limit Errors

**Problem:**
```
PHP Fatal error: Allowed memory size of X bytes exhausted
```

**Solution:**
Increase memory limit:

```bash
vendor/bin/phpstan analyse --memory-limit=2G
```

Or for persistent configuration, add to phpstan.neon:

```yaml
parameters:
    memoryLimit: 2G
```

### 3. Analysis Takes Too Long

**Problem:**
PHPStan analysis runs very slowly, especially on large codebases.

**Solution:**
Enable parallelization in phpstan.neon.dist:

```yaml
parameters:
    parallel:
        maximumNumberOfProcesses: 16
        jobSize: 20
        minimumNumberOfJobsPerProcess: 2
```

And clear the cache before running:

```bash
composer analyze:clear-cache
```

### 4. Laravel-Specific Type Errors

**Problem:**
Errors with Laravel facades, eloquent relationships, etc.:

```
Cannot call method relationship() on mixed.
```

**Solution:**
Add PHPDoc annotations:

```php
/** @var \App\Models\User $user */
$user = Auth::user();
```

Or add to the ignoreErrors section in phpstan.neon:

```yaml
parameters:
    ignoreErrors:
        - '#Cannot call method relationship\(\) on mixed.#'
```

### 5. Livewire/Volt Component Issues

**Problem:**
Errors with Livewire or Volt components:

```
Expression on a separate line does not do anything
```

**Solution:**
Add to ignoreErrors in phpstan.neon:

```yaml
parameters:
    ignoreErrors:
        - '#Expression "new.*#\[\\Livewire\\Attributes\\Layout\(.*\).*class extends \\Livewire\\Volt\\Component.*" on a separate line does not do anything\.#'
```

### 6. Issues with External Packages

**Problem:**
Errors in vendor code:

```
Call to undefined method Vendor\Package\Class::method()
```

**Solution:**
Add stub files in the stubs directory and reference them in phpstan.neon:

```yaml
parameters:
    stubFiles:
        - stubs/VendorClass.stub
```

### 7. Issues with Blade Templates

**Problem:**
Errors in blade templates with undefined variables:

```
Undefined variable: $variable
```

**Solution:**
Add to ignoreErrors in phpstan.neon:

```yaml
parameters:
    ignoreErrors:
        - '#Undefined variable: \$[a-zA-Z0-9_]+#'
```

### 8. Custom Rule Issues

**Problem:**
Custom PHPStan rule causing false positives.

**Solution:**
Create a rule exception:

```yaml
services:
    -
        class: App\PHPStan\Rules\CustomRule
        tags:
            - phpstan.rules.rule
        arguments:
            excludePaths: 
                - app/Exceptions/*
```

## Debugging PHPStan

### Verbose Output

For detailed debugging information:

```bash
vendor/bin/phpstan analyse --debug
```

### Finding Error Sources

To locate the rule causing an error:

```bash
vendor/bin/phpstan analyse --debug --error-format=raw
```

### Testing Rules in Isolation

Test only one rule at a time:

```bash
vendor/bin/phpstan analyse --debug --level=0 -c phpstan-rule-test.neon
```

## Getting Help

For more complex issues:

1. Check the [official PHPStan documentation](https://phpstan.org/user-guide/getting-started)
2. Search [PHPStan issues on GitHub](https://github.com/phpstan/phpstan/issues)
3. Check [Larastan documentation](https://github.com/larastan/larastan) for Laravel-specific help
