# PHP Parallel Lint

## 1. Overview

PHP Parallel Lint is a tool that checks PHP syntax in parallel, making it much faster than traditional linting tools. It's especially useful for large codebases where checking syntax sequentially would take too long.

### 1.1. Package Information

- **Package Name**: php-parallel-lint/php-parallel-lint
- **Version**: 1.4.0
- **GitHub**: [https://github.com/php-parallel-lint/PHP-Parallel-Lint](https://github.com/php-parallel-lint/PHP-Parallel-Lint)
- **Documentation**: [https://github.com/php-parallel-lint/PHP-Parallel-Lint/blob/master/README.md](https://github.com/php-parallel-lint/PHP-Parallel-Lint/blob/master/README.md)

## 2. Key Features

- Parallel syntax checking
- Much faster than sequential linting
- Support for PHP 5.3 to 8.4
- Colored output
- Custom file extensions
- Recursive directory scanning
- Exclude patterns
- Exit code for CI/CD integration

## 3. Installation

```bash
composer require --dev php-parallel-lint/php-parallel-lint
```

## 4. Usage

### 4.1. Basic Usage

Check syntax of all PHP files in the current directory:

```bash
./vendor/bin/parallel-lint .
```

### 4.2. Specific Directories

Check specific directories:

```bash
./vendor/bin/parallel-lint app tests
```

### 4.3. Excluding Directories

Exclude specific directories:

```bash
./vendor/bin/parallel-lint --exclude vendor --exclude storage .
```

### 4.4. Custom Extensions

Check files with custom extensions:

```bash
./vendor/bin/parallel-lint --extensions php,phpt,php7 .
```

### 4.5. No Colors

Disable colored output:

```bash
./vendor/bin/parallel-lint --no-colors .
```

### 4.6. JSON Output

Generate JSON output:

```bash
./vendor/bin/parallel-lint --json .
```

## 5. Composer Scripts

Add a script to your `composer.json`:

```json
"scripts": {
    "lint:syntax": "parallel-lint app tests"
}
```

Usage:

```bash
composer lint:syntax
```

## 6. Integration with Laravel 12 and PHP 8.4

PHP Parallel Lint is fully compatible with Laravel 12 and PHP 8.4. It can check syntax for all PHP versions from 5.3 to 8.4.

## 7. CI/CD Integration

### 7.1. GitHub Actions

```yaml
name: PHP Syntax Check

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  lint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Check PHP Syntax
        run: ./vendor/bin/parallel-lint --exclude vendor --exclude storage .
```

### 7.2. GitLab CI

```yaml
lint:
  stage: test
  script:
    - composer install --no-progress --no-scripts --no-interaction
    - ./vendor/bin/parallel-lint --exclude vendor --exclude storage .
```

## 8. Best Practices

1. **Run Before Other Checks**: Run syntax checking before other code quality tools
2. **Include in CI/CD**: Make syntax checking part of your CI/CD pipeline
3. **Exclude Vendor Directories**: Always exclude vendor and other third-party code
4. **Use with Other Tools**: Combine with PHPStan, Rector, and Pint for comprehensive quality checks

## 9. Performance Tips

### 9.1. Optimizing Performance

For large codebases:

1. Exclude unnecessary directories (vendor, storage, etc.)
2. Focus on directories with your code (app, tests, etc.)
3. Use parallel processing (enabled by default)

### 9.2. Caching

PHP Parallel Lint doesn't have built-in caching, but you can optimize CI/CD pipelines:

1. Only run on changed files in pre-commit hooks
2. Use CI/CD caching for vendor directory

## 10. Troubleshooting

### 10.1. Memory Issues

If you encounter memory issues:

```bash
php -d memory_limit=1G ./vendor/bin/parallel-lint .
```

### 10.2. Conflicts with Other Tools

PHP Parallel Lint focuses only on syntax checking, so it rarely conflicts with other tools. Use it alongside:

- PHPStan for static analysis
- Pint for code style
- Rector for automated refactoring
