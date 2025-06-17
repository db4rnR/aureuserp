# Code Quality Tools

This documentation covers all code quality and static analysis tools used in development.

## 1. Package List

The following code quality packages are used in this project:

| Package | Version | Description |
|---------|---------|-------------|
| larastan/larastan | ^3.2.0 | Laravel-specific static analysis |
| nunomaduro/collision | ^8.6 | Better error reporting |
| laravel/pint | ^1.18 | Laravel opinionated code style fixer |
| rector/rector | ^2.0.11 | Automated code refactoring |
| driftingly/rector-laravel | ^2.0.2 | Laravel-specific Rector rules |
| rector/type-perfect | ^2.0.2 | Type-related refactoring |
| php-parallel-lint/php-parallel-lint | 1.4.0 | Fast PHP syntax checking |
| nunomaduro/phpinsights | ^2.13.1 | PHP quality metrics |
| spatie/laravel-blade-comments | ^1.3.1 | Blade comment analysis |
| roave/security-advisories | dev-latest | Security vulnerability prevention |

## 2. Configuration Files

Code quality is managed through the following configuration files:

- `phpstan.neon` - PHPStan configuration
- `pint.json` - Laravel Pint configuration
- `rector.php` - Rector configuration

## 3. Running Quality Checks

Code quality checks can be run using the composer scripts:
# Code Quality Packages Documentation

## 1. Overview

This section documents all code quality packages available in the project's require-dev dependencies. These tools help maintain high code standards, detect potential issues, and enforce consistency across the codebase.

## 2. Static Analysis Tools

### 2.1. PHP Static Analysis
- [PHPStan](020-phpstan/000-index.md) - Advanced static analysis with comprehensive documentation:
  - [Configuration](020-phpstan/010-configuration.md)
  - [Baseline Management](020-phpstan/020-baseline-management.md)
  - [Daily Workflow](020-phpstan/030-workflow.md)
  - [Command Reference](020-phpstan/040-commands.md)
  - [Custom Rules](020-phpstan/050-custom-rules.md)
  - [Troubleshooting](020-phpstan/060-troubleshooting.md)

### 2.2. Architecture Analysis
- [PHP Mess Detector](030-phpmd/000-index.md) - Detects code smells and potential issues
- [PHP Insights](040-php-insights/000-index.md) - Code quality and architecture analysis

## 3. Code Style Tools

### 3.1. Formatting and Standards
- [Laravel Pint](050-pint/000-index.md) - Opinionated PHP code style fixer
- [PHP_CodeSniffer](060-phpcs/000-index.md) - Detects violations of coding standards

### 3.2. Code Style Configuration
- [EditorConfig](070-editorconfig/000-index.md) - Consistent coding styles across editors
- [Prettier](080-prettier/000-index.md) - Code formatter for non-PHP files

## 4. Security Analysis

### 4.1. Vulnerability Scanning
- [Composer Audit](090-composer-audit/000-index.md) - Checks for vulnerable dependencies
- [PHP Security Checker](100-security-checker/000-index.md) - Checks for known security issues

### 4.2. Code Security Analysis
- [PHP Security Analyzer](110-php-security-analyzer/000-index.md) - Analyzes code for security vulnerabilities

## 5. Automation and Integration

### 5.1. Pre-commit Hooks
- [Husky](120-husky/000-index.md) - Git hooks integration
- [Lint-staged](130-lint-staged/000-index.md) - Run linters on staged files

### 5.2. CI/CD Integration
- [GitHub Actions](140-github-actions/000-index.md) - Automated code quality checks
- [Quality Reports](150-quality-reports/000-index.md) - Generating and interpreting reports

## 6. Configuration Reference

- [Configuration Examples](../configs/code-quality/)
- [Project-Specific Rules](160-custom-rules/000-index.md)
- [Ignoring Issues](170-ignoring-issues/000-index.md)

## 7. Code Quality Strategy

Our code quality strategy follows a multi-layered approach:

1. **Static Analysis**: PHPStan/Larastan at level 8+ catches type errors and potential bugs
2. **Code Style**: Laravel Pint ensures consistent formatting and adherence to PSR-12
3. **Architecture Quality**: PHP Insights analyzes overall code architecture
4. **Security Scanning**: Regular vulnerability checks through Composer Audit
5. **Automation**: Pre-commit hooks and CI/CD pipelines enforce standards

All tools are configured for parallel execution where supported to minimize impact on development workflow.
```bash
// Run all quality checks
composer quality:check

// Lint code (check style)
composer test:lint

// Fix code style issues
composer lint

// Run static analysis
composer analyze

// Fix auto-fixable issues
composer analyze:fix

// Run security checks
composer test:security
```
