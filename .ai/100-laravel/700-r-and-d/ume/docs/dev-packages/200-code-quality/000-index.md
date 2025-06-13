# Code Quality Packages

This directory contains documentation for all code quality related packages used in the project.

## 1. Overview

Code quality tools help maintain high standards in the codebase by enforcing coding standards, detecting potential bugs, and identifying areas for improvement. This project uses a variety of code quality tools to ensure the codebase remains clean, consistent, and maintainable.

## 2. Code Quality Packages

| Package | Description | Documentation |
|---------|-------------|---------------|
| [PHPStan/Larastan](010-phpstan/000-index.md) | Static analysis tool | [010-phpstan/](010-phpstan/) |
| [Laravel Pint](020-pint.md) | Code style fixer | [020-pint.md](020-pint.md) |
| [Rector](030-rector/000-index.md) | Automated refactoring tool | [030-rector/](030-rector/) |
| [PHP Insights](040-phpinsights.md) | Code quality metrics | [040-phpinsights.md](040-phpinsights.md) |
| [PHP Parallel Lint](050-parallel-lint.md) | Fast PHP linter | [050-parallel-lint.md](050-parallel-lint.md) |
| [Laravel Blade Comments](060-blade-comments.md) | Blade template comments | [060-blade-comments.md](060-blade-comments.md) |
| [Security Advisories](070-security-advisories.md) | Security vulnerability checking | [070-security-advisories.md](070-security-advisories.md) |

## 3. Code Quality Workflow

The typical code quality workflow in this project includes:

1. Formatting code with Laravel Pint
2. Running static analysis with PHPStan/Larastan
3. Applying automated refactoring with Rector
4. Checking for security vulnerabilities
5. Measuring code quality metrics with PHP Insights

## 4. Composer Commands

This project includes several Composer scripts for code quality:

```bash
# Format code
composer format

# Check code style without fixing
composer lint

# Run static analysis
composer analyze

# Apply automated refactoring
composer refactor

# Run comprehensive quality checks
composer quality:check

# Fix code quality issues
composer quality:fix
```

## 5. Configuration

Code quality tools are configured through:

- `phpstan.neon` - PHPStan configuration
- `pint.json` - Laravel Pint configuration
- `rector.php` - Rector configuration

## 6. Best Practices

- Run code quality checks before committing code
- Gradually increase PHPStan level as code quality improves
- Use baseline files to manage existing issues
- Apply automated fixes when possible
- Review and understand automated changes
