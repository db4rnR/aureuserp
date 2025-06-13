# PHPStan Configuration

## 1. Overview

This guide covers the configuration options for PHPStan and Larastan in our Laravel 12 project. Proper configuration ensures accurate analysis tailored to our project's needs.

## 2. Configuration File

The main configuration file is `phpstan.neon` in the project root. This file includes all settings for PHPStan analysis.

### 2.1. Basic Structure

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

## 3. Key Configuration Options

### 3.1. Analysis Level

PHPStan offers 10 levels of strictness (0-9, with 0 being the most permissive and 9 the strictest):

```yaml
parameters:
    level: 5  # Moderate strictness
```

Our project aims for level 8, but we use a baseline to manage existing errors.

### 3.2. Analyzed Paths

Specify which directories to analyze:

```yaml
parameters:
    paths:
        - app
        - config
        - database
        - routes
        - tests
```

### 3.3. Excluded Paths

Exclude specific paths from analysis:

```yaml
parameters:
    excludePaths:
        - ./storage/*
        - ./bootstrap/cache/*
        - ./node_modules/*
```

### 3.4. Memory Limit

For large projects, increase the memory limit:

```yaml
parameters:
    memoryLimit: 1G
```

### 3.5. Parallel Processing

Enable parallel processing for faster analysis:

```yaml
parameters:
    parallel:
        maximumNumberOfProcesses: 4
        processTimeout: 300.0
```

## 4. Laravel-Specific Configuration

### 4.1. Model Properties

Configure how PHPStan handles Laravel model properties:

```yaml
parameters:
    checkModelProperties: true
    checkMissingIterableValueType: false
```

### 4.2. Blade Templates

Enable analysis of Blade templates:

```yaml
parameters:
    checkGenericClassInNonGenericObjectType: false
    checkPhpDocMissingReturn: false
    treatPhpDocTypesAsCertain: false
```

### 4.3. Livewire Components

Special handling for Livewire components:

```yaml
parameters:
    ignoreErrors:
        - '#Property [a-zA-Z0-9\\_]+::\$listeners is never written, only read.#'
```

## 5. Custom Rules

Add custom PHPStan rules:

```yaml
services:
    -
        class: App\PHPStan\Rules\NoFacadesInControllers
        tags:
            - phpstan.rules.rule
```

## 6. Baseline Configuration

Configure baseline usage:

```yaml
parameters:
    ignoreErrors:
        - '#Dynamic call to static method#'
    
    # Include the baseline file
    baseline: phpstan-baseline.neon
```

## 7. Advanced Configuration

### 7.1. Type Aliases

Define type aliases for complex types:

```yaml
parameters:
    typeAliases:
        Collection: 'Illuminate\Support\Collection<int, %s>'
        EloquentCollection: 'Illuminate\Database\Eloquent\Collection<int, %s>'
```

### 7.2. Stub Files

Use stub files for external libraries:

```yaml
parameters:
    stubFiles:
        - stubs/Laravel/Collection.stub
        - stubs/Laravel/Model.stub
```

## 8. Environment-Specific Configuration

For different environments, use environment-specific configuration files:

```yaml
# phpstan.dev.neon
includes:
    - phpstan.neon

parameters:
    level: 3  # Less strict for development
```

## 9. Example Configuration

Here's our project's complete configuration:

```yaml
includes:
    - ./vendor/larastan/larastan/extension.neon

parameters:
    level: 8
    paths:
        - app
        - config
        - database
        - routes
        - tests
    excludePaths:
        - ./storage/*
        - ./bootstrap/cache/*
        - ./node_modules/*
    checkMissingIterableValueType: false
    checkGenericClassInNonGenericObjectType: false
    checkModelProperties: true
    
    ignoreErrors:
        - '#Dynamic call to static method#'
    
    baseline: phpstan-baseline.neon
    
    parallel:
        maximumNumberOfProcesses: 4
        processTimeout: 300.0
    
    memoryLimit: 1G
```

## 10. Troubleshooting

If you encounter configuration issues:

1. Validate your NEON syntax: `vendor/bin/phpstan analyse --configuration=phpstan.neon --debug`
2. Check for conflicting rules
3. Ensure all included files exist
4. Verify memory settings if you experience out-of-memory errors
