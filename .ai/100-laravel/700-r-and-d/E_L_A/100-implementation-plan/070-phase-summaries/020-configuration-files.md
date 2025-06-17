# Phase 1.2: Configuration Files Reference

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
  - [Required Knowledge](#required-knowledge)
  - [Required Environment](#required-environment)
- [Estimated Time Requirements](#estimated-time-requirements)
- [Core Configuration Files](#core-configuration-files)
  - [Composer Configuration](#composer-configuration)
  - [NPM Configuration](#npm-configuration)
  - [Editor Configuration](#editor-configuration)
- [Code Quality Tools](#code-quality-tools)
  - [Laravel Pint](#laravel-pint)
  - [PHPStan](#phpstan)
  - [Rector](#rector)
  - [PHP Insights](#php-insights)
  - [Prettier](#prettier)
- [Testing Tools](#testing-tools)
  - [PHPUnit](#phpunit)
  - [Pest PHP](#pest-php)
  - [Infection](#infection)
- [Build Tools](#build-tools)
  - [Vite](#vite)
  - [Tailwind CSS](#tailwind-css)
- [Standardization Across Tools](#standardization-across-tools)
- [Maintenance and Updates](#maintenance-and-updates)
- [Related Documents](#related-documents)
- [Version History](#version-history)
</details>

## Overview

This document provides a comprehensive reference for all configuration files used in the Enhanced Laravel Application (ELA). It covers core configuration files, code quality tools, testing tools, and build tools.

## Prerequisites

Before working with these configuration files, ensure you have:

### Required Prior Steps
- [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) reviewed
- All Phase 0 implementation steps completed

### Required Knowledge
- Basic understanding of configuration file formats (JSON, YAML, etc.)
- Familiarity with the tools being configured
- Understanding of coding standards and quality tools

### Required Environment
- PHP 8.2 or higher
- Node.js 22.x or higher
- npm/pnpm 10.x or higher
- All development tools installed

## Estimated Time Requirements

| Task | Estimated Time |
|------|----------------|
| Review Core Configuration Files | 15 minutes |
| Review Code Quality Tool Configurations | 20 minutes |
| Review Testing Tool Configurations | 15 minutes |
| Review Build Tool Configurations | 15 minutes |
| Understand Standardization Across Tools | 10 minutes |
| **Total** | **75 minutes** |

> **Note:** These time estimates assume familiarity with the tools being configured. Actual time may vary based on experience level and the complexity of your project.

## Core Configuration Files

### Composer Configuration

**File**: `composer.json`

**Purpose**: Defines PHP dependencies and scripts for the project.

**Key Sections**:
```json
{
    "require": {
        "php": "^8.2",
        "laravel/framework": "^12.0",
        "laravel/tinker": "^2.10.1",
        "livewire/flux": "^2.1.1",
        "livewire/volt": "^1.7.0"
    },
    "require-dev": {
        "fakerphp/faker": "^1.23",
        "laravel/pail": "^1.2.2",
        "laravel/pint": "^1.18",
        "laravel/sail": "^1.41",
        "mockery/mockery": "^1.6",
        "nunomaduro/collision": "^8.6",
        "pestphp/pest": "^3.8",
        "pestphp/pest-plugin-laravel": "^3.2"
    },
    "scripts": {
        "dev": [
            "Composer\\Config::disableProcessTimeout",
            "pnpm concurrently -c \"#93c5fd,#c4b5fd,#fb7185,#fdba74\" \"php artisan serve\" \"php artisan queue:listen --tries=1\" \"php artisan pail --timeout=0\" \"pnpm run dev\" --names=server,queue,logs,vite"
        ],
        "test": [
            "@php artisan config:clear --ansi",
            "@php artisan test"
        ]
    }
}
```text

### NPM Configuration

**File**: `package.json`

**Purpose**: Defines JavaScript dependencies and scripts for the project.

**Key Sections**:
```json
{
    "scripts": {
        "build": "vite build",
        "dev": "vite"
    },
    "dependencies": {
        "@tailwindcss/vite": "^4.0.7",
        "autoprefixer": "^10.4.20",
        "axios": "^1.7.4",
        "concurrently": "^9.0.1",
        "laravel-vite-plugin": "^1.0",
        "tailwindcss": "^4.0.7",
        "vite": "^6.0"
    }
}
```php
**File**: `.npmrc`

**Purpose**: Configures npm behavior.

**Content**:
```text
engine-strict=true auto-install-peers=true strict-peer-dependencies=false
```css
### Editor Configuration

**File**: `.editorconfig`

**Purpose**: Defines consistent coding styles across different editors and IDEs.

**Content**:
```text
root = true

[*]
charset = utf-8
end_of_line = lf
indent_size = 4
indent_style = space
insert_final_newline = true
trim_trailing_whitespace = true

[*.md]
trim_trailing_whitespace = false

[*.{yml,yaml}]
indent_size = 2

[docker-compose.yml]
indent_size = 4
```php
## Code Quality Tools

### Laravel Pint

**File**: `pint.json`

**Purpose**: Configures Laravel Pint for PHP code style fixing.

**Key Sections**:
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

### PHPStan

**File**: `phpstan.neon`

**Purpose**: Configures PHPStan for static analysis of PHP code.

**Key Sections**:
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
```php
### Rector

**File**: `rector.php`

**Purpose**: Configures Rector for automated PHP code refactoring.

**Key Sections**:
```php
$rectorConfig->paths([
    __DIR__.'/app',
    __DIR__.'/bin',
    __DIR__.'/bootstrap',
    __DIR__.'/config',
    __DIR__.'/database',
    __DIR__.'/routes',
    __DIR__.'/tests',
    __DIR__.'/resources',
    __DIR__.'/packages/s-a-c/ai-prompt-addenda/src',
    __DIR__.'/packages/s-a-c/ai-prompt-addenda/tests',
]);

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
```text

**File**: `rector-type-safety.php`

**Purpose**: Configures Rector specifically for type safety improvements.

**Key Sections**:
```php
$rectorConfig->rules([
    AddReturnTypeDeclarationRector::class,
    AddVoidReturnTypeWhereNoReturnRector::class,
    ReturnTypeFromStrictNativeCallRector::class,
    AddPropertyTypeDeclarationRector::class,
    TypedPropertyFromStrictConstructorRector::class,
]);
```php
### PHP Insights

**File**: `phpinsights.php`

**Purpose**: Configures PHP Insights for code quality analysis.

**Key Sections**:
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

### Prettier

**File**: `.prettierrc.js`

**Purpose**: Configures Prettier for JavaScript, CSS, and other frontend code formatting.

**Key Sections**:
```javascript
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
```php
## Testing Tools

### PHPUnit

**File**: `phpunit.xml`

**Purpose**: Configures PHPUnit for testing.

**Key Sections**:
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
    <source>
        <include>
            <directory>app</directory>
            <directory>packages/s-a-c/ai-prompt-addenda/src</directory>
        </include>
    </source>
    <php>
        <env name="APP_ENV" value="testing"/>
        <env name="DB_CONNECTION" value="sqlite"/>
        <env name="DB_DATABASE" value=":memory:"/>
    </php>
</phpunit>
```text

### Pest PHP

**File**: `pest.config.php`

**Purpose**: Configures Pest PHP for testing.

**Key Sections**:
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
```javascript
### Infection

**File**: `infection.json5`

**Purpose**: Configures Infection for mutation testing.

**Key Sections**:
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

## Build Tools

### Vite

**File**: `vite.config.js`

**Purpose**: Configures Vite for frontend asset building.

**Example Content**:
```javascript
import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
```javascript
### Tailwind CSS

**File**: `tailwind.config.js`

**Purpose**: Configures Tailwind CSS for styling.

**Example Content**:
```javascript
/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {},
  },
  plugins: [],
}
```text

## Standardization Across Tools

The configuration files share several standardized elements:

1. **Path Standardization**:
   All tools use the same set of paths for analysis:
   - `app`: Application code
   - `config`: Configuration files
   - `database`: Database migrations and seeders
   - `routes`: Route definitions
   - `tests`: Test files
   - `bootstrap`: Bootstrap files
   - `resources`: Frontend resources
   - `packages/s-a-c/ai-prompt-addenda/src`: Package source code
   - `packages/s-a-c/ai-prompt-addenda/tests`: Package tests

2. **Exclusion Standardization**:
   All tools exclude the same set of paths:
   - `vendor`: Composer dependencies
   - `node_modules`: npm dependencies
   - `storage`: Storage files
   - `bootstrap/cache`: Cache files
   - `public`: Public assets
   - `reports/rector/cache`: Rector cache files

3. **PHP Version Standardization**:
   All tools target PHP 8.4:
   - PHPStan: `phpVersion: 80400`
   - Rector: `$rectorConfig->phpVersion(80400)`
   - PHP Insights: `'version' => '8.4'`

4. **Performance Settings Standardization**:
   All tools use similar performance settings:
   - Parallel processing: 4-8 threads/processes
   - Memory limits: 1-2GB
   - Cache directories: `reports/{tool}/cache`

## Maintenance and Updates

To maintain and update these configuration files:

1. **Version Updates**:
   - When updating PHP or package versions, update all relevant configuration files
   - Ensure PHP version is consistent across all tools

2. **Path Updates**:
   - When adding new directories, update all path configurations
   - Keep exclusions consistent across all tools

3. **Rule Updates**:
   - When updating coding standards, update all relevant tools
   - Ensure rules are consistent across tools

4. **Performance Updates**:
   - Adjust performance settings based on available resources
   - Keep settings consistent across tools

## Related Documents

- [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) - For a summary of Phase 0 implementation
- [Code Quality Tools](060-configuration/040-code-quality-tools.md) - For code quality tools configuration details
- [Testing Configuration](060-configuration/030-testing-configuration.md) - For testing configuration details
- [GitHub Workflows](080-infrastructure/010-github-workflows.md) - For CI/CD workflow configuration

## Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0.0 | 2025-05-15 | Initial version | AI Assistant |
| 1.0.1 | 2025-05-17 | Updated file references and links | AI Assistant |
| 1.0.2 | 2025-05-17 | Added standardized prerequisites, estimated time requirements, related documents, and version history | AI Assistant |

---

**Previous Step:** [Phase 0 Summary](070-phase-summaries/010-phase0-summary.md) | **Next Step:** [GitHub Workflows](080-infrastructure/010-github-workflows.md)
