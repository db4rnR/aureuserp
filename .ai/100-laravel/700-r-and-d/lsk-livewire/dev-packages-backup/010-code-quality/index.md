# Code Quality Tools

This section documents the code quality tools used in our Laravel 12 project with PHP 8.4.

## Tools Overview

| Tool | Purpose | Configuration |
|------|---------|--------------|
| [PHPStan](020-phpstan/000-index.md) | Static analysis | phpstan.neon.dist |
| [Laravel Pint](030-pint/index.md) | Code style formatting | pint.json |
| [PHP Insights](040-phpinsights/index.md) | Code quality metrics | phpinsights.php |
| [Rector](050-rector/index.md) | Automated refactoring | rector.php |

## Composer Commands

We provide a comprehensive set of composer scripts for code quality:

```bash
# Run all quality checks
composer quality:check

# Automatically fix issues
composer quality:fix

# Generate quality report
composer quality:report
```

## CI Integration

Our quality tools are integrated into our CI pipeline:

```yaml
# Example GitHub Actions workflow
quality:
  runs-on: ubuntu-latest
  steps:
    - uses: actions/checkout@v3
    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.4'
    - name: Check code quality
      run: composer quality:check
```

## Integration in Development Workflow

For the best development experience, we recommend:

1. Running `composer quality:fix` before committing
2. Configuring your IDE to use these tools
3. Installing pre-commit hooks for automated checks

## Learn More

For detailed information on each tool, see the specific documentation sections:

* [PHPStan Documentation](020-phpstan/000-index.md)
* [Laravel Pint Documentation](030-pint/index.md)
* [PHP Insights Documentation](040-phpinsights/index.md)
* [Rector Documentation](050-rector/index.md)
