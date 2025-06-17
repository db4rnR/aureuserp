# Code Quality Tools Standardization

## 1. Overview

This document outlines the standardized configuration approach for all code quality tools used in the project. The goal
is to ensure consistency across tools, reduce configuration drift, and maintain a high standard of code quality.

## 2. Standardized Configuration

All code quality tools have been configured with the following standardized settings:

### 2.1. PHP Version

All tools that support PHP version specification are set to PHP 8.4 (80400).

### 2.2. Paths

#### Include Paths

- `app`
- `config`
- `database`
- `routes`
- `tests`
- `bootstrap`
- `resources`
- `packages/s-a-c/ai-prompt-addenda/src`
- `packages/s-a-c/ai-prompt-addenda/tests`

#### Exclude Paths

- `vendor`
- `node_modules`
- `storage`
- `bootstrap/cache`
- `public`

### 2.3. Quality Thresholds

- PHPStan Level: 10 (maximum strictness)
- PHPInsights:
  - Minimum Quality: 85%
  - Minimum Complexity: 85%
  - Minimum Architecture: 85%
  - Minimum Style: 85%
- Infection:
  - Minimum MSI: 85%
  - Minimum Covered MSI: 90%

### 2.4. Parallel Processing

- Number of Processes: 8
- Memory Limit: 1G

### 2.5. Reporting

All tools store reports and cache in a standardized directory structure:

```
reports/
  phpstan/
  pint/
  rector/
  phpinsights/
  infection/
```

## 3. Meta-Configuration

A meta-configuration file has been created at `config/code-quality.php` that documents these standardized settings. This
file serves as the single source of truth for code quality configuration standards.

## 4. Tool-Specific Configurations

### 4.1. PHPStan/Larastan

Configuration file: `phpstan.neon.dist`

Key standardized settings:

- Level: 10
- PHP Version: 8.4
- Standardized paths
- Parallel processes: 8
- Cache directory: `reports/phpstan/cache`

### 4.2. Laravel Pint

Configuration file: `pint.json`

Key standardized settings:

- PSR-12 rules with Laravel preset
- Standardized paths
- Parallel processes: 8
- Memory limit: 1G
- Report path: `reports/pint/errors.log`

### 4.3. Rector

Configuration file: `rector.php`

Key standardized settings:

- PHP Version: 8.4
- Standardized paths
- Parallel processes: 8
- Memory limit: 1G
- Cache directory: `reports/rector/cache`

### 4.4. PHPInsights

Configuration file: `phpinsights.php`

Key standardized settings:

- PHP Version: 8.4
- Standardized paths
- Quality thresholds: 85%
- Threads: 8
- Cache directory: `reports/phpinsights/cache`

### 4.5. Infection

Configuration file: `infection.json5`

Key standardized settings:

- Standardized paths
- Threads: 8
- MSI threshold: 85%
- Report paths: `reports/infection/`

## 5. Maintaining Consistency

To ensure these configurations remain consistent:

1. Always refer to the meta-configuration when updating tool configurations
2. Run the configuration validation script after making changes
3. Document any necessary deviations from the standard

## 6. Configuration Validation

A validation script is available to check that all tool configurations adhere to the standardized settings:

```bash
php artisan code-quality:validate
```

This script will report any inconsistencies between the actual tool configurations and the standardized settings.
