# Development Packages Documentation

This documentation covers all development packages used in our Laravel 12 project with PHP 8.4.

## 1. Overview

Development packages are essential tools that help improve code quality, streamline testing, facilitate debugging, and enhance the development experience. This documentation provides comprehensive information about all development packages used in this project.

## 2. Documentation Structure

This documentation follows a consistent numbering system:
- Main directories use a 3-digit prefix (e.g., 100-testing)
- Index files within directories are named 000-index.md
- Individual package documentation files use a 3-digit prefix (e.g., 010-pest.md)

## 3. Package Categories

| Category | Description | Key Packages |
|----------|-------------|--------------|
| [Project Documentation](005-project-docs/000-index.md) | Project-level documentation | PRD, Implementation Plan, Progress Tracker |
| [Testing](100-testing/000-index.md) | Testing frameworks and tools | Pest, Paratest, Dusk, Infection |
| [Code Quality](200-code-quality/000-index.md) | Static analysis and code style | PHPStan, Pint, Rector, PHPInsights |
| [Debugging](300-debugging/000-index.md) | Debugging and monitoring tools | Debugbar, Ray, Pail, Telescope |
| [Dev Environment](400-dev-environment/000-index.md) | Local development tools | Sail, Peck, Solo |
| [Code Generation](500-code-generation/000-index.md) | Code generators | Eloquent Model Generator, IDE Helper |
| [Utilities](600-utilities/000-index.md) | Utility packages | Collision, Faker, Var Dumper, Polyfill PHP 8.4 |
| [Recommended](700-recommended/000-index.md) | Recommended additional packages | Various recommendations |
| [Configuration Examples](configs/000-index.md) | Example configurations | PHPStan, Pint, Rector |
| [Templates](templates/000-index.md) | Documentation templates | Package, Category, Configuration |

## 4. Project Documentation

The [Project Documentation](005-project-docs/000-index.md) section contains information about the development packages implementation:

- [Product Requirements Document](005-project-docs/005-prd.md)
- [Implementation Plan](005-project-docs/010-implementation-plan.md)
- [Progress Tracker](005-project-docs/015-progress-tracker.md)
- [Completion Report](005-project-docs/020-completion-report.md)

## 5. Testing Packages

The [Testing Packages](100-testing/000-index.md) section covers all testing-related packages:

- [Pest](100-testing/010-pest/000-index.md) - Modern testing framework for PHP
- [Paratest](100-testing/020-paratest.md) - Parallel testing for PHPUnit/Pest
- [Laravel Dusk](100-testing/030-dusk.md) - Browser testing for Laravel
- [Infection](100-testing/040-infection.md) - Mutation testing framework
- [Mockery](100-testing/050-mockery.md) - Mock object framework

## 6. Code Quality Packages

The [Code Quality Packages](200-code-quality/000-index.md) section covers all code quality related packages:

- [PHPStan/Larastan](200-code-quality/010-phpstan/000-index.md) - Static analysis tool
- [Laravel Pint](200-code-quality/020-pint.md) - Code style fixer
- [Rector](200-code-quality/030-rector/000-index.md) - Automated refactoring tool
- [PHP Insights](200-code-quality/040-phpinsights.md) - Code quality metrics
- [PHP Parallel Lint](200-code-quality/050-parallel-lint.md) - Fast PHP linter
- [Laravel Blade Comments](200-code-quality/060-blade-comments.md) - Blade template comments
- [Security Advisories](200-code-quality/070-security-advisories.md) - Security vulnerability checking

## 7. Debugging Packages

The [Debugging Packages](300-debugging/000-index.md) section covers all debugging-related packages:

- [Laravel Debugbar](300-debugging/010-debugbar.md) - Performance and debugging toolbar
- [Ray](300-debugging/020-ray.md) - Debug with Ray app
- [Laravel Pail](300-debugging/030-pail.md) - Log viewer
- [Laravel Telescope](300-debugging/040-telescope.md) - Debug assistant
- [Horizon Watcher](300-debugging/050-horizon-watcher.md) - Queue monitoring
- [Web Tinker](300-debugging/060-web-tinker.md) - In-browser REPL

## 8. Development Environment Packages

The [Development Environment Packages](400-dev-environment/000-index.md) section covers all development environment related packages:

- [Laravel Sail](400-dev-environment/010-sail.md) - Docker development environment
- [Peck PHP](400-dev-environment/020-peck.md) - PHP development server
- [Solo](400-dev-environment/030-solo.md) - Development environment tool
- [Composer Normalize](400-dev-environment/040-composer-normalize.md) - Composer file normalizer

## 9. Code Generation Packages

The [Code Generation Packages](500-code-generation/000-index.md) section covers all code generation related packages:

- [Eloquent Model Generator](500-code-generation/010-eloquent-model-generator.md) - Generate Eloquent models from database
- [Laravel IDE Helper](500-code-generation/020-ide-helper.md) - Generate IDE helper files

## 10. Utility Packages

The [Utility Packages](600-utilities/000-index.md) section covers utility packages used in the project's development environment:

- [Collision](600-utilities/010-collision.md) - Error reporting tool
- [Faker](600-utilities/020-faker.md) - Test data generation
- [Var Dumper](600-utilities/030-var-dumper.md) - Variable inspection
- [Polyfill PHP 8.4](600-utilities/040-polyfill-php84.md) - PHP 8.4 features in lower PHP versions

## 11. Recommended Packages

The [Recommended Packages](700-recommended/000-index.md) section contains documentation for recommended development packages that are not currently included in the project but could be beneficial additions.

## 12. Configuration Examples

The [Configuration Examples](configs/000-index.md) directory contains example configuration files for various packages:

- [PHPStan configuration](configs/phpstan.neon)
- [Pint configuration](configs/pint.json)
- [Rector configuration](configs/rector.php)

## 13. Templates

The [Templates](templates/000-index.md) directory contains templates for creating documentation for development packages:

- [Package documentation template](templates/package-documentation.md)
- [Category index template](templates/category-index.md)
- [Configuration example template](templates/configuration-example.md)

## 14. Composer Commands

This project includes many helpful Composer scripts for working with development packages:

```bash
# Code quality
composer format           # Format code with Laravel Pint
composer analyze          # Run static analysis with PHPStan
composer refactor         # Apply automated refactoring with Rector

# Testing
composer test             # Run tests with Pest
composer test:parallel    # Run tests in parallel
composer test:coverage    # Run tests with code coverage

# Development
composer validate:deps    # Validate dependencies
```

See our [scripts-descriptions](../composer-scripts.md) documentation for all available commands.

## 15. Contributing

When adding new development packages:

1. Add the package to composer.json
2. Create documentation in the appropriate category
3. Add configuration examples if applicable
4. Update this index file with links to the new documentation
