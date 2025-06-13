# PHP Insights

## 1. Overview

PHP Insights is a quality analysis tool for PHP projects that provides metrics and insights about code quality, architecture, complexity, and more. It's designed to help improve code quality and maintainability.

### 1.1. Package Information

- **Package Name**: nunomaduro/phpinsights
- **Version**: ^2.13.1
- **GitHub**: [https://github.com/nunomaduro/phpinsights](https://github.com/nunomaduro/phpinsights)
- **Documentation**: [https://phpinsights.com/](https://phpinsights.com/)

## 2. Key Features

- Code quality analysis
- Architecture analysis
- Complexity metrics
- Style checking
- Security analysis
- Custom insights
- Laravel-specific analysis
- Configurable thresholds
- Beautiful console output
- CI/CD integration

## 3. Installation

```bash
composer require --dev nunomaduro/phpinsights
```

### 3.1. Laravel Setup

Publish the configuration file:

```bash
php artisan vendor:publish --provider="NunoMaduro\PhpInsights\Application\Adapters\Laravel\InsightsServiceProvider"
```

## 4. Configuration

### 4.1. Configuration File

The configuration file is located at `config/insights.php`:

```php
<?php

declare(strict_types=1);

return [
    'preset' => 'laravel',
    'ide' => 'phpstorm',
    'exclude' => [
        'app/Providers',
        'storage',
        'bootstrap',
    ],
    'add' => [
        // Additional rules
    ],
    'remove' => [
        // Rules to remove
        \SlevomatCodingStandard\Sniffs\TypeHints\DeclareStrictTypesSniff::class,
        \NunoMaduro\PhpInsights\Domain\Insights\ForbiddenNormalClasses::class,
    ],
    'config' => [
        // Rule configurations
    ],
    'requirements' => [
        'min-quality' => 80,
        'min-complexity' => 80,
        'min-architecture' => 80,
        'min-style' => 80,
        'disable-security-check' => false,
    ],
];
```

### 4.2. Presets

PHP Insights includes several presets:

- `laravel` (default for Laravel projects)
- `symfony`
- `default`

### 4.3. Excluding Files

Exclude specific files or directories from analysis:

```php
'exclude' => [
    'app/Providers',
    'storage',
    'bootstrap',
    'database/migrations',
    'app/Http/Middleware/Authenticate.php',
],
```

### 4.4. Requirements

Set minimum thresholds for different metrics:

```php
'requirements' => [
    'min-quality' => 85,
    'min-complexity' => 85,
    'min-architecture' => 85,
    'min-style' => 85,
    'disable-security-check' => false,
],
```

## 5. Usage

### 5.1. Basic Usage

Run PHP Insights:

```bash
php artisan insights
```

### 5.2. Specific Analysis

Analyze specific directories or files:

```bash
php artisan insights app/Models
```

### 5.3. Detailed Output

Get detailed information about issues:

```bash
php artisan insights --verbose
```

### 5.4. Fix Issues

Automatically fix some issues:

```bash
php artisan insights --fix
```

### 5.5. Custom Configuration

Use a custom configuration file:

```bash
php artisan insights --config-path=custom-insights.php
```

## 6. Integration with Laravel 12 and PHP 8.4

PHP Insights is compatible with Laravel 12 and PHP 8.4. It includes rules specifically designed for modern PHP features, including:

- PHP 8.4 syntax support
- Type declarations
- Constructor property promotion
- Attribute syntax
- Named arguments

## 7. CI/CD Integration

### 7.1. GitHub Actions

```yaml
name: Code Quality

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  insights:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Run PHP Insights
        run: php artisan insights --no-interaction --min-quality=85 --min-complexity=85 --min-architecture=85 --min-style=85
```

### 7.2. GitLab CI

```yaml
insights:
  stage: test
  script:
    - composer install --no-progress --no-scripts --no-interaction
    - php artisan insights --no-interaction --min-quality=85 --min-complexity=85 --min-architecture=85 --min-style=85
```

## 8. Best Practices

1. **Start with Lower Thresholds**: Begin with lower thresholds and gradually increase them
2. **Focus on One Category**: Address issues in one category at a time
3. **Exclude Legacy Code**: Exclude legacy code that won't be refactored
4. **Integrate with CI/CD**: Run PHP Insights in your CI/CD pipeline
5. **Regular Analysis**: Run PHP Insights regularly to track progress

## 9. Common Issues and Troubleshooting

### 9.1. Memory Issues

If you encounter memory issues:

```bash
php -d memory_limit=1G artisan insights
```

### 9.2. Conflicts with Other Tools

If PHP Insights conflicts with other code quality tools:

1. Ensure consistent configuration between tools
2. Exclude specific rules that conflict

### 9.3. Performance Issues

For large codebases:

1. Analyze specific directories instead of the entire project
2. Exclude unnecessary directories
3. Run with `--no-interaction` flag for faster execution
