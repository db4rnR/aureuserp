# Code Quality and Testing

This directory contains documentation related to code quality and testing in the AureusERP project.

## Contents

1. [Code Quality](010-code-quality.md) - Setting up and using code quality tools
2. [Testing with Pest](020-testing.md) - Comprehensive testing setup with Pest
3. [CI/CD and Pre-commit Hooks](030-ci-cd.md) - GitHub Actions and pre-commit hooks

## Overview

AureusERP maintains high code quality standards through a combination of automated tools, testing frameworks, and continuous integration practices. This documentation provides comprehensive, step-by-step instructions on configuring these tools and making them available as composer scripts.

### Code Quality Tools

AureusERP uses several tools to ensure code quality:

- **Laravel Pint** - Code style fixer based on PHP-CS-Fixer
- **PHPStan** - Static analysis tool
- **Rector** - Automated code upgrades and refactoring
- **PHP Insights** - Code quality and architecture analysis

### Testing Framework

AureusERP uses Pest for testing:

- **Unit Tests** - Testing individual components
- **Feature Tests** - Testing application features
- **Integration Tests** - Testing component interactions
- **Browser Tests** - Testing with Laravel Dusk

### CI/CD and Pre-commit Hooks

AureusERP uses GitHub Actions and pre-commit hooks for continuous integration and deployment:

- **GitHub Actions** - Automated workflows for testing and deployment
- **Pre-commit Hooks** - Ensuring code quality before commits
- **Continuous Deployment** - Automated deployment with Deployer

## Quick Start

To get started with code quality and testing in AureusERP:

1. Install the required dependencies:

```bash
composer install
```

2. Run code quality checks:

```bash
composer analyze
```

3. Run tests:

```bash
composer test
```

4. Fix code quality issues:

```bash
composer fix
```

5. Set up pre-commit hooks:

```bash
pre-commit install
```

## Composer Scripts

AureusERP provides several composer scripts for code quality and testing:

```json
{
    "scripts": {
        "pint": "pint",
        "pint:test": "pint --test",
        "phpstan": "phpstan analyse",
        "rector": "rector process",
        "rector:dry-run": "rector process --dry-run",
        "insights": "phpinsights",
        "analyze": [
            "@pint:test",
            "@phpstan",
            "@rector:dry-run",
            "@insights"
        ],
        "fix": [
            "@pint",
            "@rector"
        ],
        "test": "pest",
        "test:coverage": "pest --coverage",
        "test:coverage-html": "pest --coverage --coverage-html=reports/coverage",
        "test:parallel": "pest --parallel",
        "test:type-coverage": "pest --type-coverage",
        "test:arch": "pest --group=arch",
        "test:stress": "pest --group=stress",
        "test:unit": "pest --group=unit",
        "test:feature": "pest --group=feature",
        "test:integration": "pest --group=integration"
    }
}
```
