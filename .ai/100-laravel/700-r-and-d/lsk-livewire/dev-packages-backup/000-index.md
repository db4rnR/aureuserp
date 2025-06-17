# Dev Packages Documentation

This documentation covers all development packages used in the project.

## 1. Documentation Structure

All documentation follows the hierarchical numbering system:
- Main sections are numbered (1, 2, 3...)
- Subsections use decimal notation (1.1, 1.2...)
- Deeper levels add additional decimals (1.1.1, 1.1.2...)

### 1.1. Folder Organization

All folders are numbered with a 3-digit prefix starting with `005-` and incrementing by 5:

- 000-index.md (this file)
- 005-testing/ - Testing-related packages
- 010-code-quality/ - Code quality and static analysis tools
- 015-debugging/ - Debugging and monitoring tools
- 020-dev-environment/ - Local development environment packages
- 025-code-generation/ - Code generation utilities
- 030-recommended/ - Recommended additional packages

### 1.2. Package Categories

Development packages are organized into the following categories:

1. Testing Packages
2. Code Quality Tools
3. Debugging Tools
4. Development Environment
5. Code Generation
# Development Packages Documentation

This documentation covers all development packages used in our Laravel 12 project with PHP 8.4.

## 1. Overview

Our project follows Laravel best practices and conventions while leveraging powerful development tools to ensure code quality, maintainability, and developer productivity.

## 2. Documentation Structure

- [2.1. Project Requirements Document](005-prd.md)
- [2.2. Implementation Plan](010-implementation-plan.md)
- [2.3. Progress Tracker](015-progress-tracker.md)
- [2.4. Completion Report](020-completion-report.md)

## 3. Categories

| Category | Description | Documentation |
|----------|-------------|---------------|
| [Code Quality](010-code-quality/000-index.md) | Static analysis, linting, and code metrics | [PHPStan](010-code-quality/020-phpstan/000-index.md), [Pint](010-code-quality/030-pint/000-index.md) |
| [Development Environment](020-dev-environment/000-index.md) | Local development tooling | Laravel Sail, IDE Helper |
| [Debugging](015-debugging/000-index.md) | Debugging and inspection tools | Laravel Ray, Debugbar |
| [Testing](005-testing/000-index.md) | Testing frameworks and tools | Pest, Paratest |
| [Code Generation](025-code-generation/000-index.md) | Scaffolding and generators | Model generators |
| [Recommended Packages](030-recommended/000-index.md) | Additional recommended packages | Various utilities |

## 4. Package Quick Links

- [PHPStan](010-code-quality/020-phpstan/000-index.md) - Static analysis at level 10
- [Laravel Pint](010-code-quality/030-pint/000-index.md) - Code style formatter
- [Rector](010-code-quality/050-rector/000-index.md) - Automated refactoring
- [Pest](005-testing/010-pest/000-index.md) - Testing framework
- [Paratest](005-testing/020-paratest/000-index.md) - Parallel testing
- [Laravel Ray](015-debugging/010-laravel-ray/000-index.md) - Debugging
- [Laravel Debugbar](015-debugging/020-debugbar/000-index.md) - Performance profiling

## 5. Composer Commands

See our [scripts-descriptions](../composer-scripts.md) documentation for all available commands.

## 6. Recent Updates

- **Consolidated PHPStan Documentation** - All PHPStan documentation has been moved to [010-code-quality/020-phpstan/](010-code-quality/020-phpstan/) for better organization
## 2. Package Management

All development packages are managed using Composer:

```bash
// Install all dev dependencies
composer install --dev

// Update dev dependencies
composer update --dev

// Add a specific dev package
composer require --dev vendor/package
```

## 3. Contributing

When adding new development packages:

1. Add the package to `composer.json`
2. Document the package in the appropriate section
3. Include configuration examples if applicable
4. Add usage examples to help other developers
