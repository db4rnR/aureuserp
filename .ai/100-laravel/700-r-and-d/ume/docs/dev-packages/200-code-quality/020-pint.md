# Laravel Pint

## 1. Overview

Laravel Pint is an opinionated PHP code style fixer for Laravel projects. It's built on top of PHP-CS-Fixer and provides a zero-configuration experience with sensible defaults aligned with Laravel's coding style.

### 1.1. Package Information

- **Package Name**: laravel/pint
- **Version**: ^1.18
- **GitHub**: [https://github.com/laravel/pint](https://github.com/laravel/pint)
- **Documentation**: [https://laravel.com/docs/10.x/pint](https://laravel.com/docs/10.x/pint)

## 2. Key Features

- Zero-configuration by default
- Laravel-specific code style rules
- Customizable presets and rules
- Fast parallel execution
- Automatic fixing of style issues
- Integration with CI/CD pipelines
- Support for custom rulesets

## 3. Installation

```bash
composer require --dev laravel/pint
```

## 4. Configuration

### 4.1. Default Configuration

Laravel Pint works without any configuration, applying Laravel's coding standards by default.

### 4.2. Custom Configuration

Create a `pint.json` file in your project root to customize the configuration:

```json
{
    "preset": "laravel",
    "rules": {
        "array_syntax": {
            "syntax": "short"
        },
        "ordered_imports": {
            "sort_algorithm": "alpha"
        },
        "no_unused_imports": true
    }
}
```

### 4.3. Available Presets

Pint includes several presets:

- `laravel` (default): Laravel's coding standards
- `psr12`: PSR-12 coding standards
- `symfony`: Symfony's coding standards

### 4.4. Excluding Files

Exclude specific files or directories from formatting:

```json
{
    "exclude": [
        "config",
        "storage",
        "bootstrap/cache"
    ]
}
```

## 5. Usage

### 5.1. Basic Usage

Format all files in your project:

```bash
./vendor/bin/pint
```

### 5.2. Test Mode

Check for style issues without fixing them:

```bash
./vendor/bin/pint --test
```

### 5.3. Specific Directories or Files

Format specific directories or files:

```bash
./vendor/bin/pint app/Models
./vendor/bin/pint app/Http/Controllers/UserController.php
```

### 5.4. Verbose Output

Get detailed information about changes:

```bash
./vendor/bin/pint -v
```

## 6. Composer Scripts

We've added these helpful scripts to our `composer.json`:

```json
"scripts": {
    "format": [ "./vendor/bin/pint" ],
    "lint": [ "./vendor/bin/pint --test" ]
}
```

Usage:

```bash
# Format code
composer format

# Check code style without fixing
composer lint
```

## 7. Integration with Laravel 12 and PHP 8.4

Laravel Pint is fully compatible with Laravel 12 and PHP 8.4. It includes rules specifically designed for modern PHP features, including:

- PHP 8.4 syntax support
- Type declarations
- Constructor property promotion
- Attribute syntax
- Named arguments

## 8. CI/CD Integration

### 8.1. GitHub Actions

```yaml
name: Code Style

on:
  push:
    branches: [ main ]
  pull_request:
    branches: [ main ]

jobs:
  pint:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v3
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.4'
      - name: Install Dependencies
        run: composer install -q --no-ansi --no-interaction --no-scripts --no-progress
      - name: Check Code Style
        run: composer lint
```

### 8.2. GitLab CI

```yaml
pint:
  stage: test
  script:
    - composer install --no-progress --no-scripts --no-interaction
    - composer lint
```

## 9. Git Hooks

### 9.1. Using Husky

Set up a pre-commit hook with Husky:

```json
// package.json
{
  "husky": {
    "hooks": {
      "pre-commit": "composer lint"
    }
  }
}
```

### 9.2. Manual Git Hook

Create a pre-commit hook manually:

```bash
#!/bin/sh
# .git/hooks/pre-commit
FILES=$(git diff --cached --name-only --diff-filter=ACMR "*.php" | sed 's| |\\ |g')
[ -z "$FILES" ] && exit 0

composer lint $FILES
```

## 10. Best Practices

1. **Run Before Committing**: Format code before committing changes
2. **Use in CI/CD**: Enforce code style in your CI/CD pipeline
3. **Consistent Configuration**: Use the same configuration across all projects
4. **Incremental Adoption**: For existing projects, apply Pint gradually
5. **Team Agreement**: Ensure the team agrees on code style rules

## 11. Common Issues and Troubleshooting

### 11.1. Conflicts with Other Tools

If Pint conflicts with other code style tools:

1. Remove other tools (PHP-CS-Fixer, PHP_CodeSniffer)
2. Ensure consistent configuration between tools if you must use multiple

### 11.2. Performance Issues

For large codebases:

1. Format specific directories instead of the entire project
2. Use parallel processing (enabled by default)
3. Exclude unnecessary directories
